<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Manager</small>
		<small>
			<form method="post" action="<?php echo base_url(); ?>index.php/Manager_dashboard/show_archived">
				<?php
					$roles = $this->session->manager_dashboard['roles'];
					$users = $this->session->manager_dashboard['users'];
				?>
				<ul class="nav navbar-nav">
					<li style="margin-right: 14px;" >
						<select class="dropdown-toggle" id="role" name="role" onchange="role_change()" required >
							<option value="" >Select one role</option>
							<option value="admin" <?php if($roles == 'admin'){ echo 'selected';}?> >Admin</option>
							<option value="manager" <?php if($roles == 'manager'){ echo 'selected';}?> >Manager</option>
							<option value="writer" <?php if($roles == 'writer'){ echo 'selected';}?> >Writer</option>
							<option value="proof_reader" <?php if($roles == 'proof_reader'){ echo 'selected';}?> >Proof Reader</option>
							<option value="help_desk" <?php if($roles == 'help_desk'){ echo 'selected';}?> >Help Desk</option>
						</select>
					</li>
					<li style="margin-right: 14px;">
						<?php 
						if(isset($roles))
						{
							$get_role_user = $this->Mdl_manager_dashboard->get_role_user($roles); 
						}else{
							$get_role_user = array();
						}
						$i = 0;
						?>
						<select class="dropdown-toggle" id="users" name="users" required >
							<option value="" >Select one user</option>
							<?php foreach($get_role_user as $user){
								if($i == 0){
									if($users == 'all')
									{ ?> 
										<option value="all" selected >All</option>
									<?php }else{ ?> 
										<option value="all" >All</option>
									<?php }
								}
								$i++;
								if($user['id'] == $users)
								{?> 
									<option value="<?php echo $user['id']; ?>" selected ><?php echo $user['name']; ?></option>
								<?php }else{ ?>
									<option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
								<?php } } ?>
						</select>
					</li>
					<li style="margin-right: 14px;">
						<button type="submit" class="btn btn-xs btn-primary" style="padding-right: 20px;padding-left: 20px;" >Filter</button>
					</li>
					<li style="margin-right: 14px;">
						<button type="button" class="btn btn-xs btn-primary" style="padding-right: 20px;padding-left: 20px;" onclick="dashboards()" >My Dashboard</button>
					</li>
				</ul>
			</form>
			<script>
				function role_change()
				{
					var role = document.getElementById("role").value;
					$.ajax({
						type:'POST',
						url: '<?php echo base_url(); ?>index.php/Admin_login/get_role_user',
						data:{ role : role },
						success:function(data){
							$('#users').html(data);
						}
					});
				}
			</script>
		</small>
      </h1>
      <ol class="breadcrumb">
		<li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<?php 
		$error_msg = $this->session->error_msg;
		$success_msg = $this->session->success_msg;
		if($error_msg != null){
			unset($_SESSION['error_msg']);
		?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> <?php echo $error_msg; ?></h4>
			</div>
		<?php }
		if($success_msg != null){
			unset($_SESSION['success_msg']);
		?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> <?php echo $success_msg; ?></h4>
			</div>
		<?php } 
		
		
		if($this->session->manager_dashboard['roles'] == 'admin')
		{
			$complete_count = 0;
			$denied_count = 0;
			$deleted_count = 0;
			
			foreach($datas as $count){
				if($count['status'] == 4 || $count['status'] == 5 || $count['status'] == 6){
					$manager_id_count = $this->Mdl_manager_dashboard->manager_id($count['id']);
					$proof_reader_id_count = $this->Mdl_manager_dashboard->proof_reader_id($count['id']);
					$writer_id_count = $this->Mdl_manager_dashboard->writer_id($count['id']);
						
					if($count['status'] == 5){
						$complete_count++;
					}else if($count['status'] == 4){
						$denied_count++;
					}else if($count['status'] == 6){
						$deleted_count++;
					}
						
				}
					
			}
			?>
				<div class="row">		
	  
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-green" onclick="onclick_box('Complete')" >
						<div class="inner">
						  <h3><?php echo $complete_count; ?></h3>
						  <p>Complete <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-check"></i>
						</div>
					  </div>
					</div>
						
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('Cancelled')" >
						<div class="inner">
						  <h3><?php echo $denied_count; ?></h3>
						  <p>Cancelled <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-trash-o"></i>
						</div>
					  </div>
					</div>
					
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('Deleted')" style="background-color: #a70f0f  !important;" >
						<div class="inner">
						  <h3><?php echo $deleted_count; ?></h3>
						  <p>Deleted <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-trash"></i>
						</div>
					  </div>
					</div>
					
				  </div>
				  <script>
					function onclick_box(id)
					{
						$('.actions_datatable').DataTable().columns( 10 ).search( id ).draw();
					}
				  </script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'manager')
		{
			$complete_count = 0;
			$cancelled_count = 0;
			foreach($datas as $data)
			{
				$manager_id = $this->Mdl_manager_dashboard->manager_id($data['id']);			
				if($data['status'] == 4 || $manager_id[0]['status'] == 5)
				{
					if($data['status'] == 4)
					{
						$cancelled_count++;
					}else if($manager_id[0]['status'] == 5)
					{
						$complete_count++;
					}
				}
			}
			?>
				<div class="row">		
	  
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-green" onclick="onclick_box('Complete')" >
						<div class="inner">
						  <h3><?php echo $complete_count; ?></h3>
						  <p>Complete <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-check"></i>
						</div>
					  </div>
					</div>
						
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('Cancelled')" >
						<div class="inner">
						  <h3><?php echo $cancelled_count; ?></h3>
						  <p>Cancelled <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-trash-o"></i>
						</div>
					  </div>
					</div>
					
				  </div>
				  
				  <script>
					function onclick_box(id)
					{
						$('.actions_datatable').DataTable().columns( 12 ).search( id ).draw();
					}
				  </script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'writer')
		{
			$complete_count = 0;
			$cancelled_count = 0;
			foreach($datas as $data)
			{
				if($data['status'] == 4)
				{
					$cancelled_count++;
				}else if($data['assign_to_ma_status'] == 5){
					$complete_count++;
				}
			}
			?>
			<div class="row">		
		
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-green" onclick="onclick_box('Complete')" >
					<div class="inner">
					  <h3><?php echo $complete_count; ?></h3>
					  <p>Complete <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-check"></i>
					</div>
				  </div>
				</div>
					
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Cancelled')" >
					<div class="inner">
					  <h3><?php echo $cancelled_count; ?></h3>
					  <p>Cancelled <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-trash-o"></i>
					</div>
				  </div>
				</div>
			
			</div>
		  <script>
			function onclick_box(id)
			{
				$('.actions_datatable').DataTable().columns( 13 ).search( id ).draw();
			}
		  </script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'proof_reader')
		{
			$complete_count = 0;
			$cancelled_count = 0;
			foreach($datas as $data)
			{
				if($data['status'] == 4)
				{
					$cancelled_count++;
				}else if($data['assign_to_ma_status'] == 5){
					$complete_count++;
				}
			}
			?>
				<div class="row">	
			
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-green" onclick="onclick_box('Complete')" >
						<div class="inner">
						  <h3><?php echo $complete_count; ?></h3>
						  <p>Complete <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-check"></i>
						</div>
					  </div>
					</div>
						
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('Cancelled')" >
						<div class="inner">
						  <h3><?php echo $cancelled_count; ?></h3>
						  <p>Cancelled <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-trash-o"></i>
						</div>
					  </div>
					</div>
				
				</div>
				<script>
					function onclick_box(id)
					{
						$('.actions_datatable').DataTable().columns( 14 ).search( id ).draw();
					}
				</script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'help_desk')
		{
			$complete_count = 0;
			$cancelled_count = 0;
			foreach($datas as $data)
			{
				if($data['status'] == 4)
				{
					$cancelled_count++;
				}else if($data['status'] == 5){
					$complete_count++;
				}
			}
			?>
				<div class="row">	
			
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-green" onclick="onclick_box('Complete')" >
						<div class="inner">
						  <h3><?php echo $complete_count; ?></h3>
						  <p>Complete <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-check"></i>
						</div>
					  </div>
					</div>
						
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('Cancelled')" >
						<div class="inner">
						  <h3><?php echo $cancelled_count; ?></h3>
						  <p>Cancelled <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-trash-o"></i>
						</div>
					  </div>
					</div>
				
				</div>
				<script>
					function onclick_box(id)
					{
						$('.actions_datatable').DataTable().columns( 11 ).search( id ).draw();
					}
				</script>
		<?php
		}
	
		?>
	
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">View Assignment Table</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<input type="checkbox" id="selectalls"/> &nbsp; Select This page &emsp;
						<a class="btn btn-default"  onclick="chk_assignment('list_clone')" style="background-color: #5f6560;color: #fff;" ><i class="fa fa-clone fa-lg"></i>&nbsp; Clone </a>
						
						<br/><br/>
						<script>
							function chk_assignment(modal)
							{
								var all_location_id = document.querySelectorAll('input[name="select_assignment_list[]"]:checked');
								var y = "";
								for(var x = 0, l = all_location_id.length; x < l;  x++)
								{
									if(x == 0)
									{
										y = all_location_id[x].value;
									}else{
										y += ","+all_location_id[x].value;
									}
								}
								$('#clone_list_id').val(y);
								$('#clone_reason').val("");
								
								if(all_location_id.length != 0)
								{
									$('#'+modal).modal();
								}
							}
							$(document).ready(function(){
								$('#selectalls').on('click',function(){
									if(this.checked){
										$('.select_assignment').each(function(){
											this.checked = true;
										});
									}else{
										 $('.select_assignment').each(function(){
											this.checked = false;
										});
									}
								});
								
								$('.select_assignment').on('click',function(){
									if($('.select_assignment:checked').length == $('.select_assignment').length){
										$('#selectalls').prop('checked',true);
									}else{
										$('#selectalls').prop('checked',false);
									}
								});
							});
						</script>
						<style>
						td a i {
							margin-left: 4px;
							margin-right: 4px;
						}
						</style>
						<table class="table table-bordered table-striped actions_datatable">
							<thead>
								<tr>
									<th>  </th>
									<th> ID </th>
									<th> Name </th>
									<th> Actions Time </th>
									<th> Client ID </th>
									<th> Admin </th>
									<th> HelpDesk </th>
									<th> Manager </th>
									<th> Writer </th>
									<th> Proof Reader </th>
									<th> Deadline </th>
									<th> Admin Status </th>
									<th> HelpDesk Status </th>
									<th> Manager Status </th>
									<th> Writer Status </th>
									<th> Proof Reader Status </th>
									<th> Actions </th>
								</tr>
							</thead>
							<tbody>
										
							<?php
							foreach($datas as $data) {
								
								$manager_id = $this->Mdl_manager_dashboard->manager_id($data['id']);
								
								$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
								$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);
								
								$created_name = $this->Mdl_manager_dashboard->user_get($data['created_id']);
								$manager_name = $this->Mdl_manager_dashboard->user_get($manager_id[0]['manager_id']);
								$proof_reader_name = $this->Mdl_manager_dashboard->user_get($proof_reader_id[0]['proof_reader_id']);
								$writer_name = $this->Mdl_manager_dashboard->user_get($writer_id[0]['writer_id']);
								$adminr_name = $this->Mdl_manager_dashboard->user_get($data['admin_id']);
							
							
							?>
								<tr>
									<td>
										<input type="checkbox" class="select_assignment" name="select_assignment_list[]" value="<?php echo $data['id']; ?>" >
									</td>
									<td>
										<?php echo $data['id']; ?>
									</td>
									<td><?php echo $data['name']; ?></td>
									<td><?php echo date('m/d/Y H:i:s', strtotime( $data['created_date'] )); ?></td>
									<td><?php echo $data['client_name']; ?></td>
									<td><?php echo $adminr_name[0]['name'];?></td>
									<td><?php 
										if($data['created_role'] == "help_desk"){
											echo $created_name[0]['name'];
										}
										?>
									</td>
									<td><?php 
										if($manager_name[0]['name'] != null)
										{
											echo $manager_name[0]['name'];
										}else{
											echo $created_name[0]['name'];
										}
									?></td>
									<td><?php echo $writer_name[0]['name'];?></td>
									<td><?php echo $proof_reader_name[0]['name'];?></td>
									
									<td><?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.date('H:i', strtotime( $data['deadline_time'] )); ?></td>
									<td>
										<?php
											if($data['status'] == 7){
												echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
											}else if($data['status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											}else if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($data['status'] == 0){
												echo '<span class="label" style="background-color: #78bce4 !important;" >Waiting for Approval</span>';
											}else if($data['status'] == 2){
												echo '<span class="label label-danger">Denied</span>';
											}else if($manager_id == null && $proof_reader_id == null && $writer_id == null){
												if($data['created_id'] == null)
												{
													echo '<span class="label label-success">New</span>';
												}else{
													echo '<span class="label label-primary">Approved</span>';
												}
											}else if($data['admin_status'] == 2){
												echo '<span class="label label-danger">Re-assigne</span>';
											}else if($data['admin_status'] == 1){
												echo '<span class="label label-warning">Assigned</span>';
											}
										?>
									</td>
									<td>
										<?php
										if($data['created_role'] == "help_desk"){
											if($data['status'] == 7){
												echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
											}else if($data['status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											}else if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($data['status'] == 0){
												echo '<span class="label" style="background-color: #78bce4 !important;" >Waiting for Approval</span>';
											}else if($data['status'] == 2){
												echo '<span class="label label-danger">Denied</span>';
											}else if($manager_id == null && $proof_reader_id == null && $writer_id == null){
												if($data['created_id'] == null)
												{
													echo '<span class="label label-success">New</span>';
												}else{
													echo '<span class="label label-primary">Approved</span>';
												}
											}else if($data['admin_status'] == 2){
												echo '<span class="label label-danger">Re-assigne</span>';
											}else if($data['admin_status'] == 1){
												echo '<span class="label label-warning">Assigned</span>';
											}
										}
										?>
									</td>
									<td>
										<?php
											if($data['status'] == 7){
													echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
											}else if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($manager_id[0]['status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											} 
										?>		
									</td>
									<td>
										<?php
											if($writer_id != null)
											{
												if($data['status'] == 7){
													echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
												}else if($data['status'] == 4){
													echo '<span class="label label-danger">Cancelled</span>';
												}else if($writer_id[0]['status'] == 1){
													echo '<span class="label label-primary">Accepted</span>';
												}else if($writer_id[0]['status'] == 2){
													echo '<span class="label label-danger">Rejected</span>';
												}else if($writer_id[0]['status'] == 5){
													echo '<span class="label label-success">Complete</span>';
												}else if($writer_id[0]['status'] == 6){
													echo '<span class="label label-danger">Re-Assigned</span>';
												}else if($writer_id[0]['status'] == 0){
													echo '<span class="label label-success">New</span>';
												}
											} 
										?>	
									</td>
									<td>
										<?php
											if($proof_reader_id != null)
											{
												if($data['status'] == 7){
													echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
												}else if($data['status'] == 4){
													echo '<span class="label label-danger">Cancelled</span>';
												}else if($proof_reader_id[0]['status'] == 1){
													echo '<span class="label label-primary">Accepted</span>';
												}else if($proof_reader_id[0]['status'] == 2){
													echo '<span class="label label-danger">Rejected</span>';
												}else if($proof_reader_id[0]['status'] == 5){
													echo '<span class="label label-success">Complete</span>';
												}else if($proof_reader_id[0]['status'] == 6){
													echo '<span class="label label-danger">Re-Assigned</span>';
												}else if($proof_reader_id[0]['status'] == 0){
													echo '<span class="label label-success">New</span>';
												}
											}
										?>
									</td>
									<td>
										<?php
											$logs_url = "myModal('".base_url()."index.php/Dashboard/logs_taske/".$data['id']."')";
											$duplicate_url = "myModal('".base_url()."index.php/Manager_dashboard/duplicate_taske/".$data['id']."')";
											
											echo '<a href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>';
											
											echo '<a onclick="'.$duplicate_url.'" style="color: #5f6560;"><i class="fa fa-clone fa-lg"></i></a>';
											
											if($data['created_role'] == "help_desk" || $adminr_name[0]['name'] != null || $writer_id != null || $proof_reader_id != null){
											
											?>
										<div class="btn-group">
										  <a data-toggle="dropdown" style="color: #2c893a;" > <i class="fa fa-commenting fa-lg"></i></a>
										  <ul class="dropdown-menu" role="menu" style="left: -161px; border-color: #000;top: -12px;" >
											<?php
											if($data['created_role'] == "help_desk")
											{
												if($data['created_role'] == "help_desk")
												{
													$role = 'Help Desk';
													$status = '5';
												}else{
													$role = 'Manager';
													$status = '2';
												}
												$created_url = "javascript:register_popup('".$data['created_id'].$status.$data['id']."','".ucfirst($created_name[0]['name'])."','".$data['created_id']."','".$role."','".$data['id']."');";
												echo'<li><a href="'.$created_url.'">'.ucfirst($created_name[0]['name']).' <small> ( '.$role.' ) </small></a></li>';
											}
											if($adminr_name[0]['name'] != null)
											{
												$admin_url = "javascript:register_popup('".$data['admin_id']."1".$data['id']."','".ucfirst($adminr_name[0]['name'])."','".$data['admin_id']."','Admin','".$data['id']."');";
												echo'<li><a href="'.$admin_url.'">'.ucfirst($adminr_name[0]['name']).' <small> ( Admin ) </small> </a></li>';
											}
											if($writer_id != null)
											{
												$writer_url = "javascript:register_popup('".$writer_name[0]['id']."3".$data['id']."', '".ucfirst($writer_name[0]['name'])."','".$writer_name[0]['id']."','Writer','".$data['id']."');";
												echo'<li><a href="'.$writer_url.'">'.ucfirst($writer_name[0]['name']).' <small> ( Writer ) </small> </a></li>';
											}
											if($proof_reader_id != null)
											{
												$proof_reader_url = "javascript:register_popup('".$proof_reader_name[0]['id']."4".$data['id']."', '".ucfirst($proof_reader_name[0]['name'])."','".$proof_reader_name[0]['id']."','Proof Reader','".$data['id']."');";
												echo'<li><a href="'.$proof_reader_url.'">'.ucfirst($proof_reader_name[0]['name']).' <small> ( Proof Reader ) </small></a></li>';
											}
											?>
										  </ul>
										</div>
										<?php
											}
											echo '<a onclick="'.$logs_url.'" style="color: #23527c;"><i class="fa fa-tasks fa-lg"></i></a>';
										?>
										
									</td>
								</tr>
							<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>  </th>
									<th> ID </th>
									<th> Name </th>
									<th> Actions Time </th>
									<th> Client ID </th>
									<th> Admin </th>
									<th> HelpDesk </th>
									<th> Manager </th>
									<th> Writer </th>
									<th> Proof Reader </th>
									<th> Deadline </th>
									<th> Admin Status </th>
									<th> HelpDesk Status </th>
									<th> Manager Status </th>
									<th> Writer Status </th>
									<th> Proof Reader Status </th>
									<th> Actions </th>
								</tr>
							</tfoot>
						</table>
						
						
							<script>
								function remove_re_assign(user)
								{
									if(user == 2)
									{
										document.getElementById('writer_id').value = "";
									}else if(user == 3)
									{
										document.getElementById('proof_reader_id').value = "";
									}
									
								}
								function myModal(url)
								{
									$('.myModal_content').load(url,function(){
										$('#myModal').modal({show:true});
									});
								}
								function assign_to_ma_status(id,status,admin_id) {
									$.ajax({
										type: "POST",
										url: '<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment',
										data: {
												id: id,
												status: status,
												admin_id: admin_id,
												}, 
										success: function(data){
											location.reload();
										},
										error: function(){
											console.log("data not found");
										}
									});
								}
							</script>
							<div class="modal fade" id="myModal">
								<div class="modal-dialog">
									<div class="modal-content myModal_content">
										
									</div>
								</div>
							</div>
							
							
							<div class="modal fade" id="list_clone">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/clone_assignment_list" enctype="multipart/form-data">
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
														<input type="hidden" name="clone_list_id" id="clone_list_id" value="">
														<textarea id="clone_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
												<button type="submit" class="btn btn-primary">Yes</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							
					</div>
				</div>
			</div>
		</div>
		<!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->