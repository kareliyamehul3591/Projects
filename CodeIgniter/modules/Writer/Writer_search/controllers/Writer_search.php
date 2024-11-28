<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Writer_search extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_writer_search');
		$this->load->library('pagination');
	}
	public function index($id)
	{
		if($this->session->Admindetail['writer'] == 1)
		{
			if($_POST['name'] != null)
			{
				$Search['users'] = $_POST['users'];
				$Search['name'] = $_POST['name'];
				$this->session->set_userdata("writer_search",$Search);
			}
			if($id == null && $_POST['name'] == null)
			{
				$this->session->unset_userdata("writer_search");
			}
			$user = $this->session->writer_search['users'];
			$name = $this->session->writer_search['name'];
			if($user == 'assignment_id' || $user == 'assignment_name')
			{
				$assignments = $this->Mdl_writer_search->assignments($user,$name);
				foreach($assignments as $assignment)
				{
					$writer_id = $this->Mdl_writer_search->writer_id($assignment['id']);
					if($writer_id != null)
					{
						$assignment['users'] = 'assignment';
						$datas[] = $assignment;
					}
					
				}
			}
			$count = count($datas);
			if($id != null)
			{
				$datass = array_slice($datas, $id, 8);
			}else{
				$datass = array_slice($datas, 0, 8);
			}
			$config['base_url'] = base_url().'index.php/Writer_search/index';
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
				'main_content'=>'writer_search',
				'left_sidebar'=>'Writer Search',
			);
			$this->load->view('writer_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
}
?>