# Presensi Kelas 2

Sistem manajemen presensi kelas berbasis web yang dibangun dengan PHP.

## 📋 Deskripsi

Aplikasi ini dirancang untuk memudahkan proses pencatatan dan manajemen presensi siswa/kelas secara digital. Sistem ini menyediakan antarmuka web yang user-friendly untuk admin/guru dalam mengelola data kehadiran.

## 🚀 Fitur Utama

- Manajemen data siswa
- Pencatatan presensi harian
- Laporan kehadiran
- Migrasi database session
- Interface web yang responsif

## 📁 Struktur Proyek

```
/
├── application/          # Direktori utama aplikasi (CodeIgniter)
├── assets/              # File statis (CSS, JS, Images)
├── index.php            # Entry point aplikasi
├── composer.json        # Dependensi PHP
├── database.sql         # Schema database utama
├── session_migration.sql # Migrasi session database
└── .htaccess           # Konfigurasi Apache
```

## 🛠️ Teknologi

- **Backend:** PHP
- **Database:** MySQL/MariaDB
- **Framework:** CodeIgniter (terlihat dari struktur `application/`)
- **Package Manager:** Composer
- **Web Server:** Apache (dengan `.htaccess`)

## 📦 Instalasi

1. Clone repository ini:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Install dependensi PHP:
   ```bash
   composer install
   ```

3. Import database:
   ```bash
   mysql -u username -p database_name < database.sql
   mysql -u username -p database_name < session_migration.sql
   ```

4. Konfigurasi web server (Apache) untuk mengarah ke root proyek

5. Akses aplikasi melalui browser

## ⚙️ Konfigurasi

Pastikan untuk mengkonfigurasi:
- Database connection di folder `application/config/`
- Base URL aplikasi
- Permission folder untuk session dan cache

## 📝 License

Silakan sesuaikan dengan license yang digunakan proyek ini.

## 👥 Kontributor

Tambahkan daftar kontributor di sini.

---

**Catatan:** File `presensikelas2.zip` mungkin berisi backup atau versi sebelumnya dari aplikasi ini.
