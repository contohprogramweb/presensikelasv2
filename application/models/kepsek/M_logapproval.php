<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_logapproval extends CI_Model {

    var $column_order = array(null, 'a.tanggal_approval', 's.nama_lengkap', null, 'p.status_presensi', 'a.status_approval', 'a.catatan', 'u.nama_lengkap');
    var $column_search = array('s.nama_lengkap', 'p.status_presensi', 'a.status_approval', 'a.catatan', 'u.nama_lengkap');
    var $order = array('a.tanggal_approval' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('a.*, u.nama_lengkap as nama_approver, p.status_presensi, p.tanggal as tanggal_presensi');
        $this->db->select('s.nama_lengkap as nama_siswa');
        $this->db->select('k.nama_kelas');
        $this->db->from('tb_approval a');
        $this->db->join('tb_user u', 'u.id = a.id_approver', 'left');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');

        // Filter tanggal
        if ($this->input->post('tanggal_mulai')) {
            $this->db->where('DATE(a.tanggal_approval) >=', $this->input->post('tanggal_mulai'));
        }
        if ($this->input->post('tanggal_sampai')) {
            $this->db->where('DATE(a.tanggal_approval) <=', $this->input->post('tanggal_sampai'));
        }

        // Filter status
        if ($this->input->post('status')) {
            $this->db->where('a.status_approval', $this->input->post('status'));
        }

        // Search
        if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $this->db->group_start();
            $this->db->like('s.nama_lengkap', $search);
            $this->db->or_like('p.status_presensi', $search);
            $this->db->or_like('a.status_approval', $search);
            $this->db->or_like('a.catatan', $search);
            $this->db->or_like('u.nama_lengkap', $search);
            $this->db->or_like('k.nama_kelas', $search);
            $this->db->group_end();
        }

        // Order
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by(implode(',', $this->order));
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_all()
    {
        $this->db->from('tb_approval a');
        return $this->db->count_all_results();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function get_all_logs($filter = array())
    {
        $this->db->select('a.*, u.nama_lengkap as approver_nama, p.status_presensi, p.tanggal');
        $this->db->select('s.nama_lengkap as nama_siswa');
        $this->db->select('k.nama_kelas');
        $this->db->from('tb_approval a');
        $this->db->join('tb_user u', 'u.id = a.id_approver', 'left');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');

        if (!empty($filter['tanggal_mulai'])) {
            $this->db->where('DATE(a.tanggal_approval) >=', $filter['tanggal_mulai']);
        }
        if (!empty($filter['tanggal_sampai'])) {
            $this->db->where('DATE(a.tanggal_approval) <=', $filter['tanggal_sampai']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('a.status_approval', $filter['status']);
        }

        $this->db->order_by('a.tanggal_approval', 'DESC');
        
        return $this->db->get()->result_array();
    }
}