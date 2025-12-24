# Google Authenticator 2FA Implementation Guide

## Overview
This document describes the Google Authenticator Two-Factor Authentication (2FA) implementation added to your Laravel application.

---

## 📦 Installation

### 1. Install the Required Package

Run the following command to install the `pragmarx/google2fa-qrcode` package:

```bash
composer require pragmarx/google2fa-qrcode
```

### 2. Run Database Migrations

The `user_settings` table already exists with the required columns:
- `enable_two_factor` (boolean)
- `google2fa_secret` (string, nullable)

If you haven't run migrations yet:

```bash
php artisan migrate
```

---

## 🎯 Features Implemented

### ✅ Service Class
- **File:** `app/Services/Google2FAService.php`
- **Methods:**
  - `generateSecretKey()` - Generate a new secret key
  - `getQRCode($companyName, $email, $secret)` - Generate QR code for Google Authenticator
  - `verifyCode($secret, $code)` - Verify 6-digit code
  - `isEnabled($user)` - Check if 2FA is enabled for user

### ✅ Models
- **UserSettings Model:** `app/Models/UserSettings.php`
  - Handles user security and preference settings
  - Relationship with User model

- **User Model Updated:** `app/Models/User.php`
  - Added `settings()` relationship

### ✅ Controller
- **File:** `app/Http/Controllers/Admin/TwoFactorController.php`
- **Methods:**
  - `index()` - Show 2FA settings page
  - `enable()` - Display QR code for enabling 2FA
  - `verify()` - Verify and activate 2FA
  - `disable()` - Disable 2FA with password confirmation
  - `showVerify()` - Show 2FA verification during login
  - `verifyLogin()` - Verify 2FA code during login

### ✅ Routes
All routes are defined in `routes/admin.php`:

#### Guest Routes (Login Flow):
- `GET /admin/two-factor/verify` - Show 2FA verification page
- `POST /admin/two-factor/verify` - Verify 2FA code during login

#### Authenticated Routes (Settings):
- `GET /admin/two-factor` - 2FA settings dashboard
- `GET /admin/two-factor/enable` - Enable 2FA (show QR code)
- `POST /admin/two-factor/verify` - Verify code to activate 2FA
- `POST /admin/two-factor/disable` - Disable 2FA

### ✅ Views (Blade + Bootstrap)
All views are in `resources/views/admin/two-factor/`:

1. **index.blade.php** - 2FA settings dashboard
   - Shows current 2FA status
   - Enable/Disable buttons
   - Information about 2FA

2. **enable.blade.php** - QR code setup page
   - Displays QR code
   - Shows manual entry option
   - Verification form

3. **verify.blade.php** - Login verification page
   - Simple code entry form
   - Used during login when 2FA is enabled

### ✅ Login Flow Updated
- **File:** `app/Services/Auth/LoginService.php`
  - Checks if 2FA is enabled after password verification
  - Redirects to 2FA verification page instead of logging in directly

- **File:** `app/Http/Controllers/Admin/AuthController.php`
  - Handles redirect to 2FA verification page

---

## 🚀 How to Use

### For Users

#### Enabling 2FA:
1. Log in to your account
2. Navigate to `/admin/two-factor`
3. Click "تفعيل المصادقة الثنائية" (Enable Two-Factor Authentication)
4. Download **Google Authenticator** app on your phone
5. Scan the QR code or enter the secret key manually
6. Enter the 6-digit code from the app to verify
7. 2FA is now enabled!

#### Logging In with 2FA:
1. Enter username/email and password as usual
2. You'll be redirected to the 2FA verification page
3. Open Google Authenticator app
4. Enter the 6-digit code
5. Click "تحقق" (Verify) to complete login

#### Disabling 2FA:
1. Go to `/admin/two-factor`
2. Enter your password
3. Click "تعطيل المصادقة الثنائية" (Disable Two-Factor Authentication)

---

## 📝 Database Structure

### user_settings Table
```sql
- id (bigint)
- user_id (bigint) - Foreign key to users table
- enable_two_factor (boolean) - Default: false
- google2fa_secret (string, nullable) - Stores encrypted secret
- ... other settings columns
- created_at (timestamp)
- updated_at (timestamp)
```

---

## 🔒 Security Notes

1. **Secret Key Storage**: The Google2FA secret is stored in the database. Consider encrypting sensitive data at rest.

2. **Session Management**: 2FA verification uses session storage for temporary user identification during login.

3. **Password Verification**: Disabling 2FA requires password confirmation for security.

4. **QR Code**: The QR code is generated dynamically and not stored.

---

## 🧪 Testing

### Test the Implementation:

1. **Enable 2FA:**
   ```
   Visit: /admin/two-factor
   Enable 2FA and scan QR code
   ```

2. **Login with 2FA:**
   ```
   Log out and log back in
   Verify the 2FA code prompt appears
   ```

3. **Disable 2FA:**
   ```
   Go to settings and disable with password
   Verify normal login works
   ```

---

## 🎨 UI/UX Features

- **Bootstrap 5** styling
- **Bootstrap Icons** for visual elements
- **Responsive design** for mobile and desktop
- **Arabic language** support (RTL compatible)
- **Form validation** with helpful error messages
- **Copy to clipboard** functionality for manual secret entry
- **Auto-format** code input (numbers only, 6 digits)

---

## 📱 Required Mobile App

Users need to install **Google Authenticator** on their mobile device:
- **iOS**: [App Store](https://apps.apple.com/app/google-authenticator/id388497605)
- **Android**: [Play Store](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)

---

## 🔧 Customization

### Change Company Name in QR Code:
Edit `app/Http/Controllers/Admin/TwoFactorController.php`:
```php
$companyName = config('app.name', 'Laravel App');
```

### Change Code Expiry Window:
The default window is 1 code (30 seconds). To modify, edit `Google2FAService.php`:
```php
$this->google2fa->setWindow(2); // 2 codes = 60 seconds
```

### Customize Messages:
All Arabic messages are in the controller and views. Search for text strings to customize.

---

## 🐛 Troubleshooting

### Issue: QR Code Not Displaying
- Ensure the `pragmarx/google2fa-qrcode` package is installed
- Check if `bacon/bacon-qr-code` is also installed (dependency)

### Issue: "Invalid Code" Error
- Ensure your server time is synchronized (NTP)
- Check if the user's phone time is correct
- Time drift can cause verification failures

### Issue: 2FA Redirect Loop
- Clear session data
- Check if `2fa:user:id` session key is being set correctly

---

## 📚 Package Documentation

For more details about the package:
- [Google2FA Documentation](https://github.com/antonioribeiro/google2fa)
- [Google2FA QRCode Documentation](https://github.com/antonioribeiro/google2fa-qrcode)

---

## ✅ Implementation Checklist

- [x] Install `pragmarx/google2fa-qrcode` package
- [x] Create Google2FAService class
- [x] Create UserSettings model
- [x] Update User model with settings relationship
- [x] Create TwoFactorController
- [x] Add routes for 2FA
- [x] Create Blade views with Bootstrap
- [x] Update login flow to check for 2FA
- [x] Test enable/disable functionality
- [x] Test login with 2FA

---

## 🎉 Congratulations!

Your Laravel application now has Google Authenticator Two-Factor Authentication fully implemented!

Users can enable 2FA from their account settings, and the login flow will automatically prompt for verification when 2FA is active.
