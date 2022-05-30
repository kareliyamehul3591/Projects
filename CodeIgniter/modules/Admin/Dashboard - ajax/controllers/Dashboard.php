<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_dashboard');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$online_managers = $this->Mdl_dashboard->online_managers();
			$others_managers = $this->Mdl_dashboard->others_managers();
			$assign_to_ma = $this->Mdl_dashboard->assign_to_ma();
			$assignment_data = $this->Mdl_dashboard->assignment();
			$data=array(
				'online_managers'=>$online_managers,
				'others_managers'=>$others_managers,
				'assign_to_ma'=>$assign_to_ma,
				'datas'=>$assignment_data,
				'main_content'=>'dashboard',
				'left_sidebar'=>'Dashboard',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function user_data_get()
	{
		$assignment_data = $this->Mdl_dashboard->assignment();
		$online_managers = $this->Mdl_dashboard->online_managers();
		$others_managers = $this->Mdl_dashboard->others_managers();
			
		$totalData = count($assignment_data);
		$requestData= $_REQUEST;
		$columns = array( 
			0 => 'id', 
			1 => 'name',
			2 => 'created_date',
			3 => 'client_name',
			7 => 'deadline_date',
		);
		$assignment_get_datas = $this->Mdl_dashboard->assignment_get_datas($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir'],$requestData['search']['value']);
		
		$totalFiltered = count($assignment_get_datas);
		
		$assignment_get_data = array_splice($assignment_get_datas,$requestData['start'],$requestData['length'],true);
		//$assignment_get_data = $this->Mdl_dashboard->assignment_get_data($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir'],$requestData['search']['value'],$requestData['start'],$requestData['length']);
		
		foreach($assignment_get_data as $data)
		{
			$manager_id = $this->Mdl_dashboard->manager_id($data['id']);
			$proof_reader_id = $this->Mdl_dashboard->proof_reader_id($data['id']);
			$writer_id = $this->Mdl_dashboard->writer_id($data['id']);
			
			$manager_name = $this->Mdl_dashboard->user_get($manager_id[0]['manager_id']);
			$proof_reader_name = $this->Mdl_dashboard->user_get($proof_reader_id[0]['proof_reader_id']);
			$writer_name = $this->Mdl_dashboard->user_get($writer_id[0]['writer_id']);
			
			if($data['status'] == 4){
				$status = '<span class="label label-danger">Cancelled</span>';
			}else if($data['status'] == 5){
				$status = '<span class="label label-success">Complete</span>';
			}else if($manager_id == null){
				$status = '<span class="label label-success">New</span>';
			}else if($manager_id[0]['status'] == 0 || $manager_id[0]['status'] == 1 || $manager_id[0]['status'] == 2 || $manager_id[0]['status'] == 5 || $manager_id[0]['status'] == 6){
				$status = '<span class="label label-warning">Assigned</span>';
			}
			
			if($manager_id != null)
			{
				if($data['status'] == 4)
				{
					$manager_status = '<span class="label label-danger">Cancelled</span>';
				}else if($manager_id[0]['status'] == 0){
					$manager_status = '<span class="label label-warning">Assigned</span>';
				}else if($manager_id[0]['status'] == 1){
					$manager_status = '<span class="label label-primary">Accepted</span>';
				}else if($manager_id[0]['status'] == 2){
					$manager_status = '<span class="label label-danger">Rejected</span>';
				}else if($manager_id[0]['status'] == 5){
					$manager_status = '<span class="label label-success">Complete</span>';
				}else if($manager_id[0]['status'] == 6){
					$manager_status = '<span class="label label-danger">Re-Assigned</span>';
				}
			}else if($data['status'] == 4){
				$manager_status = '<span class="label label-danger">Cancelled</span>';
			}else{
				$manager_status = '<span class="label label-success">New</span>';
			}	
			
			if($writer_id != null)
			{
				if($data['status'] == 4)
				{
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
			}else if($data['status'] == 4){
				$write_status = '<span class="label label-danger">Cancelled</span>';
			}else{
				$write_status = '<span class="label label-success">New</span>';
			}
			
			if($proof_reader_id != null)
			{
				if($data['status'] == 4)
				{
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
			}else if($data['status'] == 4){
				$proof_reader_status = '<span class="label label-danger">Cancelled</span>';
			}else{
				$proof_reader_status = '<span class="label label-success">New</span>';
			}	
			
			
			$view_url = "myModal('".base_url()."index.php/Dashboard/view_taske/".$data['id']."')";
			$taske_url = "myModal('".base_url()."index.php/Dashboard/taske_taske/".$data['id']."')";
			$edit_url = "myModal('".base_url()."index.php/Dashboard/edit_taske/".$data['id']."')";
			$assign_url = "myModal('".base_url()."index.php/Dashboard/assign_taske/".$data['id']."')";
			$re_assign_url = "myModal('".base_url()."index.php/Dashboard/re_assign_taske/".$data['id']."')";
									
			$actions = '<a class="btn btn-default" onclick="'.$taske_url.'" ><i class="fa fa-eye"></i> Tasks </a>';
			if($data['status'] == 4){
				$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
			}else if($data['status'] == 5){
				
				$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
				
			}else if($manager_id == null){
				$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
				
				$actions .= '<a class="btn btn-default" onclick="'.$edit_url.'" ><i class="fa fa-pencil-square-o"></i> Edit </a>';
				
				$actions .= '<a class="btn btn-default" onclick="'.$assign_url.'" ><i class="fa fa-pencil-square-o"></i> Assign </a>';
				
			}else if($manager_id != null){
				if($manager_id[0]['status'] == 1 || $manager_id[0]['status'] == 6 || $manager_id[0]['status'] == 0)
				{
					$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
				}else if($manager_id[0]['status'] == 2)
				{
					$actions .= '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
					
					$actions .= '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
				}else if($manager_id[0]['status'] == 5)
				{
					$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
					
					$actions .= '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
					
					$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Dashboard/complete_assignment/'.$data['id'].'" ><i class="fa fa-check"></i> Complete </a>';
				}
			}
			if($data['status'] != 4 && $data['status'] != 5)
			{
				$actions .= '<a class="btn btn-default" href="'.base_url().'index.php/Dashboard/assignment_delete/'.$data['id'].'" ><i class="fa fa-trash-o"></i> Cancel </a>';
			}

			$assignment_datas[] = array( 
				$data['id'], 
				$data['name'], 
				date('m/d/Y H:i:s', strtotime( $data['created_date'] )), 
				$data['client_name'], 
				$manager_name[0]['name'],
				$proof_reader_name[0]['name'],
				$writer_name[0]['name'],
				date('m/d/Y', strtotime( $data['deadline_date'] )).' '.date('H:i', strtotime( $data['deadline_time'] )),
				$status,
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
	
	
	public function view_taske($id)
	{
		$assignment_data = $this->Mdl_dashboard->assignments($id);
		$data = $assignment_data[0];
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
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			</div>
		
		<?php
	}
	public function taske_taske($id)
	{
		$taske_data = $this->Mdl_dashboard->taske_data($id);
		$assignment_data = $this->Mdl_dashboard->assignments($id);
		$data = $assignment_data[0];
		?>
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 id="myModalLabel" class="modal-title">Tasks</h4>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped datatable">
						<thead>
							<tr>
								<th> ID </th>
								<th> Assignment Name </th>
								<th> Title </th>
								<th> Keyword </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($taske_data as $t_data) { ?>
							<tr>
								<td><?php echo $t_data['id']; ?></td>
								<td><?php echo $data['name']; ?></td>
								<td><?php echo $t_data['title']; ?></td>
								<td><?php echo $t_data['keyword']; ?></td>
								<td><?php echo $t_data['action']; ?></td>
							
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Assignment Name </th>
								<th> Title </th>
								<th> Keyword </th>
								<th> Action </th>
							</tr>
						</tfoot>
					</table>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			</div>
			<script>
				$('.datatable').DataTable( {
					"order": [[ 0, "desc" ]]
				});
			</script>
		<?php
	}
	public function edit_taske($id)
	{
		$assignment_data = $this->Mdl_dashboard->assignments($id);
		$data = $assignment_data[0];
					$actions =	'<form method="post" class="login-form" action="'.base_url().'index.php/Dashboard/assignment_edit" enctype="multipart/form-data">
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
	public function assign_taske($id)
	{
		$assignment_data = $this->Mdl_dashboard->assignments($id);
		$data = $assignment_data[0];
		$online_managers = $this->Mdl_dashboard->online_managers();
		$others_managers = $this->Mdl_dashboard->others_managers();
		
			$actions = '<form method="post" class="login-form" action="'.base_url().'index.php/Dashboard/assign_to" enctype="multipart/form-data">
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
									<div class="col-xs-3">
										 Online Managers
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="online" id="online" >
											<option value="">Select Online Managers</option>';
											foreach($online_managers as $online)
											{
												$actions .= '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
											} 
										$actions .= '</select>
									</div>
								</div></br>
								<div class="row">
									<div class="col-xs-3">
										 Others Managers
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="others" id="others" >
											<option value="">Select Others Managers</option>';
											foreach($others_managers as $online){
												$actions .=  '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
											}
										$actions .= '</select>
									</div>
								</div></br>
								
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
		$assignment_data = $this->Mdl_dashboard->assignments($id);
		$data = $assignment_data[0];
		$online_managers = $this->Mdl_dashboard->online_managers();
		$others_managers = $this->Mdl_dashboard->others_managers();
		$actions =	'<form method="post" class="login-form" action="'.base_url().'index.php/Dashboard/re_assign_to" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Re-Assignment Assign To Manager</h4>
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
								<div class="col-xs-3">
									 Online Managers
								</div>
								<div class="col-xs-6">
									<select class="form-control" name="online" id="online" >
										<option value="">Select Online Managers</option>';
										foreach($online_managers as $online){ 
											$actions .='<option value="'.$online['id'].'" >'.$online['name'].'</option>';
										}
									$actions .= '</select>
								</div>
							</div></br>
							<div class="row">
								<div class="col-xs-3">
									 Others Managers
								</div>
								<div class="col-xs-6">
									<select class="form-control" name="others" id="others" >
										<option value="">Select Others Managers</option>';
										foreach($others_managers as $online){
											$actions .='<option value="'.$online['id'].'" >'.$online['name'].'</option>';
										}
									$actions .= '</select>
								</div>
							</div></br>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Re-Assign</button>
						</div>
					</form>';
		echo $actions;
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
			
				$ext=$this->Mdl_dashboard->get_file_extension($_FILES['image']['name']);
				$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
				
				if($ext == 'docx' || $ext == 'doc')
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
			$filess = array_filter($filess);
			$filename = array_filter($filename);
			$file = implode(",",$filess).','.implode(",",$filename);
		}else{
			$filess = explode(",",$_POST['files']);
			$filess = array_filter($filess);
			$file = implode(",",$filess);
		}
		$assignment_add=$this->Mdl_dashboard->assignment_edit($file);
		$this->Mdl_dashboard->assignment_date_update($_POST['ids']);
		redirect('Dashboard');
	}
	public function assignment_data($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$assignment_data = $this->Mdl_dashboard->assignment();
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_data',
				'left_sidebar'=>'Dashboard',
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
			$user_data = $this->Mdl_dashboard->active_assignment($id,4);
			$this->Mdl_dashboard->assignment_date_update($id);
			
			$manager_id_count = $this->Mdl_dashboard->manager_id($id);
			$proof_reader_id_count = $this->Mdl_dashboard->proof_reader_id($id);
			$writer_id_count = $this->Mdl_dashboard->writer_id($id);
			if($manager_id_count[0] != null)
			{
				$this->Mdl_dashboard->notifications_list($manager_id_count[0]['manager_id'],'Cancelled',$id);
			}
			if($proof_reader_id_count[0] != null)
			{
				$this->Mdl_dashboard->notifications_list($proof_reader_id_count[0]['proof_reader_id'],'Cancelled',$id);
			}
			if($writer_id_count[0] != null)
			{
				$this->Mdl_dashboard->notifications_list($writer_id_count[0]['writer_id'],'Cancelled',$id);
			}
			
			$success_msg='Assignment id Cancelled.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else if(isset($_POST['assignment_list_delete'])){
			
			$i = 0;
			foreach($_POST['select_assignment_list'] as $list)
			{
				$user_data = $this->Mdl_dashboard->active_assignment($list,4);
				$this->Mdl_dashboard->assignment_date_update($list);
				
				$manager_id_count = $this->Mdl_dashboard->manager_id($list);
				$proof_reader_id_count = $this->Mdl_dashboard->proof_reader_id($list);
				$writer_id_count = $this->Mdl_dashboard->writer_id($list);
				if($manager_id_count[0] != null)
				{
					$this->Mdl_dashboard->notifications_list($manager_id_count[0]['manager_id'],'Cancelled',$list);
				}
				if($proof_reader_id_count[0] != null)
				{
					$this->Mdl_dashboard->notifications_list($proof_reader_id_count[0]['proof_reader_id'],'Cancelled',$list);
				}
				if($writer_id_count[0] != null)
				{
					$this->Mdl_dashboard->notifications_list($writer_id_count[0]['writer_id'],'Cancelled',$list);
				}
			
				$i++;
			}
			if($i == 0)
			{
				$error_msg='Failed Please selecte valid Assigned.';
				$this->session->set_flashdata('error_msg',$error_msg);
			}else{
				$success_msg='Select Assignment is Cancelled.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}
		}
		redirect('Dashboard');
	}
	public function assign_to_list()
	{
		$ids = explode(",",$_POST['assignment_list_id']);
		$assignment_data = $this->Mdl_dashboard->assignment();
		$assign_to_ma = $this->Mdl_dashboard->assign_to_ma();
		$i = 0;
		foreach($assign_to_ma as $data_ma)
		{
			$assignment_reject_id[] = $data_ma['assignment_id'];
		}
		foreach($assignment_data as $data)
		{
			foreach($ids as $id){
				if($id == $data['id'] && $data['status'] == 1)
				{
					if(!in_array($data['id'],$assignment_reject_id))
					{
						$deadline_date = date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] ) );
						
						$datetime1 = new DateTime(date("Y-m-d H:i:s",strtotime( $data['created_date'] )));
						$datetime2 = new DateTime($deadline_date);
						
						$interval = $datetime1->diff($datetime2);
						$hours = $interval->h + ($interval->days*24);
						$minit = $interval->i + ($hours*60);
						
						$assign_hours = ($minit / 10)*9 / 60;
						$save_hours = ($minit / 10) / 60;
						
						if($_POST['online_list'] != null)
						{
							$this->Mdl_dashboard->assign_to_manager_list($_POST['online_list'],$data['id'],$deadline_date,$save_hours,$assign_hours);	
							$this->Mdl_dashboard->notifications_list($_POST['online_list'],'Assigned',$data['id']);	
							$this->Mdl_dashboard->assignment_date_update($data['id']);
							$i++;
						}else if($_POST['others_list'] != null){
							$this->Mdl_dashboard->assign_to_manager_list($_POST['others_list'],$data['id'],$deadline_date,$save_hours,$assign_hours);	
							$this->Mdl_dashboard->notifications_list($_POST['others_list'],'Assigned',$data['id']);	
							$this->Mdl_dashboard->assignment_date_update($data['id']);
							$i++;
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
			$success_msg='Assignment Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
		redirect('Dashboard');
	}
	public function re_assign_to_list()
	{
		$ids = explode(",",$_POST['re_assignment_list_id']);
		$assignment_data = $this->Mdl_dashboard->assignment();
		$assign_to_ma = $this->Mdl_dashboard->assign_to_ma();
		$i = 0;
		foreach($assign_to_ma as $data_ma)
		{
			if($data_ma['status'] == 2)
			{
				$assignment_reject_id[] = $data_ma['assignment_id'];
			}
		}
		foreach($assignment_data as $data)
		{
			foreach($ids as $id){
				if($id == $data['id'] && $data['status'] == 1)
				{
					if(in_array($data['id'],$assignment_reject_id))
					{
						$deadline_date = date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] ) );
						
						$datetime1 = new DateTime(date("Y-m-d H:i:s",strtotime( $data['created_date'] )));
						$datetime2 = new DateTime($deadline_date);
						
						$interval = $datetime1->diff($datetime2);
						$hours = $interval->h + ($interval->days*24);
						$minit = $interval->i + ($hours*60);
						
						$assign_hours = ($minit / 10)*9 / 60;
						$save_hours = ($minit / 10) / 60;
						
						if($_POST['online_list'] != null)
						{
							$this->Mdl_dashboard->assign_to_manager_list($_POST['online_list'],$data['id'],$deadline_date,$save_hours,$assign_hours);	
							$this->Mdl_dashboard->notifications_list($_POST['online_list'],'Re-Assigned',$data['id']);	
							$this->Mdl_dashboard->assignment_date_update($data['id']);
							$i++;
						}else if($_POST['others_list'] != null){
							$this->Mdl_dashboard->assign_to_manager_list($_POST['others_list'],$data['id'],$deadline_date,$save_hours,$assign_hours);	
							$this->Mdl_dashboard->notifications_list($_POST['others_list'],'Re-Assigned',$data['id']);	
							$this->Mdl_dashboard->assignment_date_update($data['id']);
							$i++;
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
			$success_msg='Re-Assignment Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
		redirect('Dashboard');
	}
	public function assign_to()
	{
		if($_POST['online'] != null)
		{
			$this->Mdl_dashboard->assign_to_manager($_POST['online']);
			$this->Mdl_dashboard->notifications($_POST['online'],'Assigned');
			$this->Mdl_dashboard->assignment_date_update($_POST['assignment_id']);
			
			$success_msg='Assignment Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else if($_POST['others'] != null){
			
			$this->Mdl_dashboard->assign_to_manager($_POST['others']);
			$this->Mdl_dashboard->notifications($_POST['others'],'Assigned');
			$this->Mdl_dashboard->assignment_date_update($_POST['assignment_id']);
			
			$success_msg='Assignment Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else {
			$error_msg='Failed Please enter a valid manager.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}
		redirect('Dashboard');
	}
	public function re_assign_to()
	{
		if($_POST['online'] != null)
		{
			$this->Mdl_dashboard->re_assign_to_manager($_POST['online']);
			$this->Mdl_dashboard->notifications($_POST['online'],'Re-Assigned');
			$this->Mdl_dashboard->assignment_date_update($_POST['assignment_id']);
			
			$success_msg='Assignment Re-Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else if($_POST['others'] != null){
			
			$this->Mdl_dashboard->re_assign_to_manager($_POST['others']);
			$this->Mdl_dashboard->notifications($_POST['others'],'Re-Assigned');
			$this->Mdl_dashboard->assignment_date_update($_POST['assignment_id']);
			
			$success_msg='Assignment Re-Assigned To Manager.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else {
			$error_msg='Failed Please enter a valid manager.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}
		redirect('Dashboard');
	}
	public function assignment_view($id)
	{		
		if($this->session->Admindetail['admin'] == 1)
		{			
			$assignment_datas = $this->Mdl_dashboard->assignment();
			
			foreach($assignment_datas as $key => $assign_to_man){	
				if($assign_to_man['id'] == $id){	
					$assignment_data[0] = $assign_to_man;
				}				
			}
			
			$assignment_final_data = $this->Mdl_dashboard->assignment_final_data($id);
			$data=array(				
				'datas'=>$assignment_data,
				'assignment_final_data'=>$assignment_final_data,	
				'main_content'=>'assignment_view',		
				'left_sidebar'=>'Dashboard',
			);
			
			$this->load->view('admin_template/template',$data);
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
		$assignment_final_data = $this->Mdl_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['file']);
		for($i = 0; $i <= $count; $i++)	
		{		
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];	
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];		
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];	
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];	
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];	
			
			$ext=$this->Mdl_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);			
			if(preg_match('/\s/',$_FILES['image']['name']) != 0){
				$error_msg='in Assigned File space is not Require.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Dashboard/assignment_view/'.$id);
			}
			if($ext == 'docx' || $ext == 'doc')		
			{
				$filename[$i]='';
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
		$this->Mdl_dashboard->assignment_image_update($id,$data);	
		$this->Mdl_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment data save.';
		$this->session->set_flashdata('success_msg',$success_msg);
		redirect('Dashboard');	
	}
	public function re_assign_assignment($id)	
	{
		$manager_id = $this->Mdl_dashboard->manager_id($id);		
		$this->Mdl_dashboard->re_assign_assignment($id,6);
		$this->Mdl_dashboard->notifications($id,'Re-Assigned',$manager_id[0]['manager_id']);
		$this->Mdl_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment Re-Assigned to Writer.';
		$this->session->set_flashdata('success_msg',$success_msg);	
		redirect('Dashboard');
	}
	public function complete_assignment($id)
	{
		$manager_id = $this->Mdl_dashboard->manager_id($id);
		$proof_reader_id = $this->Mdl_dashboard->proof_reader_id($id);
		$writer_id = $this->Mdl_dashboard->writer_id($id);
		
		$this->Mdl_dashboard->notifications_list($manager_id[0]['manager_id'],'Completed',$id);
		$this->Mdl_dashboard->notifications_list($proof_reader_id[0]['proof_reader_id'],'Completed',$id);
		$this->Mdl_dashboard->notifications_list($writer_id[0]['writer_id'],'Completed',$id);
		
		$user_data = $this->Mdl_dashboard->active_assignment($id,5);
		$success_msg='Selacted Assignment complete.';
		$this->session->set_flashdata('success_msg',$success_msg);
		redirect('Dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['id']);
		$i = 0;
		foreach($ids as $id){
			$manager_id = $this->Mdl_dashboard->manager_id($id);
			if($manager_id[0]['status'] == 5)
			{
				$proof_reader_id = $this->Mdl_dashboard->proof_reader_id($id);
				$writer_id = $this->Mdl_dashboard->writer_id($id);
				
				$this->Mdl_dashboard->notifications_list($manager_id[0]['manager_id'],'Completed',$id);
				$this->Mdl_dashboard->notifications_list($proof_reader_id[0]['proof_reader_id'],'Completed',$id);
				$this->Mdl_dashboard->notifications_list($writer_id[0]['writer_id'],'Completed',$id);
				
				$user_data = $this->Mdl_dashboard->active_assignment($id,5);
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