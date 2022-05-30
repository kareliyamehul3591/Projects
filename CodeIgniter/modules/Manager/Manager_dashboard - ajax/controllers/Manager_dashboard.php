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
	public function index()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all();
			$i = 0;
			foreach($assign_to_ma_all as $val) {
				if($val['assign_to_ma_status'] != 2)
				{
					if (!in_array($val['id'], $key_array)) {
						$key_array[$i] = $val['id'];
						$assignment_datas[$i] = $val;
					}
					$i++;
				}
			}
			$online_writer = $this->Mdl_manager_dashboard->online_writer();
			$others_writer = $this->Mdl_manager_dashboard->others_writer();
			$online_proof_reader = $this->Mdl_manager_dashboard->online_proof_reader();
			$others_proof_reader = $this->Mdl_manager_dashboard->others_proof_reader();
			$assign_to_pr = $this->Mdl_manager_dashboard->assign_to_pr();
			$assign_to_writer = $this->Mdl_manager_dashboard->assign_to_writers();
			$data=array(
				'datas'=>$assignment_datas,
				'online_writer'=>$online_writer,
				'others_writer'=>$others_writer,
				'online_proof_reader'=>$online_proof_reader,
				'others_proof_reader'=>$others_proof_reader,
				'assign_to_pr'=>$assign_to_pr,
				'assign_to_writer'=>$assign_to_writer,
				'main_content'=>'manager_dashboard',
				'left_sidebar'=>'Manager Dashboard',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function user_data_get()
	{
		$assign_to_ma_all = $this->Mdl_manager_dashboard->assign_to_ma_all();
		$i = 0;
		foreach($assign_to_ma_all as $val) {
			if($val['assign_to_ma_status'] != 2)
			{
				if (!in_array($val['id'], $key_array)) {
					$key_array[$i] = $val['id'];
					$assignment_data[$i] = $val;
				}
				$i++;
			}
		}
		$totalData = count($assignment_data);
		$requestData= $_REQUEST;
		$columns = array( 
			0 => 'assignment.id', 
			1 => 'assignment.name',
			2 => 'assignment.created_date',
			3 => 'assignment.client_name',
			7 => 'assignment.deadline_date',
		);
		$assignment_get_datas = $this->Mdl_manager_dashboard->assignment_get_datas($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir'],$requestData['search']['value']);
		
		$totalFiltered = count($assignment_get_datas);
		
		$assignment_get_data = array_splice($assignment_get_datas,$requestData['start'],$requestData['length'],true);
		//$assignment_get_data = $this->Mdl_manager_dashboard->assignment_get_data($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir'],$requestData['search']['value'],$requestData['start'],$requestData['length']);
		
		foreach($assignment_get_data as $data)
		{
			$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
			$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);
			
			$proof_reader_name = $this->Mdl_manager_dashboard->user_get($proof_reader_id[0]['proof_reader_id']);
			$writer_name = $this->Mdl_manager_dashboard->user_get($writer_id[0]['writer_id']);
			$adminr_name = $this->Mdl_manager_dashboard->user_get($data['admin_id']);
			
			
			if($data['status'] == 4){
				$manager_status = '<span class="label label-danger">Cancelled</span>';
			}else if($data['assign_to_ma_status'] == 5){
				$manager_status = '<span class="label label-success">Complete</span>';
			}else if($data['assign_to_ma_status'] == 1){	
				$manager_status = '<span class="label label-primary">Accepted</span>';
			}else if($data['assign_to_ma_status'] == 6){	
				$manager_status = '<span class="label label-danger">Re-Assigned</span>';
			}else{	
				$manager_status = '<span class="label label-success">New</span>';
			}
											
			if($writer_id != null)
			{
				if($data['status'] == 4){
					$write_status = '<span class="label label-danger">Cancelled</span>';
				}else if($writer_id[0]['status'] == 0){							
					$write_status = '<span class="label label-warning">Assigned</span>';
				}else if($writer_id[0]['status'] == 1){
					$write_status = '<span class="label label-primary">Accepted</span>';
				}else if($writer_id[0]['status'] == 2){
					$write_status = '<span class="label label-danger">Rejected</span>';
				}else if($writer_id[0]['status'] == 5){
					$write_status = '<span class="label label-success">Complete</span>';
				}else if($writer_id[0]['status'] == 6){
					$write_status = '<span class="label label-danger">Re-Assigned</span>';
				}
			}else{	
				$write_status = '<span class="label label-success">New</span>';
			}
				
			if($proof_reader_id != null)
			{
				if($data['status'] == 4){
					$proof_reader_status = '<span class="label label-danger">Cancelled</span>';
				}else if($proof_reader_id[0]['status'] == 0){							
					$proof_reader_status = '<span class="label label-warning">Assigned</span>';
				}else if($proof_reader_id[0]['status'] == 1){
					$proof_reader_status = '<span class="label label-primary">Accepted</span>';
				}else if($proof_reader_id[0]['status'] == 2){
					$proof_reader_status = '<span class="label label-danger">Rejected</span>';
				}else if($proof_reader_id[0]['status'] == 5){
					$proof_reader_status = '<span class="label label-success">Complete</span>';
				}else if($proof_reader_id[0]['status'] == 6){
					$proof_reader_status = '<span class="label label-danger">Re-Assigned</span>';
				}
			}else{	
				$proof_reader_status = '<span class="label label-success">New</span>';
			}
			
			$view_url = "myModal('".base_url()."index.php/Manager_dashboard/view_taske/".$data['id']."')";
			$assign_url = "myModal('".base_url()."index.php/Manager_dashboard/assign_taske/".$data['id']."')";
			$re_assign_url = "myModal('".base_url()."index.php/Manager_dashboard/re_assign_taske/".$data['id']."')";
			$actions = "";
			if($data['status'] == 4){
				$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
			}else if($data['status'] == 5){
				
				$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
			}else if($data['assign_to_ma_status'] == 0){
				$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
			}else if($data['assign_to_ma_status'] == 1){
				if($proof_reader_id == null){
					$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
					$actions .= '<a class="btn btn-default" onclick="'.$assign_url.'" ><i class="fa fa-pencil-square-o"></i> Assign </a>';
				}else if($proof_reader_id != null){
					if($proof_reader_id[0]['status'] == 1 || $proof_reader_id[0]['status'] == 6 || $proof_reader_id[0]['status'] == 0)
					{
						$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
						if($writer_id[0]['status'] == 2)
						{
							$actions .= '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
						}
					}else if($proof_reader_id[0]['status'] == 2)
					{
						$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
						$actions .= '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
					}else if($proof_reader_id[0]['status'] == 5)
					{
						$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
						$actions .= '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
						$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/complete_assignment/'.$data['id'].'" ><i class="fa fa-check"></i> Complete </a>';	
					}
				}
			}else if($data['assign_to_ma_status'] == 5){
				$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
			}





			
			$assignment_datas[] = array( 
				$data['id'], 
				$data['name'], 
				date('m/d/Y H:i:s', strtotime( $data['created_date'] )), 
				$data['client_name'],
				date('m/d/Y', strtotime( $data['deadline_date'] )).' '.date('H:i', strtotime( $data['deadline_time'] )),
				$data['assignment_type'],
				$adminr_name[0]['name'],
				$writer_name[0]['name'],
				$proof_reader_name[0]['name'],
				$manager_status,
				$write_status,
				$proof_reader_status,
				$actions,
			);
		}
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ), 
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $assignment_datas 
		);
			
		echo json_encode($json_data);
		die;
	}
	public function assign_taske($id)
	{
		$online_writer = $this->Mdl_manager_dashboard->online_writer();
		$others_writer = $this->Mdl_manager_dashboard->others_writer();
		$online_proof_reader = $this->Mdl_manager_dashboard->online_proof_reader();
		$others_proof_reader = $this->Mdl_manager_dashboard->others_proof_reader();
		
		$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
		
		$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
		$data = $assignment_data[0];
		
		$data = '<form method="post" class="login-form" action="'.base_url().'index.php/Manager_dashboard/assign_to" enctype="multipart/form-data">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">Assignment Assign To Writer And Proof Reader</h4>
											</div>
											<div class="modal-body">
												<input type="hidden" name="assignment_id" id="assignment_id" value="'.$data['id'].'">';
												
												$assign_hours = (($manager_id[0]['assign_hours']/3)*2)/3;
												
												
											$data .= '<input type="hidden" name="deadline_date" id="deadline_date" value="'.date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )).'">
												<input type="hidden" name="assign_hours_writer" id="assign_hours_writer" value="'.($assign_hours*2).'">
												<input type="hidden" name="assign_hours_proof_reader" id="assign_hours_proof_reader" value="'.$assign_hours.'">						
												<input type="hidden" name="save_hours" id="save_hours" value="'.($manager_id[0]['assign_hours']/3).'">
												
												Writer <br/><br/>
												<div class="row">
													<div class="col-xs-4">
														 Online Writer
													</div>
													<div class="col-xs-6">
														<select class="form-control" name="online_writer" id="online_writer" >
															<option value="">Select Online Writer</option>';
															foreach($online_writer as $online){
																$data .= '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
															}
														$data .= '</select>
													</div>
												</div></br>
												<div class="row">
													<div class="col-xs-4"> 
														 Others Writer
													</div>
													<div class="col-xs-6">
														<select class="form-control" name="others_writer" id="others_writer" >
															<option value="">Select Others Writer</option>';
															foreach($others_writer as $online){
																$data .= '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
															}
														$data .= '</select>
													</div>
												</div></br>
												
												Proof Reader <br/><br/>
												<div class="row">
													<div class="col-xs-4">
														 Online Proof Reader
													</div>
													<div class="col-xs-6">
														<select class="form-control" name="online_proof_reader" id="online_proof_reader" >
															<option value="">Select Online Proof Reader</option>';
															foreach($online_proof_reader as $online){
																$data .= '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
															}
														$data .= '</select>
													</div>
												</div></br>
												<div class="row">
													<div class="col-xs-4">
														 Others Proof Reader
													</div>
													<div class="col-xs-6">
														<select class="form-control" name="others_proof_reader" id="others_proof_reader" >
															<option value="">Select Others Proof Reader</option>';
															foreach($others_proof_reader as $online){
																$data .= '<option value="'.$online['id'].
																'" >'.$online['name'].'</option>';
															}
														$data .= '</select>
													</div>
												</div></br>
												
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Assign</button>
											</div>
										</form>';
		echo $data;
	}
	
	public function re_assign_taske($id)
	{
		$online_writer = $this->Mdl_manager_dashboard->online_writer();
		$others_writer = $this->Mdl_manager_dashboard->others_writer();
		$online_proof_reader = $this->Mdl_manager_dashboard->online_proof_reader();
		$others_proof_reader = $this->Mdl_manager_dashboard->others_proof_reader();
		
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
							<?php 
							$assign_hours = (($data['assign_hours']/3)*2)/3;
							?>
							
							<input type="hidden" name="deadline_date" id="deadline_date" value="<?php echo date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )); ?>">
							
							
							<?php 
							if($writer_id[0]['status'] == 2 || $writer_id[0]['status'] == 5 )
							{ ?>
								
								<input type="hidden" name="assign_hours_writer" id="assign_hours_writer" value="<?php echo $assign_hours*2; ?>">
								
								Writer <br/><br/>
								<div class="row">
									<div class="col-xs-4">
										 Online Writer
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="online_writer" id="online_writer" >
											<option value="">Select Online Writer</option>
											<?php foreach($online_writer as $online){?>
												<option value="<?php echo $online['id']; ?>" ><?php echo $online['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div></br>
								<div class="row">
									<div class="col-xs-4">
										 Others Writer
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="others_writer" id="others_writer" >
											<option value="">Select Others Writer</option>
											<?php foreach($others_writer as $online){?>
												<option value="<?php echo $online['id']; ?>" ><?php echo $online['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div></br>
								
						<?php }
							if($proof_reader_id[0]['status'] == 2 || $proof_reader_id[0]['status'] == 5)
							{
							?>
								<input type="hidden" name="assign_hours_proof_reader" id="assign_hours_proof_reader" value="<?php echo $assign_hours; ?>">
								Proof Reader <br/><br/>
								<div class="row">
									<div class="col-xs-4"> 
										 Online Proof Reader
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="online_proof_reader" id="online_proof_reader" >
											<option value="">Select Online Proof Reader</option>
											<?php foreach($online_proof_reader as $online){?>
												<option value="<?php echo $online['id']; ?>" ><?php echo $online['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div></br>
								<div class="row">
									<div class="col-xs-4">
										 Others Proof Reader
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="others_proof_reader" id="others_proof_reader" >
											<option value="">Select Others Proof Reader</option>
											<?php foreach($others_proof_reader as $online){?>
												<option value="<?php echo $online['id']; ?>" ><?php echo $online['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div></br>
							<?php } ?>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Re-Assign</button>
						</div>
					</form>
			<?php				
	}
	
	public function view_taske($id)
	{
		$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
		$manager_id = $this->Mdl_manager_dashboard->manager_id($id);
		$data = $assignment_data[0];
		$data['assign_hours'] = $manager_id[0]['assign_hours'];
		$data['assign_to_ma_status'] = $manager_id[0]['status'];
		?>
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">View</h4>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<th style="width:50%">Name:</th>
								<td><?php echo $data['name']; ?></td>
							</tr>
							<tr>
								<th>Client  Name:</th>
								<td><?php echo $data['client_name']; ?></td>
							</tr>
							<tr>
								<th>Deadline:</th>
								<td><?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.$data['deadline_time']; ?></td>
							</tr>
							<tr>
								<th>Type:</th>
								<td><?php echo $data['assignment_type']; ?></td>
							</tr>
							<tr>
								<th>No.of Tasks:</th>
								<td><?php echo $data['tasks_no']; ?></td>
							</tr>
							<tr>
								<th>Niche:</th>
								<td><?php echo $data['health']; ?></td>
							</tr>
							<tr>
								<th>No.of Words/Article:</th>
								<td><?php echo $data['article']; ?></td>
							</tr>
							<tr>
								<th>Description:</th>
								<td><?php echo $data['description']; ?></td>
							</tr>
							<tr>
								<th>File:</th>
								<td>
									<?php 
										foreach(explode(",", $data['file']) as $file)
										{ ?>
											<a href="<?php echo base_url();?>/uploads/Assignment/<?php echo $file; ?>" target='_blank' ><?php echo $file; ?></a><br/>
									<?php } ?>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
				<?php if($data['status'] != 4){
					if($data['assign_to_ma_status'] == 0 ){
						echo '<a class="btn btn-default pull-left" data-dismiss="modal" onclick="assign_to_ma_status('.$data['id'].',1,'.$data['admin_id'].')" >Accept</a> &nbsp; &nbsp; &nbsp; ';
						
						echo '<a class="btn btn-default pull-left" data-dismiss="modal" onclick="assign_to_ma_status('.$data['id'].',2,'.$data['admin_id'].')" style="margin-left: 10px;" >Reject</a><br/>';
					}
				}
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			</div>
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
			$assignment_data = $this->Mdl_manager_dashboard->assignment($id);
			$assignment_final_data = $this->Mdl_manager_dashboard->assignment_final_data($id);
			$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_ma();
			foreach($assign_to_ma as $key => $assign_to_man){	
				if($assign_to_man['assignment_id'] == $assignment_data[0]['id']){	
					$assignment_data[0]['assign_to_ma_status'] = $assign_to_man['status'];
				}				
			}					
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
		$count = count($_FILES['file']['name']);	
		$count--;	
		$assignment_final_data = $this->Mdl_manager_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['file']);
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
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard/assignment_view/'.$id);
			}			
			if($ext == 'docx' || $ext == 'doc')		
			{
				$filename[$i]='';
				$ida=uniqid();		
				$filename[$i]=$_FILES['image']['name'];
				$config['upload_path'] = './uploads/Assignment/'.$id.'/';	
				$config['allowed_types'] = 'docx|doc';		
				$config['overwrite'] = TRUE;
				$config['file_name']=$filename[$i];
				$this->load->library('upload');    		
				$this->upload->initialize($config);		
				if($this->upload->do_upload('image')) 	
				{				
					$config['image_library'] = 'gd2';	
					$config['source_image']  = './uploads/Assignment/'.$id.'/'.$filename[$i];	
					$this->load->library('image_lib', $config); 	
					$this->image_lib->resize(); 		
					$this->image_lib->clear();			
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
		if($filess == null)
		{
			$data = implode(",",$filename);
		}else{
			$data = implode(",",$filess).','.implode(",",$filename);
		}		
		$this->Mdl_manager_dashboard->assignment_image_update($id,$data);
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment data save.';
		$this->session->set_flashdata('success_msg',$success_msg);
		redirect('Manager_dashboard');
	}
	public function assign_to()
	{
		if($_POST['online_writer'] != null)
		{
			if($_POST['online_proof_reader'] != null)
			{
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->managet_save_time();
				$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Assigned",$_POST['online_writer'],$_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assigned To Writer And Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else if($_POST['others_proof_reader'] != null){
				
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->managet_save_time();
				$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Assigned",$_POST['online_writer'],$_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assigned To Writer And Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else {
				$error_msg='Failed Please enter a valid Proof Reader.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard');
			}
		}else if($_POST['others_writer'] != null){
			
			if($_POST['online_proof_reader'] != null)
			{
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->managet_save_time();
				$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Assigned",$_POST['others_writer'],$_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assigned To Writer And Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else if($_POST['others_proof_reader'] != null){
				
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->managet_save_time();
				$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Assigned",$_POST['others_writer'],$_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assigned To Writer And Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else {
				$error_msg='Failed Please enter a valid Proof Reader.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard');
			}
		}else {
			$error_msg='Failed Please enter a valid Writer And Proof Reader.';
			$this->session->set_flashdata('error_msg',$error_msg);
			redirect('Manager_dashboard');
		}
		
	}
	public function re_assign_to()
	{
		if($_POST['assign_hours_proof_reader'] != null && $_POST['assign_hours_writer'] != null )
		{
			if($_POST['online_writer'] != null)
			{
				if($_POST['online_proof_reader'] != null)
				{
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Re-Assigned",$_POST['online_writer'],$_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer And Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else if($_POST['others_proof_reader'] != null){
					
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Re-Assigned",$_POST['online_writer'],$_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer And Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else {
					$error_msg='Failed Please enter a valid Proof Reader.';
					$this->session->set_flashdata('error_msg',$error_msg);
					redirect('Manager_dashboard');
				}
			}else if($_POST['others_writer'] != null){
				
				if($_POST['online_proof_reader'] != null)
				{
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer'],$_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer And Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else if($_POST['others_proof_reader'] != null){
					
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_wr_pr($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer'],$_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer And Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else {
					$error_msg='Failed Please enter a valid Proof Reader.';
					$this->session->set_flashdata('error_msg',$error_msg);
					redirect('Manager_dashboard');
				}
			}else {
				
				if($_POST['online_proof_reader'] != null)
				{
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['online_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else if($_POST['others_proof_reader'] != null){
					
					$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer'],$_POST['others_proof_reader']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Proof Reader.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else if($_POST['online_writer'] != null){
					
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['online_writer']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else if($_POST['others_writer'] != null){
					
					$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
					$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer']);
					$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
					
					$success_msg='Assignment Assign To Writer.';
					$this->session->set_flashdata('success_msg',$success_msg);
					redirect('Manager_dashboard');
				}else {
					$error_msg='Failed Please enter a valid Writer And Proof Reader.';
					$this->session->set_flashdata('error_msg',$error_msg);
					redirect('Manager_dashboard');
				}
				$error_msg='Failed Please enter a valid Writer And Proof Reader.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard');
			}
		}else if($_POST['assign_hours_writer'] != null)
		{
			if($_POST['online_writer'] != null)
			{
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['online_writer']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['online_writer']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assign To Writer.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else if($_POST['others_writer'] != null){
				
				$this->Mdl_manager_dashboard->assign_to_writer($_POST['others_writer']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assign To Writer.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else {
				$error_msg='Failed Please enter a valid Writer.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard');
			}
		}else if($_POST['assign_hours_proof_reader'] != null)
		{
			if($_POST['online_proof_reader'] != null)
			{
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['online_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assign To Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else if($_POST['others_proof_reader'] != null){
				
				$this->Mdl_manager_dashboard->assign_to_proof_reader($_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->notifications_all($_POST['assignment_id'],"Re-Assigned",$_POST['others_writer'],$_POST['others_proof_reader']);
				$this->Mdl_manager_dashboard->assignment_date_update($_POST['assignment_id']);
				
				$success_msg='Assignment Assign To Proof Reader.';
				$this->session->set_flashdata('success_msg',$success_msg);
				redirect('Manager_dashboard');
			}else {
				$error_msg='Failed Please enter a valid Proof Reader.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Manager_dashboard');
			} 
		}
	}
	public function assign_to_list()
	{
		$ids = explode(",",$_POST['assignment_list_id']);
		$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_ma();
		$assign_to_writers = $this->Mdl_manager_dashboard->assign_to_writers();
		$assign_to_pr = $this->Mdl_manager_dashboard->assign_to_pr();
		foreach($assign_to_writers as $data_wr)
		{
			$assignment_reject_id_wr[] = $data_wr['assignment_id'];
		}
		foreach($assign_to_pr as $data_pr)
		{
			$assignment_reject_id_pr[] = $data_pr['assignment_id'];
		}
		$i = 0;
		foreach($assign_to_ma as $data)
		{
			if($data['status'] == 1)
			{
				foreach($ids as $id)
				{
					if($id == $data['assignment_id'])
					{
						$assign_hours_proof_reader = (($data['assign_hours']/3)*2)/3;
						$assign_hours_writer = $assign_hours_proof_reader*2;
						$save_hours = $data['assign_hours']/3;
						
						if(!in_array($data['assignment_id'],$assignment_reject_id_wr))
						{
							if($_POST['online_writer'] != null)
							{
								$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['online_writer'],$id,$data['deadline_date'],$assign_hours_writer);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);							
								$this->Mdl_manager_dashboard->notifications_all($id,"Assigned",$_POST['online_writer']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);								
								$i++;
							}else if($_POST['others_writer'] != null){
								$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['others_writer'],$id,$data['deadline_date'],$assign_hours_writer);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Assigned",$_POST['others_writer']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}
						}
						if(!in_array($data['assignment_id'],$assignment_reject_id_pr))
						{
							if($_POST['online_proof_reader'] != null)
							{
								$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['online_proof_reader'],$id,$data['deadline_date'],$assign_hours_proof_reader);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Assigned",$_POST['online_proof_reader']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}else if($_POST['others_proof_reader'] != null){
								$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['others_proof_reader'],$id,$data['deadline_date'],$assign_hours_proof_reader);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Assigned",$_POST['others_proof_reader']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}
						}
					}
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			$success_msg='Assignment Assigned To Writer And Proof Reader.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
	public function re_assign_to_list()
	{
		$ids = explode(",",$_POST['re_assignment_list_id']);
		$assign_to_ma = $this->Mdl_manager_dashboard->assign_to_ma();
		$assign_to_writers = $this->Mdl_manager_dashboard->assign_to_writers();
		$assign_to_pr = $this->Mdl_manager_dashboard->assign_to_pr();
		foreach($assign_to_writers as $data_wr)
		{
			if($data_wr['status'] == 2)
			{
				$assignment_reject_id_wr[] = $data_wr['assignment_id'];
			}
		}
		foreach($assign_to_pr as $data_pr)
		{
			if($data_pr['status'] == 2)
			{
				$assignment_reject_id_pr[] = $data_pr['assignment_id'];
			}
		}
		$i = 0;
		foreach($assign_to_ma as $data)
		{
			if($data['status'] == 1)
			{
				foreach($ids as $id)
				{
					if($id == $data['assignment_id'])
					{
						$assign_hours_proof_reader = (($data['assign_hours']/3)*2)/3;
						$assign_hours_writer = $assign_hours_proof_reader*2;
						$save_hours = $data['assign_hours']/3;
						if(in_array($data['assignment_id'],$assignment_reject_id_wr))
						{
							if($_POST['online_writer'] != null)
							{
								$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['online_writer'],$id,$data['deadline_date'],$assign_hours_writer);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Re-Assigned",$_POST['online_writer']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}else if($_POST['others_writer'] != null){
								$this->Mdl_manager_dashboard->assign_to_writer_list($_POST['others_writer'],$id,$data['deadline_date'],$assign_hours_writer);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Re-Assigned",$_POST['others_writer']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}
						}
						if(in_array($data['assignment_id'],$assignment_reject_id_pr))
						{
							if($_POST['online_proof_reader'] != null)
							{
								$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['online_proof_reader'],$id,$data['deadline_date'],$assign_hours_proof_reader);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Re-Assigned",$_POST['online_proof_reader']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}else if($_POST['others_proof_reader'] != null){
								$this->Mdl_manager_dashboard->assign_to_proof_reader_list($_POST['others_proof_reader'],$id,$data['deadline_date'],$assign_hours_proof_reader);
								$this->Mdl_manager_dashboard->managet_save_time_list($id,$save_hours);
								$this->Mdl_manager_dashboard->notifications_all($id,"Re-Assigned",$_POST['others_proof_reader']);
								$this->Mdl_manager_dashboard->assignment_date_update($id);
								$i++;
							}
						}
					}
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			$success_msg='Assignment Assigned To Writer And Proof Reader.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
		redirect('Manager_dashboard');
	}
	public function re_assign_assignment($id)	
	{
		$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($id);
		$this->Mdl_manager_dashboard->re_assign_assignment($id,6);
		$this->Mdl_manager_dashboard->notifications($id,'Re-Assigned',$proof_reader_id[0]['proof_reader_id']);
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment Re-Assigned to Proof Reader.';
		$this->session->set_flashdata('success_msg',$success_msg);	
		redirect('Manager_dashboard');
	}
	public function re_assign_to_proof_reader_update()
	{
		if($_POS['online_proof_reader'] == null)
		{
			
		}else if($_POS['others_proof_reader'] == null)
		{
			
		}
	}
	public function active_assignment()
	{
		$user_data = $this->Mdl_manager_dashboard->active_assignment($_POST['id'],$_POST['status']);
		
		if($_POST['status'] == 1)
		{
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Accepted',$_POST['admin_id']);
		}else if($_POST['status'] == 2){
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$_POST['admin_id']);
		}
		$this->Mdl_manager_dashboard->assignment_date_update($_POST['id']);
	}
	public function active_assignment_list()
	{
		$ids = explode(",",$_POST['id']);
		$i = 0;
		$assignment_data = $this->Mdl_manager_dashboard->assign_to_ma_list();
		foreach($assignment_data as $data)
		{
			foreach($ids as $id){
				if($id == $data['assignment_id'])
				{
					$assignment = $this->Mdl_manager_dashboard->assignment($data['assignment_id']);
					$this->Mdl_manager_dashboard->active_assignment($data['assignment_id'],$_POST['status']);
					if($_POST['status'] == 1)
					{
						$this->Mdl_manager_dashboard->notifications($data['assignment_id'],'Accepted',$assignment[0]['admin_id']);
					}else if($_POST['status'] == 2){
						$this->Mdl_manager_dashboard->notifications($data['assignment_id'],'Rejected',$assignment[0]['admin_id']);
					}
					$this->Mdl_manager_dashboard->assignment_date_update($data['assignment_id']);
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			if($_POST['status'] == 1)
			{
				$success_msg='Selacted Assignment Accepted.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}else if($_POST['status'] == 2){
				$success_msg='Selacted Assigned Rejected.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}
		}
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
			$this->Mdl_manager_dashboard->notifications($_POST['id'],'Rejected',$_POST['admin_id']);
			$this->Mdl_manager_dashboard->assignment_date_update($_POST['id']);
		}
		echo $hours;
	}
	public function complete_assignment($id)
	{
		$assignment = $this->Mdl_manager_dashboard->assignment($id);
		
		$user_data = $this->Mdl_manager_dashboard->active_assignment($id,5);
		$this->Mdl_manager_dashboard->notifications($id,'Completed',$assignment[0]['admin_id']);
		$this->Mdl_manager_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment complete.';
		$this->session->set_flashdata('success_msg',$success_msg);
		redirect('Manager_dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['id']);
		$i = 0;
		foreach($ids as $id){
			$proof_reader = $this->Mdl_manager_dashboard->proof_reader_id($id);
			if($proof_reader[0]['status'] == 5)
			{
				$assignment = $this->Mdl_manager_dashboard->assignment($id);
				$user_data = $this->Mdl_manager_dashboard->active_assignment($id,5,$assignment[0]['admin_id']);
				$this->Mdl_manager_dashboard->notifications($id,'Completed',$assignment[0]['admin_id']);
				$this->Mdl_manager_dashboard->assignment_date_update($id);
				
				$i++;
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			$success_msg='Selacted Assignment Completed.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
	}
	
}
?>