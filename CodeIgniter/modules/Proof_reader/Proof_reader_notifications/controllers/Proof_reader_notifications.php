<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Proof_reader_notifications extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_proof_reader_notifications');
	}
	public function index()
	{
		if($this->session->Admindetail['proof_reader'] == 1)
		{
			//$this->Mdl_proof_reader_notifications->notifications_online();
			$proof_reader_notifications = $this->Mdl_proof_reader_notifications->proof_reader_notifications();
			$data=array(
				'proof_reader_notifications'=>$proof_reader_notifications,
				'main_content'=>'proof_reader_notifications',
				'left_sidebar'=>'Proof Reader Notifications',
			);
			$this->load->view('proof_reader_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>