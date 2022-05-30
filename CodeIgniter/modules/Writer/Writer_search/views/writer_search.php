<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Search
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Search</li>
      </ol>
    </section>

    <section class="content">

	
		<div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Search</h3>
            </div>
            <div class="box-body">
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_search" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xs-2"><br/></div>
						<div class="col-xs-3">
							<input class="form-control" name="name" id="name" placeholder="Name" type="text" value="<?php echo $this->session->writer_search['name'];?>" required >
						</div>
						<div class="col-xs-3">
							<select class="form-control" id="users" name="users" required="">
								<option value="assignment_id" <?php if($this->session->writer_search['users'] == 'assignment_id'){ echo 'selected'; } ?> >Assignment ID</option>
								<option value="assignment_name" <?php if($this->session->writer_search['users'] == 'assignment_name'){ echo 'selected'; } ?> >Assignment Name</option>
							</select>
						</div>
						<div class="col-xs-2">
							<button type="submit" class="btn btn-block btn-primary">Search</button>
						</div>
						<div class="col-xs-2"><br/></div>
					</div>
				</form><br/><br/>
				<?php
				foreach($datas as $assignment)
				{
					if($assignment['users'] == 'assignment')
					{
						$manager_id = $this->Mdl_writer_search->manager_id($assignment['id']);
						$proof_reader_id = $this->Mdl_writer_search->proof_reader_id($assignment['id']);
						$writer_id = $this->Mdl_writer_search->writer_id($assignment['id']);
					?>
						<div class="row">
							<div class="col-xs-3"><br/></div>
							<div class="col-xs-6">
							
								<a href="<?php echo base_url(); ?>index.php/Writer_dashboard/assignment_view/<?php echo $assignment['id'];?>" class="info-box bg-yellow">
									<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
									<div class="info-box-content">
										<span class="info-box-number"><?php echo $assignment['id'].' '.$assignment['name']; ?></span>
										<span class="progress-description">Created On: <?php echo date('m/d/Y H:i:s', strtotime( $users['created_date'] )); ?></span>
										<span class="progress-description">Status : 
											<?php
												if($writer_id != null)
												{
													if($assignment['status'] == 4){
														echo 'Cancelled';
													}else if($assignment['status'] == 7){
														echo 'QC_Failed';
													}else if($writer_id[0]['status'] == 1){
														echo 'Accepted';
													}else if($writer_id[0]['status'] == 2){
														echo 'Rejected';
													}else if($writer_id[0]['status'] == 5){
														echo 'Complete';
													}else if($writer_id[0]['status'] == 6){
														echo 'Re-Assigned';
													}else if($writer_id[0]['status'] == 0){
														echo 'New';
													}
												} 
											?></span>
									</div>
								</a>
								
							</div>
							<div class="col-xs-3"><br/></div>
						</div>
					<?php }
				}
				?>
				<div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
				  <?php echo $this->pagination->create_links(); ?>
				   </ul> 
				</div>
				
				<?php
				if($this->session->writer_search != null){
					if($datas == null){?>
					<div class="row">
						<div class="col-xs-3"><br/></div>
						<div class="col-xs-6">
							<h3><i class="fa fa-warning text-yellow"></i> No matches found.</h3>
						</div>
						<div class="col-xs-3"><br/></div>
					</div>
				
				<?php } }?>
				
            </div>
		</div>
		  
    </section>
  </div>