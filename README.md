# CM-ClinicManage

A ready-to-use Laravel 12 project with a complete **Authentication system** (Register, Login, Logout) built using **Blade** and **Bootstrap 5**.  
This project is designed to be a quick starting point for new Laravel applications without the need to set up authentication from scratch.

---

## Features

-   Laravel **12.x**
-   Native Authentication (**no Breeze or Jetstream**)
-   Blade Templates with **Bootstrap 5**
-   User **Registration**, **Login**, and **Logout**
-   SQLite database with a pre-configured **users** table
-   Validation rules and error messages included
-   Clean and extendable structure for quick development
-   Built-in localization using **mcamara/laravel-localization** (i18n-ready)

## 📋 Requirements

Before installing and running this project, make sure your system meets the following requirements:

-   **PHP >= 8.2**

    -   Required PHP extensions:
        -   OpenSSL
        -   PDO
        -   PDO SQLite
        -   Mbstring
        -   Tokenizer
        -   XML
        -   Ctype
        -   Fileinfo
        -   Curl

-   **Composer**

    -   Dependency manager for PHP
    -   Required to install Laravel dependencies

-   **SQLite**

    -   The project uses **SQLite** as the default database
    -   No database server setup is required
    -   Make sure the SQLite PHP extension (`pdo_sqlite`) is enabled

> ⚠️ **Note:**  
> This project is intended for developers or users with basic technical knowledge.  
> If you are not familiar with PHP, Laravel, or command-line tools, it is recommended to ask a developer for assistance during setup.

---

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
6. Ensure the SQLite database file exists and is properly configured in `.env`
7. Run migrations
8. Start the server

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

## ▶️ Running the Project

### Windows (Recommended)

A ready-to-use script is included to make running the project easy.

1. Double-click `server.bat`
2. The script will:
    - Start the Laravel development server
    - Start the queue worker
    - Open the browser automatically

The application will be available at:
http://127.0.0.1:8000

### Linux/Mac

1. Open a terminal
2. Navigate to the project directory
3. Run the following commands:

```bash
php artisan serve
php artisan queue:work
```

The application will be available at:
http://127.0.0.1:8000

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
