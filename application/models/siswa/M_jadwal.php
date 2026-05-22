<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal pelajaran untuk kelas tertentu
     */
    public function get_jadwal_kelas($id_kelas, $id_tahun_ajaran = null)
    {
        if (!$id_tahun_ajaran) {
            // Get tahun ajaran aktif
            $this->db->where('status_aktif', 1);
            $ta = $this->db->get('tb_tahun_ajaran')->row();
            $id_tahun_ajaran = $ta ? $ta->id : null;
        }
        
         $this->db->select('j.*, m.nama_mapel, g.nama_lengkap as nama_guru, u.nama_lengkap as nama_guru_lengkap');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('j.id_kelas', $id_kelas);
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        $this->db->order_by('FIELD(j.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")');
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    public function get_jadwal_siswa($id_kelas, $id_tahun_ajaran = null)
    {
        $this->db->select('j.*, u.nama_lengkap as guru_nama, m.nama_mapel');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_kelas', $id_kelas);

        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }

        $this->db->order_by('FIELD(j.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")');
        $this->db->order_by('j.jam_mulai', 'ASC');

        return $this->db->get()->result_array();
    }
}
