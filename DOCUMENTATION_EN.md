# CM-ClinicManage Documentation

## 📋 Table of Contents

-   [Overview](#overview)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Key Features](#key-features)
-   [Architecture](#architecture)
-   [Database](#database)
-   [Authentication System](#authentication-system)
-   [Main Modules](#main-modules)
-   [Notification System](#notification-system)
-   [File System](#file-system)
-   [Localization](#localization)
-   [Security](#security)
-   [Testing](#testing)
-   [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

**CM-ClinicManage** is a comprehensive clinic management system built on Laravel 12 framework. The system provides a complete solution for managing patients, appointments, visits, invoices, and medical services with a modern user interface using Bootstrap 5.

### 🎨 Technologies Used

-   **Backend Framework**: Laravel 12.x
-   **Frontend**: Blade Templates + Bootstrap 5 + Bootstrap Icons
-   **Database**: MySQL/SQLite
-   **Authentication**: Custom authentication system (without Breeze or Jetstream)
-   **Real-time**: Pusher (for real-time notifications)
-   **Localization**: mcamara/laravel-localization
-   **DataTables**: Yajra DataTables
-   **2FA**: Google Authenticator (pragmarx/google2fa)
-   **Social Login**: Google, GitHub (Laravel Socialite)

---

## 💻 Requirements

### System Requirements

-   **PHP**: >= 8.2
-   **Composer**: Latest version
-   **MySQL**: >= 5.7 or **SQLite**: >= 3.8
-   **Node.js**: >= 16.x (for frontend tools)
-   **NPM**: >= 8.x

### Required PHP Extensions

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
- GD Library (for image processing)
```

---

## 🚀 Installation

### 1. Clone the Project

```bash
git clone https://github.com/AbdulbasetRS/CM-ClinicManage.git
cd CM-ClinicManage
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

Edit the `.env` file and add your database connection details:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_manage
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Link Storage

```bash
php artisan storage:link
```

### 7. Run the Project

```bash
# Start the server
php artisan serve

# Run queue workers (in a separate terminal)
php artisan queue:work

# Run Vite for development (in a third terminal)
npm run dev
```

**Or use a single command:**

```bash
composer run dev
```

---

## ✨ Key Features

### 🔐 Comprehensive Authentication System

-   **User Registration** with email verification
-   **Login** with email or username
-   **Password Recovery** via email
-   **Mandatory Email Verification**
-   **Social Login** (Google, GitHub)
-   **Two-Factor Authentication (2FA)** using Google Authenticator

### 👥 User Management

-   Advanced permission system (Admin, Doctor, Patient)
-   Complete user profile pages
-   Avatar management
-   Password change
-   Personal security settings
-   Organized file storage by user ID

### 📅 Appointment Management

-   Schedule medical appointments
-   Detailed information (date, time, appointment type, status)
-   Link appointments to patients and doctors
-   Multiple statuses (scheduled, completed, cancelled)
-   Automatic notifications for upcoming appointments

### 🏥 Visit Management

-   Register medical visits
-   Link visits to appointments
-   Add diagnosis and notes
-   Upload medical attachments (images, reports, X-rays)
-   Track visit status (pending, in progress, completed)

### 💰 Billing System (Invoices)

-   Create invoices for patients
-   Link invoices to visits
-   Add invoice items
-   Automatic total calculations
-   Payment statuses (paid, unpaid, partially paid)
-   Print invoices (Print View)
-   Invoice statistics

### 🩺 Medical Services Management

-   Define available medical services
-   Service pricing
-   Link services to invoices

### 📎 Attachment System

-   Upload medical files
-   File preview
-   Automatic organization by attachment type
-   Delete and update attachments

### 🔔 Notification System

-   Real-time notifications using Pusher
-   Database notifications
-   Notification center in the interface
-   Alerts for appointments, visits, and invoices

### 🌐 Multi-language Support

-   Support for Arabic and English
-   Easy language switching
-   RTL support for Arabic
-   Comprehensive translation of all texts

---

## 🏗️ Architecture

### Folder Structure

```
CM-ClinicManage/
├── app/
│   ├── Enums/                    # Enumerations (Status, Roles, etc.)
│   │   ├── AppointmentStatus.php
│   │   ├── InvoicePaymentStatus.php
│   │   ├── Role.php
│   │   └── VisitStatus.php
│   ├── Events/                   # Events
│   ├── Exceptions/               # Custom exception handlers
│   ├── Helpers/                  # Helper functions
│   │   ├── PathHelper.php        # Path management helper
│   │   ├── ImageHelper.php       # Image processing helper
│   │   └── NotificationHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin panel controllers
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
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Custom form requests
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Profile.php
│   │   ├── UserSettings.php
│   │   ├── Appointment.php
│   │   ├── Visit.php
│   │   ├── Invoice.php
│   │   ├── InvoiceItem.php
│   │   ├── Service.php
│   │   └── Attachment.php
│   ├── Notifications/            # Laravel notifications
│   ├── Observers/                # Model observers
│   ├── Providers/                # Service providers
│   ├── Services/                 # Service layer
│   │   ├── Auth/
│   │   │   ├── LoginService.php
│   │   │   └── RegisterService.php
│   │   ├── Google2FAService.php
│   │   └── NotificationService.php
│   └── View/                     # Blade components
├── config/                       # Configuration files
├── database/
│   ├── factories/                # Data factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Data seeders
├── lang/                         # Translation files
│   ├── ar/                       # Arabic language
│   │   └── admin.php
│   └── en/                       # English language
│       └── admin.php
├── public/                       # Public files
│   ├── assets/                   # Assets (CSS, JS, Images)
│   └── storage/                  # Storage link
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/                # Admin panel views
│       │   ├── appointments/
│       │   ├── visits/
│       │   ├── invoices/
│       │   ├── services/
│       │   ├── users/
│       │   ├── two-factor/
│       │   └── notifications/
│       ├── auth/                 # Authentication views
│       ├── components/           # Blade components
│       ├── emails/               # Email templates
│       ├── errors/               # Error pages
│       └── frontend/             # Frontend views
├── routes/
│   ├── web.php                   # Public web routes
│   ├── admin.php                 # Admin panel routes
│   ├── channels.php              # Broadcast channels
│   └── console.php               # Artisan commands
├── storage/                      # Storage files
│   ├── app/
│   │   ├── public/
│   │   │   └── users/            # User files
│   │   │       └── {user_id}/
│   │   │           ├── avatars/
│   │   │           └── documents/
│   │   └── private/
│   ├── framework/
│   └── logs/
└── tests/                        # Tests
```

---

## 🗄️ Database

### Main Tables

#### 1. Users Table (users)

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

#### 2. Profiles Table (profiles)

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

#### 3. User Settings Table (user_settings)

```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id, unique)
- enable_two_factor: boolean (default: false)
- google2fa_secret: string (nullable)
- notification_preferences: json (nullable)
- timestamps
```

#### 4. Auth Providers Table (auth_providers)

```sql
- id: bigint (PK)
- user_id: bigint (FK → users.id)
- provider: string ('google', 'github')
- provider_id: string
- avatar: string (nullable)
- timestamps
```

#### 5. Appointments Table (appointments)

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

#### 6. Visits Table (visits)

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

#### 7. Invoices Table (invoices)

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

#### 8. Invoice Items Table (invoice_items)

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

#### 9. Services Table (services)

```sql
- id: bigint (PK)
- name: string
- description: text (nullable)
- price: decimal(10,2)
- is_active: boolean (default: true)
- timestamps
```

#### 10. Attachments Table (attachments)

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

### Table Relationships

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

## 🔐 Authentication System

### Supported Authentication Types

#### 1. Traditional Authentication

-   Login with email/username and password
-   User registration
-   Email verification
-   Password recovery

#### 2. Social Login (OAuth)

-   **Google**: Using Google OAuth 2.0
-   **GitHub**: Using GitHub OAuth

**Configuration files:**

-   `.env`: Add `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
-   `config/services.php`: Configure OAuth services

#### 3. Two-Factor Authentication (2FA)

-   Using Google Authenticator
-   Generate QR Code for 2FA setup
-   Verify with 6-digit code
-   Enable/disable 2FA from settings

**Routes:**

```
GET  /admin/user-settings/two-factor          # 2FA settings page
GET  /admin/user-settings/two-factor/enable   # Display QR Code
POST /admin/two-factor/confirm                # Enable 2FA
POST /admin/user-settings/two-factor/disable  # Disable 2FA
GET  /admin/two-factor/verify                 # Verification page on login
POST /admin/two-factor/verify                 # Verify code
```

### Services Layer

#### LoginService

```php
namespace App\Services\Auth;

class LoginService
{
    public function login(array $credentials)
    {
        // Verify credentials
        // Check 2FA activation
        // Redirect based on status
    }
}
```

#### Google2FAService

```php
namespace App\Services;

class Google2FAService
{
    public function generateSecretKey()       // Generate secret key
    public function getQRCode($company, $email, $secret) // Generate QR Code
    public function verifyCode($secret, $code) // Verify code
    public function isEnabled($user)           // Check if 2FA is enabled
}
```

---

## 📦 Main Modules

### 1. Users Module

**Controller:** `UserController.php`

**Functions:**

-   Display user list (with DataTables)
-   Create new user
-   View user details
-   Edit user data
-   Delete user
-   Search for patients (API Endpoint)

**Routes:**

```
GET    /admin/users              # List
GET    /admin/users/create       # Create form
POST   /admin/users              # Save user
GET    /admin/users/{slug}       # View details
GET    /admin/users/{slug}/edit  # Edit form
PUT    /admin/users/{slug}       # Update data
DELETE /admin/users/{slug}       # Delete
```

**Views:**

-   `admin/users/index.blade.php`
-   `admin/users/create.blade.php`
-   `admin/users/show.blade.php`
-   `admin/users/edit.blade.php`

---

### 2. Appointments Module

**Controller:** `AppointmentController.php`

**Functions:**

-   Schedule new appointment
-   Display appointment list
-   Edit appointment
-   Cancel appointment
-   Convert appointment to visit

**Supported Statuses:**

```php
enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
```

**Routes:**

```
GET    /admin/appointments
POST   /admin/appointments
GET    /admin/appointments/{id}
PUT    /admin/appointments/{id}
DELETE /admin/appointments/{id}
```

---

### 3. Visits Module

**Controller:** `VisitController.php`

**Functions:**

-   Register new medical visit
-   Add diagnosis and treatment
-   Upload medical attachments
-   Update visit status
-   View visit details

**Supported Statuses:**

```php
enum VisitStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
```

**Attachments:**

-   Upload multiple files
-   Supported types: PDF, Images, Documents
-   File preview
-   Delete attachments

**Routes:**

```
GET    /admin/visits
POST   /admin/visits
GET    /admin/visits/{id}
PUT    /admin/visits/{id}
PATCH  /admin/visits/{id}/status           # Update status
GET    /admin/visits/{id}/attachments/upload  # Upload page
POST   /admin/visits/{id}/attachments       # Save attachment
DELETE /admin/attachments/{id}              # Delete attachment
```

---

### 4. Invoices Module

**Controllers:** `InvoiceController.php`, `InvoiceItemController.php`

**Functions:**

-   Create new invoice
-   Add invoice items
-   Automatic total calculations
-   Update payment status
-   Print invoice
-   Invoice statistics

**Payment Statuses:**

```php
enum InvoicePaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
}
```

**Routes:**

```
GET    /admin/invoices
POST   /admin/invoices
GET    /admin/invoices/{id}
PUT    /admin/invoices/{id}
GET    /admin/invoices/{id}/print           # Print
GET    /admin/invoices/statistics           # Statistics
GET    /admin/user/{slug}/invoices/create   # Create invoice for user
GET    /admin/visits/{id}/invoices/create   # Create invoice for visit

# Invoice items
GET    /admin/invoices/{invoice}/items
POST   /admin/invoices/{invoice}/items
PUT    /admin/invoices/{invoice}/items/{item}
DELETE /admin/invoices/{invoice}/items/{item}
```

**Automatic Calculations:**

```php
// In Invoice model
public function calculateTotal() {
    return $this->items->sum('total_price');
}

public function getRemainingAttribute() {
    return $this->total_amount - $this->paid_amount;
}
```

---

### 5. Services Module

**Controller:** `ServiceController.php`

**Functions:**

-   Create new medical service
-   Edit service
-   Set price
-   Activate/deactivate service

**Routes:**

```
GET    /admin/services
POST   /admin/services
GET    /admin/services/{id}
PUT    /admin/services/{id}
DELETE /admin/services/{id}
```

---

## 🔔 Notification System

### Notification Types

1. **Database Notifications**
2. **Real-time Notifications** (via Pusher)
3. **Email Notifications**

### Pusher Setup

In `.env` file:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

### Controller

`NotificationController.php`

**Routes:**

```
GET  /admin/notifications              # List notifications
POST /admin/notifications/{id}/mark-read  # Mark as read
POST /admin/notifications/mark-all-read   # Mark all as read
DELETE /admin/notifications/{id}          # Delete notification
```

### Notification Component in UI

```blade
<x-notifications-dropdown />
```

**Features:**

-   Unread notification counter
-   Dropdown list of recent notifications
-   Real-time updates via Pusher
-   Alert sound on new notification

---

## 📁 File System

### Storage Organization

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
    // Get user folder path
    public static function userPath($userId, $folder = '')

    // Get file URL
    public static function userFileUrl($userId, $folder, $filename)

    // Delete file
    public static function deleteUserFile($userId, $folder, $filename)
}
```

### ImageHelper

```php
namespace App\Helpers;

class ImageHelper
{
    // Resize image
    public static function resize($sourcePath, $width, $height)

    // Crop image
    public static function crop($sourcePath, $width, $height)

    // Optimize image quality
    public static function optimize($sourcePath)
}
```

### File Upload

**Example from UserSettingController:**

```php
public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = auth()->user();
    $file = $request->file('avatar');

    // Delete old image
    if ($user->profile->avatar) {
        PathHelper::deleteUserFile($user->id, 'avatars', $user->profile->avatar);
    }

    // Save new image
    $filename = 'avatar_' . time() . '.' . $file->extension();
    $path = PathHelper::userPath($user->id, 'avatars');
    $file->storeAs($path, $filename, 'public');

    // Update database
    $user->profile->update(['avatar' => $filename]);
}
```

---

## 🌍 Localization

### Supported Languages

-   Arabic (ar)
-   English (en)

### Translation Files

```
lang/
├── ar/
│   ├── admin.php       # Admin panel translations
│   ├── auth.php        # Authentication translations
│   └── validation.php  # Validation translations
└── en/
    ├── admin.php
    ├── auth.php
    └── validation.php
```

### Using Translation in Blade

```blade
{{ __('admin.dashboard') }}
{{ __('admin.appointments.title') }}
{{ trans('admin.users.welcome', ['name' => $user->name]) }}
```

### Using Translation in Controller

```php
return redirect()->back()->with('success', __('admin.messages.saved_successfully'));
```

### Language Switching

```php
// In URL
/ar/admin/dashboard
/en/admin/dashboard

// Programmatically
app()->setLocale('ar');
```

### mcamara/laravel-localization Setup

**In `config/laravellocalization.php`:**

```php
'supportedLocales' => [
    'ar' => ['name' => 'العربية', 'script' => 'Arab', 'dir' => 'rtl'],
    'en' => ['name' => 'English', 'script' => 'Latn', 'dir' => 'ltr'],
],
```

**In `app/Http/Kernel.php`:**

```php
protected $middlewareGroups = [
    'web' => [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationMiddleware::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    ],
];
```

---

## 🔒 Security

### Implemented Security Measures

#### 1. CSRF Protection

-   All forms protected with `@csrf`
-   Automatic token verification

#### 2. Password Encryption

-   Using `bcrypt` for password hashing
-   No plain text password storage

#### 3. Email Verification

-   Mandatory verification before system access
-   Using `EnsureEmailIsVerified` middleware

#### 4. Two-Factor Authentication (2FA)

-   Additional security layer
-   Using Google Authenticator

#### 5. Route Protection

-   `Authenticate` middleware for all sensitive routes
-   Permission verification

#### 6. Input Sanitization

-   Using Form Requests for validation
-   Automatic data sanitization

#### 7. Roles & Permissions

```php
enum Role: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
}
```

**Permission Checking:**

```php
if (auth()->user()->role === Role::ADMIN) {
    // Admin-specific logic
}
```

#### 8. Rate Limiting

Limiting attempts for login and password reset

---

## 🧪 Testing

### Test Environment Setup

```bash
# Copy environment file for testing
cp .env .env.testing

# Modify database for testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Run tests
php artisan test
```

### Test Types

#### 1. Unit Tests

```bash
php artisan test --testsuite=Unit
```

#### 2. Feature Tests

```bash
php artisan test --testsuite=Feature
```

#### 3. Specific Tests

```bash
php artisan test --filter=UserControllerTest
```

### Test Examples

**Login Test:**

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

## 🐛 Troubleshooting

### Common Issues and Solutions

#### 1. "Class not found" Error

```bash
# Run autoload
composer dump-autoload
```

#### 2. Database Error

```bash
# Check .env settings
# Re-run migrations
php artisan migrate:fresh
```

#### 3. Permission Error

```bash
# Fix folder permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 4. Image Display Issue

```bash
# Ensure storage link is created
php artisan storage:link
```

#### 5. Pusher Error

-   Verify credentials in `.env`
-   Ensure Pusher is activated in account

#### 6. 2FA "Invalid Code" Error

-   Check server time
-   Ensure phone time is correct
-   Sync time via NTP

#### 7. Session Lost

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Development Tools

#### Laravel Debugbar

```php
// Enable in .env
APP_DEBUG=true
DEBUGBAR_ENABLED=true
```

#### Laravel Pail (Log Monitoring)

```bash
php artisan pail
```

#### Laravel Tinker (REPL)

```bash
php artisan tinker

# Examples
>>> User::count()
>>> App\Models\Appointment::latest()->first()
```

---

## 📚 Additional Resources

### Official Documentation

-   [Laravel Documentation](https://laravel.com/docs)
-   [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0)
-   [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables)
-   [Laravel Localization](https://github.com/mcamara/laravel-localization)
-   [Google2FA](https://github.com/antonioribeiro/google2fa)

### Useful Artisan Commands

```bash
# List all routes
php artisan route:list

# Create controller
php artisan make:controller Admin/ExampleController

# Create model with migration and factory
php artisan make:model Example -mf

# Create Form Request
php artisan make:request StoreUserRequest

# Create Middleware
php artisan make:middleware CheckRole

# Create Notification
php artisan make:notification AppointmentReminder

# Create Observer
php artisan make:observer UserObserver --model=User

# List available commands
php artisan list
```

---

## 🙏 Support and Contribution

### Report Bugs

Please open an Issue on GitHub with a detailed description of the problem.

### Contributing

1. Fork the project
2. Create a feature branch
3. Commit changes
4. Push to branch
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Developer

**Abdulbaset RS**

-   GitHub: [@AbdulbasetRS](https://github.com/AbdulbasetRS)
-   Repository: [CM-ClinicManage](https://github.com/AbdulbasetRS/CM-ClinicManage)

---

**Documentation Created On:** 2025-11-26
