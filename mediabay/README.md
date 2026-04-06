# 🎥 Mediabay — Platform Jasa Kreatif

Website fullstack PHP MVC untuk layanan Photography, Videography, dan Live Streaming.

---

## 📋 Persyaratan

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache dengan mod_rewrite aktif
- Ekstensi PHP: PDO, PDO_MySQL, fileinfo, curl

---

## 🚀 Instalasi

### 1. Clone / Extract

```bash
# Letakkan folder mediabay di direktori web server
cp -r mediabay/ /var/www/html/
# atau di XAMPP:
cp -r mediabay/ C:/xampp/htdocs/
```

### 2. Buat Database

```bash
mysql -u root -p < config/schema.sql
```

Atau import via phpMyAdmin:
- Buat database baru bernama `mediabay`
- Import file `config/schema.sql`

### 3. Konfigurasi

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mediabay');
define('DB_USER', 'root');
define('DB_PASS', 'password_anda');

define('BASE_URL', 'http://localhost/mediabay/public');
// atau jika di root server:
// define('BASE_URL', 'http://localhost');

// WhatsApp (Fonnte API)
define('WA_TOKEN', 'token_fonnte_anda');
define('WA_ADMIN_NUMBER', '628xxxxxxxxxx');
```

### 4. Set Permission Upload Folder

```bash
chmod -R 755 public/uploads/
chmod -R 755 public/assets/
```

### 5. Aktifkan mod_rewrite (Apache)

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

Di `httpd.conf` atau virtualhost, pastikan:
```apache
AllowOverride All
```

### 6. Akses Website

- **Frontend:** `http://localhost/mediabay/public/`
- **Admin Panel:** `http://localhost/mediabay/public/admin`

**Default Admin Login:**
- Email: `admin@mediabay.id`
- Password: `password`

---

## 📁 Struktur Direktori

```
mediabay/
├── app/
│   ├── controllers/        # HomeController, AuthController, dll
│   ├── models/             # UserModel, BookingModel, dll
│   ├── services/           # WhatsAppService, BookingService
│   └── views/              # Template PHP
│       ├── layouts/        # main.php, admin.php
│       ├── home/           # Halaman publik
│       ├── auth/           # Login, Register
│       ├── user/           # Dashboard user
│       ├── admin/          # Panel admin
│       └── partials/       # Navbar, footer, sidebar
├── config/
│   ├── database.php        # Konfigurasi DB & konstanta
│   └── schema.sql          # SQL schema + seed data
├── core/
│   ├── Router.php          # Custom router
│   ├── Controller.php      # Base controller
│   ├── Model.php           # Base model (OOP)
│   └── Database.php        # PDO singleton
├── routes/
│   └── web.php             # Definisi semua route
├── public/
│   ├── assets/
│   │   ├── css/main.css    # Design system lengkap
│   │   └── js/main.js      # Interaksi & animasi
│   ├── uploads/            # File upload user/admin
│   ├── index.php           # Entry point
│   └── .htaccess
└── .htaccess               # Redirect ke public/
```

---

## 🔧 Konfigurasi WhatsApp (Fonnte)

1. Daftar di [fonnte.com](https://fonnte.com)
2. Hubungkan nomor WhatsApp Anda
3. Copy token API
4. Isi di `config/database.php`:
   ```php
   define('WA_TOKEN', 'xxx');
   define('WA_ADMIN_NUMBER', '628xxx');
   ```

---

## 👤 Akun Default

| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin | admin@mediabay.id   | password |

**⚠️ Ganti password default setelah instalasi!**

---

## 🎨 Fitur Utama

### Frontend
- ✅ Hero slider dengan animasi
- ✅ Navbar glassmorphism + blur saat scroll
- ✅ Dropdown menu animasi smooth
- ✅ Overlay blur saat dropdown aktif
- ✅ Scroll reveal animations (fade, slide, scale)
- ✅ Mini calendar booking preview
- ✅ Responsive mobile-first
- ✅ Lazy load gambar
- ✅ Button ripple effect

### Booking System
- ✅ Request-based (tidak auto-accept)
- ✅ Status: REQUESTED → APPROVED/REJECTED
- ✅ Upload bukti DP manual
- ✅ Satu tanggal bisa banyak booking
- ✅ Kode booking unik (MB + tanggal + ID)

### WhatsApp Notifikasi
- ✅ Booking dibuat → notif user + admin
- ✅ Pembayaran diupload → notif user + admin
- ✅ Booking disetujui → notif user
- ✅ Booking ditolak → notif user
- ✅ Log semua notifikasi di database

### Admin Panel
- ✅ Dashboard statistik
- ✅ CRUD Kategori, Layanan, Paket
- ✅ CRUD Carousel / Hero
- ✅ CRUD Berita & Portfolio
- ✅ Approve / Reject booking dengan modal
- ✅ Verifikasi pembayaran

---

## 🛡️ Keamanan

- Password di-hash dengan bcrypt
- Input di-sanitize & di-validate server-side
- Session-based authentication
- CSRF protection via session
- File upload validation (tipe & ukuran)
- PDO prepared statements (anti SQL injection)

---

## 📞 Support

Hubungi kami di WhatsApp: +62 812 3456 7890
Email: hello@mediabay.id
