# Fitur Login Guest untuk School Gallery

## Overview
Fitur login guest memungkinkan pengunjung website untuk membuat akun dan login ke sistem. Fitur ini terpisah dari sistem admin yang sudah ada.

## Fitur yang Tersedia

### 1. Registrasi Guest
- **URL**: `/guest/register`
- **Method**: GET, POST
- **Fitur**:
  - Form registrasi dengan validasi
  - Password minimal 8 karakter
  - Konfirmasi password
  - Validasi email unik
  - Auto login setelah registrasi berhasil

### 2. Login Guest
- **URL**: `/guest/login`
- **Method**: GET, POST
- **Fitur**:
  - Form login dengan validasi
  - Remember me functionality
  - Show/hide password
  - Redirect ke halaman sebelumnya setelah login

### 3. Logout Guest
- **URL**: `/guest/logout`
- **Method**: POST
- **Fitur**:
  - Logout dan clear session
  - Redirect ke halaman home

## File yang Dibuat/Dimodifikasi

### Controller
- `app/Http/Controllers/Guest/AuthController.php` - Controller untuk handle authentication guest

### Views
- `resources/views/guest/login.blade.php` - Halaman login guest
- `resources/views/guest/register.blade.php` - Halaman registrasi guest

### Routes
- `routes/web.php` - Menambahkan routes untuk guest authentication

### Navigation
- `resources/views/layouts/header.blade.php` - Menambahkan tombol login/logout di navigation

### Database
- `database/seeders/UserSeeder.php` - Seeder untuk membuat user contoh
- `database/seeders/DatabaseSeeder.php` - Update untuk menjalankan UserSeeder

## User Contoh untuk Testing

Setelah menjalankan seeder, tersedia user contoh:

| Email | Password | Nama |
|-------|----------|------|
| john@example.com | password123 | John Doe |
| jane@example.com | password123 | Jane Smith |
| admin@example.com | password123 | Admin User |

## Cara Menggunakan

1. **Registrasi**:
   - Buka `/guest/register`
   - Isi form dengan data yang valid
   - Submit form
   - Otomatis login setelah registrasi berhasil

2. **Login**:
   - Buka `/guest/login` atau klik tombol "Login" di navigation
   - Masukkan email dan password
   - Klik "Masuk"
   - Redirect ke halaman sebelumnya atau home

3. **Logout**:
   - Klik nama user di navigation (desktop) atau menu mobile
   - Klik "Logout" dari dropdown menu

## Security Features

- Password hashing menggunakan Laravel Hash
- CSRF protection pada semua form
- Session management yang aman
- Input validation dan sanitization
- Remember me functionality dengan secure cookies

## Integration dengan Sistem Existing

- Menggunakan model `User` yang sudah ada
- Terintegrasi dengan sistem navigation yang sudah ada
- Tidak mengganggu sistem admin yang sudah ada
- Menggunakan middleware `guest` dan `auth` Laravel default

## Update Terbaru - Authentication Required Features

### 🔒 Fitur yang Memerlukan Login

Sekarang fitur-fitur berikut memerlukan login terlebih dahulu:

1. **Like/Unlike Gallery**
   - Tombol "Suka" akan redirect ke halaman login jika user belum login
   - Hanya user yang sudah login yang bisa memberikan like

2. **Comment Gallery**
   - Form komentar hanya muncul untuk user yang sudah login
   - User yang belum login akan melihat pesan "Login Diperlukan" dengan tombol login

3. **Comment News**
   - Form komentar berita hanya muncul untuk user yang sudah login
   - Auto-fill nama user dari data login

4. **Contact Form**
   - Form kontak hanya bisa diakses oleh user yang sudah login
   - Auto-fill nama dan email dari data user yang login

### 🛠️ Perbaikan yang Dilakukan

1. **Fixed `$schoolProfile` undefined error**
   - Menambahkan View Composer di `AppServiceProvider` untuk share `$schoolProfile` ke semua view
   - Sekarang semua halaman memiliki akses ke data sekolah

2. **Updated Routes Protection**
   - Memindahkan routes like, comment, dan contact ke middleware `auth`
   - Routes yang memerlukan login sekarang terpisah dari routes public

3. **Enhanced UI/UX**
   - Menambahkan pesan "Login Diperlukan" yang menarik
   - Tombol login yang mudah diakses dari setiap fitur yang memerlukan login
   - Auto-fill data user di form yang memerlukan login

### 📋 Routes yang Memerlukan Login

```php
// Protected Routes (require login)
Route::middleware(['auth', 'track.visits'])->group(function () {
    // Gallery interactions
    Route::post('/gallery/{id}/like', [HomeController::class, 'likeGallery'])->name('gallery.like');
    Route::post('/gallery/{id}/unlike', [HomeController::class, 'unlikeGallery'])->name('gallery.unlike');
    Route::post('/gallery/{id}/comment', [HomeController::class, 'commentGallery'])->name('gallery.comment');
    
    // News interactions
    Route::post('/news/{slug}/comment', [HomeController::class, 'commentNews'])->name('news.comment');
    
    // Contact form submission
    Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
});
```

## Next Steps (Opsional)

Beberapa fitur yang bisa ditambahkan di masa depan:
- Email verification untuk registrasi
- Password reset functionality
- User profile management
- Role-based permissions untuk guest users
- Social login (Google, Facebook, etc.)
- Email notification untuk admin ketika ada pesan kontak baru
