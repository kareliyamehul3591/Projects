<?php
class Mdl_task extends CI_Model
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
	
	function task_add($filename)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$_POST['assignment_name'],
			'admin_id'=>$admin_id,
			'title'=>$_POST['title'],
			'keyword'=>$_POST['keyword'],
			'action'=>$_POST['action'],
			'file'=>$filename,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>1,
		);
		$this->db->insert('task',$data);
    }
	function task_edit($filename)
    {
		$id = $_POST['ids'];
         $data=array(
			'assignment_id'=>$_POST['assignment_name'],
			'title'=>$_POST['title'],
			'keyword'=>$_POST['keyword'],
			'action'=>$_POST['action'],
			'file'=>$filename,
		);
		$this->db->where('id', $id);
		$this->db->update('task',$data);
		return true; 
    }
	function assignment()
	{
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('admin_id', $admin_id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function task()
	{
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('admin_id', $admin_id);
		$data=$this->db->get('task');
		return $data->result_array(); 
	}
	function assignment_data()
	{
		$id = $_POST['id'];
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function active_task()
	{
		$id = $_POST['id'];
		$data=array(
            'status'=>$_POST['status'],
        );
    	$this->db->where('id', $id);
		$this->db->update('task',$data);
		return true; 
	}
	function task_delete($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->delete('task');
		return true; 
	}
	function task_id($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('task');
		return $data->result_array(); 
	}
	function file_update($filename)
	{
		$id = $_POST['id'];
		$data=array(
            'file'=>$filename,
        );
    	$this->db->where('id', $id);
		$this->db->update('task',$data);
		return true; 
	}
}
?>