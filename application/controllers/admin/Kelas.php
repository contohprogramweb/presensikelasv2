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
		
		 
        $data = array_merge($this->data, ['judul' => 'Manajemen Kelas', 'content' => 'admin/kelas']);
        $this->load->view('templates/template', $data);
    }

    public function ajax_list()
    {
        $id_tahun_ajaran = $this->tahun_ajaran_aktif->id ?? null;
        $list = $this->M_kelas->get_all($id_tahun_ajaran);
		


        // FIX #1: draw harus dari POST['draw'] (integer), bukan hardcode 0
        $draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;

        $output = [
            'draw'            => $draw,
            'recordsTotal'    => count($list),
            'recordsFiltered' => count($list),
            'data'            => []
        ];

        foreach ($list as $row) {
            $output['data'][] = [
                '', // Kolom nomor akan di-render oleh DataTables
                $row['nama_kelas'],
                $row['wali_nama'] ?? '-',
                $row['tahun_ajaran'] . '/' . $row['semester'],
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>'
            ];
        }

        // FIX #2: set Content-Type JSON agar tidak ada HTML output sebelum JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|is_unique[tb_kelas.nama_kelas]');
        $this->form_validation->set_rules('id_wali_kelas', 'Wali Kelas', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => validation_errors()]));
            return;
        }

        // FIX #3: tahun_ajaran_aktif bisa null jika belum ada data, guard dulu
        if (!$this->tahun_ajaran_aktif) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Tidak ada tahun ajaran aktif. Harap set tahun ajaran aktif terlebih dahulu.']));
            return;
        }

        $data = [
            'nama_kelas'      => $this->input->post('nama_kelas'),
            'id_wali_kelas'   => $this->input->post('id_wali_kelas') ?: null,
            'id_tahun_ajaran' => $this->tahun_ajaran_aktif->id
        ];

        if ($this->M_kelas->insert($data)) {
            log_aktivitas('INSERT', 'tb_kelas', $this->db->insert_id(), 'Tambah kelas ' . $data['nama_kelas']);
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Kelas berhasil ditambahkan']));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Gagal menambahkan kelas']));
        }
    }

    public function ajax_edit($id)
    {
        $id_decrypted = decrypt_id($id);
        $data = $this->M_kelas->get_by_id($id_decrypted);

        if ($data) {
            // FIX #4: tambah encrypted_id agar view tidak perlu raw ID
            $data['encrypted_id'] = encrypt_id($data['id']);
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'data' => $data]));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan']));
        }
    }

    public function ajax_update()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => validation_errors()]));
            return;
        }

        $data = [
            'nama_kelas'    => $this->input->post('nama_kelas'),
            'id_wali_kelas' => $this->input->post('id_wali_kelas') ?: null
        ];

        if ($this->M_kelas->update($id, $data)) {
            log_aktivitas('UPDATE', 'tb_kelas', $id, 'Update kelas ' . $data['nama_kelas']);
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Kelas berhasil diperbarui']));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Gagal memperbarui kelas']));
        }
    }

    public function ajax_delete($id)
    {
        $id_decrypted = decrypt_id($id);

        if ($this->M_kelas->delete($id_decrypted)) {
            log_aktivitas('DELETE', 'tb_kelas', $id_decrypted, 'Hapus kelas');
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Kelas berhasil dihapus']));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Gagal menghapus kelas']));
        }
    }
	
	
	public function ajax_get_guru_list()
	{
		$guru_list = $this->M_kelas->get_guru_for_select(); // dari model M_kelas
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($guru_list));
	}



}