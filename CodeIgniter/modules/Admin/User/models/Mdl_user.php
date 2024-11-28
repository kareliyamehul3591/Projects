<?php
class Mdl_user extends CI_Model
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
	function assignment_notifications($id)
	{
		$to_id = $this->session->Admindetail['id'];
		$data=array(
			'online'=> 1,
		);
		$this->db->where('assignment_id',0);
		$this->db->where('from_id', $id);
		$this->db->where('to_role',"Admin");
		$this->db->where('to_id', $to_id);
		$this->db->update('notifications',$data);
		return true;
	}	
	function user()
	{
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function timings_add()
	{
		$dates['user_id'] = $_POST['ids'];
		for ($i = 1; $i < 8; $i++)
		{
			$SU = '0';
			$M = '0';
			$T = '0';
			$W = '0';
			$Th = '0';
			$F = '0';
			$S = '0';
			foreach($_POST['d'.$i] as $date)
			{
				if($date == 'SU')
				{
					$SU = 'SU';
				}else if($date == 'M')
				{
					$M = 'M';
				}else if($date == 'T')
				{
					$T = 'T';
				}else if($date == 'W')
				{
					$W = 'W';
				}else if($date == 'TH')
				{
					$Th = 'TH';
				}else if($date == 'F')
				{
					$F = 'F';
				}else if($date == 'S')
				{
					$S = 'S';
				}
			}
			$dates['date'.$i] = $SU.','.$M.','.$T.','.$W.','.$Th.','.$F.','.$S;
			$dates['time'.$i] = $_POST['timefd'.$i].','.$_POST['timetd'.$i];
			if($_POST['timefd'.$i] == "" || $_POST['timetd'.$i] == "")
			{
				$dates['date'.$i] = "";
				$dates['time'.$i] = "";
			}	
		}
		$dates['created_date'] = date("Y-m-d H:i:s");
		$this->db->insert('available_timings',$dates);
		return true; 
	}
	function timings_edit()
	{
		$id = $_POST['ids'];
		for ($i = 1; $i < 8; $i++)
		{
			$SU = '0';
			$M = '0';
			$T = '0';
			$W = '0';
			$Th = '0';
			$F = '0';
			$S = '0';
			foreach($_POST['d'.$i] as $date)
			{
				if($date == 'SU')
				{
					$SU = 'SU';
				}else if($date == 'M')
				{
					$M = 'M';
				}else if($date == 'T')
				{
					$T = 'T';
				}else if($date == 'W')
				{
					$W = 'W';
				}else if($date == 'TH')
				{
					$Th = 'TH';
				}else if($date == 'F')
				{
					$F = 'F';
				}else if($date == 'S')
				{
					$S = 'S';
				}
			}
			$dates['date'.$i] = $SU.','.$M.','.$T.','.$W.','.$Th.','.$F.','.$S;
			$dates['time'.$i] = $_POST['timefd'.$i].','.$_POST['timetd'.$i];
			if($_POST['timefd'.$i] == "" || $_POST['timetd'.$i] == "")
			{
				$dates['date'.$i] = "";
				$dates['time'.$i] = "";
			}	
		}
		$dates['created_date'] = date("Y-m-d H:i:s");
		$this->db->where('user_id', $id);
		$this->db->update('available_timings',$dates);
		return true; 
	}
	function payment_details_update($data)
    {
		$id = $_POST['ids'];
        
		$this->db->where('user_id', $id);
		$this->db->update('payment_details',$data);
    }
	function user_edit($file)
	{
		$id = $_POST['ids'];
		$data=array(
            'name'=>$_POST['name'],
            'email'=>$_POST['email'],
            'first_name'=>$_POST['first_name'],
            'lastst_name'=>$_POST['lastst_name'],
            'sex'=>$_POST['sex'],
            'skype_id'=>$_POST['skype_id'],
            'freelancer_id'=>$_POST['freelancer_id'],
            'phone_number'=>$_POST['phone_number'],
            'file'=>$file,
			'updated_date'=>date("Y-m-d H:i:s"),
        );
		if($_POST['password'] != null)
		{
			$data['password'] = md5($_POST['password']);
		}
		$data['admin'] = 0;
		$data['manager'] = 0;
		$data['writer'] = 0;
		$data['proof_reader'] = 0;
		$data['help_desk'] = 0;
		foreach($_POST['role'] as $role)
		{
			if($role == 'Admin')
			{
				$data['admin'] = 1;
			}else if($role == 'Manager')
			{
				$data['manager'] = 1;
			}else if($role == 'Writer')
			{
				$data['writer'] = 1;
			}else if($role == 'Proof Reader')
			{
				$data['proof_reader'] = 1;
			}else if($role == 'Help Desk')
			{
				$data['help_desk'] = 1;
			}
		}
    	$this->db->where('id', $id);
		$this->db->update('user',$data);
		return true; 
	}
	function user_remove_file($id,$file)
	{
		$data=array(
            'file'=>$file,
        );
		$this->db->where('id', $id);
		$this->db->update('user',$data);
		return true; 
	}
	function user_delete($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->delete('user');
		return true; 
	}
	function user_detail($id)
	{
		$this->db->where('id', $id);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_admin_get()
	{
		$this->db->where('admin', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_manager_get()
	{
		$this->db->where('manager', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_help_desk_get()
	{
		$this->db->where('help_desk', 1);
		$this->db->where('status', 1);
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function notifications_list($from_id,$to_id,$ststus,$to_role)
    {
        $data=array(
			'assignment_id'=>"0",
			'from_id'=>$from_id,
			'from_role'=>"User",
			'to_id'=>$to_id,
			'to_role'=>$to_role,
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	function available_timings($id)
	{
		$this->db->where('user_id', $id);
		$data=$this->db->get('available_timings');
		return $data->result_array(); 
	}
	function payment_details($id)
	{
		$this->db->where('user_id', $id);
		$data=$this->db->get('payment_details');
		return $data->result_array(); 
	}
	function active_user($id,$status)
	{
		$data=array(
            'status'=>$status,
        );
    	$this->db->where('id', $id);
		$this->db->update('user',$data);
		return true; 
	}
	function active_role($status)
	{
		$id = $_POST['id'];
		$data=array(
            $status=>$_POST['status'],
        );
    	$this->db->where('id', $id);
		$this->db->update('user',$data);
		return true; 
	}
	function email_id_check($id,$email)
	{
    	$this->db->where('email', $email);
		$this->db->where_not_in('id', $id);
		$data=$this->db->get('user');
		return $data->row_array();
	}
	function dropdowns($tabel)
	{
		$this->db->order_by("name","asc");
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
}
?>