<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    private $table = 'tb_jadwal';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_datatables($id_tahun_ajaran = null)
    {
        $this->db->select('j.*, k.nama_kelas, u.nama_lengkap as guru_nama, m.nama_mapel, t.tahun');
        $this->db->from($this->table . ' j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_tahun_ajaran t', 't.id = j.id_tahun_ajaran');
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_conflict($id_kelas, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('hari', $hari);
        $this->db->where('(jam_mulai <', $jam_selesai);
        $this->db->where('jam_selesai >', $jam_mulai . ')');
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function get_jadwal_by_guru($id_guru, $id_tahun_ajaran = null)
    {
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel');
        $this->db->from($this->table . ' j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_guru', $id_guru);
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_jadwal_by_kelas($id_kelas, $id_tahun_ajaran = null)
    {
        $this->db->select('j.*, u.nama_lengkap as guru_nama, m.nama_mapel');
        $this->db->from($this->table . ' j');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->where('j.id_kelas', $id_kelas);
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_jadwal_hari_ini($id_guru = null)
    {
        $hari_indo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari_ini = $hari_indo[date('l')];
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel, u.nama_lengkap as guru_nama');
        $this->db->from($this->table . ' j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('j.hari', $hari_ini);
        
        if ($id_guru) {
            $this->db->where('j.id_guru', $id_guru);
        }
        
        return $this->db->get()->result_array();
    }
}
