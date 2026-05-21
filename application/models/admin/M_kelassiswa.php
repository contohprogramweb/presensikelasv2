<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kelassiswa extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_siswa_belum_ditempatkan($id_tahun_ajaran = null)
    {
        $this->db->select('s.*, u.username, u.nama_lengkap as user_nama');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('s.id_kelas IS NULL', null, false);
        
        return $this->db->get()->result_array();
    }

    public function get_siswa_per_kelas($id_kelas)
    {
        $this->db->select('s.*, u.username, u.nama_lengkap as user_nama');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('s.id_kelas', $id_kelas);
        
        return $this->db->get()->result_array();
    }

    public function get_all_kelas($id_tahun_ajaran = null)
    {
        $this->db->select('k.*, COUNT(s.id) as jumlah_siswa');
        $this->db->from('tb_kelas k');
        $this->db->join('tb_siswa s', 's.id_kelas = k.id', 'left');
        $this->db->group_by('k.id');
        
        if ($id_tahun_ajaran) {
            $this->db->where('k.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->get()->result_array();
    }

    public function tempatkan_siswa($id_siswa, $id_kelas, $id_tahun_ajaran)
    {
        $this->db->trans_start();
        
        // Update tb_siswa
        $this->db->where('id', $id_siswa);
        $this->db->update('tb_siswa', ['id_kelas' => $id_kelas]);
        
        // Insert ke tb_riwayat_kelas
        $data_riwayat = [
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'status' => 'naik',
            'tanggal_masuk' => date('Y-m-d')
        ];
        $this->db->insert('tb_riwayat_kelas', $data_riwayat);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function tempatkan_banyak_siswa($id_siswa_array, $id_kelas, $id_tahun_ajaran)
    {
        $this->db->trans_start();
        
        foreach ($id_siswa_array as $id_siswa) {
            // Update tb_siswa
            $this->db->where('id', $id_siswa);
            $this->db->update('tb_siswa', ['id_kelas' => $id_kelas]);
            
            // Insert ke tb_riwayat_kelas
            $data_riwayat = [
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas,
                'id_tahun_ajaran' => $id_tahun_ajaran,
                'status' => 'naik',
                'tanggal_masuk' => date('Y-m-d')
            ];
            $this->db->insert('tb_riwayat_kelas', $data_riwayat);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function count_siswa_by_kelas($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        return $this->db->count_all_results('tb_siswa');
    }

    public function get_siswa_by_kelas($id_kelas)
    {
        $this->db->select('s.id, s.nis, u.nama_lengkap as nama_siswa, s.jenis_kelamin');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('s.id_kelas', $id_kelas);
        
        return $this->db->get()->result();
    }
}
