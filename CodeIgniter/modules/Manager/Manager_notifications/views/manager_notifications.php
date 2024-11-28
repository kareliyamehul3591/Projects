<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Notifications
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Notifications</li>
      </ol>
    </section>
		<?php 
		//echo '<pre>';
		//print_r($manager_notifications);
		//echo '</pre>';
		
		?>
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
			
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">View Notifications Table</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped datatable_asc">
							<thead>
								<tr>
									<th> ID </th>
									<th> Assignment ID, User ID</th>
									<th> Assignment Name, User Name </th>
									<th> From Name </th>
									<th> From Role </th>
									<th> Last Activity Time </th>
									<th> Deadline </th>
									<th> Notification Message </th>
									<th> Status </th>
									<th> Action </th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($manager_notifications as $key => $data) { 
							$key++;
							$from_id = $this->Mdl_manager_notifications->user_name($data['from_id']);
							$to_id = $this->Mdl_manager_notifications->user_name($data['to_id']);
							$assignment_id = $this->Mdl_manager_notifications->assignment($data['assignment_id']);
							?>
								<tr>
									<td> <?php echo $key; ?> </td>
									<td> 
										<?php 
											if($data['assignment_id'] == 0)
											{
												echo $data['from_id']; 
											}else{
												echo $assignment_id[0]['id']; 
											}
										?> 
									</td>
									
									<td> 
										<?php 
											if($data['assignment_id'] == 0)
											{
												echo $from_id[0]['name']; 
											}else{
												echo $assignment_id[0]['name']; 
											}
										?> 
									</td>
									<td> <?php echo $from_id[0]['name']; ?> </td>
									<td> <?php echo $data['from_role']; ?> </td>
									<td> <?php echo date('m/d/Y h:i a', strtotime( $data['created_date'] )); ?> </td>
									<td><?php echo date('m/d/Y', strtotime( $assignment_id[0]['deadline_date'] )).' '.date('h:i a', strtotime( $assignment_id[0]['deadline_time'] )); ?></td>
									<td> 
										<?php 
											if($data['assignment_id'] == 0){
												echo 'User '.$from_id[0]['name'].' '.$data['status'];
											}else if($data['status'] == 'Quality check Failed')
											{
												echo 'Assignment '.$data['assignment_id'].' '.$data['status'];
											}else{
												echo $data['status'].' Assignment '.$data['assignment_id']; 
											}
										?>
									</td>
									<td> 
										<?php if($data['online'] == 0) {?>
											<span class="label label-danger">Unread</span>
										<?php }else{ ?>
											<span class="label label-success">Read</span>
										<?php } ?> 
										
									</td>
									<td> 
										<?php if($data['assignment_id'] != 0) {?>
											<?php if($data['status'] != 'Deleted') {?>
												<a href="<?php echo base_url(); ?>index.php/Manager_dashboard/assignment_view/<?php echo $data['assignment_id']; ?>" style="color: #13c5ff;" ><i class="fa fa-eye fa-lg"></i></a>
											<?php } ?>
												<a href="<?php echo base_url(); ?>index.php/Manager_message" style="color: #2c893a;"><i class="fa fa-commenting fa-lg"></i></a>
												<?php
												$logs_url = "myModal('".base_url()."index.php/Dashboard/logs_taske/".$data['assignment_id']."')";
												echo '<a onclick="'.$logs_url.'" style="color: #23527c;"><i class="fa fa-tasks fa-lg"></i></a>';
												?>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th> ID </th>
									<th> Assignment ID, User ID</th>
									<th> Assignment Name, User Name </th>
									<th> From Name </th>
									<th> From Role </th>
									<th> Last Activity Time </th>
									<th> Deadline </th>
									<th> Notification Message </th>
									<th> Status </th>
									<th> Action </th>
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
	</section>
  </div>
  <!-- /.content-wrapper -->