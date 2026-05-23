<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dompdf Generator Library
 * Wrapper untuk Dompdf 2.x
 */
require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_generator {
    
    protected $CI;
    protected $dompdf;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        
        $this->dompdf = new Dompdf($options);
    }
    
    /**
     * Generate PDF dari HTML string
     * @param string $html
     * @param string $filename
     * @param string $paper_size (A4, Letter, etc)
     * @param string $orientation (portrait, landscape)
     * @param bool $download (true = download, false = inline)
     */
    public function generate($html, $filename = 'document.pdf', $paper_size = 'A4', $orientation = 'portrait', $download = true) {
        try {
            $this->dompdf->setPaper($paper_size, $orientation);
            $this->dompdf->loadHtml($html);
            
            // Render PDF
            $this->dompdf->render();
            
            
			
			if ($download) {
                // Download langsung
                $this->dompdf->stream($filename, array('Attachment' => true));
            } else {
                // Preview di browser (inline)
                $this->dompdf->stream($filename, array('Attachment' => false));
            }
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            show_error('Gagal menghasilkan PDF: ' . $e->getMessage(), 500);
            return false;
        }
    }
    
    /**
     * Get dompdf instance untuk custom manipulation
     */
    public function get_dompdf() {
        return $this->dompdf;
    }
    
    /**
     * Preview PDF di browser (inline)
     * @param string $html
     * @param string $filename
     */
    public function preview($html, $filename = 'document.pdf') {
        try {
            $this->dompdf->setPaper('A4', 'portrait');
            $this->dompdf->loadHtml($html);
            
            // Render PDF
            $this->dompdf->render();
            
            // Preview di browser (inline) - tidak download otomatis
            $this->dompdf->stream($filename, array('Attachment' => false));
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'PDF Preview Error: ' . $e->getMessage());
            show_error('Gagal menampilkan preview PDF: ' . $e->getMessage(), 500);
            return false;
        }
    }
}
