# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

## Sistem Informasi Presensi Siswa Berbasis Web
### SMPTK Galang Kasih Ubung

---

| Dokumen | Informasi |
|---------|-----------|
| **Judul** | Software Requirements Specification (SRS) |
| **Institusi** | SMPTK Galang Kasih Ubung |
| **Versi** | Final v3.1 |
| **Tanggal** | 20 Mei 2026 |
| **Status** | Final |
| **Framework** | CodeIgniter 3 |
| **UI Framework** | Bootstrap 5 |
| **PDF Engine** | dompdf 2.x |

---

## Daftar Isi

1. Pendahuluan
2. Deskripsi Umum Sistem
3. Spesifikasi Teknis Implementasi
4. Kebutuhan Fungsional Spesifik
5. Kebutuhan Antarmuka Pengguna (UI/UX)
6. Kebutuhan Non-Fungsional
7. Kebutuhan Keamanan Sistem
8. Perancangan Basis Data
9. Matriks Pelacakan Kebutuhan

---

## 1. Pendahuluan

### 1.1 Tujuan
Dokumen ini mendefinisikan kebutuhan perangkat lunak untuk Sistem Informasi Presensi Siswa Berbasis Web di SMPTK Galang Kasih Ubung.

### 1.2 Ruang Lingkup
Sistem menggantikan proses presensi manual menjadi sistem digital terintegrasi yang dapat diakses melalui web browser. Meliputi manajemen data master, presensi harian oleh guru, approval kepala sekolah, ekspor PDF menggunakan dompdf, import Excel/CSV, pemantauan jadwal dan riwayat, log aktivitas audit, dan pengelolaan profil pengguna.

### 1.3 Definisi dan Singkatan

| Istilah | Definisi |
|---------|----------|
| SRS | Software Requirements Specification |
| CI3 | CodeIgniter 3 |
| BS5 | Bootstrap 5 |
| dompdf | Library PHP untuk konversi HTML ke PDF |
| PhpSpreadsheet | Library PHP untuk membaca/menulis Excel/CSV |
| CSRF | Cross-Site Request Forgery |
| XSS | Cross-Site Scripting |
| Datatable SS | DataTables Server-Side Processing (10 item/halaman) |
| Siswa & Wali | Aktor gabungan siswa dan orang tua dengan satu akun login |

### 1.4 Referensi
1. Dokumen Analisis dan Perancangan SMPTK Galang Kasih Ubung
2. SOP Presensi Sekolah
3. Dokumentasi CodeIgniter 3, Bootstrap 5, DataTables, dompdf, PhpSpreadsheet

### 1.5 Ikhtisar Dokumen
Sembilan bab: Pendahuluan, Deskripsi Umum, Spesifikasi Teknis, Kebutuhan Fungsional, UI/UX, Non-Fungsional, Keamanan, Basis Data, Traceability Matrix.

---

## 2. Deskripsi Umum Sistem

### 2.1 Perspektif Produk
Aplikasi web-based dengan arsitektur MVC CodeIgniter 3, tanpa instalasi aplikasi tambahan di perangkat pengguna.

### 2.2 Fungsi Produk Utama
1. Autentikasi multi-peran dengan session dan enkripsi password
2. Manajemen master data oleh Admin
3. Pengaturan penempatan siswa ke kelas dengan tracking riwayat kelas
4. Presensi digital dengan input materi pelajaran
5. Approval workflow untuk Izin/Sakit
6. Rekapitulasi dan pelaporan otomatis
7. Ekspor laporan ke PDF menggunakan dompdf (Kepala Sekolah dan Guru)
8. Import data siswa/guru dari Excel/CSV menggunakan PhpSpreadsheet
9. Pemantauan jadwal dan riwayat oleh Siswa & Wali
10. Dashboard statistik untuk Admin
11. Log aktivitas audit untuk aksi kritis
12. **Pengelolaan profil pengguna: edit data pribadi dan ubah foto profil yang tampil di sidebar**

### 2.3 Karakteristik Pengguna (Aktor)

| No | Aktor | Peran | Hak Akses |
|:--:|:------|:------|:----------|
| 1 | Admin | Pengelola sistem | CRUD master data, penempatan siswa ke kelas, import Excel/CSV, monitoring laporan, dashboard statistik, edit profil sendiri |
| 2 | Guru | Pengajar | Jadwal mengajar, input presensi + materi, rekap presensi, ekspor PDF rekap kelas, input sebagai guru pengganti, **edit profil sendiri** |
| 3 | Kepala Sekolah | Validator | Approval presensi, dashboard statistik, laporan filter, ekspor PDF laporan, monitoring log approval, **edit profil sendiri** |
| 4 | Siswa & Wali | Pemantau | Lihat jadwal kelas, riwayat presensi (read-only), **edit profil sendiri** |

### 2.4 Lingkungan Operasional
- PHP 7.4+, CI3, MySQL 5.7+/MariaDB 10.3+
- dompdf 2.x, PhpSpreadsheet 1.x
- Browser modern (Chrome, Firefox, Safari, Edge 2 versi terbaru)
- Desktop, laptop, tablet, smartphone (min 360px)

### 2.5 Batasan dan Asumsi
- Koneksi internet diperlukan
- Data master diinput Admin sebelum digunakan
- Izin/Sakit wajib keterangan dan approval Kepala Sekolah
- Satu akun Siswa & Wali digunakan bersama
- Guru hanya input presensi pada jadwal yang diassign (kecuali sebagai pengganti)
- dompdf dan PhpSpreadsheet wajib tersedia di server

---

## 3. Spesifikasi Teknis Implementasi

### 3.1 Back-End (CodeIgniter 3)

| ID | Spesifikasi | Deskripsi |
|:--:|:------------|:----------|
| TECH-001 | Framework | CI3 v3.1.13+ |
| TECH-002 | CSRF Protection | Token CSRF pada setiap form POST dan AJAX |
| TECH-003 | XSS Filtering | xss_clean() atau Query Builder escaping |
| TECH-004 | SQL Injection Prevention | Query Builder CI3, dilarang string concatenation |
| TECH-005 | Session Management | Session CI3 terenkripsi, timeout 30 menit idle |
| TECH-006 | Password Security | password_hash() bcrypt, dilarang plain-text/md5 |
| TECH-007 | Datatable Server-Side | DataTables SS, pageLength: 10 |
| TECH-008 | Soft Delete | Field status = 'nonaktif', tidak hapus permanen |
| TECH-009 | Base Controller | Cek autentikasi, otorisasi role, tahun ajaran aktif |
| TECH-010 | Auto-Load Helper | url, form, security, custom helper |
| TECH-011 | Library PDF (dompdf) | dompdf v2.x via Composer atau third_party |
| TECH-012 | PDF Generation | A4 Portrait, CSS inline table-based, header/footer, font DejaVu |
| TECH-013 | Library Import Excel | PhpSpreadsheet untuk import siswa/guru |
| TECH-014 | Transaction Database | trans_start() dan trans_complete() untuk batch operations |
| TECH-015 | Log Aktivitas | Catat ke tb_log_aktivitas untuk aksi kritis |
| TECH-016 | Riwayat Kelas Tracking | Catat perubahan penempatan ke tb_riwayat_kelas |
| **TECH-017** | **Upload Foto Profil** | **JPG/PNG, max 2MB, resize 300x300px, assets/uploads/profil/, nama [username]_[timestamp].[ext]** |
| **TECH-018** | **Edit Profil Validation** | **Validasi form edit profil: nama_lengkap wajib, email format valid, no_hp numeric, password lama wajib jika ubah password** |

### 3.2 Front-End (Bootstrap 5 + jQuery)

| ID | Spesifikasi | Deskripsi |
|:--:|:------------|:----------|
| TECH-019 | CSS Framework | Bootstrap 5.3+ |
| TECH-020 | Theme Color | Biru #0d6efd untuk navbar, sidebar, btn-primary, card-header |
| TECH-021 | Responsive Sidebar | Desktop fixed kiri 250px, mobile offcanvas (md ke bawah), hamburger putih |
| TECH-022 | Table Responsive | table-responsive wrapper untuk semua tabel |
| TECH-023 | DataTables Plugin | serverSide: true, pageLength: 10, bahasa Indonesia |
| TECH-024 | Form Validation (jValidate) | jQuery Validation, inline error merah, server-side fallback |
| TECH-025 | Full Width Form | col-12 atau col-lg-12, label form-label di atas input |
| TECH-026 | Modal Dialog Konfirmasi | Bootstrap Modal untuk hapus data, AJAX POST dengan CSRF |
| TECH-027 | Alert Auto-Hide | alert-dismissible, auto-hide 3 detik |
| TECH-028 | AJAX Processing | AJAX untuk simpan/update/hapus, sertakan CSRF token |
| **TECH-029** | **Foto Profil Preview** | **Preview gambar sebelum upload di form edit profil, crop/resize otomatis, tampilkan di sidebar langsung setelah update** |
| **TECH-030** | **Sidebar Profil Real-time** | **Foto profil, nama, dan badge role di sidebar selalu sinkron dengan data terbaru dari session/database** |

---

## 4. Kebutuhan Fungsional Spesifik

### 4.1 Autentikasi, Manajemen Sesi & Profil Pengguna

| ID | Kebutuhan | Prioritas |
|:--:|:----------|:---------:|
| F-001 | Halaman login username/password terpusat | Wajib |
| F-002 | Validasi kredensial, pesan error generik | Wajib |
| F-003 | Redirect ke dashboard sesuai role | Wajib |
| F-004 | Role-based access control, redirect 403 jika tidak berwenang | Wajib |
| F-005 | CSRF token pada setiap form POST dan AJAX | Wajib |
| F-006 | Session timeout 30 menit idle dengan peringatan | Wajib |
| F-007 | Admin CRUD akun pengguna, reset password hash | Wajib |
| F-008 | Fitur "Lupa Password" dengan token reset via email | Sangat Dianjurkan |
| **F-009** | **Semua pengguna dapat mengedit profil sendiri: ubah nama_lengkap, email, no_hp** | **Wajib** |
| **F-010** | **Semua pengguna dapat mengubah foto profil sendiri dengan upload file JPG/PNG, max 2MB, preview sebelum upload** | **Wajib** |
| **F-011** | **Semua pengguna dapat mengubah password sendiri dengan validasi password lama dan konfirmasi password baru** | **Wajib** |
| **F-012** | **Foto profil yang diupload langsung muncul di sidebar bagian atas setelah berhasil diupdate** | **Wajib** |
| **F-013** | **Data profil (nama, foto, role) di sidebar selalu terupdate real-time setelah perubahan** | **Wajib** |

### 4.2 Admin — Master Data CRUD

| ID | Kebutuhan | Prioritas |
|:--:|:----------|:---------:|
| F-014 | CRUD Tahun Ajaran (tahun, semester, status aktif, hanya 1 aktif) | Wajib |
| F-015 | CRUD Kelas (nama, tingkat, wali kelas, tahun ajaran) | Wajib |
| F-016 | CRUD Mata Pelajaran (kode unik, nama) | Wajib |
| F-017 | CRUD Siswa (NIS, nama, JK, TTL, alamat, kelas, nama ortu, no hp ortu) | Wajib |
| F-018 | CRUD Guru (NIP, nama, JK, no hp, alamat, auto-buat akun user) | Wajib |
| F-019 | CRUD Jadwal (hari, jam, kelas, guru, mapel, tahun ajaran, cek bentrok) | Wajib |
| F-020 | Penempatan Siswa ke Kelas per tahun ajaran, bulk select, catat riwayat | Wajib |
| F-021 | Import Siswa dan Guru dari Excel/CSV via PhpSpreadsheet dengan validasi | Sangat Dianjurkan |
| F-022 | Semua master menggunakan DataTables SS (10 item/halaman) | Wajib |
| F-023 | Hapus data via modal konfirmasi, soft delete | Wajib |
| F-024 | Dashboard Admin: total siswa aktif, guru aktif, kelas aktif, presensi hari ini, approval pending | Wajib |

### 4.3 Guru — Jadwal, Presensi & Rekap PDF

| ID | Kebutuhan | Prioritas |
|:--:|:----------|:---------:|
| F-025 | Lihat jadwal mengajar pribadi, terurut hari dan jam | Wajib |
| F-026 | Akses presensi, lihat kelas yang diampu hari ini | Wajib |
| F-027 | Pilih tanggal (default hari ini) dan jadwal, tampilkan daftar siswa | Wajib |
| F-028 | Input status kehadiran: Hadir, Izin, Sakit, Alpa | Wajib |
| F-029 | Keterangan wajib min 10 karakter jika Izin/Sakit, disabled jika Hadir/Alpa | Wajib |
| F-030 | Materi Pelajaran wajib min 5 karakter, diinput global per sesi | Wajib |
| F-031 | Peringatan visual: Izin/Sakit akan dikirim ke Kepala Sekolah | Wajib |
| F-032 | Simpan presensi: Hadir/Alpa final, Izin/Sakit masuk pending approval | Wajib |
| F-033 | Timestamp waktu input otomatis | Wajib |
| F-034 | Guru pengganti (piket/substitute) dapat input presensi dengan id_guru_pengganti | Sangat Dianjurkan |
| F-035 | Rekap presensi kelas yang diampu dengan filter periode dan status | Wajib |
| F-036 | Ekspor rekap presensi ke PDF via dompdf (kop sekolah, periode, tabel, statistik) | Wajib |

### 4.4 Kepala Sekolah — Approval, Laporan & PDF

| ID | Kebutuhan | Prioritas |
|:--:|:----------|:---------:|
| F-037 | Daftar presensi Izin/Sakit pending approval, DataTables SS | Wajib |
| F-038 | Approve (Setuju) atau Reject (Tolak dengan catatan) | Wajib |
| F-039 | Catat approver, tanggal, waktu ke tb_approval dan tb_log_aktivitas | Wajib |
| F-040 | Jika ditolak, ubah status presensi menjadi Alpa, notifikasi ke guru | Wajib |
| F-041 | Dashboard statistik: total Hadir, Izin, Sakit, Alpa (hari/minggu/bulan ini) | Wajib |
| F-042 | Filter laporan: kelas, periode tanggal, semester, status | Wajib |
| F-043 | Ekspor laporan ke PDF via dompdf (kop, judul, periode, tabel, statistik, tanggal cetak) | Wajib |
| F-044 | PDF A4 Portrait, header nama aplikasi, footer nomor halaman dan tanggal cetak, CSS inline table-based | Wajib |
| F-045 | Tombol Export PDF mengunduh file otomatis | Wajib |
| F-046 | Lihat log aktivitas approval (timestamp, approver, status, catatan) | Wajib |

### 4.5 Siswa & Wali — Jadwal & Riwayat

| ID | Kebutuhan | Prioritas |
|:--:|:----------|:---------:|
| F-047 | Login satu akun (role = 'siswa'), tidak ada pemisahan dengan ortu | Wajib |
| F-048 | Lihat jadwal pelajaran kelas sendiri, format tabel harian Senin-Sabtu, responsive | Wajib |
| F-049 | Dashboard ringkasan kehadiran bulan ini (Hadir, Izin, Sakit, Alpa) | Wajib |
| F-050 | Riwayat presensi dengan filter rentang tanggal: tanggal, hari, mapel, materi, status (badge warna), keterangan, status approval | Wajib |
| F-051 | Strictly read-only, tidak ada tombol edit/hapus/input | Wajib |
| F-052 | DataTables SS 10 item/halaman | Wajib |
| F-053 | Jika belum ditempatkan di kelas, tampilkan pesan "Hubungi admin" | Wajib |

---

## 5. Kebutuhan Antarmuka Pengguna (UI/UX)

### 5.1 Layout Global

| ID | Kebutuhan | Spesifikasi |
|:--:|:----------|:------------|
| UI-001 | Header/Topbar | Nama Aplikasi + Tahun Ajaran Aktif (dari tb_tahun_ajaran status_aktif=1) |
| **UI-002** | **Sidebar Profil** | **Foto profil rounded-circle 60x60px (fallback default jika null), nama lengkap user, badge role (Admin=merah, Guru=hijau, Kepsek=kuning, Siswa=biru). Foto, nama, dan badge selalu sinkron dengan data terbaru.** |
| UI-003 | Responsive Sidebar | Desktop fixed kiri 250px, mobile offcanvas (md ke bawah), hamburger putih |
| UI-004 | Container | container-fluid, padding minimal 20px |
| UI-005 | Theme Color | Biru #0d6efd untuk navbar, sidebar, btn-primary, card-header, link aktif |

### 5.2 Halaman Edit Profil (BARU)

| ID | Kebutuhan | Spesifikasi |
|:--:|:----------|:------------|
| **UI-006** | **Akses Edit Profil** | **Menu "Profil Saya" atau icon gear/user di sidebar/header untuk semua role** |
| **UI-007** | **Form Edit Profil** | **Card dengan header "Edit Profil". Field: foto profil (preview gambar saat ini + input file baru dengan preview), nama_lengkap (wajib), email (format valid), no_hp (numeric). Tombol "Simpan Perubahan" (btn-primary) dan "Batal" (btn-secondary).** |
| **UI-008** | **Form Ubah Password** | **Card terpisah atau tab "Ubah Password". Field: password_lama (wajib, min 8 karakter), password_baru (wajib, min 8, huruf+angka), konfirmasi_password_baru (wajib, sama dengan password_baru). Tombol "Ubah Password" (btn-warning).** |
| **UI-009** | **Preview Foto Upload** | **JavaScript preview gambar sebelum upload saat memilih file di input foto profil. Tampilkan thumbnail 150x150px di atas input file.** |
| **UI-010** | **Validasi Real-time** | **jValidate pada form edit profil: nama_lengkap wajib, email format valid, no_hp numeric min 10 digit. Pesan error inline merah.** |
| **UI-011** | **Notifikasi Sukses** | **Alert sukses "Profil berhasil diperbarui" atau "Password berhasil diubah" setelah submit, auto-hide 3 detik. Foto di sidebar langsung terupdate tanpa reload halaman (via AJAX atau redirect dengan session flashdata).** |
| **UI-012** | **Error Upload** | **Jika file terlalu besar (>2MB) atau format tidak sesuai, tampilkan alert error spesifik sebelum submit.** |

### 5.3 Halaman Error

| ID | Kebutuhan | Spesifikasi |
|:--:|:----------|:------------|
| UI-013 | Halaman 403 | Template konsisten theme biru, pesan "Akses Ditolak", tombol "Kembali ke Dashboard" |
| UI-014 | Halaman 404 | Template konsisten, pesan "Halaman Tidak Ditemukan", tombol "Kembali ke Dashboard" |
| UI-015 | Halaman 500 | Template konsisten (tanpa stack trace), pesan "Terjadi Kesalahan Server", tombol "Muat Ulang" |

### 5.4 Komponen Form & Tabel

| ID | Kebutuhan | Spesifikasi |
|:--:|:----------|:------------|
| UI-016 | Table Responsive | table-responsive wrapper untuk semua tabel |
| UI-017 | DataTables SS | serverSide: true, pageLength: 10, bahasa Indonesia |
| UI-018 | Form Full Width | col-12 atau col-lg-12, label form-label di atas input |
| UI-019 | jValidate | Inline error merah di bawah field, server-side fallback |
| UI-020 | Modal Hapus | Bootstrap Modal konfirmasi, AJAX POST dengan CSRF, btn-danger |
| UI-021 | Alert Auto-Hide | alert-dismissible, auto-hide 3 detik |
| UI-022 | Form Presensi | Input Materi global di atas tabel, tabel: No, Nama, Status, Keterangan (conditional), btn-primary Simpan di bawah |
| UI-023 | Filter & Search | form-select Bootstrap, search box DataTables |
| UI-024 | Tombol Export PDF | btn-primary/btn-danger dengan icon PDF, trigger dompdf download |
| UI-025 | Upload Foto Profil | Preview gambar, validasi ukuran/format, muncul di sidebar setelah upload |

### 5.5 Halaman Kunci

| Halaman | Deskripsi |
|:--------|:----------|
| Login | Centered card max-width 400px, logo SMP, input full width, error inline, bg gradasi biru muda |
| Dashboard Admin | 4 card statistik (siswa, guru, kelas, pending), border-left berwarna, tabel ringkasan presensi hari ini |
| Dashboard Guru | Card statistik (siswa hari ini, jam minggu ini), tabel jadwal hari ini, shortcut Presensi |
| Form Presensi | Full width table-responsive, input Materi global wajib, tabel presensi massal, btn Simpan & Reset |
| Approval Kepsek | Datatable pending, btn Setuju (success) / Tolak (danger) per baris, modal catatan penolakan |
| Laporan & PDF | Filter periode, ringkasan statistik 4 badge, tabel laporan, btn Export PDF kanan atas |
| Jadwal Siswa | Tabel responsive per hari (Senin-Sabtu): Jam, Mapel, Guru |
| Riwayat Siswa | Datatable SS, filter date range, badge status berwarna, tidak ada aksi |
| Import Excel | Form upload file, preview 5 baris pertama, progress bar, laporan hasil import |
| **Edit Profil** | **Card: preview foto saat ini, input file dengan preview, field nama/email/no_hp. Card/tab: password lama, password baru, konfirmasi. Alert sukses, foto sidebar terupdate.** |

---

## 6. Kebutuhan Non-Fungsional

| ID | Kategori | Kebutuhan | Target |
|:--:|:---------|:----------|:-------|
| NF-001 | Performa | Load dashboard | <= 2 detik (4G) |
| NF-002 | Performa | DataTables SS response | <= 1 detik (10 item) |
| NF-003 | Performa | Simpan presensi massal 40 siswa | <= 3 detik |
| NF-004 | Performa | Generate PDF 100 baris | <= 5 detik |
| NF-005 | Performa | Import Excel 200 baris | <= 10 detik |
| **NF-006** | **Performa** | **Upload dan resize foto profil** | **<= 2 detik** |
| NF-007 | Keamanan | CSRF token pada semua POST | Wajib |
| NF-008 | Keamanan | Validasi client-side (jValidate) dan server-side | Wajib |
| NF-009 | Keamanan | Password min 8 karakter, huruf+angka, bcrypt | Wajib |
| NF-010 | Keamanan | XSS escaping dengan html_escape() atau esc() | Wajib |
| **NF-011** | **Keamanan** | **Validasi upload foto: hanya JPG/PNG, max 2MB, cek MIME type dan extension** | **Wajib** |
| NF-012 | Keandalan | Uptime jam operasional | >= 99% |
| NF-013 | Keandalan | Error handling halaman informatif (403,404,500), log detail di server | Wajib |
| NF-014 | Usability | Guru dapat operasikan tanpa pelatihan khusus | 1 kali percobaan |
| NF-015 | Aksesibilitas | Smartphone 5 inci, tanpa horizontal scroll berlebihan | Wajib |
| NF-016 | Kompatibilitas | 2 versi browser terbaru Chrome, Firefox, Safari, Edge | Wajib |
| NF-017 | Maintainability | MVC rapi, komentar setiap fungsi utama, nama variabel/fungsi deskriptif Inggris | Wajib |
| NF-018 | Skalabilitas | Menampung data 5 tahun akademik tanpa degradasi performa | Wajib |

---

## 7. Kebutuhan Keamanan Sistem

| ID | Kebutuhan | Deskripsi |
|:--:|:----------|:----------|
| SEC-001 | Autentikasi | Session-based CI3, session ID terenkripsi server-side |
| SEC-002 | Otorisasi | Pengecekan role di constructor/base controller |
| SEC-003 | CSRF Token | csrf_field() di setiap form, ditolak 403 jika tidak valid |
| SEC-004 | SQL Injection Prevention | Query Builder/prepared statement, dilarang string concatenation |
| SEC-005 | XSS Prevention | html_escape() atau esc() untuk output dinamis |
| SEC-006 | Password Storage | password_hash() bcrypt, password_verify() |
| SEC-007 | Session Hijacking | Regenerasi session ID berkala, batasi IP/browser fingerprint |
| SEC-008 | Audit Trail | tb_log_aktivitas untuk approval, hapus data, reset password |
| SEC-009 | PDF Access Control | PDF hanya untuk Kepala Sekolah/Guru yang berwenang, tidak via URL publik |
| SEC-010 | Upload Validation | Foto: JPG/PNG max 2MB; Excel: XLSX/CSV max 5MB; direktori dilindungi |
| **SEC-011** | **Profil Access Control** | **Pengguna hanya dapat mengedit profil sendiri, tidak boleh mengubah profil pengguna lain via manipulasi ID** |
| **SEC-012** | **Password Change Validation** | **Ubah password wajib validasi password lama, tidak boleh langsung set password baru tanpa verifikasi** |

---

## 8. Perancangan Basis Data

### 8.1 Daftar Tabel (12 Tabel)

1. tb_tahun_ajaran
2. tb_kelas
3. tb_mata_pelajaran
4. tb_user
5. tb_siswa
6. tb_riwayat_kelas
7. tb_guru
8. tb_kepala_sekolah
9. tb_jadwal
10. tb_presensi
11. tb_approval
12. tb_log_aktivitas

### 8.2 Skema Tabel

#### tb_tahun_ajaran
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| tahun | VARCHAR(20) | NOT NULL | Contoh: 2025/2026 |
| semester | ENUM('1','2') | NOT NULL | 1=Ganjil, 2=Genap |
| status_aktif | ENUM('1','0') | NOT NULL, DEFAULT '0' | 1=aktif |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_kelas
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| nama_kelas | VARCHAR(50) | NOT NULL | Contoh: VII-A |
| tingkat | VARCHAR(10) | NOT NULL | VII/VIII/IX |
| id_wali_kelas | INT | UNSIGNED, NULL, FK tb_guru.id | |
| id_tahun_ajaran | INT | UNSIGNED, NOT NULL, FK tb_tahun_ajaran.id | |
| status | ENUM('aktif','nonaktif') | NOT NULL, DEFAULT 'aktif' | Soft delete |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_mata_pelajaran
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| kode_mapel | VARCHAR(20) | NOT NULL, UNIQUE | |
| nama_mapel | VARCHAR(100) | NOT NULL | |
| status | ENUM('aktif','nonaktif') | NOT NULL, DEFAULT 'aktif' | Soft delete |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_user
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| username | VARCHAR(50) | NOT NULL, UNIQUE | |
| password | VARCHAR(255) | NOT NULL | Hash bcrypt |
| nama_lengkap | VARCHAR(100) | NOT NULL | |
| email | VARCHAR(100) | NULL | |
| no_hp | VARCHAR(20) | NULL | |
| role | ENUM('admin','guru','kepsek','siswa') | NOT NULL | siswa=akun gabungan |
| foto_profil | VARCHAR(255) | NULL | Path file |
| status | ENUM('aktif','nonaktif') | NOT NULL, DEFAULT 'aktif' | |
| last_login | DATETIME | NULL | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_siswa
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_user | INT | UNSIGNED, NULL, FK tb_user.id | |
| nis | VARCHAR(50) | NOT NULL, UNIQUE | |
| nama | VARCHAR(100) | NOT NULL | |
| jenis_kelamin | ENUM('L','P') | NOT NULL | |
| tanggal_lahir | DATE | NULL | |
| alamat | TEXT | NULL | |
| id_kelas | INT | UNSIGNED, NULL, FK tb_kelas.id | Kelas aktif |
| nama_ortu | VARCHAR(100) | NULL | |
| no_hp_ortu | VARCHAR(20) | NULL | |
| status | ENUM('aktif','lulus','dropout') | NOT NULL, DEFAULT 'aktif' | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_riwayat_kelas
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_siswa | INT | UNSIGNED, NOT NULL, FK tb_siswa.id | |
| id_kelas | INT | UNSIGNED, NOT NULL, FK tb_kelas.id | |
| id_tahun_ajaran | INT | UNSIGNED, NOT NULL, FK tb_tahun_ajaran.id | |
| status | ENUM('naik','tinggal','lulus','pindah') | NOT NULL, DEFAULT 'naik' | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_guru
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_user | INT | UNSIGNED, NOT NULL, FK tb_user.id | |
| nip | VARCHAR(50) | NOT NULL, UNIQUE | |
| nama | VARCHAR(100) | NOT NULL | |
| jenis_kelamin | ENUM('L','P') | NOT NULL | |
| no_hp | VARCHAR(20) | NULL | |
| alamat | TEXT | NULL | |
| status | ENUM('aktif','nonaktif') | NOT NULL, DEFAULT 'aktif' | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_kepala_sekolah
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_user | INT | UNSIGNED, NOT NULL, FK tb_user.id | |
| nip | VARCHAR(50) | NULL | |
| nama | VARCHAR(100) | NOT NULL | |
| periode_mulai | DATE | NULL | |
| periode_selesai | DATE | NULL | |
| created_at | DATETIME | NULL | |

#### tb_jadwal
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_guru | INT | UNSIGNED, NOT NULL, FK tb_guru.id | |
| id_kelas | INT | UNSIGNED, NOT NULL, FK tb_kelas.id | |
| id_mapel | INT | UNSIGNED, NOT NULL, FK tb_mata_pelajaran.id | |
| hari | ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') | NOT NULL | |
| jam_mulai | TIME | NOT NULL | |
| jam_selesai | TIME | NOT NULL | |
| id_tahun_ajaran | INT | UNSIGNED, NOT NULL, FK tb_tahun_ajaran.id | |
| status | ENUM('aktif','nonaktif') | NOT NULL, DEFAULT 'aktif' | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_presensi
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_siswa | INT | UNSIGNED, NOT NULL, FK tb_siswa.id | |
| id_jadwal | INT | UNSIGNED, NOT NULL, FK tb_jadwal.id | |
| id_guru | INT | UNSIGNED, NOT NULL, FK tb_guru.id | Penginput |
| id_guru_pengganti | INT | UNSIGNED, NULL, FK tb_guru.id | Piket/substitute |
| tanggal | DATE | NOT NULL | |
| waktu_input | DATETIME | NOT NULL | Timestamp otomatis |
| status | ENUM('Hadir','Izin','Sakit','Alpa') | NOT NULL | |
| keterangan | VARCHAR(255) | NULL | Wajib jika Izin/Sakit |
| metode | VARCHAR(50) | NOT NULL, DEFAULT 'web' | |
| created_at | DATETIME | NULL | |
| updated_at | DATETIME | NULL | |

#### tb_approval
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_presensi | INT | UNSIGNED, NOT NULL, UNIQUE, FK tb_presensi.id | |
| id_kepsek | INT | UNSIGNED, NOT NULL, FK tb_kepala_sekolah.id | |
| status_approval | ENUM('pending','disetujui','ditolak') | NOT NULL, DEFAULT 'pending' | |
| catatan | VARCHAR(255) | NULL | |
| tanggal_approval | DATETIME | NULL | |
| created_at | DATETIME | NULL | |

#### tb_log_aktivitas
| Kolom | Tipe | Atribut | Keterangan |
|-------|------|---------|------------|
| id | INT | PK, AI, UNSIGNED | |
| id_user | INT | UNSIGNED, NOT NULL, FK tb_user.id | Pelaku |
| aksi | VARCHAR(100) | NOT NULL | Contoh: approve_presensi |
| tabel | VARCHAR(50) | NOT NULL | Tabel yang dimodifikasi |
| id_data | INT | UNSIGNED, NULL | ID record terdampak |
| keterangan | TEXT | NULL | Detail tambahan |
| ip_address | VARCHAR(45) | NULL | |
| created_at | DATETIME | NOT NULL | |

---

## 9. Matriks Pelacakan Kebutuhan

| ID Kebutuhan | Aktor | Modul CI3 | Teknologi/UI |
|:-------------|:------|:----------|:-------------|
| F-001 - F-008 | Semua | Auth, Dashboard | CSRF, Session, Bcrypt |
| **F-009 - F-013** | **Semua** | **Profil** | **Upload, jValidate, AJAX, Session Update** |
| F-014 | Admin | TahunAjaran | DataTables SS, Modal, jValidate |
| F-015 | Admin | Kelas | DataTables SS, Modal, jValidate |
| F-016 | Admin | MataPelajaran | DataTables SS, Modal, jValidate |
| F-017 | Admin | Siswa | DataTables SS, Modal, jValidate |
| F-018 | Admin | Guru | DataTables SS, Modal, jValidate, Auto-buat akun |
| F-019 | Admin | Jadwal | DataTables SS, Modal, jValidate, Cek bentrok |
| F-020 | Admin | KelasSiswa | Bulk Select, tb_riwayat_kelas |
| F-021 | Admin | Import | PhpSpreadsheet, Validation |
| F-022 | Admin | Semua Master | DataTables SS (pageLength:10) |
| F-023 | Admin | Semua Master | Modal, AJAX POST + CSRF, Soft Delete |
| F-024 | Admin | Dashboard | Query Aggregat, Card Statistik |
| F-025 - F-026 | Guru | JadwalGuru, Presensi | Query Builder, Table Responsive |
| F-027 - F-033 | Guru | Presensi | jValidate, Timestamp, Conditional Input |
| F-034 | Guru | Presensi | id_guru_pengganti |
| F-035 - F-036 | Guru | RekapPresensi, ExportPdf | dompdf, Filter Periodik |
| F-037 - F-040 | Kepsek | Approval | Modal Approval, tb_log_aktivitas |
| F-041 - F-042 | Kepsek | Laporan, Dashboard | Filter Periodik, Statistik |
| F-043 - F-045 | Kepsek | Laporan/export_pdf | dompdf 2.x, A4 Portrait, CSS inline |
| F-046 | Kepsek | LogApproval | DataTables SS |
| F-047 | Siswa & Wali | Auth | Session role='siswa' |
| F-048 | Siswa & Wali | JadwalSiswa | Query Builder, Table Responsive |
| F-049 - F-053 | Siswa & Wali | Riwayat, Dashboard | DataTables SS, Read-Only |
| UI-001 - UI-005 | Semua | Template | BS5, Offcanvas, Theme Biru |
| **UI-006 - UI-012** | **Semua** | **Profil** | **Preview Upload, jValidate, AJAX, Alert** |
| UI-013 - UI-015 | Semua | Errors | Template Konsisten |
| UI-016 - UI-025 | Semua | Template/View | jValidate, DataTables, Modal, Alert, PDF Button, Upload |
| NF-001 - NF-018 | Semua | Global | CI3, BS5, MySQL, dompdf, PhpSpreadsheet |
| SEC-001 - SEC-012 | Semua | Global | CSRF, XSS, Bcrypt, Query Builder, Log Aktivitas, PDF Access, Profil Access, Password Validation |

---

Disusun oleh: Tim Analis Sistem
Diperiksa oleh: Kepala Sekolah / Project Owner
Disetujui oleh: Stakeholder SMPTK Galang Kasih Ubung

---
Dokumen ini merupakan spesifikasi final dan mengikat.
