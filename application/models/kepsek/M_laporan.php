<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get statistik presensi untuk kelas dan periode tertentu
     */
    public function get_statistik($id_kelas, $start_date, $end_date)
    {
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN ps.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                COALESCE(SUM(CASE WHEN ps.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                COALESCE(SUM(CASE WHEN ps.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                COALESCE(SUM(CASE WHEN ps.status = 'Alpa' THEN 1 ELSE 0 END), 0) as alpa
            FROM tb_presensi_siswa ps
            JOIN tb_presensi p ON p.id = ps.id_presensi
            WHERE p.id_kelas = ?
                AND ps.tanggal >= ?
                AND ps.tanggal <= ?
        ";
        
        return $this->db->query($sql, [$id_kelas, $start_date, $end_date])->row();
    }
    
    /**
     * Get laporan detail per siswa
     */
    public function get_laporan_detail($id_kelas, $start_date, $end_date)
    {
        $sql = "
            SELECT 
                s.id,
                u.nama_lengkap as nama_siswa,
                COALESCE(SUM(CASE WHEN ps.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                COALESCE(SUM(CASE WHEN ps.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                COALESCE(SUM(CASE WHEN ps.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                COALESCE(SUM(CASE WHEN ps.status = 'Alpa' THEN 1 ELSE 0 END), 0) as alpa
            FROM tb_siswa s
            JOIN tb_user u ON u.id = s.id_user
            LEFT JOIN tb_presensi_siswa ps ON ps.id_siswa = s.id 
                AND ps.tanggal >= ? 
                AND ps.tanggal <= ?
            WHERE s.id_kelas = ?
            GROUP BY s.id, u.nama_lengkap
            ORDER BY u.nama_lengkap ASC
        ";
        
        return $this->db->query($sql, [$start_date, $end_date, $id_kelas])->result();
    }

    public function get_laporan($id_kelas = null, $start_date, $end_date, $status = null)
    {
        $this->db->select('ps.*, s.nama as nama_siswa, k.nama_kelas');
        $this->db->select('m.nama_mapel, j.hari, u.nama_lengkap as user_nama');
        $this->db->from('tb_presensi_siswa ps');
        $this->db->join('tb_presensi p', 'p.id = ps.id_presensi');
        $this->db->join('tb_siswa s', 's.id = ps.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        
        $this->db->where('ps.tanggal >=', $start_date);
        $this->db->where('ps.tanggal <=', $end_date);
        
        if ($id_kelas) {
            $this->db->where('s.id_kelas', $id_kelas);
        }
        
        if ($status) {
            $this->db->where('ps.status', $status);
        }
        
        $this->db->order_by('ps.tanggal', 'DESC');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_all_kelas()
    {
        $this->db->select('id, nama_kelas');
        $this->db->from('tb_kelas');
        $this->db->order_by('nama_kelas', 'ASC');
        
        return $this->db->get()->result_array();
    }
}
