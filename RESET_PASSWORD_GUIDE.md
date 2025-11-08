# 🔐 Reset Password System Guide

## 🎯 Overview

Sistem reset password telah diimplementasikan lengkap untuk user. User yang lupa password dapat melakukan reset melalui email dengan aman dan mudah.

---

## ✅ Fitur yang Tersedia

### 1. **Complete Reset Flow**
- 📧 Request reset password via email
- ✉️ Email reset link otomatis terkirim
- 🔒 Secure token-based verification
- ⏰ Link expire dalam 60 menit
- 🔑 Set password baru
- ✅ Auto-login setelah reset (opsional)

### 2. **Security Features**
- **Token-based authentication** - Secure random token
- **Time-limited links** - Expire setelah 60 menit
- **Email verification** - Link hanya valid untuk email yang terdaftar
- **Rate limiting** - Throttle 60 detik untuk prevent spam
- **Password strength** - Minimal 8 karakter
- **Password confirmation** - Wajib konfirmasi untuk prevent typo

### 3. **User Experience**
- **Bahasa Indonesia** - Email dan UI dalam Bahasa Indonesia
- **Responsive design** - Mobile-friendly
- **Clear instructions** - Panduan jelas di setiap step
- **Password visibility toggle** - Show/hide password
- **Error handling** - Pesan error yang jelas

---

## 🔄 Reset Password Flow

### **User Journey:**

```
[User Lupa Password]
    ↓
[Klik "Lupa Password?" di login page]
    ↓
[Form: Masukkan Email]
    ↓
[Submit] → Email sent! ✉️
    ↓
[User cek email inbox]
    ↓
[Klik link "Reset Password" di email]
    ↓
[Form: Set Password Baru]
    ↓
[Submit] → Password Changed! ✅
    ↓
[Redirect ke Login]
    ↓
[Login dengan password baru] 🎉
```

---

## 📂 File Structure

### **Routes:**
```php
// routes/web.php

Route::prefix('guest')->name('guest.')->group(function () {
    Route::middleware('guest')->group(function () {
        // Forgot Password
        Route::get('/forgot-password', [GuestAuthController::class, 'showForgotPasswordForm'])
            ->name('password.request');
        
        Route::post('/forgot-password', [GuestAuthController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        
        // Reset Password
        Route::get('/reset-password/{token}', [GuestAuthController::class, 'showResetPasswordForm'])
            ->name('password.reset');
        
        Route::post('/reset-password', [GuestAuthController::class, 'resetPassword'])
            ->name('password.update');
    });
});
```

### **Controller Methods:**

**File:** `app/Http/Controllers/Guest/AuthController.php`

```php
// 1. Show form lupa password
public function showForgotPasswordForm()

// 2. Kirim email reset link
public function sendResetLinkEmail(Request $request)

// 3. Show form reset password
public function showResetPasswordForm(string $token)

// 4. Process reset password
public function resetPassword(Request $request)
```

### **Notification:**

**File:** `app/Notifications/CustomResetPassword.php`

- Custom email notification dalam Bahasa Indonesia
- Professional email template
- Include user name untuk personalisasi
- Clear call-to-action button

### **Views:**

1. **forgot-password.blade.php** - Form request reset
2. **reset-password.blade.php** - Form set password baru

### **Database:**

**Table:** `password_reset_tokens`

```sql
email (primary key)
token
created_at
```

### **Configuration:**

**File:** `config/auth.php`

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,      // Link expire dalam 60 menit
        'throttle' => 60,    // Throttle 60 detik
    ],
],
```

---

## 🧪 Testing Guide

### **Test 1: Forgot Password - Valid Email**

1. **Buka halaman login:**
   ```
   http://127.0.0.1:8000/guest/login
   ```

2. **Klik "Lupa Password?"**

3. **Masukkan email yang terdaftar:**
   - Email: `john@example.com` (atau email test lainnya)
   - Klik "Kirim Link Reset"

4. **Expected Result:**
   - ✅ Success message: "We have emailed your password reset link!"
   - ✅ Email terkirim ke inbox
   - ✅ Redirect back to form

5. **Cek Email:**
   - Subject: "Reset Password - School Gallery"
   - Body: Bahasa Indonesia dengan greeting name
   - Button: "Reset Password"

6. **Klik button "Reset Password"**
   - ✅ Redirect ke form reset password
   - ✅ Email pre-filled
   - ✅ Token valid

### **Test 2: Set New Password**

1. **Di form reset password:**
   - Password Baru: `newpassword123`
   - Konfirmasi Password: `newpassword123`
   - Klik "Simpan Password Baru"

2. **Expected Result:**
   - ✅ Success message: "Your password has been reset!"
   - ✅ Redirect ke login page
   - ✅ Password berhasil diupdate

3. **Login dengan password baru:**
   - Email: `john@example.com`
   - Password: `newpassword123`
   - ✅ Login berhasil!

### **Test 3: Forgot Password - Invalid Email**

1. **Masukkan email tidak terdaftar:**
   - Email: `notexist@example.com`
   - Klik "Kirim Link Reset"

2. **Expected Result:**
   - ❌ Error: "We can't find a user with that email address."

### **Test 4: Expired Token**

1. **Ubah expire time untuk testing:**
   ```php
   // config/auth.php
   'expire' => 1, // 1 menit untuk testing
   ```

2. **Request reset password**
3. **Tunggu > 1 menit**
4. **Klik link di email**

5. **Expected Result:**
   - ❌ Error: "This password reset token is invalid."

### **Test 5: Password Validation**

1. **Password terlalu pendek (<8 karakter):**
   - Password: `short`
   - ❌ Error: "The password field must be at least 8 characters."

2. **Password tidak match:**
   - Password: `password123`
   - Confirmation: `different123`
   - ❌ Error: "The password field confirmation does not match."

### **Test 6: Rate Limiting**

1. **Request reset password berkali-kali dalam < 60 detik**
2. **Expected Result:**
   - ❌ Error: "Please wait before retrying."

---

## 📧 Email Template Preview

**Subject:** Reset Password - School Gallery

```
Halo, John Doe!

Anda menerima email ini karena kami menerima permintaan reset 
password untuk akun Anda.

Silakan klik tombol di bawah ini untuk mereset password Anda.

[Reset Password] ← Button (link ke form)

Link reset password ini akan kadaluarsa dalam 60 menit.

Jika Anda tidak merasa meminta reset password, abaikan email ini. 
Akun Anda tetap aman.

Salam,
Tim School Gallery
```

---

## 🔧 Configuration

### **Email Settings (Already Configured)**

File `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=dwavestoreinfinity@gmail.com
MAIL_PASSWORD=hpalewqgatxworwl
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="dwavestoreinfinity@gmail.com"
MAIL_FROM_NAME="School Gallery"
```

### **Password Broker Settings**

File `config/auth.php`:
```php
'passwords' => [
    'users' => [
        'provider' => 'users',        // Use users table
        'table' => 'password_reset_tokens',
        'expire' => 60,               // Link expire (minutes)
        'throttle' => 60,             // Throttle delay (seconds)
    ],
],
```

---

## 🚨 Troubleshooting

### **Problem 1: Email tidak terkirim**

**Symptoms:**
- Form submitted tapi tidak ada email masuk
- No error message

**Solutions:**

1. **Check email configuration:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test email manually:**
   ```bash
   php artisan tinker
   >>> use Illuminate\Support\Facades\Password;
   >>> Password::sendResetLink(['email' => 'test@example.com']);
   ```

3. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify Gmail App Password** is correct in `.env`

### **Problem 2: Link expired atau invalid**

**Symptoms:**
- Error: "This password reset token is invalid."

**Causes:**
1. Token sudah expire (> 60 menit)
2. Token sudah digunakan
3. Email tidak match

**Solution:**
- Request reset password lagi
- Check expire time di `config/auth.php`

### **Problem 3: Password tidak berubah**

**Check:**
1. Validation errors di form?
2. Password memenuhi requirements (min 8 char)?
3. Password confirmation match?

**Debug:**
```bash
# Check current password hash
php artisan tinker
>>> $user = App\Models\User::where('email', 'john@example.com')->first();
>>> $user->password; // Should be bcrypt hash
```

### **Problem 4: Rate limiting too aggressive**

**Adjust throttle time:**

File `config/auth.php`:
```php
'throttle' => 60, // Change to lower value (e.g., 30)
```

Then clear config:
```bash
php artisan config:clear
```

---

## 🔒 Security Best Practices

### **✅ Already Implemented:**

1. **Token-based verification** - Not password-based
2. **Time-limited tokens** - Auto-expire after 60 minutes
3. **One-time use tokens** - Token deleted after successful reset
4. **Email verification** - Link only valid for correct email
5. **Rate limiting** - Prevent brute force attacks
6. **Password hashing** - Bcrypt for secure storage
7. **Password confirmation** - Prevent typos

### **🛡️ Additional Security (Optional):**

1. **Add CAPTCHA** - Prevent automated attacks
2. **IP logging** - Track reset requests
3. **Email notification** - Notify when password changed
4. **2FA support** - Extra layer of security

---

## 📊 Database Schema

### **Table: password_reset_tokens**

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);
```

**Indexes:**
- Primary key on `email`
- Automatic cleanup of expired tokens

---

## 🎨 UI Features

### **forgot-password.blade.php:**
- Clean and modern design
- Email input with validation
- Clear instructions
- Link back to login
- Success/error messages
- Responsive layout

### **reset-password.blade.php:**
- Password strength indicator (via HTML5)
- Show/hide password toggle
- Password confirmation field
- Real-time validation
- Clear error messages
- Professional design

---

## 📱 API Endpoints (For Reference)

### **1. Request Reset Link**

```http
POST /guest/forgot-password
Content-Type: application/x-www-form-urlencoded

email=user@example.com
```

**Response (Success):**
```json
{
    "status": "We have emailed your password reset link!"
}
```

**Response (Error):**
```json
{
    "errors": {
        "email": ["We can't find a user with that email address."]
    }
}
```

### **2. Reset Password**

```http
POST /guest/reset-password
Content-Type: application/x-www-form-urlencoded

token=abc123...
email=user@example.com
password=newpassword123
password_confirmation=newpassword123
```

**Response (Success):**
```
Redirect to /guest/login with success message
```

**Response (Error):**
```json
{
    "errors": {
        "email": ["This password reset token is invalid."]
    }
}
```

---

## 🧑‍💻 Developer Notes

### **Customizing Email Template:**

Edit: `app/Notifications/CustomResetPassword.php`

```php
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Your Custom Subject')
        ->greeting('Custom Greeting')
        ->line('Custom message')
        ->action('Custom Button Text', $resetUrl)
        ->line('Custom footer');
}
```

### **Changing Expire Time:**

Edit: `config/auth.php`

```php
'expire' => 120, // 2 hours instead of 60 minutes
```

### **Custom Validation Rules:**

Edit: `app/Http/Controllers/Guest/AuthController.php`

```php
$request->validate([
    'password' => 'required|min:12|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
]);
```

### **Post-Reset Actions:**

Add custom logic in `resetPassword` method:

```php
function ($user, $password) {
    $user->forceFill([
        'password' => Hash::make($password),
    ])->setRememberToken(Str::random(60));
    
    $user->save();
    
    // Custom: Send notification
    $user->notify(new PasswordChangedNotification());
    
    // Custom: Log activity
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'password_reset',
    ]);
    
    event(new PasswordReset($user));
}
```

---

## 📚 Laravel Password Reset Documentation

Official docs: https://laravel.com/docs/11.x/passwords

---

## ✅ Checklist

### **Before Production:**

- [x] Email configuration tested
- [x] Custom notification created
- [x] UI tested on mobile devices
- [x] Security features verified
- [x] Rate limiting tested
- [x] Token expiration tested
- [x] Error handling complete
- [ ] CAPTCHA added (optional)
- [ ] Password strength meter (optional)
- [ ] Email notification on password change (optional)

### **Monitoring:**

- [ ] Log reset password requests
- [ ] Monitor failed attempts
- [ ] Track token expiration rate
- [ ] Alert on suspicious activity

---

## 🎉 Summary

### **Reset Password System - FULLY FUNCTIONAL! ✅**

**Features:**
✅ Secure token-based reset  
✅ Email notification (Bahasa Indonesia)  
✅ Time-limited tokens (60 min)  
✅ Rate limiting (60 sec throttle)  
✅ Password validation  
✅ Beautiful UI/UX  
✅ Mobile responsive  
✅ Error handling  
✅ Production ready  

**User dapat melakukan reset password dengan mudah dan aman!** 🔐

---

**Last Updated:** November 8, 2025  
**Version:** 1.0  
**Status:** Production Ready ✅
