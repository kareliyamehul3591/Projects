<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Manager</small>
		<small>
			<form method="post" action="<?php echo base_url(); ?>index.php/Manager_dashboard">
				
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
		
		if($this->session->manager_dashboard['roles'] == 'admin' || $this->session->manager_dashboard['roles'] == 'help_desk')
		{
			$new_count = 0;
			$approved_count = 0;
			$assigned_count = 0;
			$re_assigned_count = 0;
			$qc_failed = 0;
			$denied_count = 0;
			$waiting_approval = 0;
			foreach($datas as $count){
				if($count['status'] != 4 && $count['status'] != 5 && $count['status'] != 6){
					$manager_id_count = $this->Mdl_manager_dashboard->manager_id($count['id']);
					$proof_reader_id_count = $this->Mdl_manager_dashboard->proof_reader_id($count['id']);
					$writer_id_count = $this->Mdl_manager_dashboard->writer_id($count['id']);
										
					if($count['status'] == 7){
						$qc_failed++;
					}else if($count['status'] == 0){
						$waiting_approval++;
					}else if($count['status'] == 2){
						$denied_count++;
					}else if($manager_id_count == null && $proof_reader_id_count == null && $writer_id_count == null){
						if($count['created_id'] == null)
						{
							$new_count++;
						}else{
							$approved_count++;
						}
					}else if($count['admin_status'] == 2){
						$re_assigned_count++;
					}else if($count['admin_status'] == 1){
						$assigned_count++;
					}
				}
					
			}
			?>
			 <div class="row">
				<?php if($this->session->manager_dashboard['roles'] != 'help_desk'){?>
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-aqua" onclick="onclick_box('New')" >
						<div class="inner">
							<h3><?php echo $new_count; ?></h3>
						  <p>New <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-eye"></i>
						</div>
					  </div>
					</div>	
				<?php } ?>
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Waiting Approval')" style="background-color: #78bce4 !important;" >
					<div class="inner">
						<h3><?php echo $waiting_approval; ?></h3>
					  <p>Waiting Approval <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-clock-o"></i>
					</div>
				  </div>
				</div>	
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Approved')" style="background-color: #e686f5 !important;"  >
					<div class="inner">
					  <h3><?php echo $approved_count;?></h3>
					  <p>Approved <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-check-square"></i>
					</div>
				  </div>
				</div>	
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-yellow" onclick="onclick_box('Assigned')" >
					<div class="inner">
					  <h3><?php echo $assigned_count; ?></h3>
					  <p>Assigned <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-id-badge"></i>
					</div>
				  </div>
				</div> 	

				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Re-assigne')" style="background-color: #f61d9f !important;" >
					<div class="inner">
					  <h3><?php echo $re_assigned_count; ?></h3>
					  <p>Re-Assigned <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-retweet"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('QC_Failed')" style="background-color: #f90b5f  !important;">
					<div class="inner">
					  <h3><?php echo $qc_failed; ?></h3>
					  <p>QC_Failed <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-crop"></i>
					</div>
				  </div>
				</div>		
					
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Denied')" >
					<div class="inner">
					  <h3><?php echo $denied_count; ?></h3>
					  <p>Denied <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-trash-o"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <a class="small-box bg-red" href="<?php echo base_url(); ?>index.php/Manager_dashboard/show_archived" style="background-color: #8a838c  !important;">
					<div class="inner">
					&ensp;&ensp;
					   <p>Show <br/>Archived <br/><h6>(Completed, Cancelled, Deleted)</h6></p>
					</div>
					<div class="icon">
					  <i class="fa fa-inbox"></i>
					</div>
				  </a>
				</div>
				
			</div>
			<script>
				function onclick_box(id)
				{
					$('.actions_datatable').DataTable().columns( 11 ).search( id ).draw();
				}
			</script>
		<?php	
		}else if($this->session->manager_dashboard['roles'] == 'manager')
		{
			$new_count = 0;
			$approved_count = 0;
			$accepted_count = 0;
			$assigned_count = 0;
			$re_assigned_count = 0;
			$qc_failed = 0;
			$rejected_failed = 0;
			$denied_count = 0;
			$waiting_approval = 0;
			$complete_approval = 0;
			
			foreach($datas as $data)
			{
				$manager_id = $this->Mdl_manager_dashboard->manager_id($data['id']);
				$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
				$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);	
								
				if($manager_id != null)
				{
					if($data['status'] == 7)
					{
						$qc_failed++;
					}else if($manager_id[0]['status'] == 1)
					{
						if($manager_id[0]['manager_status'] == 2)
						{
							$re_assigned_count++;
						}else if($manager_id[0]['manager_status'] == 1){
							$assigned_count++;
						}else{
							$accepted_count++;
						}			
					}else if($manager_id[0]['status'] == 2){
						$rejected_failed++;
					}else if($manager_id[0]['status'] == 5){
						$complete_approval++;
					}else if($manager_id[0]['status'] == 6){
						$re_assigned_count++;
					}else if($manager_id[0]['status'] == 0){
						$new_count++;
					}
				}else if($data['status'] == 0){
					if($data['created_role'] == "manager"){
						$waiting_approval++;
					}
				}else if($data['status'] == 2){
					if($data['created_role'] == "manager"){
						$denied_count++;
					}
				}else if($data['status'] == 1){
					if($data['created_role'] == "manager"){
						$approved_count++;
					}
				}
			}
			?>
			<div class="row">		
	  
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('New')" >
					<div class="inner">
						<h3><?php echo $new_count; ?></h3>
					  <!--<h3>53<sup style="font-size: 20px">%</sup></h3>-->
					  <p>New <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-eye"></i>
					</div>
				  </div>
				</div>	

				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Waiting Approval')" style="background-color: #78bce4 !important;" >
					<div class="inner">
						<h3><?php echo $waiting_approval; ?></h3>
					  <p>Waiting Approval <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-clock-o"></i>
					</div>
				  </div>
				</div>	
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Approved')" style="background-color: #e686f5 !important;"  >
					<div class="inner">
					  <h3><?php echo $approved_count;?></h3>
					  <p>Approved <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-check-square"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Accepted')" style="background-color: #7169e4 !important;"  >
					<div class="inner">
					  <h3><?php echo $accepted_count;?></h3>
					  <p>Accepted <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-thumbs-up"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-yellow" onclick="onclick_box('Assigned')" >
					<div class="inner">
					  <h3><?php echo $assigned_count; ?></h3>
					  <p>Assigned <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-id-badge"></i>
					</div>
				  </div>
				</div> 	

				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Re-assigne')" style="background-color: #f61d9f !important;" >
					<div class="inner">
					  <h3><?php echo $re_assigned_count; ?></h3>
					  <p>Re-Assigned <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-retweet"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('QC_Failed')" style="background-color: #f90b5f  !important;">
					<div class="inner">
					  <h3><?php echo $qc_failed; ?></h3>
					  <p>QC_Failed <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-crop"></i>
					</div>
				  </div>
				</div>	
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Denied')" >
					<div class="inner">
					  <h3><?php echo $denied_count; ?></h3>
					  <p>Denied <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-trash-o"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <a class="small-box bg-red" href="<?php echo base_url(); ?>index.php/Manager_dashboard/show_archived" style="background-color: #8a838c  !important;">
					<div class="inner">
					&ensp;&ensp;
					   <p>Show <br/>Archived <br/><h6>(Completed, Cancelled, Deleted)</h6></p>
					</div>
					<div class="icon">
					  <i class="fa fa-inbox"></i>
					</div>
				  </a>
				</div>
			  </div>
			<script>
				function onclick_box(id)
				{
					$('.actions_datatable').DataTable().columns( 13 ).search( id ).draw();
				}
			</script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'writer')
		{
			$new_count = 0;
			$accepted_count = 0;
			$qc_failed = 0;
			$rejected_count = 0;
			foreach($datas as $data)
			{
				$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);
				if($writer_id != null)
				{
					if($data['status'] == 7)
					{
						$qc_failed++;
					}else if($writer_id[0]['status'] == 1){
						$accepted_count++;
					}else if($writer_id[0]['status'] == 2){
						$rejected_count++;
					}else if($writer_id[0]['status'] == 0){
						$new_count++;
					}
				}
			}
			?>
				<div class="row">		
		
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-aqua" onclick="onclick_box('New')" >
						<div class="inner">
							<h3><?php echo $new_count; ?></h3>
						  <p>New <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-eye"></i>
						</div>
					  </div>
					</div>		
					
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-aqua" onclick="onclick_box('Accepted')" style="background-color: #7169e4 !important;"  >
						<div class="inner">
						  <h3><?php echo $accepted_count;?></h3>
						  <p>Accepted <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-thumbs-up"></i>
						</div>
					  </div>
					</div>
					
					<div class="col-lg-2 col-xs-6">
					  <div class="small-box bg-red" onclick="onclick_box('QC_Failed')" style="background-color: #f90b5f  !important;">
						<div class="inner">
						  <h3><?php echo $qc_failed; ?></h3>
						  <p>QC_Failed <br/> Assignment</p>
						</div>
						<div class="icon">
						  <i class="fa fa-crop"></i>
						</div>
					  </div>
					</div>	
					
					<div class="col-lg-2 col-xs-6">
					  <a class="small-box bg-red" href="<?php echo base_url(); ?>index.php/Manager_dashboard/show_archived" style="background-color: #8a838c  !important;">
						<div class="inner">
						&ensp;&ensp;
						   <p>Show <br/>Archived <br/><h6>(Completed, Cancelled, Deleted)</h6></p>
						</div>
						<div class="icon">
						  <i class="fa fa-inbox"></i>
						</div>
					  </a>
					</div>
				
				</div>
			  <script>
				function onclick_box(id)
				{
					$('.actions_datatable').DataTable().columns( 14 ).search( id ).draw();
				}
			  </script>
		<?php
		}else if($this->session->manager_dashboard['roles'] == 'proof_reader')
		{
			$new_count = 0;
			$accepted_count = 0;
			$re_assigned_count = 0;
			$qc_failed = 0;
			$rejected_count = 0;
			foreach($datas as $data)
			{
				$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
				if($proof_reader_id != null)
				{
					if($data['status'] == 7){
						$qc_failed++;
					}else if($proof_reader_id[0]['status'] == 1){
						if($proof_reader_id[0]['proof_reader_status'] != null)
						{
							$re_assigned_count++;
						}else{
							$accepted_count++;
						}
					}else if($proof_reader_id[0]['status'] == 2){
						$rejected_count++;
					}else if($proof_reader_id[0]['status'] == 6){
						$re_assigned_count++;
					}else if($proof_reader_id[0]['status'] == 0){
						$new_count++;
					}
				}
			}
			?>
			<div class="row">	
			
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('New')" >
					<div class="inner">
						<h3><?php echo $new_count; ?></h3>
					  <p>New <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-eye"></i>
					</div>
				  </div>
				</div>		
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-aqua" onclick="onclick_box('Accepted')" style="background-color: #7169e4 !important;"  >
					<div class="inner">
					  <h3><?php echo $accepted_count;?></h3>
					  <p>Accepted <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-thumbs-up"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('Re-assigne')" style="background-color: #f61d9f !important;" >
					<div class="inner">
					  <h3><?php echo $re_assigned_count; ?></h3>
					  <p>Re-Assigned <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-retweet"></i>
					</div>
				  </div>
				</div>
				
				<div class="col-lg-2 col-xs-6">
				  <div class="small-box bg-red" onclick="onclick_box('QC_Failed')" style="background-color: #f90b5f  !important;">
					<div class="inner">
					  <h3><?php echo $qc_failed; ?></h3>
					  <p>QC_Failed <br/> Assignment</p>
					</div>
					<div class="icon">
					  <i class="fa fa-crop"></i>
					</div>
				  </div>
				</div>	
				
				<div class="col-lg-2 col-xs-6">
				  <a class="small-box bg-red" href="<?php echo base_url(); ?>index.php/Manager_dashboard/show_archived" style="background-color: #8a838c  !important;">
					<div class="inner">
					&ensp;&ensp;
					   <p>Show <br/>Archived <br/><h6>(Completed, Cancelled, Deleted)</h6></p>
					</div>
					<div class="icon">
					  <i class="fa fa-inbox"></i>
					</div>
				  </a>
				</div>
			
			</div>
			<script>
				function onclick_box(id)
				{
					$('.actions_datatable').DataTable().columns( 15 ).search( id ).draw();
				}
			</script>
		<?php } ?>
	  
		<!-- Main row -->
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
						<a class="btn btn-default"  onclick="chk_assignment('list_accept')" style="background-color: #7169e4;color: #fff;" ><i class="fa fa-thumbs-up fa-lg"></i>&nbsp; Accept </a>
						<a class="btn btn-default"  onclick="chk_assignment('list_reject')" style="background-color: #c436da;color: #fff;" ><i class="fa fa-times-circle fa-lg"></i>&nbsp; Reject </a>
						<a class="btn btn-default"  onclick="chk_assignment('list_complete')" style="background-color: #16ec7b;color: #fff;" ><i class="fa fa-check fa-lg"></i>&nbsp; Complete </a>
						
						<a class="btn btn-default"  onclick="chk_assignment('list_assign')" style="background-color: #f39c12;color: #fff;" ><i class="fa fa-id-badge fa-lg"></i>&nbsp; Assign </a>
						<a class="btn btn-default"  onclick="chk_assignment('list_re_assign')" style="background-color: #f61d9f;color: #fff;" ><i class="fa fa-retweet fa-lg"></i>&nbsp; Re-Assign </a>
						
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
								$('#complete_list_id').val(y);
								$('#accept_list_id').val(y);
								$('#reject_list_id').val(y);
								$('#assignment_list_id').val(y);
								$('#re_assignment_list_id').val(y);
								
								
								$('#clone_reason').val("");
								$('#accept_reason').val("");
								$('#reject_reason').val("");
								$('#complete_reason').val("");
								$('#assign_reason').val("");
								$('#assign_writer_id').val("");
								$('#assign_proof_reader_id').val("");
								$('#re_assign_reason').val("");
								$('#re_assign_reasons').val("");
								$('#re_assign_writer_id').val("");
								$('#re_assignment_proof_reader_id').val("");
								
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
								
								//if($data['status'] != 4 && $data['status'] != 5 && $data['status'] != 6 && $manager_id[0]['status'] != 5){
									
								
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
											if($manager_id != null)
											{
												if($data['status'] == 7){
													echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
												}else if($manager_id[0]['status'] == 1){
													if($manager_id[0]['manager_status'] == 2)
													{
														echo '<span class="label label-danger">Re-assigne</span>';
													}else if($manager_id[0]['manager_status'] == 1){
														echo '<span class="label label-warning">Assigned</span>';
													}else{
														echo '<span class="label label-primary">Accepted</span>';
													}
												}else if($manager_id[0]['status'] == 2){
													echo '<span class="label label-danger">Rejected</span>';
												}else if($manager_id[0]['status'] == 5){
													echo '<span class="label label-success">Complete</span>';
												}else if($manager_id[0]['status'] == 6){
													echo '<span class="label label-danger">Re-Assigned</span>';
												}else if($manager_id[0]['status'] == 0){
													echo '<span class="label label-success">New</span>';
												}
											}else if($data['status'] == 0){
												if($data['created_role'] == "manager"){
													echo '<span class="label" style="background-color: #78bce4 !important;" >Waiting for Approval</span>';
												}
											}else if($data['status'] == 2){
												if($data['created_role'] == "manager"){
													echo '<span class="label label-danger">Denied</span>';
												}
											}else if($data['status'] == 1){
												if($data['created_role'] == "manager"){
													echo '<span class="label label-primary">Approved</span>';
												}
											}
										?>		
									</td>
									<td>
										<?php
											if($writer_id != null)
											{
												if($data['status'] == 7){
													echo '<span class="label" style="background-color: #f90b5f !important;" >QC_Failed</span>';
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
												}else if($proof_reader_id[0]['status'] == 1){
													if($proof_reader_id[0]['proof_reader_status'] != null)
													{
														echo '<span class="label label-danger">Re-Assigned</span>';
													}else{
														echo '<span class="label label-primary">Accepted</span>';
													}
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
										$view_url = "myModal('".base_url()."index.php/Manager_dashboard/view_taske/".$data['id']."')";
										$assign_url = "myModal('".base_url()."index.php/Manager_dashboard/assign_taske/".$data['id']."')";
										$re_assign_url = "myModal('".base_url()."index.php/Manager_dashboard/re_assign_taske/".$data['id']."')";
										
										$accept_url = "myModal('".base_url()."index.php/Manager_dashboard/accept_taske/".$data['id']."')";
										$reject_url = "myModal('".base_url()."index.php/Manager_dashboard/reject_taske/".$data['id']."')";
										$complete_url = "myModal('".base_url()."index.php/Manager_dashboard/complete_taske/".$data['id']."')";
										$logs_url = "myModal('".base_url()."index.php/Dashboard/logs_taske/".$data['id']."')";
										$duplicate_url = "myModal('".base_url()."index.php/Manager_dashboard/duplicate_taske/".$data['id']."')";
										
										echo '<a href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>';
										
										if($this->session->manager_dashboard == null)
										{
											$roles = "manager";
											$users = $this->session->Admindetail['id'];
										}
										if($roles == 'manager' && $users == $this->session->Admindetail['id'])
										{
											if($manager_id != null)
											{
												if($data['status'] == 7)
												{
													echo '<a onclick="'.$re_assign_url.'" style="color: #f61d9f;"><i class="fa fa-retweet fa-lg"></i></a>';
												}else if($manager_id[0]['status'] == 0){
													
													echo '<a onclick="'.$accept_url.'" style="color: #7169e4;"><i class="fa fa-thumbs-up fa-lg"></i></a>';
												
													echo '<a onclick="'.$reject_url.'" style="color: #c436da;"><i class="fa fa-times-circle fa-lg"></i></a>';
												}else if($manager_id[0]['status'] == 1)
												{
													if($writer_id == null && $proof_reader_id == null)
													{
														echo '<a onclick="'.$assign_url.'" style="color: #f39c12;"><i class="fa fa-id-badge fa-lg"></i></a>';
													}else{
														echo '<a onclick="'.$re_assign_url.'" style="color: #f61d9f;"><i class="fa fa-retweet fa-lg"></i></a>';
													}
													echo '<a onclick="'.$complete_url.'" style="color: #16ec7b;"><i class="fa fa-check fa-lg"></i></a>'; 
												}
											}else if($data['status'] == 0){
												if($data['created_role'] == "manager"){
													
												}
											}else if($data['status'] == 2){
												if($data['created_role'] == "manager"){
													echo '<a href="'.base_url().'index.php/Manager_assignment/edit_assignment/'.$data['id'].'" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>';
												}
											}
										}
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
										<?php }
										echo '<a onclick="'.$logs_url.'" style="color: #23527c;"><i class="fa fa-tasks fa-lg"></i></a>';
										?>
									</td>
								</tr>
							<?php }  ?>
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
							<div class="modal fade" id="list_accept">
								<div class="modal-dialog">
									<div class="modal-content ">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment_list" enctype="multipart/form-data">
											<div class="modal-header">
												<a class="close" data-dismiss="modal">&times;</a>
												<h4 id="myModalLabel" class="modal-title">
													<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> </a>
													Are you sure you want to Accept ? 
												</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12">
														<input type="hidden" name="accept_list_id" id="accept_list_id" value="">
														<input type="hidden" name="status" id="status" value="1">
														<textarea id="accept_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
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
							<div class="modal fade" id="list_reject">
								<div class="modal-dialog">
									<div class="modal-content ">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment_list" enctype="multipart/form-data">
											<div class="modal-header">
												<a class="close" data-dismiss="modal">&times;</a>
												<h4 id="myModalLabel" class="modal-title">
													<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> </a>
													Are you sure you want to Reject ? 
												</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12">
														<input type="hidden" name="reject_list_id" id="reject_list_id" value="">
														<input type="hidden" name="status" id="status" value="2">
														<textarea id="reject_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" required ></textarea>
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
							<div class="modal fade" id="list_complete">
								<div class="modal-dialog">
									<div class="modal-content ">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/complete_assignment_list" enctype="multipart/form-data">
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
														<input type="hidden" name="complete_list_id" id="complete_list_id" value="">
														<textarea id="complete_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
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
							<div class="modal fade" id="list_assign">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/assign_to_list" enctype="multipart/form-data">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">Assignment Assign To Writer And Proof Reader</h4>
											</div>
											<div class="modal-body">
											<input type="hidden" name="assignment_list_id" id="assignment_list_id" value="">
												
												<?php 
												$all_writers = $this->Mdl_manager_dashboard->all_writers();
												$all_proof_readers = $this->Mdl_manager_dashboard->all_proof_readers();
												?>
												<div class="row">
													<div class="col-xs-8">
														<select class="form-control" name="writer_id" id="assign_writer_id" >
															<option value="">Assign One writer</option>
														<?php foreach($all_writers as $online){
																echo '<option value="'.$online['id'].'" >'.$online['name'].'</option>';
															} ?>
														</select>
													</div>
												</div></br>
								
												<div class="row">
													<div class="col-xs-8">
														<select class="form-control" name="proof_reader_id" id="assign_proof_reader_id" >
															<option value="">Assign One Proof Reader</option>';
														<?php foreach($all_proof_readers as $all_manager){
															echo '<option value="'.$all_manager['id'].'">'.$all_manager['name'].'</option>';
															} ?>
														</select>
													</div>
												</div></br>
												
												<div class="row">
													<div class="col-xs-12">
														<textarea id="assign_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" ></textarea>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Assign</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade" id="list_re_assign">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/re_assign_to_list" enctype="multipart/form-data">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">Re-Assignment Assign To Writer And Proof Reader</h4>
											</div>
											<div class="modal-body">
											<?php
												$all_writers = $this->Mdl_manager_dashboard->all_writers();
												$all_proof_readers = $this->Mdl_manager_dashboard->all_proof_readers();
											?>
											<input type="hidden" name="re_assignment_list_id" id="re_assignment_list_id" value="">
											<div class="row">
												<div class="col-xs-8">
													<select class="form-control" name="writer_id" id="re_assign_writer_id" >
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
											</div></br>
											<div class="row">
												<div class="col-xs-8">
														<select class="form-control" name="proof_reader_id" id="re_assignment_proof_reader_id" >
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
											</div></br>
											<div class="row">
												<div class="col-xs-8">
													<?php $qc_failed = $this->Mdl_manager_dashboard->dropdowns('dropdowns_qc_failed'); ?>
													<select class="form-control" name="reasons" id="re_assign_reasons" required >
														<option value="">Choose one reason</option>
														<?php foreach($qc_failed as $qc_faileda){ ?>
															<option value="<?php echo $qc_faileda['name'];?>"><?php echo $qc_faileda['name'];?></option>
														<?php } ?>
													</select>
												</div>
											</div></br>
											<div class="row">
												<div class="col-xs-12">
													<textarea id="re_assign_reason" name="reason" placeholder="Enter The Reason Here" rows="5" cols="50" required ></textarea>
												</div>
											</div>	
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Re-Assign</button>
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