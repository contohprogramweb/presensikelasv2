<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Guru - Rekap Presensi & Export PDF
 */
class Rekap extends MY_Controller {
    
    protected $role_required = ['guru'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('guru/M_rekap');
        $this->load->model('admin/M_kelas');
    }
    
    /**
     * Halaman rekap presensi
     */
    public function index() {
        $this->data['page_title'] = 'Rekap Presensi';
        
        // Get kelas yang diampu guru
        $id_user = $this->session->userdata('id');
        $this->db->select('DISTINCT j.id_kelas, k.nama_kelas');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_guru g', 'g.id = j.id_guru');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas');
        $this->db->where('g.id_user', $id_user);
        $this->db->where('j.id_tahun_ajaran', $this->tahun_ajaran_aktif->id ?? null);
        $kelas_list = $this->db->get()->result();
        
        $this->data['kelas_list'] = $kelas_list;
        $this->data['rekap'] = [];
        
        if ($this->input->get('kelas') && $this->input->get('start_date') && $this->input->get('end_date')) {
            $id_kelas = $this->input->get('kelas');
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            
            $this->data['filter_kelas'] = $id_kelas;
            $this->data['filter_start'] = $start_date;
            $this->data['filter_end'] = $end_date;
            
            $this->data['rekap'] = $this->M_rekap->get_rekap_per_siswa($id_kelas, $start_date, $end_date);
        }
        
        $this->load->view('templates/template', ['content' => 'guru/rekap'] + $this->data);
    }
    
    /**
     * Export PDF rekap presensi
     */
    public function export_pdf() {
        $id_kelas = $this->input->get('kelas');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        if (!$id_kelas || !$start_date || !$end_date) {
            $this->session->set_flashdata('error', 'Filter tidak lengkap');
            redirect('guru/rekap');
        }
        
        // Get data rekap
        $rekap = $this->M_rekap->get_rekap_per_siswa($id_kelas, $start_date, $end_date);
        
        // Get info kelas
        $this->db->select('nama_kelas');
        $kelas = $this->db->get_where('tb_kelas', ['id' => $id_kelas])->row();
        
        // Generate HTML untuk PDF
        $html = $this->_generate_html_rekap($kelas->nama_kelas, $start_date, $end_date, $rekap);
        
        // Load library dompdf
        $this->load->library('dompdf_generator');
        
        $filename = 'Rekap_Presensi_'.$kelas->nama.'_'.date('YmdHis').'.pdf';
        $this->dompdf_generator->generate($html, $filename, 'A4', 'portrait', true);
    }
    
    /**
     * Generate HTML untuk PDF
     */
    private function _generate_html_rekap($kelas, $start_date, $end_date, $rekap) {
        $start_indo = tanggal_indo($start_date);
        $end_indo = tanggal_indo($end_date);
        
        $html = '
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page { margin: 20px; }
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .header h2 { margin: 5px 0; font-size: 16px; }
                .header p { margin: 3px 0; font-size: 11px; }
                .info { margin-bottom: 15px; }
                .info table { width: 100%; border-collapse: collapse; }
                .info td { padding: 5px; }
                table.rekap { width: 100%; border-collapse: collapse; margin-top: 10px; }
                table.rekap th, table.rekap td { border: 1px solid #333; padding: 6px; text-align: center; }
                table.rekap th { background-color: #f0f0f0; font-weight: bold; }
                table.rekap td.text-left { text-align: left; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; }
                .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; color: white; font-size: 10px; }
                .bg-hadir { background-color: #28a745; }
                .bg-izin { background-color: #17a2b8; }
                .bg-sakit { background-color: #ffc107; color: #000; }
                .bg-alpa { background-color: #dc3545; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SMPTK GALANG KASIH UBUNG</h2>
                <p>Jl. Raya Ubung No. 123, Denpasar, Bali</p>
                <h3 style="margin: 15px 0 5px 0;">REKAP PRESENSI SISWA</h3>
            </div>
            
            <div class="info">
                <table>
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
            
            <table class="rekap">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Siswa</th>
                        <th width="10%">Hadir</th>
                        <th width="10%">Izin</th>
                        <th width="10%">Sakit</th>
                        <th width="10%">Alpa</th>
                        <th width="10%">Total</th>
                        <th width="20%">Persentase</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($rekap as $r) {
            $total = $r->hadir + $r->izin + $r->sakit + $r->alpa;
            $persen = $total > 0 ? round(($r->hadir / $total) * 100, 1) : 0;
            
            $html .= '
            <tr>
                <td>'.$no++.'</td>
                <td class="text-left">'.html_escape($r->nama_siswa).'</td>
                <td><span class="badge bg-hadir">'.$r->hadir.'</span></td>
                <td><span class="badge bg-izin">'.$r->izin.'</span></td>
                <td><span class="badge bg-sakit">'.$r->sakit.'</span></td>
                <td><span class="badge bg-alpa">'.$r->alpa.'</span></td>
                <td>'.$total.'</td>
                <td>'.$persen.'%</td>
            </tr>';
        }
        
        if (empty($rekap)) {
            $html .= '<tr><td colspan="8">Tidak ada data presensi</td></tr>';
        }
        
        // Statistik summary
        $total_hadir = array_sum(array_column((array)$rekap, 'hadir'));
        $total_izin = array_sum(array_column((array)$rekap, 'izin'));
        $total_sakit = array_sum(array_column((array)$rekap, 'sakit'));
        $total_alpa = array_sum(array_column((array)$rekap, 'alpa'));
        
        $html .= '
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2">Total</td>
                        <td><span class="badge bg-hadir">'.$total_hadir.'</span></td>
                        <td><span class="badge bg-izin">'.$total_izin.'</span></td>
                        <td><span class="badge bg-sakit">'.$total_sakit.'</span></td>
                        <td><span class="badge bg-alpa">'.$total_alpa.'</span></td>
                        <td>'.($total_hadir + $total_izin + $total_sakit + $total_alpa).'</td>
                        <td>-</td>
                    </tr>
                </tfoot>
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
