# Panduan Instalasi dan Konfigurasi Sistem Presensi Kelas

## Perbaikan yang Sudah Diterapkan:

1. ✅ Session save path dikonfigurasi ke `application/sessions/`
2. ✅ Encryption key sudah diisi dengan key aman
3. ✅ Excel import library diperbaiki dengan pengecekan file autoload
4. ✅ Base64 decode ditambahkan error handling
5. ✅ Log threshold diaktifkan (level 1 - Error)
6. ✅ Global XSS filtering dimatikan (deprecated)
7. ✅ File composer.json dibuat untuk instalasi dependencies

## Langkah Instalasi di Server Lokal:

### 1. Install Dependencies via Composer
```bash
composer install
```
Atau jika ingin install PhpSpreadsheet saja:
```bash
composer require phpoffice/phpspreadsheet:^1.29
```

### 2. Konfigurasi Database
Edit file `application/config/database.php`:
- Ubah `username`, `password`, dan `database` sesuai server Anda

### 3. Set Permission Folder
```bash
chmod 755 application/logs/
chmod 755 application/sessions/
```

### 4. Buat Database
Jalankan SQL file yang tersedia (jika ada) atau buat database baru bernama `presensikelas`

### 5. Akses Aplikasi
Buka browser dan akses `http://localhost/nama_folder_anda/`

## Catatan Keamanan:
- Ganti encryption key dengan yang baru di production
- Update database credentials sesuai environment
- Pastikan folder sessions dan logs writable oleh web server
