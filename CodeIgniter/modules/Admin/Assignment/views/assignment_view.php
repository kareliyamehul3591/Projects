  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Assignment
        <small>View Assignment tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
				<h3 class="box-title">View Assignment Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			
			<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Assignment/assignment_delete" enctype="multipart/form-data">
			
				<button type="submit" name="assignment_list_delete" value="delete" class="btn btn-primary" ><i class="fa fa-trash-o"></i> Delete </button>
				<br/><br/>
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
							<th> Client Name </th>
							<th> Deadline </th>
							<th> Type </th>
							<th> Actions </th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($datas as $data) { ?>
						<tr>
							<td>
								<input type="checkbox" class="minimal" name="select_assignment_list[]" value="<?php echo $data['id']; ?>" >
							</td>
							<td><?php echo $data['id']; ?></td>
							<td><?php echo $data['name']; ?></td>
							<td><?php echo date('m/d/Y h:i A', strtotime( $data['created_date'] )); ?></td>
							<td><?php echo $data['client_name']; ?></td>
							<td><?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.$data['deadline_time']; ?></td>
							<td><?php echo $data['assignment_type']; ?></td>
							<td>
								<?php 	
									$view_url = "myModal('".base_url()."index.php/Dashboard/view_taske/".$data['id']."')";
									$edit_url = "myModal('".base_url()."index.php/Dashboard/edit_taske/".$data['id']."')";
									$approval_url = "myModal('".base_url()."index.php/Assignment/approval_taske/".$data['id']."')";
									
									echo '<a onclick="'.$view_url.'" style="color: #13c5ff;"><i class="fa fa-eye fa-lg"></i></a>';
									echo '<a onclick="'.$edit_url.'" style="color: #089048;"><i class="fa fa-pencil fa-lg"></i></a>';
									
									echo '<a onclick="'.$approval_url.'" style="color: #16ec7b;"><i class="fa fa-check fa-lg"></i></a>';							
								?>		
								<a href="<?php echo base_url(); ?>index.php/Assignment/assignment_delete/<?php echo $data['id']; ?>" style="color: #ff0808;"><i class="fa fa-trash-o fa-lg"></i></a>
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
							<th> Client Name </th>
							<th> Deadline </th>
							<th> Type </th>
							<th> Actions </th>
						</tr>
					</tfoot>
				</table>
			</form>
				<script>
					function remove_file(ids,id)
					{
						var images_r = confirm("Are you sure want to delete?");	
						if (images_r == true) {
							$.ajax({
								type:'POST',
								url: '<?php echo base_url(); ?>index.php/Assignment/remove_file',
								data:{ id : id , ids : ids },
								success:function(data){
									location.reload();
								}
							});
						}
					}
				</script>
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
