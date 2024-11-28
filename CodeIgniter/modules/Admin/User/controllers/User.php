<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->library('session');
		$this->load->model('Mdl_user');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$user_data = $this->Mdl_user->user();
			$data=array(
				'datas'=>$user_data,
				'main_content'=>'user',
				'left_sidebar'=>'User',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
	public function view_user($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$this->Mdl_user->assignment_notifications($id);
			$user_data = $this->Mdl_user->user_detail($id);
			$data=array(
				'datas'=>$user_data,
				'main_content'=>'view_user',
				'left_sidebar'=>'User',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function edit_user($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$user_data = $this->Mdl_user->user_detail($id);
			$data=array(
				'datas'=>$user_data,
				'main_content'=>'edit_user',
				'left_sidebar'=>'User',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function user_edit()
	{
		$count = count($_FILES['file']['name']);
		$count--;
		$user_data = $this->Mdl_user->user_detail($_POST['ids']);
		$filess = explode(",",$user_data[0]['file']);
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext = $this->Mdl_user->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/User/';
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image')) 
					{
					  $config['image_library'] = 'gd2';
					  $config['source_image']  = './uploads/User/'.$filename[$i];
					  $this->load->library('image_lib', $config); 
					  $this->image_lib->resize(); 
					  $this->image_lib->clear();
					}
				}
			}
			foreach($filess as $key => $files)
			{
				if($files == $_FILES['image']['name'])
				{
					unset($filess[$key]);
				}
			}
		}
		$filess = array_filter($filess);
		$filename = array_filter($filename);
		if($filename == null)
		{
			$filess = array_splice($filess,0,5,true);
			$file = implode(",",$filess);
		}else{
			$result = array_filter(array_unique(array_merge($filename, $filess)));
			$result = array_splice($result,0,5,true);
			$file = implode(",",$result);
		}
		$this->Mdl_user->user_edit($file);
		
		$timings = $this->Mdl_user->available_timings($_POST['ids']);
		if($timings == null)
		{
			$this->Mdl_user->timings_add();
		}else{
			$this->Mdl_user->timings_edit(); 
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
				$update = $this->Mdl_user->payment_details_update($data);	
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
				$update = $this->Mdl_user->payment_details_update($data);
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
				$update = $this->Mdl_user->payment_details_update($data);
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
				$update = $this->Mdl_user->payment_details_update($data);
			}				
		}
		
		
		
		
		$success_msg='Success! User '.$_POST['ids'].' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		redirect('User');
	}
	public function user_remove_file($id)
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_user->user_detail($id);
		$getemage = explode(',',$getdatas[0]['file']);
		unset($getemage[$ids]);
		$images = $this->Mdl_user->user_remove_file($id,implode(",", $getemage));
		
		$success_msg='Selacted User File Remove.';
		$this->session->set_userdata('success_msg',$success_msg);
	}
	public function user_delete($id)
	{
		$user_data = $this->Mdl_user->user_delete($id);
		redirect('User');
	}
	public function active_role()
	{
		if($_POST['role'] == 1)
		{
			$status = 'admin';
		}else if($_POST['role'] == 2)
		{
			$status = 'manager';
		}else if($_POST['role'] == 3)
		{
			$status = 'writer';
		}else if($_POST['role'] == 4)
		{
			$status = 'proof_reader';
		}else if($_POST['role'] == 5)
		{
			$status = 'help_desk';
		}
		$user_data = $this->Mdl_user->active_role($status);
	}
	public function active_user_list()
	{
		if($_POST['status'] == 0)
		{
			$status = 'Deactivated';
			$success_msg = 'Success! '.count(explode(",",$_POST['id'])).' User are deactivated.';
		}else if($_POST['status'] == 1)
		{
			$status = 'Activated';
			$success_msg = 'Success! '.count(explode(",",$_POST['id'])).' User are activate.';
		}else if($_POST['status'] == 2)
		{
			$status = 'Deleted';
			$success_msg = 'Success! '.count(explode(",",$_POST['id'])).' User are delete.';
		}
		foreach(explode(",",$_POST['id']) as $id)
		{
			$dat = $this->Mdl_user->active_user($id,$_POST['status']);
			
			$all_admin_get = $this->Mdl_user->all_admin_get();
			foreach($all_admin_get as $admin_get)
			{
				$this->Mdl_user->notifications_list($id,$admin_get['id'],$status,'Admin');
			}
			/*$all_manager_get = $this->Mdl_user->all_manager_get();
			foreach($all_manager_get as $manager_get)
			{
				$this->Mdl_user->notifications_list($id,$manager_get['id'],$status,'Manager');
			}
			$all_help_desk_get = $this->Mdl_user->all_help_desk_get();
			foreach($all_help_desk_get as $help_desk_get)
			{
				$this->Mdl_user->notifications_list($id,$help_desk_get['id'],$status,'Help Desk');
			}*/
		
		}
		$this->session->set_userdata('success_msg',$success_msg);
	}
	public function active_user()
	{
		$dat = $this->Mdl_user->active_user($_POST['id'],$_POST['status']);
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
		
		$all_admin_get = $this->Mdl_user->all_admin_get();
		foreach($all_admin_get as $admin_get)
		{
			if($admin_get['id'] != $this->session->Admindetail['id'])
			{
				$this->Mdl_user->notifications_list($_POST['id'],$admin_get['id'],$status,'Admin');
			}
		}
		/*$all_manager_get = $this->Mdl_user->all_manager_get();
		foreach($all_manager_get as $manager_get)
		{
			$this->Mdl_user->notifications_list($_POST['id'],$manager_get['id'],$status,'Manager');
		}
		$all_help_desk_get = $this->Mdl_user->all_help_desk_get();
		foreach($all_help_desk_get as $help_desk_get)
		{
			$this->Mdl_user->notifications_list($_POST['id'],$help_desk_get['id'],$status,'Help Desk');
		}*/
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