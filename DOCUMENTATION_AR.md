# وثائق نظام إدارة العيادة - CM-ClinicManage

## 📋 جدول المحتويات

-   [نظرة عامة](#نظرة-عامة)
-   [المتطلبات](#المتطلبات)
-   [التثبيت](#التثبيت)
-   [الميزات الرئيسية](#الميزات-الرئيسية)
-   [البنية المعمارية](#البنية-المعمارية)
-   [قاعدة البيانات](#قاعدة-البيانات)
-   [نظام المصادقة](#نظام-المصادقة)
-   [الوحدات الرئيسية](#الوحدات-الرئيسية)
-   [نظام الإشعارات](#نظام-الإشعارات)
-   [نظام الملفات](#نظام-الملفات)
-   [التعريب](#التعريب)
-   [الأمان](#الأمان)
-   [الاختبار](#الاختبار)
-   [استكشاف الأخطاء](#استكشاف-الأخطاء)

---

## 🎯 نظرة عامة

**CM-ClinicManage** هو نظام متكامل لإدارة العيادات الطبية مبني على إطار عمل Laravel 12. يوفر النظام حلاً شاملاً لإدارة المرضى، المواعيد، الزيارات، الفواتير، والخدمات الطبية مع واجهة مستخدم عصرية باستخدام Bootstrap 5.

### 🎨 التقنيات المستخدمة

-   **Backend Framework**: Laravel 12.x
-   **Frontend**: Blade Templates + Bootstrap 5 + Bootstrap Icons
-   **Database**: MySQL/SQLite
-   **Authentication**: نظام مصادقة مخصص (بدون Breeze أو Jetstream)
-   **Real-time**: Pusher (للإشعارات الفورية)
-   **Localization**: mcamara/laravel-localization
-   **DataTables**: Yajra DataTables
-   **2FA**: Google Authenticator (pragmarx/google2fa)
-   **Social Login**: Google, GitHub (Laravel Socialite)

---

## 💻 المتطلبات

### متطلبات النظام

-   **PHP**: >= 8.2
-   **Composer**: أحدث إصدار
-   **MySQL**: >= 5.7 أو **SQLite**: >= 3.8
-   **Node.js**: >= 16.x (للأدوات الأمامية)
-   **NPM**: >= 8.x

### الامتدادات المطلوبة في PHP

```
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD Library (لمعالجة الصور)
```

---

## 🚀 التثبيت

### 1. استنساخ المشروع

```bash
git clone https://github.com/AbdulbasetRS/CM-ClinicManage.git
cd CM-ClinicManage
```

### 2. تثبيت التبعيات

```bash
composer install
npm install
```

### 3. إعداد البيئة

```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات

قم بتعديل ملف `.env` وإضافة بيانات الاتصال بقاعدة البيانات:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_manage
DB_USERNAME=root
DB_PASSWORD=
```

### 5. تشغيل الترحيلات

```bash
php artisan migrate
```

### 6. ربط مجلد التخزين

```bash
php artisan storage:link
```

### 7. تشغيل المشروع

```bash
# تشغيل الخادم
php artisan serve

# تشغيل قوائم الانتظار (في نافذة طرفية منفصلة)
php artisan queue:work

# تشغيل Vite للتطوير (في نافذة طرفية ثالثة)
npm run dev
```

**أو استخدم أمر واحد:**

```bash
composer run dev
```

---

## ✨ الميزات الرئيسية

### 🔐 نظام المصادقة الشامل

-   **تسجيل المستخدمين الجدد** مع التحقق من البريد الإلكتروني
-   **تسجيل الدخول** بالبريد الإلكتروني أو اسم المستخدم
-   **استعادة كلمة المرور** عبر البريد الإلكتروني
-   **التحقق من البريد الإلكتروني** الإلزامي
-   **تسجيل الدخول الاجتماعي** (Google, GitHub)
-   **المصادقة الثنائية (2FA)** باستخدام Google Authenticator

### 👥 إدارة المستخدمين

-   نظام صلاحيات متقدم (Admin, Doctor, Patient)
-   صفحات شخصية كاملة للمستخدمين
-   إدارة الصور الشخصية (Avatars)
-   تغيير كلمة المرور
-   إعدادات الأمان الشخصية
-   تخزين منظم للملفات حسب معرف المستخدم

### 📅 إدارة المواعيد (Appointments)

-   جدولة المواعيد الطبية
-   معلومات تفصيلية (التاريخ، الوقت، نوع الموعد، الحالة)
-   ربط المواعيد بالمرضى والأطباء
-   حالات متعددة (مجدول، مكتمل، ملغي)
-   إشعارات تلقائية للمواعيد القادمة

### 🏥 إدارة الزيارات (Visits)

-   تسجيل الزيارات الطبية
-   ربط الزيارات بالمواعيد
-   إضافة التشخيص والملاحظات
-   رفع المرفقات الطبية (صور، تقارير، أشعة)
-   تتبع حالة الزيارة (قيد الانتظار، جارية، مكتملة)

### 💰 نظام الفوترة (Invoices)

-   إنشاء الفواتير للمرضى
-   ربط الفواتير بالزيارات
-   إضافة بنود الفاتورة (InvoiceItems)
-   حساب تلقائي للمجاميع
-   حالات الدفع (مدفوع، غير مدفوع، مدفوع جزئياً)
-   طباعة الفواتير (Print View)
-   إحصائيات الفواتير

### 🩺 إدارة الخدمات الطبية (Services)

-   تعريف الخدمات الطبية المتاحة
-   تسعير الخدمات
-   ربط الخدمات بالفواتير

### 📎 نظام المرفقات

-   رفع الملفات الطبية
-   معاينة الملفات
-   تنظيم تلقائي حسب نوع المرفق
-   حذف وتحديث المرفقات

### 🔔 نظام الإشعارات

-   إشعارات فورية (Real-time) باستخدام Pusher
-   إشعارات قاعدة البيانات
-   مركز إشعارات في الواجهة
-   تنبيهات للمواعيد والزيارات والفواتير

### 🌐 الدعم متعدد اللغات

-   دعم اللغة العربية والإنجليزية
-   تبديل سهل بين اللغات
-   دعم RTL للعربية
-   ترجمة شاملة لجميع النصوص

---

## 🏗️ البنية المعمارية

### هيكل المجلدات

```
CM-ClinicManage/
├── app/
│   ├── Enums/                    # التعدادات (Status, Roles, etc.)
│   │   ├── AppointmentStatus.php
│   │   ├── InvoicePaymentStatus.php
│   │   ├── Role.php
│   │   └── VisitStatus.php
│   ├── Events/                   # الأحداث
│   ├── Exceptions/               # معالجة الاستثناءات المخصصة
│   ├── Helpers/                  # الدوال المساعدة
│   │   ├── PathHelper.php        # مساعد إدارة المسارات
│   │   ├── ImageHelper.php       # مساعد معالجة الصور
│   │   └── NotificationHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # كونترولرات لوحة الإدارة
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── AppointmentController.php
│   │   │   │   ├── VisitController.php
│   │   │   │   ├── InvoiceController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── TwoFactorController.php
│   │   │   │   └── NotificationController.php
│   │   │   ├── Auth/
│   │   │   │   └── GoogleController.php
│   │   │   └── Frontend/
│   │   │       └── WelcomeController.php
│   │   ├── Middleware/           # الميدل وير المخصص
│   │   └── Requests/             # طلبات التحقق المخصصة
│   ├── Models/                   # نماذج Eloquent
│   │   ├── User.php
│   │   ├── Profile.php
│   │   ├── UserSettings.php
│   │   ├── Appointment.php
│   │   ├── Visit.php
│   │   ├── Invoice.php
│   │   ├── InvoiceItem.php
│   │   ├── Service.php
│   │   └── Attachment.php
│   ├── Notifications/            # إشعارات Laravel
│   ├── Observers/                # مراقبي النماذج
│   ├── Providers/                # مزودي الخدمات
│   ├── Services/                 # طبقة الخدمات
│   │   ├── Auth/
│   │   │   ├── LoginService.php
│   │   │   └── RegisterService.php
│   │   ├── Google2FAService.php
│   │   └── NotificationService.php
│   └── View/                     # مكونات Blade
├── config/                       # ملفات التكوين
├── database/
│   ├── factories/                # مصانع البيانات
│   ├── migrations/               # ترحيلات قاعدة البيانات
│   └── seeders/                  # بذور البيانات
├── lang/                         # ملفات الترجمة
│   ├── ar/                       # اللغة العربية
│   │   └── admin.php
│   └── en/                       # اللغة الإنجليزية
│       └── admin.php
├── public/                       # الملفات العامة
│   ├── assets/                   # الأصول (CSS, JS, Images)
│   └── storage/                  # رابط التخزين
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/                # واجهات لوحة الإدارة
│       │   ├── appointments/
│       │   ├── visits/
│       │   ├── invoices/
│       │   ├── services/
│       │   ├── users/
│       │   ├── two-factor/
│       │   └── notifications/
│       ├── auth/                 # واجهات المصادقة
│       ├── components/           # مكونات Blade
│       ├── emails/               # قوالب البريد الإلكتروني
│       ├── errors/               # صفحات الأخطاء
│       └── frontend/             # الواجهة الأمامية
├── routes/
│   ├── web.php                   # مسارات الويب العامة
│   ├── admin.php                 # مسارات لوحة الإدارة
│   ├── channels.php              # قنوات البث
│   └── console.php               # أوامر Artisan
├── storage/                      # ملفات التخزين
│   ├── app/
│   │   ├── public/
│   │   │   └── users/            # ملفات المستخدمين
│   │   │       └── {user_id}/
│   │   │           ├── avatars/
│   │   │           └── documents/
│   │   └── private/
│   ├── framework/
│   └── logs/
└── tests/                        # الاختبارات
```

---

## 🗄️ قاعدة البيانات

### الجداول الرئيسية

#### 1. جدول المستخدمين (users)

```sql
- id: bigint (PK)
- name: string
- username: string (unique)
- email: string (unique)
- email_verified_at: timestamp (nullable)
- password: string
- role: enum ('admin', 'doctor', 'patient')
- is_active: boolean (default: true)
- remember_token: string (nullable)
- timestamps
```

#### 2. جدول الملفات الشخصية (profiles)

```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id)
- slug: string (unique)
- full_name: string
- phone: string (nullable)
- address: text (nullable)
- birth_date: date (nullable)
- gender: enum ('male', 'female') (nullable)
- avatar: string (nullable)
- bio: text (nullable)
- timestamps
```

#### 3. جدول إعدادات المستخدم (user_settings)

```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id, unique)
- enable_two_factor: boolean (default: false)
- google2fa_secret: string (nullable)
- notification_preferences: json (nullable)
- timestamps
```

#### 4. جدول مزودي المصادقة (auth_providers)

```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id)
- provider: string ('google', 'github')
- provider_id: string
- avatar: string (nullable)
- timestamps
```

#### 5. جدول المواعيد (appointments)

```sql
- id: bigint (PK)
- patient_id: bigint (FK → users.id)
- doctor_id: bigint (FK → users.id, nullable)
- appointment_date: date
- appointment_time: time
- appointment_type: string
- status: enum ('scheduled', 'completed', 'cancelled')
- notes: text (nullable)
- timestamps
```

#### 6. جدول الزيارات (visits)

```sql
- id: bigint (PK)
- appointment_id: bigint (FK → appointments.id, nullable)
- patient_id: bigint (FK → users.id)
- doctor_id: bigint (FK → users.id)
- visit_date: date
- chief_complaint: text
- diagnosis: text (nullable)
- treatment: text (nullable)
- notes: text (nullable)
- status: enum ('pending', 'in_progress', 'completed')
- timestamps
```

#### 7. جدول الفواتير (invoices)

```sql
- id: bigint (PK)
- invoice_number: string (unique)
- user_id: bigint (FK → users.id)
- visit_id: bigint (FK → visits.id, nullable)
- total_amount: decimal(10,2)
- paid_amount: decimal(10,2) (default: 0)
- payment_status: enum ('unpaid', 'partially_paid', 'paid')
- issue_date: date
- due_date: date (nullable)
- notes: text (nullable)
- timestamps
```

#### 8. جدول بنود الفاتورة (invoice_items)

```sql
- id: bigint (PK)
- invoice_id: bigint (FK → invoices.id)
- service_id: bigint (FK → services.id, nullable)
- description: string
- quantity: integer
- unit_price: decimal(10,2)
- total_price: decimal(10,2)
- timestamps
```

#### 9. جدول الخدمات (services)

```sql
- id: bigint (PK)
- name: string
- description: text (nullable)
- price: decimal(10,2)
- is_active: boolean (default: true)
- timestamps
```

#### 10. جدول المرفقات (attachments)

```sql
- id: bigint (PK)
- attachable_type: string (polymorphic)
- attachable_id: bigint (polymorphic)
- file_name: string
- file_path: string
- file_type: string
- file_size: bigint
- uploaded_by: bigint (FK → users.id)
- timestamps
```

### العلاقات بين الجداول

```
User (1) ──→ (1) Profile
User (1) ──→ (1) UserSettings
User (1) ──→ (*) AuthProviders
User (1) ──→ (*) Appointments (as patient)
User (1) ──→ (*) Appointments (as doctor)
User (1) ──→ (*) Visits (as patient)
User (1) ──→ (*) Visits (as doctor)
User (1) ──→ (*) Invoices
Appointment (1) ──→ (0..1) Visit
Visit (1) ──→ (0..1) Invoice
Invoice (1) ──→ (*) InvoiceItems
Service (1) ──→ (*) InvoiceItems
Attachable (*) ──→ (*) Attachments (polymorphic)
```

---

## 🔐 نظام المصادقة

### أنواع المصادقة المدعومة

#### 1. المصادقة التقليدية

-   تسجيل الدخول بالبريد الإلكتروني/اسم المستخدم وكلمة المرور
-   تسجيل المستخدمين الجدد
-   التحقق من البريد الإلكتروني
-   استعادة كلمة المرور

#### 2. تسجيل الدخول الاجتماعي (OAuth)

-   **Google**: باستخدام Google OAuth 2.0
-   **GitHub**: باستخدام GitHub OAuth

**ملفات التكوين:**

-   `.env`: إضافة `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
-   `config/services.php`: تكوين خدمات OAuth

#### 3. المصادقة الثنائية (2FA)

-   استخدام Google Authenticator
-   إنشاء QR Code لإعداد 2FA
-   التحقق برمز من 6 أرقام
-   تعطيل/تفعيل 2FA من الإعدادات

**المسارات:**

```
GET  /admin/user-settings/two-factor          # صفحة إعدادات 2FA
GET  /admin/user-settings/two-factor/enable   # عرض QR Code
POST /admin/two-factor/confirm                # تفعيل 2FA
POST /admin/user-settings/two-factor/disable  # تعطيل 2FA
GET  /admin/two-factor/verify                 # صفحة التحقق عند تسجيل الدخول
POST /admin/two-factor/verify                 # التحقق من الرمز
```

### طبقة الخدمات (Services Layer)

#### LoginService

```php
namespace App\Services\Auth;

class LoginService
{
    public function login(array $credentials)
    {
        // التحقق من بيانات الدخول
        // فحص تفعيل 2FA
        // إعادة توجيه حسب الحالة
    }
}
```

#### Google2FAService

```php
namespace App\Services;

class Google2FAService
{
    public function generateSecretKey()       // إنشاء مفتاح سري
    public function getQRCode($company, $email, $secret) // إنشاء QR Code
    public function verifyCode($secret, $code) // التحقق من الرمز
    public function isEnabled($user)           // فحص تفعيل 2FA
}
```

---

## 📦 الوحدات الرئيسية

### 1. وحدة المستخدمين (Users Module)

**الكونترولر:** `UserController.php`

**الوظائف:**

-   عرض قائمة المستخدمين (مع DataTables)
-   إنشاء مستخدم جديد
-   عرض تفاصيل المستخدم
-   تعديل بيانات المستخدم
-   حذف المستخدم
-   البحث عن المرضى (API Endpoint)

**المسارات:**

```
GET    /admin/users              # القائمة
GET    /admin/users/create       # نموذج الإنشاء
POST   /admin/users              # حفظ المستخدم
GET    /admin/users/{slug}       # عرض التفاصيل
GET    /admin/users/{slug}/edit  # نموذج التعديل
PUT    /admin/users/{slug}       # تحديث البيانات
DELETE /admin/users/{slug}       # الحذف
```

**الواجهات:**

-   `admin/users/index.blade.php`
-   `admin/users/create.blade.php`
-   `admin/users/show.blade.php`
-   `admin/users/edit.blade.php`

---

### 2. وحدة المواعيد (Appointments Module)

**الكونترولر:** `AppointmentController.php`

**الوظائف:**

-   جدولة موعد جديد
-   عرض قائمة المواعيد
-   تعديل الموعد
-   إلغاء الموعد
-   تحويل الموعد إلى زيارة

**الحالات المدعومة:**

```php
enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
```

**المسارات:**

```
GET    /admin/appointments
POST   /admin/appointments
GET    /admin/appointments/{id}
PUT    /admin/appointments/{id}
DELETE /admin/appointments/{id}
```

---

### 3. وحدة الزيارات (Visits Module)

**الكونترولر:** `VisitController.php`

**الوظائف:**

-   تسجيل زيارة طبية جديدة
-   إضافة التشخيص والعلاج
-   رفع المرفقات الطبية
-   تحديث حالة الزيارة
-   عرض تفاصيل الزيارة

**الحالات المدعومة:**

```php
enum VisitStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
```

**المرفقات:**

-   رفع ملفات متعددة
-   أنواع مدعومة: PDF, Images, Documents
-   معاينة الملفات
-   حذف المرفقات

**المسارات:**

```
GET    /admin/visits
POST   /admin/visits
GET    /admin/visits/{id}
PUT    /admin/visits/{id}
PATCH  /admin/visits/{id}/status           # تحديث الحالة
GET    /admin/visits/{id}/attachments/upload  # صفحة رفع الملفات
POST   /admin/visits/{id}/attachments       # حفظ المرفق
DELETE /admin/attachments/{id}              # حذف المرفق
```

---

### 4. وحدة الفواتير (Invoices Module)

**الكونترولر:** `InvoiceController.php`, `InvoiceItemController.php`

**الوظائف:**

-   إنشاء فاتورة جديدة
-   إضافة بنود الفاتورة
-   حساب المجاميع تلقائياً
-   تحديث حالة الدفع
-   طباعة الفاتورة
-   إحصائيات الفواتير

**حالات الدفع:**

```php
enum InvoicePaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
}
```

**المسارات:**

```
GET    /admin/invoices
POST   /admin/invoices
GET    /admin/invoices/{id}
PUT    /admin/invoices/{id}
GET    /admin/invoices/{id}/print           # طباعة
GET    /admin/invoices/statistics           # الإحصائيات
GET    /admin/user/{slug}/invoices/create   # إنشاء فاتورة لمستخدم
GET    /admin/visits/{id}/invoices/create   # إنشاء فاتورة لزيارة

# بنود الفاتورة
GET    /admin/invoices/{invoice}/items
POST   /admin/invoices/{invoice}/items
PUT    /admin/invoices/{invoice}/items/{item}
DELETE /admin/invoices/{invoice}/items/{item}
```

**الحسابات التلقائية:**

```php
// في نموذج Invoice
public function calculateTotal() {
    return $this->items->sum('total_price');
}

public function getRemainingAttribute() {
    return $this->total_amount - $this->paid_amount;
}
```

---

### 5. وحدة الخدمات (Services Module)

**الكونترولر:** `ServiceController.php`

**الوظائف:**

-   إنشاء خدمة طبية جديدة
-   تعديل الخدمة
-   تحديد السعر
-   تفعيل/تعطيل الخدمة

**المسارات:**

```
GET    /admin/services
POST   /admin/services
GET    /admin/services/{id}
PUT    /admin/services/{id}
DELETE /admin/services/{id}
```

---

## 🔔 نظام الإشعارات

### أنواع الإشعارات

1. **إشعارات قاعدة البيانات** (Database Notifications)
2. **إشعارات فورية** (Real-time via Pusher)
3. **إشعارات البريد الإلكتروني** (Email Notifications)

### إعداد Pusher

في ملف `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

### الكونترولر

`NotificationController.php`

**المسارات:**

```
GET  /admin/notifications              # قائمة الإشعارات
POST /admin/notifications/{id}/mark-read  # تعليم كمقروء
POST /admin/notifications/mark-all-read   # تعليم الكل كمقروء
DELETE /admin/notifications/{id}          # حذف إشعار
```

### مكون الإشعارات في الواجهة

```blade
<x-notifications-dropdown />
```

**الميزات:**

-   عداد الإشعارات غير المقروءة
-   قائمة منسدلة بآخر الإشعارات
-   تحديث فوري عبر Pusher
-   صوت تنبيه عند وصول إشعار جديد

---

## 📁 نظام الملفات

### تنظيم التخزين

```
storage/app/public/
└── users/
    └── {user_id}/
        ├── avatars/
        │   └── avatar_{timestamp}.{ext}
        ├── documents/
        │   └── document_{timestamp}.{ext}
        └── attachments/
            └── attachment_{timestamp}.{ext}
```

### PathHelper

```php
namespace App\Helpers;

class PathHelper
{
    // الحصول على مسار مجلد المستخدم
    public static function userPath($userId, $folder = '')

    // الحصول على URL للملف
    public static function userFileUrl($userId, $folder, $filename)

    // حذف ملف
    public static function deleteUserFile($userId, $folder, $filename)
}
```

### ImageHelper

```php
namespace App\Helpers;

class ImageHelper
{
    // تغيير حجم الصورة
    public static function resize($sourcePath, $width, $height)

    // قص الصورة (crop)
    public static function crop($sourcePath, $width, $height)

    // تحسين جودة الصورة
    public static function optimize($sourcePath)
}
```

### رفع الملفات

**مثال من UserSettingController:**

```php
public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = auth()->user();
    $file = $request->file('avatar');

    // حذف الصورة القديمة
    if ($user->profile->avatar) {
        PathHelper::deleteUserFile($user->id, 'avatars', $user->profile->avatar);
    }

    // حفظ الصورة الجديدة
    $filename = 'avatar_' . time() . '.' . $file->extension();
    $path = PathHelper::userPath($user->id, 'avatars');
    $file->storeAs($path, $filename, 'public');

    // تحديث قاعدة البيانات
    $user->profile->update(['avatar' => $filename]);
}
```

---

## 🌍 التعريب

### اللغات المدعومة

-   العربية (ar)
-   الإنجليزية (en)

### ملفات الترجمة

```
lang/
├── ar/
│   ├── admin.php       # ترجمات لوحة الإدارة
│   ├── auth.php        # ترجمات المصادقة
│   └── validation.php  # ترجمات التحقق
└── en/
    ├── admin.php
    ├── auth.php
    └── validation.php
```

### استخدام الترجمة في Blade

```blade
{{ __('admin.dashboard') }}
{{ __('admin.appointments.title') }}
{{ trans('admin.users.welcome', ['name' => $user->name]) }}
```

### استخدام الترجمة في الكونترولر

```php
return redirect()->back()->with('success', __('admin.messages.saved_successfully'));
```

### تبديل اللغة

```php
// في URL
/ar/admin/dashboard
/en/admin/dashboard

// برمجياً
app()->setLocale('ar');
```

### إعداد mcamara/laravel-localization

**في `config/laravellocalization.php`:**

```php
'supportedLocales' => [
    'ar' => ['name' => 'العربية', 'script' => 'Arab', 'dir' => 'rtl'],
    'en' => ['name' => 'English', 'script' => 'Latn', 'dir' => 'ltr'],
],
```

**في `app/Http/Kernel.php`:**

```php
protected $middlewareGroups = [
    'web' => [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationMiddleware::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    ],
];
```

---

## 🔒 الأمان

### الإجراءات الأمنية المطبقة

#### 1. حماية CSRF

-   جميع النماذج محمية بـ `@csrf`
-   التحقق التلقائي من الرموز

#### 2. تشفير كلمات المرور

-   استخدام `bcrypt` لتشفير كلمات المرور
-   عدم تخزين كلمات المرور بصيغة نصية

#### 3. التحقق من البريد الإلكتروني

-   إلزامية التحقق قبل الوصول للنظام
-   استخدام `EnsureEmailIsVerified` middleware

#### 4. المصادقة الثنائية (2FA)

-   طبقة أمان إضافية
-   استخدام Google Authenticator

#### 5. حماية المسارات

-   Middleware `Authenticate` لجميع المسارات الحساسة
-   التحقق من الصلاحيات

#### 6. تنظيف المدخلات

-   استخدام Form Requests للتحقق
-   تنقية البيانات تلقائياً

#### 7. الصلاحيات (Roles)

```php
enum Role: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
}
```

**التحقق من الصلاحيات:**

```php
if (auth()->user()->role === Role::ADMIN) {
    // منطق خاص بالمدير
}
```

#### 8. Rate Limiting

تحديد عدد المحاولات لتسجيل الدخول وإعادة تعيين كلمة المرور

---

## 🧪 الاختبار

### إعداد بيئة الاختبار

```bash
# نسخ ملف البيئة للاختبار
cp .env .env.testing

# تعديل قاعدة البيانات للاختبار
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# تشغيل الاختبارات
php artisan test
```

### أنواع الاختبارات

#### 1. اختبارات الوحدات (Unit Tests)

```bash
php artisan test --testsuite=Unit
```

#### 2. اختبارات الميزات (Feature Tests)

```bash
php artisan test --testsuite=Feature
```

#### 3. اختبارات محددة

```bash
php artisan test --filter=UserControllerTest
```

### أمثلة للاختبارات

**اختبار تسجيل الدخول:**

```php
public function test_user_can_login()
{
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticatedAs($user);
}
```

---

## 🐛 استكشاف الأخطاء

### المشاكل الشائعة والحلول

#### 1. خطأ "Class not found"

```bash
# تشغيل autoload
composer dump-autoload
```

#### 2. خطأ قاعدة البيانات

```bash
# التحقق من إعدادات .env
# إعادة تشغيل الترحيلات
php artisan migrate:fresh
```

#### 3. خطأ في الصلاحيات

```bash
# تصحيح صلاحيات المجلدات
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 4. مشكلة في عرض الصور

```bash
# التأكد من ربط مجلد التخزين
php artisan storage:link
```

#### 5. خطأ في Pusher

-   التحقق من بيانات الاعتماد في `.env`
-   التأكد من تفعيل Pusher في الحساب

#### 6. خطأ 2FA "Invalid Code"

-   التحقق من توقيت الخادم
-   التأكد من توقيت الهاتف صحيح
-   مزامنة الوقت عبر NTP

#### 7. Session Lost

```bash
# مسح الكاش
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### أدوات التطوير

#### Laravel Debugbar

```php
// تفعيل في .env
APP_DEBUG=true
DEBUGBAR_ENABLED=true
```

#### Laravel Pail (مراقبة Logs)

```bash
php artisan pail
```

#### Laravel Tinker (REPL)

```bash
php artisan tinker

# أمثلة
>>> User::count()
>>> App\Models\Appointment::latest()->first()
```

---

## 📚 موارد إضافية

### الوثائق الرسمية

-   [Laravel Documentation](https://laravel.com/docs)
-   [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0)
-   [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables)
-   [Laravel Localization](https://github.com/mcamara/laravel-localization)
-   [Google2FA](https://github.com/antonioribeiro/google2fa)

### أوامر Artisan المفيدة

```bash
# عرض جميع المسارات
php artisan route:list

# إنشاء كونترولر
php artisan make:controller Admin/ExampleController

# إنشاء نموذج مع الترحيل والمصنع
php artisan make:model Example -mf

# إنشاء Form Request
php artisan make:request StoreUserRequest

# إنشاء Middleware
php artisan make:middleware CheckRole

# إنشاء Notification
php artisan make:notification AppointmentReminder

# إنشاء Observer
php artisan make:observer UserObserver --model=User

# عرض قائمة الأوامر المتاحة
php artisan list
```

---

## 🙏 الدعم والمساهمة

### الإبلاغ عن الأخطاء

يرجى فتح Issue على GitHub مع وصف تفصيلي للمشكلة.

### المساهمة

1. Fork المشروع
2. إنشاء فرع للميزة الجديدة
3. Commit التغييرات
4. Push إلى الفرع
5. فتح Pull Request

---

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT License.

---

## 👨‍💻 المطور

**Abdulbaset RS**

-   GitHub: [@AbdulbasetRS](https://github.com/AbdulbasetRS)
-   Repository: [CM-ClinicManage](https://github.com/AbdulbasetRS/CM-ClinicManage)

---

**تم إنشاء هذه الوثائق بتاريخ:** 2025-11-26
