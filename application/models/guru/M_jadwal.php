<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_jadwal_guru($id_guru, $id_tahun_ajaran = null)
    {
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_guru', $id_guru);
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_jadwal_hari_ini($id_guru)
    {
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
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_guru', $id_guru);
        $this->db->where('j.hari', $hari_ini);
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        return $this->db->get()->result_array();
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
