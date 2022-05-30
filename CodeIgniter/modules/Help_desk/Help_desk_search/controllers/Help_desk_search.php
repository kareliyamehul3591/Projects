<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_desk_search extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_help_desk_search');
		$this->load->library('pagination');
	}
	public function index($id)
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			if($_POST['users'] != null)
			{
				$Search['users'] = $_POST['users'];
				$Search['name'] = $_POST['name'];
				$this->session->set_userdata("help_desk_search",$Search);
			}
			if($id == null && $_POST['users'] == null)
			{
				$this->session->unset_userdata("help_desk_search");
			}
			$user = $this->session->help_desk_search['users'];
			$name = $this->session->help_desk_search['name'];
			if($user == 'users_id' || $user == 'users_name' || $user == 'users_first_name' || $user == 'users_last_name')
			{
				$userss = $this->Mdl_help_desk_search->users($user,$name);
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
			if($user == 'client_id' || $user == 'client_first_name' || $user == 'client_last_name')
			{
				$clients = $this->Mdl_help_desk_search->clients($user,$name);
				foreach($clients as $client)
				{
					$client['users'] = 'clients';
					$datas[] = $client;
				}
			}
			if($user == 'assignment_id' || $user == 'assignment_name')
			{
				$assignments = $this->Mdl_help_desk_search->assignments($user,$name);
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
			
			
			$config['base_url'] = base_url().'index.php/Help_desk_search/index';
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
				'main_content'=>'help_desk_search',
				'left_sidebar'=>'Help Desk Search',
			);
			$this->load->view('help_desk_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
}
?>