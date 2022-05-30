<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1> Settings Help </h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"> Settings Help </li>
		</ol>
	</section>

  
		  
    <section class="content">
		<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Settings/basic_guidelines_update" enctype="multipart/form-data">
			<h2 class="page-header">Basic Guidelines</h2>
			<div class="row">
				<div class="col-md-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#basic_admin_tab" data-toggle="tab">Admin</a></li>
							<li><a href="#basic_helpdesk_tab" data-toggle="tab">HelpDesk</a></li>
							<li><a href="#basic_manager_tab" data-toggle="tab">Manager</a></li>
							<li><a href="#basic_writer_tab" data-toggle="tab">Writer</a></li>
							<li><a href="#basic_proofReader_tab" data-toggle="tab">ProofReader</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="basic_admin_tab">
								<textarea id="basic_admin" name="basic_admin" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('basic_admin')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="basic_helpdesk_tab">
								<textarea id="basic_helpdesk" name="basic_helpdesk" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('basic_helpdesk')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="basic_manager_tab">
								<textarea id="basic_manager" name="basic_manager" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('basic_manager')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="basic_writer_tab">
								<textarea id="basic_writer" name="basic_writer" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('basic_writer')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="basic_proofReader_tab">
								<textarea id="basic_proofReader" name="basic_proofReader" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('basic_proofReader')[0]['name'];?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<h2 class="page-header">Samples</h2>
			<div class="row">
				<div class="col-md-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#samples_admin_tab" data-toggle="tab">Admin</a></li>
							<li><a href="#samples_helpdesk_tab" data-toggle="tab">HelpDesk</a></li>
							<li><a href="#samples_manager_tab" data-toggle="tab">Manager</a></li>
							<li><a href="#samples_writer_tab" data-toggle="tab">Writer</a></li>
							<li><a href="#samples_proofReader_tab" data-toggle="tab">ProofReader</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="samples_admin_tab">
								<textarea id="samples_admin" name="samples_admin" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('samples_admin')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="samples_helpdesk_tab">
								<textarea id="samples_helpdesk" name="samples_helpdesk" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('samples_helpdesk')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="samples_manager_tab">
								<textarea id="samples_manager" name="samples_manager" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('samples_manager')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="samples_writer_tab">
								<textarea id="samples_writer" name="samples_writer" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('samples_writer')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="samples_proofReader_tab">
								<textarea id="samples_proofReader" name="samples_proofReader" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('samples_proofReader')[0]['name'];?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<h2 class="page-header">Help Withe This Page</h2>
			<div class="row">
				<div class="col-md-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#help_login_tab" data-toggle="tab">Login</a></li>
							<li><a href="#help_admin_tab" data-toggle="tab">Admin</a></li>
							<li><a href="#help_helpdesk_tab" data-toggle="tab">HelpDesk</a></li>
							<li><a href="#help_manager_tab" data-toggle="tab">Manager</a></li>
							<li><a href="#help_writer_tab" data-toggle="tab">Writer</a></li>
							<li><a href="#help_proofReader_tab" data-toggle="tab">ProofReader</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="help_login_tab">
								<h3>Login</h3>
									<textarea id="help_login_login" name="help_login_login" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_login_login')[0]['name'];?></textarea>
								<h3>Registration</h3>
									<textarea id="help_login_registration" name="help_login_registration" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_login_registration')[0]['name'];?></textarea>
								<h3>Forgot Password</h3>
									<textarea id="help_login_forgot" name="help_login_forgot" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_login_forgot')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="help_admin_tab">
								<h3>Dashboard</h3>
									<textarea id="help_admin_dashboard" name="help_admin_dashboard" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_dashboard')[0]['name'];?></textarea>
								<h3>Add Assignment</h3>
									<textarea id="help_admin_addassignment" name="help_admin_addassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_addassignment')[0]['name'];?></textarea>
								<h3>View Assignment</h3>
									<textarea id="help_admin_viewassignment" name="help_admin_viewassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_viewassignment')[0]['name'];?></textarea>
								<h3>Profile</h3>
									<textarea id="help_admin_profile" name="help_admin_profile" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_profile')[0]['name'];?></textarea>
								<h3>User</h3>
									<textarea id="help_admin_user" name="help_admin_user" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_user')[0]['name'];?></textarea>
								<h3>Client</h3>
									<textarea id="help_admin_client" name="help_admin_client" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_client')[0]['name'];?></textarea>
								<h3>Message</h3>
									<textarea id="help_admin_message" name="help_admin_message" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_message')[0]['name'];?></textarea>
								<h3>Settings</h3>
									<textarea id="help_admin_settings" name="help_admin_settings" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_settings')[0]['name'];?></textarea>
								<h3>Search</h3>
									<textarea id="help_admin_search" name="help_admin_search" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_search')[0]['name'];?></textarea>
								<h3>Notification</h3>
									<textarea id="help_admin_notification" name="help_admin_notification" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_admin_notification')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="help_helpdesk_tab">
								<h3>Dashboard</h3>
									<textarea id="help_helpdesk_dashboard" name="help_helpdesk_dashboard" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_dashboard')[0]['name'];?></textarea>
								<h3>Add Assignment</h3>
									<textarea id="help_helpdesk_addassignment" name="help_helpdesk_addassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_addassignment')[0]['name'];?></textarea>
								<h3>View Assignment</h3>
									<textarea id="help_helpdesk_viewassignment" name="help_helpdesk_viewassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_viewassignment')[0]['name'];?></textarea>
								<h3>Profile</h3>
									<textarea id="help_helpdesk_profile" name="help_helpdesk_profile" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_profile')[0]['name'];?></textarea>
								<h3>Message</h3>
									<textarea id="help_helpdesk_message" name="help_helpdesk_message" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_message')[0]['name'];?></textarea>
								<h3>Search</h3>
									<textarea id="help_helpdesk_search" name="help_helpdesk_search" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_search')[0]['name'];?></textarea>
								<h3>Notification</h3>
									<textarea id="help_helpdesk_notification" name="help_helpdesk_notification" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_helpdesk_notification')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="help_manager_tab">
								<h3>Dashboard</h3>
									<textarea id="help_manager_dashboard" name="help_manager_dashboard" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_dashboard')[0]['name'];?></textarea>
								<h3>Add Assignment</h3>
									<textarea id="help_manager_addassignment" name="help_manager_addassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_addassignment')[0]['name'];?></textarea>
								<h3>View Assignment</h3>
									<textarea id="help_manager_viewassignment" name="help_manager_viewassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_viewassignment')[0]['name'];?></textarea>
								<h3>Profile</h3>
									<textarea id="help_manager_profile" name="help_manager_profile" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_profile')[0]['name'];?></textarea>
								<h3>Message</h3>
									<textarea id="help_manager_message" name="help_manager_message" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_message')[0]['name'];?></textarea>
								<h3>Search</h3>
									<textarea id="help_manager_search" name="help_manager_search" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_search')[0]['name'];?></textarea>
								<h3>Notification</h3>
									<textarea id="help_manager_notification" name="help_manager_notification" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_manager_notification')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="help_writer_tab">
								<h3>Dashboard</h3>
									<textarea id="help_writer_dashboard" name="help_writer_dashboard" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_dashboard')[0]['name'];?></textarea>
								<h3>View Assignment</h3>
									<textarea id="help_writer_viewassignment" name="help_writer_viewassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_viewassignment')[0]['name'];?></textarea>
								<h3>Profile</h3>
									<textarea id="help_writer_profile" name="help_writer_profile" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_profile')[0]['name'];?></textarea>
								<h3>Message</h3>
									<textarea id="help_writer_message" name="help_writer_message" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_message')[0]['name'];?></textarea>
								<h3>Search</h3>
									<textarea id="help_writer_search" name="help_writer_search" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_search')[0]['name'];?></textarea>
								<h3>Notification</h3>
									<textarea id="help_writer_notification" name="help_writer_notification" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_writer_notification')[0]['name'];?></textarea>
							</div>
							<div class="tab-pane" id="help_proofReader_tab">
								<h3>Dashboard</h3>
									<textarea id="help_proofreader_dashboard" name="help_proofreader_dashboard" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_dashboard')[0]['name'];?></textarea>
								<h3>View Assignment</h3>
									<textarea id="help_proofreader_viewassignment" name="help_proofreader_viewassignment" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_viewassignment')[0]['name'];?></textarea>
								<h3>Profile</h3>
									<textarea id="help_proofreader_profile" name="help_proofreader_profile" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_profile')[0]['name'];?></textarea>
								<h3>Message</h3>
									<textarea id="help_proofreader_message" name="help_proofreader_message" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_message')[0]['name'];?></textarea>
								<h3>Search</h3>
									<textarea id="help_proofreader_search" name="help_proofreader_search" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_search')[0]['name'];?></textarea>
								<h3>Notification</h3>
									<textarea id="help_proofreader_notification" name="help_proofreader_notification" placeholder="Description"><?php echo $this->Mdl_settings->help_detaile('help_proofreader_notification')[0]['name'];?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
			
				CKEDITOR.replace('samples_admin')
				CKEDITOR.replace('samples_helpdesk')
				CKEDITOR.replace('samples_manager')
				CKEDITOR.replace('samples_writer')
				CKEDITOR.replace('samples_proofReader')
				
				CKEDITOR.replace('basic_admin')
				CKEDITOR.replace('basic_helpdesk')
				CKEDITOR.replace('basic_manager')
				CKEDITOR.replace('basic_writer')
				CKEDITOR.replace('basic_proofReader')
				
				
				
				
				CKEDITOR.replace('help_login_login')
				CKEDITOR.replace('help_login_registration')
				CKEDITOR.replace('help_login_forgot')
				
				CKEDITOR.replace('help_admin_dashboard')
				CKEDITOR.replace('help_admin_addassignment')
				CKEDITOR.replace('help_admin_viewassignment')
				CKEDITOR.replace('help_admin_profile')
				CKEDITOR.replace('help_admin_user')
				CKEDITOR.replace('help_admin_client')
				CKEDITOR.replace('help_admin_message')
				CKEDITOR.replace('help_admin_settings')
				CKEDITOR.replace('help_admin_search')
				CKEDITOR.replace('help_admin_notification')
				
				CKEDITOR.replace('help_helpdesk_dashboard')
				CKEDITOR.replace('help_helpdesk_addassignment')
				CKEDITOR.replace('help_helpdesk_viewassignment')
				CKEDITOR.replace('help_helpdesk_profile')
				CKEDITOR.replace('help_helpdesk_message')
				CKEDITOR.replace('help_helpdesk_search')
				CKEDITOR.replace('help_helpdesk_notification')
				
				CKEDITOR.replace('help_manager_dashboard')
				CKEDITOR.replace('help_manager_addassignment')
				CKEDITOR.replace('help_manager_viewassignment')
				CKEDITOR.replace('help_manager_profile')
				CKEDITOR.replace('help_manager_message')
				CKEDITOR.replace('help_manager_search')
				CKEDITOR.replace('help_manager_notification')
				
				CKEDITOR.replace('help_writer_dashboard')
				CKEDITOR.replace('help_writer_viewassignment')
				CKEDITOR.replace('help_writer_profile')
				CKEDITOR.replace('help_writer_message')
				CKEDITOR.replace('help_writer_search')
				CKEDITOR.replace('help_writer_notification')
				
				CKEDITOR.replace('help_proofreader_dashboard')
				CKEDITOR.replace('help_proofreader_viewassignment')
				CKEDITOR.replace('help_proofreader_profile')
				CKEDITOR.replace('help_proofreader_message')
				CKEDITOR.replace('help_proofreader_search')
				CKEDITOR.replace('help_proofreader_notification')
			</script>
			<div class="row">
				<div class="col-sm-10">
					<button type="submit" class="btn btn-danger">Update</button>
				</div>
			</div>
		</form>
    </section>
  </div>