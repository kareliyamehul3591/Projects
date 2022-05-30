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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style.css"/>
  <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

<script src="<?php echo base_url(); ?>assets/jquery.validate.js"></script>
<style type="text/css">
    #registration label.error, .output {color:#FB3A3A;font-weight:bold;}
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box" style="width: 60%;" >
  <!-- /.login-logo -->
  <div class="login-box-body">
	<div class="pull-right box-tools">
		<a href="<?php echo base_url(); ?>index.php/Help?tab=help&role=login&page=registration" > <i class="fa fa-question-circle fa-2x"></i></a>
	</div>
	<div class="login-logo">
		<a href=""><b>REGISTRATION FORM</b></a>
	</div>
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
    <form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Admin_login/registration" id="registration" name="registration" enctype="multipart/form-data" >
		<label style="color:#FB3A3A;font-weight:bold;" >* Indicates required field</label>
		<p>LOGIN DETAILS<p>
		<div class="row">
			<div class="col-xs-4">
				<input type="email" name="email" id="email" class="form-control" placeholder="Email ID *"  >
				<span class="glyphicon glyphicon-envelope form-control-feedback" style="width: 60px;"></span>
			</div>
			<div class="col-xs-4">
				<input type="text" name="name" id="name" class="form-control" placeholder="User Name *"  >
			</div>
			<div class="col-xs-4">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password *"  >
				<span class="glyphicon glyphicon-lock form-control-feedback" style="width: 60px;"></span>
			</div>
		</div><br/>
		<p>PERSONAL DETAILS<p>
		<div class="row">
			<div class="col-xs-4">
				<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name *"  >
			</div>
			<div class="col-xs-4">
				<input type="text" name="lastst_name" id="lastst_name" class="form-control" placeholder="Last Name *"  >
			</div>
			<div class="col-xs-4">
				<?php $sexs = $this->Mdl_admin_login->dropdowns('dropdowns_sex'); ?>
				<select class="form-control" name="sex" id="sex"  >
					<option value="">Select sex *</option>
					<?php foreach($sexs as $sex){ ?>
						<option value="<?php echo $sex['name'];?>"><?php echo $sex['name'];?></option>
					<?php } ?>
				</select>
			</div>
		</div><br/>
		<p>CONTACT DETAILS<p>
		<div class="row">
			<div class="col-xs-4">
				<input type="text" name="skype_id" id="skype_id" class="form-control" placeholder="Skype ID"  >
			</div>
			<div class="col-xs-4">
				<input type="text" name="freelancer_id" id="freelancer_id" class="form-control" placeholder="Freelancer ID"  >
			</div>
			<div class="col-xs-4">
				<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number"  >
			</div>
		</div><br/>
		<p>PAYMENT DETAILS<p>
		<div class="row">
			<div class="col-xs-4">
				<?php $payments = $this->Mdl_admin_login->dropdowns('dropdowns_payment'); ?>
				<select class="form-control" name="payment_type" id="payment_type"  >
					<option value="">Select Payment Type *</option>
					<?php foreach($payments as $payment){ ?>
						<option value="<?php echo $payment['name'];?>"><?php echo $payment['name'];?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-xs-4">
				<select class="form-control" name="payment_method" id="payment_method"  onchange="payment_methods()">
					<option value="">Select Payment Method *</option>
					<option value="Bank Transfer" >Bank Transfer</option>
					<option value="Freelancer" >Freelancer</option>
					<option value="Paypal" >Paypal</option>
					<option value="Paytm" >Paytm</option>
				</select>
			</div>
			<div class="col-xs-4 freelancer" style="display: none">
				<input type="text" name="profile_url" id="profile_url" class="form-control" placeholder="URL *">
			</div>
			<div class="col-xs-4 paypal" style="display: none">
				<input type="text" name="paypal_user_id" id="paypal_user_id" class="form-control" placeholder="Email ID *">
			</div>
			<div class="col-xs-4 paytm" style="display: none">
				<input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Contact Number *">
			</div>
		</div><br/>
		<div class="row bank_transfer" style="display: none" >
			<div class="col-xs-4">
				<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Account Name *">
			</div>
			<div class="col-xs-4">
				<input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account Number *">
			</div>
			<div class="col-xs-4">
				<input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="IFSC *">
			</div>
		</div><br/>
		<p>ROLE<p>
		<div class="row">
			<div class="col-xs-12">
				<!--<input type="checkbox" name="role[]" id="clients" value="Client" > Client &emsp;-->
				<input type="checkbox" name="role[]" id="roles" value="Admin"> Admin &emsp;
				<input type="checkbox" name="role[]" id="roles" value="Help Desk"> HelpDesk &emsp;
				<input type="checkbox" name="role[]" id="roles" value="Manager"> Manager &emsp;
				<input type="checkbox" name="role[]" id="roles" value="Writer"> Writer &emsp;
				<input type="checkbox" name="role[]" id="roles" value="Proof Reader"> ProofReader &emsp;
			</div>
		</div><br/>
		<p>AVAILABLE TIMINGS<p>
		<?php 
			$timesss = $this->Mdl_admin_login->dropdowns('dropdowns_time');
			$times = array();
			foreach($timesss as $timess)
			{
				if(strpos($timess['name'], 'AM') == true)
				{
					$times[] = $timess['name'];
				}
			}
			foreach($timesss as $timess)
			{
				if(strpos($timess['name'], 'PM') == true)
				{
					$times[] = $timess['name'];
				}
			}
		?>
		<div id="addDiv1" class="addDiv form-group">
			<div class="icheckbox_minimal-blue checked">
			
				<label><input type="checkbox" name="d1[]" id="day1" value="SU" style="margin: 0 5px 0;">&nbsp;S</label>
				<label><input type="checkbox" name="d1[]" id="day2" value="M" style="margin: 0 5px 0;">&nbsp;M</label>
				<label><input type="checkbox" name="d1[]" id="day3" value="T" style="margin: 0 5px 0;">&nbsp;T</label>
				<label><input type="checkbox" name="d1[]" id="day4" value="W" style="margin: 0 5px 0;">&nbsp;W</label>
				<label><input type="checkbox" name="d1[]" id="day5" value="TH" style="margin: 0 5px 0;">&nbsp;T</label>
				<label><input type="checkbox" name="d1[]" id="day6" value="F" style="margin: 0 5px 0;">&nbsp;F</label>
				<label><input type="checkbox" name="d1[]" id="day7" value="S" style="margin: 0 5px 0;">&nbsp;S</label>
				
				&nbsp;	From :  <select name="timefd1" id="timef" style="width:160px;height:30px;">
								  <option value="">Select one time</option>
								  <?php 
								  foreach($times as $time)
								  {
									  echo "<option value='". $time ."'>" .$time ."</option>" ;
								  }
								  ?>
								</select> 
				&nbsp;	To :    <select name="timetd1" id="timet" style="width:160px;height:30px;">
								  <option value="">Select one time</option>
								  <?php 
								  foreach($times as $time)
								  {
									  echo "<option value='". $time ."'>" .$time ."</option>" ;
								  }
								  ?>
								</select> 
				&nbsp;	<button type="button" class="Add btn btn-default" id="Add" onclick="add_time()" >+</button>
			  </div>
		</div>
		<script>
			var tim = 2;
			function add_time(){
				$('#addDiv'+tim).show();
				tim++;
			}
			function remove_time(ids){
				$('#addDiv'+ids).hide();
				tim = 2;
			}
		</script>
		<?php for ($i = 2; $i < 8; $i++){ ?>
			<div id="addDiv<?php echo $i; ?>" class="addDiv form-group" style="display:none;">
				<div class="icheckbox_minimal-blue checked">
				
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day1" value="SU" style="margin: 0 5px 0;">&nbsp;S</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day2" value="M" style="margin: 0 5px 0;">&nbsp;M</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day3" value="T" style="margin: 0 5px 0;">&nbsp;T</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day4" value="W" style="margin: 0 5px 0;">&nbsp;W</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day5" value="TH" style="margin: 0 5px 0;">&nbsp;T</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day6" value="F" style="margin: 0 5px 0;">&nbsp;F</label>
					<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day7" value="S" style="margin: 0 5px 0;">&nbsp;S</label>
					
					&nbsp;	From :  <select name="timefd<?php echo $i; ?>" id="timef<?php echo $i; ?>" style="width:160px;height:30px;">
									  <option value="">Select one time</option>
									  <?php 
									  foreach($times as $time)
									  {
										  echo "<option value='". $time ."'>" .$time ."</option>" ;
									  }
									  ?>
									</select> 
					&nbsp;	To :    <select name="timetd<?php echo $i; ?>" id="timet<?php echo $i; ?>" style="width:160px;height:30px;">
									  <option value="">Select one time</option>
									  <?php 
									  foreach($times as $time)
									  {
										  echo "<option value='". $time ."'>" .$time ."</option>" ;
									  }
									  ?>
									</select> 
					&nbsp;<button type="button" class="remove_field btn btn-default" id="Remove" onclick="remove_time(<?php echo $i; ?>)" >-</button>
				  </div>
			</div>
		<?php } ?>
		<br/>
		<br/>
		<p>SAMPLE ATTACHMENTS<p>
		
		<div class="row">
			<div class="col-xs-6">
				<input type="file" name="file" id="file"  multiple>
			</div>
			<div class="col-xs-2">
				<button type="button" class="btn btn-primary btn-block btn-flat" onclick="uploadfile();" >Upload</button>
			</div>
			<div class="col-xs-4" id="selectedFiles">
			</div>			
			<div class="col-xs-12">
				<label style="color:#FB3A3A;font-weight:bold;" >
					Only .doc, .docx, .xls, .xlsx, .pdf,.txt,.zip, all image files are allowed.<br/>
					Each file upload llimit is 50 MB and a maximum of 5 files are allowed.
				</label>
			</div>
		</div><br/>
		<script>
		
			function remove_file(key){
				
				$.ajax({
					type: "POST",
					url: '<?php echo base_url(); ?>index.php/Admin_login/remove_file_uplode',
					data: { key: key }, 
					success: function(data){
						$('#selectedFiles').html(data);
					},
					error: function(){
						console.log("data not found");
					}
				});
				
			}
			function uploadfile(){
				var file = document.getElementById("file");	
				var formData = new FormData();	
				var count = file.files.length;
				if(count != 0)
				{
					for (var i = 0, len = count; i < len; i++) {
						formData.append('file[]', file.files[i]);
					}
					$.ajax({
						type: "POST",
						url: '<?php echo base_url(); ?>index.php/Admin_login/file_uplode',
						data: formData, 
						contentType: false,
						cache: false,
						processData:false,
						success: function(data){
							$('#selectedFiles').html(data);
						},
						error: function(){
							console.log("data not found");
						}
					});
				}else{
					alert('File must be select');
				}
					
				
			}
			function payment_methods(){
				var da = $("#payment_method").val();
				if(da == 'Freelancer')
				{
					$(".freelancer").show();
					$(".paypal").hide();
					$(".paytm").hide();
					$(".bank_transfer").hide();
				}else if(da == 'Paypal'){
					$(".freelancer").hide();
					$(".paypal").show();
					$(".paytm").hide();
					$(".bank_transfer").hide();
				}else if(da == 'Paytm'){
					$(".freelancer").hide();
					$(".paypal").hide();
					$(".paytm").show();
					$(".bank_transfer").hide();
				}else if(da == 'Bank Transfer'){
					$(".freelancer").hide();
					$(".paypal").hide();
					$(".paytm").hide();
					$(".bank_transfer").show();
				}
			}
		</script>
				
      <div class="row">
        <div class="col-xs-4" align="right" >
          <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
      </div><br/>
	  <div class="row">
		<div class="col-xs-12">
          <a href="<?php echo base_url(); ?>index.php/Admin_login"> Have an account already? Click here to Sign in.</a>
        </div>
      </div>
     <div class="row">
		<div class="col-xs-8">
          <a href="<?php echo base_url(); ?>index.php/Admin_login/forgot_password"> Forgot Password? Reset here.</a>
        </div>
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

<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>


<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  $('.datepicker').datepicker({
	  autoclose: true
	})
</script>
<script>
  $(function() {
    $("#registration").validate({
        rules: {
			email: {
                required: true,
                email: true,
				rangelength: [5 , 100],
				remote:{
                    url:'<?php echo base_url(); ?>index.php/Admin_login/email_id_check',
                    type:'post',
                    data:{email:function(){ return $("#email").val(); }}
                },
            },
			name: {
                required: true,
				rangelength: [5 , 20],
				remote:{
                    url:'<?php echo base_url(); ?>index.php/Admin_login/name_check',
                    type:'post',
                    data:{email:function(){ return $("#name").val(); }}
                },
            },
			first_name: {
				required: true,
				rangelength: [3 , 35],
            },
			lastst_name: {
				required: true,
				rangelength: [1 , 35],
            },
			sex: {
				required: true,
            },
			skype_id: {
				rangelength: [6 , 32],
            },
			freelancer_id: {
				rangelength: [6 , 20],
            },
			phone_number: {
				rangelength: [10 , 15],
            },
			company_name: {
                required: true,
            },
			company_location: {
                required: true,
            },
			company_country: {
                required: true,
            },
            password: {
                required: true,
				rangelength: [8, 15],
            },
			'role[]': {
                required: true,
            },
			'd1[]': {
                required: true,
            },
			timefd1: {
                required: true,
            },
			timetd1: {
                required: true,
            },
			profile_url: {
                required: true,
            },
			paypal_user_id: {
                required: true,
            },
			contact_no: {
                required: true,
            },
			bank_name: {
                required: true,
            },
			account_no: {
                required: true,
            },
			ifsc_code: {
                required: true,
            },
        },
       messages: {
			email: {
				required: "Enter a valid Email Address.",
				rangelength: "Email ID Should be Between 5 to 100 Characters",
				remote: "Already exists",
			},
			name: {
				required: "This field is required.",
				rangelength: "User Name Should be Between 5 to 20 Characters",
				remote: "Already exists",
			}, 
			first_name: {
				required: "This field is required.",
				rangelength: "First Name Should be Between 3 to 35 Characters",
			},
			lastst_name: {
				required: "This field is required.",
				rangelength: "Lastst Name Should be Between 1 to 35 Characters",
			},
			skype_id: {
				rangelength: "Skype ID Should be Between 6 to 32 Characters",
			},
			freelancer_id: {
				rangelength: "Freelancer ID Should be Between 6 to 20 Characters",
			},
			phone_number: {
				rangelength: "Phone Number Should be Between 10 to 15 Characters",
			},
            password: {
                required: "Please provide a password",
				rangelength: "Password Should be Between 8 to 15 Characters",
            },
			'role[]': {
                required: "Please enter your Role",
            },
			'd1[]': {
                 required: "Please enter your date",
            },
        },
    });
  });
</script>
  
</body>
</html>