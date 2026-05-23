<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_riwayat_siswa_datatable($id_siswa, $start_date = null, $end_date = null, $status_filter = null, $search = null, $length = 10, $start = 0, $order = [])
    {
        // Debug logging
        log_message('debug', 'M_riwayat::get_riwayat_siswa_datatable - id_siswa: ' . $id_siswa . ', start_date: ' . $start_date . ', end_date: ' . $end_date);
        
        // Query untuk mendapatkan data distinct per tanggal
        $this->db->select('DATE(ps.tanggal) as tanggal');
        $this->db->select('MAX(j.hari) as hari');
        $this->db->select("COUNT(ps.id) as jumlah_sesi");
        $this->db->select("MAX(ps.status) as status");
        $this->db->select("MAX(ps.keterangan) as keterangan");
        $this->db->select("MAX(a.status_approval) as status_approval");
        $this->db->select("MAX(a.catatan_penolakan) as catatan_penolakan");
        
        $this->db->from('tb_presensi_siswa ps');
        $this->db->join('tb_presensi p', 'p.id = ps.id_presensi', 'inner');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal', 'inner');
        $this->db->join('tb_approval a', 'a.id_presensi = p.id AND a.id_siswa = ps.id_siswa', 'left');
        
        $this->db->where('ps.id_siswa', $id_siswa);
        
        if ($start_date) {
            $this->db->where('DATE(ps.tanggal) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(ps.tanggal) <=', $end_date);
        }
        if ($status_filter) {
            $this->db->where('ps.status', $status_filter);
        }
        
        // Search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('DATE(ps.tanggal)', $search);
            $this->db->or_like('j.hari', $search);
            $this->db->or_like('ps.status', $search);
            $this->db->group_end();
        }
        
        // Group by tanggal untuk mendapatkan satu record per hari
        $this->db->group_by('DATE(ps.tanggal)');
        
        // Ordering
        if (!empty($order)) {
            foreach ($order as $o) {
                $column_index = $o['column'];
                $dir = $o['dir'];
                
                switch ($column_index) {
                    case 1: $this->db->order_by('tanggal', $dir); break;
                    case 2: $this->db->order_by('hari', $dir); break;
                    case 5: $this->db->order_by('status', $dir); break;
                    default: $this->db->order_by('tanggal', 'DESC'); break;
                }
            }
        } else {
            $this->db->order_by('tanggal', 'DESC');
        }
        
        // Limit and offset
        $this->db->limit($length, $start);
        
        $query = $this->db->get();
        
        // Debug: log the SQL query
        log_message('debug', 'M_riwayat::get_riwayat_siswa_datatable - SQL: ' . $this->db->last_query());
        
        $result = $query->result_array();
        
        return $result;
    }

    public function count_all_riwayat($id_siswa, $start_date = null, $end_date = null, $status_filter = null, $search = null)
    {
        // Debug logging
        log_message('debug', 'M_riwayat::count_all_riwayat - id_siswa: ' . $id_siswa . ', start_date: ' . $start_date . ', end_date: ' . $end_date);
        
        $this->db->select('DATE(ps.tanggal) as tanggal');
        $this->db->from('tb_presensi_siswa ps');
        $this->db->join('tb_presensi p', 'p.id = ps.id_presensi', 'inner');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal', 'inner');
        $this->db->where('ps.id_siswa', $id_siswa);
        
        if ($start_date) {
            $this->db->where('DATE(ps.tanggal) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(ps.tanggal) <=', $end_date);
        }
        if ($status_filter) {
            $this->db->where('ps.status', $status_filter);
        }
        
        // Search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('DATE(ps.tanggal)', $search);
            $this->db->or_like('j.hari', $search);
            $this->db->or_like('ps.status', $search);
            $this->db->group_end();
        }
        
        // Group by tanggal untuk mendapatkan satu record per hari
        $this->db->group_by('DATE(ps.tanggal)');
        
        // Debug: log the SQL query
        log_message('debug', 'M_riwayat::count_all_riwayat - SQL: ' . $this->db->last_query());
        
        return $this->db->count_all_results();
    }

    public function get_statistik_bulan_ini($id_siswa)
    {
        $this->db->select('SUM(CASE WHEN ps.status = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $this->db->select('SUM(CASE WHEN ps.status = "Izin" THEN 1 ELSE 0 END) as izin');
        $this->db->select('SUM(CASE WHEN ps.status = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $this->db->select('SUM(CASE WHEN ps.status = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $this->db->from('tb_presensi_siswa ps');
        $this->db->where('ps.id_siswa', $id_siswa);
        $this->db->where('ps.tanggal >=', date('Y-m-01'));
        $this->db->where('ps.tanggal <=', date('Y-m-t'));
        
        return $this->db->get()->row_array();
    }
}