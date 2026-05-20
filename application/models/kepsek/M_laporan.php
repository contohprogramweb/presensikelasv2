<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_laporan($id_kelas = null, $start_date, $end_date, $status = null)
    {
        $this->db->select('p.*, s.nama as nama_siswa, k.nama_kelas');
        $this->db->select('m.nama_mapel, j.hari, u.nama_lengkap as user_nama');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        
        $this->db->where('p.tanggal >=', $start_date);
        $this->db->where('p.tanggal <=', $end_date);
        
        if ($id_kelas) {
            $this->db->where('s.id_kelas', $id_kelas);
        }
        
        if ($status) {
            $this->db->where('p.status', $status);
        }
        
        $this->db->order_by('p.tanggal', 'DESC');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_statistik($id_kelas = null, $start_date, $end_date)
    {
        $this->db->select('SUM(CASE WHEN p.status = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $this->db->select('SUM(CASE WHEN p.status = "Izin" THEN 1 ELSE 0 END) as izin');
        $this->db->select('SUM(CASE WHEN p.status = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $this->db->select('SUM(CASE WHEN p.status = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        
        $this->db->where('p.tanggal >=', $start_date);
        $this->db->where('p.tanggal <=', $end_date);
        
        if ($id_kelas) {
            $this->db->where('s.id_kelas', $id_kelas);
        }
        
        return $this->db->get()->row_array();
    }

    public function get_all_kelas()
    {
        $this->db->select('id, nama_kelas');
        $this->db->from('tb_kelas');
        $this->db->order_by('nama_kelas', 'ASC');
        
        return $this->db->get()->result_array();
    }
}
