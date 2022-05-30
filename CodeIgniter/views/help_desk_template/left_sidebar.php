  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          </br></br>
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->Admindetail['name']; ?> ( Help Desk ) </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
	  
      <ul class="sidebar-menu" data-widget="tree">
	  
		<li class="<?php if($left_sidebar == 'Help Desk Dashboard'){ echo 'active';}?>" >
			<a href="<?php echo base_url(); ?>index.php/Help_desk_dashboard">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			</a>
		</li>
		<li class="<?php if($left_sidebar == 'Help Desk Profile'){ echo 'active';}?>" >
			<a href="<?php echo base_url(); ?>index.php/Help_desk_profile">
				<i class="fa fa-user"></i> <span>Profile</span>
			</a>
		</li>
		<li class="<?php if($left_sidebar == 'Help Desk Assignment Add'){ echo 'active';}?>" >
			<a href="<?php echo base_url(); ?>index.php/Help_desk_dashboard/help_desk_assignment_add">
				<i class="fa fa-edit"></i> <span>Assignment Add</span>
			</a>
		</li>
		<li class="<?php if($left_sidebar == 'Help Desk Message'){ echo 'active';}?>" >
			<a href="<?php echo base_url(); ?>index.php/Help_desk_message">
				<i class="fa fa-envelope-o"></i> <span>Message</span>
			</a>
		</li>
		
		
		<li class="treeview">
			<a href="#">
				<i class="fa fa-edit"></i> 
				<span>Switch Roles</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
				<?php if($this->session->Admindetail['super_admin'] == 1){ ?>
				<li><a href="<?php echo base_url(); ?>index.php/Admin_dashboard"><i class="fa fa-circle-o"></i> Super Admin </a></li>
				<?php } if($this->session->Admindetail['admin'] == 1){ ?>
				<li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-circle-o"></i> Admin </a></li>
				<?php } if($this->session->Admindetail['manager'] == 1){ ?>
				<li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-circle-o"></i> Manager </a></li>
				<?php } if($this->session->Admindetail['writer'] == 1){ ?>
				<li><a href="<?php echo base_url(); ?>index.php/Writer_dashboard"><i class="fa fa-circle-o"></i> Writer </a></li>
				<?php } if($this->session->Admindetail['proof_reader'] == 1){ ?>
				<li><a href="<?php echo base_url(); ?>index.php/Proof_reader_dashboard"><i class="fa fa-circle-o"></i> Proof Reader </a></li>
				<?php } ?>
			</ul>
		</li>
		<li class="<?php if($left_sidebar == 'Help Desk Search'){ echo 'active';}?>" >
			<a href="<?php echo base_url(); ?>index.php/Help_desk_search">
				<i class="fa fa-search"></i> <span>Search</span>
			</a>
		</li>
		
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>