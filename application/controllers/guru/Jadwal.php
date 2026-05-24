<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Guru - Jadwal Mengajar
 */
class Jadwal extends MY_Controller {
    
    protected $role_required = ['guru'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('guru/M_jadwal');
    }
    
    /**
     * Halaman jadwal mengajar guru
     */
    public function index() {
        $this->data['page_title'] = 'Jadwal Mengajar';
        $this->data['jadwal'] = $this->M_jadwal->get_jadwal_guru($this->session->userdata('id'));
        
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
        $this->data['hari_list'] = $hari_list;
        
         $this->load->view('templates/template', ['content' => 'guru/jadwal'] + $this->data);
    }
    
    /**
     * Generate PDF jadwal guru
     */
    public function generate_pdf() {
        // Load data jadwal guru
        $jadwal_list = $this->M_jadwal->get_jadwal_guru($this->session->userdata('id'));
        
        // Get guru info
        $this->db->where('id_user', $this->session->userdata('id'));
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            show_error('Data guru tidak ditemukan');
            return;
        }
        
        // Prepare data for PDF
        $data = array(
            'jadwal_list' => $jadwal_list,
            'guru' => $guru,
            'generated_date' => date('d F Y H:i:s')
        );
        
        // Load PDF library (TCPDF)
        require_once(APPPATH . '../vendor/autoload.php');
        
        // Create PDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistem Presensi Kelas');
        $pdf->SetTitle('Jadwal Mengajar Guru');
        $pdf->SetSubject('Jadwal Mengajar');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add page
        $pdf->AddPage();
        
        // Build HTML content
        $html = '<h2 style="text-align: center; margin-bottom: 10px;">JADWAL MENGAJAR GURU</h2>';
        
        // Info Guru
        $html .= '<table border="0" cellpadding="3" cellspacing="0" style="width: 100%; font-size: 11px; margin-bottom: 20px;">';
        $html .= '<tr>';
        $html .= '<td style="width: 20%; font-weight: bold;">Nama Guru</td>';
        $html .= '<td style="width: 2%;">:</td>';
        $html .= '<td style="width: 78%;">' . strtoupper($guru->nama_lengkap) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">NIP</td>';
        $html .= '<td>:</td>';
        $html .= '<td>' . ($guru->nip ?? '-') . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Define day order
        $days_order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Group jadwal by hari
        $grouped_jadwal = array();
        foreach ($jadwal_list as $jadwal) {
            $hari = $jadwal->hari;
            
            if (!isset($grouped_jadwal[$hari])) {
                $grouped_jadwal[$hari] = array();
            }
            $grouped_jadwal[$hari][] = $jadwal;
        }
        
        // Sort jadwal within each day by jam_mulai
        foreach ($grouped_jadwal as $hari => &$jadwal_array) {
            usort($jadwal_array, function($a, $b) {
                return strcmp($a->jam_mulai, $b->jam_mulai);
            });
        }
        
        // Column widths (must be consistent for th and td)
        $col_width_no = '5%';
        $col_width_jam = '15%';
        $col_width_mapel = '30%';
        $col_width_kelas = '35%';
        $col_width_ruangan = '15%';
        
        // Generate table per hari
        foreach ($days_order as $day) {
            if (isset($grouped_jadwal[$day]) && !empty($grouped_jadwal[$day])) {
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
                $html .= '<th style="width: ' . $col_width_kelas . '; border: 1px solid #000; padding: 4px;">Kelas</th>';
                $html .= '<th style="width: ' . $col_width_ruangan . '; border: 1px solid #000; padding: 4px;">Ruangan</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                
                // Table body
                $no = 1;
                foreach ($grouped_jadwal[$day] as $jadwal) {
                    $html .= '<tr>';
                    $html .= '<td style="width: ' . $col_width_no . '; text-align: center; border: 1px solid #000; padding: 4px;">' . $no++ . '</td>';
                    $html .= '<td style="width: ' . $col_width_jam . '; border: 1px solid #000; padding: 4px;">' . $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai . '</td>';
                    $html .= '<td style="width: ' . $col_width_mapel . '; border: 1px solid #000; padding: 4px;">' . $jadwal->nama_mapel . '</td>';
                    $html .= '<td style="width: ' . $col_width_kelas . '; border: 1px solid #000; padding: 4px;">' . $jadwal->nama_kelas . '</td>';
                    $html .= '<td style="width: ' . $col_width_ruangan . '; border: 1px solid #000; padding: 4px;">' . ($jadwal->ruangan ?? '-') . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody>';
                $html .= '</table>';
            }
        }
        
        // If no data
        if (empty($jadwal_list)) {
            $html .= '<p style="text-align: center; font-style: italic; margin-top: 30px;">Tidak ada jadwal mengajar.</p>';
        }
        
        // Footer
        $html .= '<div style="margin-top: 20px; font-size: 9px; text-align: right;">';
        $html .= 'Dicetak pada: ' . $data['generated_date'];
        $html .= '</div>';
        
        // Output HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF
        $filename = 'jadwal_mengajar_' . strtolower(str_replace(' ', '_', $guru->nama_lengkap)) . '_' . date('YmdHis') . '.pdf';
        $pdf->Output($filename, 'I');
    }
}
