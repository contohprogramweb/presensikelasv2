<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['admin'];
        $this->load->model('admin/M_kelas');
        $this->load->model('admin/M_tahunajaran');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Kelas';
        $this->load->view('templates/template', ['contents' => $this->load->view('admin/kelas', $data, TRUE)]);
    }

    public function ajax_list()
    {
        $id_tahun_ajaran = $this->tahun_ajaran_aktif['id'] ?? null;
        $list = $this->M_kelas->get_all($id_tahun_ajaran);
        
        $output = [
            'draw' => 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        foreach ($list as $row) {
            $output['data'][] = [
                $row['nama_kelas'],
                $row['wali_nama'] ?? '-',
                $row['tahun'] . '/' . $row['semester'],
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>'
            ];
        }
        
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|is_unique[tb_kelas.nama_kelas]');
        $this->form_validation->set_rules('id_wali_kelas', 'Wali Kelas', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $data = [
            'nama_kelas' => $this->input->post('nama_kelas'),
            'id_wali_kelas' => $this->input->post('id_wali_kelas') ?: null,
            'id_tahun_ajaran' => $this->tahun_ajaran_aktif['id']
        ];
        
        if ($this->M_kelas->insert($data)) {
            log_aktivitas('INSERT', 'tb_kelas', $this->db->insert_id(), 'Tambah kelas ' . $data['nama_kelas']);
            echo json_encode(['status' => true, 'message' => 'Kelas berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan kelas']);
        }
    }

    public function ajax_edit($id)
    {
        $id_decrypted = decrypt_id($id);
        $data = $this->M_kelas->get_by_id($id_decrypted);
        
        if ($data) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }

    public function ajax_update()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $data = [
            'nama_kelas' => $this->input->post('nama_kelas'),
            'id_wali_kelas' => $this->input->post('id_wali_kelas') ?: null
        ];
        
        if ($this->M_kelas->update($id, $data)) {
            log_aktivitas('UPDATE', 'tb_kelas', $id, 'Update kelas ' . $data['nama_kelas']);
            echo json_encode(['status' => true, 'message' => 'Kelas berhasil diperbarui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui kelas']);
        }
    }

    public function ajax_delete($id)
    {
        $id_decrypted = decrypt_id($id);
        
        if ($this->M_kelas->delete($id_decrypted)) {
            log_aktivitas('DELETE', 'tb_kelas', $id_decrypted, 'Hapus kelas');
            echo json_encode(['status' => true, 'message' => 'Kelas berhasil dihapus']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus kelas']);
        }
    }
}
