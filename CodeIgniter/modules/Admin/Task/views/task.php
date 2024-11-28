<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Add Task</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

	
		<div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Task</h3>
            </div>
            <div class="box-body">
				<form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Task/task_add" id="assignment_add" enctype="multipart/form-data" >
					<div class="row">
						<div class="col-xs-6">
							<select class="form-control" name="assignment_name" id="assignment_name" onchange="assignment_data(this.value);" required >
								<option value="">Select One Assignment Name *</option>
								<?php foreach($assignment as $assignments){?>
									<option value="<?php echo $assignments['id']; ?>"><?php echo $assignments['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-6">
							<input type="text" id="assignment_type" class="form-control" placeholder="Type Of Assignment" readonly >
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="text" id="assignment_id" class="form-control" placeholder="Assignment ID" readonly >						
						</div>
						<div class="col-xs-6">
							<input type="text" id="assignment_article" class="form-control" placeholder="No. of Words/Article" readonly >
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="text" name="title" id="title" class="form-control" placeholder="Title Name *" required >
						</div>
						<div class="col-xs-6">
							<input type="text" id="assignment_deadline" class="form-control" placeholder="Deadline" readonly >
						</div>
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword *" required >
						</div>
						<div class="col-xs-6">
							<input type="text" id="assignment_client_name" class="form-control" placeholder="Assignment Client Name" readonly >
						</div>
					</div></br>
					
					<div class="row">
						<div class="col-xs-12">
							<div id="assignment_description">Assignment Description</div>
						</div>
					</div></br>
					
					<div class="row">
						<div class="col-xs-6">
							<select class="form-control" name="action" id="action" >
								<option value="">Action Assign</option>
								<option value="Reassign">Reassign</option>
								<option value="Delete">Delete</option>
								<option value="Cancel">Cancel</option>
								<option value="Completed">Completed</option>
							</select>
						</div>
						
					</div></br>
					<div class="row">
						<div class="col-xs-6">
							<input type="file" name="file[]" id="file" class="form-control" multiple >
						</div>
					</div></br>
					<div class="box-footer">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#leave_the_page" >Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Submit</button>
					</div>
				</form>
				
				
				<div class="modal fade in" id="leave_the_page" style="display: none; padding-right: 17px;">
				  <div class="modal-dialog" style="width: 370px;" >
					<div class="modal-content">
					
					  <div class="modal-body">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">×</span></button>
						  <br/>
						<h4 style="text-align:center;" >
						<a style="color: #f3623b;"><i class="fa fa fa-exclamation-circle fa-3x" aria-hidden="true"></i> </a><br/>
						Are you sure you wany to leave the page? <br/>
						All the changes made will be lost !
						</h4>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Do not leave</button>
						<a href="<?php echo base_url(); ?>index.php/Dashboard" type="button" class="btn btn-primary">Leave Page</a>
					  </div>
					</div>
				  </div>
				</div>
				
				
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  <script>
			function assignment_data(id)
			{
				if(id == '')
				{
					document.getElementById("assignment_type").value = ""; 
					document.getElementById("assignment_id").value = ""; 
					document.getElementById("assignment_article").value = ""; 
					document.getElementById("assignment_deadline").value = ""; 
					document.getElementById("assignment_client_name").value = ""; 
					document.getElementById("assignment_description").innerHTML = "Assignment Description";
				}
				$.ajax({
					type: "POST",
					dataType: "json",
					url: '<?php echo base_url(); ?>index.php/Task/assignment_data',
					data: { id: id }, 
					success: function(data){
						document.getElementById("assignment_type").value = data.assignment_type; 
						document.getElementById("assignment_id").value = data.id; 
						document.getElementById("assignment_article").value = data.article; 
						document.getElementById("assignment_deadline").value = data.deadline_date+' '+data.deadline_time; 
						document.getElementById("assignment_client_name").value = data.client_name; 
						document.getElementById("assignment_description").innerHTML = data.description;
					},
					error: function(){
						console.log("data not found");
					}
				});
			}
		  </script>
		  
    </section>
    <!-- /.content -->
  </div>