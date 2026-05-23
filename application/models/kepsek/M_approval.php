<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_approval extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_pending_approval()
    {
        $this->db->select('a.*, ps.status as status_presensi, ps.keterangan');
        $this->db->select('s.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->select('g.nama_lengkap as nama_guru, u.nama_lengkap as user_nama');
        $this->db->from('tb_approval a');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_presensi_siswa ps', 'ps.id_presensi = p.id');
        $this->db->join('tb_siswa s', 's.id = ps.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->where('a.status_approval', 'pending');
        $this->db->order_by('p.tanggal', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function approve($id_approval, $id_approver)
    {
        $data = [
            'status_approval' => 'disetujui',
            'tanggal_approval' => date('Y-m-d H:i:s'),
            'id_approver' => $id_approver
        ];
        
        $this->db->where('id', $id_approval);
        return $this->db->update('tb_approval', $data);
    }

    public function reject($id_approval, $id_approver, $catatan)
    {
        $this->db->trans_start();
        
        // Update approval status
        $data_approval = [
            'status_approval' => 'ditolak',
            'tanggal_approval' => date('Y-m-d H:i:s'),
            'id_approver' => $id_approver,
            'catatan_penolakan' => $catatan
        ];
        
        $this->db->where('id', $id_approval);
        $this->db->update('tb_approval', $data_approval);
        
        // Get presensi ID and siswa ID
        $this->db->where('id', $id_approval);
        $approval = $this->db->get('tb_approval')->row_array();
        
        if ($approval) {
            // Get the presensi_siswa record
            $this->db->where('id_presensi', $approval['id_presensi']);
            $presensi_siswa = $this->db->get('tb_presensi_siswa')->row_array();
            
            if ($presensi_siswa) {
                // Update presensi_siswa status to Alpa
                $this->db->where('id', $presensi_siswa['id']);
                $this->db->update('tb_presensi_siswa', ['status' => 'Alpa']);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function get_by_id($id)
    {
        $this->db->select('a.*, p.tanggal, ps.status as status_presensi, ps.keterangan');
        $this->db->select('s.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->select('g.nama_lengkap as nama_guru, u.nama_lengkap as user_nama');
        $this->db->from('tb_approval a');
        $this->db->join('tb_presensi p', 'p.id = a.id_presensi');
        $this->db->join('tb_presensi_siswa ps', 'ps.id_presensi = p.id');
        $this->db->join('tb_siswa s', 's.id = ps.id_siswa');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas', 'left');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->where('a.id', $id);
        
        return $this->db->get()->row_array();
    }
}