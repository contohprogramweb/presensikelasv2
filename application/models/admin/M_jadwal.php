<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {

    private $table = 'tb_jadwal';
    private $column_order = array(null, 'k.nama_kelas', 'j.hari', 'j.jam_mulai', 'm.nama_mapel', 'u.nama_lengkap', 't.tahun_ajaran');
    private $column_search = array('k.nama_kelas', 'j.hari', 'm.nama_mapel', 'u.nama_lengkap', 't.tahun_ajaran');
    private $order = array('k.nama_kelas' => 'ASC', 'j.hari' => 'ASC', 'j.jam_mulai' => 'ASC');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($id_tahun_ajaran = null)
    {
        $this->db->select('j.*, k.nama_kelas, u.nama_lengkap as nama_guru, m.nama_mapel, t.tahun_ajaran as tahun_ajaran');
        $this->db->from($this->table . ' j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_tahun_ajaran t', 't.id = j.id_tahun_ajaran');
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        
        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']], 
                $_POST['order']['0']['dir']
            );
        } elseif (isset($this->order)) {
            foreach ($this->order as $col => $dir) {
                $this->db->order_by($col, $dir);
            }
        }
    }

    public function get_datatables($id_tahun_ajaran = null)
    {
        $this->_get_datatables_query($id_tahun_ajaran);
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all($id_tahun_ajaran = null)
    {
        $this->db->from($this->table . ' j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel');
        $this->db->join('tb_tahun_ajaran t', 't.id = j.id_tahun_ajaran');
        
        if ($id_tahun_ajaran) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        return $this->db->count_all_results();
    }

    public function count_filtered($id_tahun_ajaran = null)
    {
        $this->_get_datatables_query($id_tahun_ajaran);
        return $this->db->get()->num_rows();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
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

    public function check_conflict($data, $exclude_id = null)
    {
        $this->db->where('id_kelas', $data['id_kelas']);
        $this->db->where('hari', $data['hari']);
         
        $this->db->where('status_aktif', 1);
		// Check for overlapping time ranges
        $this->db->where('(jam_mulai < "' . $this->db->escape_str($data['jam_selesai']) . '" AND jam_selesai > "' . $this->db->escape_str($data['jam_mulai']) . '")');
		
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