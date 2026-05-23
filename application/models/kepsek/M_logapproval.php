<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_logapproval extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_datatables()
    {
        $this->db->select('a.*, u.nama_lengkap as approver_nama, p.tanggal');
        $this->db->select('s.nama_lengkap as nama_siswa');
        $this->db->from('tb_approval a');
        $this->db->join('tb_user u', 'u.id = a.id_approver', 'left');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->order_by('a.tanggal_approval', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function get_by_date_range($start_date, $end_date)
    {
        $this->db->select('a.*, u.nama_lengkap as approver_nama, p.tanggal');
        $this->db->select('s.nama_lengkap as nama_siswa');
        $this->db->from('tb_approval a');
        $this->db->join('tb_user u', 'u.id = a.id_approver', 'left');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->where('a.tanggal_approval >=', $start_date);
        $this->db->where('a.tanggal_approval <=', $end_date);
        $this->db->order_by('a.tanggal_approval', 'DESC');
        
        return $this->db->get()->result_array();
    }
}