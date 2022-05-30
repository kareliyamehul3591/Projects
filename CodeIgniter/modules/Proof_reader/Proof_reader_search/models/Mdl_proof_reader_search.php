<?php
class Mdl_proof_reader_search extends CI_Model
{
	function __construct()
	{
		parent::__construct();   
		date_default_timezone_set("Asia/Kolkata");
	}
	function users($name)
	{
		$this->db->like('name', $name);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function assignments($user,$name)
	{
		if($user == 'assignment_id')
		{
			$this->db->where('id', $name);
		}else if($user == 'assignment_name')
		{
			$this->db->like('name', $name);
		}
		$data=$this->db->get('assignment');
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
		$proof_reader_id = $this->session->Admindetail['id'];
		$this->db->where('proof_reader_id', $proof_reader_id);
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
}
?>