<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal mengajar guru dengan join lengkap
     * @param int $id_user ID user (dari session)
     * @param int|null $id_tahun_ajaran ID tahun ajaran
     * @return array Jadwal list
     */
    public function get_jadwal_guru($id_user, $id_tahun_ajaran = null)
    {
        // Dapatkan id_guru dari tb_guru berdasarkan id_user
        $this->db->where('id_user', $id_user);
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            return [];
        }
        
        $id_guru = $guru->id;
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_guru', $id_guru);
        
        if ($id_tahun_ajaran !== null && $id_tahun_ajaran > 0) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        $this->db->order_by('FIELD(j.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")');
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get jadwal mengajar guru untuk hari ini
     * @param int $id_user ID user (dari session)
     * @param int|null $id_tahun_ajaran ID tahun ajaran
     * @return array Jadwal list untuk hari ini
     */
    public function get_jadwal_hari_ini($id_user, $id_tahun_ajaran = null)
    {
        // Mapping hari dari format PHP (Bahasa Inggris) ke Bahasa Indonesia
        $hari_indo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari_ini = $hari_indo[date('l')];
        
        // Dapatkan id_guru dari tb_guru berdasarkan id_user
        $this->db->where('id_user', $id_user);
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            log_message('error', 'M_jadwal::get_jadwal_hari_ini - Guru tidak ditemukan untuk user_id: ' . $id_user);
            return [];
        }
        
        $id_guru = $guru->id;
        
        log_message('debug', 'M_jadwal::get_jadwal_hari_ini - Hari: ' . $hari_ini . ', id_guru: ' . $id_guru . ', id_tahun_ajaran: ' . ($id_tahun_ajaran ?? 'null'));
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel, g.status_aktif as guru_status');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas', 'left');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel', 'left');
        $this->db->join('tb_guru g', 'g.id = j.id_guru', 'left');
        $this->db->where('j.id_guru', $id_guru);
        $this->db->where('j.hari', $hari_ini);
        
        // Hanya tampilkan jadwal yang aktif
        $this->db->where('j.status_aktif', 1);
        
        // Filter tahun ajaran jika diberikan
        if ($id_tahun_ajaran !== null && $id_tahun_ajaran > 0) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        $query = $this->db->get();
        
        log_message('debug', 'M_jadwal::get_jadwal_hari_ini - SQL: ' . $this->db->last_query());
        log_message('debug', 'M_jadwal::get_jadwal_hari_ini - Result count: ' . $query->num_rows());
        
        return $query->result();
    }

    /**
     * Get jadwal by ID
     * @param int $id Jadwal ID
     * @return array|null Jadwal data
     */
    public function get_by_id($id)
    {
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel, u.nama_lengkap as guru_nama');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('j.id', $id);
        
        return $this->db->get()->row_array();
    }
    
    /**
     * Check if jadwal belongs to guru
     * @param int $jadwal_id Jadwal ID
     * @param int $guru_id Guru ID
     * @return bool
     */
    public function is_jadwal_guru($jadwal_id, $guru_id)
    {
        $this->db->where('id', $jadwal_id);
        $this->db->where('id_guru', $guru_id);
        return $this->db->count_all_results('tb_jadwal') > 0;
    }
}