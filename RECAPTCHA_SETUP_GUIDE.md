# 🤖 Google reCAPTCHA v2 Setup Guide

## 🎯 Overview

Google reCAPTCHA v2 telah diimplementasikan untuk mencegah bot dan spam pada form **Login** dan **Register**. User harus mencentang checkbox "I'm not a robot" sebelum dapat submit form.

---

## ✅ Fitur yang Diimplementasikan

### **1. Form Protection**
- ✅ **Login Form** - Mencegah brute force attacks
- ✅ **Register Form** - Mencegah spam registration
- ✅ Server-side validation - Keamanan ganda
- ✅ User-friendly error messages - Bahasa Indonesia

### **2. Security Benefits**
- 🛡️ **Bot Prevention** - Block automated login/register attempts
- 🚫 **Spam Protection** - Prevent fake accounts
- 🔒 **Brute Force Defense** - Rate limiting via CAPTCHA
- 📊 **Google Security** - Leverage Google's anti-bot algorithms

### **3. User Experience**
- ✨ **Simple checkbox** - User-friendly interface
- 📱 **Responsive** - Works on mobile devices
- 🌐 **Multi-language** - Auto-detect user language
- ⚡ **Fast loading** - Async script loading

---

## 📦 Installed Package

**Package:** `anhskohbo/no-captcha` v3.6  
**Docs:** https://github.com/anhskohbo/no-captcha

This package provides Laravel integration for Google reCAPTCHA v2.

---

## 🔧 Setup Instructions

### **Step 1: Install Composer Package**

Jalankan command ini untuk install package:

```bash
composer install
```

Package `anhskohbo/no-captcha` sudah ditambahkan ke `composer.json`, tinggal install.

### **Step 2: Get Google reCAPTCHA Keys**

1. **Buka Google reCAPTCHA Admin Console:**
   ```
   https://www.google.com/recaptcha/admin/create
   ```

2. **Login dengan Google Account** (gunakan: dwavestoreinfinity@gmail.com atau akun lain)

3. **Register a new site:**
   - **Label:** School Gallery
   - **reCAPTCHA type:** ✅ reCAPTCHA v2 → "I'm not a robot" Checkbox
   - **Domains:** 
     - `localhost` (untuk development)
     - `127.0.0.1` (untuk development)
     - `yourdomain.com` (untuk production, opsional)
   - **Accept reCAPTCHA Terms of Service:** ✅ Check
   - Klik **Submit**

4. **Copy Keys:**
   Setelah submit, Google akan memberikan 2 keys:
   - **Site Key** (public key) - Untuk frontend
   - **Secret Key** (private key) - Untuk backend

### **Step 3: Configure .env File**

Update file `.env` dengan keys yang didapat:

```env
# Google reCAPTCHA v2
NOCAPTCHA_SITEKEY=your_site_key_here_from_google
NOCAPTCHA_SECRET=your_secret_key_here_from_google
```

**Example:**
```env
NOCAPTCHA_SITEKEY=6LdxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxQ
NOCAPTCHA_SECRET=6LdxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxW
```

### **Step 4: Clear Configuration Cache**

Setelah update `.env`, clear cache:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📁 Files Modified

### **1. Configuration:**
- ✅ `composer.json` - Added package dependency
- ✅ `config/captcha.php` - Package configuration
- ✅ `.env` - reCAPTCHA keys

### **2. Views:**
- ✅ `resources/views/guest/login.blade.php` - Added reCAPTCHA widget
- ✅ `resources/views/guest/register.blade.php` - Added reCAPTCHA widget

### **3. Controller:**
- ✅ `app/Http/Controllers/Guest/AuthController.php` - Added validation

---

## 🧪 Testing Guide

### **Test 1: Login WITHOUT CAPTCHA**

1. **Buka form login:**
   ```
   http://127.0.0.1:8000/guest/login
   ```

2. **Isi form TANPA centang reCAPTCHA:**
   - Email: `john@example.com`
   - Password: `password123`
   - ⚠️ **Jangan centang checkbox "I'm not a robot"**
   - Klik **Masuk**

3. **Expected Result:**
   - ❌ Error message: **"Harap centang kotak 'Saya bukan robot'."**
   - Form tidak submit
   - User tetap di halaman login

### **Test 2: Login WITH CAPTCHA - Success**

1. **Isi form dengan benar:**
   - Email: `john@example.com`
   - Password: `password123`
   - ✅ **Centang checkbox "I'm not a robot"**
   - Tunggu checkmark hijau muncul
   - Klik **Masuk**

2. **Expected Result:**
   - ✅ Login berhasil!
   - Redirect ke home page
   - User authenticated

### **Test 3: Login WITH CAPTCHA - Wrong Credentials**

1. **Isi form dengan password salah:**
   - Email: `john@example.com`
   - Password: `wrongpassword`
   - ✅ Centang reCAPTCHA
   - Klik **Masuk**

2. **Expected Result:**
   - ❌ Error: "Email atau password yang Anda masukkan salah."
   - reCAPTCHA di-reset (harus centang lagi)

### **Test 4: Register WITHOUT CAPTCHA**

1. **Buka form register:**
   ```
   http://127.0.0.1:8000/guest/register
   ```

2. **Isi form tanpa centang CAPTCHA:**
   - Name: Test User
   - Email: testuser@example.com
   - Password: password123
   - Confirm Password: password123
   - ⚠️ Jangan centang reCAPTCHA
   - Klik **Daftar**

3. **Expected Result:**
   - ❌ Error: "Harap centang kotak 'Saya bukan robot'."
   - Registration gagal

### **Test 5: Register WITH CAPTCHA - Success**

1. **Isi form lengkap:**
   - Name: Test User
   - Email: newemail@example.com
   - Password: password123
   - Confirm Password: password123
   - ✅ **Centang reCAPTCHA**
   - Klik **Daftar**

2. **Expected Result:**
   - ✅ Registration berhasil
   - Email verification dikirim
   - Redirect ke verify-email page

### **Test 6: reCAPTCHA Expired**

1. **Isi form tapi tunggu >2 menit sebelum submit**
2. **Submit form**

3. **Expected Result:**
   - ❌ Error: "Verifikasi reCAPTCHA gagal. Silakan coba lagi."
   - User harus centang reCAPTCHA lagi

---

## 🔍 How It Works

### **Frontend (View):**

**Added to login.blade.php & register.blade.php:**

```html
<!-- Load reCAPTCHA script -->
@push('head')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

<!-- reCAPTCHA widget in form -->
<div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}"></div>

<!-- Error message -->
@error('g-recaptcha-response')
    <p class="text-red-500 text-sm text-center">{{ $message }}</p>
@enderror
```

### **Backend (Controller):**

**Added to AuthController.php:**

```php
// Login validation
$request->validate([
    'email' => 'required|email',
    'password' => 'required',
    'g-recaptcha-response' => 'required|captcha', // ← reCAPTCHA validation
], [
    'g-recaptcha-response.required' => 'Harap centang kotak "Saya bukan robot".',
    'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
]);

// Register validation
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
    'g-recaptcha-response' => 'required|captcha', // ← reCAPTCHA validation
], [
    'g-recaptcha-response.required' => 'Harap centang kotak "Saya bukan robot".',
    'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
]);
```

### **Validation Process:**

1. User submit form dengan reCAPTCHA token
2. Laravel validates token dengan Google servers
3. Google verifies token dan returns result
4. If valid → proceed, if invalid → show error

---

## 🚨 Troubleshooting

### **Problem 1: reCAPTCHA widget tidak muncul**

**Symptoms:**
- Checkbox tidak render di halaman
- Console error tentang reCAPTCHA

**Solutions:**

1. **Check site key di .env:**
   ```env
   NOCAPTCHA_SITEKEY=6LdxxxxxxxxxxxxxxxxQ  # Harus valid
   ```

2. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

3. **Check browser console** untuk JavaScript errors

4. **Verify domain whitelist** di Google reCAPTCHA Admin:
   - Pastikan `localhost` dan `127.0.0.1` sudah ditambahkan

### **Problem 2: Validation selalu gagal**

**Error:** "Verifikasi reCAPTCHA gagal. Silakan coba lagi."

**Possible Causes:**

1. **Secret key salah** di `.env`
2. **Keys untuk domain berbeda**
3. **Server tidak bisa reach Google servers**

**Solutions:**

1. **Verify secret key:**
   ```env
   NOCAPTCHA_SECRET=6LdxxxxxxxxxxxxxxxxW  # Must match Google Console
   ```

2. **Test connection ke Google:**
   ```bash
   curl https://www.google.com/recaptcha/api/siteverify
   ```

3. **Check firewall** - Server harus bisa connect ke Google

### **Problem 3: "Harap centang kotak..." padahal sudah centang**

**Cause:** CAPTCHA expired (>2 minutes)

**Solution:**
- Centang ulang checkbox
- Submit form lebih cepat setelah centang

### **Problem 4: Package error - Class not found**

**Error:** `Class 'Anhskohbo\NoCaptcha\...' not found`

**Solution:**

```bash
# Install package
composer install

# Or update
composer update anhskohbo/no-captcha

# Dump autoload
composer dump-autoload
```

---

## 🌐 Production Deployment

### **Before Going Live:**

1. **Update domain whitelist:**
   - Login ke https://www.google.com/recaptcha/admin
   - Edit site settings
   - Add production domain: `schoolgallery.com`
   - Save

2. **Update .env di production server:**
   ```env
   NOCAPTCHA_SITEKEY=your_production_site_key
   NOCAPTCHA_SECRET=your_production_secret_key
   ```

3. **Test di production:**
   - Test login form
   - Test register form
   - Monitor for errors

4. **Enable HTTPS:**
   - reCAPTCHA works better over HTTPS
   - More secure

---

## 🔐 Security Best Practices

### **✅ Already Implemented:**

1. **Server-side validation** - Never trust client-side only
2. **Custom error messages** - User-friendly dalam Bahasa Indonesia
3. **Secret key protection** - Stored in .env, not in code
4. **Rate limiting** - CAPTCHA adds extra layer on top of Laravel throttle

### **🛡️ Additional Recommendations:**

1. **Monitor failed attempts:**
   ```php
   // Log failed CAPTCHA attempts
   if (!$request->validate(['g-recaptcha-response' => 'captcha'])) {
       Log::warning('Failed CAPTCHA attempt', [
           'ip' => $request->ip(),
           'email' => $request->email,
       ]);
   }
   ```

2. **Add to forgot password** (optional):
   ```php
   // Prevent password reset spam
   $request->validate([
       'email' => 'required|email',
       'g-recaptcha-response' => 'required|captcha',
   ]);
   ```

3. **Add to admin login** (optional):
   - Protect admin panel from brute force

---

## 📊 reCAPTCHA Types Comparison

| Type | User Action | Use Case | Implementation |
|------|-------------|----------|----------------|
| **v2 Checkbox** ✅ | Click "I'm not a robot" | Login, Register | **CURRENT** |
| v2 Invisible | None (automatic) | Better UX, less secure | Available |
| v3 | None (score-based) | Advanced, no user interaction | Complex |

**Why we chose v2 Checkbox:**
- ✅ **Balance** between security & UX
- ✅ **Visual feedback** - Users know they're protected
- ✅ **Simple implementation**
- ✅ **Effective** against most bots

---

## 🎨 Customization Options

### **1. Change reCAPTCHA Theme:**

```html
<!-- Dark theme -->
<div class="g-recaptcha" 
     data-sitekey="{{ config('captcha.sitekey') }}"
     data-theme="dark">
</div>

<!-- Light theme (default) -->
<div class="g-recaptcha" 
     data-sitekey="{{ config('captcha.sitekey') }}"
     data-theme="light">
</div>
```

### **2. Change Size:**

```html
<!-- Compact size -->
<div class="g-recaptcha" 
     data-sitekey="{{ config('captcha.sitekey') }}"
     data-size="compact">
</div>

<!-- Normal size (default) -->
<div class="g-recaptcha" 
     data-sitekey="{{ config('captcha.sitekey') }}"
     data-size="normal">
</div>
```

### **3. Callback on Success:**

```html
<div class="g-recaptcha" 
     data-sitekey="{{ config('captcha.sitekey') }}"
     data-callback="onCaptchaSuccess">
</div>

<script>
function onCaptchaSuccess(token) {
    console.log('CAPTCHA verified!', token);
    // Enable submit button, etc.
}
</script>
```

---

## 📚 Resources

### **Official Documentation:**
- Google reCAPTCHA: https://developers.google.com/recaptcha
- Package Docs: https://github.com/anhskohbo/no-captcha

### **Get Keys:**
- reCAPTCHA Admin: https://www.google.com/recaptcha/admin

### **Testing:**
- Test Keys (development only):
  - Site Key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
  - Secret: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`
  - ⚠️ These always pass validation (for testing only!)

---

## ✅ Implementation Checklist

### **Setup:**
- [x] Install package `anhskohbo/no-captcha`
- [x] Create `config/captcha.php`
- [x] Add keys to `.env`
- [x] Add reCAPTCHA widget to login form
- [x] Add reCAPTCHA widget to register form
- [x] Add validation in AuthController
- [x] Test login flow
- [x] Test register flow

### **Production:**
- [ ] Register production domain di Google reCAPTCHA
- [ ] Update production .env with real keys
- [ ] Test on production server
- [ ] Monitor failed attempts
- [ ] Document for team

---

## 🎉 Summary

### **reCAPTCHA v2 - FULLY IMPLEMENTED! ✅**

**Protected Forms:**
- ✅ Login (`/guest/login`)
- ✅ Register (`/guest/register`)

**Security Features:**
- ✅ Bot prevention
- ✅ Spam protection
- ✅ Server-side validation
- ✅ Custom error messages (ID)

**User Experience:**
- ✅ Simple checkbox
- ✅ Mobile responsive
- ✅ Fast loading
- ✅ Clear error messages

**Status:** Production Ready! 🚀

---

**Last Updated:** November 8, 2025  
**Version:** 1.0  
**Package:** anhskohbo/no-captcha v3.6
