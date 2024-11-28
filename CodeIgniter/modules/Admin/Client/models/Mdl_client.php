<?php
class Mdl_client extends CI_Model
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
	function client()
	{
		$data=$this->db->get('client');
		return $data->result_array(); 
	}
	
	function payment_details_update($data)
    {
		$id = $_POST['ids'];
        
		$this->db->where('client_id', $id);
		$this->db->update('payment_details',$data);
    }
	
	function payment_details_insert($id)
    {
        $data=array(
            'client_id'=>$id,
		);
		$this->db->insert('payment_details',$data);
    }
	function client_add()
	{
		 $data=array(
            'email'=>$_POST['email'],
            'first_name'=>$_POST['first_name'],
            'lastst_name'=>$_POST['lastst_name'],
            'sex'=>$_POST['sex'],
            'skype_id'=>$_POST['skype_id'],
            'freelancer_id'=>$_POST['freelancer_id'],
            'phone_number'=>$_POST['phone_number'],
            'company_name'=>$_POST['company_name'],
            'company_location'=>$_POST['company_location'],
            'company_country'=>$_POST['company_country'],
			'status'=>1,
			'updated_date'=>date("Y-m-d H:i:s"),
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('client',$data);
		$last_id = $this->db->insert_id();
		return $last_id; 
	}
	function client_detail($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('client');
		return $data->result_array(); 
	}
	function client_edit()
	{
		$id = $_POST['ids'];
		$data=array(
            'email'=>$_POST['email'],
            'first_name'=>$_POST['first_name'],
            'lastst_name'=>$_POST['lastst_name'],
            'sex'=>$_POST['sex'],
            'skype_id'=>$_POST['skype_id'],
            'freelancer_id'=>$_POST['freelancer_id'],
            'phone_number'=>$_POST['phone_number'],
			'company_name'=>$_POST['company_name'],
            'company_location'=>$_POST['company_location'],
            'company_country'=>$_POST['company_country'],
			'updated_date'=>date("Y-m-d H:i:s"),
        );
    	$this->db->where('id', $id);
		$this->db->update('client',$data);
		return true; 
	}
	function active_client($id,$status)
	{
		$data=array(
            'status'=>$status,
			'updated_date'=>date("Y-m-d H:i:s"),
        );
    	$this->db->where('id', $id);
		$this->db->update('client',$data);
		return true; 
	}
	function payment_details($id)
	{
		$this->db->where('client_id', $id);
		$data=$this->db->get('payment_details');
		return $data->result_array(); 
	}
	function assignment($id)
	{
		$this->db->where('client_name', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function dropdowns($tabel)
	{
		$this->db->order_by("name","asc");
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
}
?>