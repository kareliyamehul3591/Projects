<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1> Help </h1>
	</section>

  
		  
    <section class="content">
	
		<div class="row">
		
			
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Help</h3>
					</div>
					<div class="box-body">
						<?php 
						if($_GET['page'] == 'help_desk_dashboard' || $_GET['page'] == 'dashboard'  || $_GET['page'] == 'manager_dashboard' || $_GET['page'] == 'writer_dashboard' || $_GET['page'] == 'proof_reader_dashboard')
						{
							$page = 'dashboard';
						}else if($_GET['page'] == 'help_desk_profile' || $_GET['page'] == 'admin_profile' || $_GET['page'] == 'manager_profile' || $_GET['page'] == 'writer_profile' || $_GET['page'] == 'proof_reader_profile')
						{
							$page = 'profile';
						}else if($_GET['page'] == 'help_desk_assignment_add' || $_GET['page'] == 'assignment' || $_GET['page'] == 'assignment_edit' || $_GET['page'] == 'manager_assignment')
						{
							$page = 'addassignment';
						}else if($_GET['page'] == 'assignment_view')
						{
							$page = 'viewassignment';
						}else if($_GET['page'] == 'help_desk_message' || $_GET['page'] == 'message' || $_GET['page'] == 'manager_message' || $_GET['page'] == 'writer_message' || $_GET['page'] == 'proof_reader_message')
						{
							$page = 'message';
						}else if($_GET['page'] == 'help_desk_search' || $_GET['page'] == 'search' || $_GET['page'] == 'manager_search' || $_GET['page'] == 'writer_search' || $_GET['page'] == 'proof_reader_search')
						{
							$page = 'search';
						}else if($_GET['page'] == 'help_desk_notifications' || $_GET['page'] == 'notifications' || $_GET['page'] == 'manager_notifications' || $_GET['page'] == 'writer_notifications' || $_GET['page'] == 'proof_reader_notifications')
						{
							$page = 'notification';
						}else if($_GET['page'] == 'settings' || $_GET['page'] == 'basic_guidelines')
						{
							$page = 'settings';
						}else if($_GET['page'] == 'user' || $_GET['page'] == 'edit_user' || $_GET['page'] == 'view_user')
						{
							$page = 'user';
						}else if($_GET['page'] == 'client' || $_GET['page'] == 'edit_client' || $_GET['page'] == 'view_client' || $_GET['page'] == 'edit_client')
						{
							$page = 'client';
						}
						$date = $this->Mdl_help->help_detaile($_GET['tab'],$_GET['role'],$page); 
						echo $date[0]['name'];
						//echo $_GET['page'];
						?>
					</div>
				</div>
			</div>
			
		</div>
		  
		
    </section>
    <!-- /.content -->
  </div>