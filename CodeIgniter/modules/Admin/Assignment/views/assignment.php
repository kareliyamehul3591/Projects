<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Assignment
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Add Assignment</li>
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
		<div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Assignment</h3>
            </div>
            <div class="box-body">
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Assignment/assignment_add" id="assignment_add" enctype="multipart/form-data" >
					<div class="row">
						<div class="col-xs-6">
							<input type="text" name="name" id="name" class="form-control" placeholder="Name *" required >
						</div>
						<div class="col-xs-6">
							<?php $client = $this->Mdl_assignment->dropdowns('client'); ?>
							<select class="form-control" name="client_name" id="client_name" required >
								<option value="">Select One Client Name *</option>
								<?php foreach($client as $data){
									if($data['status'] == 1){
									?>
									<option value="C<?php echo $data['id'];?>">C<?php echo $data['id'];?></option>
								<?php } } ?>
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<div class="bootstrap-timepicker">
								<div class="form-group">
								  <div class="input-group">
									<input type="text" name="deadline_date" id="deadline_date" class="form-control datepicker" placeholder="Deadline Date *" required >
									<div class="input-group-addon">
									  <i class="fa fa-calendar"></i>
									</div>
								  </div>
								</div>
							</div>
						</div>
						<?php $types = $this->Mdl_assignment->dropdowns('dropdowns_type'); ?>
						<div class="col-xs-6"> 
							<select class="form-control" name="assignment_type" id="assignment_type">
								<option value="">Select One Assignment Type *</option>
								<?php foreach($types as $data){ ?>
									<option value="<?php echo $data['name'];?>"><?php echo $data['name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<select class="form-control" name="tasks_no" id="tasks_no">
								<option value="">Select One No Of Tasks *</option>
								<?php for($x = 1; $x <= 100; $x++){?>
									<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-6">
							<?php $niches = $this->Mdl_assignment->dropdowns('dropdowns_niche'); ?>
							<select class="form-control" name="health" id="health">
								<option value="">Niche *</option>
								<?php foreach($niches as $data){ ?>
									<option value="<?php echo $data['name'];?>"><?php echo $data['name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="number" name="article" id="article" class="form-control" placeholder="No. of Words/Article *" required >
						</div>
					</div></br>
					
					<div class="row">
						<div class="col-xs-12">
							<textarea id="description" name="description" placeholder="Description" ></textarea>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<h4>Client Attachments</h4>
							<input type="file" name="file[]" id="file" class="form-control" accept=".txt,.doc,.docx,.xls,.xlsx,.pdf,.zip,.jpeg,.png,.jpg" multiple >
						</div>
						<div class="col-xs-6">
							<h4>Assignment Assign</h4>
							<div class="row">
								<div class="col-xs-12">
									<?php $all_managers = $this->Mdl_assignment->all_managers(); ?>							
									<select class="form-control" name="manager_id" id="manager_id" >
										<option value="">Assign One manager</option>
										<?php foreach($all_managers as $all_manager){ ?>
										<option value="<?php echo $all_manager['id']; ?>"><?php echo $all_manager['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div></br>
							<div class="row">
								<?php $all_writers = $this->Mdl_assignment->all_writers(); ?>
								<div class="col-xs-12">
									<select class="form-control" name="writer_id" id="writer_id" >
										<option value="">Assign One writer</option>
										<?php foreach($all_writers as $all_writer){ ?>
										<option value="<?php echo $all_writer['id']; ?>"><?php echo $all_writer['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div></br>
							<div class="row">
								<?php $all_proof_readers = $this->Mdl_assignment->all_proof_readers(); ?>
								<div class="col-xs-12">
									<select class="form-control" name="proof_reader_id" id="proof_reader_id" >
										<option value="">Assign One Proof Reader</option>
										<?php foreach($all_proof_readers as $all_proof_reader){ ?>
										<option value="<?php echo $all_proof_reader['id']; ?>"><?php echo $all_proof_reader['name']; ?></option>
										<?php } ?>
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
					<div class="box-footer">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Submit</button>
					</div>
				</form>
				
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
						<a href="<?php echo base_url(); ?>index.php/Dashboard" type="button" class="btn btn-primary">Leave Page</a>
					  </div>
					</div>
				  </div>
				</div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  <script>
			function assign_manager()
			{
				var da = $("#action").val();
				if(da == 'Assign')
				{
					$(".assign_mang").show();
				}else{
					$(".assign_mang").hide();
				}
			}
		  </script>
		  
    </section>
    <!-- /.content -->
  </div>