<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_task');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$assignment_data = $this->Mdl_task->assignment();
			$data=array(
				'assignment'=>$assignment_data,
				'main_content'=>'task',
				'left_sidebar'=>'Task',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function task_add()
	{
		if (!is_dir('uploads/Task')) {
			mkdir('./uploads/Task', 0777, TRUE);
		}
		$count = count($_FILES['file']['name']);
		$count--;
		for($i = 0; $i <= $count; $i++)
		{
			$_FILES['image']['name']     = $_FILES['file']['name'][$i];
			$_FILES['image']['type']     = $_FILES['file']['type'][$i];
			$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
			$_FILES['image']['error']    = $_FILES['file']['error'][$i];
			$_FILES['image']['size']     = $_FILES['file']['size'][$i];
			
			$ext=$this->Mdl_task->get_file_extension($_FILES['image']['name']);
			$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
			if($ext == 'docx' || $ext == 'doc')		
			{
				$filename[$i]='';
				$filename[$i]=$_FILES['image']['name'];
				$config['upload_path'] = './uploads/Task/';
				$config['allowed_types'] = 'docx|doc';	
				$config['overwrite'] = TRUE;
				$this->load->library('upload');    
				$this->upload->initialize($config);
				if($this->upload->do_upload('image')) 
				{
				  $config['image_library'] = 'gd2';
				  $config['source_image']  = './uploads/Task/'.$filename[$i];
				  $this->load->library('image_lib', $config); 
				  $this->image_lib->resize(); 
				  $this->image_lib->clear();
				}
			}
		}
		$filename = array_filter($filename);
		$task_add=$this->Mdl_task->task_add(implode(",",$filename));
		redirect('Task/task_view');
	}
	
	public function task_edit()
	{
		if($_FILES['file']['name'][0] != null)
		{
			$count = count($_FILES['file']['name']);
			$count--;
			$filess = explode(",",$_POST['files']); 
			for($i = 0; $i <= $count; $i++)
			{
				$_FILES['image']['name']     = $_FILES['file']['name'][$i];
				$_FILES['image']['type']     = $_FILES['file']['type'][$i];
				$_FILES['image']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
				$_FILES['image']['error']    = $_FILES['file']['error'][$i];
				$_FILES['image']['size']     = $_FILES['file']['size'][$i];
			
				$ext=$this->Mdl_task->get_file_extension($_FILES['image']['name']);
				$_FILES['image']['name'] = str_replace(' ', '_', $_FILES['image']['name']);
				
				if($ext == 'docx' || $ext == 'doc')		
				{
					$filename[$i]='';
					$filename[$i]=$_FILES['image']['name'];
					$config['upload_path'] = './uploads/Task/';
					$config['allowed_types'] = 'docx|doc';	
					$config['overwrite'] = TRUE;
					$config['file_name']=$filename[$i];
					$this->load->library('upload');    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image')) 
					{
					  $config['image_library'] = 'gd2';
					  $config['source_image']  = './uploads/Task/'.$filename[$i];
					  $this->load->library('image_lib', $config); 
					  $this->image_lib->resize(); 
					  $this->image_lib->clear();
					}
				}
				foreach($filess as $key => $files)
				{
					if($files == $_FILES['image']['name'])
					{
						unset($filess[$key]);
					}
				}
			}
			$filess = array_filter($filess);
			$filename = array_filter($filename);
			$file = implode(",",$filess).','.implode(",",$filename);
		}else{
			$filess = explode(",",$_POST['files']);
			$filess = array_filter($filess);
			$file = implode(",",$filess);
		}
		$assignment_add=$this->Mdl_task->task_edit($file);
		redirect('Task/task_view');
	}
	
	public function task_view()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$assignment_data = $this->Mdl_task->assignment();
			$task_data = $this->Mdl_task->task();
			$data=array(
				'datas'=>$task_data,
				'assignment_data'=>$assignment_data,
				'main_content'=>'task_view',
				'left_sidebar'=>'Task View',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	
	public function assignment_data()
	{
		$getdatas = $this->Mdl_task->assignment_data();
		echo json_encode($getdatas[0]);
	}
	public function active_task()
	{
		$user_data = $this->Mdl_task->active_task();
	}
	public function task_delete($id)
	{
		if($id != null)
		{
			$user_data = $this->Mdl_task->task_delete($id);
			$success_msg='Task id Delete.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}else if(isset($_POST['task_list_delete'])){
			foreach($_POST['select_task_list'] as $list)
			{
				$user_data = $this->Mdl_task->task_delete($list);
			}
			$success_msg='Select Task is Delete.';
			$this->session->set_flashdata('success_msg',$success_msg);
		}
		redirect('Task/task_view');
	}
	public function remove_file()
	{
		$id = $_POST['id'];
		$ids = $_POST['ids'] - 1;
		$getdatas = $this->Mdl_task->task_id($id);
		foreach($getdatas as $ab)
		{
			$getemage = explode(',',$ab['file']);
		}
		unset($getemage[$ids]);
		$images = $this->Mdl_task->file_update(implode(",", $getemage));
	}
	
}
?>