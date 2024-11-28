<?php
class Mdl_message extends CI_Model
{
	function __construct()
	{
		parent::__construct();  
		date_default_timezone_set("Asia/Kolkata");
	}
	function user()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where_not_in('id', $id);
		$this->db->order_by('name', 'ASC');
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function user_id($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}

	function message($to_id,$to_role,$from_role,$assignment_id)
	{
		$id = $this->session->Admindetail['id'];
		$query = 'select * from message where (from_id = '.$to_id.' and from_role = "'.$to_role.'" and to_id = '.$id.' and to_role = "'.$from_role.'" and assignment_id = '.$assignment_id.') or (from_id = '.$id.' and from_role = "'.$from_role.'" and to_id = '.$to_id.' and to_role = "'.$to_role.'" and assignment_id = '.$assignment_id.') ORDER BY id ASC';
		$data=$this->db->query($query);
		return $data->result_array(); 
	}
	function message_remove($to_id,$to_role,$from_role,$assignment_id)
	{
		$id = $this->session->Admindetail['id'];
		$query = 'DELETE from message where (from_id = '.$to_id.' and from_role = "'.$to_role.'" and to_id = '.$id.' and to_role = "'.$from_role.'" and assignment_id = '.$assignment_id.') or (from_id = '.$id.' and from_role = "'.$from_role.'" and to_id = '.$to_id.' and to_role = "'.$to_role.'" and assignment_id = '.$assignment_id.')';
		$data=$this->db->query($query);
		return true; 
	}
	
	function deletes($to_id)
	{
		$id = $this->session->Admindetail['id'];
		$query = 'DELETE from message where (from_id = '.$to_id.' and to_id = '.$id.') or (from_id = '.$id.' and to_id = '.$to_id.')';
		$data=$this->db->query($query);
		return true; 
	}
	
	
	function message_add()
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'message'=>$_POST['message'],
			'from_id'=>$from_id,
			'from_role'=>$_POST['from_role'],
			'to_id'=>$_POST['to_id'],
			'to_role'=>$_POST['to_role'],
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('message',$data);
		return true;
    }
	function message_status($to_id,$to_role,$from_role,$assignment_id)
	{
		$id = $this->session->Admindetail['id'];
		$data=array(
            'status'=>1,
        );
    	$this->db->where('to_id', $id);
    	$this->db->where('to_role', $from_role);
    	$this->db->where('from_id', $to_id);
    	$this->db->where('from_role', $to_role);
    	$this->db->where('assignment_id', $assignment_id);
		
		$this->db->update('message',$data);
		return true; 
	}
	function header_message_get($to_role)
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role', $to_role);
		$this->db->order_by('id','desc');
		$data=$this->db->get('message');
		return $data->result_array(); 
	}
	function header_notifications_get()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role',"Admin");
		$this->db->order_by("id","desc");
		$this->db->limit(100);
		$data=$this->db->get('notifications');
		return $data->result_array(); 
	}
	function header_notifications_get_all()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role',"Admin");
		$this->db->order_by("id","desc");
		$data=$this->db->get('notifications');
		return $data->result_array(); 
	}
	function time_to_hours($time)
	{
		$time = time() - $time;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		foreach ($tokens as $unit => $text) 
		{
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}
	}
	function assignment($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
}
?>