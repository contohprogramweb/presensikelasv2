<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('templates/header', $data ?? []);
$this->load->view('templates/sidebar', $data ?? []);
?>
<div class="content-wrapper">
<?php
// Support dua pola: $content (string nama view) atau $contents (HTML string)
if (!empty($content)) {
    $this->load->view($content, $data ?? []);
} elseif (!empty($contents)) {
    echo $contents;
} 
?>
</div>
<?php
$this->load->view('templates/footer', $data ?? []);