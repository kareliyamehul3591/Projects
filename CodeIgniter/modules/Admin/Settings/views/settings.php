<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1> Settings </h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"> Settings</li>
		</ol>
	</section>

  
		  
    <section class="content">
	
		<div class="row">
		
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Type</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm type_add" data-toggle="modal" data-target="#type_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
					<?php $types = $this->Mdl_settings->get('dropdowns_type'); ?>
					  <table class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($types as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td>
										<a href="#" class="type_edit" data-toggle="modal" data-target="#type_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_type" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="type_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.type_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="type_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Type Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.type_add').click(function() {
															document.getElementById("type_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_type" >
													<input class="form-control" name="name" id="type_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="type_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Type Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.type_edit').click(function() {
															document.getElementById("type_id").value = $(this).attr('data-id');
															document.getElementById("type_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_type" >
													<input type="hidden" name="id" id="type_id" value="" >
													<input class="form-control" name="name" id="type_name" placeholder="Name" type="text" required>
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Niche</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm niche_add" data-toggle="modal" data-target="#niche_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $niches = $this->Mdl_settings->get('dropdowns_niche'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($niches as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="niche_edit" data-toggle="modal" data-target="#niche_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_niche" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="niche_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.niche_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="niche_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Niche Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.niche_add').click(function() {
															document.getElementById("niche_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_niche" >
													<input class="form-control" name="name" id="niche_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="niche_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Niche Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.niche_edit').click(function() {
															document.getElementById("niche_id").value = $(this).attr('data-id');
															document.getElementById("niche_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_niche" >
													<input type="hidden" name="id" id="niche_id" value="" >
													<input class="form-control" name="name" id="niche_name" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<!--<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Client</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm client_add" data-toggle="modal" data-target="#client_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $client = $this->Mdl_settings->get('dropdowns_client'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($client as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="client_edit" data-toggle="modal" data-target="#client_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_client" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="client_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.client_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="client_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Client Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.client_add').click(function() {
															document.getElementById("client_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_client" >
													<input class="form-control" name="name" id="client_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						
						<div class="modal fade" id="client_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Client Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.client_edit').click(function() {
															document.getElementById("client_id").value = $(this).attr('data-id');
															document.getElementById("client_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_client" >
													<input type="hidden" name="id" id="client_id" value="" >
													<input class="form-control" name="name" id="client_name" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			
			<!-- /.modal-content -->
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Sex</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm sex_add" data-toggle="modal" data-target="#sex_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $Sex = $this->Mdl_settings->get('dropdowns_sex'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($Sex as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="sex_edit" data-toggle="modal" data-target="#sex_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_sex" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="sex_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.sex_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="sex_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Sex Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.sex_add').click(function() {
															document.getElementById("sex_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_sex" >
													<input class="form-control" name="name" id="sex_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="sex_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Sex Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.sex_edit').click(function() {
															document.getElementById("sex_id").value = $(this).attr('data-id');
															document.getElementById("sex_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_sex" >
													<input type="hidden" name="id" id="sex_id" value="" >
													<input class="form-control" name="name" id="sex_name" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Payment Type</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm payment_add" data-toggle="modal" data-target="#payment_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $payment = $this->Mdl_settings->get('dropdowns_payment'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($payment as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="payment_edit" data-toggle="modal" data-target="#payment_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_payment" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="payment_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.payment_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="payment_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Payment Type Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.payment_add').click(function() {
															document.getElementById("payment_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_payment" >
													<input class="form-control" name="name" id="payment_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="payment_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Payment Type Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.payment_edit').click(function() {
															document.getElementById("payment_id").value = $(this).attr('data-id');
															document.getElementById("payment_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_payment" >
													<input type="hidden" name="id" id="payment_id" value="" >
													<input class="form-control" name="name" id="payment_name" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Select one time (From, To)</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#time_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $time = $this->Mdl_settings->get('dropdowns_time'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($time as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="time_edit" data-toggle="modal" data-target="#time_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_time" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="time_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.time_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="time_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Select one time Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													
													<input type="hidden" name="table" id="table" value="dropdowns_time" >
													<div class="bootstrap-timepicker">
														<div class="form-group">
														  <div class="input-group">
															<input class="form-control timepicker" name="name" id="name" placeholder="Name" type="text" required >
															<div class="input-group-addon">
															  <i class="fa fa-clock-o"></i>
															</div>
														  </div>
														  <!-- /.input group -->
														</div>
														<!-- /.form group -->
													</div>
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="time_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Select one time Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.time_edit').click(function() {
															document.getElementById("time_id").value = $(this).attr('data-id');
															document.getElementById("time_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_time" >
													<input type="hidden" name="id" id="time_id" value="" >
													
													<div class="bootstrap-timepicker">
														<div class="form-group">
														  <div class="input-group">
															<input class="form-control timepicker" name="name" id="time_name" placeholder="Name" type="text" required >
															<div class="input-group-addon">
															  <i class="fa fa-clock-o"></i>
															</div>
														  </div>
														  <!-- /.input group -->
														</div>
														<!-- /.form group -->
													</div>
													
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Re-Assign/QC_Failed</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm qc_failed_add" data-toggle="modal" data-target="#qc_failed_add" ><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php $qc_failed = $this->Mdl_settings->get('dropdowns_qc_failed'); ?>
					  <table id="datatable_asc" class="table table-bordered table-striped datatable_asc_1">
						<thead>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($qc_failed as $data){ ?>
								<tr>
									<td> <?php echo $data['id'];?> </td>
									<td> <?php echo $data['name'];?> </td>
									<td> 
										<a href="#" class="qc_failed_edit" data-toggle="modal" data-target="#qc_failed_edit" data-id="<?php echo $data['id'];?>" data-name="<?php echo $data['name'];?>" style="color: #089048;"><i class="fa fa-pencil  fa-lg"></i></a>
										<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/deletes" enctype="multipart/form-data">
											<input type="hidden" name="table" id="table" value="dropdowns_qc_failed" >
											<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" >
											<a class="qc_failed_delete<?php echo $data['id']; ?>" style="color: #dd4b39;"><i class="fa fa-trash-o fa-lg"></i></a>
											<script>
												$('.qc_failed_delete'+<?php echo $data['id']; ?>).click(function() {
													
													var images_r = confirm("Are you sure want to delete?");
													if (images_r == true) {
														$(this).closest('form').submit();
													}
												});
											</script>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th> ID </th>
								<th> Name </th>
								<th> Actions </th>
							</tr>
						</tfoot>
					  </table>
					  
						<div class="modal fade" id="qc_failed_add">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/add" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Re-Assign/QC_Failed  Add</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													<script>
														$('.qc_failed_add').click(function() {
															document.getElementById("qc_failed_name_add").value = "";
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_qc_failed" >
													<input class="form-control" name="name" id="qc_failed_name_add" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Add</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
						<div class="modal fade" id="qc_failed_edit">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/edit" enctype="multipart/form-data">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span></button>
											<h4 class="modal-title">Re-Assign/QC_Failed  Edit</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xs-2"><br/></div>
												<div class="col-xs-8">
													 <script>
														$('.qc_failed_edit').click(function() {
															document.getElementById("qc_failed_id").value = $(this).attr('data-id');
															document.getElementById("qc_failed_name").value = $(this).attr('data-name');
														});
													 </script>
													<input type="hidden" name="table" id="table" value="dropdowns_qc_failed" >
													<input type="hidden" name="id" id="qc_failed_id" value="" >
													<input class="form-control" name="name" id="qc_failed_name" placeholder="Name" type="text" required >
												</div>
												<div class="col-xs-2"><br/></div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Edit</button>
										</div>
									</form>
								</div>
								<!-- /.modal-content -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
		</div>
		  
		
    </section>
    <!-- /.content -->
  </div>