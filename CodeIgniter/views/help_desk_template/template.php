<?php
	error_reporting(0);
    $this->load->view('help_desk_template/header');
	$this->load->view('help_desk_template/left_sidebar');
	$this->load->view($main_content);
    $this->load->view('help_desk_template/footer');
?>