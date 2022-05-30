<?php
class Mdl_writer_message extends CI_Model
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
	function message_add()
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'message'=>$_POST['message'],
			'from_id'=>$from_id,
			'to_id'=>$_POST['to_id'],
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('message',$data);
    }
	function message_status($ids)
	{
		$id = $this->session->Admindetail['id'];
		$data=array(
            'status'=>1,
        );
    	$this->db->where('to_id', $id);
    	$this->db->where('from_id', $ids);
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
	function header_notifications_get()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role',"Writer");
		$this->db->order_by("id","desc");
		$this->db->limit(100);
		$data=$this->db->get('notifications');
		return $data->result_array(); 
	}
	function header_notifications_get_all()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role',"Writer");
		$this->db->order_by("id","desc");
		$data=$this->db->get('notifications');
		return $data->result_array(); 
	}
	function assignment($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function assign_to_ma_id($id)
	{
		$this->db->where('writer_id', $this->session->Admindetail['id']);
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_writer');
		return $data->result_array(); 	
	}
}
?>