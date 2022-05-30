<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_notifications');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			//$this->Mdl_notifications->notifications_online();
			$notifications = $this->Mdl_notifications->notifications();
			$data=array(
				'notifications'=>$notifications,
				'main_content'=>'notifications',
				'left_sidebar'=>'Notifications',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>