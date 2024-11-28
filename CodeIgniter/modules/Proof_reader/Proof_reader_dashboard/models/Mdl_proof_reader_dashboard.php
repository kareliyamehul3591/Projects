<?php
class Mdl_proof_reader_dashboard extends CI_Model{
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
	function qc_failede($id)
    {
		$this->db->where('id', $id);
		$datas = array(			
			'status'=>7,	
		);
		$this->db->update('assignment',$datas);
		return true; 
    }
	function get_file_extension($filename)    
	{    
		$parts=explode('.',$filename);
        return $parts[count($parts)-1];    
	}
	function online_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 0);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}	
	function assign_to_writer($id)
    {
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'writer_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'deadline_date'=>$_POST['deadline_date'],
			'assign_hours'=>$_POST['assign_hours_writer'],
			'status'=>0,
		);
		$this->db->insert('assign_to_writer',$data);
    }
	function assign_to_ma_all()
	{
		$this->db->select('assignment.*,assign_to_pr.proof_reader_id,assign_to_pr.status as assign_to_ma_status,assign_to_pr.assign_hours,assign_to_pr.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_pr', 'assignment.id = assign_to_pr.assignment_id');
		$this->db->order_by("assignment.id","desc");
		$this->db->order_by("assign_to_pr.id","desc");
		$this->db->where('assign_to_pr.proof_reader_id', $this->session->Admindetail['id']);
		$data=$this->db->get(); 
		return $data->result_array(); 
	}
	function assign_to_ma()
	{		
		$this->db->where('proof_reader_id', $this->session->Admindetail['id']);	
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_pr');	
		return $data->result_array(); 
	}
	function assign_to_ma_list()
	{		
		$this->db->where('proof_reader_id', $this->session->Admindetail['id']);	
		$this->db->where('status', 0);
		$data=$this->db->get('assign_to_pr');	
		return $data->result_array(); 
	}	
	function assign_to_ma_id($id)
	{
		$this->db->where('proof_reader_id', $this->session->Admindetail['id']);
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_pr');
		return $data->result_array(); 	
	}
	function proof_reader_id($id)
	{
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_pr');
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
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function manager_all_id_get($id)	
	{		
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");	
		$data=$this->db->get('assign_to_ma');	
		return $data->result_array(); 	
	}	
	function writer_detaile($id)
	{		
		$this->db->where('assignment_id', $id);	
		$this->db->order_by("id","desc");	
		$data=$this->db->get('assign_to_writer');
		return $data->result_array(); 
	}
	function active_assignment($id,$status)	
	{
		$proof_reader_id = $this->session->Admindetail['id'];	
		$data=array(     
			'status'=>$status,      
		);    	
		$this->db->where('proof_reader_id', $proof_reader_id); 
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_pr',$data);
		return true; 	
	}	
	function managet_save_time($id) 
	{	
		$this->db->where('assignment_id', $id);	
		$data=$this->db->get('save_time');
		return $data->result_array(); 
	}	
	function managet_save_time_update($id,$time,$times)  
	{	
		$this->db->where('assignment_id', $id); 
		$datas=array(		
			'manager'=>$time,
			'proof_reader'=>$times,	
		);		
		$this->db->update('save_time',$datas);  
	}
	function assignment_proof_reader_status($id,$status)
	{
		$this->db->where('assignment_id', $id);   
		$datas=array(		
			'proof_reader_status'=>$status,	
		);		
		$this->db->update('assign_to_pr',$datas);
	}
	function assign_to_proof_reader_updste($id,$date)
    {		
		$this->db->where('assignment_id', $id);   
		$datas=array(		
			'assign_hours'=>$date,	
		);		
		$this->db->update('assign_to_pr',$datas);    
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
		$this->db->update('assign_to_writer',$data);
		return true; 	
	}	
	function manager_id_get($id)	
	{	
		$this->db->where('assignment_id', $id);	
		$this->db->where('status', 1);
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
	function notifications($id,$ststus,$to_id,$to_role)
	{	
		$from_id = $this->session->Admindetail['id']; 
		$data=array(
			'assignment_id'=>$id,	
			'from_id'=>$from_id,
			'from_role'=>"Proof Reader",
			'to_id'=>$to_id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,	
		);		
		$this->db->insert('notifications',$data);  
	}	
	function assignment_final_data($id)
    {	
		$this->db->where('assignment_id', $id);	
		$data=$this->db->get('assignment_final_data');	
		return $data->result_array(); 
    }	
	function assignment_image_add($id,$filename)   
	{		
		$proof_reader_id = $this->session->Admindetail['id'];       
		$data=array(			
			'assignment_id'=>$id,			
			'proof_reader_id'=>$proof_reader_id,			
			'proof_reader_file'=>$filename,
			'description'=>$_POST['description'],			
			'created_date'=>date("Y-m-d H:i:s"),		
		);		
		$this->db->insert('assignment_final_data',$data);   
	}	
	function assignment_image_update($id,$filename)
    {		
		$proof_reader_id = $this->session->Admindetail['id'];
		$this->db->where('assignment_id', $id);   
		$data=array(		
			'proof_reader_file'=>$filename,		
			'proof_reader_id'=>$proof_reader_id,	
			'description'=>$_POST['description'],		
		);	
		$this->db->update('assignment_final_data',$data);  
	}	
	function assignment_remove_file($id,$filename)  
	{	
		$proof_reader_id = $this->session->Admindetail['id'];
		
		$this->db->where('assignment_id', $id);     
		$this->db->where('proof_reader_id', $proof_reader_id);     
		$data=array(	
			'proof_reader_file'=>$filename,	
		);		
		$this->db->update('assignment_final_data',$data);   
	}
	function re_assign_assignment($id,$status,$count)	
	{	
		$data=array(     
			'status'=>$status,   
			're_assign_count'=>$count,   
		);
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_writer',$data);
		return true; 	
	}
	function logs_insert_c($id,$status,$reason)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Proof Reader",
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }	
	function logs_insert($id,$to_id,$reason,$status,$to_role)
    {
		$from_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$from_id,
			'from_role'=>"Proof Reader",
			'to_id'=>$to_id,
			'to_role'=>$to_role,
			'status'=>$status,
			'description'=>$reason,
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('logs',$data);
    }
	function assignment_notifications($id)
	{
		$to_id = $this->session->Admindetail['id'];
		$data=array(
			'online'=> 1,
		);
		$this->db->where('assignment_id',$id);
		$this->db->where('to_role',"Proof Reader");
		$this->db->where('to_id', $to_id);
		$this->db->update('notifications',$data);
		return true;
	}
	function dropdowns($tabel)
	{
		$this->db->order_by("name","asc");
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
} ?>