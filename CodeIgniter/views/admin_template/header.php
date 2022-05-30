<!DOCTYPE html>
<html>
<?php
header('Cache-Control: max-age=900');
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $left_sidebar; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <?php date_default_timezone_set("Asia/Kolkata"); ?>
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  
  <script src="<?php echo base_url(); ?>assets/bower_components/ckeditor/ckeditor.js"></script>
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
 
 
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  
  
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style.css"/>
  <script src="<?php echo base_url(); ?>assets/jquery.validate.js"></script>
  
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo" onclick="dashboard()" >
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>RR</b>C</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>RR</b> Content</span>
    </a>
	<script>
	function dashboard()
	{
		$.ajax({
			url: '<?php echo base_url(); ?>index.php/Dashboard/dashboards/',
			success: function (data) {
				window.location = "<?php echo base_url(); ?>index.php/Dashboard";
			}
		});	
	}
	</script>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
		
			<ul class="nav navbar-nav">
			
			
			<li class="dropdown messages-menu" style="padding-top: 13px;" >
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Search" enctype="multipart/form-data">
					<ul class="nav navbar-nav">
						<li style="margin-right: 14px;">
							<input name="name" id="name1" placeholder="Search..." type="text" required >
						</li>
						<li style="margin-right: 14px;">
							<select class="dropdown-toggle" name="users" required="" style="padding-top: 6px;">
								<option value="assignment_id">Assignment ID</option>
								<option value="assignment_name"  >Assignment Name</option>
								<option value="client_id" >Client ID</option>
								<option value="client_first_name" >Client First Nam</option>
								<option value="client_last_name">Client Last Name</option>
								<option value="users_id" >Users ID</option>
								<option value="users_name" >Users Name</option>
								<option value="users_first_name" >Users First Name</option>
								<option value="users_last_name" >Users Last Name</option>
							</select>
						</li>
						<li style="margin-right: 14px;">
							<button type="submit" class="btn btn-sm btn-info" style="padding-right: 20px;padding-left: 20px;padding-top: 3px;"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
						</li>
					</ul>
				</form>
			</li>
			
			
			<?php //$messages = $this->Mdl_common->message(); ?>
			<li class="dropdown messages-menu header_messages">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 22px;" >
					<i class="fa fa-envelope-o"></i>
					<span class="label label-success" style="font-size: 15px;" >0</span>
				</a>
				<ul class="dropdown-menu">
					<li class="header">You have 0 messages</li>
					<li class="footer"><a href="<?php echo base_url(); ?>index.php/Message">See All Messages</a></li>
				</ul>
			</li>
			<input type="hidden" id="header_messages_count" name="header_messages_count" value="" >
			
			<audio id="my_audio" src="<?php echo base_url(); ?>uploads/sms.mp3" ></audio>
			
			<script>
				header_message();
				function header_message() {
					setTimeout(function () {
						var messages_count = document.getElementById("header_messages_count").value;						
						$.ajax({
							url: '<?php echo base_url(); ?>index.php/Message/header_message_get/',
							type: "POST",
							data : ({ to_role: "Admin",count: messages_count }),
							dataType: "json",
							success: function (data) {
								if(data.status == 1)
								{
									$(".header_messages").html(data.datas);
									if(data.sms >= 1)
									{
										document.getElementById("my_audio").play();
									}
								}
								document.getElementById("header_messages_count").value = data.count;
								header_message();
							}
						});						
					}, 1000);
				}			
			</script>

			<li class="dropdown messages-menu header_notifications">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 22px;" >
					<i class="fa fa-bell-o"></i>
					<span class="label label-warning" style="font-size: 15px;" >0</span>
				</a>
				<ul class="dropdown-menu" >
					<li class="header">You have 0 notifications</li>
					<li class="footer"><a href="<?php echo base_url(); ?>index.php/Notifications">View all</a></li>
				</ul>
			</li>
			<input type="hidden" id="header_notifications_count" name="header_notifications_count" value="" >
			 <script>
				header_notifications();
				function header_notifications() {
					setTimeout(function () {
						var count = document.getElementById("header_notifications_count").value;
						$.ajax({
							url: '<?php echo base_url(); ?>index.php/Message/header_notifications_get/',
							type: "POST",
							data : ({ count: count }),
							dataType: "json",
							success: function (data) {
								if(data.status == 1)
								{
									$(".header_notifications").html(data.datas);
								}
								document.getElementById("header_notifications_count").value = data.count;
								header_notifications();
							}
						});						
					}, 1000);
				}
				
				loginchake();
				function loginchake() {
					setTimeout(function () {						
						$.ajax({
							url: '<?php echo base_url(); ?>index.php/Admin_login/loginchake/',
							success: function (data) {
								if(data != 1)
								{
									window.location = "<?php echo base_url(); ?>index.php/Admin_login/logout";
								}
								loginchake();
							}
						});
					}, 1000);
				}
			</script>
			
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				  <span class="hidden-xs"><?php echo $this->session->Admindetail['name']; ?></span>
				</a>
				<ul class="dropdown-menu">
				 <li class="user-header">
					<p style="font-size: 20px;color: #fff;">
						<b><?php echo $this->session->Admindetail['name']; ?></b>
					</p>
				  </li>
				  <li class="user-footer">
					<div class="pull-right">
					  <a href="<?php echo base_url(); ?>index.php/Admin_login/logout" class="btn btn-default btn-flat">Sign out</a>
					</div>
				  </li> 
				</ul>
			  </li>
			  
			<li class="dropdown messages-menu">
				<a href="#" data-toggle="dropdown" class="dropdown-toggle" style="font-size: 22px;" ><i class="fa fa-question-circle"></i></a>
				<ul class="dropdown-menu" role="menu" style="width: 0px;" >
					<li><a href="<?php echo base_url(); ?>index.php/Help?tab=basic&role=admin">Basic Guidelines</a></li>
					<li><a href="<?php echo base_url(); ?>index.php/Help?tab=samples&role=admin">Samples</a></li>
					<?php if($main_content != 'help'){ ?>
					<li><a href="<?php echo base_url(); ?>index.php/Help?tab=help&role=admin&page=<?php echo $main_content; ?>">Help Withe This Page</a></li>
					<?php } ?>
					<li class="divider"></li>
					<li><a href="<?php echo base_url(); ?>index.php/Help/contact?role=admin">Contact Us</a></li>
				</ul>
			</li>
			
			
			  <?php if($left_sidebar != 'Message'){?>
			  <li>
				<a href="#" data-toggle="control-sidebar" style="font-size: 22px;" ><i class="fa fa-comments"></i></a>
			  </li>
			  <?php } ?>
			</ul>
		
      </div>
    </nav>
  </header>
