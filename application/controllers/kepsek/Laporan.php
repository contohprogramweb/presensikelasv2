<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Kepsek - Laporan Presensi
 */
class Laporan extends MY_Controller {
    
    protected $role_required = ['kepsek'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('kepsek/M_laporan');
    }
    
    /**
     * Halaman laporan presensi
     */
    public function index() {
        $this->data['page_title'] = 'Laporan Presensi';
        
        // Get semua kelas aktif
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_kelas', 'ASC');
        $this->data['kelas_list'] = $this->db->get('tb_kelas')->result();
        
        $this->data['filter_kelas'] = null;
        $this->data['filter_start'] = null;
        $this->data['filter_end'] = null;
        $this->data['statistik'] = null;
        $this->data['laporan'] = [];
        
        if ($this->input->get('kelas') && $this->input->get('start_date') && $this->input->get('end_date')) {
            $id_kelas = $this->input->get('kelas');
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            
            $this->data['filter_kelas'] = $id_kelas;
            $this->data['filter_start'] = $start_date;
            $this->data['filter_end'] = $end_date;
            
            $this->data['statistik'] = $this->M_laporan->get_statistik($id_kelas, $start_date, $end_date);
            $this->data['laporan'] = $this->M_laporan->get_laporan_detail($id_kelas, $start_date, $end_date);
        }
        
         $this->load->view('templates/template', ['content' => 'kepsek/laporan'] + $this->data);
    }
    
    /**
     * Export PDF laporan
     */
    public function export_pdf() {
        $id_kelas = $this->input->get('kelas');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        if (!$id_kelas || !$start_date || !$end_date) {
            $this->session->set_flashdata('error', 'Filter tidak lengkap');
            redirect('kepsek/laporan');
        }
        
        $statistik = $this->M_laporan->get_statistik($id_kelas, $start_date, $end_date);
        $laporan = $this->M_laporan->get_laporan_detail($id_kelas, $start_date, $end_date);
        
        $this->db->select('nama_kelas');
        $kelas = $this->db->get_where('tb_kelas', ['id' => $id_kelas])->row();
        
        $html = $this->_generate_html($kelas->nama_kelas, $start_date, $end_date, $statistik, $laporan);
        
        $this->load->library('dompdf_generator');
        $filename = 'Laporan_Presensi_'.$kelas->nama_kelas.'_'.date('YmdHis').'.pdf';
        $this->dompdf_generator->generate($html, $filename, 'A4', 'portrait', true);
    }
    
    /**
     * Generate HTML untuk PDF
     */
    private function _generate_html($kelas, $start_date, $end_date, $statistik, $laporan) {
        $start_indo = tanggal_indo($start_date);
        $end_indo = tanggal_indo($end_date);
        
        $html = '
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page { margin: 20px; }
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .header h2 { margin: 5px 0; font-size: 16px; }
                .header p { margin: 3px 0; font-size: 10px; }
                .info { margin-bottom: 15px; }
                .statistik { margin-bottom: 20px; }
                .statistik table { width: 100%; border-collapse: collapse; }
                .statistik td { border: 1px solid #333; padding: 8px; text-align: center; font-weight: bold; }
                .bg-hadir { background-color: #28a745; color: white; }
                .bg-izin { background-color: #17a2b8; color: white; }
                .bg-sakit { background-color: #ffc107; color: black; }
                .bg-alpa { background-color: #dc3545; color: white; }
                table.detail { width: 100%; border-collapse: collapse; margin-top: 15px; }
                table.detail th, table.detail td { border: 1px solid #333; padding: 6px; }
                table.detail th { background-color: #f0f0f0; font-weight: bold; }
                .footer { margin-top: 30px; font-size: 9px; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SMPTK GALANG KASIH UBUNG</h2>
                <p>Jl. Raya Ubung No. 123, Denpasar, Bali</p>
                <h3 style="margin: 15px 0 5px 0;">LAPORAN PRESENSI SISWA</h3>
            </div>
            
            <div class="info">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td width="150"><strong>Kelas</strong></td>
                        <td>: '.$kelas.'</td>
                    </tr>
                    <tr>
                        <td><strong>Periode</strong></td>
                        <td>: '.$start_indo.' s/d '.$end_indo.'</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Cetak</strong></td>
                        <td>: '.tanggal_indo(date('Y-m-d')).'</td>
                    </tr>
                </table>
            </div>
            
            <div class="statistik">
                <h4 style="margin-bottom: 10px;">Statistik Kehadiran</h4>
                <table>
                    <tr>
                        <td class="bg-hadir">Hadir<br>'.$statistik->hadir.'</td>
                        <td class="bg-izin">Izin<br>'.$statistik->izin.'</td>
                        <td class="bg-sakit">Sakit<br>'.$statistik->sakit.'</td>
                        <td class="bg-alpa">Alpa<br>'.$statistik->alpa.'</td>
                        <td>Total<br>'.($statistik->hadir + $statistik->izin + $statistik->sakit + $statistik->alpa).'</td>
                    </tr>
                </table>
            </div>
            
            <h4 style="margin-bottom: 10px;">Detail Presensi per Siswa</h4>
            <table class="detail">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Nama Siswa</th>
                        <th width="10%">Hadir</th>
                        <th width="10%">Izin</th>
                        <th width="10%">Sakit</th>
                        <th width="10%">Alpa</th>
                        <th width="10%">Total</th>
                        <th width="15%">Persentase</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($laporan as $l) {
            $total = $l->hadir + $l->izin + $l->sakit + $l->alpa;
            $persen = $total > 0 ? round(($l->hadir / $total) * 100, 1) : 0;
            
            $html .= '
            <tr>
                <td>'.$no++.'</td>
                <td>'.html_escape($l->nama_siswa).'</td>
                <td align="center">'.$l->hadir.'</td>
                <td align="center">'.$l->izin.'</td>
                <td align="center">'.$l->sakit.'</td>
                <td align="center">'.$l->alpa.'</td>
                <td align="center">'.$total.'</td>
                <td align="center">'.$persen.'%</td>
            </tr>';
        }
        
        if (empty($laporan)) {
            $html .= '<tr><td colspan="8" align="center">Tidak ada data</td></tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Sistem Informasi Presensi Siswa - SMPTK Galang Kasih Ubung</p>
                <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Halaman 1</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
