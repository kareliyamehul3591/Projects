  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Task
        <small>View Task tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">View Task</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php 
		$error_msg = $this->session->error_msg;
		$success_msg = $this->session->success_msg;
		if($error_msg != null){
		?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error_msg; ?>
			</div>
		<?php }
		if($success_msg != null){
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
				<h3 class="box-title">View Task Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				
			<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Task/task_delete" enctype="multipart/form-data">
			
				<button type="submit" name="task_list_delete" value="delete" class="btn btn-primary" ><i class="fa fa-trash-o"></i> Delete </button>
				<br/><br/>
				<style>
				td a i {
					margin-left: 4px;
					margin-right: 4px;
				}
				</style>
				<table class="table table-bordered table-striped datatable">
					<thead>
						<tr>
							<th>  </th>
							<th> ID </th>
							<th> Assignment Name </th>
							<th> Title </th>
							<th> Keyword </th>
							<th> Action </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($datas as $data) { ?>
						<tr>
							<td>
								<input type="checkbox" class="minimal" name="select_task_list[]" value="<?php echo $data['id']; ?>" >
							</td>
							<td><?php echo $data['id']; ?></td>
							<td>
								<?php 
								foreach($assignment_data as $assignment){
									if($data['assignment_id'] == $assignment['id'])
									{
										echo $assignment['name']; 
									}
								}
								?>
							</td>
							<td><?php echo $data['title']; ?></td>
							<td><?php echo $data['keyword']; ?></td>
							<td><?php echo $data['action']; ?></td>
							<td align="center"> 
								<?php 
								if($data['status'] == 1){
									echo '<i id="active10" class="fa fa-check-square-o" aria-hidden="true" onclick="suspended('.$data['id'].',0)"></i>';
								}else{
									echo '<i id="active10" class="fa fa-square-o" aria-hidden="true" onclick="suspended('.$data['id'].',1)"></i>';
								}?>
							</td>
							<td>
								<a  data-target="#view_<?php echo $data['id']; ?>" data-toggle="modal" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>
								<a data-target="#edit_<?php echo $data['id']; ?>" data-toggle="modal" style="color: #089048;" ><i class="fa fa-pencil fa-lg"></i></a>
								<a href="<?php echo base_url(); ?>index.php/Task/task_delete/<?php echo $data['id']; ?>" style="color: #ff0808;" ><i class="fa fa-trash-o fa-lg"></i></a>
							</td>
						</tr>
					<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th>  </th>
							<th> ID </th>
							<th> Assignment Name </th>
							<th> Title </th>
							<th> Keyword </th>
							<th> Action </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</tfoot>
				</table>
				
			</form>
			
				<script>
					function suspended(id,status) {
						$.ajax({
							type: "POST",
							url: '<?php echo base_url(); ?>index.php/Task/active_task',
							data: {
									id: id,
									status: status,
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
				
				
				
				<?php foreach($datas as $data) {?>
					<div class="modal fade" id="view_<?php echo $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<a class="close" data-dismiss="modal">&times;</a>
									<h4 id="myModalLabel" class="modal-title">View</h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table">
											<tbody>
												<tr>
													<th style="width:50%">Assignment Name:</th>
													<td>
														<?php 
														foreach($assignment_data as $assignment){
															if($data['assignment_id'] == $assignment['id'])
															{
																echo $assignment['name']; 
															}
														}
														?>
													</td>
												</tr>
												<tr>
													<th>Title:</th>
													<td><?php echo $data['title']; ?></td>
												</tr>
												<tr>
													<th>Keyword:</th>
													<td><?php echo $data['keyword']; ?></td>
												</tr>
												<tr>
													<th>Action:</th>
													<td><?php echo $data['action']; ?></td>
												</tr>
												<tr>
													<th>File:</th>
													<td>
														<?php 
															foreach(explode(",", $data['file']) as $file)
															{ ?>
																<a href="<?php echo base_url();?>/uploads/Task/<?php echo $file; ?>" target='_blank' ><?php echo $file; ?></a><br/>
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
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php foreach($datas as $data) {?>
					<div class="modal fade" id="edit_<?php echo $data['id']; ?>">
						<div class="modal-dialog">
							<div class="modal-content">
								<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Task/task_edit" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Edit Taske Date </h4>
									</div>
									<div class="modal-body">
										<input type="hidden" name="ids" id="ids" value="<?php echo $data['id']; ?>">
										<input type="hidden" name="files" id="files" value="<?php echo $data['file']; ?>">
										
										<div class="row">
											<div class="col-xs-6">
												<select class="form-control" name="assignment_name" id="assignment_name" onchange="assignment_data(this.value);" required >
													<option value="">Select One Assignment Name *</option>
													<?php foreach($assignment_data as $assignments){?>
														<option value="<?php echo $assignments['id']; ?>" <?php if($data['assignment_id'] == $assignments['id']){ echo 'selected'; } ?> ><?php echo $assignments['name']; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-xs-6">
												<input type="text" name="title" id="title" class="form-control" placeholder="Title Name *" value="<?php echo $data['title']; ?>" required >
											</div>
										</div></br>
										<div class="row">
											<div class="col-xs-6">
												<input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword *" value="<?php echo $data['keyword']; ?>" required >
											</div>
											<div class="col-xs-6">
												<select class="form-control" name="action" id="action" >
													<option value="">Action Assign</option>
													<option value="Reassign" <?php if($data['action'] == 'Reassign'){ echo 'selected'; } ?> >Reassign</option>
													<option value="Delete" <?php if($data['action'] == 'Delete'){ echo 'selected'; } ?> >Delete</option>
													<option value="Cancel" <?php if($data['action'] == 'Cancel'){ echo 'selected'; } ?> >Cancel</option>
													<option value="Completed" <?php if($data['action'] == 'Completed'){ echo 'selected'; } ?> >Completed</option>
													
												</select>
											</div>
										</div></br>										
										<div class="row">
											<div class="col-xs-12">
												<input type="file" name="file[]" id="file" class="form-control" multiple>
											</div>
										</div></br>
										<?php 
										if($data['file'] != null)
										{
											foreach(explode(",", $data['file']) as $file)
											{
												if($file != NULL)
												{
													$key++;
													echo $key." :- ";
													echo '<a href="'.base_url().'/uploads/Task/'.$file.'" target="_blank" >'.$file.'</a>';
													echo '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$data['id'].')"></i>';
													echo '</br>';
												}	
											} 
											$key = 0;
										} ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Save changes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<script type="text/javascript">
					function remove_file(ids,id)
					{
						var images_r = confirm("Are you sure want to delete?");	
						if (images_r == true) {
							$.ajax({
								type:'POST',
								url: '<?php echo base_url(); ?>index.php/Task/remove_file',
								data:{ id : id , ids : ids },
								success:function(data){
									location.reload();
								}
							});
						}
					}
				</script>
				
				
            </div>
            <!-- /.box-body -->
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
