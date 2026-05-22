<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Template Generator Library
 * Generate file Excel (.xlsx) yang kompatibel dengan MS Office 2010+
 * Menggunakan PHPSpreadsheet
 */

// Cek apakah PhpSpreadsheet tersedia
$composerAutoload = APPPATH . '../vendor/autoload.php';
$localAutoload = APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';

if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
    define('HAS_PHPSPREADSHEET_TEMPLATE', true);
} elseif (file_exists($localAutoload)) {
    require_once $localAutoload;
    define('HAS_PHPSPREADSHEET_TEMPLATE', true);
} else {
    define('HAS_PHPSPREADSHEET_TEMPLATE', false);
}

class Excel_template {
    
    protected $CI;
    protected $spreadsheet;
    protected $worksheet;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        if (!defined('HAS_PHPSPREADSHEET_TEMPLATE') || !HAS_PHPSPREADSHEET_TEMPLATE) {
            log_message('error', 'PhpSpreadsheet library tidak ditemukan. Pastikan sudah menjalankan composer install.');
        }
    }
    
    /**
     * Generate template Excel untuk import siswa
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function generate_siswa_template() {
        if (!defined('HAS_PHPSPREADSHEET_TEMPLATE') || !HAS_PHPSPREADSHEET_TEMPLATE) {
            return false;
        }
        
        $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->worksheet = $this->spreadsheet->getActiveSheet();
        
        // Set judul sheet
        $this->worksheet->setTitle('Template Siswa');
        
        // Header
        $headers = [
            'NIS',
            'Nama Lengkap',
            'JK',
            'Tempat, Tanggal Lahir',
            'Alamat',
            'Nama Orang Tua',
            'No HP Orang Tua'
        ];
        
        // Set header row
        $this->worksheet->fromArray([$headers], NULL, 'A1');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $this->worksheet->getStyle('A1:G1')->applyFromArray($headerStyle);
        
        // Contoh data
        $exampleData = [
            ['12345', 'Ahmad Santoso', 'L', 'Ubung, 15 Januari 2013', 'Jl. Raya Ubung No. 123', 'Budi Santoso', '081234567890'],
            ['12346', 'Siti Aminah', 'P', 'Denpasar, 20 Maret 2013', 'Jl. Merdeka No. 45', 'Hasan Abdullah', '081234567891']
        ];
        
        $this->worksheet->fromArray($exampleData, NULL, 'A2');
        
        // Style data cells
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $this->worksheet->getStyle('A2:G3')->applyFromArray($dataStyle);
        
        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $this->worksheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set row height
        $this->worksheet->getRowDimension(1)->setRowHeight(20);
        $this->worksheet->getRowDimension(2)->setRowHeight(18);
        $this->worksheet->getRowDimension(3)->setRowHeight(18);
        
        // Add notes/cell comments
        $noteCell = $this->worksheet->getCell('A4');
        $noteCell->setValue('CATATAN:');
        $noteCell->getStyle()->getFont()->setBold(true);
        
        $notes = [
            'A5' => '• NIS harus unik (maksimal 10 digit angka)',
            'A6' => '• JK diisi L untuk Laki-laki atau P untuk Perempuan',
            'A7' => '• Format TTL: Tempat, DD Bulan YYYY (contoh: Denpasar, 15 Januari 2013)',
            'A8' => '• No HP minimal 10 digit angka (contoh: 081234567890)',
            'A9' => '• Jangan menghapus atau mengubah baris header (baris 1)'
        ];
        
        foreach ($notes as $cell => $note) {
            $this->worksheet->getCell($cell)->setValue($note);
            $this->worksheet->getStyle($cell)->getFont()->setSize(9);
            $this->worksheet->getStyle($cell)->getFont()->setColor(
                new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED)
            );
        }
        
        // Merge cells for note section
        $this->worksheet->mergeCells('A5:G5');
        $this->worksheet->mergeCells('A6:G6');
        $this->worksheet->mergeCells('A7:G7');
        $this->worksheet->mergeCells('A8:G8');
        $this->worksheet->mergeCells('A9:G9');
        
        // Freeze header row
        $this->worksheet->freezePane('A2');
        
        return $this->spreadsheet;
    }
    
    /**
     * Generate template Excel untuk import guru
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function generate_guru_template() {
        if (!defined('HAS_PHPSPREADSHEET_TEMPLATE') || !HAS_PHPSPREADSHEET_TEMPLATE) {
            return false;
        }
        
        $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->worksheet = $this->spreadsheet->getActiveSheet();
        
        // Set judul sheet
        $this->worksheet->setTitle('Template Guru');
        
        // Header
        $headers = [
            'NIP',
            'Nama Lengkap',
            'JK',
            'No HP',
            'Alamat'
        ];
        
        // Set header row
        $this->worksheet->fromArray([$headers], NULL, 'A1');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $this->worksheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        // Contoh data
        $exampleData = [
            ['198501012010011001', 'Drs. John Doe, M.Pd', 'L', '081234567890', 'Jl. Pendidikan No. 45'],
            ['198602022011012002', 'Dra. Jane Smith, M.Pd', 'P', '081234567891', 'Jl. Guru No. 12']
        ];
        
        $this->worksheet->fromArray($exampleData, NULL, 'A2');
        
        // Style data cells
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $this->worksheet->getStyle('A2:E3')->applyFromArray($dataStyle);
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $this->worksheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set row height
        $this->worksheet->getRowDimension(1)->setRowHeight(20);
        $this->worksheet->getRowDimension(2)->setRowHeight(18);
        $this->worksheet->getRowDimension(3)->setRowHeight(18);
        
        // Add notes/cell comments
        $noteCell = $this->worksheet->getCell('A4');
        $noteCell->setValue('CATATAN:');
        $noteCell->getStyle()->getFont()->setBold(true);
        
        $notes = [
            'A5' => '• NIP harus unik (18 digit angka sesuai standar PNS)',
            'A6' => '• JK diisi L untuk Laki-laki atau P untuk Perempuan',
            'A7' => '• No HP minimal 10 digit angka (contoh: 081234567890)',
            'A8' => '• Jangan menghapus atau mengubah baris header (baris 1)'
        ];
        
        foreach ($notes as $cell => $note) {
            $this->worksheet->getCell($cell)->setValue($note);
            $this->worksheet->getStyle($cell)->getFont()->setSize(9);
            $this->worksheet->getStyle($cell)->getFont()->setColor(
                new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED)
            );
        }
        
        // Merge cells for note section
        $this->worksheet->mergeCells('A5:E5');
        $this->worksheet->mergeCells('A6:E6');
        $this->worksheet->mergeCells('A7:E7');
        $this->worksheet->mergeCells('A8:E8');
        
        // Freeze header row
        $this->worksheet->freezePane('A2');
        
        return $this->spreadsheet;
    }
    
    /**
     * Download/Output file Excel ke browser
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet
     * @param string $filename Nama file output
     * @return void
     */
    public function download($spreadsheet, $filename) {
        if (!defined('HAS_PHPSPREADSHEET_TEMPLATE') || !HAS_PHPSPREADSHEET_TEMPLATE) {
            show_error('PhpSpreadsheet library tidak ditemukan');
            return;
        }
        
        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        // Simpan ke output
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
