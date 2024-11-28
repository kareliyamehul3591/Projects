<?php
class Mdl_help_desk_dashboard extends CI_Model
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
	function assignment_admin_status($id,$status)
    {
        $data=array(
			'admin_status'=>$status,
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
	
	function assignment_admin_filter()
	{
		$admin_id = $this->session->help_desk_dashboard['users'];
		if($admin_id != 'all')
		{
			$this->db->where('admin_id', $admin_id);
			$this->db->or_where('admin_id',0);
		}
		$this->db->order_by("created_date","desc");
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assign_to_ma_all_filter()
	{
		$admin_id = $this->session->help_desk_dashboard['users'];
		$this->db->select('assignment.*,assign_to_ma.manager_id,assign_to_ma.status as assign_to_ma_status,assign_to_ma.assign_hours,assign_to_ma.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_ma', 'assignment.id = assign_to_ma.assignment_id');
		if($admin_id != 'all')
		{
			$this->db->where('assign_to_ma.manager_id', $admin_id);
		}
		$this->db->order_by("assignment.created_date","desc");
		$this->db->order_by("assign_to_ma.id","desc");
		$data=$this->db->get();
		return $data->result_array(); 
	}
	function assignment_approval_filter()
	{
		$admin_id = $this->session->help_desk_dashboard['users'];
		if($admin_id != 'all')
		{
			$this->db->where('created_id', $admin_id);
		}
		$this->db->where('created_role', 'manager');
		$this->db->where("(`status` = '0' OR `status` = '1' OR `status` = '2')", NULL, FALSE);
		$data=$this->db->get('assignment');
		return $data->result_array();
	}
	
	function assign_to_wr_filter()
	{
		$admin_id = $this->session->help_desk_dashboard['users'];
		$this->db->select('assignment.*,assign_to_writer.writer_id,assign_to_writer.status as assign_to_ma_status,assign_to_writer.assign_hours,assign_to_writer.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_writer', 'assignment.id = assign_to_writer.assignment_id');
		$this->db->order_by("assignment.id","desc");
		$this->db->order_by("assign_to_writer.id","desc");
		if($admin_id != 'all')
		{
			$this->db->where('assign_to_writer.writer_id', $admin_id);
		}
		$data=$this->db->get();
		return $data->result_array(); 
	}
	function assign_to_pr_filter()
	{
		$admin_id = $this->session->help_desk_dashboard['users'];
		$this->db->select('assignment.*,assign_to_pr.proof_reader_id,assign_to_pr.status as assign_to_ma_status,assign_to_pr.assign_hours,assign_to_pr.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_pr', 'assignment.id = assign_to_pr.assignment_id');
		$this->db->order_by("assignment.id","desc");
		$this->db->order_by("assign_to_pr.id","desc");
		if($admin_id != 'all')
		{
			$this->db->where('assign_to_pr.proof_reader_id', $admin_id);
		}
		$data=$this->db->get(); 
		return $data->result_array(); 
	}
	function assign_to_he_filter()
	{
		$this->db->where('created_id', $this->session->help_desk_dashboard['users']);
		$this->db->where('created_role', 'help_desk');
		$this->db->where("(`status` = '0' OR `status` = '1' OR `status` = '2' OR `status` = '7')", NULL, FALSE);
		$data=$this->db->get('assignment');
		return $data->result_array();
	}
	function assignment()
	{
		$this->db->where('created_id', $this->session->Admindetail['id']);
		$this->db->where('created_role', 'help_desk');
		$this->db->where("(`status` = '0' OR `status` = '1' OR `status` = '2' OR `status` = '7')", NULL, FALSE);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assign_to_he_show_filter()
	{
		$this->db->where('created_id', $this->session->help_desk_dashboard['users']);
		$this->db->where('created_role', 'help_desk');
		$this->db->where("(`status` = '4' OR `status` = '5')", NULL, FALSE);
		$data=$this->db->get('assignment');
		return $data->result_array();
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
			'created_role'=>"help_desk",
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
	function duplicate_assignment($data)
    {
		$data['admin_id'] = 0;
		$data['created_id'] = $this->session->Admindetail['id'];
		$data['created_role'] = "help_desk";
		$data['status'] = 0;
		$data['created_date'] = date("Y-m-d H:i:s");
		$this->db->insert('assignment',$data);
		return $this->db->insert_id();
    }
	
	function approval($id)
	{
        $data=array(
			'status'=>0,
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
	}
	function assignment_edit($filename,$status)
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
			'status'=>$status,
		);
		$this->db->where('id', $id);
		$this->db->update('assignment',$data);
		return true; 
    }
	function assignment_delete($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->delete('assignment');
		return true; 
	}
	function assignment_image_add($id,$filename)
    {
		$help_desk_id = $this->session->Admindetail['id'];
		$data=array(		
			'assignment_id'=>$id,		
			'help_desk_file'=>$filename,		
			'help_desk_id'=>$help_desk_id,	
			'description'=>$_POST['description'],		
			'created_date'=>date("Y-m-d H:i:s"),
		);	
		$this->db->insert('assignment_final_data',$data);
	}
	function assignment_image_update($id,$filename)
    {		
		$help_desk_id = $this->session->Admindetail['id'];
		$this->db->where('assignment_id', $id);   
		$data=array(		
			'help_desk_file'=>$filename,		
			'help_desk_id'=>$help_desk_id,	
			'description'=>$_POST['description'],
		);	
		$this->db->update('assignment_final_data',$data);  
	}
	function assignment_remove_file($id,$filename)
    {		
		$help_desk_id = $this->session->Admindetail['id'];
		$this->db->where('assignment_id', $id);   
		$data=array(		
			'help_desk_file'=>$filename,		
			'help_desk_id'=>$help_desk_id,
		);	
		$this->db->update('assignment_final_data',$data);  
	}
	function logs_insert($id,$to_id,$reason,$status,$to_role)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Help Desk",
			'to_id'=>$to_id,
			'to_role'=>$to_role,
			'status'=>$status,
			'description'=>$reason,
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
			'from_role'=>"Help Desk",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function all_admin_get()
	{
		$this->db->where('admin', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function notifications($id,$ststus,$admin_id,$to_role)
    {
		$manager_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'from_role'=>"Help Desk",
			'to_id'=>$admin_id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
		
    }
	function notifications_list($id,$ststus,$to_role)
    {
		$admin_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'from_id'=>$admin_id,
			'from_role'=>"Help Desk",
			'to_id'=>$id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	
	function get_role_user($role)
	{
    	$this->db->where($role, 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
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
	function user_get($id)
	{
		$this->db->where('id', $id);
		$this->db->order_by("name","asc");
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
	function assignment_notifications($id)
	{
		$to_id = $this->session->Admindetail['id'];
		$data=array(
			'online'=> 1,
		);
		$this->db->where('assignment_id',$id);
		$this->db->where('to_role',"Help Desk");
		$this->db->where('to_id', $to_id);
		$this->db->update('notifications',$data);
		return true;
	}	
	function assignment_all()
	{
		$this->db->order_by("created_date","desc");
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assignment_final_data($id)
    {	
		$this->db->where('assignment_id', $id);	
		$data=$this->db->get('assignment_final_data');	
		return $data->result_array(); 
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
	function re_assign_assignment_remove($id)	
	{
		$this->db->where('assignment_id', $id);
		$data = $this->db->delete('assign_to_ma');
		return true;
	}
	function re_assign_assignment_remove_w($id)	
	{	
		$this->db->where('assignment_id', $id);
		$data = $this->db->delete('assign_to_writer');
		return true;
	}
	function re_assign_assignment_remove_p($id)	
	{	
		$this->db->where('assignment_id', $id);
		$data = $this->db->delete('assign_to_pr');
		return true;
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
	function assignment_re_assign_file($id,$role,$rlo_id)
    {
		$this->db->where('assignment_id', $id);   
		if($role == 'Manager')
		{
			$data=array(		
				'manager_id'=>$rlo_id,
			);	
		}else if($role == 'Writer')
		{
			$data=array(		
				'writer_id'=>$rlo_id,
			);	
		}else if($role == 'Proof Reader')
		{
			$data=array(		
				'proof_reader_id'=>$rlo_id,
			);	
		}
		$this->db->update('assignment_final_data',$data);  
	}
	function manager_complet($id,$status)	
	{	
		$data=array(     
			'status'=>$status,   
		);
		$this->db->where('id', $id);
		$this->db->update('assign_to_ma',$data);
		return true; 	
	}
	function proof_reader_complet($id,$status)	
	{	
		$data=array(     
			'status'=>$status,   
		);
		$this->db->where('id', $id);
		$this->db->update('assign_to_pr',$data);
		return true; 	
	}
	function writer_complet($id,$status)	
	{	
		$data=array(     
			'status'=>$status,   
		);
		$this->db->where('id', $id);
		$this->db->update('assign_to_writer',$data);
		return true; 	
	}
}
?>