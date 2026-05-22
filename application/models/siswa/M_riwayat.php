<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_riwayat_siswa_datatable($id_siswa, $start_date = null, $end_date = null, $status_filter = null, $search = null, $length = 10, $start = 0, $order = [])
    {
        $this->db->select('p.*, m.nama_mapel, j.hari');
        $this->db->select('a.status_approval, a.catatan as catatan_approval');
        $this->db->select('u.nama_lengkap as nama_guru');
        $this->db->select('p.keterangan as materi_pelajaran');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_approval a', 'a.id_presensi = p.id', 'left');
        $this->db->where('p.id_siswa', $id_siswa);
        
        if ($start_date) {
            $this->db->where('p.tanggal >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('p.tanggal <=', $end_date);
        }
        if ($status_filter) {
            $this->db->where('p.status', $status_filter);
        }
        
        // Search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.tanggal', $search);
            $this->db->or_like('j.hari', $search);
            $this->db->or_like('m.nama_mapel', $search);
            $this->db->or_like('u.nama_lengkap', $search);
            $this->db->or_like('p.status', $search);
            $this->db->or_like('p.keterangan', $search);
            $this->db->group_end();
        }
        
        // Ordering
        if (!empty($order)) {
            foreach ($order as $o) {
                $column_index = $o['column'];
                $dir = $o['dir'];
                
                switch ($column_index) {
                    case 1: $this->db->order_by('p.tanggal', $dir); break;
                    case 2: $this->db->order_by('j.hari', $dir); break;
                    case 3: $this->db->order_by('m.nama_mapel', $dir); break;
                    case 4: $this->db->order_by('u.nama_lengkap', $dir); break;
                    case 6: $this->db->order_by('p.status', $dir); break;
                    default: $this->db->order_by('p.tanggal', 'DESC'); break;
                }
            }
        } else {
            $this->db->order_by('p.tanggal', 'DESC');
        }
        
        // Limit and offset
        $this->db->limit($length, $start);
        
        $result = $this->db->get()->result_array();
        
        return $result;
    }

    public function count_all_riwayat($id_siswa, $start_date = null, $end_date = null, $status_filter = null, $search = null)
    {
        $this->db->from('tb_presensi p');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('p.id_siswa', $id_siswa);
        
        if ($start_date) {
            $this->db->where('p.tanggal >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('p.tanggal <=', $end_date);
        }
        if ($status_filter) {
            $this->db->where('p.status', $status_filter);
        }
        
        // Search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.tanggal', $search);
            $this->db->or_like('j.hari', $search);
            $this->db->or_like('m.nama_mapel', $search);
            $this->db->or_like('u.nama_lengkap', $search);
            $this->db->or_like('p.status', $search);
            $this->db->or_like('p.keterangan', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
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
