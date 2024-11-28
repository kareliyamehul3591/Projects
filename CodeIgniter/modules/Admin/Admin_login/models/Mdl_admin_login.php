<?php 
class Mdl_admin_login extends CI_Model
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
	function admin_login_check()
	{
		$email = $_POST['email'];
		$password = md5($_POST['password']);
		$this->db->where('password',$password);
		$this->db->where('status',1);
    	
		if($_POST['role'] == 'Super Admin')
		{
			$this->db->where('super_admin',1);
		}else if($_POST['role'] == 'Admin')
		{
			$this->db->where('admin',1);
		}else if($_POST['role'] == 'Manager')
		{
			$this->db->where('manager',1);
		}else if($_POST['role'] == 'Writer')
		{
			$this->db->where('writer',1);
		}else if($_POST['role'] == 'Proof Reader')
		{
			$this->db->where('proof_reader',1);
		}else if($_POST['role'] == 'Help Desk')
		{
			$this->db->where('help_desk',1);
		}
		
		$this->db->where("(`email` = '".$email."' OR `name` = '".$email."')", NULL, FALSE);	
		
		$data=$this->db->get('user');		
		return $data->row_array(); 
	}
	function all_user_data()
	{
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function email_id_check()
	{
		$email = $_POST['email'];
    	$this->db->where('email', $email);
		$data=$this->db->get('user');
		return $data->row_array(); 
	}
	function name_check()
	{
		$name = $_POST['name'];
    	$this->db->where('name', $name);
		$data=$this->db->get('user');
		return $data->row_array(); 
	}
	function loginchake($email)
	{
    	$this->db->where('email', $email);
		$data=$this->db->get('user');
		return $data->row_array(); 
	}
	function admin_login_online($id,$online)
	{
    	$this->db->where('id', $id);
		$data=array(
            'online'=>$online,
			'updated_date'=>date("Y-m-d H:i:s"),
        );
		$this->db->update('user',$data);
		return true; 
	}
	
	function forgot_password_check()
	{
		$email = $_POST['email'];
		
    	$this->db->where('email', $email);
		$data=$this->db->get('user');
		return $data->row_array(); 
	}
	function filetemp_empty()
	{
		$this->db->from('filetemp'); 
		$this->db->truncate(); 
	}
	function filetemp_update($id,$filename)
	{
		$this->db->where('id', $id);
		$data=array(
            'file'=>$filename,
        );
		$this->db->update('filetemp',$data);
	}
	function filetemp_get()
	{
		$this->db->where('id', 1);
		$data=$this->db->get('filetemp');
		return $data->row_array(); 
	}
	function filetemp_insert($filename)
	{
		$data=array(
            'file'=>$filename,
        );
		$this->db->insert('filetemp',$data);
		return $this->db->insert_id();
	}
	function registration($filename)
	{
		$data=array(
            'name'=>$_POST['name'],
            'email'=>$_POST['email'],
            'password'=>md5($_POST['password']),
            'first_name'=>$_POST['first_name'],
            'lastst_name'=>$_POST['lastst_name'],
            'sex'=>$_POST['sex'],
            'skype_id'=>$_POST['skype_id'],
            'freelancer_id'=>$_POST['freelancer_id'],
            'phone_number'=>$_POST['phone_number'],
            //'company_name'=>$_POST['company_name'],
            //'company_location'=>$_POST['company_location'],
            //'company_country'=>$_POST['company_country'],
            'file'=>$filename,
            'status'=>0,
			'created_date'=>date("Y-m-d H:i:s"),
			'updated_date'=>date("Y-m-d H:i:s"),
        );
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
		$this->db->insert('user',$data);
		$last_id = $this->db->insert_id();
		$dataa=array(
			'user_id'=>$last_id,
			'payment_type'=>$_POST['payment_type'],
			'payment_method'=>$_POST['payment_method'],
			'profile_url'=>$_POST['profile_url'],
			'paypal_user_id'=>$_POST['paypal_user_id'],
			'contact_no'=>$_POST['contact_no'],
			'bank_name'=>$_POST['bank_name'],
			'account_no'=>$_POST['account_no'],
			'ifsc_code'=>$_POST['ifsc_code'],
			'created_date'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('payment_details',$dataa);		
		$dates['user_id'] = $last_id;
		
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
		return $last_id;
	}
	function set_password($id,$password)
	{
    	$this->db->where('id', $id);
		$data=array(
            'password'=>$password,
        );
		$this->db->update('user',$data);
		return true; 
	}
	function get_role_user($role)
	{
    	$this->db->where($role, 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function get_user_id($id)
	{
    	$this->db->where('id', $id);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function all_admin_get()
	{
		$this->db->where('admin', 1);
		$this->db->where('status', 1);
		$this->db->order_by("name","asc");
		$data=$this->db->get('user');
		return $data->result_array(); 
	}
	function notifications_list($user_id,$admin_id,$ststus)
    {
        $data=array(
			'assignment_id'=>"0",
			'from_id'=>$user_id,
			'from_role'=>"User",
			'to_id'=>$admin_id,
			'to_role'=>'Admin',
			'created_date'=>date("Y-m-d H:i:s"),
			'status'=>$ststus,
		);
		$this->db->insert('notifications',$data);
    }
	function dropdowns($tabel)
	{
		$this->db->order_by("name","asc");
		$data=$this->db->get($tabel);
		return $data->result_array(); 
	}
}
?>