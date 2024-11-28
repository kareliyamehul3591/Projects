<?php
class Mdl_manager_dashboard extends CI_Model
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
		
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_ma',$data);
		
		return true; 
    }
	function get_file_extension($filename)    
	{    
		$parts=explode('.',$filename);
        return $parts[count($parts)-1];    
	}
	function assign_to_ma_all()
	{
		$this->db->select('assignment.*,assign_to_ma.manager_id,assign_to_ma.status as assign_to_ma_status,assign_to_ma.assign_hours,assign_to_ma.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_ma', 'assignment.id = assign_to_ma.assignment_id');
		$this->db->where('assign_to_ma.manager_id', $this->session->Admindetail['id']);		
		$this->db->order_by("assignment.created_date","desc");
		$this->db->order_by("assign_to_ma.id","desc");
		$data=$this->db->get();
		return $data->result_array(); 
	}
	function assignment_get_datas($column,$dir,$value)
	{
		$this->db->select('assignment.*,assign_to_ma.manager_id,assign_to_ma.status as assign_to_ma_status,assign_to_ma.assign_hours,assign_to_ma.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_ma', 'assignment.id = assign_to_ma.assignment_id');		
		$this->db->where('assign_to_ma.manager_id', $this->session->Admindetail['id']);
		
		if($column != null)
		{
			$this->db->order_by($column,$dir);
		}
		$this->db->order_by("assign_to_ma.id","desc");
		if($value != null)
		{
			$this->db->like('assignment.name',$value);
			$this->db->or_like('assignment.client_name',$value);
		}
		$data=$this->db->get();
		$assignment_get = $data->result_array();

		$i = 0;
		foreach($assignment_get as $val) {
			if($val['assign_to_ma_status'] != 2)
			{
				if (!in_array($val['id'], $key_array)) {
					$key_array[$i] = $val['id'];
					$assignment_data[$i] = $val;
					$i++;
				}
			}
		}
		return $assignment_data;
	}
	function assignment_get_data($column,$dir,$value,$start,$length)
	{
		$this->db->select('assignment.*,assign_to_ma.manager_id,assign_to_ma.status as assign_to_ma_status,assign_to_ma.assign_hours,assign_to_ma.created_date as assign_created_date');
		$this->db->from('assignment');
		$this->db->join('assign_to_ma', 'assignment.id = assign_to_ma.assignment_id');
		$this->db->where('assign_to_ma.manager_id', $this->session->Admindetail['id']);
		
		if($column != null)
		{
			$this->db->order_by($column,$dir);
		}
		$this->db->order_by("assign_to_ma.id","desc");
		if($value != null)
		{
			$this->db->like('assignment.name',$value);
			$this->db->or_like('assignment.client_name',$value);
		}
		$data=$this->db->get();
		$assignment_get = $data->result_array();
		$i = 0;
		foreach($assignment_get as $val) {
			if($val['assign_to_ma_status'] != 2)
			{
				if (!in_array($val['id'], $key_array)) {
					$key_array[$i] = $val['id'];
					$assignment_data[$i] = $val;
					$i++;
				}
			}
		}
		$rss_feeds = array_splice($assignment_data,$start,$length,true);
		return $rss_feeds;
	}
	function assign_to_ma()
	{
		$this->db->where('manager_id', $this->session->Admindetail['id']);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');
		return $data->result_array(); 
	}
	function assign_to_ma_list()
	{
		$this->db->where('manager_id', $this->session->Admindetail['id']);		
		$this->db->where('status', 0);
		$data = $this->db->get('assign_to_ma');
		return $data->result_array(); 
	}
	function manager_id($id)
	{
		$this->db->where('assignment_id', $id);
		$this->db->order_by("id","desc");
		$data=$this->db->get('assign_to_ma');
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
	function online_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_writer()
	{
		$this->db->where('writer', 1);
		$this->db->where('online', 0);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function online_proof_reader()
	{
		$this->db->where('proof_reader', 1);
		$this->db->where('online', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function others_proof_reader()
	{
		$this->db->where('proof_reader', 1);
		$this->db->where('online', 0);
		$data=$this->db->get('user');
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
	function assign_to_writer_list($id,$assignment_id,$deadline_date,$assign_hours_writer)
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
	function assign_to_proof_reader($id)
    {
        $data=array(
			'assignment_id'=>$_POST['assignment_id'],
			'proof_reader_id'=>$id,
			'created_date'=>date("Y-m-d H:i:s"),
			'deadline_date'=>$_POST['deadline_date'],
			'assign_hours'=>$_POST['assign_hours_proof_reader'],
			'status'=>0,
		);
		$this->db->insert('assign_to_pr',$data);
    }
	function assign_to_proof_reader_list($id,$assignment_id,$deadline_date,$assign_hours_proof_reader)
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
	function managet_save_time()
    {
		$this->db->where('assignment_id', $_POST['assignment_id']);
        $datas=array(
			'manager'=>$_POST['save_hours'],
		);
		$this->db->update('save_time',$datas);
    }
	function managet_save_time_list($assignment_id,$save_hours)
    {
		$this->db->where('assignment_id', $assignment_id);
        $datas=array(
			'manager'=>$save_hours,
		);
		$this->db->update('save_time',$datas);
    }
	function assign_to_pr()
    {
        $data=$this->db->get('assign_to_pr');
		$this->db->order_by("id","desc");
		return $data->result_array();
    }
	function assign_to_writers()
    {
        $data=$this->db->get('assign_to_writer');
		$this->db->order_by("id","desc");
		return $data->result_array();
    }
	function active_assignment($id,$status)
	{
		$ma_id = $this->session->Admindetail['id'];
		$data=array(
            'status'=>$status,
        );
    	$this->db->where('manager_id', $ma_id);
    	$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_ma',$data);
		return true; 
	}
	function notifications($id,$ststus,$admin_id)
    {
		$manager_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'to_id'=>$admin_id,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
		
    }
	function notifications_wr_pr($id,$ststus,$writer_id,$proof_reader_id)
    {
		$manager_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'created_date'=>date("Y-m-d H:i:s"),
			'to_id'=>$writer_id,
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
		
		$datas=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'created_date'=>date("Y-m-d H:i:s"),
			'to_id'=>$proof_reader_id,
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$datas);
    }
	function notifications_all($id,$ststus,$writer_id)
    {
		$manager_id = $this->session->Admindetail['id'];
        $data=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'created_date'=>date("Y-m-d H:i:s"),
			'to_id'=>$writer_id,
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
	function assignment_image_update($id,$filename)
    {		
		$manager_id = $this->session->Admindetail['id'];
		$this->db->where('assignment_id', $id);   
		$data=array(		
			'file'=>$filename,		
			'manager_id'=>$manager_id,	
			'description'=>$_POST['description'],		
		);	
		$this->db->update('assignment_final_data',$data);  
	}
	function re_assign_assignment($id,$status)	
	{	
		$data=array(     
			'status'=>$status,   
		);
		$this->db->where('assignment_id', $id);
		$this->db->update('assign_to_pr',$data);
		return true; 	
	}
	/*function notifications_pr($id,$ststus,$proof_reader_id)
    {
		$manager_id = $this->session->Admindetail['id'];
        $datas=array(
			'assignment_id'=>$id,
			'from_id'=>$manager_id,
			'created_date'=>date("Y-m-d H:i:s"),
			'to_id'=>$proof_reader_id,
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$datas);
    }*/
}
?>