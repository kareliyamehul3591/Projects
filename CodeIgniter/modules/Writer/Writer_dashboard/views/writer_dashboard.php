<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			Dashboard        
			<small>Writer</small>
		</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>index.php/Writer_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
		
		$new_count = 0;
		$accepted_count = 0;
		$qc_failed = 0;
		$rejected_count = 0;
		foreach($datas as $data)
		{
			$writer_id = $this->Mdl_writer_dashboard->writer_id($data['id']);
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
			  <a class="small-box bg-red" href="<?php echo base_url(); ?>index.php/Writer_dashboard/show_archived" style="background-color: #8a838c  !important;">
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
						
						<a class="btn btn-default"  onclick="chk_assignment('list_accept')" style="background-color: #7169e4;color: #fff;" ><i class="fa fa-thumbs-up fa-lg"></i>&nbsp; Accept </a>
						<a class="btn btn-default"  onclick="chk_assignment('list_reject')" style="background-color: #c436da;color: #fff;" ><i class="fa fa-times-circle fa-lg"></i>&nbsp; Reject </a>
						<a class="btn btn-default"  onclick="chk_assignment('list_complete')" style="background-color: #16ec7b;color: #fff;" ><i class="fa fa-check fa-lg"></i>&nbsp; Complete </a>
						
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
								$('#complete_list_id').val(y);
								$('#accept_list_id').val(y);
								$('#reject_list_id').val(y);
								
								$('#complete_reason').val("");
								$('#accept_reason').val("");
								$('#reject_reason').val("");
						
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
								<?php foreach($datas as $data) { 
								
								$proof_reader_id = $this->Mdl_writer_dashboard->pr_id_get($data['id']);
								$manager_id = $this->Mdl_writer_dashboard->manager_all_id_get($data['id']);
								$writer_id = $this->Mdl_writer_dashboard->writer_id($data['id']);
								
								$writer_name = $this->Mdl_writer_dashboard->user_get($writer_id[0]['writer_id']);
								$proof_reader_name = $this->Mdl_writer_dashboard->user_get($proof_reader_id[0]['proof_reader_id']);
								$manager_name = $this->Mdl_writer_dashboard->user_get($manager_id[0]['manager_id']);
								
								$adminr_name = $this->Mdl_writer_dashboard->user_get($data['admin_id']);
								$created_name = $this->Mdl_writer_dashboard->user_get($data['created_id']);
								?>
									<tr>
										<td>
											<input type="checkbox" class="select_assignment" name="select_assignment_list[]" value="<?php echo $data['id']; ?>" >
										</td>
										<td><?php echo $data['id']; ?></td>
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
											
											$accept_url = "myModal('".base_url()."index.php/Writer_dashboard/accept_taske/".$data['id']."')";
											$reject_url = "myModal('".base_url()."index.php/Writer_dashboard/reject_taske/".$data['id']."')";
											$complete_url = "myModal('".base_url()."index.php/Writer_dashboard/complete_taske/".$data['id']."')";
											$logs_url = "myModal('".base_url()."index.php/Dashboard/logs_taske/".$data['id']."')";
											
											echo '<a href="'.base_url().'index.php/Writer_dashboard/assignment_view/'.$data['id'].'" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>';
											if($data['status'] != 7)
											{
												if($data['assign_to_ma_status'] == 0){
													echo '<a onclick="'.$accept_url.'" style="color: #7169e4;"><i class="fa fa-thumbs-up fa-lg"></i></a>';
											
													echo '<a onclick="'.$reject_url.'" style="color: #c436da;"><i class="fa fa-times-circle fa-lg"></i></a>';
												}else if($data['assign_to_ma_status'] == 1){
													echo '<a onclick="'.$complete_url.'" style="color: #16ec7b;"><i class="fa fa-check fa-lg"></i></a>';
												}
											}
											if($data['created_role'] == "help_desk" || $adminr_name[0]['name'] != null || $proof_reader_id != null || $manager_id != null){
											?> 
											<div class="btn-group">
											  <a data-toggle="dropdown" style="color: #2c893a;" > <i class="fa fa-commenting fa-lg"></i></a>
											  <ul class="dropdown-menu" role="menu" style="left: -161px; border-color: #000;top: -12px;" >
												<?php
												
												if($adminr_name[0]['name'] != null)
												{
													$admin_url = "javascript:register_popup('".$data['admin_id']."1".$data['id']."','".ucfirst($adminr_name[0]['name'])."','".$data['admin_id']."','Admin','".$data['id']."');";
													echo'<li><a href="'.$admin_url.'">'.ucfirst($adminr_name[0]['name']).' <small> ( Admin ) </small> </a></li>';
												}
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
												if($manager_id != null)
												{
													$manager_url = "javascript:register_popup('".$manager_name[0]['id']."2".$data['id']."','".ucfirst($manager_name[0]['name'])."','".$manager_name[0]['id']."','Manager','".$data['id']."');";
													echo'<li><a href="'.$manager_url.'">'.ucfirst($manager_name[0]['name']).' <small> ( Manager ) </small></a></li>';
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
							function myModal(url)
							{
								$('.myModal_content').load(url,function(){
									$('#myModal').modal({show:true});
								});
							}
							function assign_to_ma_status(id, status) {
								$.ajax({
									type: "POST",
									url: '<?php echo base_url(); ?>index.php/Writer_dashboard/active_assignment',
									data: {
										id: id,
										status: status,
									},
									success: function(data) {
										location.reload();
									},
									error: function() {
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
						
						<div class="modal fade" id="list_complete">
							<div class="modal-dialog">
								<div class="modal-content ">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/complete_assignment_list" enctype="multipart/form-data">
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
						<div class="modal fade" id="list_accept">
							<div class="modal-dialog">
								<div class="modal-content ">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/active_assignment_list" enctype="multipart/form-data">
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
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/active_assignment_list" enctype="multipart/form-data">
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
						
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->