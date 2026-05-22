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

    public function simpan_presensi($data_presensi)
    {
        $this->db->trans_start();
        
        $inserted_ids = [];
        
        foreach ($data_presensi as $presensi) {
            // Extract presensi header data (untuk tb_presensi)
            $id_jadwal = $presensi['id_jadwal'];
            $tanggal = $presensi['tanggal'];
            $id_guru = $presensi['id_guru'];
            $materi_pelajaran = $presensi['materi_pelajaran'];
            
            // Check if presensi header already exists for this jadwal and tanggal
            $this->db->where('id_jadwal', $id_jadwal);
            $this->db->where('tanggal', $tanggal);
            $existing_header = $this->db->get('tb_presensi')->row_array();
            
            if ($existing_header) {
                // Update existing header
                $this->db->where('id', $existing_header['id']);
                $this->db->update('tb_presensi', [
                    'materi_pelajaran' => $materi_pelajaran,
                    'id_guru' => $id_guru
                ]);
                $id_presensi_header = $existing_header['id'];
            } else {
                // Insert new header
                $header_data = [
                    'id_jadwal' => $id_jadwal,
                    'id_guru' => $id_guru,
                    'tanggal' => $tanggal,
                    'materi_pelajaran' => $materi_pelajaran
                ];
                $this->db->insert('tb_presensi', $header_data);
                $id_presensi_header = $this->db->insert_id();
            }
            
            // Insert/update detail presensi siswa
            $id_siswa = $presensi['id_siswa'];
            $status = $presensi['status'];
            $keterangan = $presensi['keterangan'];
            
            // Check if detail already exists
            $this->db->where('id_presensi', $id_presensi_header);
            $this->db->where('id_siswa', $id_siswa);
            $existing_detail = $this->db->get('tb_presensi_siswa')->row_array();
            
            if ($existing_detail) {
                // Update existing detail
                $this->db->where('id', $existing_detail['id']);
                $this->db->update('tb_presensi_siswa', [
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);
                $inserted_ids[] = $existing_detail['id'];
                
                // If updating to Izin/Sakit, need to create approval
                if (in_array($status, ['Izin', 'Sakit'])) {
                    $approval_data = [
                        'id_presensi' => $id_presensi_header,
                        'id_siswa' => $id_siswa,
                        'id_guru' => $presensi['id_guru'],
                        'tanggal' => $tanggal,
                        'status_asli' => $status,
                        'status_approval' => 'pending'
                    ];
                    $this->db->insert('tb_approval', $approval_data);
                }
            } else {
                // Insert new detail
                $detail_data = [
                    'id_presensi' => $id_presensi_header,
                    'id_siswa' => $id_siswa,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan
                ];
                $this->db->insert('tb_presensi_siswa', $detail_data);
                $new_detail_id = $this->db->insert_id();
                $inserted_ids[] = $new_detail_id;
                
                // If Izin/Sakit, create approval record
                if (in_array($status, ['Izin', 'Sakit'])) {
                    $approval_data = [
                        'id_presensi' => $id_presensi_header,
                        'id_siswa' => $id_siswa,
                        'id_guru' => $presensi['id_guru'],
                        'tanggal' => $tanggal,
                        'status_asli' => $status,
                        'status_approval' => 'pending'
                    ];
                    $this->db->insert('tb_approval', $approval_data);
                }
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
        $this->db->select('ps.*, s.nama_lengkap as nama_siswa, u.nama_lengkap');
        $this->db->from('tb_presensi_siswa ps');
        $this->db->join('tb_presensi p', 'p.id = ps.id_presensi');
        $this->db->join('tb_siswa s', 's.id = ps.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('p.id_jadwal', $id_jadwal);
        $this->db->where('p.tanggal', $tanggal);
        
        return $this->db->get()->result_array();
    }

    public function get_rekap_presensi($id_guru, $start_date, $end_date, $id_kelas = null)
    {
        $this->db->select('s.id, u.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->select('SUM(CASE WHEN ps.status = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $this->db->select('SUM(CASE WHEN ps.status = "Izin" THEN 1 ELSE 0 END) as izin');
        $this->db->select('SUM(CASE WHEN ps.status = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $this->db->select('SUM(CASE WHEN ps.status = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_presensi_siswa ps', 'ps.id_siswa = s.id', 'left');
        $this->db->join('tb_presensi p', 'p.id = ps.id_presensi', 'left');
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
