<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_search extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_manager_search');
		$this->load->library('pagination');
	}
	public function index($id)
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			if($_POST['users'] != null)
			{
				$Search['users'] = $_POST['users'];
				$Search['name'] = $_POST['name'];
				$this->session->set_userdata("manager_search",$Search);
			}
			if($id == null && $_POST['users'] == null)
			{
				$this->session->unset_userdata("manager_search");
			}
			$user = $this->session->manager_search['users'];
			$name = $this->session->manager_search['name'];
			if($user == 'users_id' || $user == 'users_name' || $user == 'users_first_name' || $user == 'users_last_name')
			{
				$userss = $this->Mdl_manager_search->users($user,$name);
				foreach($userss as $users)
				{
					$users['users'] = 'users';
					foreach($users as $key => $role)
					{
						if($key == 'admin' || $key == 'manager' || $key == 'writer' || $key == 'proof_reader' || $key == 'help_desk')
						{
							$users['role'] = $key;
							$users['role_value'] = $role;
							$datas[] = $users;
						}
					}
					
				}
			}
			if($user == 'assignment_id' || $user == 'assignment_name')
			{
				$assignments = $this->Mdl_manager_search->assignments($user,$name);
				foreach($assignments as $assignment)
				{
					$assignment['users'] = 'assignment';
					$datas[] = $assignment;
				}
			}
			
			$count = count($datas);
			if($id != null)
			{
				$datass = array_slice($datas, $id, 8);
			}else{
				$datass = array_slice($datas, 0, 8);
			}
			
			
			$config['base_url'] = base_url().'index.php/Manager_search/index';
			$config['total_rows'] = $count;
			$config['per_page'] = 8;

			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a>';
			$config['cur_tag_close'] = '</a></li>';
			
			$this->pagination->initialize($config);

			$data=array(
				'datas'=>$datass,
				'main_content'=>'manager_search',
				'left_sidebar'=>'Manager Search',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
}
?>