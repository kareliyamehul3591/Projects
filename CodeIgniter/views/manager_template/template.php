<?php
	error_reporting(0);
    $this->load->view('manager_template/header');
	$this->load->view('manager_template/left_sidebar');
	$this->load->view($main_content);
    $this->load->view('manager_template/footer');
?>