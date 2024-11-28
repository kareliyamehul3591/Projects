<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Writer_notifications extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_writer_notifications');
	}
	public function index()
	{
		if($this->session->Admindetail['writer'] == 1)
		{
			//$this->Mdl_writer_notifications->notifications_online();
			$writer_notifications = $this->Mdl_writer_notifications->writer_notifications();
			$data=array(
				'writer_notifications'=>$writer_notifications,
				'main_content'=>'writer_notifications',
				'left_sidebar'=>'Writer Notifications',
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>