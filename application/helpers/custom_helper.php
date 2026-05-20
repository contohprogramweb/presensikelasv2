<?php
/**
 * Custom Helper untuk Sistem Presensi SMP
 * Fungsi-fungsi umum yang digunakan di seluruh aplikasi
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cek akses user berdasarkan role
 * @param array $allowed_roles Array role yang diizinkan
 * @return bool
 */
if (!function_exists('cek_akses')) {
    function cek_akses($allowed_roles) {
        $CI =& get_instance();
        $user_role = $CI->session->userdata('role');
        
        if (!$user_role) {
            return false;
        }
        
        if (is_array($allowed_roles)) {
            return in_array($user_role, $allowed_roles);
        }
        
        return $user_role === $allowed_roles;
    }
}

/**
 * Tampilkan halaman error 403 Forbidden
 */
if (!function_exists('show_403')) {
    function show_403() {
        $CI =& get_instance();
        $CI->output->set_status_header(403);
        $CI->load->view('errors/html/error_403');
        exit;
    }
}

/**
 * Log aktivitas user ke database
 * @param string $aksi Nama aksi yang dilakukan
 * @param string $tabel Nama tabel yang terpengaruh
 * @param int|null $id_data ID data yang terpengaruh
 * @param string|null $keterangan Keterangan tambahan
 */
if (!function_exists('log_aktivitas')) {
    function log_aktivitas($aksi, $tabel = null, $id_data = null, $keterangan = null) {
        $CI =& get_instance();
        
        $data = array(
            'id_user' => $CI->session->userdata('id'),
            'aksi' => $aksi,
            'tabel' => $tabel,
            'id_data' => $id_data,
            'keterangan' => $keterangan,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent()
        );
        
        $CI->db->insert('tb_log_aktivitas', $data);
    }
}

/**
 * Format tanggal ke format Indonesia
 * @param string $tanggal Tanggal dalam format Y-m-d atau Y-m-d H:i:s
 * @return string Tanggal dalam format "20 Mei 2026"
 */
if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal) {
        if (empty($tanggal)) {
            return '';
        }
        
        $bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        
        $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
        
        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }
}

/**
 * Format tanggal lengkap dengan hari
 * @param string $tanggal Tanggal dalam format Y-m-d
 * @return string Tanggal dengan hari, contoh: "Senin, 20 Mei 2026"
 */
if (!function_exists('tanggal_hari_indo')) {
    function tanggal_hari_indo($tanggal) {
        if (empty($tanggal)) {
            return '';
        }
        
        $hari = array(
            'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa',
            'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
        );
        
        $hari_ini = $hari[date('D', strtotime($tanggal))];
        
        return $hari_ini . ', ' . tanggal_indo($tanggal);
    }
}

/**
 * Generate badge HTML untuk role user
 * @param string $role Role user
 * @return string HTML badge
 */
if (!function_exists('badge_role')) {
    function badge_role($role) {
        $colors = array(
            'admin' => 'danger',
            'guru' => 'success',
            'kepsek' => 'warning',
            'siswa' => 'info'
        );
        
        $color = isset($colors[$role]) ? $colors[$role] : 'secondary';
        
        return '<span class="badge bg-' . $color . '">' . ucfirst($role) . '</span>';
    }
}

/**
 * Generate badge HTML untuk status presensi
 * @param string $status Status presensi
 * @return string HTML badge
 */
if (!function_exists('badge_presensi')) {
    function badge_presensi($status) {
        $colors = array(
            'Hadir' => 'success',
            'Izin' => 'info',
            'Sakit' => 'warning',
            'Alpa' => 'danger'
        );
        
        $color = isset($colors[$status]) ? $colors[$status] : 'secondary';
        
        return '<span class="badge bg-' . $color . '">' . $status . '</span>';
    }
}

/**
 * Generate badge HTML untuk status approval
 * @param string $status Status approval
 * @return string HTML badge
 */
if (!function_exists('badge_approval')) {
    function badge_approval($status) {
        $colors = array(
            'pending' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger'
        );
        
        $color = isset($colors[$status]) ? $colors[$status] : 'secondary';
        
        return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
    }
}

/**
 * Enkripsi ID untuk URL
 * @param int $id ID yang akan dienkripsi
 * @return string ID terenkripsi (base64)
 */
if (!function_exists('encrypt_id')) {
    function encrypt_id($id) {
        $salt = 'smp_galang_kasih_2025';
        $encrypted = base64_encode($salt . '|' . $id);
        return str_replace(array('+', '/', '='), array('-', '_', '.'), $encrypted);
    }
}

/**
 * Dekripsi ID dari URL
 * @param string $encrypted_id ID terenkripsi
 * @return int|false ID asli atau false jika gagal
 */
if (!function_exists('decrypt_id')) {
    function decrypt_id($encrypted_id) {
        $salt = 'smp_galang_kasih_2025';
        $decoded = base64_decode(str_replace(array('-', '_', '.'), array('+', '/', '='), $encrypted_id));
        
        if ($decoded === false) {
            return false;
        }
        
        $parts = explode('|', $decoded);
        
        if (count($parts) !== 2 || $parts[0] !== $salt) {
            return false;
        }
        
        return (int)$parts[1];
    }
}

/**
 * Format angka ke format Rupiah
 * @param float|int $angka Angka yang akan diformat
 * @return string Format Rupiah
 */
if (!function_exists('format_rupiah')) {
    function format_rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

/**
 * Get nama hari dari tanggal
 * @param string $tanggal Tanggal dalam format Y-m-d
 * @return string Nama hari
 */
if (!function_exists('get_nama_hari')) {
    function get_nama_hari($tanggal) {
        $hari = array(
            'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa',
            'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
        );
        
        return $hari[date('D', strtotime($tanggal))];
    }
}

/**
 * Hitung selisih hari antara dua tanggal
 * @param string $tgl1 Tanggal pertama
 * @param string $tgl2 Tanggal kedua
 * @return int Selisih hari
 */
if (!function_exists('selisih_hari')) {
    function selisih_hari($tgl1, $tgl2) {
        $datetime1 = new DateTime($tgl1);
        $datetime2 = new DateTime($tgl2);
        $interval = $datetime1->diff($datetime2);
        return $interval->days;
    }
}

/**
 * Truncate text dengan panjang tertentu
 * @param string $text Text yang akan dipotong
 * @param int $length Panjang maksimal
 * @return string Text yang sudah dipotong
 */
if (!function_exists('truncate_text')) {
    function truncate_text($text, $length = 50) {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
}
