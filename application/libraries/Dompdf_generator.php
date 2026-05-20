<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dompdf Generator Library
 * Wrapper untuk Dompdf 2.x
 */
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

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
                $this->dompdf->stream($filename, array('Attachment' => true));
            } else {
                echo $this->dompdf->output();
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
}
