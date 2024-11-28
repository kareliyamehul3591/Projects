  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Assignment
        <small>View Assignment Data</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">View Assignment Data</li>
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
      <div class="row">
        <div class="col-xs-12">
		
          <div class="box box-info">
            <div class="box-header">
				<h3 class="box-title">View Assignment Data</h3>
            </div>
            <!-- /.box-header -->
			<?php
				$assignment_data = $datas[0];
			?>
            <div class="box-body">
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Name :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['name']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Client Name :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['client_name']; ?>
							</div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Deadline :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['deadline_date']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Type Of Assignment :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['assignment_type']; ?>
							</div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">No of Taske :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['tasks_no']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Niche :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['health']; ?>
							</div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">No of Words/Article :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['article']; ?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">Status :-</label>
							<div class="col-sm-7">
								<?php echo $assignment_data['status']; ?>
							</div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Description :-</label>
							<div class="col-sm-9">
								<?php echo $assignment_data['description']; ?>
							</div>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-5 control-label">File :-</label>
							<div class="col-sm-7">
								<?php 									
								foreach(explode(",", $assignment_data['file']) as $file)									
								{ ?>
									<a href="<?php echo base_url();?>uploads/Assignment/<?php echo $file; ?>" target='_blank'>
										<?php echo $file; ?>
									</a>
									<br/>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				</br>
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
