<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Import Library
 * Wrapper untuk membaca file Excel/CSV tanpa依赖 PhpSpreadsheet
 * Menggunakan SimpleXML untuk XLSX dan fungsi bawaan PHP untuk CSV
 */

class Excel_import {
    
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Extract file ZIP (untuk XLSX)
     * @param string $file_path
     * @param string $destination
     * @return bool
     */
    private function extract_zip($file_path, $destination) {
        if (!class_exists('ZipArchive')) {
            log_message('error', 'ZipArchive extension tidak tersedia');
            return false;
        }
        
        $zip = new ZipArchive();
        if ($zip->open($file_path) !== TRUE) {
            return false;
        }
        
        $zip->extractTo($destination);
        $zip->close();
        return true;
    }
    
    /**
     * Baca file XLSX (Office Open XML)
     * @param string $file_path
     * @param int|null $limit
     * @return array|false
     */
    private function read_xlsx($file_path, $limit = null) {
        $temp_dir = sys_get_temp_dir() . '/excel_import_' . uniqid();
        
        if (!mkdir($temp_dir, 0777, true)) {
            log_message('error', 'Gagal membuat direktori temp');
            return false;
        }
        
        try {
            if (!$this->extract_zip($file_path, $temp_dir)) {
                log_message('error', 'Gagal extract file XLSX');
                return false;
            }
            
            $worksheet_file = $temp_dir . '/xl/worksheets/sheet1.xml';
            if (!file_exists($worksheet_file)) {
                // Coba sheet lainnya
                for ($i = 1; $i <= 10; $i++) {
                    $alt_file = $temp_dir . '/xl/worksheets/sheet' . $i . '.xml';
                    if (file_exists($alt_file)) {
                        $worksheet_file = $alt_file;
                        break;
                    }
                }
            }
            
            if (!file_exists($worksheet_file)) {
                log_message('error', 'File worksheet tidak ditemukan');
                return false;
            }
            
            // Baca shared strings
            $shared_strings = array();
            $shared_strings_file = $temp_dir . '/xl/sharedStrings.xml';
            if (file_exists($shared_strings_file)) {
                $xml = simplexml_load_file($shared_strings_file);
                if ($xml) {
                    foreach ($xml->si as $si) {
                        $shared_strings[] = (string)$si->t;
                    }
                }
            }
            
            // Parse worksheet XML
            $xml = simplexml_load_file($worksheet_file);
            if (!$xml) {
                log_message('error', 'Gagal parse XML worksheet');
                return false;
            }
            
            $data = array();
            $row_count = 0;
            
            foreach ($xml->sheetData->row as $row) {
                if ($limit !== null && $row_count >= $limit) {
                    break;
                }
                
                $row_data = array();
                $max_col = 0;
                
                foreach ($row->c as $cell) {
                    // Dapatkan index kolom dari atribut r (contoh: A1, B1, C1)
                    $cell_ref = (string)$cell['r'];
                    $col_index = $this->column_letter_to_index(preg_replace('/[0-9]+/', '', $cell_ref));
                    
                    $value = '';
                    
                    // Cek tipe cell
                    $cell_type = isset($cell['t']) ? (string)$cell['t'] : 'n';
                    
                    if ($cell_type == 's') {
                        // Shared string
                        $index = (int)$cell->v;
                        $value = isset($shared_strings[$index]) ? $shared_strings[$index] : '';
                    } elseif ($cell_type == 'b') {
                        // Boolean
                        $value = (int)$cell->v ? 'TRUE' : 'FALSE';
                    } elseif ($cell_type == 'e') {
                        // Error
                        $value = (string)$cell->v;
                    } else {
                        // Number atau inline string
                        if (isset($cell->v)) {
                            $value = (string)$cell->v;
                        } elseif (isset($cell->is->t)) {
                            $value = (string)$cell->is->t;
                        }
                    }
                    
                    $row_data[$col_index] = $value;
                    if ($col_index > $max_col) {
                        $max_col = $col_index;
                    }
                }
                
                // Fill empty cells
                $complete_row = array();
                for ($i = 0; $i <= $max_col; $i++) {
                    $complete_row[] = isset($row_data[$i]) ? trim($row_data[$i]) : '';
                }
                
                $data[] = $complete_row;
                $row_count++;
            }
            
            return $data;
            
        } catch (Exception $e) {
            log_message('error', 'XLSX Read Error: ' . $e->getMessage());
            return false;
        } finally {
            // Cleanup temp directory
            $this->delete_directory($temp_dir);
        }
    }
    
    /**
     * Convert column letter to index (A=0, B=1, C=2, ...)
     * @param string $letter
     * @return int
     */
    private function column_letter_to_index($letter) {
        $letter = strtoupper($letter);
        $length = strlen($letter);
        $index = 0;
        
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($letter[$i]) - ord('A') + 1);
        }
        
        return $index - 1;
    }
    
    /**
     * Baca file CSV
     * @param string $file_path
     * @param int|null $limit
     * @return array|false
     */
    private function read_csv($file_path, $limit = null) {
        $data = array();
        $row_count = 0;
        
        try {
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    if ($limit !== null && $row_count >= $limit) {
                        break;
                    }
                    $data[] = $row;
                    $row_count++;
                }
                fclose($handle);
            }
            
            return $data;
        } catch (Exception $e) {
            log_message('error', 'CSV Read Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete directory recursively
     * @param string $dir
     */
    private function delete_directory($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->delete_directory($path) : unlink($path);
        }
        rmdir($dir);
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
        $ext = strtolower($ext);
        
        if ($ext === 'csv') {
            return $this->read_csv($file_path, $limit);
        } elseif ($ext === 'xlsx') {
            return $this->read_xlsx($file_path, $limit);
        } elseif ($ext === 'xls') {
            log_message('error', 'Format XLS (biner) tidak didukung. Gunakan format XLSX atau CSV.');
            return false;
        } else {
            log_message('error', 'Ekstensi file tidak dikenali: ' . $ext);
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
