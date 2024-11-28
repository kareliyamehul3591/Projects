<?php
class Mdl_settings extends CI_Model
{
	function __construct()
	{
		parent::__construct();   
		date_default_timezone_set("Asia/Kolkata");
	}
	function get($tabel)
	{
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
	function add_table()
    {
		$data=array(
			'name'=>$_POST['name'],
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>1,
		);
		$this->db->insert($_POST['table'],$data);
    }
	function deletes()
	{
		$this->db->where('id', $_POST['id']);
		$data = $this->db->delete($_POST['table']);
		return true; 
	}
	function edit_table()
	{
		$data=array(
            'name'=>$_POST['name'],
        );
    	$this->db->where('id', $_POST['id']);
		$this->db->update($_POST['table'],$data);
		return true; 
	}
	
	function help_add($tab,$role,$page,$name)
    {
		$data=array(
			'tab'=>$tab,
			'role'=>$role,
			'page'=>$page,
			'name'=>$name,
		);
		$this->db->insert('help',$data);
    }
	
	function help_update($tab,$role,$page,$name)
    {
		$data=array(
			'name'=>$name,
		);
		$this->db->where('tab', $tab);
		$this->db->where('role', $role);
		$this->db->where('page', $page);
		$this->db->update('help',$data);
    }
	function help_detaile($tab)
	{
		$values = explode("_",$tab);
		$this->db->where('tab', $values[0]);
		$this->db->where('role', $values[1]);
		if($values[2] != null)
		{
			$this->db->where('page', $values[2]);
		}
		$data=$this->db->get('help');
		return $data->result_array(); 
	}
}
?>