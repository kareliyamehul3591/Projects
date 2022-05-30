<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Proof_reader_welcome extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}
	public function index()
	{
		if($this->session->Admindetail['proof_reader'] == 1)
		{
			$data=array(
				'main_content'=>'proof_reader_welcome',
				'left_sidebar'=>'Proof Reader Welcome',
			);
			$this->load->view('proof_reader_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
}
?>