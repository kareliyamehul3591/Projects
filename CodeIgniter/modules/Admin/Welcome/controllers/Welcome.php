<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$data=array(
				'main_content'=>'welcome',
				'left_sidebar'=>'Welcome',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>