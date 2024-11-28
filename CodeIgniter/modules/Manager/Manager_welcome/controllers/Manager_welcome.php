<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_welcome extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}
	public function index()
	{
		if($this->session->Admindetail['role'] == 'Manager')
		{
			$data=array(
				'main_content'=>'manager_welcome',
				'left_sidebar'=>'Manager Welcome',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>