<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Import Library
 * Wrapper untuk membaca file Excel/CSV
 */

// Cek apakah PhpSpreadsheet tersedia
$composerAutoload = APPPATH . '../vendor/autoload.php';
$localAutoload = APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';

if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
    define('HAS_PHPSPREADSHEET', true);
} elseif (file_exists($localAutoload)) {
    require_once $localAutoload;
    define('HAS_PHPSPREADSHEET', true);
} else {
    define('HAS_PHPSPREADSHEET', false);
}

if (HAS_PHPSPREADSHEET) {
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
}

class Excel_import {
    
    protected $CI;
    protected $spreadsheet;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        // Cek apakah PhpSpreadsheet tersedia
        if (!defined('HAS_PHPSPREADSHEET') || !HAS_PHPSPREADSHEET) {
            log_message('error', 'PhpSpreadsheet library tidak ditemukan. Pastikan sudah menjalankan composer install.');
        }
    }
    
    /**
     * Upload dan baca file Excel
     * @param string $file_path Path file uploaded
     * @return array Data dalam format array 2D
     */
    public function read_excel($file_path) {
        if (!defined('HAS_PHPSPREADSHEET') || !HAS_PHPSPREADSHEET) {
            log_message('error', 'PhpSpreadsheet library tidak ditemukan');
            return false;
        }
        
        try {
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
            }
            
            return $data;
        } catch (Exception $e) {
            log_message('error', 'Excel Read Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Preview beberapa baris pertama
     * @param string $file_path
     * @param int $limit Jumlah baris preview
     * @return array
     */
    public function preview($file_path, $limit = 5) {
        $data = $this->read_excel($file_path);
        if ($data === false) {
            return false;
        }
        
        return array_slice($data, 0, $limit);
    }
    
    /**
     * Baca file Excel/CSV dengan deteksi otomatis
     * @param string $file_path Path file
     * @param string $ext Ekstensi file (xlsx, xls, csv)
     * @param int $limit Limit baris (opsional, untuk preview)
     * @return array|false
     */
    public function read_file($file_path, $ext = 'xlsx', $limit = null) {
        if (!defined('HAS_PHPSPREADSHEET') || !HAS_PHPSPREADSHEET) {
            log_message('error', 'PhpSpreadsheet library tidak ditemukan');
            return false;
        }
        
        try {
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $rowCount = 0;
            
            foreach ($worksheet->getRowIterator() as $row) {
                if ($limit !== null && $rowCount >= $limit) {
                    break;
                }
                
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
                $rowCount++;
            }
            
            return $data;
        } catch (Exception $e) {
            log_message('error', 'Excel Read Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validasi struktur file Excel
     * @param array $data
     * @param array $required_columns Kolom yang wajib ada (header)
     * @return bool
     */
    public function validate_structure($data, $required_columns = []) {
        if (empty($data)) {
            return false;
        }
        
        // Baris pertama sebagai header
        $headers = array_map('strtolower', array_map('trim', $data[0]));
        
        foreach ($required_columns as $col) {
            if (!in_array(strtolower(trim($col)), $headers)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Convert array data ke format associative berdasarkan header
     * @param array $data
     * @return array
     */
    public function to_associative($data) {
        if (empty($data) || count($data) < 2) {
            return [];
        }
        
        $headers = array_map('trim', $data[0]);
        $result = [];
        
        for ($i = 1; $i < count($data); $i++) {
            $row = [];
            for ($j = 0; $j < count($headers); $j++) {
                $key = strtolower(str_replace(' ', '_', trim($headers[$j])));
                $row[$key] = isset($data[$i][$j]) ? trim($data[$i][$j]) : null;
            }
            $result[] = $row;
        }
        
        return $result;
    }
}
