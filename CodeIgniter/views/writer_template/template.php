<?php
	error_reporting(0);
    $this->load->view('writer_template/header');
	$this->load->view('writer_template/left_sidebar');
	$this->load->view($main_content);
    $this->load->view('writer_template/footer');
?>