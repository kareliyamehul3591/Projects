<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_notifications extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_manager_notifications');
	}
	public function index()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$this->Mdl_manager_notifications->notifications_online();
			$manager_notifications = $this->Mdl_manager_notifications->manager_notifications();
			$data=array(
				'manager_notifications'=>$manager_notifications,
				'main_content'=>'manager_notifications',
				'left_sidebar'=>'Manager Notifications',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>