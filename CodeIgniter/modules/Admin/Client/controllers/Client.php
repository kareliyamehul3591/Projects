<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->library('session');
		$this->load->model('Mdl_client');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$client_data = $this->Mdl_client->client();
			$data=array(
				'datas'=>$client_data,
				'main_content'=>'client',
				'left_sidebar'=>'Client',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function view_client($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$user_data = $this->Mdl_client->client_detail($id);
			$data=array(
				'datas'=>$user_data,
				'main_content'=>'view_client',
				'left_sidebar'=>'Client',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function edit_client($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$user_data = $this->Mdl_client->client_detail($id);
			$data=array(
				'datas'=>$user_data,
				'main_content'=>'edit_client',
				'left_sidebar'=>'Client',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function client_edit()
	{
		if($_POST['ids'] == null)
		{
			$id = $this->Mdl_client->client_add();
			$this->Mdl_client->payment_details_insert($id);
			$_POST['ids'] = $id;
		}else{
			$this->Mdl_client->client_edit();
		}
		
		if($_POST['payment_method'] == "Freelancer")
		{
			if($_POST['profile_url'] != null)
			{
				$data=array(
					'payment_type'=>$_POST['payment_type'],
					'payment_method'=>$_POST['payment_method'],
					'profile_url'=>$_POST['profile_url'],
					'paypal_user_id'=>'',
					'contact_no'=>'',
					'bank_name'=>'',
					'account_no'=>'',
					'ifsc_code'=>'',
				);					
				$update = $this->Mdl_client->payment_details_update($data);	
			}
		}else if($_POST['payment_method'] == "Paypal"){
			if($_POST['paypal_user_id'] != null)
			{
				$data=array(
					'payment_type'=>$_POST['payment_type'],
					'payment_method'=>$_POST['payment_method'],
					'profile_url'=>'',
					'paypal_user_id'=>$_POST['paypal_user_id'],
					'contact_no'=>'',
					'bank_name'=>'',
					'account_no'=>'',
					'ifsc_code'=>'',
				);					
				$update = $this->Mdl_client->payment_details_update($data);
			}
		}else if($_POST['payment_method'] == "Paytm"){
			if($_POST['contact_no'] != null)
			{
				$data=array(
					'payment_type'=>$_POST['payment_type'],
					'payment_method'=>$_POST['payment_method'],
					'profile_url'=>'',
					'paypal_user_id'=>'',
					'contact_no'=>$_POST['contact_no'],
					'bank_name'=>'',
					'account_no'=>'',
					'ifsc_code'=>'',
				);					
				$update = $this->Mdl_client->payment_details_update($data);
			}			
		}else if($_POST['payment_method'] == "Bank Transfer"){
			if($_POST['bank_name'] != null && $_POST['account_no'] != null && $_POST['ifsc_code'] != null)
			{
				$data=array(
					'payment_type'=>$_POST['payment_type'],
					'payment_method'=>$_POST['payment_method'],
					'profile_url'=>'',
					'paypal_user_id'=>'',
					'contact_no'=>'',
					'bank_name'=>$_POST['bank_name'],
					'account_no'=>$_POST['account_no'],
					'ifsc_code'=>$_POST['ifsc_code'],
				);					
				$update = $this->Mdl_client->payment_details_update($data);
			}				
		}
		$success_msg='Success! client '.$_POST['ids'].' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		redirect('Client');
	}
	public function active_client_list()
	{
		$k = 0;
		foreach(explode(",",$_POST['id']) as $id)
		{
			$i = 0;
			$assignment = $this->Mdl_client->assignment('C'.$id);
			foreach($assignment as $assignmen)
			{
				if($assignmen['status'] != 5 && $assignmen['status'] != 4 && $assignmen['status'] != 6)
				{
					$i++;
				}
			}
			if($_POST['status'] == 1)
			{
				$dat = $this->Mdl_client->active_client($id,$_POST['status']);
				$k++;
			}else if($_POST['status'] == 0 || $_POST['status'] == 2)
			{
				if($i == 0){
					$dat = $this->Mdl_client->active_client($id,$_POST['status']);
					$k++;
				}
			}
		}
		if($_POST['status'] == 0)
		{
			$status = 'Deactivated';
			$success_msg = 'Success! '.$k.' Client are deactivated.';
		}else if($_POST['status'] == 1)
		{
			$status = 'Activated';
			$success_msg = 'Success! '.$k.' Client are activate.';
		}else if($_POST['status'] == 2)
		{
			$status = 'Deleted';
			$success_msg = 'Success! '.$k.' Client are delete.';
		}
		if($k == 0){
			$error_msg = 'This is an invalid action!'; 
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$this->session->set_userdata('success_msg',$success_msg);
		}
		
	}
	public function active_client()
	{
		$dat = $this->Mdl_client->active_client($_POST['id'],$_POST['status']);
		if($_POST['status'] == 0)
		{ 
			$status = 'Deactivated';
			$success_msg = 'Success! User '.$_POST['id'].' is deactivated.';
		}else if($_POST['status'] == 1)
		{
			$status = 'Activated';
			$success_msg = 'Success! User '.$_POST['id'].' is activated.';
		}else if($_POST['status'] == 2)
		{
			$status = 'Deleted';
			$success_msg = 'Success! User '.$_POST['id'].' is deleted.';
		}
		
		$this->session->set_userdata('success_msg',$success_msg);
	}
	
	public function email_id_check($id)
	{
		$email_id = $this->Mdl_user->email_id_check($id,$_POST['email']);
		if($email_id != null)
		{
			echo json_encode(FALSE);
		}
		else
		{
			echo json_encode(TRUE);
		}
		
	}
}
?>