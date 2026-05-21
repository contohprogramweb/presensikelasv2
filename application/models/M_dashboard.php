<?php
/**
 * Model untuk dashboard dan data statistik
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {
    
    /**
     * Get tahun ajaran aktif
     * @return object|null Tahun ajaran data
     */
    public function get_tahun_ajaran_aktif() {
        $this->db->where('status_aktif', 1);
        $query = $this->db->get('tb_tahun_ajaran', 1);
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row();
    }
    
    /**
     * Get tahun ajaran terbaru
     * @return object|null Tahun ajaran data
     */
    public function get_tahun_ajaran_terbaru() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('tb_tahun_ajaran', 1);
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row();
    }
    
    /**
     * Get statistik untuk admin dashboard
     * @param int $tahun_ajaran_id ID tahun ajaran
     * @return array Statistik data
     */
    public function get_admin_stats($tahun_ajaran_id = null) {
        $stats = array();
        
        // Total siswa aktif
        $this->db->where('status_aktif', 1);
        if ($tahun_ajaran_id) {
            $this->db->where('id_kelas IN (SELECT id FROM tb_kelas WHERE id_tahun_ajaran = ' . (int)$tahun_ajaran_id . ')', null, false);
        }
        $stats['total_siswa'] = $this->db->count_all_results('tb_siswa');
        
        // Total guru aktif
        $this->db->where('status_aktif', 1);
        $stats['total_guru'] = $this->db->count_all_results('tb_guru');
        
        // Total kelas aktif
        $this->db->where('status_aktif', 1);
        if ($tahun_ajaran_id) {
            $this->db->where('id_tahun_ajaran', $tahun_ajaran_id);
        }
        $stats['total_kelas'] = $this->db->count_all_results('tb_kelas');
        
        // Presensi hari ini
        $today = date('Y-m-d');
        $this->db->where('tanggal', $today);
        $stats['presensi_hari_ini'] = $this->db->count_all_results('tb_presensi');
        
        // Approval pending
        $this->db->where('status_approval', 'pending');
        $stats['approval_pending'] = $this->db->count_all_results('tb_approval');
        
        return $stats;
    }
    
    /**
     * Get statistik untuk guru dashboard
     * @param int $guru_id ID guru
     * @return array Statistik data
     */
    public function get_guru_stats($guru_id) {
        $stats = array();
        
        // Jadwal hari ini
        $hari_ini = date('l', strtotime('+0 days'));
        $hari_map = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        $hari_ini_indo = $hari_map[$hari_ini];
        
        $this->db->where('hari', $hari_ini_indo);
        $this->db->where('id_guru', $guru_id);
        $this->db->where('status_aktif', 1);
        $stats['jadwal_hari_ini'] = $this->db->count_all_results('tb_jadwal');
        
        // Total presensi bulan ini
        $this->db->where('id_guru', $guru_id);
        $this->db->where('MONTH(tanggal)', date('n'));
        $this->db->where('YEAR(tanggal)', date('Y'));
        $stats['presensi_bulan_ini'] = $this->db->count_all_results('tb_presensi');
        
        return $stats;
    }
    
    /**
     * Get statistik untuk siswa dashboard
     * @param int $siswa_id ID siswa
     * @return array Statistik data
     */
    public function get_siswa_stats($siswa_id) {
        $stats = array(
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0
        );
        
        // Get presensi bulan ini
        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->where('id_siswa', $siswa_id);
        $this->db->where('MONTH(tanggal)', date('n'));
        $this->db->where('YEAR(tanggal)', date('Y'));
        $this->db->group_by('status');
        $query = $this->db->get('tb_presensi');
        
        foreach ($query->result() as $row) {
            $status_lower = strtolower($row->status);
            if (isset($stats[$status_lower])) {
                $stats[$status_lower] = $row->jumlah;
            }
        }
        
        return $stats;
    }
    
    /**
     * Get statistik untuk kepsek dashboard
     * @return array Statistik data
     */
    public function get_kepsek_stats() {
        $stats = array();
        
        // Approval pending
        $this->db->where('status_approval', 'pending');
        $stats['approval_pending'] = $this->db->count_all_results('tb_approval');
        
        // Total presensi hari ini
        $today = date('Y-m-d');
        $this->db->where('tanggal', $today);
        $stats['presensi_hari_ini'] = $this->db->count_all_results('tb_presensi');
        
        // Total siswa
        $this->db->where('status_aktif', 1);
        $stats['total_siswa'] = $this->db->count_all_results('tb_siswa');
        
        // Total guru
        $this->db->where('status_aktif', 1);
        $stats['total_guru'] = $this->db->count_all_results('tb_guru');
        
        // Total kelas
        $this->db->where('status_aktif', 1);
        $stats['total_kelas'] = $this->db->count_all_results('tb_kelas');
        
        // Ringkasan presensi hari ini (hadir, izin, sakit, alpa)
        $today = date('Y-m-d');
        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->where('tanggal', $today);
        $this->db->group_by('status');
        $query = $this->db->get('tb_presensi');
        
        $ringkasan = array(
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0
        );
        
        foreach ($query->result() as $row) {
            $status_lower = strtolower($row->status);
            if (isset($ringkasan[$status_lower])) {
                $ringkasan[$status_lower] = $row->jumlah;
            }
        }
        
        $stats['ringkasan_hari_ini'] = $ringkasan;
        
        return $stats;
    }
}
