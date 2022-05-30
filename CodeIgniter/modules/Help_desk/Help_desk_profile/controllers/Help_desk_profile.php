<?php defined('BASEPATH') OR exit('No direct script access allowed');class Help_desk_profile extends MX_Controller {	function __construct()	{		parent::__construct();		date_default_timezone_set("Asia/Kolkata");		$this->load->model('Mdl_help_desk__profile');	}	public function index()	{		if($this->session->Admindetail['proof_reader'] == 1)		{			$personal_details = $this->Mdl_help_desk__profile->personal_details();			$work_experience_get = $this->Mdl_help_desk__profile->work_experience_get();			$payment_details_get = $this->Mdl_help_desk__profile->payment_details_get();			$data=array(				'personal_details'=>$personal_details,				'work_experience_get'=>$work_experience_get,				'payment_details_get'=>$payment_details_get,				'main_content'=>'help_desk_profile',				'left_sidebar'=>'Help Desk Profile',			);			$this->load->view('help_desk_template/template',$data);		}else{			redirect('Admin_login');		}	}	public function update_personal_details()	{		$count = count($_FILES['file']['name']);		$count--;		$user_data = $this->Mdl_help_desk__profile->personal_details();		$filess = explode(",",$user_data[0]['file']);		for($i = 0; $i <= $count; $i++)		{			$_FILES['image']['name']     = $_FILES['file']['name'][$i];			$_FILES['image']['type']     = $_FILES['file']['type'][$i];			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];			$_FILES['image']['error']    = $_FILES['file']['error'][$i];			$_FILES['image']['size']     = $_FILES['file']['size'][$i];					$ext = $this->Mdl_help_desk__profile->get_file_extension($_FILES['image']['name']);			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);			if($_FILES['image']['size'] < 52428800)			{				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')				{					$filename[$i]='';					$filename[$i]=$_FILES['image']['name'];					$config['upload_path'] = './uploads/User/';					$config['allowed_types'] = '*';					$config['overwrite'] = TRUE;					$config['file_name']=$filename[$i];					$this->load->library('upload');    					$this->upload->initialize($config);					if($this->upload->do_upload('image')) 					{					  $config['image_library'] = 'gd2';					  $config['source_image']  = './uploads/User/'.$filename[$i];					  $this->load->library('image_lib', $config); 					  $this->image_lib->resize(); 					  $this->image_lib->clear();					}				}			}			foreach($filess as $key => $files)			{				if($files == $_FILES['image']['name'])				{					unset($filess[$key]);				}			}		}		$filess = array_filter($filess);		$filename = array_filter($filename);		if($filename == null)		{			$filess = array_splice($filess,0,5,true);			$file = implode(",",$filess);		}else{			$result = array_filter(array_unique(array_merge($filename, $filess)));			$result = array_splice($result,0,5,true);			$file = implode(",",$result);		}		$data = $this->Mdl_help_desk__profile->update_personal_details($file);				$timings = $this->Mdl_help_desk__profile->available_timings();		if($timings == null)		{			$this->Mdl_help_desk__profile->timings_add();		}else{			$this->Mdl_help_desk__profile->timings_edit(); 		}		$payment_details_get = $this->Mdl_help_desk__profile->payment_details_get();		if($payment_details_get == null)		{			$this->Mdl_help_desk__profile->payment_details_null();		}		if($_POST['payment_method'] == "Freelancer")		{			if($_POST['profile_url'] != null)			{				$data=array(					'payment_type'=>$_POST['payment_type'],					'payment_method'=>$_POST['payment_method'],					'profile_url'=>$_POST['profile_url'],					'paypal_user_id'=>'',					'contact_no'=>'',					'bank_name'=>'',					'account_no'=>'',					'ifsc_code'=>'',				);									$update = $this->Mdl_help_desk__profile->payment_details_update($data);				}		}else if($_POST['payment_method'] == "Paypal"){			if($_POST['paypal_user_id'] != null)			{				$data=array(					'payment_type'=>$_POST['payment_type'],					'payment_method'=>$_POST['payment_method'],					'profile_url'=>'',					'paypal_user_id'=>$_POST['paypal_user_id'],					'contact_no'=>'',					'bank_name'=>'',					'account_no'=>'',					'ifsc_code'=>'',				);									$update = $this->Mdl_help_desk__profile->payment_details_update($data);			}		}else if($_POST['payment_method'] == "Paytm"){			if($_POST['contact_no'] != null)			{				$data=array(					'payment_type'=>$_POST['payment_type'],					'payment_method'=>$_POST['payment_method'],					'profile_url'=>'',					'paypal_user_id'=>'',					'contact_no'=>$_POST['contact_no'],					'bank_name'=>'',					'account_no'=>'',					'ifsc_code'=>'',				);									$update = $this->Mdl_help_desk__profile->payment_details_update($data);			}					}else if($_POST['payment_method'] == "Bank Transfer"){			if($_POST['bank_name'] != null && $_POST['account_no'] != null && $_POST['ifsc_code'] != null)			{				$data=array(					'payment_type'=>$_POST['payment_type'],					'payment_method'=>$_POST['payment_method'],					'profile_url'=>'',					'paypal_user_id'=>'',					'contact_no'=>'',					'bank_name'=>$_POST['bank_name'],					'account_no'=>$_POST['account_no'],					'ifsc_code'=>$_POST['ifsc_code'],				);									$update = $this->Mdl_help_desk__profile->payment_details_update($data);			}						}else{			$data=array(					'payment_type'=>$_POST['payment_type'],					'payment_method'=>"",					'profile_url'=>'',					'paypal_user_id'=>'',					'contact_no'=>'',					'bank_name'=>"",					'account_no'=>"",					'ifsc_code'=>"",				);			$update = $this->Mdl_help_desk__profile->payment_details_update($data);		}				$success_msg='Your Profile Personal Details is Update.';		$this->session->set_userdata('success_msg',$success_msg);		if($_POST['password'] != null)		{			redirect('Admin_login/logout');		}else{			redirect('Help_desk_profile');		}	}	public function work_experience_details()	{		if($_POST['user_id'] == null)		{			$data = $this->Mdl_help_desk__profile->work_experience();					$success_msg='Your Work Experience Details is Insert.';			$this->session->set_userdata('success_msg',$success_msg);			redirect('Help_desk_profile');		}else{			$data = $this->Mdl_help_desk__profile->work_experience_update();					$success_msg='Your Work Experience Details is Update.';			$this->session->set_userdata('success_msg',$success_msg);			redirect('Help_desk_profile');		}			}	public function payment_details()	{		if($_POST['user_id'] == null)		{			if($_POST['payment_method'] == "Freelancer")			{				if($_POST['profile_url'] != null)				{					$data = $this->Mdl_help_desk__profile->payment_details();							$success_msg='Your Payment Details is Insert.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}			}else if($_POST['payment_method'] == "Paypal"){				if($_POST['paypal_user_id'] != null)				{					$data = $this->Mdl_help_desk__profile->payment_details();							$success_msg='Your Payment Details is Insert.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}			}else if($_POST['payment_method'] == "Paytm"){				if($_POST['contact_no'] != null)				{					$data = $this->Mdl_help_desk__profile->payment_details();							$success_msg='Your Payment Details is Insert.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}							}else if($_POST['payment_method'] == "Bank Transfer"){				if($_POST['bank_name'] != null && $_POST['account_no'] != null && $_POST['ifsc_code'] != null)				{					$data = $this->Mdl_help_desk__profile->payment_details();							$success_msg='Your Payment Details is Insert.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}							}		}else{						if($_POST['payment_method'] == "Freelancer")			{				if($_POST['profile_url'] != null)				{					$data=array(						'payment_type'=>$_POST['payment_type'],						'payment_method'=>$_POST['payment_method'],						'profile_url'=>$_POST['profile_url'],						'paypal_user_id'=>'',						'contact_no'=>'',						'bank_name'=>'',						'account_no'=>'',						'ifsc_code'=>'',					);							$update = $this->Mdl_help_desk__profile->payment_details_update($data);							$success_msg='Your Payment Details is Update.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}			}else if($_POST['payment_method'] == "Paypal"){				if($_POST['paypal_user_id'] != null)				{					$data=array(						'payment_type'=>$_POST['payment_type'],						'payment_method'=>$_POST['payment_method'],						'profile_url'=>'',						'paypal_user_id'=>$_POST['paypal_user_id'],						'contact_no'=>'',						'bank_name'=>'',						'account_no'=>'',						'ifsc_code'=>'',					);										$update = $this->Mdl_help_desk__profile->payment_details_update($data);							$success_msg='Your Payment Details is Update.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}			}else if($_POST['payment_method'] == "Paytm"){				if($_POST['contact_no'] != null)				{					$data=array(						'payment_type'=>$_POST['payment_type'],						'payment_method'=>$_POST['payment_method'],						'profile_url'=>'',						'paypal_user_id'=>'',						'contact_no'=>$_POST['contact_no'],						'bank_name'=>'',						'account_no'=>'',						'ifsc_code'=>'',					);										$update = $this->Mdl_help_desk__profile->payment_details_update($data);							$success_msg='Your Payment Details is Update.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}							}else if($_POST['payment_method'] == "Bank Transfer"){				if($_POST['bank_name'] != null && $_POST['account_no'] != null && $_POST['ifsc_code'] != null)				{					$data=array(						'payment_type'=>$_POST['payment_type'],						'payment_method'=>$_POST['payment_method'],						'profile_url'=>'',						'paypal_user_id'=>'',						'contact_no'=>'',						'bank_name'=>$_POST['bank_name'],						'account_no'=>$_POST['account_no'],						'ifsc_code'=>$_POST['ifsc_code'],					);										$update = $this->Mdl_help_desk__profile->payment_details_update($data);							$success_msg='Your Payment Details is Update.';					$this->session->set_userdata('success_msg',$success_msg);				}else{					$error_msg='Please enter a valid data.';					$this->session->set_userdata('error_msg',$error_msg);				}							}		}		redirect('Help_desk_profile');			}		}?>