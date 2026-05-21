<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Jadwal Pelajaran - Admin
 * Mengelola jadwal pelajaran sekolah
 * 
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Admin
 */
class Jadwal extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load model tahun ajaran jika belum dimuat
        if (!isset($this->M_tahunajaran)) {
            $this->load->model('admin/M_tahunajaran');
        }
        
        // Set role
        $this->role_required = array('admin');
        
        // Load model
        $this->load->model('admin/M_jadwal');
        $this->load->model('admin/M_kelas');
        $this->load->model('admin/M_matapelajaran');
        $this->load->model('admin/M_guru');
        
        // Set data global
        $this->data['page_title'] = 'Jadwal Pelajaran';
        $this->data['active_menu'] = 'jadwal';
    }

    /**
     * Halaman utama jadwal
     * 
     * @return void
     */
    public function index() {
        $tahun_ajaran_id = $this->tahun_ajaran_aktif;
        $this->data['tahun_ajaran'] = $this->M_tahunajaran->get_by_id($tahun_ajaran_id);
        
        $this->data['content'] = 'admin/jadwal';
        $this->load->view('templates/template', $this->data);
    }

    /**
     * AJAX: Get list jadwal untuk DataTables
     * 
     * @return void
     */
    public function ajax_list() {
        $list = $this->M_jadwal->get_datatables($this->tahun_ajaran_aktif);
        $data = array();
        $no = $_POST['start'];
        
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->nama_kelas;
            $row[] = $item->hari;
            $row[] = $item->jam_mulai . ' - ' . $item->jam_selesai;
            $row[] = $item->nama_mapel;
            $row[] = $item->nama_guru;
            $row[] = $item->tahun_ajaran;
            
            // Action buttons
            $action = '<div class="btn-group" role="group">';
            $action .= '<button type="button" class="btn btn-sm btn-info" onclick="edit_jadwal(\'' . encrypt_id($item->id) . '\')" title="Edit"><i class="fas fa-edit"></i></button>';
            $action .= ' <button type="button" class="btn btn-sm btn-danger" onclick="delete_jadwal(\'' . encrypt_id($item->id) . '\')" title="Hapus"><i class="fas fa-trash"></i></button>';
            $action .= '</div>';
            
            $row[] = $action;
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_jadwal->count_all($this->tahun_ajaran_aktif),
            "recordsFiltered" => $this->M_jadwal->count_filtered($this->tahun_ajaran_aktif),
            "data" => $data,
        );
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * AJAX: Tambah jadwal baru
     * 
     * @return void
     */
    public function ajax_add() {
        $this->_validate();
        
        $data = array(
            'id_kelas' => $this->input->post('id_kelas'),
            'id_guru' => $this->input->post('id_guru'),
            'id_mapel' => $this->input->post('id_mapel'),
            'id_tahun_ajaran' => $this->tahun_ajaran_aktif,
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan'),
            'status' => 'aktif'
        );
        
        // Cek bentrok jadwal
        if ($this->M_jadwal->check_conflict($data)) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Jadwal bentrok dengan jadwal yang sudah ada pada kelas, hari, dan jam yang sama'
            )));
            return;
        }
        
        $insert = $this->M_jadwal->save($data);
        
        log_aktivitas('insert', 'tb_jadwal', $insert, 'Tambah jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil ditambahkan'
        )));
    }

    /**
     * AJAX: Edit jadwal
     * 
     * @param string $encrypted_id ID terenkripsi
     * @return void
     */
    public function ajax_edit($encrypted_id) {
        $id = decrypt_id($encrypted_id);
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $data = $this->M_jadwal->get_by_id($id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'data' => $data
        )));
    }

    /**
     * AJAX: Update jadwal
     * 
     * @return void
     */
    public function ajax_update() {
        $id = decrypt_id($this->input->post('id'));
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $this->_validate();
        
        $data = array(
            'id_kelas' => $this->input->post('id_kelas'),
            'id_guru' => $this->input->post('id_guru'),
            'id_mapel' => $this->input->post('id_mapel'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan'),
        );
        
        // Cek bentrok jadwal (kecuali jadwal ini sendiri)
        if ($this->M_jadwal->check_conflict($data, $id)) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Jadwal bentrok dengan jadwal yang sudah ada pada kelas, hari, dan jam yang sama'
            )));
            return;
        }
        
        $this->M_jadwal->update($id, $data);
        
        log_aktivitas('update', 'tb_jadwal', $id, 'Update jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil diperbarui'
        )));
    }

    /**
     * AJAX: Hapus jadwal
     * 
     * @param string $encrypted_id ID terenkripsi
     * @return void
     */
    public function ajax_delete($encrypted_id) {
        $id = decrypt_id($encrypted_id);
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $this->M_jadwal->delete($id);
        
        log_aktivitas('delete', 'tb_jadwal', $id, 'Hapus jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil dihapus'
        )));
    }

    /**
     * AJAX: Get dropdown data
     * 
     * @return void
     */
    public function ajax_get_dropdown() {
        $type = $this->input->get('type');
        
        $data = array();
        
        switch ($type) {
            case 'kelas':
                $result = $this->M_kelas->get_active_classes();
                foreach ($result as $item) {
                    $data[] = array('id' => $item->id, 'text' => $item->nama_kelas);
                }
                break;
            case 'guru':
                $result = $this->M_guru->get_active_teachers();
                foreach ($result as $item) {
                    $data[] = array('id' => $item->id, 'text' => $item->nama_guru);
                }
                break;
            case 'mapel':
                $result = $this->M_matapelajaran->get_active_subjects();
                foreach ($result as $item) {
                    $data[] = array('id' => $item->id, 'text' => $item->nama_mapel);
                }
                break;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Validasi input form
     * 
     * @return void
     */
    private function _validate() {
        $data = array();
        $data['error'] = array();
        $data['status'] = TRUE;
        
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('id_guru', 'Guru', 'required');
        $this->form_validation->set_rules('id_mapel', 'Mata Pelajaran', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required|callback_check_jam_selesai');
        
        if ($this->form_validation->run() === FALSE) {
            $fields = array('id_kelas', 'id_guru', 'id_mapel', 'hari', 'jam_mulai', 'jam_selesai');
            foreach ($fields as $field) {
                if (form_error($field)) {
                    $data['error'][] = form_error($field);
                }
            }
            $data['status'] = FALSE;
        }
        
        if (!$data['status']) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
            exit;
        }
    }

    /**
     * Callback: Cek jam selesai > jam mulai
     * 
     * @param string $str Jam selesai
     * @return bool
     */
    public function check_jam_selesai($str) {
        $jam_mulai = $this->input->post('jam_mulai');
        
        if ($str <= $jam_mulai) {
            $this->form_validation->set_message('check_jam_selesai', 'Jam selesai harus lebih besar dari jam mulai');
            return FALSE;
        }
        
        return TRUE;
    }
}
