<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Writer_welcome extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}
	public function index()
	{
		if($this->session->Admindetail['role'] == 'Writer')
		{
			$data=array(
				'main_content'=>'writer_welcome',
				'left_sidebar'=>'Writer Welcome',
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>