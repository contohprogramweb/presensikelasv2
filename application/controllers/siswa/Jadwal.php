<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Siswa - Jadwal Pelajaran
 */
class Jadwal extends MY_Controller {
    
    protected $role_required = ['siswa'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('siswa/M_jadwal');
    }
    
    /**
     * Halaman jadwal pelajaran siswa
     */
    public function index() {
        $this->data['page_title'] = 'Jadwal Pelajaran';
        
        // Get id_siswa dari tb_siswa berdasarkan session user
        $id_user = $this->session->userdata('id');
        $this->db->where('id_user', $id_user);
        $siswa = $this->db->get('tb_siswa')->row();
        
        if (!$siswa || !$siswa->id_kelas) {
            $this->data['error_message'] = 'Anda belum ditempatkan di kelas. Hubungi admin.';
            $this->data['jadwal'] = [];
            $this->data['jadwal_grouped'] = [];
            $this->data['siswa_info'] = null;
        } else {
            $this->data['jadwal'] = $this->M_jadwal->get_jadwal_kelas($siswa->id_kelas);
            
            // Get info siswa dan kelas untuk PDF
            $this->db->select('s.nis, s.nama_lengkap as nama_siswa, k.nama_kelas');
            $this->db->from('tb_siswa s');
            $this->db->join('tb_kelas k', 'k.id = s.id_kelas');
            $this->db->where('s.id_user', $id_user);
            $this->data['siswa_info'] = $this->db->get()->row();
            
            // Group by hari
            $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $jadwal_grouped = [];
            
            foreach ($hari_list as $hari) {
                $jadwal_grouped[$hari] = [];
            }
            
            foreach ($this->data['jadwal'] as $j) {
                $jadwal_grouped[$j->hari][] = $j;
            }
            
            $this->data['jadwal_grouped'] = $jadwal_grouped;
        }
        
        $this->data['hari_list'] = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
         
		$this->data['content'] = 'siswa/jadwal'; 
        $this->load->view('templates/template', $this->data);
		
		
    }
    
    /**
     * Preview Jadwal Pelajaran dalam format PDF (tampil di browser)
     */
    public function preview_pdf() {
        // Get id_siswa dari tb_siswa berdasarkan session user
        $id_user = $this->session->userdata('id');
        $this->db->where('id_user', $id_user);
        $siswa = $this->db->get('tb_siswa')->row();
        
        if (!$siswa || !$siswa->id_kelas) {
            show_error('Anda belum ditempatkan di kelas.');
            return;
        }
        
        // Get data jadwal
        $jadwal = $this->M_jadwal->get_jadwal_kelas($siswa->id_kelas);
        
        // Get info siswa dan kelas
        $this->db->select('s.nis, s.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas');
        $this->db->where('s.id_user', $id_user);
        $siswa_info = $this->db->get()->row();
        
        // Group by hari
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwal_grouped = [];
        
        foreach ($hari_list as $hari) {
            $jadwal_grouped[$hari] = [];
        }
        
        foreach ($jadwal as $j) {
            $jadwal_grouped[$j->hari][] = $j;
        }
        
        // Load TCPDF library
        require_once(APPPATH . '../vendor/autoload.php');
        
        // Create PDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistem Presensi Kelas');
        $pdf->SetTitle('Jadwal Pelajaran Siswa');
        $pdf->SetSubject('Jadwal Pelajaran');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add page
        $pdf->AddPage();
        
        // Generate HTML content
        $html = $this->_generate_html_jadwal_tcpdf($siswa_info, $jadwal_grouped, $hari_list);
        
        // Output HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF (inline - display in browser)
        $filename = 'Jadwal_Pelajaran_'.$siswa_info->nama_siswa.'.pdf';
        $pdf->Output($filename, 'I');
    }
    
    /**
     * Export Jadwal Pelajaran ke PDF (download langsung)
     */
    public function export_pdf() {
        // Get id_siswa dari tb_siswa berdasarkan session user
        $id_user = $this->session->userdata('id');
        $this->db->where('id_user', $id_user);
        $siswa = $this->db->get('tb_siswa')->row();
        
        if (!$siswa || !$siswa->id_kelas) {
            $this->session->set_flashdata('error', 'Anda belum ditempatkan di kelas.');
            redirect('siswa/jadwal');
        }
        
        // Get data jadwal
        $jadwal = $this->M_jadwal->get_jadwal_kelas($siswa->id_kelas);
        
        // Get info siswa dan kelas
        $this->db->select('s.nis, s.nama_lengkap as nama_siswa, k.nama_kelas');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas');
        $this->db->where('s.id_user', $id_user);
        $siswa_info = $this->db->get()->row();
        
        // Group by hari
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwal_grouped = [];
        
        foreach ($hari_list as $hari) {
            $jadwal_grouped[$hari] = [];
        }
        
        foreach ($jadwal as $j) {
            $jadwal_grouped[$j->hari][] = $j;
        }
        
        // Generate HTML untuk PDF
        $html = $this->_generate_html_jadwal($siswa_info, $jadwal_grouped, $hari_list);
        
        // Load library dompdf
        $this->load->library('dompdf_generator');
        
        $filename = 'Jadwal_Pelajaran_'.$siswa_info->nama_siswa.'_'.date('YmdHis').'.pdf';
        $this->dompdf_generator->generate($html, $filename, 'A4', 'portrait', true);
    }
    
    /**
     * Generate HTML untuk PDF Jadwal (TCPDF format)
     */
    private function _generate_html_jadwal_tcpdf($siswa_info, $jadwal_grouped, $hari_list) {
        $html = '<h2 style="text-align: center; margin-bottom: 10px;">JADWAL PELAJARAN SISWA</h2>';
        
        // Info Siswa
        $html .= '<table border="0" cellpadding="3" cellspacing="0" style="width: 100%; font-size: 11px; margin-bottom: 20px;">';
        $html .= '<tr>';
        $html .= '<td style="width: 20%; font-weight: bold;">Nama Siswa</td>';
        $html .= '<td style="width: 2%;">:</td>';
        $html .= '<td style="width: 78%;">' . strtoupper($siswa_info->nama_siswa) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">NIS</td>';
        $html .= '<td>:</td>';
        $html .= '<td>' . ($siswa_info->nis ?? '-') . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">Kelas</td>';
        $html .= '<td>:</td>';
        $html .= '<td>' . strtoupper($siswa_info->nama_kelas) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">Tanggal Cetak</td>';
        $html .= '<td>:</td>';
        $html .= '<td>' . tanggal_indo(date('Y-m-d')) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Define day order
        $days_order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Column widths (must be consistent for th and td)
        $col_width_no = '5%';
        $col_width_jam = '15%';
        $col_width_mapel = '30%';
        $col_width_guru = '30%';
        $col_width_ruangan = '20%';
        
        // Generate table per hari
        foreach ($days_order as $day) {
            if (isset($jadwal_grouped[$day]) && !empty($jadwal_grouped[$day])) {
                // Day header
                $html .= '<div style="background-color: #2196F3; color: white; padding: 6px; font-weight: bold; font-size: 12px; margin-top: 15px; margin-bottom: 8px;">';
                $html .= 'HARI ' . strtoupper($day);
                $html .= '</div>';
                
                // Table for this day
                $html .= '<table border="1" cellpadding="3" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 10px;">';
                $html .= '<thead>';
                $html .= '<tr style="background-color: #f0f0f0;">';
                $html .= '<th style="width: ' . $col_width_no . '; text-align: center; border: 1px solid #000; padding: 4px;">No</th>';
                $html .= '<th style="width: ' . $col_width_jam . '; border: 1px solid #000; padding: 4px;">Jam</th>';
                $html .= '<th style="width: ' . $col_width_mapel . '; border: 1px solid #000; padding: 4px;">Mata Pelajaran</th>';
                $html .= '<th style="width: ' . $col_width_guru . '; border: 1px solid #000; padding: 4px;">Guru Pengampu</th>';
                $html .= '<th style="width: ' . $col_width_ruangan . '; border: 1px solid #000; padding: 4px;">Ruangan</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                
                // Table body
                $no = 1;
                foreach ($jadwal_grouped[$day] as $jadwal) {
                    $jam_mulai = date('H:i', strtotime($jadwal->jam_mulai));
                    $jam_selesai = date('H:i', strtotime($jadwal->jam_selesai));
                    $nama_guru = $jadwal->nama_guru_lengkap ?? $jadwal->nama_guru ?? '-';
                    $ruangan = $jadwal->ruangan ?? '-';
                    
                    $html .= '<tr>';
                    $html .= '<td style="width: ' . $col_width_no . '; text-align: center; border: 1px solid #000; padding: 4px;">' . $no++ . '</td>';
                    $html .= '<td style="width: ' . $col_width_jam . '; border: 1px solid #000; padding: 4px;">' . $jam_mulai . ' - ' . $jam_selesai . '</td>';
                    $html .= '<td style="width: ' . $col_width_mapel . '; border: 1px solid #000; padding: 4px;">' . $jadwal->nama_mapel . '</td>';
                    $html .= '<td style="width: ' . $col_width_guru . '; border: 1px solid #000; padding: 4px;">' . $nama_guru . '</td>';
                    $html .= '<td style="width: ' . $col_width_ruangan . '; border: 1px solid #000; padding: 4px;">' . $ruangan . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody>';
                $html .= '</table>';
            }
        }
        
        // If no data
        $has_data = false;
        foreach ($jadwal_grouped as $day => $items) {
            if (!empty($items)) {
                $has_data = true;
                break;
            }
        }
        
        if (!$has_data) {
            $html .= '<p style="text-align: center; font-style: italic; margin-top: 30px;">Tidak ada jadwal pelajaran.</p>';
        }
        
        // Footer
        $html .= '<div style="margin-top: 20px; font-size: 9px; text-align: right;">';
        $html .= 'Dicetak pada: ' . date('d/m/Y H:i:s');
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Generate HTML untuk PDF Jadwal (legacy dompdf format)
     */
    private function _generate_html_jadwal($siswa_info, $jadwal_grouped, $hari_list) {
        $html = '
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page { margin: 20px; }
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .header h2 { margin: 5px 0; font-size: 16px; }
                .header p { margin: 3px 0; font-size: 11px; }
                .info { margin-bottom: 15px; }
                .info table { width: 100%; border-collapse: collapse; }
                .info td { padding: 5px; }
                table.jadwal { width: 100%; border-collapse: collapse; margin-top: 10px; }
                table.jadwal th, table.jadwal td { border: 1px solid #333; padding: 6px; text-align: center; }
                table.jadwal th { background-color: #f0f0f0; font-weight: bold; }
                table.jadwal td.text-left { text-align: left; }
                .hari-section { margin-top: 15px; }
                .hari-title { background-color: #0d6efd; color: white; padding: 8px; font-weight: bold; text-align: center; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SMPTK GALANG KASIH UBUNG</h2>
                <p>Jl. Raya Ubung No. 123, Denpasar, Bali</p>
                <h3 style="margin: 15px 0 5px 0;">JADWAL PELAJARAN</h3>
            </div>
            
            <div class="info">
                <table>
                    <tr>
                        <td width="150"><strong>Nama Siswa</strong></td>
                        <td>: '.html_escape($siswa_info->nama_siswa).'</td>
                    </tr>
                    <tr>
                        <td><strong>NIS</strong></td>
                        <td>: '.html_escape($siswa_info->nis).'</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>: '.html_escape($siswa_info->nama_kelas).'</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Cetak</strong></td>
                        <td>: '.tanggal_indo(date('Y-m-d')).'</td>
                    </tr>
                </table>
            </div>';
        
        foreach ($hari_list as $hari) {
            $html .= '
            <div class="hari-section">
                <div class="hari-title">'.$hari.'</div>
                <table class="jadwal">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Jam</th>
                            <th width="30%">Mata Pelajaran</th>
                            <th width="30%">Guru Pengampu</th>
                            <th width="20%">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if (empty($jadwal_grouped[$hari])) {
                $html .= '<tr><td colspan="5">Tidak ada jadwal pada hari '.$hari.'</td></tr>';
            } else {
                $no = 1;
                foreach ($jadwal_grouped[$hari] as $j) {
                    $jam_mulai = date('H:i', strtotime($j->jam_mulai));
                    $jam_selesai = date('H:i', strtotime($j->jam_selesai));
                    $nama_guru = html_escape($j->nama_guru_lengkap ?? $j->nama_guru ?? '-');
                    $ruangan = html_escape($j->ruangan ?? '-');
                    
                    $html .= '
                    <tr>
                        <td>'.$no++.'</td>
                        <td>'.$jam_mulai.' - '.$jam_selesai.'</td>
                        <td class="text-left">'.html_escape($j->nama_mapel).'</td>
                        <td class="text-left">'.$nama_guru.'</td>
                        <td class="text-left">'.$ruangan.'</td>
                    </tr>';
                }
            }
            
            $html .= '
                    </tbody>
                </table>
            </div>';
        }
        
        $html .= '
            <div class="footer">
                <p>Sistem Informasi Presensi Siswa - SMPTK Galang Kasih Ubung</p>
                <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Halaman 1</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
