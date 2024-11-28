<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_desk_notifications extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_help_desk_notifications');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$notifications = $this->Mdl_help_desk_notifications->notifications();
			$data=array(
				'notifications'=>$notifications,
				'main_content'=>'help_desk_notifications',
				'left_sidebar'=>'Help Desk Notifications',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>