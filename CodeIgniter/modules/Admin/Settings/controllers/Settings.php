<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_settings');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$data=array(
				'main_content'=>'settings',
				'left_sidebar'=>'Settings',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function add()
	{
		$this->Mdl_settings->add_table();
		redirect('Settings');
	}
	public function deletes()
	{
		$this->Mdl_settings->deletes();
		redirect('Settings');
	}
	public function edit()
	{
		$this->Mdl_settings->edit_table();
		redirect('Settings');
	}
	public function basic_guidelines()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$data=array(
				'main_content'=>'basic_guidelines',
				'left_sidebar'=>'Basic Guidelines',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function basic_guidelines_update()
	{
		
		$datas = array();
		$i = 0; 
		foreach($_POST as $key => $name)
		{
			$values = null;
			$values = explode("_",$key);
			//$this->Mdl_settings->help_add($values[0],$values[1],$values[2],$i);
			$this->Mdl_settings->help_update($values[0],$values[1],$values[2],$name);
			$i++;
		}
		redirect('Settings/basic_guidelines');
	}
}
?>