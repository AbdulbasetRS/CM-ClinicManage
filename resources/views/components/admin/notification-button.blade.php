<!-- 🔔 Toast Container -->
<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<!-- 🔔 Notifications Dropdown -->
<div class="dropdown js-notifications">
    <button class="btn border position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary"
            style="top: 2px !important; left: 75% !important;">0</span>
    </button>

    <div id="notificationsDropdown" class="dropdown-menu dropdown-menu-end p-0 shadow" style="width: 320px;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-bold">الإشعارات</span>
            <button type="button" class="btn btn-sm text-muted close-dropdown" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div id="notificationsList" class="list-group list-group-flush text-start"
            style="max-height: 350px; overflow-y: auto;">
            <div id="emptyNotifications" class="text-center text-muted py-4" style="display: none;">لا توجد إشعارات
            </div>
{{-- 
            <a href="{{ route('admin.notify.show','8f4994c5-493d-49b0-ab38-27002634a6de') }}"
                class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-3 position-relative unread-notification"
                style="background-color: rgba(0,128,0,0.1);">

                <!-- Icon / Image -->
                <div class="flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle bg-light"
                    style="width: 45px; height: 45px;">
                    <i class="fas fa-bell text-primary fs-5"></i>
                    <!-- لو عايز صورة بدل الأيقونة:
                        <img src="IMAGE_URL.jpg" class="img-fluid" style="object-fit: cover;" />
                        -->
                </div>

                <!-- Content -->
                <div class="flex-grow-1 text-truncate">
                    <div class="fw-bold text-truncate">عنوان الإشعار</div>
                    <div class="text-muted small text-truncate">ده نص تجريبي للإشعار</div>
                    <div class="text-secondary small mt-1"><i class="far fa-clock"></i> الآن</div>
                </div>

                <!-- Mark as Read Button -->
                <button class="btn btn-sm btn-outline-success position-absolute end-0 bottom-0 m-2">
                    Mark as read
                </button>

            </a> --}}


            <!-- Spinner -->
            <div id="notificationsLoader" class="text-center py-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center border-top py-2 d-flex justify-content-between align-items-center px-2">
            <a href="{{ route('admin.notifications.index') }}" class="small text-decoration-none text-primary">
                عرض كل الإشعارات
            </a>
            <button id="markAllNotificationsAsReadBtn" class="btn btn-sm btn-outline-primary">
                Mark All as Read
            </button>
        </div>
    </div>
</div>

<script>
    const notificationsRoute = "{{ route('admin.notifications.index') }}";
</script>

<script src="{{ asset('assets/js/notifications.js') }}"></script>