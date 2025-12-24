# CM-ClinicManage

A ready-to-use Laravel 12 project with a complete **Authentication system** (Register, Login, Logout) built using **Blade** and **Bootstrap 5**.  
This project is designed to be a quick starting point for new Laravel applications without the need to set up authentication from scratch.

---

## Features

-   Laravel **12.x**
-   Native Authentication (**no Breeze or Jetstream**)
-   Blade Templates with **Bootstrap 5**
-   User **Registration**, **Login**, and **Logout**
-   MySQL database with a pre-configured **users** table
-   Validation rules and error messages included
-   Clean and extendable structure for quick development
-   Built-in localization using **mcamara/laravel-localization** (i18n-ready)

## Included Packages

### Core Packages

-   **mcamara/laravel-localization** (v2.3+): Provides an easy way to support multiple languages in your Laravel application.
-   **yajra/laravel-datatables** (v12): Powerful server-side and client-side datatables integration for Laravel.

### Development Packages

-   **barryvdh/laravel-debugbar** (v3.16+): A debug bar for Laravel to help with debugging and profiling.
-   **spatie/laravel-ignition** (v2.9+): A beautiful error page for Laravel applications.

---

## Installation

### Steps

1. Clone the repository
2. Navigate into the project
3. Install dependencies with composer
4. Copy `.env.example` to `.env`
5. Generate application key
6. Set up database credentials in `.env` and run migrations
7. Start the server

### Commands

```bash
git clone https://github.com/AbdulbasetRS/CM-ClinicManage.git
cd CM-ClinicManage
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
php artisan queue:work
```

---

## 📚 Documentation

### About This Project

To learn more about the project's purpose, target audience, and how it can help you:

-   **📖 [About the Project (العربية)](ABOUT_AR.md)** - نبذة تفصيلية عن المشروع والفئة المستهدفة
-   **📖 [About the Project (English)](ABOUT_EN.md)** - Detailed overview of the project and target audience

### Complete Documentation

For comprehensive technical documentation:

-   **📘 [English Documentation](DOCUMENTATION_EN.md)** - Complete project documentation in English
-   **📗 [Arabic Documentation (التوثيق العربي)](DOCUMENTATION_AR.md)** - توثيق شامل للمشروع بالعربية

The documentation covers:

-   Complete installation guide
-   System architecture and database structure
-   Authentication system (including 2FA)
-   All modules and features
-   API endpoints
-   Security practices
-   Troubleshooting guide

---
