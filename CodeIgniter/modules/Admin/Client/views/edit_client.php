  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Client
        <small>Client Data</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Client</li>
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
			<style type="text/css">
				#edit_user label.error, .output {color:#FB3A3A;font-weight:bold;}
			</style>
            <!-- /.box-header -->
			<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Client/client_edit" id="edit_user" enctype="multipart/form-data" >
				<div class="box-body">
					<?php $data = $datas[0];?>
					<input type="hidden" name="ids" id="ids" value="<?php echo $data['id']; ?>">
					<b><p>PERSONAL DETAILS<p></b>
					<div class="row">
						<div class="col-xs-3">
							<input type="text" name="email" id="email" class="form-control" placeholder="Email *"  value="<?php echo $data['email']; ?>" required>
						</div>
						<div class="col-xs-3">
							<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name *"  value="<?php echo $data['first_name']; ?>" required>
						</div>
						<div class="col-xs-3">
							<input type="text" name="lastst_name" id="lastst_name" class="form-control" placeholder="Last Name *"  value="<?php echo $data['lastst_name']; ?>" required>
						</div>
						<div class="col-xs-3">
							<?php $sexs = $this->Mdl_client->dropdowns('dropdowns_sex'); ?>
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
							<input type="text" name="skype_id" id="skype_id" class="form-control" placeholder="Skype ID"  value="<?php echo $data['skype_id']; ?>" required >
						</div>
						<div class="col-xs-4">
							<input type="text" name="freelancer_id" id="freelancer_id" class="form-control" placeholder="Freelancer ID" value="<?php echo $data['freelancer_id']; ?>" required >
						</div>
						<div class="col-xs-4">
							<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo $data['phone_number']; ?>" required  >
						</div>
					</div><br/>
					<b><p>PAYMENT DETAILS<p></b>
					<div class="row">
						<?php 
						if($data != null)
						{
							$payment_details = $this->Mdl_client->payment_details($data['id']); 
							$payment = $payment_details[0];
						}
						
						?>
						<div class="col-xs-4">
							<?php $paymentss = $this->Mdl_client->dropdowns('dropdowns_payment'); ?>
							<select class="form-control" name="payment_type" id="payment_type" required >
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
							<select class="form-control" name="payment_method" id="payment_method"  onchange="payment_methods()" required >
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
					
					<b><p>Client Details<p></b>
					<div class="row">
						<div class="col-xs-4">
							<input type="text" name="company_name" id="company_name" class="form-control" placeholder="Company Name *"  value="<?php echo $data['company_name']; ?>" required>
						</div>
						<div class="col-xs-4">
							<input type="text" name="company_location" id="company_location" class="form-control" placeholder="Location *" value="<?php echo $data['company_location']; ?>" required>
						</div>
						<div class="col-xs-4">
							<input type="text" name="company_country" id="company_country" class="form-control" placeholder="Select one country*" value="<?php echo $data['company_country']; ?>" required>
						</div>
					</div><br/>
					
					<div class="modal-footer">
						<a href="<?php echo base_url(); ?>index.php/Client" class="btn btn-default pull-left" >Cancel</a>
						<?php if($data == null){?>
							<button type="submit" class="btn btn-primary">Add</button>
						<?php }else{ ?>
							<button type="submit" class="btn btn-primary">Save changes</button>
						<?php } ?>
					</div>
				</div>
			</form>
			<script>
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
