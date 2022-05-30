<?php
class Mdl_notifications extends CI_Model
{
	function __construct()
	{
		parent::__construct();     
		date_default_timezone_set("Asia/Kolkata");
	}
	
	function notifications()
	{
		$id = $this->session->Admindetail['id'];
		$this->db->where('to_id', $id);
		$this->db->where('to_role',"Admin");
		$this->db->order_by("id","desc");
		$this->db->limit(100);
		$data=$this->db->get('notifications');
		return $data->result_array(); 
	}
	function user_name($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function assignment($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function notifications_online()
	{
		$id = $this->session->Admindetail['id'];
		$data=array(
			'online'=> 1,
		);
		$this->db->where('to_role',"Admin");
		$this->db->where('to_id', $id);
		$this->db->update('notifications',$data);
		return true;
	}
}
?>