<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Assignment
      </h1>
      <ol class="breadcrumb">
		<li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Add Assignment</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Assignment</h3>
            </div>
            <div class="box-body">
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_assignment/assignment_add" id="assignment_add" enctype="multipart/form-data" >
					<div class="row">
						<?php 
						$assignments = $this->Mdl_manager_assignment->assignments($_POST['id']);
						$data = $assignments[0]
						?>
						<input type="hidden" name="reason" id="reason"  value="<?php echo $_POST['reason']; ?>">
						<input type="hidden" name="files" id="files"  value="<?php echo $data['file']; ?>">
						<div class="col-xs-6">
							<input type="text" name="name" id="name" class="form-control" placeholder="Name *" value="<?php echo $data['name']; ?>" required >
						</div>
						<div class="col-xs-6">
							<?php $clients = $this->Mdl_manager_assignment->dropdowns('client'); ?>
							<select class="form-control" name="client_name" id="client_name" required >
								<option value="">Select One Client Name *</option>
								<?php foreach($clients as $client){ if($client['status'] == 1){
								if($data['client_name'] == 'C'.$client['id']){ ?>
									<option value="C<?php echo $client['id'];?>" selected >C<?php echo $client['id'];?></option>
								<?php }else{ ?>
									<option value="C<?php echo $client['id'];?>">C<?php echo $client['id'];?></option>
								<?php } } } ?> 
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<div class="bootstrap-timepicker">
								<div class="form-group">
								  <div class="input-group">
									<?php if($data == null){ ?>
										<input type="text" name="deadline_date" id="deadline_date" class="form-control datepicker" placeholder="Deadline Date *" required >
									<?php }else{ ?>
										<input type="text" name="deadline_date" id="deadline_date" class="form-control datepicker" placeholder="Deadline Date *" value="<?php echo date('m/d/Y h:i A', strtotime( $data['deadline_date'].' '.$data['deadline_time'] )); ?>" required >
									<?php } ?>
									<div class="input-group-addon">
									  <i class="fa fa-calendar"></i>
									</div>
								  </div>
								</div>
							</div>
						</div> 
						<div class="col-xs-6">
							<?php $types = $this->Mdl_manager_assignment->dropdowns('dropdowns_type'); ?>
							<select class="form-control" name="assignment_type" id="assignment_type">
								<option value="">Select One Assignment Type *</option>
								<?php foreach($types as $typee){ 
								if($data['assignment_type'] == $typee['name']){ ?>
									<option value="<?php echo $typee['name'];?>" selected ><?php echo $typee['name'];?></option>
								<?php }else{ ?>
									<option value="<?php echo $typee['name'];?>"><?php echo $typee['name'];?></option>
								<?php } } ?>
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<select class="form-control" name="tasks_no" id="tasks_no">
								<option value="">Select One No Of Tasks *</option>								
								<?php for($x = 1; $x <= 100; $x++){
									if($data['tasks_no'] == $x){ ?>
									<option value="<?php echo $x; ?>" selected ><?php echo $x; ?></option>
								<?php }else{ ?>
									<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
								<?php } }?>
							</select>
						</div>
						<div class="col-xs-6">
							<?php $niches = $this->Mdl_manager_assignment->dropdowns('dropdowns_niche'); ?>
							<select class="form-control" name="health" id="health">
								<option value="">Niche *</option>
								<?php foreach($niches as $niche){ 
								if($data['health'] == $niche['name']){ ?>
									<option value="<?php echo $niche['name'];?>" selected ><?php echo $niche['name'];?></option>
								<?php }else{ ?>
									<option value="<?php echo $niche['name'];?>"><?php echo $niche['name'];?></option>
								<?php } } ?>
							</select>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="number" name="article" id="article" class="form-control" placeholder="No. of Words/Article *" value="<?php echo $data['article']; ?>" required >
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-12">
							<textarea id="description" name="description" placeholder="Description" ><?php echo $data['description']; ?></textarea>
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="file" name="file[]" id="file" class="form-control" accept=".txt,.doc,.docx,.xls,.xlsx,.pdf,.zip,.jpeg,.png,.jpg" multiple >
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
					</div></br>
					<div class="box-footer">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Send for Admin Approval</button>
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
						<a href="<?php echo base_url(); ?>index.php/Manager_dashboard" type="button" class="btn btn-primary">Leave Page</a>
					  </div>
					</div>
				  </div>
				</div>
				
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  
		  
    </section>
    <!-- /.content -->
  </div>