  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
        <small>User Data</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">User</li>
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
				<h3 class="box-title">UPDATE PROFILE</h3>
            </div>
			<style type="text/css">
				#edit_user label.error, .output {color:#FB3A3A;font-weight:bold;}
			</style>
            <!-- /.box-header -->
			<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/User/user_edit" id="edit_user" enctype="multipart/form-data" >
				<div class="box-body">
					<?php $data = $datas[0];?>
					<input type="hidden" name="ids" id="ids" value="<?php echo $data['id']; ?>">
					<b><p>LOGIN DETAILS<p></b>
					<div class="row">
						<div class="col-xs-4">
							<input type="text" name="name" id="name" class="form-control" placeholder="User Name" value="<?php echo $data['name']; ?>" required >
						</div>
						<div class="col-xs-4">
							<input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $data['email']; ?>" required >
							<span class="glyphicon glyphicon-envelope form-control-feedback" style="width: 60px;"></span>
						</div>
						<div class="col-xs-4">
							<input type="password" name="password" id="password" class="form-control"  placeholder="Password">
							<span class="glyphicon glyphicon-lock form-control-feedback" style="width: 60px;"></span>
						</div>
					</div><br/>
					<b><p>PERSONAL DETAILS<p></b>
					<div class="row">
						<div class="col-xs-4">
							<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name *"  value="<?php echo $data['first_name']; ?>">
						</div>
						<div class="col-xs-4">
							<input type="text" name="lastst_name" id="lastst_name" class="form-control" placeholder="Last Name *"  value="<?php echo $data['lastst_name']; ?>">
						</div>
						<div class="col-xs-4">
							<?php $sexs = $this->Mdl_user->dropdowns('dropdowns_sex'); ?>
							<select class="form-control" name="sex" id="sex" required >
								<option value="">Select sex</option>
								<?php foreach($sexs as $sex){ 
								if($data['sex'] == $sex['name']){?>
									<option value="<?php echo $sex['name'];?>" selected ><?php echo $sex['name'];?></option>
								<?php }else{ ?>
									<option value="<?php echo $sex['name'];?>"><?php echo $sex['name'];?></option>
								<?php } } ?>
							</select>
						</div>	
					</div><br/>
					<b><p>CONTACT DETAILS<p></b>
					<div class="row">
						<div class="col-xs-4">
							<input type="text" name="skype_id" id="skype_id" class="form-control" placeholder="Skype ID"  value="<?php echo $data['skype_id']; ?>" >
						</div>
						<div class="col-xs-4">
							<input type="text" name="freelancer_id" id="freelancer_id" class="form-control" placeholder="Freelancer ID" value="<?php echo $data['freelancer_id']; ?>" >
						</div>
						<div class="col-xs-4">
							<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo $data['phone_number']; ?>" >
						</div>
					</div><br/>
					<b><p>PAYMENT DETAILS<p></b>
					<div class="row">
						<?php $payment_details = $this->Mdl_user->payment_details($data['id']); 
						$payment = $payment_details[0];
						?>
						<div class="col-xs-4">
							<?php $paymentss = $this->Mdl_user->dropdowns('dropdowns_payment'); ?>
							<select class="form-control" name="payment_type" id="payment_type"  >
								<option value="">Select Payment Type *</option>
								<?php foreach($paymentss as $payments){ 
								if($payment['payment_type'] == $payments['name']){?>
									<option value="<?php echo $payments['name'];?>" selected ><?php echo $payments['name'];?></option>
								<?php }else{ ?>
									<option value="<?php echo $payments['name'];?>"><?php echo $payments['name'];?></option>
								<?php } } ?>
							</select>
						</div>
						<div class="col-xs-4">
							<select class="form-control" name="payment_method" id="payment_method"  onchange="payment_methods()">
								<option value="">Select Payment Method *</option>
								<option value="Bank Transfer" <?php if($payment['payment_method'] == "Bank Transfer"){ echo 'selected'; } ?> >Bank Transfer</option>
								<option value="Freelancer" <?php if($payment['payment_method'] == "Freelancer"){ echo 'selected'; } ?> >Freelancer</option>
								<option value="Paypal" <?php if($payment['payment_method'] == "Paypal"){ echo 'selected'; } ?> >Paypal</option>
								<option value="Paytm" <?php if($payment['payment_method'] == "Paytm"){ echo 'selected'; } ?> >Paytm</option>
							</select>
						</div>
						<div class="col-xs-4 freelancer" style="<?php if($payment['payment_method'] != "Freelancer"){ echo 'display: none'; }?>;">
							<input type="text" name="profile_url" id="profile_url" class="form-control" placeholder="URL *" value="<?php echo $payment['profile_url']; ?>" >
						</div>
						<div class="col-xs-4 paypal" style="<?php if($payment['payment_method'] != "Paypal"){ echo 'display: none'; }?>;">
							<input type="text" name="paypal_user_id" id="paypal_user_id" class="form-control" placeholder="Email ID *" value="<?php echo $payment['paypal_user_id']; ?>" >
						</div>
						<div class="col-xs-4 paytm" style="<?php if($payment['payment_method'] != "Paytm"){ echo 'display: none'; }?>;" >
							<input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Contact Number *" value="<?php echo $payment['contact_no']; ?>" >
						</div>
					</div><br/>
					<div class="row bank_transfer" style="<?php if($payment['payment_method'] != "Bank Transfer"){ echo 'display: none'; }?>;" >
						<div class="col-xs-4">
							<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Account Name *" value="<?php echo $payment['bank_name']; ?>" >
						</div>
						<div class="col-xs-4">
							<input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account Number *" value="<?php echo $payment['account_no']; ?>" >
						</div>
						<div class="col-xs-4">
							<input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="IFSC *" value="<?php echo $payment['ifsc_code']; ?>" >
						</div>
					</div><br/>
					<b><p>ROLE<p></b>
					<div class="row">
						<div class="col-xs-12">
							<input type="checkbox" name="role[]" id="clients" value="Client"> Client &emsp;
							<input type="checkbox" name="role[]" id="roles" value="Admin" <?php if($data['admin'] == 1){ echo 'checked'; } ?> > Admin &emsp;
							<input type="checkbox" name="role[]" id="roles" value="Help Desk" <?php if($data['help_desk'] == 1){ echo 'checked'; } ?> > HelpDesk &emsp;
							<input type="checkbox" name="role[]" id="roles" value="Manager" <?php if($data['manager'] == 1){ echo 'checked'; } ?> > Manager &emsp;
							<input type="checkbox" name="role[]" id="roles" value="Writer" <?php if($data['writer'] == 1){ echo 'checked'; } ?> > Writer &emsp;
							<input type="checkbox" name="role[]" id="roles" value="Proof Reader" <?php if($data['proof_reader'] == 1){ echo 'checked'; } ?> > ProofReader &emsp;
						</div>
					</div><br/>
					<b><p>AVAILABLE TIMINGS<p></b>
					<?php
						$timings = $this->Mdl_user->available_timings($data['id']);
						$timing = $timings[0];
						
						$timesss = $this->Mdl_user->dropdowns('dropdowns_time');
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
						$tt = array();
						$tt = explode(",",$timing['time1']);
						$dd = array();
						$dd = explode(",",$timing['date1']);
					?>
					<div id="addDiv1" class="addDiv form-group">
						<div>
						
							<label><input type="checkbox" name="d1[]" id="day1" value="SU" style="margin: 0 5px 0;" <?php if($dd[0] == 'SU'){ echo 'checked'; }?> >&nbsp;S</label>
							<label><input type="checkbox" name="d1[]" id="day2" value="M" style="margin: 0 5px 0;" <?php if($dd[1] == 'M'){ echo 'checked'; }?>>&nbsp;M</label>
							<label><input type="checkbox" name="d1[]" id="day3" value="T" style="margin: 0 5px 0;" <?php if($dd[2] == 'T'){ echo 'checked'; }?>>&nbsp;T</label>
							<label><input type="checkbox" name="d1[]" id="day4" value="W" style="margin: 0 5px 0;" <?php if($dd[3] == 'W'){ echo 'checked'; }?>>&nbsp;W</label>
							<label><input type="checkbox" name="d1[]" id="day5" value="TH" style="margin: 0 5px 0;" <?php if($dd[4] == 'TH'){ echo 'checked'; }?>>&nbsp;T</label>
							<label><input type="checkbox" name="d1[]" id="day6" value="F" style="margin: 0 5px 0;" <?php if($dd[5] == 'F'){ echo 'checked'; }?>>&nbsp;F</label>
							<label><input type="checkbox" name="d1[]" id="day7" value="S" style="margin: 0 5px 0;" <?php if($dd[6] == 'S'){ echo 'checked'; }?>>&nbsp;S</label>
							
							
							&nbsp;	From :  <select name="timefd1" id="timef" style="width:160px;height:30px;" required >
											  <option value="">Select one time</option>
											  <?php 
											  foreach($times as $time)
											  {
													echo "<option value='". $time ."'";
													if($tt[0] == $time)
													{
														echo 'selected'; 
													}
													echo ">" .$time ."</option>" ;
											  }
											  ?>
											</select> 
							&nbsp;	To :    <select name="timetd1" id="timet" style="width:160px;height:30px;" required >
											  <option value="">Select one time</option>
											  <?php 
											  foreach($times as $time)
											  {
													echo "<option value='". $time ."'";
													if($tt[1] == $time)
													{
														echo 'selected'; 
													}
													echo ">" .$time ."</option>" ;
											  }
											  ?>
											</select> 
							&nbsp;	<button type="button" class="Add btn btn-default" id="Add" onclick="add_time()" >+</button>
						  </div>
					</div>
					<?php
					
					for ($i = 2; $i < 8; $i++){ 
						$tt = array();
						$tt = explode(",",$timing['time'.$i]);
						$dd = array();
						$dd = explode(",",$timing['date'.$i]);
						?>
						<div id="addDiv<?php echo $i; ?>" class="addDiv form-group"  <?php  if($timing['time'.$i] == null) { echo 'style="display:none;"'; } ?> >
							<div>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day1" value="SU" style="margin: 0 5px 0;" <?php if($dd[0] == 'SU'){ echo 'checked'; }?> >&nbsp;S</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day2" value="M" style="margin: 0 5px 0;" <?php if($dd[1] == 'M'){ echo 'checked'; }?>>&nbsp;M</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day3" value="T" style="margin: 0 5px 0;" <?php if($dd[2] == 'T'){ echo 'checked'; }?>>&nbsp;T</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day4" value="W" style="margin: 0 5px 0;" <?php if($dd[3] == 'W'){ echo 'checked'; }?>>&nbsp;W</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day5" value="TH" style="margin: 0 5px 0;" <?php if($dd[4] == 'TH'){ echo 'checked'; }?>>&nbsp;T</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day6" value="F" style="margin: 0 5px 0;" <?php if($dd[5] == 'F'){ echo 'checked'; }?>>&nbsp;F</label>
								<label><input type="checkbox" name="d<?php echo $i; ?>[]" id="day7" value="S" style="margin: 0 5px 0;" <?php if($dd[6] == 'S'){ echo 'checked'; }?>>&nbsp;S</label>
								
								&nbsp;	From :  <select name="timefd<?php echo $i; ?>" id="timef<?php echo $i; ?>" style="width:160px;height:30px;">
												  <option value="">Select one time</option>
												  <?php 
												  foreach($times as $time)
												  {
													  echo "<option value='". $time ."'";
													  if($tt[0] == $time)
													  {
														 echo 'selected'; 
													  }
													  echo ">" .$time ."</option>" ;
												  }
												  ?>
												</select> 
								&nbsp;	To :    <select name="timetd<?php echo $i; ?>" id="timet<?php echo $i; ?>" style="width:160px;height:30px;">
												  <option value="">Select one time</option>
												  <?php 
												  foreach($times as $time)
												  {
														echo "<option value='". $time ."'";
														if($tt[1] == $time)
														{
															echo 'selected'; 
														}
														echo ">" .$time ."</option>" ;
												  }
												  ?>
												</select> 
								&nbsp;<button type="button" class="remove_field btn btn-default" id="Remove" onclick="remove_time(<?php echo $i; ?>)" >-</button>
							  </div>
						</div>
					<?php }?>
					<script>
						var tim = 2;
						function add_time(){
							$('#addDiv'+tim).show();
							tim++;
						}
						function remove_time(ids){
							$('#addDiv'+ids).hide();
							document.getElementById('timef'+ids).value = "";
							document.getElementById('timet'+ids).value = "";
							tim = 2;
						}
					</script>
		
					<br/>
					<br/>
					
					
					<b><p>SAMPLE ATTACHMENTS<p></b>
					<div class="row">
						<div class="col-xs-6">
							<input type="file" name="file[]" id="file" class="form-control" accept=".doc,.docx,.xls,.xlsx,.pdf,.zip,.jpeg,.png,.jpg" multiple>
						</div>
						<div class="col-xs-4">
							<?php 
							
							foreach(explode(",", $data['file']) as $file)
							{ 
								if($file != NULL)
								{
									$key++;
									echo $key." :- ";
									echo '<a href="'.base_url().'uploads/User/'.$file.'" target="_blank" >'.$file.'</a>';
									if($datas[0]['status'] != 5){
										echo '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$data['id'].')"></i>';
									}
									echo '</br>';
								}
							} ?>
						</div>			
						<div class="col-xs-12">
							<label style="color:#FB3A3A;font-weight:bold;" >
								Only .doc, .docx, .xls, .xlsx, .pdf,.txt,.zip, all image files are allowed.<br/>
								Each file upload llimit is 50 MB and a maximum of 5 files are allowed.
							</label>
						</div>
					</div><br/>
					<div class="modal-footer">
						<a href="<?php echo base_url(); ?>index.php/User" class="btn btn-default pull-left" >Cancel</a>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</form>
			<script>
				function remove_file(ids,id)
				{
					var images_r = confirm("Are you sure want to delete?");
					if (images_r == true) {
						$.ajax({
							type:'POST',
							url: '<?php echo base_url(); ?>index.php/User/user_remove_file',
							data:{ id : id , ids : ids },
							success:function(data){
								location.reload();
							}
						});
					}
				}
				function payment_methods(){
					var da = $("#payment_method").val();
					if(da == '')
					{
						$(".freelancer").hide();
						$(".paypal").hide();
						$(".paytm").hide();
						$(".bank_transfer").hide();
					}else if(da == 'Freelancer')
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
				$(function() {
					var id = <?php echo $data['id']; ?>;
					$("#edit_user").validate({
						rules: {
							email: {
								required: true,
								email: true,
								remote:{
									url:'<?php echo base_url(); ?>index.php/User/email_id_check/'+id,
									type:'post',
									data:{email:function(){ return $("#email"+id).val(); }}
								},
							},
							name: {
								required: true,
								rangelength: [5 , 20],
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
							'role[]': {
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
							'd1[]': {
								required: true,
							},
						},
						messages: {
							email: {
								required: "Please enter a valid email address",
								remote: "Already exists",
							},
							name: {
								required: "This field is required.",
								rangelength: "User Name Should be Between 5 to 20 Characters",
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
						}
					});
				});
			</script>
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
