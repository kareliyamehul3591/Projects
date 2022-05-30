<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>        View Assignment        <small>View Assignment tables</small>      </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>index.php/Writer_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">View Assignment</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">View Assignment</h3> </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Name :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['name']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Client Name :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['client_name']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Deadline :-</label>
                                    <div class="col-sm-7">
                                        <?php echo date('m/d/Y', strtotime( $datas[0]['deadline_date'] )).' '.$datas[0]['deadline_time']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Type Of Assignment :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['assignment_type']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">No of Taske :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['tasks_no']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Niche :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['health']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">No of Words/Article :-</label>
                                    <div class="col-sm-7">
                                        <?php echo $datas[0]['article']; ?>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">Status :-</label>
                                    <div class="col-sm-7">
                                        <?php 
									if($datas[0]['status'] == 4){
										echo 'Cancelled';
									}else if($datas[0]['status'] == 5){
										echo 'Complete';
									}else if($datas[0]['status'] == 1){
										echo 'Accepted';
									}
								?>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Description :-</label>
                                    <div class="col-sm-9">
                                        <?php echo $datas[0]['description']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">File :-</label>
                                    <div class="col-sm-7">
                                        <?php 									
										foreach(explode(",", $datas[0]['file']) as $file)									
										{ ?>
                                            <a href="<?php echo base_url();?>uploads/Assignment/<?php echo $file; ?>" target='_blank'>
                                                <?php echo $file; ?>
                                            </a>
                                            <br/>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-header">
                        <h3 class="box-title">ATTACHMENTS</h3> </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" class="login-form" action="<?php echo base_url(); ?>index.php/Writer_dashboard/assignment_image_add" id="assignment_add" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="assignment_id" id="assignment_id" value="<?php echo $datas[0]['id']; ?>" required>
                                <div class="col-xs-12">
                                    <textarea id="description" name="description" placeholder="Description">
                                        <?php echo $assignment_final_data[0]['description'];?>
                                    </textarea><br/><br/> 
								</div>
                                <?php if($datas[0]['assign_to_ma_status'] != 5){ ?>
                                    <div class="col-xs-6">
                                        <input type="file" name="file[]" id="file" class="form-control" accept=".docx,.doc" multiple> 
									</div>
								<?php } ?></br></br></br>
								<div class="col-xs-12">
									<?php 								
										foreach(explode(",", $assignment_final_data[0]['file']) as $file)			
										{ 																	
											if($file != NULL)									
											{										
												$key++;										
												echo $key." :- ";										
												echo '<a href="'.base_url().'uploads/Assignment/'.$datas[0]['id'].'/'.$file.'" target="_blank" >'.$file.'</a>';										
												if($datas[0]['assign_to_ma_status'] != 5){											
													echo '&nbsp; &nbsp; &nbsp;<i class="fa fa-times" onclick="remove_file('.$key.','.$datas[0]['id'].')"></i>';										
												}										
												echo '</br>';									
											}								
										} ?> 
								</div>
                            </div>
                            </br>
                            <?php if($datas[0]['assign_to_ma_status'] != 5){ ?>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
							<?php } ?>
                        </form>
                        <script type="text/javascript">
                            function remove_file(ids, id) {
                                var images_r = confirm("Are you sure want to delete?");
                                if (images_r == true) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '<?php echo base_url(); ?>index.php/Writer_dashboard/assignment_remove_file',
                                        data: {
                                            id: id,
                                            ids: ids
                                        },
                                        success: function(data) {
                                            location.reload();
                                        }
                                    });
                                }
                            }
                        </script>
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