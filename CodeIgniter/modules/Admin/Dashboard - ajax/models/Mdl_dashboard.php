<?php
class Mdl_dashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();  
		date_default_timezone_set("Asia/Kolkata");		
	}	
	function assignment_date_update($id)
    {
        $data=array(
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	function get_file_extension($filename)
    {
        $parts=explode('.',$filename);
        return $parts[count($parts)-1];
    }
	function assignment()
	{
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('admin_id', $admin_id);
		$this->db->order_by("created_date","desc");
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignments($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignment_get_datas($column,$dir,$value)
	{
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('admin_id', $admin_id);
		if($column != null)
		{
			$this->db->order_by($column,$dir);
		}
		if($value != null)
		{
			$this->db->like('name',$value);
			$this->db->or_like('client_name',$value);
		}
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}	
	function assignment_get_data($column,$dir,$value,$start,$length)
	{
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('admin_id', $admin_id);
		if($column != null)
		{
			$this->db->order_by($column,$dir);
		}
		if($value != null)
		{
			$this->db->like('name',$value);
			$this->db->or_like('client_name',$value);
		}
		$this->db->limit($length, $start);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
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
	function taske_data($id)
	{
		$this->db->where('assignment_id', $id);
		$data=$this->db->get('task');
		return $data->result_array(); 
	}
	function assignment_delete($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->delete('assignment');
		return true; 
	}
	function active_assignment($id,$status)
	{
		$data=array(
            'status'=>$status,
			'created_date'=>date("Y-m-d H:i:s"),
        );
    	$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
	}
	function online_managers()
	{
		$this->db->where('manager', 1);
		$this->db->where('online', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_managers()
	{
		$this->db->where('manager', 1);
		$this->db->where('online', 0);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function assign_to_manager($id)
    {
		$datas=array(
			'assignment_id'=>$_POST['assignment_id'],
			'deadline_date'=>$_POST['deadline_date'],
			'admin'=>$_POST['save_hours'],
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('save_time',$datas);
		
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'manager_id'=>$id,
			'deadline_date'=>$_POST['deadline_date'],
			'assign_hours'=>$_POST['assign_hours'],
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>0,
		);
		$this->db->insert('assign_to_ma',$data);
    }
	function assign_to_manager_list($id,$assignment_id,$deadline_date,$save_hours,$assign_hours)
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
	function re_assign_to_manager($id)
    {
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'manager_id'=>$id,
			'deadline_date'=>$_POST['deadline_date'],
			'assign_hours'=>$_POST['assign_hours'],
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>0,
		);
		$this->db->insert('assign_to_ma',$data);
    }
	function assign_to_ma()
	{
		$data=$this->db->get('assign_to_ma');
		return $data->result_array(); 
	}
	function manager_id($id)
	{
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');
		return $data->result_array(); 
	}
	function proof_reader_id($id)
	{
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_pr');
		return $data->result_array(); 
	}
	function writer_id($id)
	{
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_writer');
		return $data->result_array(); 
	}
	function user_get($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function notifications($id,$ststus)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'from_id'=>$admin_id,
			'to_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	function notifications_list($id,$ststus,$assignment_id)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$assignment_id,
			'from_id'=>$admin_id,
			'to_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	function re_assign_assignment($id,$status)	
	{	
		$data=array(     
			'status'=>$status,   
		);
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_ma',$data);
		return true; 	
	}
	function assignment_final_data($id)
    {	
		$this->db->where('assignment_id', $id);	
		$data=$this->db->get('assignment_final_data');	
		return $data->result_array(); 
    }
	function assignment_image_update($id,$filename)
    {		
		$admin_id = $this->session->Admindetail['id'];
		$this->db->where('assignment_id', $id);   
		$data=array(		
			'file'=>$filename,		
			'admin_id'=>$admin_id,	
			'description'=>$_POST['description'],		
		);	
		$this->db->update('assignment_final_data',$data);  
	}	
}
?>