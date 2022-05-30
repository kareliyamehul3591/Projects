<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_assignment extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_manager_assignment');
	}
	public function index()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$data=array(
				'main_content'=>'manager_assignment',
				'left_sidebar'=>'Manager Assignment',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function edit_taske($id)
	{
		$assignment_data = $this->Mdl_manager_assignment->assignments($id);
		$data = $assignment_data[0];
					$actions =	'<form method="post" class="login-form" action="'.base_url().'index.php/Manager_assignment/assignment_edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">Edit Assignment Date</h4>
										</div>
										<div class="modal-body">
											<input type="hidden" name="ids" id="ids" value="'.$data['id'].'">
											<input type="hidden" name="files" id="files" value="'.$data['file'].'">
											
											<div class="row">
												<div class="col-xs-6">
													<input type="text" name="name" id="name" class="form-control" placeholder="Name *" value="'.$data['name'].'" required >
												</div>
												<div class="col-xs-6">
													<select class="form-control" name="client_name" id="client_name" required >
														<option value="">Select One Client Name *</option>';
														 for($x = 1; $x <= 100; $x++){
															 
															$actions .= '<option value="C'.$x.'"';
															if($data['client_name'] == 'C'.$x){ 
																$actions .= 'selected'; 
															} 
															$actions .= '>C'.$x.'</option>';
														 }
													$actions .= '</select>
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-6">
													<div class="bootstrap-timepicker">
														<div class="form-group">
														  <div class="input-group">
															<input type="text" name="deadline_date" id="deadline_date" class="form-control datepicker" placeholder="Deadline Date *" value="'.date('m/d/Y h:i A', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )).'" required readonly >
															<div class="input-group-addon">
															  <i class="fa fa-calendar"></i>
															</div>
														  </div>
														</div>
													</div>
													
												</div>
												<div class="col-xs-6">
													<select class="form-control" name="assignment_type" id="assignment_type" required >
														<option value="">Select One Assignment Type *</option>
														<option value="Product Review"';
														if($data['assignment_type'] == 'Product Review'){
															$actions .= 'selected'; 
														}
														$actions .= '>Product Review</option>
														<option value="Article Review"';
														if($data['assignment_type'] == 'Article Review'){
															$actions .= 'selected'; 
														} $actions .= '>Article Review</option>
													</select>
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-6">
													<select class="form-control" name="tasks_no" id="tasks_no" required >
														<option value="">Select One No Of Tasks *</option>';
														for($x = 1; $x <= 100; $x++){
															$actions .= '<option value="'.$x.'"';
															if($data['tasks_no'] == $x){ 
																$actions .= 'selected';
															}
															
															$actions .= '>'.$x.'</option>';
														}
													$actions .= '</select>
												</div>
												<div class="col-xs-6">
													<select class="form-control" name="health" id="health" required >
														<option value="">Niche *</option>
														<option value="Arts and Entertainment"';
														if($data['health'] == "Arts and Entertainment"){ $actions .= 'selected'; }$actions .= '>Arts and Entertainment</option>
														<option value="Academic"';
														if($data['health'] == "Academic"){ $actions .= 'selected'; }
														$actions .= '>Academic</option>
														<option value="Advertisement"';
														if($data['health'] == "Advertisement"){ $actions .= 'selected'; }
														$actions .= '>Advertisement</option>
														<option value="Beauty"';
														if($data['health'] == "Beauty"){ $actions .= 'selected'; } 
														$actions .= '>Beauty</option>
														<option value="Blogs"';
														if($data['health'] == "Blogs"){ $actions .= 'selected'; }
														$actions .= '>Blogs</option>
														<option value="Branding"';
														if($data['health'] == "Branding"){ $actions .= 'selected'; } 
														$actions .= ' >Branding</option>
														<option value="Brochures/Pamphlets" ';
														if($data['health'] == "Brochures/Pamphlets"){ $actions .=  'selected'; }  $actions .= '>Brochures/Pamphlets</option>
														<option value="Business/Money"';
														if($data['health'] == "Business/Money"){ $actions .=  'selected'; }
														$actions .= '>Business/Money</option>
														<option value="Case Study" ';
														if($data['health'] == "Case Study"){ $actions .=  'selected'; } 
														$actions .= '>Case Study</option>
														<option value="Crafts and hobbies"';
														if($data['health'] == "Crafts and hobbie"){ $actions .=  'selected'; }
														$actions .= '>Crafts and hobbies</option>
														<option value="Education and Learning" ';
														if($data['health'] == "Education and Learning"){ $actions .=  'selected'; }$actions .= '>Education and Learning</option>
														<option value="Email" ';
														if($data['health'] == "Email"){ $actions .=  'selected'; } 
														$actions .= '>Email</option>
														<option value="Fashion and Style"';
														if($data['health'] == "Fashion and Style"){ $actions .=  'selected'; }
														$actions .= '>Fashion and Style</option>
														<option value="Games"';
														if($data['health'] == "Games"){ $actions .=  'selected'; }
														$actions .= '>Games</option>
														<option value="Health & Wellness"';
														if($data['health'] == "Health & Wellness"){ $actions .=  'selected'; }
														$actions .= '>Health & Wellness</option>
														<option value="Job"';
														if($data['health'] == "Job"){ $actions .=  'selected'; }
														$actions .= '>Job</option>
														<option value="Lifestyle"';
														if($data['health'] == "Lifestyle"){ $actions .=  'selected'; }
														$actions .= '>Lifestyle</option>
														<option value="Multimedia"';
														if($data['health'] == "Multimedia"){ $actions .= 'selected'; }
														$actions .= '>Multimedia</option>
														<option value="Online Ads"';
														if($data['health'] == "Online Ads"){ $actions .= 'selected'; } 
														$actions .='>Online Ads</option>
														<option value="Presentation"';
														if($data['health'] == "Presentation"){ $actions .= 'selected'; }
														$actions .='>Presentation</option>
														<option value="Research"';
														if($data['health'] == "Research"){ $actions .= 'selected'; } 
														$actions .='>Research</option>
														<option value="SEO"';
														if($data['health'] == "SEO"){ $actions .= 'selected'; } 
														$actions .='>SEO</option>
														<option value="Sports Technology"';
														if($data['health'] == "Sports Technology"){ $actions .= 'selected'; } $actions .='>Sports Technology</option>
														<option value="Travel"';
														if($data['health'] == "Travel"){ $actions .= 'selected'; } 
														$actions .='>Travel</option>
														<option value="Web Content"';
														if($data['health'] == "Web Content"){ $actions .= 'selected'; }
														$actions .='>Web Content</option>
													</select>
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-6">
													<input type="number" name="article" id="article" class="form-control" placeholder="No. of Words/Article *" value="'.$data['article'].'" required >
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-12">
													<textarea id="description_'.$data['id'].'" name="description" placeholder="Description" class="description_'.$data['id'].'">'.$data['description'].'</textarea>
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-12">
													<input type="file" name="file[]" id="file" class="form-control" multiple>
												</div>
											</div></br>';
											
											if($data['file'] != null)
											{
												foreach(explode(",", $data['file']) as $file)
												{
													if($file != NULL)
													{
														$key++;
														$actions .= $key." :- ";
														$actions .= '<a href="'.base_url().'/uploads/Assignment/'.$file.'" target="_blank" >'.$file.'</a>';
														$actions .= '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$data['id'].')"></i>';
														$actions .= '</br>';
													}	
												} 
												$key = 0;
											}
										$actions .='</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Save changes</button>
										</div>
									</form>';
						
		$actions .= '<script type="text/javascript">
						CKEDITOR.replace("description_'.$data['id'].'")
						var defDate = new Date ();
							defDate.setHours(defDate.getHours() + 2);
							$( ".datepicker" ).datetimepicker({
								format: "mm/dd/yyyy HH:ii P",
								showMeridian: true,
								autoclose: true,
								todayBtn: true,
								startDate: defDate,
							});
					</script>';
		echo $actions;
	}
	
	public function assignment_add()
	{
		if (!is_dir('uploads/Assignment')) {
			mkdir('./uploads/Assignment', 0777, TRUE);
		}
		$count = count($_FILES['file']['name']);
		$count--;
		$filename = array();
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext=$this->Mdl_manager_assignment->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Assignment/';
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');
					$this->upload->initialize($config);
					if($this->upload->do_upload('image')) 
					{
					  $config['image_library'] = 'gd2';
					  $config['source_image']  = './uploads/Assignment/'.$filename[$i];
					  $this->load->library('image_lib', $config);
					  $this->image_lib->resize();
					  $this->image_lib->clear();
					}
				}
			}
		}
		$filename = array_filter($filename);
		if($_POST['files'] == null)
		{
			$result = array_splice($filename,0,5,true);
			$filename = implode(",",$result);
			$assignment_add=$this->Mdl_manager_assignment->assignment_add($filename);
			$this->Mdl_manager_assignment->logs_insert($assignment_add,"Add");
		}else{
			
			$result = array_splice($filename,0,5,true);
			$filename = implode(",",$result);
			
			$assignment_add=$this->Mdl_manager_assignment->assignment_add($filename);
			$this->Mdl_manager_assignment->logs_insert_c($assignment_add,'Clone',$_POST['reason']);
		}
		
		$all_admin_get = $this->Mdl_manager_assignment->all_admin_get();
		foreach($all_admin_get as $admin_get)
		{
			$this->Mdl_manager_assignment->notifications($assignment_add,'waiting for approval',$admin_get['id'],'Admin');
		}
		
		$success_msg='Success! Assignment '.$assignment_add.' is added.';
		$this->session->set_flashdata('success_msg',$success_msg);
		
		redirect('Manager_dashboard');
	}
	public function assignment_edit()
	{
		$count = count($_FILES['file']['name']);
		$count--;
		$assignments = $this->Mdl_manager_assignment->assignments($id);
		$filess = explode(",",$assignments[0]['file']);
		$filename = array();
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext=$this->Mdl_manager_assignment->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Assignment/';
					$config['allowed_types'] = 'docx|doc';
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');
					$this->upload->initialize($config);
					if($this->upload->do_upload('image')) 
					{
					  $config['image_library'] = 'gd2';
					  $config['source_image']  = './uploads/Assignment/'.$filename[$i];
					  $this->load->library('image_lib', $config);
					  $this->image_lib->resize();
					  $this->image_lib->clear();
					}
					foreach($filess as $key => $files)
					{
						if($files == $_FILES['image']['name'])
						{
							unset($filess[$key]);
						}
					}
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
		$assignment_add=$this->Mdl_manager_assignment->assignment_edit($file);
		
		$success_msg='Success! Assignment '.$assignment_add.' is edited.';
		$this->session->set_flashdata('success_msg',$success_msg);
		
		redirect('Manager_dashboard');
	}
	public function edit_assignment($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$assignment_data = $this->Mdl_manager_assignment->assignments($id);
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_edit',
				'left_sidebar'=>'Assignment Edit',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_view()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$assignment_data = $this->Mdl_manager_assignment->assignment();
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'manager_assignment_view',
				'left_sidebar'=>'Manager Assignment View',
			);
			
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_delete($id)
	{
		$user_data = $this->Mdl_manager_assignment->assignment_delete($id);
		redirect('Manager_assignment/assignment_view');
	}
	public function active_assignment()
	{
		$user_data = $this->Mdl_manager_assignment->active_assignment();
	}
	
	public function remove_file()
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_manager_assignment->assignment_id($id);
		foreach($getdatas as $ab)
		{
			$getemage = explode(',',$ab['file']);
		}
		unset($getemage[$ids]);
		$images = $this->Mdl_manager_assignment->file_update(implode(",", $getemage));
	}
}
?>