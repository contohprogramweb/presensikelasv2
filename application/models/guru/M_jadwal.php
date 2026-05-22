<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal mengajar guru dengan join lengkap
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
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        } else {
            // Default tahun ajaran aktif
            if (isset($this->tahun_ajaran_aktif) && isset($this->tahun_ajaran_aktif->id)) {
                $this->db->where('j.id_tahun_ajaran', $this->tahun_ajaran_aktif->id);
            }
        }
        
        $this->db->order_by('FIELD(j.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")');
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

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
            return [];
        }
        
        $id_guru = $guru->id;
        
        // Debug: Log query untuk troubleshooting
        log_message('debug', 'get_jadwal_hari_ini - hari: ' . $hari_ini . ', id_guru: ' . $id_guru . ', id_tahun_ajaran: ' . ($id_tahun_ajaran ?? 'null'));
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas', 'left');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel', 'left');
        $this->db->where('j.id_guru', $id_guru);
        $this->db->where('j.hari', $hari_ini);
        $this->db->where('j.status_aktif', 1);
        
        // Filter berdasarkan tahun ajaran - WAJIB ada
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        } else {
            // Jika tidak ada parameter, gunakan tahun ajaran aktif dari property
            if (isset($this->tahun_ajaran_aktif) && isset($this->tahun_ajaran_aktif->id)) {
                $this->db->where('j.id_tahun_ajaran', $this->tahun_ajaran_aktif->id);
            }
        }
        
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        $query = $this->db->get();
        
        // Debug: Log hasil query dan SQL
        log_message('debug', 'get_jadwal_hari_ini - result count: ' . $query->num_rows());
        log_message('debug', 'get_jadwal_hari_ini - last_query: ' . $this->db->last_query());
        
        return $query->result();
    }

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
}