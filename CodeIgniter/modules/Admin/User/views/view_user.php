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
		<?php }  ?>
      <div class="row">
        <div class="col-xs-12">
		
          <div class="box box-info">
            <div class="box-header">
				<h3 class="box-title">REGISTRATION FORM</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<?php $data = $datas[0];?>
				
				<b><p>LOGIN DETAILS<p></b>
				<div class="row">
					<div class="col-xs-4">
						<label>Email ID :- </label> &emsp; <?php echo $data['email'];?>
					</div>
					<div class="col-xs-4">
						<label>User Name :-</label> &emsp; <?php echo $data['name'];?>
					</div>
				</div><br/>
				<b><p>PERSONAL DETAILS<p></b>
				<div class="row">
					<div class="col-xs-4">
						<label>First Name :- </label> &emsp; <?php echo $data['first_name'];?>
					</div>
					<div class="col-xs-4">
						<label>Last Name :-</label> &emsp; <?php echo $data['lastst_name'];?>
					</div>
					<div class="col-xs-4">
						<label>Sex :-</label> &emsp; <?php echo $data['sex'];?>
					</div>
				</div><br/>
				<b><p>CONTACT DETAILS<p></b>
				<div class="row">
					<div class="col-xs-4">
						<label>Skype ID :- </label> &emsp; <?php echo $data['skype_id'];?>
					</div>
					<div class="col-xs-4">
						<label>Freelancer ID :-</label> &emsp; <?php echo $data['freelancer_id'];?>
					</div>
					<div class="col-xs-4">
						<label>Phone Number :-</label> &emsp; <?php echo $data['phone_number'];?>
					</div>
				</div><br/>
				<b><p>PAYMENT DETAILS<p></b>
				<div class="row">
					<?php $payment_details = $this->Mdl_user->payment_details($data['id']); ?>
					<div class="col-xs-4">
						<label>Payment Type :- </label> &emsp; <?php echo $payment_details[0]['payment_type'];?>
					</div>
					<div class="col-xs-4">
						<label>Payment Method :-</label> &emsp; <?php echo $payment_details[0]['payment_method'];?>
					</div>
					<?php  
					if($payment_details[0]['payment_method'] == "Freelancer")
					{?>
						<div class="col-xs-4">
							<label>Profile URL :-</label> &emsp; <?php echo $payment_details[0]['profile_url'];?>
						</div>
					<?php
					}else if($payment_details[0]['payment_method'] == "Paypal")
					{?>
						<div class="col-xs-4">
							<label>User ID/Email :-</label> &emsp; <?php echo $payment_details[0]['paypal_user_id'];?>
						</div>
					<?php
					}else if($payment_details[0]['payment_method'] == "Paytm")
					{?>
						<div class="col-xs-4">
							<label>Contact No :-</label> &emsp; <?php echo $payment_details[0]['contact_no'];?>
						</div>
					<?php
					}else if($payment_details[0]['payment_method'] == "Bank Transfer")
					{?>
						<div class="col-xs-4"><br/><br/></div>
						<div class="col-xs-4">
							<label>Bank Name :-</label> &emsp; <?php echo $payment_details[0]['bank_name'];?>
						</div>
						<div class="col-xs-4">
							<label>Account No :-</label> &emsp; <?php echo $payment_details[0]['account_no'];?>
						</div>
						<div class="col-xs-4">
							<label>IFSC Code :-</label> &emsp; <?php echo $payment_details[0]['ifsc_code'];?>
						</div>
					<?php } ?>
					
				</div><br/>
				<b><p>ROLE<p></b>
				<div class="row">
					<div class="col-xs-4">
						<?php 
							if($data['super_admin'] == 1)
							{
								echo 'Super Admin,';
							}								
							if($data['admin'] == 1)
							{
								echo ' Admin, ';
							}
							if($data['manager'] == 1)
							{
								echo ' Manager, ';
							}
							if($data['writer'] == 1)
							{
								echo ' Writern, ';
							}
							if($data['proof_reader'] == 1)
							{
								echo ' Proof Reader, ';
							}
							if($data['help_desk'] == 1)
							{
								echo ' Help Desk ';
							}
						?>
					</div>
				</div><br/>
				<b><p>AVAILABLE TIMINGS<p></b>
				<div class="row">
					<div class="col-xs-4">
						
						<?php 	
							$available_timings = $this->Mdl_user->available_timings($data['id']);
							foreach($available_timings[0] as $key => $time)
							{
								if($time != null)
								{
									$times = '';
									if($key == 'date1' || $key == 'date2' || $key == 'date3' || $key == 'date4' || $key == 'date5' || $key == 'date6' || $key == 'date7')
									{
										$times .= implode(",",array_filter(explode(",",$time))).' :- ';
										
									}else if($key == 'time1' || $key == 'time2' || $key == 'time3' || $key == 'time4' || $key == 'time5' || $key == 'time6' || $key == 'time7')
									{
										$times .= implode(",",array_filter(explode(",",$time))).'</br>';
									}
									echo $times;
								}
							}
						?>
					</div>
				</div><br/>
				<b><p>SAMPLE ATTACHMENTS<p></b>
				<div class="row">
					<div class="col-xs-4">
						<?php $i = 1;
							foreach(explode(",", $data['file']) as $file)
							{ 
							if($file != null)
							{
							echo $i.' :- ';
							$i++; ?>
								<a href="<?php echo base_url();?>/uploads/User/<?php echo $file; ?>" target='_blank' ><?php echo $file; ?></a><br/>
							<?php }} ?>
					</div>
				</div><br/>
				<div class="modal-footer">
					<a href="<?php echo base_url(); ?>index.php/User" class="btn btn-default pull-left" >Cancel</a>
				</div>
		
				
            </div>
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
