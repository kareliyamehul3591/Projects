  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Client
        <small>Client tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Client</li>
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
		
		$active_users = 0;
		$inactive_users = 0;
		$deleted_users = 0;
		foreach($datas as $data)
		{
			if($data['status'] == 1)
			{
				$active_users++;
			}else if($data['status'] == 0)
			{
				$inactive_users++;
			}else if($data['status'] == 2)
			{
				$deleted_users++;
			}
		}
		?>
		<div class="row">		
		
			<div class="col-lg-4 col-xs-6">
			  <div class="small-box bg-green" onclick="onclick_box('Active')" >
				<div class="inner">
				  <h3><?php echo $active_users;?></h3>
				  <p>Active <br/> Client</p>
				</div>
				<div class="icon">
				  <i class="fa fa-check"></i>
				</div>
			  </div>
			</div>		
			
			<div class="col-lg-4 col-xs-6">
			  <div class="small-box bg-primary" onclick="onclick_box('Inactive')" >
				<div class="inner">
				  <h3><?php echo $inactive_users; ?></h3>
				  <p>Inactive <br/> Client</p>
				</div>
				<div class="icon">
				  <i class="fa fa-times-circle"></i>
				</div>
			  </div>
			</div>
			
			<div class="col-lg-4 col-xs-6">
			  <div class="small-box bg-red" onclick="onclick_box('Deleted')">
				<div class="inner">
				  <h3><?php echo $deleted_users; ?></h3>
				  <p>Deleted <br/> Client</p>
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
				$('.actions_datatable').DataTable().columns( 11 ).search( id ).draw();
			}
		</script>
      <div class="row">
        <div class="col-xs-12">
		
          <div class="box box-info">
            <div class="box-header">
				<h3 class="box-title">Client Table</h3>
				<div class="pull-right box-tools">
					<a type="button" class="btn btn-info btn-sm type_add" href="<?php echo base_url(); ?>index.php/Client/edit_client" >Add Client</a>
				</div>
				
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<style>
				td a i {
					margin-left: 4px;
					margin-right: 4px;
				}
				</style>
				<input type="checkbox" id="selectalls"/> &nbsp; Select All &emsp;
					<a class="btn btn-success" onclick="active_user(1)" ><i class="fa fa-check fa-lg"></i> Active  </a>
					<a class="btn btn-primary" onclick="active_user(0)" ><i class="fa fa-times-circle fa-lg"></i> Inactive  </a>
					<a class="btn btn-danger" onclick="active_user(2)" ><i class="fa fa-trash-o fa-lg"></i> Deleted  </a>
					
					<script>
						$(document).ready(function(){
							$('#selectalls').on('click',function(){
								if(this.checked){
									$('.select_user').each(function(){
										this.checked = true;
									});
								}else{
									 $('.select_user').each(function(){
										this.checked = false;
									});
								}
							});
							$('.select_user').on('click',function(){
								if($('.select_user:checked').length == $('.select_user').length){
									$('#selectalls').prop('checked',true);
								}else{
									$('#selectalls').prop('checked',false);
								}
							});
						});
						function active_user(status)
						{
							if(status == '0')
							{
								var images_r = confirm("Do you want to inactivate?");
							}else if(status == '1')
							{
								var images_r = confirm("Do you want to activate ?");
							}else if(status == '2')
							{
								var images_r = confirm("Do you want to delete?");
							}
							
							if (images_r == true) {
							
								var all_location_id = document.querySelectorAll('input[name="select_client_list[]"]:checked');
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
									url: '<?php echo base_url(); ?>index.php/Client/active_client_list',
									data: { id: y,status: status }, 
									success: function(data){
										location.reload();
									},
									error: function(){
										console.log("data not found");
									}
								});
							}
						}
					</script>
					
				<br/><br/>
				<table id="employee-grid"  class="table table-bordered table-striped actions_datatable">
					<thead>
						<tr>
							<th> </th>
							<th> Client ID </th>
							<th> Client Name </th>
							<th> Last Activity Time </th>
							<th> Added On </th>
							<th> Email ID </th>
							<th> Content No. </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</thead>
					<tbody>
					<?php 
					foreach($datas as $data) { ?>
						<tr>
							<td> 
								<input type="checkbox" class="select_user" name="select_client_list[]" value="<?php echo $data['id']; ?>" >
							</td>
							<td>C<?php echo $data['id']; ?></td>
							<td><?php echo $data['first_name'].' '.$data['lastst_name']; ?></td>
							<td><?php echo $data['updated_date']; ?></td>
							<td><?php echo $data['created_date']; ?></td>
							<td><?php echo $data['email']; ?></td>
							<td><?php echo $data['phone_number']; ?></td>
							
							<td align="center"> 
								<?php 
									if($data['status'] == 1)
									{
										echo '<span class="label label-success">Active</span>';
									}else if($data['status'] == 0)
									{
										echo '<span class="label label-primary">Inactive</span>';
									}else if($data['status'] == 2)
									{
										echo '<span class="label label-danger">Deleted</span>';
									}
								?>
							</td>
							<td>
								<?php
									$i = 0;
									$assignment = $this->Mdl_client->assignment('C'.$data['id']);
									foreach($assignment as $assignmen)
									{
										if($assignmen['status'] != 5 && $assignmen['status'] != 4 && $assignmen['status'] != 6)
										{
											$i++;
										}
										
									}
									
									echo '<a href="'.base_url().'index.php/Client/view_client/'.$data['id'].'" style="color: #13c5ff;"><i class="fa fa-eye fa-lg"></i></a>';
									echo '<a href="'.base_url().'index.php/Client/edit_client/'.$data['id'].'" style="color: #089048;"><i class="fa fa-pencil fa-lg"></i></a>';
									
										if($data['status'] == 1)
										{
											if($i == 0){
												echo '<a onclick="suspended('.$data['id'].',0)" style="color: #337ab7;"><i class="fa fa-times-circle fa-lg"></i></a>';
												echo '<a onclick="suspended('.$data['id'].',2)" style="color: #ff0808;"><i class="fa fa-trash-o fa-lg"></i></a>';
											}
										}else if($data['status'] == 0)
										{
											echo '<a onclick="suspended('.$data['id'].',1)" style="color: #16ec7b;"><i class="fa fa-check fa-lg"></i></a>';
											echo '<a onclick="suspended('.$data['id'].',2)" style="color: #ff0808;"><i class="fa fa-trash-o fa-lg"></i></a>';
										}else if($data['status'] == 2)
										{
											echo '<a onclick="suspended('.$data['id'].',1)" style="color: #16ec7b;"><i class="fa fa-check fa-lg"></i></a>';
										}
								?>
							</td>
						</tr>
					<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th> </th>
							<th> Client ID </th>
							<th> Client Name </th>
							<th> Last Activity Time </th>
							<th> Added On </th>
							<th> Email ID </th>
							<th> Content No. </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</tfoot>
				</table>
				<script>
					function suspended(id,status) {
						if(status == '0')
						{
							var images_r = confirm("Do you want to inactivate?");
						}else if(status == '1')
						{
							var images_r = confirm("Do you want to activate ?");
						}else if(status == '2')
						{
							var images_r = confirm("Do you want to delete?");
						}
						if (images_r == true) {
							$.ajax({
								type: "POST",
								url: '<?php echo base_url(); ?>index.php/Client/active_client',
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
					}
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
