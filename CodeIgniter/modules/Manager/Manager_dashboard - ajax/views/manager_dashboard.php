<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Manager</small>
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
		<?php } 
		
		$new_count = 0;
		$accepted_count = 0;
		$assigned_count = 0;
		$complete_count = 0;
		$re_assigned_count = 0;
		$rejected_count = 0;
		$cancel_count = 0;
		foreach($datas as $data){
			
			if($data['status'] == 4){
				$cancel_count++;
			}else if($data['assign_to_ma_status'] == 5){
				$complete_count++;
			}else if($data['assign_to_ma_status'] == 1){
				$accepted_count++;
			}else if($data['assign_to_ma_status'] == 6){	
				$re_assigned_count++;
			}else{
				$new_count++;
			}
		}
		?>
	  <div class="row">		
		<div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green" onclick="onclick_box('New')" >
            <div class="inner">
				<h3><?php echo $new_count; ?></h3>
              <!--<h3>53<sup style="font-size: 20px">%</sup></h3>-->
              <p>New <br/> Assignment</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
          </div>
        </div>	
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua" onclick="onclick_box('Accepted')" >
            <div class="inner">
              <h3><?php echo $accepted_count;?></h3>
              <p>Accepted <br/> Assignment</p>
            </div>
            <div class="icon">
              <i class="fa fa-check"></i>
            </div>
          </div>
        </div>		
		<div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green" onclick="onclick_box('complete')" >
            <div class="inner">
				<h3><?php echo $complete_count; ?></h3>
              <!--<h3>53<sup style="font-size: 20px">%</sup></h3>-->
              <p>Completed <br/> Assignment</p>
            </div>
            <div class="icon">
              <i class="fa fa-file"></i>
            </div>
          </div>
        </div>		
		<div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red" onclick="onclick_box('cancelled')" >
            <div class="inner">
              <h3><?php echo $cancel_count; ?></h3>
              <p>Cancelled <br/> Assignment</p>
            </div>
            <div class="icon">
              <i class="fa fa-exclamation-triangle"></i>
            </div>
          </div>
        </div>		
      </div>
	  <script>
		function onclick_box(id)
		{
			$('.actions_datatabl').DataTable().columns( 9 ).search( id ).draw();
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
						<!--<input type="checkbox" id="selectalls"/> &nbsp; Select This page &emsp;
						<?php 
						echo '<a class="btn btn-default" onclick="assign_to_ma_status_list(1)" > Accept </a>';
						
						echo '<a class="btn btn-default" onclick="assign_to_ma_status_list(2)" style="margin-left: 2px;" > Reject </a>';
						?>
						
						<a class="btn btn-default" data-target="#assign" data-toggle="modal" onclick="chk_assignment()" ><i class="fa fa-pencil-square-o"></i> Assign </a>
						
						<a class="btn btn-default" data-target="#re_assign" data-toggle="modal" onclick="chk_assignment()" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>
						
						<a class="btn btn-default" onclick="complete_assignment()" ><i class="fa fa-check"></i> Complete </a>
						
						<br/><br/>-->
						<script>
							function assign_to_ma_status_list(status)
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
								$.ajax({
									type: "POST",
									url: '<?php echo base_url(); ?>index.php/Manager_dashboard/active_assignment_list',
									data: { id: y, status: status, }, 
									success: function(data){
										location.reload();
									},
									error: function(){
										console.log("data not found");
									}
								});
							}
							function chk_assignment()
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
								$('#assignment_list_id').val(y);
								$('#re_assignment_list_id').val(y);
							}
							function complete_assignment()
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
								$.ajax({
									type: "POST",
									url: '<?php echo base_url(); ?>index.php/Manager_dashboard/complete_assignment_list',
									data: { id: y, }, 
									success: function(data){
										location.reload();
									},
									error: function(){
										console.log("data not found");
									}
								});
							}
							$(function(){
								$("#selectalls").click(function () {
									  $('.select_assignment').attr('checked', this.checked);
								});
								$(".select_assignment").click(function(){
									if($(".select_assignment").length == $(".select_assignment:checked").length) {
										$("#selectalls").attr("checked", "checked");
									} else {
										$("#selectalls").removeAttr("checked");
									}
								});
							});
						</script>
						<table id="employee-grid" class="table table-bordered table-striped actions_datatabl">
							<thead>
								<tr>
									<!--<th>  </th>-->
									<th> ID </th>
									<th> Name </th>
									<th> Actions Time </th>
									<th> Client Name </th>
									<th> Deadline </th>
									<th> Type </th>
									<th> Admin </th>
									<th> Writer </th>
									<th> Proof Reader </th>
									<th> Status Manager </th>
									<th> Status Writer </th>
									<th> Status Proof Reader </th>
									<th> Actions </th>
								</tr>
							</thead>
							<tbody>
										
							<?php 
							foreach($assign_to_writer as $assign_to_man){
								if($assign_to_man['status'] == 0 || $assign_to_man['status'] == 1 || $assign_to_man['status'] == 5 || $assign_to_man['status'] == 6)
								{
									$assignment_writer_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 0)
								{
									$assignment_writer_not_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 1)
								{
									$assignment_writer_working_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 2)
								{
									$assignment_writer_reject_id[] = $assign_to_man['assignment_id'];
									$assignment_reject_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 5)
								{
									$assignment_writer_complete_id[] = $assign_to_man['assignment_id'];
								}
							}
							foreach($assign_to_pr as $assign_to_man){
								if($assign_to_man['status'] == 0 || $assign_to_man['status'] == 1 || $assign_to_man['status'] == 5 || $assign_to_man['status'] == 6)
								{
									$assignment_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 0)
								{
									$assignment_not_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 1)
								{
									$assignment_working_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 2)
								{
									$assignment_pr_reject_id[] = $assign_to_man['assignment_id'];
									$assignment_reject_id[] = $assign_to_man['assignment_id'];
								}
								if($assign_to_man['status'] == 5)
								{
									$assignment_complete_id[] = $assign_to_man['assignment_id'];
								}
							}
							
							
							
							$count = count($datas);
							$dat = $datas;
							$datas = array_slice($datas, 0, 10);
							foreach($datas as $data) {
								
								$proof_reader_id = $this->Mdl_manager_dashboard->proof_reader_id($data['id']);
								$writer_id = $this->Mdl_manager_dashboard->writer_id($data['id']);
								
								$proof_reader_name = $this->Mdl_manager_dashboard->user_get($proof_reader_id[0]['proof_reader_id']);
								$writer_name = $this->Mdl_manager_dashboard->user_get($writer_id[0]['writer_id']);
								$adminr_name = $this->Mdl_manager_dashboard->user_get($data['admin_id']);
							
							
							?>
								<tr>
									<!--<td>
										<input type="checkbox" class="select_assignment" name="select_assignment_list[]" value="<?php echo $data['id']; ?>" >
									</td>-->
									<td>
										<?php echo $data['id']; ?>
										<?php //echo $data['id'].' / '.gmdate("H:i", ($data['assign_hours'] * 60) * 60 ); ?>
									</td>
									<td><?php echo $data['name']; ?></td>
									<td><?php echo date('m/d/Y H:i:s', strtotime( $data['created_date'] )); ?></td>
									<td><?php echo $data['client_name']; ?></td>
									<td><?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.date('H:i', strtotime( $data['deadline_time'] )); ?></td>
									<td><?php echo $data['assignment_type']; ?></td>
									<td><?php echo $adminr_name[0]['name'];?></td>
									<td><?php echo $writer_name[0]['name'];?></td>
									<td><?php echo $proof_reader_name[0]['name'];?></td>
									<td>
										<?php 
											if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($data['assign_to_ma_status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											}else if($data['assign_to_ma_status'] == 1){	
												echo '<span class="label label-primary">Accepted</span>';
											}else if($data['assign_to_ma_status'] == 6){	
												echo '<span class="label label-danger">Re-Assigned</span>';
											}else{	
												echo '<span class="label label-success">New</span>';
											}
										?>
									</td>
									<td>
										<?php 
										if($writer_id != null)
										{
											if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($writer_id[0]['status'] == 0){							
												echo '<span class="label label-warning">Assigned</span>';
											}else if($writer_id[0]['status'] == 1){
												echo '<span class="label label-primary">Accepted</span>';
											}else if($writer_id[0]['status'] == 2){
												echo '<span class="label label-danger">Rejected</span>';
											}else if($writer_id[0]['status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											}else if($writer_id[0]['status'] == 6){
												echo '<span class="label label-danger">Re-Assigned</span>';
											}
										}else{	
												echo '<span class="label label-success">New</span>';
											}
										?>
									</td>
									<td>
										<?php 
										if($proof_reader_id != null)
										{
											if($data['status'] == 4){
												echo '<span class="label label-danger">Cancelled</span>';
											}else if($proof_reader_id[0]['status'] == 0){							
												echo '<span class="label label-warning">Assigned</span>';
											}else if($proof_reader_id[0]['status'] == 1){
												echo '<span class="label label-primary">Accepted</span>';
											}else if($proof_reader_id[0]['status'] == 2){
												echo '<span class="label label-danger">Rejected</span>';
											}else if($proof_reader_id[0]['status'] == 5){
												echo '<span class="label label-success">Complete</span>';
											}else if($proof_reader_id[0]['status'] == 6){
												echo '<span class="label label-danger">Re-Assigned</span>';
											}
										}else{	
												echo '<span class="label label-success">New</span>';
											}
										?>
									</td>
									<td>
										<?php
										$view_url = "myModal('".base_url()."index.php/Manager_dashboard/view_taske/".$data['id']."')";
										$assign_url = "myModal('".base_url()."index.php/Manager_dashboard/assign_taske/".$data['id']."')";
										$re_assign_url = "myModal('".base_url()."index.php/Manager_dashboard/re_assign_taske/".$data['id']."')";
										
										if($data['status'] == 4){
											echo '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
										}else if($data['status'] == 5){
											
											echo '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
										}else if($data['assign_to_ma_status'] == 0){
											echo '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
										}else if($data['assign_to_ma_status'] == 1){
											if($proof_reader_id == null){
												echo '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
												echo '<a class="btn btn-default" onclick="'.$assign_url.'" ><i class="fa fa-pencil-square-o"></i> Assign </a>';
											}else if($proof_reader_id != null){
												if($proof_reader_id[0]['status'] == 1 || $proof_reader_id[0]['status'] == 6 || $proof_reader_id[0]['status'] == 0)
												{
													echo '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
													if($writer_id[0]['status'] == 2)
													{
														echo '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
													}
												}else if($proof_reader_id[0]['status'] == 2)
												{
													echo '<a class="btn btn-default" onclick="'.$view_url.'" ><i class="fa fa-eye"></i> View </a>';
													echo '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
												}else if($proof_reader_id[0]['status'] == 5)
												{
													echo '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
													echo '<a class="btn btn-default" onclick="'.$re_assign_url.'" ><i class="fa fa-pencil-square-o"></i> Re-Assign </a>';
													echo '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/complete_assignment/'.$data['id'].'" ><i class="fa fa-check"></i> Complete </a>';	
												}
											}
										}else if($data['assign_to_ma_status'] == 5){
											echo '<a class="btn btn-default" href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$data['id'].'" ><i class="fa fa-eye"></i> View </a>';
										}
										?>
										
									</td>
								</tr>
							<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<!--<th>  </th>-->
									<th> ID </th>
									<th> Name </th>
									<th> Actions Time </th>
									<th> Client Name </th>
									<th> Deadline </th>
									<th> Type </th>
									<th> Admin </th>
									<th> Writer </th>
									<th> Proof Reader </th>
									<th> Status Manager </th>
									<th> Status Writer </th>
									<th> Status Proof Reader </th>
									<th> Actions </th> 
								</tr>
							</tfoot>
						</table>
						
							<div class="modal fade" id="assign">
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
												
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Assign</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<div class="modal fade" id="re_assign">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_dashboard/re_assign_to_list" enctype="multipart/form-data">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">Re-Assignment Assign To Writer And Proof Reader</h4>
											</div>
											<div class="modal-body">
												<input type="hidden" name="re_assignment_list_id" id="re_assignment_list_id" value="">
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
												
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Assign</button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<script>
								$(document).ready(function() {
									$('#employee-grid').DataTable( {
										"processing": true,
										"serverSide": true,
										"ajax": "<?php echo base_url(); ?>index.php/Manager_dashboard/user_data_get",
										"deferLoading": <?php echo $count; ?>,
										"order": [[ 2, "desc" ]]
									} );
								} );
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
							
					</div>
				</div>
			</div>
		</div>
		<!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->