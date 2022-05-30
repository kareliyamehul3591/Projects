  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Message Box
        <small>Message box</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Message box</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
			<div class="box box-default">
				<!-- /.box-body -->
				<div class="box-footer no-padding">
					<ul class="products-list product-list-in-box" style="overflow: scroll;height: 695px;" >
						<?php 
							$all_messages = $this->Mdl_common->all_message('Manager');
							$all_messages_me = $this->Mdl_common->all_message_me('Manager');
							$user = array();
							foreach($all_messages_me as $all_message_me)
							{
								$user[] =  array(
									'assignment_id' => $all_message_me['assignment_id'],
									'to_id' => $all_message_me['from_id'],
									'to_role' => $all_message_me['from_role'],
								);
							}
							foreach($all_messages as $all_message)
							{
								$user[] =  array(
									'assignment_id' => $all_message['assignment_id'],
									'to_id' => $all_message['to_id'],
									'to_role' => $all_message['to_role'],
								);
							}
							$user = array_map("unserialize", array_unique(array_map("serialize", $user)));
							foreach($user as $data)
							{
								$to_id = $this->Mdl_common->user_get($data['to_id']);
								if($to_id[0]['status'] == 1)
								{
									if($data['to_role'] == "Help Desk")
									{
										$status = '5';
									}else if($data['to_role'] == "Admin"){
										$status = '1';
									}else if($data['to_role'] == "Manager"){
										$status = '2';
									}else if($data['to_role'] == "Writer"){
										$status = '3';
									}else if($data['to_role'] == "Proof Reader"){
										$status = '4';
									}
									$c = 0;
									$messages = $this->Mdl_manager_message->message($to_id[0]['id'],$data['to_role'],'Manager',$data['assignment_id']);
									foreach($messages as $messag)
									{
										if($this->session->Admindetail['id'] == $messag['from_id'] && $messag['from_role'] == $data['to_role'])
										{
											if($messag['status'] == 0)
											{
												$c++;
											}
										}
										
									}
									$proof_reader_url = base_url()."index.php/Manager_message?id=".$to_id[0]['id']."&role=".$data['to_role']."&assignment_id=".$data['assignment_id'];
									echo '<li class="item">
										<div class="product-info" style="margin-left: 13px;"><a  href="'.$proof_reader_url.'">
											<div class="product-title" >'.ucfirst($to_id[0]['name']).'
												<span class=" pull-right" style="margin-right: 8px;" >('.$data['to_role'].')</span>';
												if($c != 0)
												{
													echo '<span class="badge bg-yellow pull-right">'.$c.'</span>';
												}
												echo '</div>
												<span class="product-description">
												  Assignment Id '.$data['assignment_id'].'
												</span></a>
										</div>
									</li>';
								}
							}
						?>
					</ul>
				</div>
			</div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
		<?php if($_GET['id'] != null){ 
		$ids = $this->Mdl_manager_message->user_id($_GET['id']);
		?>
		  <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
					<h3 class="box-title"><?php echo ucfirst($ids[0]['name']); ?> 
					</h3>
					<?php
						if($ids[0]['online'] == 1)
						{
							echo '&emsp; <i class="fa fa-circle text-success"></i> Online ';
						}else{
							echo '&emsp; <i class="fa fa-circle-o" aria-hidden="true"></i> Offline ';
						}
					?>
					<button type="submit" onclick="message_deletes()" class="btn btn-social-icon btn-bitbucket" style="float: right;" ><i class="fa fa-bitbucket"></i></button>
                </div>
                <div class="box-body">
                  <div class="direct-chat-messages message_boxs " style="height: 600px;">
				  
				  <?php
				  $message = $this->Mdl_manager_message->message($_GET['id'],$_GET['role'],'Manager',$_GET['assignment_id']);
				  foreach($message as $messag){
					  
					  $user_id = $this->Mdl_manager_message->user_id($messag['from_id']);
					  if($this->session->Admindetail['id'] == $messag['from_id'] && $messag['from_role'] == $_GET['role']){
					  ?>
							<div class="direct-chat-msg">
								<div class="direct-chat-info clearfix">
									<span class="direct-chat-name pull-left"><?php echo ucfirst($user_id[0]['name']); ?></span>
									<span class="direct-chat-timestamp pull-right"><?php echo date("jS F g:i a",strtotime($messag['created_date'])); ?></span>
								</div>
								<img class="direct-chat-img" src="<?php echo base_url(); ?>assets/dist/img/user1-128x128.jpg" alt="message user image">
								<div class="direct-chat-text" style="overflow-wrap: break-word;" >
									<?php echo $messag['message']; ?>
								</div>
							</div>
							
					  <?php }else{ ?>
					  
							<div class="direct-chat-msg right">
								<div class="direct-chat-info clearfix">
									<span class="direct-chat-name pull-right"><?php echo ucfirst($user_id[0]['name']); ?></span>
									<span class="direct-chat-timestamp pull-left"><?php echo date("jS F g:i a",strtotime($messag['created_date'])); ?></span>
								</div>
								<img class="direct-chat-img" src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" alt="message user image">
								<div class="direct-chat-text" style="overflow-wrap: break-word;" >
									<?php echo $messag['message']; ?>
								</div>
							</div>

					  <?php } } ?>
                  </div>
                </div>
				<div class="box-footer">
					<!--<form method="post" action="<?php echo base_url(); ?>index.php/Manager_message/message_add" >-->
					<form action="javascript:message_add();">
						<div class="input-group">
							<input type="hidden" name="to_id" id="to_id" value="<?php echo $_GET['id']; ?>">
							<input type="hidden" name="to_role" id="to_role" value="<?php echo $_GET['role']; ?>">
							<input type="hidden" name="assignment_id" id="assignment_id" value="<?php echo $_GET['assignment_id']; ?>">
							
							<input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
							<span class="input-group-btn">
								<button type="button" onclick="message_add()" class="btn btn-primary btn-flat">Send</button>
							</span>
						</div>
					</form>
				</div>
              </div>
		<?php } ?>
		  
		  <input type="hidden" id="count" name="count" value="<?php echo count($message); ?>" >
		  <input type="hidden" id="scrol" name="scrol" value="1" >
		  <?php if($_GET['id'] != null){?>
			<script>
				setMarkers();
				function setMarkers() {
					setTimeout(function () {	
					
						var count = document.getElementById("count").value;
						var to_id = document.getElementById("to_id").value;
						var to_role = document.getElementById("to_role").value;
						var assignment_id = document.getElementById("assignment_id").value;
						var from_role = "Manager";
						var scrol = document.getElementById("scrol").value;
						
						$.ajax({
							url: '<?php echo base_url(); ?>index.php/Message/message_get/',
							type: "POST",
							data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id,count: count }),
							dataType: "json",
							success: function (data) { 
								if(data.status == 1)
								{
									$(".message_boxs").append(data.datas);
									$(".message_boxs").stop().animate({ 
										scrollTop: $(".message_boxs")[0].scrollHeight
									}, 1000);
									document.getElementById("count").value = data.count;
								}else if(data.status == 0)
								{
									document.getElementById("count").value = data.count;
								}
								if(scrol == 1){
									$(".message_boxs").stop().animate({ 
										scrollTop: $(".message_boxs")[0].scrollHeight
									}, 1000);
									document.getElementById("scrol").value = 2;
								}
								setMarkers();
							}
						});						
					}, 500);
				} 
				function message_deletes()
				{
					var to_id = document.getElementById("to_id").value;
					var to_role = document.getElementById("to_role").value;
					var assignment_id = document.getElementById("assignment_id").value;
					var from_role = "Manager";
					$.ajax({
						url: '<?php echo base_url(); ?>index.php/Message/message_remove/',
						type: "POST",
						data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id }),
						success: function (data){
							$(".message_boxs").html('');
							document.getElementById("count").value = 0;
						}
					});
				}
				function message_add()
				{
					var to_id = document.getElementById("to_id").value;
					var to_role = document.getElementById("to_role").value;
					var assignment_id = document.getElementById("assignment_id").value;
					var from_role = "Manager";
					var message = document.getElementById("message").value;
					if(message != "")
					{
						$.ajax({
							url: '<?php echo base_url(); ?>index.php/Message/message_add/',
							type: "POST",
							data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id,message: message }),
							success: function (data) {
								document.getElementById("message").value = "";
							}
						});
					}
				}							
			</script>
		  <?php } ?>
		  
		  
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->