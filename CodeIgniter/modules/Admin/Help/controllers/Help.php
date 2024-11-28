<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_help');
	}
	public function index()
	{
		$data=array(
			'main_content'=>'help',
			'left_sidebar'=>'Help',
		);
		if($_GET['role'] == 'helpdesk')
		{
			$this->load->view('help_desk_template/template',$data);
		}else if($_GET['role'] == 'admin')
		{
			$this->load->view('admin_template/template',$data);
		}else if($_GET['role'] == 'manager')
		{
			$this->load->view('manager_template/template',$data);
		}else if($_GET['role'] == 'writer')
		{
			$this->load->view('writer_template/template',$data);
		}else if($_GET['role'] == 'proofreader')
		{
			$this->load->view('proof_reader_template/template',$data);
		}else if($_GET['role'] == 'login')
		{
			$this->load->view('helps');
		}
	}
	public function contact()
	{
		$data=array(
			'main_content'=>'contact',
			'left_sidebar'=>'Contact',
		);
		if($_GET['role'] == 'helpdesk')
		{
			$this->load->view('help_desk_template/template',$data);
		}else if($_GET['role'] == 'admin')
		{
			$this->load->view('admin_template/template',$data);
		}else if($_GET['role'] == 'manager')
		{
			$this->load->view('manager_template/template',$data);
		}else if($_GET['role'] == 'writer')
		{
			$this->load->view('writer_template/template',$data);
		}else if($_GET['role'] == 'proofreader')
		{
			$this->load->view('proof_reader_template/template',$data);
		}
	}
	public function email()
	{
		$to = 'writersteam2018@gmail.com';
		$subject = "Contact";
		$txt = "
		<html>
		<head>
		<title>Contact</title>
		</head>
		<body>
		<p>Contact</p>
			<table>
				<tr>
					<th>Name :</th>
					<th>".$_POST['name']."</th>
				</tr>
				<tr>
					<th>Email :</th>
					<th>".$_POST['email']."</th>
				</tr>
				<tr>
					<th>Contact :</th>
					<th>".$_POST['contact']."</th>
				</tr>
				<tr>
					<th>Message :</th>
					<th>".$_POST['message']."</th>
				</tr>
			</table>
		</body>
		</html>
		";

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		mail($to,$subject,$txt,$headers);
		
		$success_msg='Your Message has been sent.';
		$this->session->set_userdata('success_msg',$success_msg);
	} 
}
?>