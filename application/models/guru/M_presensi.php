<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_presensi extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_siswa_by_kelas($id_kelas)
    {
        $this->db->select('s.*, u.nama_lengkap as user_nama');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('s.id_kelas', $id_kelas);
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function simpan_presensi($data_presensi, $data_approval = null)
    {
        $this->db->trans_start();
        
        $inserted_ids = [];
        
        foreach ($data_presensi as $presensi) {
            // Check if already exists
            $this->db->where('id_jadwal', $presensi['id_jadwal']);
            $this->db->where('id_siswa', $presensi['id_siswa']);
            $this->db->where('tanggal', $presensi['tanggal']);
            $existing = $this->db->get('tb_presensi')->row_array();
            
            if ($existing) {
                // Update existing
                $this->db->where('id', $existing['id']);
                $this->db->update('tb_presensi', $presensi);
                $inserted_ids[] = $existing['id'];
            } else {
                // Insert new
                $this->db->insert('tb_presensi', $presensi);
                $inserted_ids[] = $this->db->insert_id();
            }
        }
        
        // Handle approval for Izin/Sakit
        if ($data_approval && !empty($data_approval)) {
            foreach ($data_approval as $approval_data) {
                $this->db->insert('tb_approval', $approval_data);
            }
        }
        
        $this->db->trans_complete();
        
        return [
            'status' => $this->db->trans_status(),
            'inserted_ids' => $inserted_ids
        ];
    }

    public function get_presensi_by_jadwal_tanggal($id_jadwal, $tanggal)
    {
        $this->db->select('p.*, s.nama as nama_siswa, u.nama_lengkap');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_siswa s', 's.id = p.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('p.id_jadwal', $id_jadwal);
        $this->db->where('p.tanggal', $tanggal);
        
        return $this->db->get()->result_array();
    }

    public function get_rekap_presensi($id_guru, $start_date, $end_date, $id_kelas = null)
    {
        $this->db->select('s.id, u.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->select('SUM(CASE WHEN p.status = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $this->db->select('SUM(CASE WHEN p.status = "Izin" THEN 1 ELSE 0 END) as izin');
        $this->db->select('SUM(CASE WHEN p.status = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $this->db->select('SUM(CASE WHEN p.status = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_presensi p', 'p.id_siswa = s.id', 'left');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal', 'left');
        
        $this->db->where('j.id_guru', $id_guru);
        $this->db->where('p.tanggal >=', $start_date);
        $this->db->where('p.tanggal <=', $end_date);
        
        if ($id_kelas) {
            $this->db->where('s.id_kelas', $id_kelas);
        }
        
        $this->db->group_by('s.id');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_kelas_dianggur($id_guru)
    {
        $this->db->select_distinct('k.id, k.nama_kelas');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->where('j.id_guru', $id_guru);
        
        return $this->db->get()->result_array();
    }
}
