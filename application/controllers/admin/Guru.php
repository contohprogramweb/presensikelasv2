<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['admin'];
        $this->load->model('admin/M_guru');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Guru';
        $this->load->view('templates/template', ['contents' => $this->load->view('admin/guru', $data, TRUE)]);
    }

    public function ajax_list()
    {
        $list = $this->M_guru->get_all_datatables();
        
        $output = [
            'draw' => 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        foreach ($list as $row) {
            $jk_badge = $row['jenis_kelamin'] == 'L' ? '<span class="badge bg-info">L</span>' : '<span class="badge bg-pink">P</span>';
            $status_badge = $row['user_status'] == 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>';
            
            $output['data'][] = [
                $row['nip'],
                $row['nama'],
                $jk_badge,
                $row['no_hp'] ?? '-',
                $row['username'],
                $status_badge,
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>'
            ];
        }
        
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim|is_unique[tb_guru.nip]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $username = $this->input->post('nip');
        $default_password = 'guru123';
        $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);
        
        // Create user first
        $user_data = [
            'username' => $username,
            'password' => $hashed_password,
            'nama_lengkap' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'role' => 'guru',
            'status' => 'aktif'
        ];
        
        $this->db->insert('tb_user', $user_data);
        $id_user = $this->db->insert_id();
        
        // Create guru
        $guru_data = [
            'nip' => $this->input->post('nip'),
            'id_user' => $id_user,
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp')
        ];
        
        if ($this->M_guru->insert($guru_data)) {
            log_aktivitas('INSERT', 'tb_guru', $this->db->insert_id(), 'Tambah guru ' . $guru_data['nama']);
            echo json_encode(['status' => true, 'message' => 'Guru berhasil ditambahkan']);
        } else {
            // Rollback user
            $this->db->where('id', $id_user);
            $this->db->delete('tb_user');
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan guru']);
        }
    }

    public function ajax_edit($id)
    {
        $id_decrypted = decrypt_id($id);
        $data = $this->M_guru->get_by_id($id_decrypted);
        
        if ($data) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }

    public function ajax_update()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $guru_data = [
            'nip' => $this->input->post('nip'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp')
        ];
        
        // Update user info
        $user_data = [
            'nama_lengkap' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp')
        ];
        
        $guru = $this->M_guru->get_by_id($id);
        
        $this->db->trans_start();
        $this->M_guru->update($id, $guru_data);
        $this->db->where('id', $guru['id_user']);
        $this->db->update('tb_user', $user_data);
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            log_aktivitas('UPDATE', 'tb_guru', $id, 'Update guru ' . $guru_data['nama']);
            echo json_encode(['status' => true, 'message' => 'Guru berhasil diperbarui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui guru']);
        }
    }

    public function ajax_delete($id)
    {
        $id_decrypted = decrypt_id($id);
        $guru = $this->M_guru->get_by_id($id_decrypted);
        
        $this->db->trans_start();
        $this->M_guru->delete($id_decrypted);
        $this->db->where('id', $guru['id_user']);
        $this->db->delete('tb_user');
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            log_aktivitas('DELETE', 'tb_guru', $id_decrypted, 'Hapus guru');
            echo json_encode(['status' => true, 'message' => 'Guru berhasil dihapus']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus guru']);
        }
    }

    public function ajax_list_guru_select()
    {
        $this->db->select('g.id, u.nama_lengkap');
        $this->db->from('tb_guru g');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('u.status', 'aktif');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        $list = $this->db->get()->result_array();
        echo json_encode($list);
    }
}
