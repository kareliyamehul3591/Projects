<?php
	error_reporting(0);
    $this->load->view('proof_reader_template/header');
	$this->load->view('proof_reader_template/left_sidebar');
	$this->load->view($main_content);
    $this->load->view('proof_reader_template/footer');
?>