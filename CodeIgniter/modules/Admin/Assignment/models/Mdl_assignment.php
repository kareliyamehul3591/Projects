<?php
class Mdl_assignment extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}
	
	function get_file_extension($filename)
    {
        $parts=explode('.',$filename);
        return $parts[count($parts)-1];
    }
	function assignment_admin_status($id,$status)
    {
        $data=array(
			'admin_status'=>$status,
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	function taske_data($id)
	{
		$this->db->where('assignment_id', $id);
		$data=$this->db->get('task');
		return $data->result_array(); 
	}
	function all_managers()
	{
		$this->db->where('manager', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_writers()
	{
		$this->db->where('writer', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_proof_readers()
	{
		$this->db->where('proof_reader', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function online_managers()
	{
		$this->db->where('manager', 1);
		$this->db->where('online', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_managers()
	{
		$this->db->where('manager', 1);
		$this->db->where('online', 0);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function online_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 0);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function online_proof_reader()
	{
		$this->db->where('proof_reader', 1);
		$this->db->where('online', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_proof_reader()
	{
		$this->db->where('proof_reader', 1);
		$this->db->where('online', 0);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function assignment_add($filename)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'name'=>$_POST['name'],
			'admin_id'=>$admin_id,
			'client_name'=>$_POST['client_name'],
			'deadline_date'=>date('Y-m-d', strtotime( $_POST['deadline_date'] )),
			'deadline_time'=>date('h:i A', strtotime( $_POST['deadline_date'] )),
			'assignment_type'=>$_POST['assignment_type'],
			'tasks_no'=>$_POST['tasks_no'],
			'health'=>$_POST['health'],
			'article'=>$_POST['article'],
			'action'=>$_POST['action'],
			'description'=>$_POST['description'],
			'file'=>$filename,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>1,
		);
		$this->db->insert('assignment',$data);
		return $this->db->insert_id();
    }
	function assignment_edit($filename)
    {
		$id = $_POST['ids'];
        $data=array(
			'name'=>$_POST['name'],
			'client_name'=>$_POST['client_name'],
			'deadline_date'=>date('Y-m-d', strtotime( $_POST['deadline_date'] )),
			'deadline_time'=>date('h:i A', strtotime( $_POST['deadline_date'] )),
			'assignment_type'=>$_POST['assignment_type'],
			'tasks_no'=>$_POST['tasks_no'],
			'health'=>$_POST['health'],
			'article'=>$_POST['article'],
			'action'=>$_POST['action'],
			'description'=>$_POST['description'],
			'file'=>$filename,
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	
	function approval_assignment($id,$status)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'status'=>$status,
			'admin_id'=>$admin_id,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	function assignment()
	{
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	
	function approval_assignment_list()
	{
		$this->db->where('status', 0);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignment_id($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignment_delete($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->delete('assignment');
		return true; 
	}
	function file_update($filename)
	{
		$id = $_POST['id'];
		$data=array(
            'file'=>$filename,
        );
    	$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
	}
	function active_assignment()
	{
		$id = $_POST['id'];
		$data=array(
            'status'=>$_POST['status'],
        );
    	$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
	}
	function assign_to_ma()
	{
		$data=$this->db->get('assign_to_ma');
		return $data->result_array(); 
	}
	function assign_to_manager($id,$assignment_id,$deadline_date,$save_hours,$assign_hours)
    {
		$datas=array(
			'assignment_id'=>$assignment_id,
			'deadline_date'=>$deadline_date,
			'admin'=>$save_hours,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('save_time',$datas);
		
        $data=array(
			'assignment_id'=>$assignment_id,
			'manager_id'=>$id,
			'deadline_date'=>$deadline_date,
			'assign_hours'=>$assign_hours,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>0,
		);
		$this->db->insert('assign_to_ma',$data);
    }
	function notifications_list($id,$ststus,$assignment_id,$to_role)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$assignment_id,
			'from_id'=>$admin_id,
			'from_role'=>"Admin",
			'to_id'=>$id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	function logs_insert($id,$description,$status)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$admin_id,
			'from_role'=>'Admin',
			'status'=>$status,
			'description'=>$description,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function logs_insert_c($id,$to_id,$reason,$status)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Admin",
			'to_id'=>$to_id,
			'to_role'=>"Manager",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function logs_insert_w($id,$to_id,$reason,$status)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Admin",
			'to_id'=>$to_id,
			'to_role'=>"Writer",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function logs_insert_p($id,$to_id,$reason,$status)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Admin",
			'to_id'=>$to_id,
			'to_role'=>"Proof Reader",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function managet_save_time($assignment_id,$save_hours)
    {
		$this->db->where('assignment_id', $assignment_id);
        $datas=array(
			'manager'=>$save_hours,
		);
		$this->db->update('save_time',$datas);
    }
	function assign_to_writer($id,$assignment_id,$deadline_date,$assign_hours_writer)
    {
        $data=array(
			'assignment_id'=>$assignment_id,
			'writer_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'deadline_date'=>$deadline_date,
			'assign_hours'=>$assign_hours_writer,
			'status'=>0,
		);
		$this->db->insert('assign_to_writer',$data);
    }
	function assign_to_proof_reader($id,$assignment_id,$deadline_date,$assign_hours_proof_reader)
    {
        $data=array(
			'assignment_id'=>$assignment_id,
			'proof_reader_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'deadline_date'=>$deadline_date,
			'assign_hours'=>$assign_hours_proof_reader,
			'status'=>0,
		);
		$this->db->insert('assign_to_pr',$data);
    }
	function dropdowns($tabel)
	{
		if($tabel != 'client')
		{
			$this->db->order_by("name","asc");
		}
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
}
?>