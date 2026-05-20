# Sistem Presensi SMP - SMPTK Galang Kasih Ubung

Sistem Informasi Presensi Siswa berbasis web yang dibangun menggunakan **CodeIgniter 3** dan **MySQL**. Sistem ini dirancang untuk mengelola presensi siswa, jadwal pelajaran, approval presensi, dan laporan kepegawaian di lingkungan SMP.

## 🚀 Fitur Utama

### 1. Manajemen Pengguna & Role

Sistem ini memiliki 4 role pengguna dengan hak akses berbeda:
- **Admin**: Akses penuh ke master data (Tahun Ajaran, Kelas, Mata Pelajaran, Siswa, Guru, Jadwal, Penempatan Siswa) dan import data.
- **Guru**: Melihat jadwal mengajar, input presensi siswa, dan rekap laporan presensi.
- **Siswa**: Melihat jadwal pelajaran sesuai kelasnya dan riwayat presensi pribadi.
- **Kepala Sekolah**: Approval presensi, melihat laporan, dan log approval.

### 2. Tahun Ajaran Aktif di Header

- Tahun ajaran aktif ditampilkan di bagian header (navbar) setiap halaman.
- Format tampilan: `T.A. 2025/2026 Semester 1`
- Data tahun ajaran diambil dari model `M_dashboard` dan tersedia secara global melalui `MY_Controller`.

### 3. Jadwal Pelajaran

- **Guru**: Menu "Jadwal Mengajar" di sidebar untuk melihat jadwal mengajar per hari (Senin-Sabtu).
- **Siswa**: Menu "Jadwal Pelajaran" di sidebar untuk melihat jadwal pelajaran sesuai kelasnya.
- Jadwal ditampilkan dalam format accordion per hari dengan detail: Jam, Mata Pelajaran, Guru Pengampu, dan Ruangan/Kelas.

### 4. Presensi & Approval

- **Guru**: Input presensi siswa (Hadir, Izin, Sakit, Alpa) untuk kelas yang diajar.
- **Kepala Sekolah**: Approval atau penolakan terhadap presensi yang diinput guru.
- **Log Approval**: Riwayat semua aktivitas approval oleh kepala sekolah.

### 5. Dashboard Interaktif

Setiap role memiliki dashboard dengan statistik yang relevan:
- **Admin**: Total siswa, guru, kelas, presensi hari ini, dan approval pending.
- **Guru**: Jumlah jadwal hari ini dan total presensi bulan ini.
- **Siswa**: Statistik kehadiran pribadi (Hadir, Izin, Sakit, Alpa) bulan ini.
- **Kepala Sekolah**: Approval pending, presensi hari ini, total siswa dan guru.

## 🛠️ Teknologi yang Digunakan

- **Framework**: CodeIgniter 3.x
- **Bahasa Pemrograman**: PHP 7.4+
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5.3, Font Awesome 6, jQuery, DataTables 1.13
- **Template Engine**: Native PHP Views dengan custom helper

## 📋 Persyaratan Server

- Web Server (Apache/Nginx)
- PHP versi 7.4 atau lebih tinggi
- MySQL versi 5.7 atau lebih tinggi / MariaDB 10.3+
- Ekstensi PHP: `mysqli`, `gd`, `curl`, `mbstring`

## ⚙️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal:

### 1. Clone Repository

```bash
git clone <url-repository-anda>
cd <nama-folder-proyek>


2. Konfigurasi Database

    Buat database baru di MySQL (misalnya: presensi_smp).

    Impor file SQL yang terdapat dalam folder database/:
    bash

    mysql -u root -p presensi_smp < database/presensi_smp.sql

3. Konfigurasi Aplikasi

Buka file application/config/database.php dan sesuaikan konfigurasi database Anda:
php

$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'presensi_smp',
    'dbdriver' => 'mysqli',
    // ... konfigurasi lainnya
);

File konfigurasi database sudah diset dengan nama database presensi_smp.
4. Izin Folder

Pastikan folder berikut memiliki izin tulis (writable):

    application/cache/

    application/logs/

    assets/uploads/

5. Akses Aplikasi

Buka browser dan akses aplikasi melalui URL yang telah dikonfigurasi. Base URL otomatis terdeteksi berdasarkan host dan script name.
👤 Akun Default untuk Testing

Berikut adalah akun default yang bisa digunakan untuk pengujian awal (sesuaikan dengan data di database jika berbeda):
Role	Username	Password	Keterangan
Admin	admin	lihat di database	Akses Penuh ke Master Data
Guru	lihat di tb_user	hashed	Input Presensi & Lihat Jadwal
Siswa	lihat di tb_user	hashed	Lihat Jadwal & Riwayat
Kepsek	lihat di tb_user	hashed	Approval & Laporan

    Catatan: Password disimpan dalam bentuk hash di database. Untuk testing, silakan cek langsung data di tabel tb_user atau reset password melalui query SQL jika diperlukan.

📂 Struktur Direktori Utama
text

/application              # Logika aplikasi (Controllers, Models, Views)
    /controllers          # Controller berdasarkan role
        /admin            # Controller untuk Admin (Siswa, Guru, Kelas, dll)
        /guru             # Controller untuk Guru (Jadwal, Presensi, Rekap)
        /siswa            # Controller untuk Siswa (Jadwal, Riwayat)
        /kepsek           # Controller untuk Kepsek (Approval, Laporan, Log)
        Auth.php          # Login/Logout
        Dashboard.php     # Dashboard berbasis role
        Profil.php        # Manajemen profil user
    /models               # Model untuk interaksi database
        M_auth.php        # Autentikasi
        M_dashboard.php   # Statistik & tahun ajaran aktif
        /admin            # Model khusus admin
        /guru             # Model khusus guru
        /siswa            # Model khusus siswa
        /kepsek           # Model khusus kepsek
    /views                # Tampilan berdasarkan role
        /admin            # View untuk admin
        /guru             # View untuk guru
        /siswa            # View untuk siswa
        /kepsek           # View untuk kepsek
        /auth             # Login page
        /templates        # Header, Sidebar, Footer
    /config               # Konfigurasi aplikasi
    /core                 # MY_Controller (base controller)
    /helpers              # custom_helper.php (fungsi umum)
/assets                   # File statis (CSS, JS, Gambar)
    /css                  # Custom CSS
    /js                   # Custom JavaScript
    /img                  # Gambar default
/database                 # Script SQL untuk instalasi

🔑 Fitur Teknis Penting
MY_Controller (Base Controller)

Semua controller extends MY_Controller yang menyediakan:

    Cek autentikasi (wajib login)

    Idle timeout (30 menit)

    Authorization berdasarkan role

    Load tahun ajaran aktif secara global

    Helper functions: render_template(), json_response(), dll

Custom Helper Functions

File application/helpers/custom_helper.php menyediakan fungsi-fungsi umum:

    cek_akses() - Cek akses berdasarkan role

    show_403() - Tampilkan error 403

    log_aktivitas() - Log aktivitas user

    tanggal_indo() - Format tanggal Indonesia

    badge_role(), badge_presensi(), badge_approval() - Badge HTML

    encrypt_id(), decrypt_id() - Enkripsi ID untuk URL

    Dan lainnya...

Tahun Ajaran Aktif

Tahun ajaran aktif disimpan di tb_tahun_ajaran dengan kolom status_aktif = 1.
Data ini dimuat di MY_Controller::__construct() dan tersedia di semua view sebagai $tahun_ajaran.
🐛 Troubleshooting
Error 403 Forbidden

    Pastikan user sudah login

    Cek role user apakah memiliki akses ke controller tersebut

Tahun Ajaran Tidak Muncul

    Pastikan ada data di tabel tb_tahun_ajaran dengan status_aktif = 1

    Jika tidak ada, sistem akan mengambil tahun ajaran terbaru

Jadwal Kosong

    Untuk Guru: Pastikan ada data di tb_jadwal dengan id_guru sesuai user

    Untuk Siswa: Pastikan siswa sudah ditempatkan di kelas (tb_siswa.id_kelas)

🤝 Kontribusi

Kontribusi sangat dihargai! Silakan buat fork dari repository ini, buat branch baru untuk fitur atau perbaikan bug, dan ajukan Pull Request.
📄 Lisensi

Proyek ini dilisensikan di bawah MIT License.
📞 Kontak & Dukungan

Jika menemukan bug atau memiliki pertanyaan, silakan hubungi tim pengembang atau buat issue pada repository ini.

Sistem Presensi SMP - SMPTK Galang Kasih Ubung
Dibuat dengan ❤️ untuk kemajuan pendidikan.
