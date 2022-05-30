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
		<!-- Main row -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">View Assignment Table</h3> 
					</div>
					<div class="box-body">
						<style>
						td a i {
							margin-left: 4px;
							margin-right: 4px;
						}
						</style>
						<table class="table table-bordered table-striped actions_datatable_2">
							<thead>
								<tr>
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
												if($data['status'] == 5){
													echo '<span class="label label-success">Complete</span>';
												}else if($data['status'] == 4){
													echo '<span class="label label-danger">Cancelled</span>';
												}else if($data['status'] == 7){
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
												if($data['status'] == 5){
													echo '<span class="label label-success">Complete</span>';
												}else if($data['status'] == 4){
													echo '<span class="label label-danger">Cancelled</span>';
												}else if($data['status'] == 7){
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
													if($data['status'] == 4){
														echo '<span class="label label-danger">Cancelled</span>';
													}else if($data['status'] == 7){
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
													if($data['status'] == 4){
														echo '<span class="label label-danger">Cancelled</span>';
													}else if($data['status'] == 7){
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
													if($data['status'] == 4){
														echo '<span class="label label-danger">Cancelled</span>';
													}else if($data['status'] == 7){
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
											$logs_url = "myModal('".base_url()."index.php/Dashboard/logs_taske/".$data['id']."')";
											
											echo '<a href="'.base_url().'index.php/Writer_dashboard/assignment_view/'.$data['id'].'" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>';
											
											echo '<a href="'.base_url().'index.php/Writer_message" style="color: #2c893a;"><i class="fa fa-commenting fa-lg"></i></a>';
											echo '<a onclick="'.$logs_url.'" style="color: #23527c;"><i class="fa fa-tasks fa-lg"></i></a>';
											?> 
										</td>
									</tr>
									<?php } ?>
							</tbody>
							<tfoot>
								<tr>
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