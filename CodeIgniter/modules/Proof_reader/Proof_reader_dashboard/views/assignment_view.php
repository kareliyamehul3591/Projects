  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Assignment
        <small>View Assignment tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error_msg; ?>
			</div>
		<?php }
		if($success_msg != null){
			unset($_SESSION['success_msg']);
		?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> Success!</h4>
				<?php echo $success_msg; ?>
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
					<!--<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Status :-</label>
							<div class="col-sm-7">
								<?php 
									if($datas[0]['status'] == 4){
										echo 'Cancelled';
									}else if($datas[0]['status'] == 5){
										echo 'Complete';
									}else if($datas[0]['status'] == 1){
										echo 'Accepted';
									}
								?>
							</div>
						</div>
					</div>-->
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
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Proof_reader_dashboard/assignment_image_add" id="assignment_add" enctype="multipart/form-data" >
					<div class="row">
						<input type="hidden" name="assignment_id" id="assignment_id" value="<?php echo $datas[0]['id']; ?>" required >
						
						<?php $proof_reader_id = $this->Mdl_proof_reader_dashboard->proof_reader_id($datas[0]['id']); ?>
			
						<div class="col-xs-12">
							<textarea id="description" name="description" placeholder="Description" rows="15" cols="120" ><?php echo $assignment_final_data[0]['description'];?></textarea><br/>
							<a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard/download_file/<?php echo $datas[0]['id']; ?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;" > <i class="fa fa-download"></i> Download .doc File </a>
							<br/><br/>
							
						</div>
						<?php if($datas[0]['status'] == 7 || $proof_reader_id[0]['status'] != 1){ ?>
							<script>
								CKEDITOR.replace('description').config.readOnly = true;
							</script>
						<?php } ?>
						
						<?php if($datas[0]['status'] != 7 && $proof_reader_id[0]['status'] == 1){ ?>
							<div class="col-xs-6">
								<input type="file" name="file[]" id="file" class="form-control" accept=".txt,.doc,.docx,.xls,.xlsx,.pdf,.zip,.jpeg,.png,.jpg" multiple>
							</div>
							<div class="col-xs-6">
								<?php
									$proof_reader_ids = $this->Mdl_proof_reader_dashboard->proof_reader_id($datas[0]['id']);
									if($proof_reader_ids[0]['proof_reader_id'] == $this->session->Admindetail['id'] && $proof_reader_ids[0]['status'] == 1)
									{
										foreach(explode(",", $assignment_final_data[0]['proof_reader_file']) as $file)
										{ 
											if($file != NULL)
											{
												$key++;
												echo $key." :- ";
												echo '<a href="'.base_url().'uploads/Assignment/'.$datas[0]['id'].'/proof_reader/'.$file.'" target="_blank" >'.$file.'</a>';
												if($datas[0]['status'] != 5){
													echo '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$datas[0]['id'].')"></i>';
												}
												echo '</br>';
											}
										} 
									}
								?>
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
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/help_desk/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>,&ensp;
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
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/admin/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>,&ensp; 
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
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/manager/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>,&ensp; 
											<?php $i++; } } ?>
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
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/proof_reader/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>,&ensp; 
											<?php $i++; } } ?>
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
												-&ensp;<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $datas[0]['id'].'/writer/'.$file; ?>" target='_blank' ><?php echo $file; ?></a>,&ensp; 
											<?php $i++; } } ?>
									</div>
								</div>
							</div>
						<?php }?>
					</div></br>
					<?php 
					if($proof_reader_id[0]['status'] == 5 || $datas[0]['status'] == 4 || $proof_reader_id[0]['status'] == 2 || $datas[0]['status'] == 7)
					{ ?>
						<div class="box-footer">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
						</div>
					<?php
					}else if($proof_reader_id[0]['status'] == 0)
					{
						$accept_url = "myModal('".base_url()."index.php/Proof_reader_dashboard/accept_taske/".$datas[0]['id']."')";
						$reject_url = "myModal('".base_url()."index.php/Proof_reader_dashboard/reject_taske/".$datas[0]['id']."')";
					?>
						<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="<?php echo $accept_url; ?>" > <i class="fa fa-thumbs-up fa-lg" style="color: #7169e4;" ></i>&nbsp; Accept</button>
							<button type="button" class="btn btn-default pull-right" onclick="<?php echo $reject_url; ?>" > <i class="fa fa-times-circle fa-lg" style="color: #c436da;" ></i>&nbsp; Reject</button>
						</div>
						<script>
							function myModal(url)
							{
								$('.myModal_content').load(url,function(){
									$('#myModal').modal({show:true});
								});
							}
						</script>
					<?php }else{ ?>
						<div class="box-footer">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
							<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#terms_and_condition" >Save</button>
						</div>
						<div class="modal fade in" id="terms_and_condition" style="display: none; padding-right: 17px;">
							<div class="modal-dialog" style="width: 50%;" >
								<div class="modal-content">
								  <div class="modal-body" >
									<h2 style="text-align:center;" > Terms and Condition </h2>
									<div class="box-body" style="height: 585px; overflow-y: scroll ; " >
										<dl>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
											<dt>Writer guidelines</dt>
											<dd>We rarely publish narrative travel features on this site, but we do consider interesting blogs, inspirational round-ups, "listicles", guides and advice pieces. Please ensure that you are familiar with the site, our style and our audience. Note, we do not take 'Guest Posts' from agencies. If you are promoting an organisation, then please contact our very helpful commercial team about the opportunities!</dd>
											<dt>Euismod</dt>
											<dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
											<dd>Donec id elit non mi porta gravida at eget metus.</dd>
											<dt>Malesuada porta</dt>
											<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
										</dl>
									</div>
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-primary">Agree</button>
									
								  </div>
								</div>
							</div>
						</div>
					<?php } ?>
					
						
					
					
				</form>
				<div class="modal fade" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content myModal_content">
							
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
								url: '<?php echo base_url(); ?>index.php/Proof_reader_dashboard/assignment_remove_file',
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
						<?php if($proof_reader_id[0]['status'] == 5 || $datas[0]['status'] == 4){?>
							<a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard/show_archived" type="button" class="btn btn-primary">Leave Page</a>
						<?php }else{ ?>
							<a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard" type="button" class="btn btn-primary">Leave Page</a>
						<?php } ?>
				  </div>
				</div>
			  </div>
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
							<a href="<?php echo base_url();?>index.php/Proof_reader_dashboard/re_assign_assignment/<?php echo $assignment_final_data[0]['assignment_id'];?>" type="submit" class="btn btn-primary re_assign_assignment_btn" style="display:none;">Re-Assign</a>
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