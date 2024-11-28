<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_dashboard extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_manager_dashboard');
	}
	public function dashboards()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$role = 'manager';
		}
		$manager_dashboard['roles'] = $role;
		$manager_dashboard['users'] = $this->session->Admindetail['id'];
		$this->session->set_userdata("manager_dashboard",$manager_dashboard);
	}
	public function index()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			if($_POST['role'] != null)
			{
				$manager_dashboard['roles'] = $_POST['role'];
				$manager_dashboard['users'] = $_POST['users'];
				$this->session->set_userdata("manager_dashboard",$manager_dashboard);
			}
			if($this->session->manager_dashboard != null)
			{
				if($this->session->manager_dashboard['roles'] == 'admin')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assignment_admin_filter();
					$i = 0;
					foreach($assign_to_ma as $val) { 
						if($val['status'] != 4 && $val['status'] != 5 && $val['status'] != 6)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
					
				}else if($this->session->manager_dashboard['roles'] == 'manager')
				{
					$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all_filter();
					$assignment_datas = $this->Mdl_manager_dashboard->assignment_approval_filter();					
					$assign_to_ma_alls = array_merge($assign_to_ma_all, $assignment_datas);					
					$i = 0;
					foreach($assign_to_ma_alls as $val) {
						if($val['status'] == 7 || $val['assign_to_ma_status'] != 5 && $val['status'] != 4 && $val['assign_to_ma_status'] != 2)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'writer')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_wr_filter();
					$i = 0;
					foreach($assign_to_ma as $val) {
						if($val['assign_to_ma_status'] != 5 && $val['status'] != 4 && $val['assign_to_ma_status'] != 2)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'proof_reader')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_pr_filter();
					$i = 0;
					foreach($assign_to_ma as $val) {
						if($val['assign_to_ma_status'] != 5 && $val['status'] != 4 && $val['assign_to_ma_status'] != 2)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'help_desk')
				{
					$assignment_data = $this->Mdl_manager_dashboard->assign_to_he_filter();
				}
			}else{
				$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all();
				$assignment_datas = $this->Mdl_manager_dashboard->assignment_approval();
				
				$assign_to_ma_alls = array_merge($assign_to_ma_all, $assignment_datas);
				
				$i = 0;
				foreach($assign_to_ma_alls as $val) {
					if($val['assign_to_ma_status'] != 5 && $val['status'] != 4  && $val['assign_to_ma_status'] != 2)
					{
						if (!in_array($val['id'], $key_array)) {
							$key_array[$i] = $val['id'];
							$assignment_data[$i] = $val;
						}
						$i++;
					}
				}
			}
			
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'manager_dashboard',
				'left_sidebar'=>'Manager Dashboard',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function show_archived()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			if($_POST['role'] != null)
			{
				$manager_dashboard['roles'] = $_POST['role'];
				$manager_dashboard['users'] = $_POST['users'];
				$this->session->set_userdata("manager_dashboard",$manager_dashboard);
			}
			if($this->session->manager_dashboard != null)
			{
				if($this->session->manager_dashboard['roles'] == 'admin')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assignment_admin_filter();
					$i = 0;
					foreach($assign_to_ma as $val) { 
						if($val['status'] == 4 || $val['status'] == 5 || $val['status'] == 6)
						{ 
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
					
				}else if($this->session->manager_dashboard['roles'] == 'manager')
				{
					$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all_filter();
					$assignment_datas = $this->Mdl_manager_dashboard->assignment_approval_filter();					
					$assign_to_ma_alls = array_merge($assign_to_ma_all, $assignment_datas);					
					$i = 0;
					foreach($assign_to_ma_alls as $val) {
						if($val['status'] != 7 && $val['assign_to_ma_status'] == 5 || $val['status'] == 4 )
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'writer')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_wr_filter();
					$i = 0;
					foreach($assign_to_ma as $val) {
						if($val['assign_to_ma_status'] == 5 || $val['status'] == 4)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'proof_reader')
				{
					$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_pr_filter();
					$i = 0;
					foreach($assign_to_ma as $val) {
						if($val['assign_to_ma_status'] == 5 || $val['status'] == 4)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					}
				}else if($this->session->manager_dashboard['roles'] == 'help_desk')
				{
					$assignment_data = $this->Mdl_manager_dashboard->assign_to_he_show_filter();
				}
			}else{
				$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all();
				$assignment_datas = $this->Mdl_manager_dashboard->assignment_approval();				
				$assign_to_ma_alls = array_merge($assign_to_ma_all, $assignment_datas);				
				$i = 0;
				foreach($assign_to_ma_alls as $val) {
					if($val['assign_to_ma_status'] == 5 || $val['status'] == 4)
					{
						if (!in_array($val['id'], $key_array)) {
							$key_array[$i] = $val['id'];
							$assignment_data[$i] = $val;
						}
						$i++;
					}
				}
			}
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'show_archived',
				'left_sidebar'=>'Show Archived',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assign_taske($id)
	{
		$all_writers = $this->Mdl_manager_dashboard->all_writers();
		$all_proof_readers = $this->Mdl_manager_dashboard->all_proof_readers();
		
		
		$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
		
		$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
		$data = $assignment_data[0];
		
		$actions = '<form method="post" class="login-form" action="'.base_url().'index.php/Manager_dashboard/assign_to" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Assignment Assign To Writer And Proof Reader</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="assignment_id" id="assignment_id" value="'.$data['id'].'">';
					
					$assign_hours = (($manager_id[0]['assign_hours']/3)*2)/3;
					
					
				$actions .= '<input type="hidden" name="deadline_date" id="deadline_date" value="'.date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )).'">
					<input type="hidden" name="assign_hours_writer" id="assign_hours_writer" value="'.($assign_hours*2).'">
					<input type="hidden" name="assign_hours_proof_reader" id="assign_hours_proof_reader" value="'.$assign_hours.'">						
					<input type="hidden" name="save_hours" id="save_hours" value="'.($manager_id[0]['assign_hours']/3).'">
					
					<div class="row">
						<div class="col-xs-12">
							<select class="form-control" name="writer_id" id="writer_id" >
								<option value="">Assign One writer</option>';
								foreach($all_writers as $online){
									$actions .='<option value="'.$online['id'].'" >'.$online['name'].'</option>';
								}
						$actions .= '</select>
						</div>
					</div></br>
	
					<div class="row">
						<div class="col-xs-12">
								<select class="form-control" name="proof_reader_id" id="proof_reader_id" >
									<option value="">Assign One Proof Reader</option>';
									foreach($all_proof_readers as $all_manager){
									$actions .='<option value="'.$all_manager['id'].'">'.$all_manager['name'].'</option>';
									}
								$actions .='</select>
						</div>
					</div></br>
					

					<div class="row">
						<div class="col-xs-12">
							<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Assign</button>
				</div>
			</form>';
		echo $actions;
	}
	
	public function re_assign_taske($id)
	{
		$all_writers = $this->Mdl_manager_dashboard->all_writers();
		$all_proof_readers = $this->Mdl_manager_dashboard->all_proof_readers();
		
		$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
		$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
		$data = $assignment_data[0];
		$data['assign_hours'] = $manager_id[0]['assign_hours'];
		
		$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
		$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);
			?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/re_assign_to" enctype="multipart/form-data">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Re-Assignment Assign To Writer And Proof Reader</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" name="assignment_id" id="assignment_id" value="<?php echo $data['id']; ?>">
				<?php  $assign_hours = (($data['assign_hours']/3)*2)/3; ?>	
				
				<input type="hidden" name="deadline_date" id="deadline_date" value="<?php echo date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )); ?>">
				
				<input type="hidden" name="assign_hours_writer" id="assign_hours_writer" value="<?php echo $assign_hours*2; ?>">
				<input type="hidden" name="assign_hours_proof_reader" id="assign_hours_proof_reader" value="<?php echo $assign_hours; ?>">						
				<input type="hidden" name="save_hours" id="save_hours" value="<?php echo $manager_id[0]['assign_hours']/3; ?>">
					
				
				<div class="row">
					<div class="col-xs-8">
						<select class="form-control" name="writer_id" id="writer_id" >
							<option value="">Assign One writer</option>';
							<?php foreach($all_writers as $online){
								echo '<option value="'.$online['id'].'"';
								if($online['id'] == $writer_id[0]['writer_id'])
								{
									echo 'selected';
								}
								echo '>'.$online['name'].'</option>';
							} ?>
						</select>
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-primary" onclick="remove_re_assign(2)" >Remove</button>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-8">
							<select class="form-control" name="proof_reader_id" id="proof_reader_id" >
								<option value="">Assign One Proof Reader</option>';
								<?php foreach($all_proof_readers as $all_manager){
									echo '<option value="'.$all_manager['id'].'"';
									if($all_manager['id'] == $proof_reader_id[0]['proof_reader_id'])
									{
										echo 'selected';
									}
									echo '>'.$all_manager['name'].'</option>';
								} ?>
							</select>
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-primary" onclick="remove_re_assign(3)" >Remove</button>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-8">
						<?php $qc_failed = $this->Mdl_manager_dashboard->dropdowns('dropdowns_qc_failed'); ?>
						<select class="form-control" name="reasons" id="reasons" required >
							<option value="">Choose one reason</option>
							<?php foreach($qc_failed as $qc_faileda){ ?>
								<option value="<?php echo $qc_faileda['name'];?>"><?php echo $qc_faileda['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-12">
						<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" required ></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Re-Assign</button>
			</div>
		</form>
		<?php				
	}
	public function accept_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment" enctype="multipart/form-data">
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
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment" enctype="multipart/form-data">
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
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/complete_assignment" enctype="multipart/form-data">
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
						<input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
						<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50"></textarea>
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
	public function duplicate_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> </a>
					Are you sure you want to clone the assignment ?
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
		if($this->session->Admindetail['manager'] == 1)
		{
			$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_data',
				'left_sidebar'=>'Manager Dashboard',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_view($id)
	{
		if($this->session->Admindetail['manager'] == 1)
		{			
			$this->Mdl_manager_dashboard->assignment_notifications($id);
			$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
			$assignment_final_data = $this->Mdl_manager_dashboard->assignment_final_data($id);
			
			$data=array(				
				'datas'=>$assignment_data,
				'assignment_final_data'=>$assignment_final_data,	
				'main_content'=>'assignment_view',		
				'left_sidebar'=>'Manager Dashboard',
			);	
			$this->load->view('manager_template/template',$data);
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
		if (!is_dir('uploads/Assignment/'.$id.'/manager')) {
			mkdir('./uploads/Assignment/'.$id.'/manager', 0777, TRUE);		
		}
		$count = count($_FILES['file']['name']);	
		$count--;	
		$assignment_final_data = $this->Mdl_manager_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['manager_file']);
		for($i = 0; $i <= $count; $i++)	
		{		
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];	
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];		
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];	
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];	
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];	
			
			$ext=$this->Mdl_manager_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if(preg_match('/\s/',$_FILES['image']['name']) != 0){
				$error_msg='in Assigned File space is not Require.';
				$this->session->set_userdata('error_msg',$error_msg);
				redirect('Manager_dashboard/assignment_view/'.$id);
			}	
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg' || $ext == 'txt')	
				{
					$filename[$i]='';
					$ida=uniqid();		
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Assignment/'.$id.'/manager/';	
					$config['allowed_types'] = '*';	 	
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');    		
					$this->upload->initialize($config);		
					if($this->upload->do_upload('image')) 	
					{				
						$config['image_library'] = 'gd2';	
						$config['source_image']  = './uploads/Assignment/'.$id.'/manager/'.$filename[$i];	
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
		
		if($assignment_final_data == null)
		{
			$filename = array_splice($filename,0,5,true);
			$this->Mdl_manager_dashboard->assignment_image_add($id,implode(",",$filename));
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
			$this->Mdl_manager_dashboard->assignment_image_update($id,$data);
		}
		
		if($_POST['writer_id'] != null)
		{
			//$writer_id = $this->Mdl_manager_dashboard->writer_id($_POST['assignment_id']);
			//if($writer_id[0]['writer_id'] != $_POST['writer_id'])
			//{
				//$this->Mdl_manager_dashboard->re_assign_assignment_w($_POST['assignment_id'],2);
			
				$assign_hours_writer = ((($_POST['assign_hours']/3)*2)/3)*2;
				$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_writer);
				
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['writer_id'],"Writer");	
				$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reason'],"Re-Assigned","Writer");
				
			//}
		}/*else{
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
		}*/
		
		if($_POST['proof_reader_id'] != null)
		{
			//$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($_POST['assignment_id']); 
			//if($proof_reader_id[0]['proof_reader_id'] != $_POST['proof_reader_id'])
			//{
				//$this->Mdl_manager_dashboard->re_assign_assignment_p($_POST['assignment_id'],2);
			
				$assign_hours_proof_reader = (($_POST['assign_hours']/3)*2)/3;	
				$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_proof_reader);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['proof_reader_id'],"Proof Reader");
				$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reason'],"Re-Assigned","Proof Reader");
				
			//}
		}/*else{
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
		}*/
		 
		
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Success! Assignment '.$_POST['assignment_id'].' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		redirect('Manager_dashboard');
	}
	public function assignment_remove_file()	
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_manager_dashboard->assignment_final_data($id);
		$getemage = explode(',',$getdatas[0]['manager_file']);
		unset($getemage[$ids]);
		$images = $this->Mdl_manager_dashboard->assignment_remove_file($id,implode(",", $getemage));
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Success! File is removed.';
		$this->session->set_userdata('success_msg',$success_msg);
	}
	public function assign_to()
	{
		$i = 0;
		if($_POST['writer_id'] != null)
		{
			$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_writer']);
			$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Assigned',$_POST['writer_id'],"Writer");			
			$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reason'],"Assigned","Writer");
			$i++;
		}
		if($_POST['proof_reader_id'] != null)
		{	
			$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_proof_reader']);
			$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Assigned',$_POST['proof_reader_id'],"Proof Reader");
			$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reason'],"Assigned","Proof Reader");
			$i++;
		}
		$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
		$this->Mdl_manager_dashboard->assignment_manager_status($_POST['assignment_id'],1);
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! Assignment '.$_POST['assignment_id'].' is assigned.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
	public function re_assign_to()
	{
		$assignments = $this->Mdl_manager_dashboard->assignments($_POST['assignment_id']);
		if($assignments[0]['status'] == 7)
		{
			$this->Mdl_manager_dashboard->assignment_status_active($_POST['assignment_id'],1);
			$this->Mdl_manager_dashboard->active_assignment($_POST['assignment_id'],1);
			$this->Mdl_manager_dashboard->assignment_admin_status($_POST['assignment_id'],2);
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
		}
		if($_POST['writer_id'] != null)
		{
			$writer_id = $this->Mdl_manager_dashboard->writer_id($_POST['assignment_id']);
			if($writer_id[0]['writer_id'] != $_POST['writer_id'])
			{
				$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
				
				$this->Mdl_manager_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Writer',$_POST['writer_id']);
				
				$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_writer']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['writer_id'],"Writer");		
				$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
			}else{
				if($writer_id[0]['status'] == 2 || $writer_id[0]['status'] == 5)
				{
					$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
					$this->Mdl_manager_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Writer',$_POST['writer_id']);
					$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_writer']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['writer_id'],"Writer");		
					$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
				}
			}
		}else{
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
		}
		
		if($_POST['proof_reader_id'] != null)
		{
			$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($_POST['assignment_id']);
			if($proof_reader_id[0]['proof_reader_id'] != $_POST['proof_reader_id'])
			{
				$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
				$this->Mdl_manager_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Proof Reader',$_POST['proof_reader_id']);
				$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_proof_reader']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['proof_reader_id'],"Proof Reader");		
				$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
			}else{
				if($proof_reader_id[0]['status'] == 2 || $proof_reader_id[0]['status'] == 5 )
				{
					$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
					$this->Mdl_manager_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Proof Reader',$_POST['proof_reader_id']);
					$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$_POST['assign_hours_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],'Re-Assigned',$_POST['proof_reader_id'],"Proof Reader");		
					$this->Mdl_manager_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
				}
			}
		}else{
			$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
		}
		$this->Mdl_manager_dashboard->assignment_manager_status($_POST['assignment_id'],2);
		
		$writer_ids = $this->Mdl_manager_dashboard->writer_id($_POST['assignment_id']);
		$proof_reader_ids = $this->Mdl_manager_dashboard->proof_reader_id($_POST['assignment_id']);
		if($writer_ids == null && $proof_reader_ids == null)
		{
			$this->Mdl_manager_dashboard->assignment_manager_status($_POST['assignment_id'],null);
		}
		$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
		
		$success_msg='Success! Assignment '.$_POST['assignment_id'].' is Re-assigned.';
		$this->session->set_userdata('success_msg',$success_msg);
		redirect('Manager_dashboard');
	}
	public function assign_to_list()
	{
		$ids = explode(",",$_POST['assignment_list_id']);
		$i = 0;
		foreach($ids as $id)
		{
			$proof_reader_id = null;
			$writer_id = null;
			$manager_id = null;
			$assignment_data = null;
			$deadline_date = null;
			
			$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
			$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($id);
			$writer_id = $this->Mdl_manager_dashboard->writer_id($id);
			
			$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
			if($manager_id[0]['status'] == 1)
			{
				if($writer_id == null && $proof_reader_id == null)
				{
					$deadline_date = date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] ));
					
					$assign_hours = (($manager_id[0]['assign_hours']/3)*2)/3;
					$assign_hours_writer = $assign_hours*2;
					
					if($_POST['writer_id'] != null)
					{
						$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
						$this->Mdl_manager_dashboard->notifications_all($id,'Assigned',$_POST['writer_id'],"Writer");			
						$this->Mdl_manager_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reason'],"Assigned","Writer");
					}
					if($_POST['proof_reader_id'] != null)
					{	
						$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours);
						$this->Mdl_manager_dashboard->notifications_all($id,'Assigned',$_POST['proof_reader_id'],"Proof Reader");
						$this->Mdl_manager_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reason'],"Assigned","Proof Reader");
					}
					$this->Mdl_manager_dashboard->assignment_date_update($id);
					$this->Mdl_manager_dashboard->assignment_manager_status($id,1);
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Assignment Assigned '.$i.' is Writer And Proof Reader.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
	public function re_assign_to_list()
	{
		$ids = explode(",",$_POST['re_assignment_list_id']);
		foreach($ids as $id)
		{
			$proof_reader_id = null;
			$writer_id = null;
			$manager_id = null;
			$assignment_data = null;
			
			$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
			$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($id);
			$writer_id = $this->Mdl_manager_dashboard->writer_id($id);
			
			$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
			if($assignment_data[0]['status'] == 7 || $manager_id[0]['status'] == 1)
			{
				if($writer_id != null || $proof_reader_id != null)
				{
					if($assignment_data[0]['status'] == 7)
					{
						$this->Mdl_manager_dashboard->assignment_status_active($id,1);
						$this->Mdl_manager_dashboard->active_assignment($id,1);
						$this->Mdl_manager_dashboard->assignment_admin_status($id,2);
						$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($id);
						$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($id);
					}
					
					$deadline_date = date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] ));
					$assign_hours = (($manager_id[0]['assign_hours']/3)*2)/3;
					$assign_hours_writer = $assign_hours*2;
					
					
					if($_POST['writer_id'] != null)
					{
						$writer_id = $this->Mdl_manager_dashboard->writer_id($id);
						if($writer_id[0]['writer_id'] != $_POST['writer_id'])
						{
							$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($id);
							
							$this->Mdl_manager_dashboard->assignment_re_assign_file($id,'Writer',$_POST['writer_id']);
							
							$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
							$this->Mdl_manager_dashboard->notifications_all($id,'Re-Assigned',$_POST['writer_id'],"Writer");		
							$this->Mdl_manager_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
						}else{
							if($writer_id[0]['status'] == 2 || $writer_id[0]['status'] == 5)
							{
								$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($id);
								$this->Mdl_manager_dashboard->assignment_re_assign_file($id,'Writer',$_POST['writer_id']);
								$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
								$this->Mdl_manager_dashboard->notifications_all($id,'Re-Assigned',$_POST['writer_id'],"Writer");		
								$this->Mdl_manager_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
							}
						}
					}else{
						$this->Mdl_manager_dashboard->re_assign_assignment_remove_w($id);
					}
					
					if($_POST['proof_reader_id'] != null)
					{
						$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($id);
						if($proof_reader_id[0]['proof_reader_id'] != $_POST['proof_reader_id'])
						{
							$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($id);
							$this->Mdl_manager_dashboard->assignment_re_assign_file($id,'Proof Reader',$_POST['proof_reader_id']);
							$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours);
							$this->Mdl_manager_dashboard->notifications_all($id,'Re-Assigned',$_POST['proof_reader_id'],"Proof Reader");		
							$this->Mdl_manager_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
						}else{
							if($proof_reader_id[0]['status'] == 2 || $proof_reader_id[0]['status'] == 5 )
							{
								$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($id);
								$this->Mdl_manager_dashboard->assignment_re_assign_file($id,'Proof Reader',$_POST['proof_reader_id']);
								$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,'Re-Assigned',$_POST['proof_reader_id'],"Proof Reader");		
								$this->Mdl_manager_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
							}
						}
					}else{
						$this->Mdl_manager_dashboard->re_assign_assignment_remove_p($id);
					}
					$this->Mdl_manager_dashboard->assignment_manager_status($id,2);
					
					$writer_ids = $this->Mdl_manager_dashboard->writer_id($id);
					$proof_reader_ids = $this->Mdl_manager_dashboard->proof_reader_id($id);
					if($writer_ids == null && $proof_reader_ids == null)
					{
						$this->Mdl_manager_dashboard->assignment_manager_status($id,null);
					}
					$this->Mdl_manager_dashboard->assignment_date_update($id);
		
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Assignment Re-Assigned '.$i.' is  Writer And Proof Reader.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
	public function re_assign_assignment($id)	
	{
		$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($id);
		$this->Mdl_manager_dashboard->re_assign_assignment($id,6);
		$this->Mdl_manager_dashboard->notifications($id,'Re-Assigned',$proof_reader_id[0]['proof_reader_id'],"Proof Reader");
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment Re-Assigned to Proof Reader.';
		$this->session->set_userdata('success_msg',$success_msg);	
		redirect('Manager_dashboard');
	}
	public function active_assignment()
	{
		$user_data = $this->Mdl_manager_dashboard->active_assignment($_POST['id'],$_POST['status']);
		$assignment = $this->Mdl_manager_dashboard->assignment($_POST['id']);
		if($_POST['status'] == 1)
		{
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_manager_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['created_id'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_manager_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['created_id'],"Manager");
			}
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Accepted',$assignment[0]['admin_id'],"Admin");
			$this->Mdl_manager_dashboard->logs_insert_c($_POST['id'],'Accepted',$_POST['reason']);
			
			$success_msg='Success! Assignment '.$_POST['id'].' is accepted.';
			$this->session->set_userdata('success_msg',$success_msg);
			
		}else if($_POST['status'] == 2){
			if($assignment[0]['created_role'] == 'help_desk'){
				$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['created_id'],"Help Desk");
			}else if($assignment[0]['created_role'] == 'manager'){
				$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['created_id'],"Manager");
			}
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$assignment[0]['admin_id'],"Admin");
			$this->Mdl_manager_dashboard->logs_insert_c($_POST['id'],'Rejected',$_POST['reason']);
			
			$success_msg='Success! Assignment '.$_POST['id'].' is rejected.';
			$this->session->set_userdata('success_msg',$success_msg);
		
		}
		$this->Mdl_manager_dashboard->assignment_date_update($_POST['id']);
		redirect('Manager_dashboard');
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
			$manager_id = null;
			$user_data = null;
			$assignment = null;
			$assignment = $this->Mdl_manager_dashboard->assignment($id);
			$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
			
			if($assignment[0]['status'] == 1)
			{
				if($manager_id != null)
				{
					if($manager_id[0]['status'] == 0)
					{
						$user_data = $this->Mdl_manager_dashboard->active_assignment($id,$_POST['status']);
						if($_POST['status'] == 1)
						{
							if($assignment[0]['created_role'] == 'help_desk'){
								$this->Mdl_manager_dashboard->notifications($id,'Accepted',$assignment[0]['created_id'],"Help Desk");
							}else if($assignment[0]['created_role'] == 'manager'){
								$this->Mdl_manager_dashboard->notifications($id,'Accepted',$assignment[0]['created_id'],"Manager");
							}
							$this->Mdl_manager_dashboard->notifications($id,'Accepted',$assignment[0]['admin_id'],"Admin");
							$this->Mdl_manager_dashboard->logs_insert_c($id,'Accepted',$_POST['reason']);
						}else if($_POST['status'] == 2){
							if($assignment[0]['created_role'] == 'help_desk'){
								$this->Mdl_manager_dashboard->notifications($id,'Rejected',$assignment[0]['created_id'],"Help Desk");
							}else if($assignment[0]['created_role'] == 'manager'){
								$this->Mdl_manager_dashboard->notifications($id,'Rejected',$assignment[0]['created_id'],"Manager");
							}
							$this->Mdl_manager_dashboard->notifications($id,'Rejected',$assignment[0]['admin_id'],"Admin");
							$this->Mdl_manager_dashboard->logs_insert_c($id,'Rejected',$_POST['reason']);
						}
						$this->Mdl_manager_dashboard->assignment_date_update($id);
						$i++;
					}
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
		redirect('Manager_dashboard');
	}
	public function reject_assignment()
	{
		$datetime1 = new DateTime(date("Y-m-d H:i:s"));
		$datetime2 = new DateTime($_POST['created_date']);
		$interval = $datetime1->diff($datetime2);
		$hours = $interval->h + ($interval->days*24);
		
		if($hours != '0')
		{
			$user_data = $this->Mdl_manager_dashboard->active_assignment($_POST['id'],2);
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$_POST['admin_id'],"Admin");
			$this->Mdl_manager_dashboard->assignment_date_update($_POST['id']);
		}
		echo $hours;
	}
	public function complete_assignment()
	{
		$assignment = $this->Mdl_manager_dashboard->assignment($_POST['ids']);
		$user_data = $this->Mdl_manager_dashboard->active_assignment($_POST['ids'],5);
		
		if($assignment[0]['created_role'] == 'help_desk'){
			$this->Mdl_manager_dashboard->notifications($_POST['ids'],'Completed',$assignment[0]['created_id'],"Help Desk");
		}else if($assignment[0]['created_role'] == 'manager'){
			$this->Mdl_manager_dashboard->notifications($_POST['ids'],'Completed',$assignment[0]['created_id'],"Manager");
		}
			
		$this->Mdl_manager_dashboard->notifications($_POST['ids'],'Completed',$assignment[0]['admin_id'],"Admin");
		
		$this->Mdl_manager_dashboard->assignment_date_update($_POST['ids']);
		$this->Mdl_manager_dashboard->logs_insert_c($_POST['ids'],'Completed',$_POST['reason']);
		
		$success_msg='Success! Assignment '.$_POST['ids'].' is complete.';
		$this->session->set_userdata('success_msg',$success_msg);
			
		redirect('Manager_dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['complete_list_id']);
		$i = 0;
		foreach($ids as $id){
			$manager_id = null;
			$user_data = null;
			$assignment = null;
			
			$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
			if($manager_id != null){
				if($manager_id[0]['status'] == 1)
				{
					if($assignment[0]['created_role'] == 'help_desk'){
						$this->Mdl_manager_dashboard->notifications($id,'Completed',$assignment[0]['created_id'],"Help Desk");
					}else if($assignment[0]['created_role'] == 'manager'){
						$this->Mdl_manager_dashboard->notifications($id,'Completed',$assignment[0]['created_id'],"Manager");
					}
					$assignment = $this->Mdl_manager_dashboard->assignment($id);
					$user_data = $this->Mdl_manager_dashboard->active_assignment($id,5);
					
					$this->Mdl_manager_dashboard->notifications($id,'Completed',$assignment[0]['admin_id'],"Admin");
					
					$this->Mdl_manager_dashboard->assignment_date_update($id);
					$this->Mdl_manager_dashboard->logs_insert_c($id,'Completed',$_POST['reason']);
					
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
		redirect('Manager_dashboard');
	}
	public function clone_assignment_list()
	{
		$ids = explode(",",$_POST['clone_list_id']);
		$i = 0;
		foreach($ids as $id){
			if($id != null)
			{
				$datas = array();
				$datas = $this->Mdl_manager_dashboard->assignment($id);
				unset($datas[0]['id']);
				unset($datas[0]['created_id']);
				unset($datas[0]['created_role']);
				unset($datas[0]['admin_status']);
				$assignment_id = $this->Mdl_manager_dashboard->duplicate_assignment($datas[0]);
				$this->Mdl_manager_dashboard->logs_insert_c($assignment_id,'Clone',$_POST['reason']);
				$i++;
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! Assignment '.$i.' is clone.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
}
?>