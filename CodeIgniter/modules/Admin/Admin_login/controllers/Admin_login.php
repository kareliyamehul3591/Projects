<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_login extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('cookie', 'url'));
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_admin_login');
	}
	Public function index()
	{
		$data =array(
		);
		$this->load->view('admin_login',$data);
	}

	public function login()
	{
		$rowdata = $this->Mdl_admin_login->admin_login_check();
		
		if($rowdata == null)
		{
			$error_msg='Login failed. Please enter a valid email and password.';
			$this->session->set_flashdata('error_msg',$error_msg);
			redirect('Admin_login');
		}else{
			$this->Mdl_admin_login->admin_login_online($rowdata['id'],1);
			
			$data['id'] = $rowdata['id'];
			$data['name'] = $rowdata['name'];
			$data['email'] = $rowdata['email'];
			$data['first_name'] = $rowdata['first_name'];
			$data['lastst_name'] = $rowdata['lastst_name'];
			$data['sex'] = $rowdata['sex'];
			$data['skype_id'] = $rowdata['skype_id'];
			$data['freelancer_id'] = $rowdata['freelancer_id'];
			$data['phone_number'] = $rowdata['phone_number'];
			$data['company_name'] = $rowdata['company_name'];
			$data['company_location'] = $rowdata['company_location'];
			$data['company_country'] = $rowdata['company_country'];
			$data['super_admin'] = $rowdata['super_admin'];
			$data['admin'] = $rowdata['admin'];
			$data['manager'] = $rowdata['manager'];
			$data['writer'] = $rowdata['writer'];
			$data['proof_reader'] = $rowdata['proof_reader'];
			$data['help_desk'] = $rowdata['help_desk'];
			
			$this->session->set_userdata("Admindetail",$data);
			
			if($data['admin'] == 1)
			{
				$admin_dashboard['roles'] = 'admin';
				$admin_dashboard['users'] = $data['id'];
				$this->session->set_userdata("admin_dashboard",$admin_dashboard);
			}
			if($data['manager'] == 1){
				$manager_dashboard['roles'] = 'manager';
				$manager_dashboard['users'] = $data['id'];
				$this->session->set_userdata("manager_dashboard",$manager_dashboard);
			}
			if($data['help_desk'] == 1){
				$manager_dashboard['roles'] = 'help_desk';
				$manager_dashboard['users'] = $data['id'];
				$this->session->set_userdata("help_desk_dashboard",$manager_dashboard);
			}
			if($_POST['role'] == 'Super Admin')
			{
				$success_msg='Welcome '.$data['name'].'';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Admin_dashboard');
			}else if($_POST['role'] == 'Admin'){
				$success_msg='Welcome '.$data['name'].'';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Dashboard');
			}else if($_POST['role'] == 'Manager'){
				$success_msg='Welcome '.$data['name'].'';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else if($_POST['role'] == 'Writer'){
				$success_msg='Welcome '.$data['name'].'. You are logged in as a writer';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Writer_dashboard');
			}else if($_POST['role'] == 'Proof Reader'){
				$success_msg='Welcome '.$data['name'].'';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Proof_reader_dashboard');
			}else if($_POST['role'] == 'Help Desk'){
				$success_msg='Welcome '.$data['name'].'';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Help_desk_dashboard');
			}
		}
	}
	Public function logout()
	{
		$this->Mdl_admin_login->admin_login_online($this->session->Admindetail['id'],0);
		
		$all_userdata = $this->session->all_userdata();
		$this->session->unset_userdata($all_userdata);
        session_destroy();   
        redirect('Admin_login');
	}
	Public function loginchake()
	{
		$email = $this->session->Admindetail['email'];
		$rowdata = $this->Mdl_admin_login->loginchake($email);
		
		$data['id'] = $rowdata['id'];
		$data['name'] = $rowdata['name'];
		$data['email'] = $rowdata['email'];
		$data['first_name'] = $rowdata['first_name'];
		$data['lastst_name'] = $rowdata['lastst_name'];
		$data['sex'] = $rowdata['sex'];
		$data['skype_id'] = $rowdata['skype_id'];
		$data['freelancer_id'] = $rowdata['freelancer_id'];
		$data['phone_number'] = $rowdata['phone_number'];
		$data['company_name'] = $rowdata['company_name'];
		$data['company_location'] = $rowdata['company_location'];
		$data['company_country'] = $rowdata['company_country'];
		$data['super_admin'] = $rowdata['super_admin'];
		$data['admin'] = $rowdata['admin'];
		$data['manager'] = $rowdata['manager'];
		$data['writer'] = $rowdata['writer'];
		$data['proof_reader'] = $rowdata['proof_reader'];
		$data['help_desk'] = $rowdata['help_desk'];
		$this->session->set_userdata("Admindetail",$data);
		echo $rowdata['status'];
	}
	
	
	public function forgot_password()
	{
		if($_POST != null)
		{
			$rowdata = $this->Mdl_admin_login->forgot_password_check();
			if($rowdata == null)
			{
				$error_msg='Please enter a valid email.';
				$this->session->set_flashdata('error_msg',$error_msg);
			}else{
				
				$to = $rowdata['email'];
				$subject = "Forgot Password";
				$txt = '<a href="httpis://www.rrfreelancer.com/mehul/index.php/Admin_login/resate_password/'.md5($rowdata['id']).'" >Reset Password</a>';
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				mail($to,$subject,$txt,$headers);
				
				$success_msg='Your request for password reset link is sent to your registered email address. to go to Sign In. If you have not received the mail then contact admin.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}
		}
		$this->load->view('forgot_password');
	}
	public function resate_password($id)
	{
		$rowdata = $this->Mdl_admin_login->all_user_data();
		foreach($rowdata as $rowdatas)
		{
			if(md5($rowdatas['id']) == $id)
			{
				$data =array(
					'id'=>$id,
					'datas'=>$rowdatas,
				);
				$this->load->view('resate_password',$data);
			}
		}
	}
	public function set_password()
	{
		if($_POST['password'] == $_POST['co_password'])
		{
			$rowdata = $this->Mdl_admin_login->set_password($_POST['ids'],md5($_POST['password']));
		
			$success_msg='your Password Reset. Please Login.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else{
			$error_msg='Password is not match.';
			$this->session->set_flashdata('error_msg',$error_msg);
			redirect('Admin_login/resate_password/'.$_POST['md5']);
		}
		redirect('Admin_login');
	}
	Public function remove_file_uplode()
	{
		$datas = $this->Mdl_admin_login->filetemp_get();
		
		$files = explode(",",$datas['file']);
		$key = $_POST['key'] - 1;
		unset($files[$key]);
		$this->Mdl_admin_login->filetemp_update($datas['id'],implode(",",$files));
		
		$actions = '';
		$key = 0;
		foreach($files as $file)
		{
			if($file != NULL)
			{
				$key++;
				$actions .= $key." :- ";
				$actions .= '<a href="'.base_url().'uploads/User/'.$file.'" target="_blank" >'.$file.'</a>';
				$actions .= '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.')"></i>';
				$actions .= '</br>';
			}	
		}
		echo $actions;
	}
	Public function file_uplode()
	{
		$count = count($_FILES['file']['name']);		
		if (!is_dir('uploads/User')) {
			mkdir('./uploads/User', 0777, TRUE);
		}			
		$count--;
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext=$this->Mdl_admin_login->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
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
				}else{
					echo '<label style="color:#FB3A3A;font-weight:bold;" >
						Only .txt, .doc, .docx, .xls, .xlsx, .pdf,.txt,.zip, all image files are allowed.
					</label>';
					die;
				}
				
			}else{
				echo '<label style="color:#FB3A3A;font-weight:bold;" >
					Each file upload llimit is 50 MB and a maximum of 5 files are allowed.
				</label>';
				die;
			}
		}
		$datas = $this->Mdl_admin_login->filetemp_get();
		$this->Mdl_admin_login->filetemp_empty();
		$filenam = explode(",",$datas['file']);
		
		$result = array_filter(array_unique(array_merge($filename, $filenam)));
		
		$result = array_splice($result,0,5,true);
		
		$rowdata = $this->Mdl_admin_login->filetemp_insert(implode(",",$result));
		$actions = '';
		$key = 0;
		foreach($result as $file)
		{
			if($file != NULL)
			{
				$key++;
				$actions .= $key." :- ";
				$actions .= '<a href="'.base_url().'uploads/User/'.$file.'" target="_blank" >'.$file.'</a>';
				$actions .= '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.')"></i>';
				$actions .= '</br>';
			}	
		}
		echo $actions;
	}
	Public function registration()
	{
		if($_POST != null)
		{
			$datas = $this->Mdl_admin_login->filetemp_get();
			$this->Mdl_admin_login->filetemp_empty();
			
			$rowdata = $this->Mdl_admin_login->registration($datas['file']);
			
			$all_admin_get = $this->Mdl_admin_login->all_admin_get();
			foreach($all_admin_get as $admin_get)
			{ 
				$this->Mdl_admin_login->notifications_list($rowdata,$admin_get['id'],"Request Pending");
			}
			$success_msg='Thank you for registering with us. Your request is pending with Admin for Approval.';
			$this->session->set_flashdata('success_msg',$success_msg);
			
			redirect('Admin_login');
		}else{
			$this->Mdl_admin_login->filetemp_empty();
		}
		$this->load->view('registration');
	}
	Public function email_id_check()
	{
		$email_id_check = $this->Mdl_admin_login->email_id_check();
		if($email_id_check != null)
		{
			echo json_encode(FALSE);
		}
		else
		{
			echo json_encode(TRUE);
		}
	}
	Public function name_check()
	{
		$name_check = $this->Mdl_admin_login->name_check();
		if($name_check != null)
		{
			echo json_encode(FALSE);
		}
		else
		{
			echo json_encode(TRUE);
		}
	}
	Public function get_role_user()
	{
		$users = $this->Mdl_admin_login->get_role_user($_POST['role']);
		$data = '<option value="" >Select one user</option>';
		$data .= '<option value="all" >All</option>';
		foreach($users as $user)
		{
			$data .= '<option value="'.$user['id'].'" >'.$user['name'].'</option>';
		}
		echo $data;
	}
	Public function change_role_user()
	{
		$rowdata = $this->Mdl_admin_login->get_user_id($_POST['users']);
		$rowdata = $rowdata[0];
		
		$data['id'] = $rowdata['id'];
		$data['name'] = $rowdata['name'];
		$data['email'] = $rowdata['email'];
		$data['first_name'] = $rowdata['first_name'];
		$data['lastst_name'] = $rowdata['lastst_name'];
		$data['sex'] = $rowdata['sex'];
		$data['skype_id'] = $rowdata['skype_id'];
		$data['freelancer_id'] = $rowdata['freelancer_id'];
		$data['phone_number'] = $rowdata['phone_number'];
		$data['company_name'] = $rowdata['company_name'];
		$data['company_location'] = $rowdata['company_location'];
		$data['company_country'] = $rowdata['company_country'];
		$data['super_admin'] = $rowdata['super_admin'];
		$data['admin'] = $rowdata['admin'];
		$data['manager'] = $rowdata['manager'];
		$data['writer'] = $rowdata['writer'];
		$data['proof_reader'] = $rowdata['proof_reader'];
		$data['help_desk'] = $rowdata['help_desk'];
		
		$this->session->set_userdata("Admin",$data);
		
		if($_POST['role'] == 'super_admin')
		{
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Admin_dashboard');
		}else if($_POST['role'] == 'admin'){
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Dashboard');
		}else if($_POST['role'] == 'manager'){
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Manager_dashboard');
		}else if($_POST['role'] == 'writer'){
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Writer_dashboard');
		}else if($_POST['role'] == 'proof_reader'){
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Proof_reader_dashboard');
		}else if($_POST['role'] == 'help_desk'){
			$success_msg='Welcome '.$data['name'].'';
			$this->session->set_flashdata('success_msg',$success_msg);
			redirect('Help_desk_dashboard');
		}
	}
}
?>
