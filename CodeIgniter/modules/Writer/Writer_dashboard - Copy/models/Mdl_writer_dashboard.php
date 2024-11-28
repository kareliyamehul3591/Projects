<?php
class Mdl_writer_dashboard extends CI_Model
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
	function assign_to_ma()
	{
		$this->db->where('writer_id', $this->session->Admindetail['id']);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_writer');
		return $data->result_array(); 
	}
	function assign_to_ma_list()
	{
		$this->db->where('writer_id', $this->session->Admindetail['id']);
		$this->db->where('status', 0);
		$data=$this->db->get('assign_to_writer');
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
	function assignment($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('assignment');
		return $data->result_array(); 
	}
	function user_get($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function active_assignment($id,$status)
	{
		$writer_id = $this->session->Admindetail['id'];
		$data=array( 
			'status'=>$status, 
		);    	
		$this->db->where('writer_id', $writer_id);
    	$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_writer',$data);
		return true; 	
	}	
	function managet_save_time($id)
    {		
		$this->db->where('assignment_id', $id);
		$data=$this->db->get('save_time');
		return $data->result_array();
	}
	function writer_save_time()
    {		
		$this->db->where('assignment_id', $_POST['assignment_id']);
        $datas=array(
			'manager'=>$_POST['save_hours'],
		);
		$this->db->update('save_time',$datas);
	}	
	function managet_save_time_update($id,$time,$times)    
	{		
		$this->db->where('assignment_id', $id);
        $datas=array(
			'manager'=>$time,
			'writer'=>$times,
		);		
		$this->db->update('save_time',$datas);
	}	
	function assignment_remove($id) 
	{		
		$this->db->where('id', $id);
        $datas=array(
			'status'=>4,
		);		
		$this->db->update('assignment',$datas); 
	}	
	function assignment_manager_remove($id,$status)
	{		
		$data=array( 
			'status'=>$status,
		);    	
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_ma',$data);
		$this->db->where('assignment_id', $id);	
		$this->db->update('assign_to_pr',$data);
		return true; 	
	}	
	function assign_to_writer_updste($id,$date)    
	{		
		$this->db->where('assignment_id', $id); 
		$datas=array(		
			'assign_hours'=>$date,	
		);		
		$this->db->update('assign_to_writer',$datas); 
	}	
	function manager_id_get($id)	
	{		
		$this->db->where('assignment_id', $id);
		$this->db->where('status', 1);	
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');	
		return $data->result_array(); 	
	}	
	function manager_all_id_get($id)	
	{		
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');	
		return $data->result_array(); 	
	}	
	function manager_id_get_list($id)	
	{		
		$this->db->where('assignment_id', $id);
		$this->db->where('status', 0);	
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');	
		return $data->result_array(); 	
	}	
	function pr_id_get($id)	
	{	
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");	
		$data=$this->db->get('assign_to_pr');	
		return $data->result_array(); 	
	}	
	function notifications($id,$ststus,$to_id)    
	{		
		$writer_id = $this->session->Admindetail['id'];
        $data=array(			
			'assignment_id'=>$id,			
			'from_id'=>$writer_id,			
			'to_id'=>$to_id,			
			'created_date'=>date("Y-m-d H:i:s"),			
			'status'=>$ststus,		
		);		
		$this->db->insert('notifications',$data);
	}	
	function assignment_image_add($id,$filename)   
	{		
		$writer_id = $this->session->Admindetail['id'];       
		$data=array(			
			'assignment_id'=>$id,			
			'writer_id'=>$writer_id,			
			'file'=>$filename,
			'description'=>$_POST['description'],			
			'created_date'=>date("Y-m-d H:i:s"),		
		);		
		$this->db->insert('assignment_final_data',$data);   
	}	
	function assignment_image_add_null($id)
	{		
		$writer_id = $this->session->Admindetail['id'];       
		$data=array(			
			'assignment_id'=>$id,			
			'writer_id'=>$writer_id,			
			'file'=>'',
			'description'=>'',			
			'created_date'=>date("Y-m-d H:i:s"),		
		);		
		$this->db->insert('assignment_final_data',$data);   
	}	
	function assignment_image_update($id,$filename) 
	{
		$writer_id = $this->session->Admindetail['id'];	
		$this->db->where('assignment_id', $id);	
		$this->db->where('writer_id', $writer_id); 
		$data=array(			
			'file'=>$filename,		
			'description'=>$_POST['description'],	
		);		
		$this->db->update('assignment_final_data',$data);    
	}	
	function assignment_remove_file($id,$filename) 
	{	
		$writer_id = $this->session->Admindetail['id'];		
		$this->db->where('assignment_id', $id);	
		$this->db->where('writer_id', $writer_id);  
		$data=array(
			'file'=>$filename,		
		);
		$this->db->update('assignment_final_data',$data); 
	}	
	function assignment_final_data($id)   
	{		
		$writer_id = $this->session->Admindetail['id'];		
		$this->db->where('assignment_id', $id);		
		//$this->db->where('writer_id', $writer_id);	
		$data=$this->db->get('assignment_final_data');	
		return $data->result_array();     
	}	
}
?>