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
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Manager_search" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xs-2"><br/></div>
						<div class="col-xs-3">
							<input class="form-control" name="name" id="name" placeholder="Name" type="text" value="<?php echo $this->session->manager_search['name'];?>" required >
						</div>
						<div class="col-xs-3">
							<select class="form-control" id="users" name="users" required="">
								<option value="assignment_id" <?php if($this->session->manager_search['users'] == 'assignment_id'){ echo 'selected'; } ?> >Assignment ID</option>
								<option value="assignment_name" <?php if($this->session->manager_search['users'] == 'assignment_name'){ echo 'selected'; } ?> >Assignment Name</option>
								<option value="users_id" <?php if($this->session->manager_search['users'] == 'users_id'){ echo 'selected'; } ?> >Users ID</option>
								<option value="users_name" <?php if($this->session->manager_search['users'] == 'users_name'){ echo 'selected'; } ?> >Users Name</option>
								<option value="users_first_name" <?php if($this->session->manager_search['users'] == 'users_first_name'){ echo 'selected'; } ?> >Users First Name</option>
								<option value="users_last_name" <?php if($this->session->manager_search['users'] == 'users_last_name'){ echo 'selected'; } ?> >Users Last Name</option>
							</select>
						</div>
						<div class="col-xs-2">
							<button type="submit" class="btn btn-block btn-primary">Search</button>
						</div>
						<div class="col-xs-2"><br/></div>
					</div>
				</form><br/><br/>
				<?php
				foreach($datas as $users)
				{
					if($users['users'] == 'users') { ?>
						<div class="row">
							<div class="col-xs-3"><br/></div>
							<div class="col-xs-6">
								<div  class="info-box bg-aqua"> <!--style="background-color: #00a65a !important;" --> 
									<span class="info-box-icon"><i class="ion ion-ios-people-outline"></i></span>
									<div class="info-box-content">
										<span class="info-box-number"><?php echo $users['name'].' &nbsp;&nbsp;('.ucfirst(str_replace("_"," ",$users['role'])).')'; ?></span>
										<span class="progress-description">First Name: <?php echo $users['first_name']; ?></span>
										<span class="progress-description">Lastst Name: <?php echo $users['lastst_name']; ?></span>
										<span class="progress-description">Created On: <?php echo date('m/d/Y H:i:s', strtotime( $users['created_date'] )); ?></span>
										<span class="progress-description">Status : <?php if($users['status'] == 1){ echo 'Active'; }else if($users['status'] == 0){  echo 'Inactive'; }else if($users['status'] == 2){  echo 'Deleted'; } ?></span>
									</div>
								</div>
							</div>
							<div class="col-xs-3"><br/></div>
						</div>
					<?php }
					
				}
				foreach($datas as $assignment)
				{
					if($assignment['users'] == 'assignment')
					{
						$manager_id = $this->Mdl_manager_search->manager_id($assignment['id']);
						$proof_reader_id = $this->Mdl_manager_search->proof_reader_id($assignment['id']);
						$writer_id = $this->Mdl_manager_search->writer_id($assignment['id']);
					?>
						<div class="row">
							<div class="col-xs-3"><br/></div>
							<div class="col-xs-6">
							
								<a href="<?php echo base_url(); ?>index.php/Manager_dashboard/assignment_view/<?php echo $assignment['id'];?>" class="info-box bg-yellow">
									<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
									<div class="info-box-content">
										<span class="info-box-number"><?php echo $assignment['id'].' '.$assignment['name']; ?></span>
										<span class="progress-description">Created On: <?php echo date('m/d/Y H:i:s', strtotime( $users['created_date'] )); ?></span>
										<span class="progress-description">Status : 
											<?php 
												if($manager_id != null)
												{
													if($assignment['status'] == 7){
														echo 'QC_Failed';
													}else if($manager_id[0]['status'] == 1){
														if($manager_id[0]['manager_status'] == 2)
														{
															echo 'Re-assigne';
														}else if($manager_id[0]['manager_status'] == 1){
															echo 'Assigned';
														}else{
															echo 'Accepted';
														}
													}else if($manager_id[0]['status'] == 2){
														echo 'Rejected';
													}else if($manager_id[0]['status'] == 5){
														echo 'Complete';
													}else if($manager_id[0]['status'] == 6){
														echo 'Re-Assigned';
													}else if($manager_id[0]['status'] == 0){
														echo 'New';
													}
												}else if($assignment['status'] == 0){
													if($assignment['created_role'] == "manager"){
														echo 'Waiting for Approval';
													}
												}else if($assignment['status'] == 2){
													if($assignment['created_role'] == "manager"){
														echo 'Denied';
													}
												}else if($assignment['status'] == 1){
													if($assignment['created_role'] == "manager"){
														echo 'Approved';
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
				if($this->session->manager_search != null){
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