<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Writer_dashboard extends MX_Controller {
	
	function __construct()	{		
		parent::__construct();		
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_writer_dashboard');	
	}
	public function index()	
	{
		if($this->session->Admindetail['writer'] == 1)
		{			
			$assign_to_ma = $this->Mdl_writer_dashboard->assign_to_ma();
			foreach($assign_to_ma as $key => $assign_to_man){	
				if($assign_to_man['status'] != 2)			
				{
					$date = $this->Mdl_writer_dashboard->assignment($assign_to_man['assignment_id']);
					$assignment_data[$key] = $date[0];						
					$assignment_data[$key]['assign_to_ma_status'] = $assign_to_man['status'];
					$assignment_data[$key]['assign_hours'] = $assign_to_man['assign_hours'];
					$assignment_data[$key]['assign_created_date'] = $assign_to_man['created_date'];	
				}
			}
			$i = 0;
			foreach($assignment_data as $val) {
				if (!in_array($val['id'], $key_array)) {
					$key_array[$i] = $val['id'];
					$assignment_datas[$i] = $val;
				}
				$i++;
			}
			$data=array(				
				'datas'=>$assignment_datas,
				'main_content'=>'writer_dashboard',	
				'left_sidebar'=>'Writer Dashboard',	
			);			
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}	
	}
	public function view_taske($id)
	{
		$assignment_data = $this->Mdl_writer_dashboard->assignment($id);
		$ma_id = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
		$data = $assignment_data[0];
		$data['assign_to_ma_status'] = $ma_id[0]['status'];
		?>
		
				<div class="modal-header"> <a class="close" data-dismiss="modal">&times;</a>
					<h4 id="myModalLabel" class="modal-title">View</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<tr>
									<th style="width:50%">Name:</th>
									<td>
										<?php echo $data['name']; ?>
									</td>
								</tr>
								<tr>
									<th>Client Name:</th>
									<td>
										<?php echo $data['client_name']; ?>
									</td>
								</tr>
								<tr>
									<th>Deadline:</th>
									<td>
										<?php echo date('m/d/Y', strtotime( $data['deadline_date'] )).' '.$data['deadline_time']; ?>
									</td>
								</tr>
								<tr>
									<th>Type:</th>
									<td>
										<?php echo $data['assignment_type']; ?>
									</td>
								</tr>
								<tr>
									<th>No.of Tasks:</th>
									<td><?php echo $data['tasks_no']; ?></td>
								</tr>
								<tr>
									<th>Niche:</th>
									<td><?php echo $data['health']; ?></td>
								</tr>
								<tr>
									<th>No.of Words/Article:</th>
									<td><?php echo $data['article']; ?></td>
								</tr>
								<tr>
									<th>Description:</th>
									<td>
										<?php echo $data['description']; ?>
									</td>
								</tr>
								<tr>
									<th>File:</th>
									<td>
										<?php															
										foreach(explode(",", $data['file']) as $file)
										{ ?>
											<a href="<?php echo base_url();?>/uploads/Assignment/<?php echo $file; ?>" target='_blank'>
												<?php echo $file; ?>
											</a><br/>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php 
				if($data['status'] != 4){							
					if($data['assign_to_ma_status'] == 0){		
						echo '<a class="btn btn-default pull-left" data-dismiss="modal" onclick="assign_to_ma_status('.$data['id'].',1)" >Accept</a> &nbsp; &nbsp; &nbsp; ';
						echo '<a class="btn btn-default pull-left" data-dismiss="modal" onclick="assign_to_ma_status('.$data['id'].',2)" style="margin-left: 10px;" >Reject</a><br/>';
					}	
				} ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				</div>
		
		<?php
	}
	public function assignment_data($id)
	{
		if($this->session->Admindetail['writer'] == 1)
		{
			$assignment_data = $this->Mdl_writer_dashboard->assignment($id);
			$data=array(
				'assignment_id'=>$id,
				'datas'=>$assignment_data,
				'main_content'=>'assignment_data',
				'left_sidebar'=>'Writer Dashboard',
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}	
	public function assignment_view($id)	
	{		
		if($this->session->Admindetail['writer'] == 1)	
		{			
			$assignment_data = $this->Mdl_writer_dashboard->assignment($id);			
			$assignment_final_data = $this->Mdl_writer_dashboard->assignment_final_data($id);
			$assign_to_ma = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
			$assignment_data[0]['assign_to_ma_status'] = $assign_to_ma[0]['status'];
			$data = array(
				'datas'=>$assignment_data,
				'assignment_final_data'=>$assignment_final_data,
				'main_content'=>'assignment_view',
				'left_sidebar'=>'Writer Dashboard',	
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function assignment_image_add()	
	{
		$id = $_POST['assignment_id'];
		if (!is_dir('uploads/Assignment/'.$id)) {
			mkdir('./uploads/Assignment/'.$id, 0777, TRUE);		
		}
		$count = count($_FILES['file']['name']);
		$count--;
		$assignment_final_data = $this->Mdl_writer_dashboard->assignment_final_data($id);
		$filess = explode(",",$assignment_final_data[0]['file']);
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
			$ext=$this->Mdl_writer_dashboard->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);			
			if(preg_match('/\s/',$_FILES['image']['name']) != 0){
				$error_msg='in Assigned File space is not Require.';
				$this->session->set_flashdata('error_msg',$error_msg);
				redirect('Writer_dashboard/assignment_view/'.$id);
			}
			
			if($ext == 'docx' || $ext == 'doc')			
			{
				$filename[$i]='';
				$filename[$i]=$_FILES['image']['name'];
				$config['upload_path'] = './uploads/Assignment/'.$id.'/';
				$config['allowed_types'] = 'docx|doc';
				$config['overwrite'] = TRUE;
				$config['file_name']=$filename[$i];
				$this->load->library('upload');
				$this->upload->initialize($config);
				if($this->upload->do_upload('image'))
				{
					$config['image_library'] = 'gd2';
					$config['source_image']  = './uploads/Assignment/'.$id.'/'.$filename[$i];
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				foreach($filess as $key => $files)
				{
					if($files == $_FILES['image']['name'])
					{
						unset($filess[$key]);
					}
				}
			}
		}
		if($assignment_final_data == null)
		{
			$this->Mdl_writer_dashboard->assignment_image_add($id,implode(",",$filename));
		}else{
			$filess = array_filter($filess);
			$filename = array_filter($filename);
			if($filess == null)
			{
				$data = implode(",",$filename);
			}else{
				$data = implode(",",$filess).','.implode(",",$filename);
			}
			$this->Mdl_writer_dashboard->assignment_image_update($id,$data);
		}
		$this->Mdl_writer_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment data save.';
		$this->session->set_flashdata('success_msg',$success_msg);		
		redirect('Writer_dashboard');	
	}		
	public function assignment_remove_file()
	{		
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_writer_dashboard->assignment_final_data($id);
		$getemage = explode(',',$getdatas[0]['file']);
		unset($getemage[$ids]);
		$images = $this->Mdl_writer_dashboard->assignment_remove_file($id,implode(",", $getemage));
		$this->Mdl_writer_dashboard->assignment_date_update($id);
		
		$success_msg='Selacted Assignment File Remove.';
		$this->session->set_flashdata('success_msg',$success_msg);
		
	}		
	public function active_assignment()	
	{
		$user_data = $this->Mdl_writer_dashboard->active_assignment($_POST['id'],$_POST['status']);
		$data = $this->Mdl_writer_dashboard->manager_id_get($_POST['id']);
		if($_POST['status'] == 1)
		{
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Accepted',$data[0]['manager_id']);
		}else if($_POST['status'] == 2){
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$data[0]['manager_id']);
		}
		$this->Mdl_writer_dashboard->assignment_date_update($_POST['id']);
		if($_POST['status'] == 1)
		{
			$success_msg='Selacted Assignment Accepted.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else if($_POST['status'] == 2){
			$success_msg='Selacted Assigned Rejected.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
	}	
	public function reject_assignment()	
	{		
		print_r($_POST);
		$datetime1 = new DateTime(date("Y-m-d H:i:s"));
		$datetime2 = new DateTime($_POST['created_date']);
		$interval = $datetime1->diff($datetime2);
		$hours = $interval->h + ($interval->days*24);
		if($hours != '0')
		{			
			$data = $this->Mdl_writer_dashboard->manager_id_get($_POST['id']);
			$user_data = $this->Mdl_writer_dashboard->active_assignment($_POST['id'],2);
			$this->Mdl_writer_dashboard->notifications($_POST['id'],'Rejected',$data[0]['manager_id']);
			$this->Mdl_writer_dashboard->assignment_date_update($_POST['id']);
		}
		echo $hours;	
	}
	public function active_assignment_list()
	{
		$ids = explode(",",$_POST['id']);
		$i = 0;
		$assignment_data = $this->Mdl_writer_dashboard->assign_to_ma_list();
		foreach($assignment_data as $data)
		{
			foreach($ids as $id){
				if($id == $data['assignment_id'])
				{
					$datas = $this->Mdl_writer_dashboard->manager_id_get_list($data['assignment_id']);
					$this->Mdl_writer_dashboard->active_assignment($data['assignment_id'],$_POST['status']);
					if($_POST['status'] == 1)
					{
						$this->Mdl_writer_dashboard->notifications($data['assignment_id'],'Accepted',$datas[0]['manager_id']);
					}else if($_POST['status'] == 2){
						$this->Mdl_writer_dashboard->notifications($data['assignment_id'],'Rejected',$datas[0]['manager_id']);
					}
					$this->Mdl_writer_dashboard->assignment_date_update($data['assignment_id']);
					$i++;
				}
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid Assigned.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			if($_POST['status'] == 1)
			{
				$success_msg='Selacted Assignment Accepted.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}else if($_POST['status'] == 2){
				$success_msg='Selacted Assigned Rejected.';
				$this->session->set_flashdata('success_msg',$success_msg);
			}
		}
	}
	public function assign_to_ma_add_time()
	{		
		$user_data = $this->Mdl_writer_dashboard->managet_save_time($_POST['id']);
		if($user_data[0]['writer'] == null)
		{
			$times = ($user_data[0]['manager'] / 3);
			$this->Mdl_writer_dashboard->managet_save_time_update($_POST['id'],$times,($times *2));
			$this->Mdl_writer_dashboard->assign_to_writer_updste($_POST['id'],($times *2) + $_POST['assign_hours']);
		}else{
			$this->Mdl_writer_dashboard->assignment_remove($_POST['id']);
			$this->Mdl_writer_dashboard->active_assignment($_POST['id'],2);
			$this->Mdl_writer_dashboard->assignment_manager_remove($_POST['id'],2);
		}	
	}
	public function complete_assignment($id)
	{		
		$assignment_final_data = $this->Mdl_writer_dashboard->assignment_final_data($id);
		if($assignment_final_data == null)
		{
			$this->Mdl_writer_dashboard->assignment_image_add_null($id);
		}
		$data = $this->Mdl_writer_dashboard->manager_id_get($id);
		$data_pr = $this->Mdl_writer_dashboard->pr_id_get($id);
		$user_data = $this->Mdl_writer_dashboard->active_assignment($id,5);
		$this->Mdl_writer_dashboard->notifications($id,'Completed',$data[0]['manager_id']);
		$this->Mdl_writer_dashboard->notifications($id,'Completed',$data_pr[0]['proof_reader_id']);		
		$this->Mdl_writer_dashboard->assignment_date_update($id);
		$success_msg='Selacted Assignment complete.';
		$this->session->set_flashdata('success_msg',$success_msg);				
		redirect('Writer_dashboard');
	}
	public function complete_assignment_list()
	{
		$ids = explode(",",$_POST['id']);
		$i = 0;
		foreach($ids as $id){
			$writer = $this->Mdl_writer_dashboard->assign_to_ma_id($id);
			if($writer[0]['status'] == 1)
			{
				$data = $this->Mdl_writer_dashboard->manager_id_get($id);
				$data_pr = $this->Mdl_writer_dashboard->pr_id_get($id);
				$user_data = $this->Mdl_writer_dashboard->active_assignment($id,5);
				$this->Mdl_writer_dashboard->notifications($id,'Completed',$data[0]['manager_id']);
				$this->Mdl_writer_dashboard->notifications($id,'Completed',$data_pr[0]['proof_reader_id']);
				$this->Mdl_writer_dashboard->assignment_date_update($id);
				$i++;
			}
		}
		if($i == 0)
		{
			$error_msg='Failed Please selecte valid data.';
			$this->session->set_flashdata('error_msg',$error_msg);
		}else{
			$success_msg='Selacted Assignment Completed.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
	}	
}?>