<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_assignment');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$data=array(
				'main_content'=>'assignment',
				'left_sidebar'=>'Assignment',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_add()
	{
		if (!is_dir('uploads/Assignment')) {
			mkdir('./uploads/Assignment', 0777, TRUE);
		}
		$count = count($_FILES['file']['name']);
		$count--;
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext=$this->Mdl_assignment->get_file_extension($_FILES['image']['name']);
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
		$assignment_add = $this->Mdl_assignment->assignment_add(implode(",",$filename));
		
		$this->Mdl_assignment->logs_insert($assignment_add," ",'Add');
		
		
		$datetime1 = new DateTime(date("Y-m-d H:i:s"));
		$deadline_date = date('Y-m-d H:i:s', strtotime( $_POST['deadline_date'].' '.$_POST['deadline_time'] ) );
		$datetime2 = new DateTime($deadline_date);
		
		$interval = $datetime1->diff($datetime2);
		$hours = $interval->h + ($interval->days*24);
		$minit = $interval->i + ($hours*60);
		
		$assign_hours = ($minit / 10)*9 / 60;
		$save_hours = ($minit / 10) / 60;
		
		if($_POST['manager_id'] != null)
		{
			$assign_to_manager = $this->Mdl_assignment->assign_to_manager($_POST['manager_id'],$assignment_add,$deadline_date,$save_hours,$assign_hours);
			$this->Mdl_assignment->notifications_list($_POST['manager_id'],'Assigned',$assignment_add,"Manager");
			$this->Mdl_assignment->logs_insert_c($assignment_add,$_POST['manager_id'],$_POST['reason'],'Assigned');
			
			$this->Mdl_assignment->assignment_admin_status($assignment_add,1);
			
		}
		if($_POST['writer_id'] != null)
		{
			$assign_hours_writer = ((($assign_hours/3)*2)/3)*2;				
			$save_hours = $assign_hours/3;
			$this->Mdl_assignment->managet_save_time($assignment_add,$save_hours);
			$this->Mdl_assignment->assign_to_writer($_POST['writer_id'],$assignment_add,$deadline_date,$assign_hours_writer);
			$this->Mdl_assignment->notifications_list($_POST['writer_id'],'Assigned',$assignment_add,"Writer");
			
			$this->Mdl_assignment->logs_insert_w($assignment_add,$_POST['writer_id'],$_POST['reason'],'Assigned');
			$this->Mdl_assignment->assignment_admin_status($assignment_add,1);
		}
		if($_POST['proof_reader_id'] != null)
		{			
			$assign_hours_proof_reader = (($assign_hours/3)*2)/3;				
			$save_hours = $assign_hours/3;
			$this->Mdl_assignment->managet_save_time($assignment_add,$save_hours);
			$this->Mdl_assignment->assign_to_proof_reader($_POST['proof_reader_id'],$assignment_add,$deadline_date,$assign_hours_proof_reader);
			$this->Mdl_assignment->notifications_list($_POST['proof_reader_id'],'Assigned',$assignment_add,"Proof Reader");
			
			$this->Mdl_assignment->logs_insert_p($assignment_add,$_POST['proof_reader_id'],$_POST['reason'],'Assigned');
			$this->Mdl_assignment->assignment_admin_status($assignment_add,1);
		}
		
		$success_msg='Success! Assignment '.$assignment_add.' is added.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Dashboard');
	}
	public function assignment_edit()
	{
		if($_FILES['file']['name'][0] != null)
		{
			$count = count($_FILES['file']['name']);
			$count--;
			$filess = explode(",",$_POST['files']);
			for($i = 0; $i <= $count; $i++)
			{
				$_FILES['image']['name']     = $_FILES['file']['name'][$i];
				$_FILES['image']['type']     = $_FILES['file']['type'][$i];
				$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
				$_FILES['image']['error']    = $_FILES['file']['error'][$i];
				$_FILES['image']['size']     = $_FILES['file']['size'][$i];
				
				$ext=$this->Mdl_assignment->get_file_extension($_FILES['image']['name']);
				$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
				
				if($_FILES['image']['size'] < 52428800)
				{
					if($ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
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
			$file = $filess.','.implode(",",$filename);
		}else{
			$filess = explode(",",$_POST['files']);
			$filess = array_filter($filess);
			$file = implode(",",$filess);
		}
		$assignment_add=$this->Mdl_assignment->assignment_edit($file);
		
		$success_msg='Success! Assignment '.$assignment_add.' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Assignment/assignment_view');
	}
	
	public function assignment_view()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$assignment_data = $this->Mdl_assignment->approval_assignment_list();
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'assignment_view',
				'left_sidebar'=>'Assignment View',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_delete($id)
	{
		if($id != null)
		{
			$user_data = $this->Mdl_assignment->assignment_delete($id);
			$success_msg='Assignment id Delete.';
			$this->session->set_userdata('success_msg',$success_msg);
		}else if(isset($_POST['assignment_list_delete'])){
			foreach($_POST['select_assignment_list'] as $list)
			{
				$user_data = $this->Mdl_assignment->assignment_delete($list);
			}
			$success_msg='Select Assignment is Delete.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Assignment/assignment_view');
	}
	
	public function remove_file()
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_assignment->assignment_id($id);
		foreach($getdatas as $ab)
		{
			$getemage = explode(',',$ab['file']);
		}
		unset($getemage[$ids]);
		$images = $this->Mdl_assignment->file_update(implode(",", $getemage));
	}
	public function approval_assignment()
	{
		$assignment = $this->Mdl_assignment->assignment_id($_POST['ids']);
		$this->Mdl_assignment->approval_assignment($_POST['ids'],$_POST['status']);
		if($_POST['status'] == 1)
		{
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_assignment->notifications_list($assignment[0]['created_id'],'Approval',$_POST['ids'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_assignment->notifications_list($assignment[0]['created_id'],'Approval',$_POST['ids'],"Manager");
			}
			$this->Mdl_assignment->logs_insert($_POST['ids'],$_POST['description'],'Approval');
			$success_msg='Success! Assignment '.$_POST['ids'].' is approved.';
			$this->session->set_userdata('success_msg',$success_msg);
		
		}else if($_POST['status'] == 2){
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_assignment->notifications_list($assignment[0]['created_id'],'Denied',$_POST['ids'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_assignment->notifications_list($assignment[0]['created_id'],'Denied',$_POST['ids'],"Manager");
			}
			$this->Mdl_assignment->logs_insert($_POST['ids'],$_POST['description'],'Denied');
			$success_msg='Success! Assignment '.$_POST['ids'].' is denied.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Dashboard');
	}
	public function approval_assignment_list()
	{
		$ids = explode(",",$_POST['ids']);
		$i = 0;
		foreach($ids as $id){
			$datas = array();
			$datas = $this->Mdl_assignment->assignment_id($id);
			if($datas[0]['status'] == 0)
			{
				$this->Mdl_assignment->approval_assignment($id,$_POST['status']);
				if($_POST['status'] == 1)
				{
					if($datas[0]['created_role'] == 'help_desk'){
						$this->Mdl_assignment->notifications_list($datas[0]['created_id'],'Approval',$id,"Help Desk");
					}else if($datas[0]['created_role'] == 'manager'){
						$this->Mdl_assignment->notifications_list($datas[0]['created_id'],'Approval',$id,"Manager");
					}
					$this->Mdl_assignment->logs_insert($id,$_POST['reason'],'Approval');
				}else if($_POST['status'] == 2){
					if($datas[0]['created_role'] == 'help_desk'){
						$this->Mdl_assignment->notifications_list($datas[0]['created_id'],'Denied',$id,"Help Desk");
					}else if($datas[0]['created_role'] == 'manager'){
						$this->Mdl_assignment->notifications_list($datas[0]['created_id'],'Denied',$id,"Manager");
					}
					$this->Mdl_assignment->logs_insert($id,$_POST['reason'],'Denied');
				}
				$i++;
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			if($_POST['status'] == 1)
			{
				$success_msg='Success! '.$i.' Assignment are approved.';
				$this->session->set_userdata('success_msg',$success_msg);
			}else if($_POST['status'] == 2){
				$success_msg='Success! '.$i.' Assignment are denied.';
				$this->session->set_userdata('success_msg',$success_msg);
			}
		}
		redirect('Dashboard');
	}
	public function approval_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Assignment/approval_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to Approval ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
						<input type="hidden" name="status" id="status" value="1">
						<textarea id="description" name="description" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
				<button type="submit" class="btn btn-primary">Yes</button>
			</div>
		</form>
		<?php
	}
	public function denied_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Assignment/approval_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to Denied ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
						<input type="hidden" name="status" id="status" value="2">
						<textarea id="description" name="description" placeholder="Enter The Reason Here" rows="5" cols="50" required ></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
				<button type="submit" class="btn btn-primary">Yes</button>
			</div>
		</form>
		<?php
	}
}
?>