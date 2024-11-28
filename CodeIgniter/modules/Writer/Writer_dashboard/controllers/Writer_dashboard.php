<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Writer_dashboard extends MX_Controller {
	
	function __construct()	{		
		parent::__construct();		
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_writer_dashboard');	
	}
	public function index()	
	{
		if($this->session->Admindetail['writer'] == 1)
		{			
			$assign_to_ma_all = $this->Mdl_writer_dashboard->assign_to_ma_all();
			$i = 0;
			foreach($assign_to_ma_all as $val) {
				if($val['assign_to_ma_status'] != 5 && $val['status'] != 4 && $val['assign_to_ma_status'] != 2)
				{
					if (!in_array($val['id'], $key_array)) {
						$key_array[$i] = $val['id'];
						$assignment_datas[$i] = $val;
					}
					$i++;
				}
			}
			$data=array(				
				'datas'=>$assignment_datas,
				'main_content'=>'writer_dashboard',	
				'left_sidebar'=>'Writer Dashboard',	
			);			
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}	
	}
	public function show_archived()	
	{
		if($this->session->Admindetail['writer'] == 1)
		{			
			$assign_to_ma_all = $this->Mdl_writer_dashboard->assign_to_ma_all();
			$i = 0;
			foreach($assign_to_ma_all as $val) {
				if($val['assign_to_ma_status'] == 5 || $val['status'] == 4)
				{
					if (!in_array($val['id'], $key_array)) {
						$key_array[$i] = $val['id'];
						$assignment_datas[$i] = $val;
					}
					$i++;
				}
			}
			$data=array(				
				'datas'=>$assignment_datas,
				'main_content'=>'show_archived',	
				'left_sidebar'=>'Show Archived',	
			);			
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}	
	}
	public function accept_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/active_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to Accept ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="status" id="status" value="1">
						<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
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
	public function reject_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/active_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to Reject ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="status" id="status" value="2">
						<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" required ></textarea>
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
	public function complete_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/complete_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to complete ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
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
	public function assignment_data($id)
	{
		if($this->session->Admindetail['writer'] == 1)
		{
			$assignment_data = $this->Mdl_writer_dashboard->assignment($id);
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_data',
				'left_sidebar'=>'Writer Dashboard',
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}	
	public function assignment_view($id)	
	{		
		if($this->session->Admindetail['writer'] == 1)	
		{			
			$this->Mdl_writer_dashboard->assignment_notifications($id);			
			$assignment_data = $this->Mdl_writer_dashboard->assignment($id);			
			$assignment_final_data = $this->Mdl_writer_dashboard->assignment_final_data($id);
			$assign_to_ma = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
			$assignment_data[0]['assign_to_ma_status'] = $assign_to_ma[0]['status'];
			$data = array(
				'datas'=>$assignment_data,
				'assignment_final_data'=>$assignment_final_data,
				'main_content'=>'assignment_view',
				'left_sidebar'=>'Writer Dashboard',	
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_image_add()	
	{
		$id = $_POST['assignment_id'];
		if (!is_dir('uploads/Assignment/'.$id)) {
			mkdir('./uploads/Assignment/'.$id, 0777, TRUE);		
		}
		if (!is_dir('uploads/Assignment/'.$id.'/writer')) {
			mkdir('./uploads/Assignment/'.$id.'/writer', 0777, TRUE);		
		}
		$count = count($_FILES['file']['name']);
		$count--;
		$assignment_final_data = $this->Mdl_writer_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['writer_file']);
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
			$ext=$this->Mdl_writer_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);			
			if(preg_match('/\s/',$_FILES['image']['name']) != 0){
				$error_msg='in Assigned File space is not Require.';
				$this->session->set_userdata('error_msg',$error_msg);
				redirect('Writer_dashboard/assignment_view/'.$id);
			}
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')		
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Assignment/'.$id.'/writer/';
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');
					$this->upload->initialize($config);
					if($this->upload->do_upload('image'))
					{
						$config['image_library'] = 'gd2';
						$config['source_image']  = './uploads/Assignment/'.$id.'/writer/'.$filename[$i];
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
		if($assignment_final_data == null)
		{
			$filename = array_splice($filename,0,5,true);
			$this->Mdl_writer_dashboard->assignment_image_add($id,implode(",",$filename));
		}else{
			$filess = array_filter($filess);
			$filename = array_filter($filename);
			if($filename == null)
			{
				$filess = array_splice($filess,0,5,true);
				$data = implode(",",$filess);
			}else{
				$result = array_filter(array_unique(array_merge($filename, $filess)));
				$result = array_splice($result,0,5,true);
				$data = implode(",",$result);
			}
			$this->Mdl_writer_dashboard->assignment_image_update($id,$data);
		}
		$this->Mdl_writer_dashboard->assignment_date_update($id);
		
		$success_msg='Success! Assignment '.$id.' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
			
		redirect('Writer_dashboard');	
	}		
	public function assignment_remove_file()
	{		
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_writer_dashboard->assignment_final_data($id);
		$getemage = explode(',',$getdatas[0]['writer_file']);
		unset($getemage[$ids]);
		$images = $this->Mdl_writer_dashboard->assignment_remove_file($id,implode(",", $getemage));
		$this->Mdl_writer_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment File Remove.';
		$this->session->set_userdata('success_msg',$success_msg);
		
	}		
	public function active_assignment()	
	{
		$user_data = $this->Mdl_writer_dashboard->active_assignment($_POST['id'],$_POST['status']);
		$data = $this->Mdl_writer_dashboard->manager_all_id_get($_POST['id']);
		$assignment = $this->Mdl_writer_dashboard->assignment($_POST['id']);
		
		if($_POST['status'] == 1)
		{
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_writer_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['created_id'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_writer_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['created_id'],"Manager");
			}
					
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['admin_id'],"Admin");
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Accepted',$data[0]['manager_id'],"Manager");
			$this->Mdl_writer_dashboard->logs_insert_c($_POST['id'],'Accepted',$_POST['reason']);
			
			$success_msg='Success! Assignment '.$_POST['id'].' is accepted.';
			$this->session->set_userdata('success_msg',$success_msg);
		}else if($_POST['status'] == 2){
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['created_id'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['created_id'],"Manager");
			}
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['admin_id'],"Admin");
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$data[0]['manager_id'],"Manager");
			$this->Mdl_writer_dashboard->logs_insert_c($_POST['id'],'Rejected',$_POST['reason']);
			
			$success_msg='Success! Assignment '.$_POST['id'].' is rejected.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		$this->Mdl_writer_dashboard->assignment_date_update($_POST['id']);
		redirect('Writer_dashboard');
	}
	public function reject_assignment()	
	{		
		print_r($_POST);
		$datetime1 = new DateTime(date("Y-m-d H:i:s"));
		$datetime2 = new DateTime($_POST['created_date']);
		$interval = $datetime1->diff($datetime2);
		$hours = $interval->h + ($interval->days*24);
		if($hours != '0')
		{			
			$data = $this->Mdl_writer_dashboard->manager_id_get($_POST['id']);
			$user_data = $this->Mdl_writer_dashboard->active_assignment($_POST['id'],2);
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$data[0]['manager_id'],"Manager");
			$this->Mdl_writer_dashboard->assignment_date_update($_POST['id']);
		}
		echo $hours;	
	}
	public function active_assignment_list()
	{
		if($_POST['status'] == 1)
		{
			$ids = explode(",",$_POST['accept_list_id']);
		}else if($_POST['status'] == 2){
			$ids = explode(",",$_POST['reject_list_id']);
		}
		$i = 0;
		foreach($ids as $id){
			$writer = null;
			$data = null;
			$assignment = null;
			
			$assignment = $this->Mdl_writer_dashboard->assignment($id);
			$writer = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
			if($assignment[0]['status'] == 1){
				if($writer[0]['status'] == 0)
				{
					$this->Mdl_writer_dashboard->assignment_date_update($id);
					$user_data = $this->Mdl_writer_dashboard->active_assignment($id,$_POST['status']);
					$data = $this->Mdl_writer_dashboard->manager_all_id_get($id);
					
					
					if($_POST['status'] == 1)
					{
						if($assignment[0]['created_role'] == 'help_desk'){
							$this->Mdl_writer_dashboard->notifications($id,'Accepted',$assignment[0]['created_id'],"Help Desk");
						}else if($assignment[0]['created_role'] == 'manager'){
							$this->Mdl_writer_dashboard->notifications($id,'Accepted',$assignment[0]['created_id'],"Manager");
						}
						$this->Mdl_writer_dashboard->notifications($id,'Accepted',$assignment[0]['admin_id'],"Admin");
						$this->Mdl_writer_dashboard->notifications($id,'Accepted',$data[0]['manager_id'],"Manager");
						$this->Mdl_writer_dashboard->logs_insert_c($id,'Accepted',$_POST['reason']);
					}else if($_POST['status'] == 2){
						if($assignment[0]['created_role'] == 'help_desk'){
							$this->Mdl_writer_dashboard->notifications($id,'Rejected',$assignment[0]['created_id'],"Help Desk");
						}else if($assignment[0]['created_role'] == 'manager'){
							$this->Mdl_writer_dashboard->notifications($id,'Rejected',$assignment[0]['created_id'],"Manager");
						}
						$this->Mdl_writer_dashboard->notifications($id,'Rejected',$assignment[0]['admin_id'],"Admin");
						$this->Mdl_writer_dashboard->notifications($id,'Rejected',$data[0]['manager_id'],"Manager");
						$this->Mdl_writer_dashboard->logs_insert_c($id,'Rejected',$_POST['reason']);
					}
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			if($_POST['status'] == 1)
			{
				$success_msg='Success! Assignment '.$i.' is accepted.';
				$this->session->set_userdata('success_msg',$success_msg);
			}else if($_POST['status'] == 2){
				$success_msg='Success! Assignment '.$i.' is rejected.';
				$this->session->set_userdata('success_msg',$success_msg);
			}
		}
		redirect('Writer_dashboard');
	}
	public function assign_to_ma_add_time()
	{		
		$user_data = $this->Mdl_writer_dashboard->managet_save_time($_POST['id']);
		if($user_data[0]['writer'] == null)
		{
			$times = ($user_data[0]['manager'] / 3);
			$this->Mdl_writer_dashboard->managet_save_time_update($_POST['id'],$times,($times *2));
			$this->Mdl_writer_dashboard->assign_to_writer_updste($_POST['id'],($times *2) + $_POST['assign_hours']);
		}else{
			$this->Mdl_writer_dashboard->assignment_remove($_POST['id']);
			$this->Mdl_writer_dashboard->active_assignment($_POST['id'],2);
			$this->Mdl_writer_dashboard->assignment_manager_remove($_POST['id'],2);
		}	
	}
	public function complete_assignment()
	{
		$user_data = $this->Mdl_writer_dashboard->active_assignment($_POST['id'],5);
		$this->Mdl_writer_dashboard->assignment_date_update($_POST['id']);
		$this->Mdl_writer_dashboard->logs_insert_c($_POST['id'],'Completed',$_POST['reason']);
		
		$data = $this->Mdl_writer_dashboard->manager_all_id_get($_POST['id']);
		$data_pr = $this->Mdl_writer_dashboard->pr_id_get($_POST['id']);
		
		$assignment = $this->Mdl_writer_dashboard->assignment($_POST['id']);
		$this->Mdl_writer_dashboard->notifications($_POST['id'],'Completed',$assignment[0]['admin_id'],"Admin");
		
		if($assignment[0]['created_role'] == 'help_desk'){
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Completed',$assignment[0]['created_id'],"Help Desk");
		}else if($assignment[0]['created_role'] == 'manager'){
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Completed',$assignment[0]['created_id'],"Manager");
		}
			
		if($data != null)
		{
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Completed',$data[0]['manager_id'],"Manager");
		}
		if($data_pr != null)
		{
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Completed',$data_pr[0]['proof_reader_id'],"Proof Reader");
		}
		$success_msg='Success! Assignment '.$_POST['id'].' is completed.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Writer_dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['complete_list_id']);
		$i = 0;
		foreach($ids as $id){
			$writer = null;
			$user_data = null;
			$data = null;
			$data_pr = null;
			$assignment = null;
			$assignment = $this->Mdl_writer_dashboard->assignment($id);
			
			$writer = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
			if($assignment[0]['status'] == 1)
			{
				if($writer[0]['status'] == 1)
				{
					$user_data = $this->Mdl_writer_dashboard->active_assignment($id,5);
					$this->Mdl_writer_dashboard->assignment_date_update($id);
					$this->Mdl_writer_dashboard->logs_insert_c($id,'Completed',$_POST['reason']);
					
					$data = $this->Mdl_writer_dashboard->manager_all_id_get($id);
					$data_pr = $this->Mdl_writer_dashboard->pr_id_get($id);
					
					if($assignment[0]['created_role'] == 'help_desk'){
						$this->Mdl_writer_dashboard->notifications($id,'Completed',$assignment[0]['created_id'],"Help Desk");
					}else if($assignment[0]['created_role'] == 'manager'){
						$this->Mdl_writer_dashboard->notifications($id,'Completed',$assignment[0]['created_id'],"Manager");
					}
					$this->Mdl_writer_dashboard->notifications($id,'Completed',$assignment[0]['admin_id'],"Admin");
					
					if($data != null)
					{
						$this->Mdl_writer_dashboard->notifications($id,'Completed',$data[0]['manager_id'],"Manager");
					}
					if($data_pr != null)
					{
						$this->Mdl_writer_dashboard->notifications($id,'Completed',$data_pr[0]['proof_reader_id'],"Proof Reader");
					}
					$i++;
				}
			}
			
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! Assignment '.$i.' is Completed.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Writer_dashboard');
	}	
}?>