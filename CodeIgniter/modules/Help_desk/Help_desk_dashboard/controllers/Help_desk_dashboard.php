<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_desk_dashboard extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_help_desk_dashboard');
	}
	public function dashboards()
	{
		if($this->session->Admindetail['help_desk'] == 1)
		{
			$role = 'help_desk';
		}
		$admin_dashboard['roles'] = $role;
		$admin_dashboard['users'] = $this->session->Admindetail['id'];
		$this->session->set_userdata("help_desk_dashboard",$admin_dashboard);
	}
	public function index()
	{
		if($this->session->Admindetail['help_desk'] == 1)
		{
			if($_POST['role'] != null)
			{
				$admin_dashboard['roles'] = $_POST['role'];
				$admin_dashboard['users'] = $_POST['users'];
				$this->session->set_userdata("help_desk_dashboard",$admin_dashboard);
			}	
			if($this->session->help_desk_dashboard != null)
			{
				if($this->session->help_desk_dashboard['roles'] == 'admin')
				{
					$assignment_data = $this->Mdl_help_desk_dashboard->assignment_admin_filter();
				}else if($this->session->help_desk_dashboard['roles'] == 'manager')
				{
					$assign_to_ma_all = $this->Mdl_help_desk_dashboard->assign_to_ma_all_filter();
					$assignment_datas = $this->Mdl_help_desk_dashboard->assignment_approval_filter();					
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
				}else if($this->session->help_desk_dashboard['roles'] == 'writer')
				{
					$assign_to_ma = $this->Mdl_help_desk_dashboard->assign_to_wr_filter();
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
				}else if($this->session->help_desk_dashboard['roles'] == 'proof_reader')
				{
					$assign_to_ma = $this->Mdl_help_desk_dashboard->assign_to_pr_filter();
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
				}else if($this->session->help_desk_dashboard['roles'] == 'help_desk')
				{
					$assignment_data = $this->Mdl_help_desk_dashboard->assign_to_he_filter();
				}
			}else{
				$assignment_data = $this->Mdl_help_desk_dashboard->assignment();
			}
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'help_desk_dashboard',
				'left_sidebar'=>'Help Desk Dashboard',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function show_archived()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			if($_POST['role'] != null)
			{
				$admin_dashboard['roles'] = $_POST['role'];
				$admin_dashboard['users'] = $_POST['users'];
				$this->session->set_userdata("help_desk_dashboard",$admin_dashboard);
			}
			
			if($this->session->help_desk_dashboard != null)
			{
				if($this->session->help_desk_dashboard['roles'] == 'admin')
				{
					$assign_to_ma = $this->Mdl_help_desk_dashboard->assignment_admin_filter();
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
				}else if($this->session->help_desk_dashboard['roles'] == 'manager')
				{
					$assign_to_ma_all = $this->Mdl_help_desk_dashboard->assign_to_ma_all_filter();
					$assignment_datas = $this->Mdl_help_desk_dashboard->assignment_approval_filter();					
					$assign_to_ma_alls = array_merge($assign_to_ma_all, $assignment_datas);					
					$i = 0;
					foreach($assign_to_ma_alls as $val) {
						if($val['status'] != 7 && $val['assign_to_ma_status'] == 5 || $val['status'] == 4)
						{
							if (!in_array($val['id'], $key_array)) {
								$key_array[$i] = $val['id'];
								$assignment_data[$i] = $val;
							}
							$i++;
						}
					} 
				}else if($this->session->help_desk_dashboard['roles'] == 'writer')
				{
					$assign_to_ma = $this->Mdl_help_desk_dashboard->assign_to_wr_filter();
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
				}else if($this->session->help_desk_dashboard['roles'] == 'proof_reader')
				{
					$assign_to_ma = $this->Mdl_help_desk_dashboard->assign_to_pr_filter();
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
				}else if($this->session->help_desk_dashboard['roles'] == 'help_desk')
				{
					$assignment_data = $this->Mdl_help_desk_dashboard->assign_to_he_show_filter();
				}
			}else{
				$assignment_data = $this->Mdl_help_desk_dashboard->assign_to_he_show_filter();
			}
			
			
			
			$data=array(
				'datas'=>$assignment_data,
				'main_content'=>'show_archived',
				'left_sidebar'=>'Show Archived',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function help_desk_assignment_add()
	{
		if($this->session->Admindetail['help_desk'] == 1)
		{
			$data=array(
				'main_content'=>'help_desk_assignment_add',
				'left_sidebar'=>'Help Desk Assignment Add',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function edit_assignment($id)
	{
		if($this->session->Admindetail['help_desk'] == 1)
		{
			$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_edit',
				'left_sidebar'=>'Help Desk Dashboard',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function cancel_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Help_desk_dashboard/cancel_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i></a>
					Are you sure you want to Cancel ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
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
	public function duplicate_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Help_desk_dashboard/help_desk_assignment_add" enctype="multipart/form-data">
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
	public function complete_taske($id)
	{
		?>
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Help_desk_dashboard/complete_assignment" enctype="multipart/form-data">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">
					<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> </a>
					Are you sure you want to Complete ?
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
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
	public function complete_assignment()
	{
		$manager_id = $this->Mdl_help_desk_dashboard->manager_id($_POST['ids']);
		$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($_POST['ids']);
		$writer_id = $this->Mdl_help_desk_dashboard->writer_id($_POST['ids']);
		
		if($manager_id != null)
		{
			$this->Mdl_help_desk_dashboard->manager_complet($manager_id[0]['id'],5);
			$this->Mdl_help_desk_dashboard->notifications($manager_id[0]['manager_id'],'Completed',$_POST['ids'],"Manager");
		}
		if($proof_reader_id != null)
		{
			$this->Mdl_help_desk_dashboard->proof_reader_complet($proof_reader_id[0]['id'],5);
			$this->Mdl_help_desk_dashboard->notifications($proof_reader_id[0]['proof_reader_id'],'Completed',$_POST['ids'],"Proof Reader");
		}
		if($writer_id != null)
		{
			$this->Mdl_help_desk_dashboard->writer_complet($writer_id[0]['id'],5);
			$this->Mdl_help_desk_dashboard->notifications($writer_id[0]['writer_id'],'Completed',$_POST['ids'],"Writer");
		}
		$this->Mdl_help_desk_dashboard->logs_insert_c($_POST['ids'],"Completed",$_POST['reason']);
		
		$user_data = $this->Mdl_help_desk_dashboard->active_assignment($_POST['ids'],5);
		$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['ids']);
		$success_msg='Success! Assignment '.$_POST['ids'].' is Completed.';
		$this->session->set_userdata('success_msg',$success_msg);
		redirect('Help_desk_dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['complete_list_id']);
		$i = 0;
		foreach($ids as $id){
			$manager_id = null;
			$proof_reader_id = null;
			$writer_id = null;
			$assignment_data = null;
			$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
			if($assignment_data[0]['status'] == 1)
			{
				$manager_id = $this->Mdl_help_desk_dashboard->manager_id($id);
				$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($id);
				$writer_id = $this->Mdl_help_desk_dashboard->writer_id($id);
				if($manager_id != null)
				{
					$this->Mdl_help_desk_dashboard->manager_complet($manager_id[0]['id'],5);
					$this->Mdl_help_desk_dashboard->notifications($manager_id[0]['manager_id'],'Completed',$id,"Manager");
				}
				if($proof_reader_id != null)
				{
					$this->Mdl_help_desk_dashboard->proof_reader_complet($proof_reader_id[0]['id'],5);
					$this->Mdl_help_desk_dashboard->notifications($proof_reader_id[0]['proof_reader_id'],'Completed',$id,"Proof Reader");
				}
				if($writer_id != null)
				{
					$this->Mdl_help_desk_dashboard->writer_complet($writer_id[0]['id'],5);
					$this->Mdl_help_desk_dashboard->notifications($writer_id[0]['writer_id'],'Completed',$id,"Writer");
				}
				$this->Mdl_help_desk_dashboard->logs_insert_c($id,"Completed",$_POST['reason']);
				$user_data = $this->Mdl_help_desk_dashboard->active_assignment($id,5);
				$this->Mdl_help_desk_dashboard->assignment_date_update($id);
				$i++;
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
		
		redirect('Help_desk_dashboard');
	}
	public function assign_taske($id)
	{
		$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
		$data = $assignment_data[0];
		
		$all_managers = $this->Mdl_help_desk_dashboard->all_managers();
		$all_writers = $this->Mdl_help_desk_dashboard->all_writers();
		$all_proof_readers = $this->Mdl_help_desk_dashboard->all_proof_readers();
		
		
			$actions = '<form method="post" class="login-form" action="'.base_url().'index.php/Help_desk_dashboard/assign_to" enctype="multipart/form-data">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Assignment Assign To Manager</h4>
							</div>
							<div class="modal-body">
								<input type="hidden" name="assignment_id" id="assignment_id" value="'.$data['id'].'">';
				
					$datetime1 = new DateTime($data['created_date']);
					$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )));
					$interval = $datetime1->diff($datetime2);
					$hours = $interval->h + ($interval->days*24);
					$minit = $interval->i + ($hours*60);
				$actions .= '<input type="hidden" name="deadline_date" id="deadline_date" value="'.date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )).'">
				<input type="hidden" name="assign_hours" id="assign_hours" value="'.(($minit / 10)*9 / 60).'">
				
				<input type="hidden" name="save_hours" id="save_hours" value="'.(($minit / 10) / 60).'">
				
								<div class="row">
									<div class="col-xs-12">
										<select class="form-control" name="manager_id" id="manager_id" >
											<option value="">Assign One manager</option>';
											foreach($all_managers as $all_manager){
											$actions .='<option value="'.$all_manager['id'].'">'.$all_manager['name'].'</option>';
											}
										$actions .='</select>
									</div>
									
								</div></br>
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
										<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50"></textarea>
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
		$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
		$data = $assignment_data[0];
		
		$all_managers = $this->Mdl_help_desk_dashboard->all_managers();
		$all_writers = $this->Mdl_help_desk_dashboard->all_writers();
		$all_proof_readers = $this->Mdl_help_desk_dashboard->all_proof_readers();
		
		
		$manager_id = $this->Mdl_help_desk_dashboard->manager_id($data['id']);
		$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($data['id']);
		$writer_id = $this->Mdl_help_desk_dashboard->writer_id($data['id']);
					
					
		$actions =	'<form method="post" class="login-form" action="'.base_url().'index.php/Help_desk_dashboard/re_assign_to" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Assignment Re-Assign To Manager</h4>
						</div> 
						<div class="modal-body">
							<input type="hidden" name="assignment_id" id="assignment_id" value="'.$data['id'].'">';
			
				$datetime1 = new DateTime($data['created_date']);
				$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )));
				$interval = $datetime1->diff($datetime2);
				$hours = $interval->h + ($interval->days*24);
				$minit = $interval->i + ($hours*60);
		
		$actions .= '<input type="hidden" name="deadline_date" id="deadline_date" value="'.date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )).'">
			<input type="hidden" name="assign_hours" id="assign_hours" value="'.(($minit / 10)*9 / 60).'">
			
			<input type="hidden" name="save_hours" id="save_hours" value="'.(($minit / 10) / 60).'">
			
							<div class="row">
								<div class="col-xs-8">
									<select class="form-control" name="manager_id" id="manager_id" >
										<option value="">Assign One manager</option>';
										foreach($all_managers as $all_manager){
											$actions .='<option value="'.$all_manager['id'].'"';
											if($all_manager['id'] == $manager_id[0]['manager_id'])
											{
												$actions .='selected';
											}
											$actions .='>'.$all_manager['name'].'</option>';
										}
									$actions .='</select>
								</div>
								<div class="col-xs-4">
									<button type="button" class="btn btn-primary" onclick="remove_re_assign(1)" >Remove</button>
								</div>
							</div></br>
							<div class="row">
								<div class="col-xs-8">
									<select class="form-control" name="writer_id" id="writer_id" >
										<option value="">Assign One writer</option>';
										foreach($all_writers as $online){
											$actions .='<option value="'.$online['id'].'"';
											if($online['id'] == $writer_id[0]['writer_id'])
											{
												$actions .='selected';
											}
											$actions .='>'.$online['name'].'</option>';
										}
								$actions .= '</select>
								</div>
								<div class="col-xs-4">
									<button type="button" class="btn btn-primary" onclick="remove_re_assign(2)" >Remove</button>
								</div>
							</div></br>
			
							<div class="row">
								<div class="col-xs-8">
										<select class="form-control" name="proof_reader_id" id="proof_reader_id" >
											<option value="">Assign One Proof Reader</option>';
											foreach($all_proof_readers as $all_manager){
												$actions .='<option value="'.$all_manager['id'].'"';
												if($all_manager['id'] == $proof_reader_id[0]['proof_reader_id'])
												{
													$actions .='selected';
												}
												$actions .='>'.$all_manager['name'].'</option>';
											}
										$actions .='</select>
								</div>
								<div class="col-xs-4">
									<button type="button" class="btn btn-primary" onclick="remove_re_assign(3)" >Remove</button>
								</div>
							</div></br>
			
							<div class="row">
								<div class="col-xs-8">
									<select class="form-control" name="reasons" id="reasons" required >
										<option value="">Choose one reason</option>';
										$qc_failed = $this->Mdl_help_desk_dashboard->dropdowns('dropdowns_qc_failed');
										foreach($qc_failed as $qc_faile){
											$actions .='<option value="'.$qc_faile['name'].'">'.$qc_faile['name'].'</option>';
										}
									$actions .='</select>
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
					</form>';
		echo $actions;
	}
	public function assign_to()
	{
		$i = 0;
		if($_POST['manager_id'] != null)
		{
			$this->Mdl_help_desk_dashboard->assign_to_manager($_POST['manager_id']);
			$this->Mdl_help_desk_dashboard->notifications_list($_POST['manager_id'],'Assigned',"Manager");
			$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['assignment_id']);
			$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],1);
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['manager_id'],$_POST['reason'],"Assigned","Manager");
			$i++;
		}
		if($_POST['writer_id'] != null)
		{
			
			$assign_hours_writer = ((($_POST['assign_hours']/3)*2)/3)*2;
			$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_writer);
			$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Assigned',$_POST['assignment_id'],"Writer");
			$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['assignment_id']);
			$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],1);
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reason'],"Assigned","Writer");
			$i++;
		}
		if($_POST['proof_reader_id'] != null)
		{
			$assign_hours_proof_reader = (($_POST['assign_hours']/3)*2)/3;	
			$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_proof_reader);
			$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Assigned',$_POST['assignment_id'],"Proof Reader");	
			$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['assignment_id']);
			$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],1);
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reason'],"Assigned","Proof Reader");
			$i++;
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! Assignment '.$_POST['assignment_id'].' is assigned.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Help_desk_dashboard');
	}
	public function assign_to_list()
	{
		$ids = explode(",",$_POST['assignment_list_id']);
		$i = 0;
		foreach($ids as $id){
			$manager_id = null;
			$proof_reader_id = null;
			$writer_id = null;
			$assignment_data = null;
			$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
			
			$manager_id = $this->Mdl_help_desk_dashboard->manager_id($id);
			$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($id);
			$writer_id = $this->Mdl_help_desk_dashboard->writer_id($id);
			if($assignment_data[0]['status'] == 1)
			{
				if($manager_id == null && $proof_reader_id == null && $writer_id == null )
				{
					$datetime1 = new DateTime($assignment_data[0]['created_date']);
					$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] )));
					$interval = $datetime1->diff($datetime2);
					$hours = $interval->h + ($interval->days*24);
					$minit = $interval->i + ($hours*60);
					
					$deadline_date = date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] ));
					$assign_hours = ($minit / 10)*9 / 60;
					$save_hours = ($minit / 10) / 60;

					if($_POST['manager_id'] != null)
					{
						$this->Mdl_help_desk_dashboard->assign_to_manager_list($_POST['manager_id'],$id,$deadline_date,$save_hours,$assign_hours);
						$this->Mdl_help_desk_dashboard->notifications($_POST['manager_id'],'Assigned',$id,"Manager");
						$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['manager_id'],$_POST['reason'],"Assigned","Manager");
					}
					if($_POST['writer_id'] != null)
					{
						$assign_hours_writer = ((($assign_hours/3)*2)/3)*2;
						$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
						$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Assigned',$id,"Writer");
						$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reason'],"Assigned","Writer");
					}
					if($_POST['proof_reader_id'] != null)
					{
						$assign_hours_proof_reader = (($assign_hours/3)*2)/3;	
						$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours_proof_reader);
						$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Assigned',$id,"Proof Reader");
						$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reason'],"Assigned","Proof Reader");
					}
					$this->Mdl_help_desk_dashboard->assignment_date_update($id);
					$this->Mdl_help_desk_dashboard->assignment_admin_status($id,1);
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! '.$i.' Assignment are assigned.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Help_desk_dashboard');
	}
	public function re_assign_to()
	{
		$assignments = $this->Mdl_help_desk_dashboard->assignments($_POST['assignment_id']);
		if($assignments[0]['status'] == 7)
		{
			$this->Mdl_help_desk_dashboard->active_assignment($_POST['assignment_id'],1);
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($_POST['assignment_id']);
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
		}
		if($_POST['manager_id'] != null)
		{
			$manager_id = $this->Mdl_help_desk_dashboard->manager_id($_POST['assignment_id']);
			if($manager_id[0]['manager_id'] != $_POST['manager_id'])
			{
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($_POST['assignment_id']);
				$this->Mdl_help_desk_dashboard->re_assign_to_manager($_POST['manager_id']);
				$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Manager',$_POST['manager_id']);
				$this->Mdl_help_desk_dashboard->notifications_list($_POST['manager_id'],'Re-Assigned',"Manager");		
				$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['manager_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Manager");
				
			}else{
				if($manager_id[0]['status'] == 2 || $manager_id[0]['status'] == 5)
				{
					$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($_POST['assignment_id']);
					$this->Mdl_help_desk_dashboard->re_assign_to_manager($_POST['manager_id']);
					$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Manager',$_POST['manager_id']);
					$this->Mdl_help_desk_dashboard->notifications_list($_POST['manager_id'],'Re-Assigned',"Manager");		
					$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['manager_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Manager");
					
				}
			}
		}else{
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($_POST['assignment_id']);
		}
		
		if($_POST['writer_id'] != null)
		{
			$writer_id = $this->Mdl_help_desk_dashboard->writer_id($_POST['assignment_id']);
			if($writer_id[0]['writer_id'] != $_POST['writer_id'])
			{
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
				$assign_hours_writer = ((($_POST['assign_hours']/3)*2)/3)*2;
				$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_writer);
				$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Writer',$_POST['writer_id']);
				$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Re-Assigned',$_POST['assignment_id'],"Writer");		
				$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
				
			}else{
				if($writer_id[0]['status'] == 2 || $writer_id[0]['status'] == 5)
				{
					$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
					$assign_hours_writer = ((($_POST['assign_hours']/3)*2)/3)*2;
					$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_writer);
					$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Writer',$_POST['writer_id']);
					$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Re-Assigned',$_POST['assignment_id'],"Writer");		
					$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
					
				}
			}
		}else{
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($_POST['assignment_id']);
		}
		
		if($_POST['proof_reader_id'] != null)
		{
			$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($_POST['assignment_id']); 
			if($proof_reader_id[0]['proof_reader_id'] != $_POST['proof_reader_id'])
			{
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
				$assign_hours_proof_reader = (($_POST['assign_hours']/3)*2)/3;	
				$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_proof_reader);
				$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Proof Reader',$_POST['proof_reader_id']);
				$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Re-Assigned',$_POST['assignment_id'],"Proof Reader");		
				$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
				
			}else{
				if($proof_reader_id[0]['status'] == 2 || $proof_reader_id[0]['status'] == 5 )
				{
					$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
					$assign_hours_proof_reader = (($_POST['assign_hours']/3)*2)/3;	
					$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_proof_reader);
					$this->Mdl_help_desk_dashboard->assignment_re_assign_file($_POST['assignment_id'],'Proof Reader',$_POST['proof_reader_id']);
					$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Re-Assigned',$_POST['assignment_id'],"Proof Reader");		
					$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
					
				}
			}
		}else{
			$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($_POST['assignment_id']);
		}
		$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],2);
		
		$manager_ids = $this->Mdl_help_desk_dashboard->manager_id($_POST['assignment_id']);
		$writer_ids = $this->Mdl_help_desk_dashboard->writer_id($_POST['assignment_id']);
		$proof_reader_ids = $this->Mdl_help_desk_dashboard->proof_reader_id($_POST['assignment_id']); 
		if($manager_ids == null && $writer_ids == null && $proof_reader_ids == null)
		{
			$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],null);
		}
		
		$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['assignment_id']);
		
		$success_msg='Success! Assignment '.$_POST['assignment_id'].' is Re-assigned.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Help_desk_dashboard');
	}
	public function re_assign_to_list()
	{
		$ids = explode(",",$_POST['re_assignment_list_id']);
		$i = 0;
		foreach($ids as $id){
			$manager_id = null;
			$proof_reader_id = null;
			$writer_id = null;
			$assignment_data = null;
			$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
			
			$manager_id = $this->Mdl_help_desk_dashboard->manager_id($id);
			$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($id);
			$writer_id = $this->Mdl_help_desk_dashboard->writer_id($id);
			
			if($assignment_data[0]['status'] == 7)
			{
				$this->Mdl_help_desk_dashboard->active_assignment($id,1);
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($id);
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($id);
				$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($id);
				$assignment_data[0]['status'] = 1;
				$i++;
			}
			if($assignment_data[0]['status'] == 1)
			{
				if($manager_id != null || $proof_reader_id != null || $writer_id != null)
				{
					$datetime1 = new DateTime($assignment_data[0]['created_date']);
					$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] )));
					$interval = $datetime1->diff($datetime2);
					$hours = $interval->h + ($interval->days*24);
					$minit = $interval->i + ($hours*60);
					
					$deadline_date = date('Y-m-d H:i:s', strtotime( $assignment_data[0]['deadline_date'].' '.$assignment_data[0]['deadline_time'] ));
					$assign_hours = ($minit / 10)*9 / 60;
					$save_hours = ($minit / 10) / 60;
					
					if($_POST['manager_id'] != null)
					{
						$manager_id = $this->Mdl_help_desk_dashboard->manager_id($id);
						if($manager_id[0]['manager_id'] != $_POST['manager_id'])
						{
							$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($id);
							
							$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Manager',$_POST['manager_id']);
							
							$this->Mdl_help_desk_dashboard->assign_to_manager_list($_POST['manager_id'],$id,$deadline_date,$save_hours,$assign_hours);
							$this->Mdl_help_desk_dashboard->notifications($_POST['manager_id'],'Re-Assigned',$id,"Manager");		
							$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['manager_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Manager");
						}else{
							if($manager_id[0]['status'] == 2 || $manager_id[0]['status'] == 5)
							{
								$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($id);
								$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Manager',$_POST['manager_id']);
								
								$this->Mdl_help_desk_dashboard->assign_to_manager_list($_POST['manager_id'],$id,$deadline_date,$save_hours,$assign_hours);
								$this->Mdl_help_desk_dashboard->notifications($_POST['manager_id'],'Re-Assigned',$id,"Manager");		
								$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['manager_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Manager");
							}
						}
					}else{
						$this->Mdl_help_desk_dashboard->re_assign_assignment_remove($id);
					}
					if($_POST['writer_id'] != null)
					{
						$writer_id = $this->Mdl_help_desk_dashboard->writer_id($id);
						if($writer_id[0]['writer_id'] != $_POST['writer_id'])
						{
							$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($id);
							$assign_hours_writer = ((($assign_hours/3)*2)/3)*2;
							$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Writer',$_POST['writer_id']);
							
							$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
							$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Re-Assigned',$id,"Writer");		
							$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
						}else{
							if($writer_id[0]['status'] == 2 || $writer_id[0]['status'] == 5)
							{
								$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($id);
								$assign_hours_writer = ((($assign_hours/3)*2)/3)*2;
								$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Writer',$_POST['writer_id']);
								$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$id,$deadline_date,$assign_hours_writer);
								$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Re-Assigned',$id,"Writer");		
								$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['writer_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Writer");
							}
						}
					}else{
						$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_w($id);
					}
					
					if($_POST['proof_reader_id'] != null)
					{
						$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($id); 
						if($proof_reader_id[0]['proof_reader_id'] != $_POST['proof_reader_id'])
						{
							$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($id);
							$assign_hours_proof_reader = (($assign_hours/3)*2)/3;	
							$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Writer',$_POST['proof_reader_id']);
							$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours_proof_reader);
							$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Re-Assigned',$id,"Proof Reader");		
							$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
						}else{
							if($proof_reader_id[0]['status'] == 2 || $proof_reader_id[0]['status'] == 5 )
							{
								$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($id);
								$assign_hours_proof_reader = (($assign_hours/3)*2)/3;	
								$this->Mdl_help_desk_dashboard->assignment_re_assign_file($id,'Writer',$_POST['proof_reader_id']);
								$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$id,$deadline_date,$assign_hours_proof_reader);
								$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Re-Assigned',$id,"Proof Reader");		
								$this->Mdl_help_desk_dashboard->logs_insert($id,$_POST['proof_reader_id'],$_POST['reasons'].','.$_POST['reason'],"Re-Assigned","Proof Reader");
							}
						}
					}else{
						$this->Mdl_help_desk_dashboard->re_assign_assignment_remove_p($id);
					}
					$this->Mdl_help_desk_dashboard->assignment_admin_status($id,2);
					$manager_ids = $this->Mdl_help_desk_dashboard->manager_id($id);
					$writer_ids = $this->Mdl_help_desk_dashboard->writer_id($id);
					$proof_reader_ids = $this->Mdl_help_desk_dashboard->proof_reader_id($id); 
					if($manager_ids == null && $writer_ids == null && $proof_reader_ids == null)
					{
						$this->Mdl_help_desk_dashboard->assignment_admin_status($id,null);
					}
					$this->Mdl_help_desk_dashboard->assignment_date_update($id);
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! '.$i.' Assignment are Re-Assignment.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		
		redirect('Help_desk_dashboard');
	}
	
	public function assignment_image_add()	
	{
		$id = $_POST['assignment_id'];
		if (!is_dir('uploads/Assignment/'.$id)) {
			mkdir('./uploads/Assignment/'.$id, 0777, TRUE);		
		}
		if (!is_dir('uploads/Assignment/'.$id.'/help_desk')) {
			mkdir('./uploads/Assignment/'.$id.'/help_desk', 0777, TRUE);		
		}
		$count = count($_FILES['file']['name']);	
		$count--;
		$assignment_final_data = $this->Mdl_help_desk_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['help_desk_file']);
		for($i = 0; $i <= $count; $i++)	
		{		
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];	
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];		
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];	
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];	
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];	
			
			$ext=$this->Mdl_help_desk_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);			
			if(preg_match('/\s/',$_FILES['image']['name']) != 0){
				$error_msg='in Assigned File space is not Require.';
				$this->session->set_userdata('error_msg',$error_msg);
				redirect('Dashboard/assignment_view/'.$id);
			}
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'txt' || $ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg')
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Assignment/'.$id.'/help_desk/';	
					$config['allowed_types'] = '*';	
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];	
					$this->load->library('upload');    		
					$this->upload->initialize($config);		
					if($this->upload->do_upload('image')) 	
					{				
						$config['image_library'] = 'gd2';	
						$config['source_image']  = './uploads/Assignment/'.$id.'/help_desk/'.$filename[$i];	
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
			$this->Mdl_help_desk_dashboard->assignment_image_add($id,implode(",",$filename));
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
			$this->Mdl_help_desk_dashboard->assignment_image_update($id,$data);
		}
		
		
		if($_POST['manager_id'] != null)
		{
			$this->Mdl_help_desk_dashboard->re_assign_to_manager($_POST['manager_id']);
			$this->Mdl_help_desk_dashboard->notifications_list($_POST['manager_id'],'Re-Assigned',"Manager");		
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['manager_id'],$_POST['reason'],"Re-Assigned","Manager");
			//$this->Mdl_help_desk_dashboard->assignment_created_update($_POST['assignment_id']);
			
			$success_msg='Assignment Re-Assigned To Manager.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		if($_POST['writer_id'] != null)
		{
			$assign_hours_writer = ((($_POST['assign_hours']/3)*2)/3)*2;
			$this->Mdl_help_desk_dashboard->assign_to_writer($_POST['writer_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_writer);
			$this->Mdl_help_desk_dashboard->notifications($_POST['writer_id'],'Re-Assigned',$_POST['assignment_id'],"Writer");	
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['writer_id'],$_POST['reason'],"Re-Assigned","Writer");
			//$this->Mdl_help_desk_dashboard->assignment_created_update($_POST['assignment_id']);
			
			$success_msg='Assignment Re-Assigned To Writer.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		if($_POST['proof_reader_id'] != null)
		{
			$assign_hours_proof_reader = (($_POST['assign_hours']/3)*2)/3;	
			$this->Mdl_help_desk_dashboard->assign_to_proof_reader($_POST['proof_reader_id'],$_POST['assignment_id'],$_POST['deadline_date'],$assign_hours_proof_reader);
			$this->Mdl_help_desk_dashboard->notifications($_POST['proof_reader_id'],'Re-Assigned',$_POST['assignment_id'],"Proof Reader");
			$this->Mdl_help_desk_dashboard->logs_insert($_POST['assignment_id'],$_POST['proof_reader_id'],$_POST['reason'],"Re-Assigned","Proof Reader");
			//$this->Mdl_help_desk_dashboard->assignment_created_update($_POST['assignment_id']);
			
			$success_msg='Assignment Re-Assigned To Proof Reader.';
			$this->session->set_userdata('success_msg',$success_msg);
			
		}
		
		if($_POST['assign'] != null)
		{
			$manager_ids = $this->Mdl_help_desk_dashboard->manager_id($_POST['assignment_id']);
			$writer_ids = $this->Mdl_help_desk_dashboard->writer_id($_POST['assignment_id']);
			$proof_reader_ids = $this->Mdl_help_desk_dashboard->proof_reader_id($_POST['assignment_id']); 
			if($manager_ids != null || $writer_ids != null || $proof_reader_ids != null)
			{
				$this->Mdl_help_desk_dashboard->assignment_admin_status($_POST['assignment_id'],1);
			}
		}
		
		$this->Mdl_help_desk_dashboard->assignment_date_update($id);
		
		$success_msg='Success! Assignment '.$_POST['assignment_id'].' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Help_desk_dashboard');	
	}
	public function assignment_remove_file()	
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_help_desk_dashboard->assignment_final_data($id);
		$getemage = explode(',',$getdatas[0]['help_desk_file']);
		unset($getemage[$ids]);
		$images = $this->Mdl_help_desk_dashboard->assignment_remove_file($id,implode(",", $getemage));
		$this->Mdl_help_desk_dashboard->assignment_date_update($id);
		
		$success_msg='Success! File is removed.';
		$this->session->set_userdata('success_msg',$success_msg);
	}
	public function edit_taske($id)
	{
		$assignment_data = $this->Mdl_help_desk_dashboard->assignments($id);
		$data = $assignment_data[0];
					$actions =	'<form method="post" class="login-form" action="'.base_url().'index.php/Help_desk_dashboard/assignment_edit" enctype="multipart/form-data">
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
		
			$ext=$this->Mdl_help_desk_dashboard->get_file_extension($_FILES['image']['name']);
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
		if($_POST['idss'] == null)
		{
			$result = array_splice($filename,0,5,true);
			$filename = implode(",",$result);
			$assignment_add=$this->Mdl_help_desk_dashboard->assignment_add($filename);
			$this->Mdl_help_desk_dashboard->logs_insert_c($assignment_add,"Add",'');
		}else{
			$result = array_splice($filename,0,5,true);
			$filename = implode(",",$result);
			$assignment_add=$this->Mdl_help_desk_dashboard->assignment_add($filename);
			$this->Mdl_help_desk_dashboard->logs_insert_c($assignment_add,'Clone',$_POST['reason']);
		}
		
		$all_admin_get = $this->Mdl_help_desk_dashboard->all_admin_get();
		foreach($all_admin_get as $admin_get)
		{
			$this->Mdl_help_desk_dashboard->notifications($assignment_add,'waiting for approval',$admin_get['id'],'Admin');
		}
		$success_msg='Success! Assignment '.$assignment_add.' is added.';
		$this->session->set_flashdata('success_msg',$success_msg);
		
		redirect('Help_desk_dashboard');
	}
	public function approval($id)
	{
		$this->Mdl_help_desk_dashboard->approval($id);
		redirect('Help_desk_dashboard');
	}
	public function assignment_view($id)
	{
		if($this->session->Admindetail['help_desk'] == 1)
		{			
			$this->Mdl_help_desk_dashboard->assignment_notifications($id);
			$assignment_datas = $this->Mdl_help_desk_dashboard->assignment_all();
			
			foreach($assignment_datas as $key => $assign_to_man){	
				if($assign_to_man['id'] == $id){	
					$assignment_data[0] = $assign_to_man;
				}				
			}
			
			$assignment_final_data = $this->Mdl_help_desk_dashboard->assignment_final_data($id);
			$data=array(				
				'datas'=>$assignment_data,
				'assignment_final_data'=>$assignment_final_data,	
				'main_content'=>'assignment_view',		
				'left_sidebar'=>'Help Desk Dashboard',
			);
			
			$this->load->view('help_desk_template/template',$data);
		}else{			
			redirect('Admin_login');	
		}	
	}
	public function assignment_edit()
	{
		$id = $_POST['ids'];
		$count = count($_FILES['file']['name']);
		$count--;
		$assignments = $this->Mdl_help_desk_dashboard->assignments($id);
		$filess = explode(",",$assignments[0]['file']);
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
		
			$ext=$this->Mdl_help_desk_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($_FILES['image']['size'] < 52428800)
			{
				if($ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'pdf' || $ext == 'zip' || $ext == 'jpeg' || $ext == 'png' || $ext == 'jpg' || $ext == 'txt')
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
		if($filename == null)
		{
			$filess = array_splice($filess,0,5,true);
			$file = implode(",",$filess);
		}else{
			$result = array_filter(array_unique(array_merge($filename, $filess)));
			$result = array_splice($result,0,5,true);
			$file = implode(",",$result);
		}
		if($assignments[0]['status'] == 2)
		{
			$assignment_add = $this->Mdl_help_desk_dashboard->assignment_edit($file,0);
		}else{
			$assignment_add = $this->Mdl_help_desk_dashboard->assignment_edit($file,$assignments[0]['status']);
		}
		
		$this->Mdl_help_desk_dashboard->logs_insert_c($_POST['assignment_id'],'Edit','');
		
		$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['ids']);
		
		$success_msg='Success! Assignment '.$_POST['ids'].' is edited.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Help_desk_dashboard');
	}
	public function assignment_delete($id)
	{
		$user_data = $this->Mdl_help_desk_dashboard->assignment_delete($id);
		redirect('Help_desk_dashboard');
	}
	public function clone_assignment_list()
	{
		$ids = explode(",",$_POST['clone_list_id']);
		$i = 0;
		foreach($ids as $id){
			if($id != null)
			{
				$datas = array();
				$datas = $this->Mdl_help_desk_dashboard->assignments($id);
				unset($datas[0]['id']);
				unset($datas[0]['created_id']);
				unset($datas[0]['created_role']);
				unset($datas[0]['admin_status']);
				$assignment_id = $this->Mdl_help_desk_dashboard->duplicate_assignment($datas[0]);
				$this->Mdl_help_desk_dashboard->logs_insert_c($assignment_id,'Clone',$_POST['reason']);
				
				$all_admin_get = $this->Mdl_help_desk_dashboard->all_admin_get();
				foreach($all_admin_get as $admin_get)
				{
					$this->Mdl_help_desk_dashboard->notifications($assignment_id,'waiting for approval',$admin_get['id'],'Admin');
				}
				$i++;
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! '.$i.' Assignment are added.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		
		redirect('Help_desk_dashboard');
	}
	public function cancel_assignment()
	{
		$user_data = $this->Mdl_help_desk_dashboard->active_assignment($_POST['ids'],4);
		$this->Mdl_help_desk_dashboard->assignment_date_update($_POST['ids']);
		
		$manager_id_count = $this->Mdl_help_desk_dashboard->manager_id($_POST['ids']);
		$proof_reader_id_count = $this->Mdl_help_desk_dashboard->proof_reader_id($_POST['ids']);
		$writer_id_count = $this->Mdl_help_desk_dashboard->writer_id($_POST['ids']);
		if($manager_id_count[0] != null)
		{
			$this->Mdl_help_desk_dashboard->notifications($manager_id_count[0]['manager_id'],'Cancelled',$_POST['ids'],"Manager");
		}
		if($proof_reader_id_count[0] != null)
		{
			$this->Mdl_help_desk_dashboard->notifications($proof_reader_id_count[0]['proof_reader_id'],'Cancelled',$_POST['ids'],"Proof Reader");
		}
		if($writer_id_count[0] != null)
		{
			$this->Mdl_help_desk_dashboard->notifications($writer_id_count[0]['writer_id'],'Cancelled',$_POST['ids'],"Writer");
		}
		
		$this->Mdl_help_desk_dashboard->logs_insert_c($_POST['ids'],"Cancelled",$_POST['reason']);
		
		$success_msg='Success! Assignment '.$_POST['ids'].' is Cancelled.';
		$this->session->set_userdata('success_msg',$success_msg);
		
		redirect('Help_desk_dashboard');	
	}
	public function cancel_assignment_list()
	{
		$ids = explode(",",$_POST['cancel_list_id']);
		$i = 0;
		foreach($ids as $id)
		{
			$user_data = null;
			$manager_id_count = null;
			$proof_reader_id_count = null;
			$writer_id_count = null;
			
			$user_data = $this->Mdl_help_desk_dashboard->active_assignment($id,4);
			$this->Mdl_help_desk_dashboard->assignment_date_update($id);
			
			$manager_id_count = $this->Mdl_help_desk_dashboard->manager_id($id);
			$proof_reader_id_count = $this->Mdl_help_desk_dashboard->proof_reader_id($id);
			$writer_id_count = $this->Mdl_help_desk_dashboard->writer_id($id);
			if($manager_id_count[0] != null)
			{
				$this->Mdl_help_desk_dashboard->notifications($manager_id_count[0]['manager_id'],'Cancelled',$id,"Manager");
			}
			if($proof_reader_id_count[0] != null)
			{
				$this->Mdl_help_desk_dashboard->notifications($proof_reader_id_count[0]['proof_reader_id'],'Cancelled',$id,"Proof Reader");
			}
			if($writer_id_count[0] != null)
			{
				$this->Mdl_help_desk_dashboard->notifications($writer_id_count[0]['writer_id'],'Cancelled',$id,"Writer");
			}
			$this->Mdl_help_desk_dashboard->logs_insert_c($id,"Cancelled",$_POST['reason']);
			$i++;
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_userdata('error_msg',$error_msg);
		}else{
			$success_msg='Success! Assignment '.$i.' is Cancelled.';
			$this->session->set_userdata('success_msg',$success_msg);
		}
		redirect('Help_desk_dashboard');
	}
}
?>