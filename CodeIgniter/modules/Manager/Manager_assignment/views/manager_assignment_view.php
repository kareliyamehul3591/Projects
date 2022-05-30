  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Assignment
        <small>View Assignment tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>index.php/Manager_dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">View Assignment</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		
          <div class="box box-info">
            <div class="box-header">
				<h3 class="box-title">View Assignment Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<style>
				td a i {
					margin-left: 4px;
					margin-right: 4px;
				}
				</style>
				<table class="table table-bordered table-striped datatable">
					<thead>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Client Name </th>
							<th> Deadline </th>
							<th> Type </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($datas as $data) { ?>
						<tr>
							<td><?php echo $data['id']; ?></td>
							<td><?php echo $data['name']; ?></td>
							<td><?php echo $data['client_name']; ?></td>
							<td><?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.$data['deadline_time']; ?></td>
							<td><?php echo $data['assignment_type']; ?></td>
							<td align="center"> 
								<?php if($data['status'] == 1){
										echo '<i id="active10" class="fa fa-check-square-o" aria-hidden="true" onclick="suspended('.$data['id'].',0)"></i>';
									}else{
										echo '<i id="active10" class="fa fa-square-o" aria-hidden="true" onclick="suspended('.$data['id'].',1)"></i>';
									}?>
							</td>
							<td>
								<?php
								$view_url = "myModal('".base_url()."index.php/Dashboard/view_taske/".$data['id']."')";
								$edit_url = "myModal('".base_url()."index.php/Manager_assignment/edit_taske/".$data['id']."')";
								
								echo '<a onclick="'.$view_url.'" style="color: #13c5ff;"><i class="fa fa-eye fa-lg"></i></a>';
								echo '<a onclick="'.$edit_url.'" style="color: #089048;"><i class="fa fa-pencil fa-lg"></i></a>';
									
								?>
								<a href="<?php echo base_url(); ?>index.php/Assignment/assignment_delete/<?php echo $data['id']; ?>" style="color: #ff0808;" ><i class="fa fa-trash-o fa-lg"></i></a>
							</td>
						</tr>
					<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th> ID </th>
							<th> Name </th>
							<th> Client Name </th>
							<th> Deadline </th>
							<th> Type </th>
							<th> Status </th>
							<th> Actions </th>
						</tr>
					</tfoot>
				</table>
				<script>
					function suspended(id,status) {
						$.ajax({
							type: "POST",
							url: '<?php echo base_url(); ?>index.php/Assignment/active_assignment',
							data: {
									id: id,
									status: status,
									}, 
							success: function(data){
								 location.reload();
							},
							error: function(){
								console.log("data not found");
							}
						});
					}
				</script>
				<script>
					function myModal(url)
					{
						$('.myModal_content').load(url,function(){
							$('#myModal').modal({show:true});
						});
					}
				</script>
				<div class="modal fade" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content myModal_content">
							
						</div>
					</div>
				</div>
				
				
				
				<script type="text/javascript">
					function remove_file(ids,id)
					{
						var images_r = confirm("Are you sure want to delete?");	
						if (images_r == true) {
							$.ajax({
								type:'POST',
								url: '<?php echo base_url(); ?>index.php/Assignment/remove_file',
								data:{ id : id , ids : ids },
								success:function(data){
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
