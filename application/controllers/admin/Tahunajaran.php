<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Admin - Tahun Ajaran
 * Manajemen tahun ajaran akademik
 */
class Tahunajaran extends MY_Controller {
    
    protected $role_required = ['admin'];
    private $table = 'tb_tahun_ajaran';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/M_tahunajaran');
    }
    
    /**
     * Halaman index tahun ajaran
     */
    public function index() {
        $this->data['page_title'] = 'Manajemen Tahun Ajaran';
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->load->view('templates/template', 'admin/tahunajaran', $this->data);
    }
    
    /**
     * AJAX: List data untuk DataTables
     */
    public function ajax_list() {
        $list = $this->M_tahunajaran->get_datatables();
        $data = [];
        $no = $_POST['start'];
        
        foreach ($list as $ta) {
            $no++;
            $status_badge = $ta->status_aktif == 1 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-secondary">Tidak Aktif</span>';
            
            $action_btn = '
                <button class="btn btn-sm btn-info" onclick="edit_tahunajaran(\''.encrypt_id($ta->id).'\')" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                '.($ta->status_aktif != 1 ? '
                <button class="btn btn-sm btn-danger" onclick="delete_tahunajaran(\''.encrypt_id($ta->id).'\')" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>' : '').'
            ';
            
            $data[] = [
                $no,
                $ta->tahun_ajaran,
                $ta->semester == 1 ? 'Ganjil' : 'Genap',
                $status_badge,
                tanggal_indo($ta->tanggal_dibuat),
                $action_btn
            ];
        }
        
        $output = [
            'draw' => $_POST['draw'],
            'recordsTotal' => $this->M_tahunajaran->count_all(),
            'recordsFiltered' => $this->M_tahunajaran->count_filtered(),
            'data' => $data,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
    
    /**
     * AJAX: Tambah tahun ajaran
     */
    public function ajax_add() {
        $this->_validate();
        
        $data = [
            'tahun_ajaran' => $this->input->post('tahun_ajaran'),
            'semester' => $this->input->post('semester'),
            'status_aktif' => $this->input->post('status_aktif'),
            'tanggal_dibuat' => date('Y-m-d H:i:s')
        ];
        
        // Jika aktif, set yang lain jadi tidak aktif
        if ($data['status_aktif'] == 1) {
            $this->db->update($this->table, ['status_aktif' => 0]);
        }
        
        $insert = $this->M_tahunajaran->save($data);
        
        log_aktivitas('insert', $this->table, $this->db->insert_id(), 'Tambah tahun ajaran '.$data['tahun_ajaran']);
        
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan',
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]));
    }
    
    /**
     * AJAX: Edit data tahun ajaran
     */
    public function ajax_edit($id) {
        $id = decrypt_id($id);
        $data = $this->M_tahunajaran->get_by_id($id);
        
        $output = [
            'status' => true,
            'data' => $data,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
    
    /**
     * AJAX: Update tahun ajaran
     */
    public function ajax_update() {
        $this->_validate();
        
        $id = decrypt_id($this->input->post('id'));
        
        $data = [
            'tahun_ajaran' => $this->input->post('tahun_ajaran'),
            'semester' => $this->input->post('semester'),
            'status_aktif' => $this->input->post('status_aktif')
        ];
        
        // Jika aktif, set yang lain jadi tidak aktif (kecuali diri sendiri)
        if ($data['status_aktif'] == 1) {
            $this->db->where('id !=', $id)->update($this->table, ['status_aktif' => 0]);
        }
        
        $this->M_tahunajaran->update(['id' => $id], $data);
        
        log_aktivitas('update', $this->table, $id, 'Update tahun ajaran '.$data['tahun_ajaran']);
        
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => true,
            'message' => 'Tahun ajaran berhasil diupdate',
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]));
    }
    
    /**
     * AJAX: Delete tahun ajaran
     */
    public function ajax_delete($id) {
        $id = decrypt_id($id);
        
        // Cek apakah sedang aktif
        $ta = $this->M_tahunajaran->get_by_id($id);
        if ($ta->status_aktif == 1) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => false,
                'message' => 'Tidak dapat menghapus tahun ajaran yang sedang aktif'
            ]));
            return;
        }
        
        $this->M_tahunajaran->delete_by_id($id);
        
        log_aktivitas('delete', $this->table, $id, 'Hapus tahun ajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => true,
            'message' => 'Tahun ajaran berhasil dihapus',
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]));
    }
    
    /**
     * Validasi form
     */
    private function _validate() {
        $data = [];
        $data['error'] = [];
        $data['status'] = true;
        
        if ($this->input->post('tahun_ajaran', TRUE) == '') {
            $data['error']['tahun_ajaran'] = 'Tahun ajaran wajib diisi';
            $data['status'] = false;
        }
        
        if (!in_array($this->input->post('semester', TRUE), ['1', '2'])) {
            $data['error']['semester'] = 'Semester harus 1 atau 2';
            $data['status'] = false;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
