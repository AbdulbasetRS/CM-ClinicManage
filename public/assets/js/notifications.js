/**
 * =====================================================
 * 🔔 Notifications & Toast System (With Read Buttons)
 * Author: Abdulbaset R. Sayed
 * =====================================================
 */

// =======================
// 🔹 Selectors
// =======================
const toastContainer = document.getElementById("toastContainer");
const list = document.getElementById("notificationsList");
const loader = document.getElementById("notificationsLoader");
const badge = document.querySelector(".btn .badge");
const emptyMsg = document.getElementById("emptyNotifications");
const markAllBtn = document.getElementById("markAllNotificationsAsReadBtn"); // زر Mark All Read

// =======================
// 🔹 Global State
// =======================
let currentPage = 1;
let loading = false;
let allLoaded = false;

// =======================
// 🔹 Helper: Update Badge
// =======================
function updateUnreadBadge(serverUnread = null) {
    let unread = serverUnread !== null
        ? serverUnread
        : list.querySelectorAll(".unread-notification").length;

    badge.textContent = unread;
    badge.classList.toggle("bg-danger", unread > 0);
    badge.classList.toggle("bg-secondary", unread === 0);
}

// =======================
// 🔹 Helper: Create Notification Item
// =======================
function buildNotificationItem(notification) {
    const a = document.createElement("a");
    a.href = notification.notification_url ? notification.notification_url : "#";
    a.className = "list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-3 position-relative";

    // حالة الإشعار غير مقروء
    if (!notification.read_at) {
        a.classList.add("unread-notification");
        a.style.backgroundColor = "rgba(0,128,0,0.1)";
    }

    // أيقونة أو صورة
    const iconDiv = document.createElement("div");
    iconDiv.className = "flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle bg-light";
    iconDiv.style.width = "45px";
    iconDiv.style.height = "45px";

    if (notification.image_url) {
        const img = document.createElement("img");
        img.src = notification.image_url;
        img.alt = "Notification Image";
        img.className = "img-fluid";
        img.style.objectFit = "cover";
        iconDiv.appendChild(img);
    } else {
        const i = document.createElement("i");
        i.className = notification.icon_class || "fas fa-bell text-primary";
        i.classList.add("fs-5");
        iconDiv.appendChild(i);
    }

    // محتوى الإشعار
    const contentDiv = document.createElement("div");
    contentDiv.className = "flex-grow-1 text-truncate";
    contentDiv.innerHTML = `
        <div class="fw-bold text-truncate">${notification.title}</div>
        <div class="text-muted small text-truncate">${notification.content}</div>
        <div class="text-secondary small mt-1"><i class="far fa-clock"></i> ${notification.time}</div>
    `;

    a.appendChild(iconDiv);
    a.appendChild(contentDiv);

    // إضافة زر Mark as Read لو الإشعار غير مقروء
    if (!notification.read_at) {
        const readBtn = document.createElement("button");
        readBtn.className = "btn btn-sm btn-outline-success position-absolute bottom-0 m-2";
        document.documentElement.dir === "rtl"
            ? readBtn.classList.add("start-0")
            : readBtn.classList.add("end-0");
        readBtn.textContent = "Mark as read";

        readBtn.addEventListener("click", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            try {


                const res = await fetch(`${notificationsRoute}/${notification.id}/mark-read`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await res.json();

                a.classList.remove("unread-notification");
                a.style.backgroundColor = "transparent";
                readBtn.remove();

                // تحديث العدد من السيرفر
                updateUnreadBadge(data.unread_count);
            } catch (err) {
                console.error(err);
                alert("حدث خطأ أثناء تحديث الإشعار.");
            }
        });

        a.appendChild(readBtn);
    }

    return a;
}

// =======================
// 🔹 Fetch Notifications (with pagination)
// =======================
async function fetchNotificationsPage() {
    if (loading || allLoaded) return;

    loading = true;
    loader.style.display = "block";

    try {
        const res = await fetch(`${notificationsRoute}?page=${currentPage}&per_page=5`);
        if (!res.ok) throw new Error("Network error");
        const data = await res.json();

        // 🟢 حدّث الـ badge من الداتا بيز أول ما يوصلك الـ response
        updateUnreadBadge(data.meta?.unread_count ?? null);

        if (!data.data.length) {
            allLoaded = true;
            return;
        }

        data.data.forEach(n => {
            const el = buildNotificationItem({
                title: n.data.title,
                content: n.data.body,
                time: new Date(n.created_at).toLocaleString(),
                icon_class: n.icon_class,
                image_url: n.image_url,
                notification_url: n.notification_url,
                read_at: n.read_at,
                id: n.id,
            });
            list.insertBefore(el, loader);
        });

        currentPage++;

    } catch (err) {
        console.error(err);
    } finally {
        loading = false;
        loader.style.display = "none";
    }
}

// =======================
// 🔹 Mark All Notifications as Read
// =======================
async function markAllNotificationsAsRead() {
    try {
        const res = await fetch(`${notificationsRoute}/mark-read`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
        });
        if (!res.ok) throw new Error("Failed");

        // تحديث محليًا كل الإشعارات كمقروءة
        list.querySelectorAll(".unread-notification").forEach(el => {
            el.classList.remove("unread-notification");
            el.style.backgroundColor = "transparent";
        });

        // 🔥 إزالة كل أزرار mark as read
        list.querySelectorAll(".btn-outline-success").forEach(btn => btn.remove());

        updateUnreadBadge(0);
    } catch (err) {
        console.error(err);
        alert("حدث خطأ أثناء تحديث كل الإشعارات.");
    }
}

// =======================
// 🔹 Event Listeners
// =======================
document.addEventListener("DOMContentLoaded", () => {
    fetchNotificationsPage();

    if (markAllBtn) {
        markAllBtn.addEventListener("click", (e) => {
            e.preventDefault();
            markAllNotificationsAsRead();
        });
    }

    const dropdown = document.querySelector(".js-notifications");
    const toggle = dropdown.querySelector(".btn");
    const menu = dropdown.querySelector(".dropdown-menu");

    menu.addEventListener("click", (e) => {
        if (!e.target.closest(".close-dropdown")) e.stopPropagation();
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
            if (bsDropdown) bsDropdown.hide();
        }
    });

    list.addEventListener("scroll", () => {
        const nearBottom = list.scrollTop + list.clientHeight >= list.scrollHeight - 20;
        if (nearBottom) fetchNotificationsPage();
    });
});

// =======================
// 🔹 Show Notification Toast
// =======================
function displayNotificationToast(title, content, time = "الآن", link = "#", iconClass = "fas fa-bell text-primary", duration = 4000, soundUrl = null) {
    if (!toastContainer) return;

    const toast = document.createElement("div");
    toast.className = "toast align-items-start border-0 shadow-sm mb-2";
    toast.style.width = "320px";
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    toast.innerHTML = `
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start">
                <a href="${link}" class="d-flex text-decoration-none text-dark p-1 flex-fill">
                    <div class="align-content-center p-4">
                        <i class="${iconClass} fa-lg"></i>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-1 mt-1 fw-bold text-muted text-truncate" style="max-width: 220px;">${title}</p>
                        <small class="text-muted d-block text-truncate">${content}</small>
                        <small class="text-muted d-block"><i class="far fa-clock"></i> ${time}</small>
                    </div>
                </a>
                <button type="button" class="btn-close btn-close-white m-2" aria-label="Close"></button>
            </div>
            <div class="progress mt-2" style="height: 3px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%; transition: width linear ${duration}ms;"></div>
            </div>
        </div>
    `;

    toastContainer.appendChild(toast);

    // تشغيل الصوت
    const audio = new Audio(soundUrl || `${window.location.origin}/assets/sounds/default-notify.mp3`);
    audio.play().catch(() => { });

    // عرض الـ toast
    const bsToast = new bootstrap.Toast(toast, { delay: duration });
    bsToast.show();

    // تحريك progress
    const progress = toast.querySelector(".progress-bar");
    setTimeout(() => (progress.style.width = "0%"), 50);

    // زر الإغلاق
    toast.querySelector(".btn-close").addEventListener("click", () => bsToast.hide());
    toast.addEventListener("hidden.bs.toast", () => toast.remove());
}

// =======================
// 🔹 Add notification to list + toast
// =======================
function pushNotificationToList(id, title, content, time, notification_url, icon = "fas fa-bell text-primary", image = null, sound = null, unreadCountFromServer = null) {
    created_at = new Date(time).toLocaleString();
    const el = buildNotificationItem({
        title,
        content,
        time: created_at,
        icon_class: icon,
        image_url: image,
        notification_url: notification_url,
        id: id
    });
    list.prepend(el);

    // لو وصلنا عدد غير المقروءة من السيرفر استخدمه
    updateUnreadBadge(unreadCountFromServer);

    // إظهار الـ toast
    displayNotificationToast(title, content, created_at, notification_url, icon, 4000, sound);
}

