<?php
class Mdl_help extends CI_Model
{
	function __construct()
	{
		parent::__construct();   
		date_default_timezone_set("Asia/Kolkata");
	}
	function help_detaile($tab,$role,$page)
	{
		$this->db->where('tab', $tab);
		$this->db->where('role', $role);
		if($page != null)
		{
			$this->db->where('page', $page);
		}
		$data=$this->db->get('help');
		return $data->result_array(); 
	}
}
?>