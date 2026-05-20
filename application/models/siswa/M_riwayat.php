<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_riwayat_siswa($id_siswa, $start_date = null, $end_date = null)
    {
        $this->db->select('p.*, m.nama_mapel, j.hari');
        $this->db->select('a.status_approval, a.catatan as catatan_approval');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_approval a', 'a.id_presensi = p.id', 'left');
        $this->db->where('p.id_siswa', $id_siswa);
        
        if ($start_date) {
            $this->db->where('p.tanggal >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('p.tanggal <=', $end_date);
        }
        
        $this->db->order_by('p.tanggal', 'DESC');
        $this->db->order_by('j.hari', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_statistik_bulan_ini($id_siswa)
    {
        $this->db->select('SUM(CASE WHEN p.status = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $this->db->select('SUM(CASE WHEN p.status = "Izin" THEN 1 ELSE 0 END) as izin');
        $this->db->select('SUM(CASE WHEN p.status = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $this->db->select('SUM(CASE WHEN p.status = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $this->db->from('tb_presensi p');
        $this->db->where('p.id_siswa', $id_siswa);
        $this->db->where('p.tanggal >=', date('Y-m-01'));
        $this->db->where('p.tanggal <=', date('Y-m-t'));
        
        return $this->db->get()->row_array();
    }
}
