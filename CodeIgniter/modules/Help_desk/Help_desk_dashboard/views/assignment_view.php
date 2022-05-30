  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Assignment
        <small>View Assignment tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Help_desk_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">View Assignment</li>
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
		<?php } ?>
      <div class="row">
        <div class="col-xs-12">
		
          <div class="box box-info">
            <div class="box-header">
				<h3 class="box-title">View Assignment</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Name :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['name']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Client Name :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['client_name']; ?>
							</div>
						</div>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Deadline :-</label>
							<div class="col-sm-7">
								<?php echo date('m/d/Y', strtotime( $datas[0]['deadline_date'] )).' '.$datas[0]['deadline_time']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Type Of Assignment :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['assignment_type']; ?>
							</div>
						</div>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">No of Taske :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['tasks_no']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Niche :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['health']; ?>
							</div>
						</div>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">No of Words/Article :-</label>
							<div class="col-sm-7">
								<?php echo $datas[0]['article']; ?>
							</div>
						</div>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Description :-</label>
							<div class="col-sm-9">
								<?php echo $datas[0]['description']; ?>
							</div>
						</div>
					</div>
				</div></br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">File :-</label>
							<div class="col-sm-7">
								<?php 
									foreach(explode(",", $datas[0]['file']) as $file)
									{ ?>
										<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $file; ?>" target='_blank' ><?php echo $file; ?></a><br/>
								<?php } ?>
							</div>
						</div>
					</div>
				</div></br>
            </div>
            <!-- /.box-body -->
			<div class="box-header">
				<h3 class="box-title">ATTACHMENTS</h3>
            </div>
			<!-- /.box-header -->
            <div class="box-body">
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Help_desk_dashboard/assignment_image_add" id="assignment_add" enctype="multipart/form-data" >
					
					<div class="row">
						<input type="hidden" name="assignment_id" id="assignment_id" value="<?php echo $datas[0]['id']; ?>" required >
						
						
						<div class="col-xs-12">
							<textarea id="description" name="description" placeholder="Description" rows="15" cols="120" ><?php echo $assignment_final_data[0]['description'];?></textarea><br/>
							<a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard/download_file/<?php echo $datas[0]['id']; ?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;" > <i class="fa fa-download"></i> Download .doc File </a>
							<br/><br/> 
						</div>
						
						<?php if($datas[0]['status'] == 4 || $datas[0]['status'] == 5 || $datas[0]['status'] == 6 || $datas[0]['status'] == 0){?>
							<script>
								CKEDITOR.replace('description').config.readOnly = true;
							</script>
						<?php } ?>
						
						<?php if($datas[0]['status'] != 4 && $datas[0]['status'] != 5 && $datas[0]['status'] != 6 && $datas[0]['status'] != 0){?>
							<div class="col-xs-6">
								<input type="file" name="file[]" id="file" class="form-control" accept=".txt,.doc,.docx,.xls,.xlsx,.pdf,.zip,.jpeg,.png,.jpg" multiple>
							</div>
							<div class="col-xs-6">
								<?php
									foreach(explode(",", $assignment_final_data[0]['help_desk_file']) as $file)
									{ 
										if($file != NULL)
										{
											$key++;
											echo $key." :- ";
											echo '<a href="'.base_url().'uploads/Assignment/'.$datas[0]['id'].'/help_desk/'.$file.'" target="_blank" >'.$file.'</a>';
											if($datas[0]['status'] != 5){
												echo '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$datas[0]['id'].')"></i>';
											}
											echo '</br>';
										}
									} ?>
							</div>
							<div class="col-xs-12 file_size_error " style="display: none"  >
								<label style="color:#FB3A3A;font-weight:bold;" >
									Only .doc, .docx, .xls, .xlsx, .pdf,.txt,.zip, all image files are allowed.<br/>
									Each file upload llimit is 50 MB and a maximum of 5 files are allowed.
								</label>
							</div>
							<script>
							var uploadField = document.getElementById("file");
							uploadField.onchange = function() {
								for (var i = 0; i < this.files.length; i++) {
									if(this.files[i].size > 52428800){
									   $(".file_size_error").show();
									   this.value = "";
									}
								} 
								if(this.files.length > 5)
								{
									$(".file_size_error").show();
									this.value = "";
								}
							};
							</script>
						<?php } ?>
					</div></br>
					
					<div class="row">
						<?php if($assignment_final_data[0]['help_desk_id'] != null){?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">By Help Desk :-</label>
									<div class="col-sm-10">
										<?php 
											$i = 1;
											foreach(explode(",", $assignment_final_data[0]['help_desk_file']) as $file)
											{ 
											if($file != null){
											echo $i;?>
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/help_desk/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>&ensp;,
											<?php $i++; } } ?>
									</div>
								</div>
							</div>
						<?php } if($assignment_final_data[0]['admin_id'] != null){?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">By Admin :-</label>
									<div class="col-sm-10">
										<?php 
											$i = 1;
											foreach(explode(",", $assignment_final_data[0]['admin_file']) as $file)
											{ 
											if($file != null){
											echo $i;?>
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/admin/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>&ensp;,
											<?php $i++; } } ?>
									</div>
								</div>
							</div>
						<?php } if($assignment_final_data[0]['manager_id'] != null){?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">By Manager :-</label>
									<div class="col-sm-10">
										<?php 
											$i = 1;
											foreach(explode(",", $assignment_final_data[0]['manager_file']) as $file)
											{ 
											if($file != null){
											echo $i;?>
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/manager/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>&nbsp;,
											<?php
											$i++; } } ?>
									</div>
								</div>
							</div>
						<?php } if($assignment_final_data[0]['proof_reader_id'] != null){?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">By Proof Reader :-</label>
									<div class="col-sm-10">
										<?php 
											$i = 1;
											foreach(explode(",", $assignment_final_data[0]['proof_reader_file']) as $file)
											{ 
											if($file != null){
											echo $i;?>
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/proof_reader/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>&nbsp;,
											<?php
											$i++; } } ?>
									</div>
								</div>
							</div>
						<?php } if($assignment_final_data[0]['writer_id'] != null){?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">By Writer :-</label>
									<div class="col-sm-10">
										<?php 
											$i = 1;
											foreach(explode(",", $assignment_final_data[0]['writer_file']) as $file)
											{ 
											if($file != null){
											echo $i;?>
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/writer/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>&nbsp;,
											<?php $i++; } } ?>
									</div>
								</div>
							</div>
						<?php }?>
					</div></br>
					
					<?php 
						$manager_id = $this->Mdl_help_desk_dashboard->manager_id($datas[0]['id']);
						$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($datas[0]['id']);
						$writer_id = $this->Mdl_help_desk_dashboard->writer_id($datas[0]['id']);
						
						if($manager_id == null && $proof_reader_id == null && $writer_id == null && $datas[0]['status'] != 5 && $datas[0]['status'] != 4 && $datas[0]['status'] != 6 && $datas[0]['status'] != 0 && $datas[0]['status'] != 2){ ?>
							<div class="row">
								<?php
									$all_managers = $this->Mdl_help_desk_dashboard->all_managers();
									$all_writers = $this->Mdl_help_desk_dashboard->all_writers();
									$all_proof_readers = $this->Mdl_help_desk_dashboard->all_proof_readers();
									
									$manager_id = $this->Mdl_help_desk_dashboard->manager_id($datas[0]['id']);
									$writer_id = $this->Mdl_help_desk_dashboard->writer_id($datas[0]['id']);
									$proof_reader_id = $this->Mdl_help_desk_dashboard->proof_reader_id($datas[0]['id']); 
									
									
									$datetime1 = new DateTime($datas[0]['created_date']);
									$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime( $datas[0]['deadline_date'].' '.$datas[0]['deadline_time'] )));
									$interval = $datetime1->diff($datetime2);
									$hours = $interval->h + ($interval->days*24);
									$minit = $interval->i + ($hours*60);
								?>
								<input type="hidden" name="deadline_date" id="deadline_date" value="<?php echo date('Y-m-d H:i:s', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )); ?>">
								<input type="hidden" name="assign_hours" id="assign_hours" value="<?php echo ($minit / 10)*9 / 60;?>">
								<input type="hidden" name="save_hours" id="save_hours" value="<?php echo ($minit / 10) / 60; ?>">
								<input type="hidden" name="assign" id="assign" value="assign"> 
								<div class="col-xs-6">
									<h4>Assignment Assign</h4>
									<div class="row">
										<div class="col-xs-12">				
											<select class="form-control" name="manager_id" id="manager_id" >
												<option value="">Assign One manager</option>
												<?php foreach($all_managers as $all_manager){ 
												if($all_manager['id'] == $manager_id[0]['manager_id']){ ?>
													<option value="<?php echo $all_manager['id']; ?>" selected ><?php echo $all_manager['name']; ?></option>
												<?php }else{ ?>
													<option value="<?php echo $all_manager['id']; ?>"><?php echo $all_manager['name']; ?></option>
												<?php } } ?>
											</select>
										</div>
									</div></br>
									<div class="row">
										<div class="col-xs-12">
											<select class="form-control" name="writer_id" id="writer_id" >
												<option value="">Assign One writer</option>
												<?php foreach($all_writers as $all_manager){ 
												if($all_manager['id'] == $writer_id[0]['writer_id']){ ?>
													<option value="<?php echo $all_manager['id']; ?>" selected ><?php echo $all_manager['name']; ?></option>
												<?php }else{ ?>
													<option value="<?php echo $all_manager['id']; ?>"><?php echo $all_manager['name']; ?></option>
												<?php } } ?>
											</select>
										</div>
									</div></br>
									<div class="row">
										<div class="col-xs-12">
											<select class="form-control" name="proof_reader_id" id="proof_reader_id" >
												<option value="">Assign One Proof Reader</option>
												<?php foreach($all_proof_readers as $all_manager){ 
												if($all_manager['id'] == $proof_reader_id[0]['proof_reader_id']){ ?>
													<option value="<?php echo $all_manager['id']; ?>" selected ><?php echo $all_manager['name']; ?></option>
												<?php }else{ ?>
													<option value="<?php echo $all_manager['id']; ?>"><?php echo $all_manager['name']; ?></option>
												<?php } } ?>
											</select>
										</div>
									</div></br>
									<div class="row">
										<div class="col-xs-12">
											<textarea id="reason" name="reason" placeholder="Enter The Reason Here" rows="2" cols="50"></textarea>
										</div>
									</div></br>
								</div>
							</div></br>
						<?php } ?>
						
					
					<div class="box-footer">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
						<?php if($datas[0]['status'] != 5 && $datas[0]['status'] != 4 && $datas[0]['status'] != 6 && $datas[0]['status'] != 2 && $datas[0]['status'] != 0 ){ ?>
							<button type="submit" class="btn btn-primary pull-right">Save</button>
						<?php } ?>
					</div>
					
						
				</form>
				<div class="modal fade" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content myModal_content">
							
						</div>
					</div>
				</div>
				
				<div class="modal fade in" id="leave_the_page" style="display: none; padding-right: 17px;">
				  <div class="modal-dialog" style="width: 370px;" >
					<div class="modal-content">
					
					  <div class="modal-body">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">×</span></button>
						  <br/>
						<h4 style="text-align:center;" >
						<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-3x" aria-hidden="true"></i> </a><br/>
						Are you sure you wany to leave the page? <br/>
						All the changes made will be lost !
						</h4>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Do not leave</button>
						<?php if($datas[0]['status'] != 4 && $datas[0]['status'] != 5 && $datas[0]['status'] != 6){ ?>
							<a href="<?php echo base_url(); ?>index.php/Help_desk_dashboard" type="button" class="btn btn-primary">Leave Page</a>
						<?php }else{ ?>
							<a href="<?php echo base_url(); ?>index.php/Help_desk_dashboard/show_archived" type="button" class="btn btn-primary">Leave Page</a>
						<?php }?>
					  </div>
					</div>
				  </div>
				</div>
		
				<script type="text/javascript">
					function remove_file(ids,id)
					{
						var images_r = confirm("Are you sure want to delete?");
						if (images_r == true) {
							$.ajax({
								type:'POST',
								url: '<?php echo base_url(); ?>index.php/Help_desk_dashboard/assignment_remove_file',
								data:{ id : id , ids : ids },
								success:function(data){
									location.reload();
								}
							});
						}
					}
					function copyscape()
					{
						var id = <?php echo $assignment_final_data[0]['assignment_id'];?>;
						$.ajax({
							type:'POST',
							url: '<?php echo base_url(); ?>index.php/Proof_reader_dashboard/copyscape',
							data:{ id : id },
							dataType: "json",
							success:function(data){
								$('#view').modal('show');
								if(data[0] < 6)
								{
									$(".copyscape_complete").html(data[0]+ " results found");
								}else{
									$(".copyscape_cancel").html(data[0]+ " results found");
									$(".re_assign_assignment").show();
									$(".re_assign_assignment_btn").show();
								}							
							}
						});
					}
				</script>
            </div>
            <!-- /.box-body -->
			<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header"> <a class="close" data-dismiss="modal">&times;</a>
								<h4 id="myModalLabel" class="modal-title">View</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<h1 style="color: #00a65a;" class="copyscape_complete"></h1>
								<h1 style="color: red;" class="copyscape_cancel"></h1>
								<h4 style="color: red; display:none;" class="re_assign_assignment" >Re-Assign the Assignment</h4>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<a href="<?php echo base_url();?>index.php/Dashboard/re_assign_assignment/<?php echo $assignment_final_data[0]['assignment_id'];?>" type="submit" class="btn btn-primary re_assign_assignment_btn" style="display:none;">Re-Assign</a>
						</div>
					</div>
				</div>
			</div>
			
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- page script -->