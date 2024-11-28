<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1> Contact </h1>
	</section>

  
		  
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
		
			
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Contact</h3>
					</div>
					
					<form class="form-horizontal" action="javascript:contact_data();" >
					  <div class="box-body">
					  
						<div class="form-group">
						  <label  class="col-sm-2 control-label">Name* :</label>

						  <div class="col-sm-5">
							<input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label  class="col-sm-2 control-label">Email* :</label>

						  <div class="col-sm-5">
							<input class="form-control" id="email" name="email" placeholder="Email" type="email" required >
						  </div>
						</div>
						
						<div class="form-group">
						  <label  class="col-sm-2 control-label">Contact No :</label>

						  <div class="col-sm-5">
							<input class="form-control" id="contact" name="contact" placeholder="Contact No" type="text">
						  </div>
						</div>
						
						<div class="form-group">
						  <label  class="col-sm-2 control-label">Message* :</label>

						  <div class="col-sm-5">
							<textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter your message here..." required ></textarea>
						  </div>
						</div>
						
						
					  </div>
					  <!-- /.box-body -->
					  <div class="box-footer">
						<button type="button" class="btn btn-default">Cancel</button>
						<button type="submit" class="btn btn-info pull-right">submit</button>
					  </div>
					  <!-- /.box-footer -->
					</form>
					
				</div>
			</div>
			
		</div>
		<script>
			function contact_data()
			{
				var name = document.getElementById("name").value;
				var email = document.getElementById("email").value;
				var contact = document.getElementById("contact").value;
				var message = document.getElementById("message").value;
				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Help/email/',
					type: "POST",
					data : ({ name: name,email: email,contact: contact,message: message}),
					success: function (data) {
						//alert(data);
						document.getElementById("name").value = "";
						document.getElementById("email").value = "";
						document.getElementById("contact").value = "";
						document.getElementById("message").value = "";
						location.reload(); 
					} 
				});	
			}
		</script>
		
    </section>
    <!-- /.content -->
  </div>