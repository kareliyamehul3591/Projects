<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style.css"/>
  <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

<script src="<?php echo base_url(); ?>assets/jquery.validate.js"></script>
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    #set_password label.error, .output {color:#FB3A3A;font-weight:bold;}
</style>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Reset Password</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

	 <div class="login-box-msg" style="color:red"> <?php echo $error_msg; ?></div>
	
	
    <form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Admin_login/set_password" id="set_password" name="set_password">
	
	<?php 
	$error_msg = $this->session->error_msg;
	$success_msg = $this->session->success_msg;
	if($error_msg != null){
	?>
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-ban"></i> Error!</h4>
			<?php echo $error_msg; ?>
		</div>
	<?php }
	if($success_msg != null){
	?>
		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Success!</h4>
			<?php echo $success_msg; ?>
		</div>
	<?php } ?>
	</br>
		<input type="hidden" name="ids" id="ids" value="<?php echo $datas['id']; ?>" >
		<input type="hidden" name="md5" id="md5" value="<?php echo $id; ?>" >
      <div class="form-group has-feedback">
		<div class="row">
			<div class="col-xs-12">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password *" required >
				<span class="glyphicon glyphicon-lock form-control-feedback" style="width: 60px;"></span>
			</div><br/><br/>
			<div class="col-xs-12">
				<input type="password" name="co_password" id="co_password" class="form-control" placeholder="Confirm Password *" required >
				<span class="glyphicon glyphicon-lock form-control-feedback" style="width: 60px;"></span>
			</div>
		</div></br>
      </div>
      <div class="row">
        <div class="col-xs-5">
        </div>
        <!-- /.col -->
        <div class="col-xs-7">
          <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

   
    <!-- /.social-auth-links -->

   <!-- <a href="#">I forgot my password</a><br>
    <a href="#" class="text-center">Register a new membership</a> -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>-->
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script>
  $(function() {
    $("#set_password").validate({
        rules: {
            password: {
                required: true,
            },
			co_password: {
                required: true,
				equalTo: "#password"
            }
        },
       messages: {
            password: {
                required: "Please provide a password",
            },
			co_password: {
                required: "Please provide a password",
                equalTo: "Confirm password does not match",
            }
        },
    });

  });
</script>
</body>
</html>