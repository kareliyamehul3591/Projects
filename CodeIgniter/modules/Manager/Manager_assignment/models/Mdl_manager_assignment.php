<?php
class Mdl_manager_assignment extends CI_Model
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
	function assignments($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignment_add($filename)
    {
        $data=array(
			'admin_id'=>0,
			'created_id'=>$this->session->Admindetail['id'],
			'created_role'=>"manager",
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
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>0,
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
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>0,
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	function assignment()
	{
		$this->db->where('created_id', $this->session->Admindetail['id']);
		$this->db->where('created_role', 'manager');
		$this->db->where('status', 0);
		$this->db->or_where('status',2);
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
	function logs_insert($id,$description)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>'Manager',
			'status'=>$description,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function logs_insert_c($id,$status,$reason)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Manager",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	
	function notifications($id,$ststus,$admin_id,$to_role)
    {
		$manager_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'from_role'=>"Manager",
			'to_id'=>$admin_id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
		
    }
	function all_admin_get()
	{
		$this->db->where('admin', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
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