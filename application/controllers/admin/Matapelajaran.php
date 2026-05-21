<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matapelajaran extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['admin'];
        $this->load->model('admin/M_matapelajaran');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array_merge($this->data, ['judul' => 'Manajemen Mata Pelajaran', 'content' => 'admin/matapelajaran']);
        $this->load->view('templates/template', $data);
    }

    public function ajax_list()
    {
        // Get DataTables parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_input = $this->input->post('search');
        $search = isset($search_input['value']) ? $search_input['value'] : '';
        
        // Get all data
        $all_data = $this->M_matapelajaran->get_all();
        
        // Apply search filter if exists
        $filtered_data = $all_data;
        if (!empty($search)) {
            $filtered_data = array_filter($all_data, function($row) use ($search) {
                return stripos($row['nama_mapel'], $search) !== false || stripos($row['kode_mapel'], $search) !== false;
            });
            $filtered_data = array_values($filtered_data);
        }
        
        // Apply pagination
        if ($length > 0) {
            $paginated_data = array_slice($filtered_data, $start, $length);
        } else {
            $paginated_data = $filtered_data;
        }
        
        // Format output for DataTables
        $output = [
            'draw' => $draw,
            'recordsTotal' => count($all_data),
            'recordsFiltered' => count($filtered_data),
            'data' => [],
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        
        foreach ($paginated_data as $row) {
            $output['data'][] = [
                '', // Nomor akan di-generate oleh DataTables
                $row['kode_mapel'],
                $row['nama_mapel'],
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>'
            ];
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim|is_unique[tb_mata_pelajaran.kode_mapel]');
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim|is_unique[tb_mata_pelajaran.nama_mapel]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $data = [
            'kode_mapel' => $this->input->post('kode_mapel'),
            'nama_mapel' => $this->input->post('nama_mapel')
        ];
        
        if ($this->M_matapelajaran->insert($data)) {
            log_aktivitas('INSERT', 'tb_mata_pelajaran', $this->db->insert_id(), 'Tambah mapel ' . $data['nama_mapel']);
            echo json_encode(['status' => true, 'message' => 'Mata pelajaran berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan mata pelajaran']);
        }
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        if (!$id) {
            $id = $this->uri->segment(4);
        }
        
        $id_decrypted = decrypt_id($id);
        $data = $this->M_matapelajaran->get_by_id($id_decrypted);
        
        if ($data) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['status' => true, 'data' => $data]));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan']));
        }
    }

    public function ajax_update()
    {
        $id_encrypted = $this->input->post('id');
        
        if (!$id_encrypted) {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
            return;
        }
        
        $id = decrypt_id($id_encrypted);
        
        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim');
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        // Check if kode_mapel or nama_mapel already exists (excluding current record)
        $this->db->where('kode_mapel', $this->input->post('kode_mapel'));
        $this->db->where('id !=', $id);
        $check_kode = $this->db->get('tb_mata_pelajaran')->row();
        
        if ($check_kode) {
            echo json_encode(['status' => false, 'message' => 'Kode mata pelajaran sudah digunakan']);
            return;
        }
        
        $this->db->where('nama_mapel', $this->input->post('nama_mapel'));
        $this->db->where('id !=', $id);
        $check_nama = $this->db->get('tb_mata_pelajaran')->row();
        
        if ($check_nama) {
            echo json_encode(['status' => false, 'message' => 'Nama mata pelajaran sudah digunakan']);
            return;
        }
        
        $data = [
            'kode_mapel' => $this->input->post('kode_mapel'),
            'nama_mapel' => $this->input->post('nama_mapel')
        ];
        
        if ($this->M_matapelajaran->update($id, $data)) {
            log_aktivitas('UPDATE', 'tb_mata_pelajaran', $id, 'Update mapel ' . $data['nama_mapel']);
            echo json_encode(['status' => true, 'message' => 'Mata pelajaran berhasil diperbarui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui mata pelajaran']);
        }
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        
        if (!$id) {
            echo json_encode([
                'status' => false, 
                'message' => 'ID tidak valid',
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }
        
        $id_decrypted = decrypt_id($id);
        
        if ($this->M_matapelajaran->delete($id_decrypted)) {
            log_aktivitas('DELETE', 'tb_mata_pelajaran', $id_decrypted, 'Hapus mapel');
            echo json_encode([
                'status' => true, 
                'message' => 'Mata pelajaran berhasil dihapus',
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => false, 
                'message' => 'Gagal menghapus mata pelajaran',
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
        }
    }
}
