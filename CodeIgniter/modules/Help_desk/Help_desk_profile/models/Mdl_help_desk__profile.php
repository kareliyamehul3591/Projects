<?phpclass Mdl_help_desk__profile extends CI_Model{	function __construct()	{		parent::__construct(); 		date_default_timezone_set("Asia/Kolkata");	}	function get_file_extension($filename)    {        $parts=explode('.',$filename);        return $parts[count($parts)-1];    }	function available_timings()	{		$id = $this->session->Admindetail['id'];		$this->db->where('user_id', $id);		$data=$this->db->get('available_timings');		return $data->result_array(); 	}	function timings_add()	{		$dates['user_id'] = $this->session->Admindetail['id'];		for ($i = 1; $i < 8; $i++)		{			$SU = '0';			$M = '0';			$T = '0';			$W = '0';			$Th = '0';			$F = '0';			$S = '0';			foreach($_POST['d'.$i] as $date)			{				if($date == 'SU')				{					$SU = 'SU';				}else if($date == 'M')				{					$M = 'M';				}else if($date == 'T')				{					$T = 'T';				}else if($date == 'W')				{					$W = 'W';				}else if($date == 'TH') 				{					$Th = 'TH';				}else if($date == 'F')				{					$F = 'F';				}else if($date == 'S')				{					$S = 'S';				}			}			$dates['date'.$i] = $SU.','.$M.','.$T.','.$W.','.$Th.','.$F.','.$S;			$dates['time'.$i] = $_POST['timefd'.$i].','.$_POST['timetd'.$i];			if($_POST['timefd'.$i] == "" || $_POST['timetd'.$i] == "")			{				$dates['date'.$i] = "";				$dates['time'.$i] = "";			}			}		$dates['created_date'] = date("Y-m-d H:i:s");		$this->db->insert('available_timings',$dates);		return true; 	}	function timings_edit()	{		$id = $this->session->Admindetail['id'];		for ($i = 1; $i < 8; $i++)		{			$SU = '0';			$M = '0';			$T = '0';			$W = '0';			$Th = '0';			$F = '0';			$S = '0';			foreach($_POST['d'.$i] as $date)			{				if($date == 'SU')				{					$SU = 'SU';				}else if($date == 'M')				{					$M = 'M';				}else if($date == 'T')				{					$T = 'T';				}else if($date == 'W')				{					$W = 'W';				}else if($date == 'TH')				{					$Th = 'TH';				}else if($date == 'F')				{					$F = 'F';				}else if($date == 'S')				{					$S = 'S';				}			}			$dates['date'.$i] = $SU.','.$M.','.$T.','.$W.','.$Th.','.$F.','.$S;			$dates['time'.$i] = $_POST['timefd'.$i].','.$_POST['timetd'.$i];			if($_POST['timefd'.$i] == "" || $_POST['timetd'.$i] == "")			{				$dates['date'.$i] = "";				$dates['time'.$i] = "";			}			}		$dates['created_date'] = date("Y-m-d H:i:s");		$this->db->where('user_id', $id);		$this->db->update('available_timings',$dates);		return true; 	}	function personal_details()	{		$id = $this->session->Admindetail['id'];		$this->db->where('id', $id);		$data=$this->db->get('user');		return $data->result_array(); 	}	function update_personal_details($file)	{		$id = $this->session->Admindetail['id'];		$data=array(            'name'=>$_POST['name'],            'email'=>$_POST['email'],            'first_name'=>$_POST['first_name'],            'lastst_name'=>$_POST['lastst_name'],            'sex'=>$_POST['sex'],            'skype_id'=>$_POST['skype_id'],            'freelancer_id'=>$_POST['freelancer_id'],			'file'=>$file,             'phone_number'=>$_POST['phone_number'],        );		if($_POST['password'] != null)		{			$data['password'] = md5($_POST['password']);		}    	$this->db->where('id', $id);		$this->db->update('user',$data);		return true; 	}	function work_experience_get()	{		$id = $this->session->Admindetail['id'];		$this->db->where('user_id', $id);		$data=$this->db->get('work_experience');		return $data->result_array(); 	}	function work_experience()    {		$id = $this->session->Admindetail['id'];        $data=array(			'user_id'=>$id,			'qualification'=>$_POST['qualification'],			'expertise'=>$_POST['expertise'],			'experiences'=>$_POST['experiences'],			'currently_working'=>$_POST['currently_working'],			'stat_time'=>date('H:i', strtotime($_POST['stat_time'])),			'end_time'=>date('H:i', strtotime($_POST['end_time'])),			'created_date'=>date("Y-m-d h:i:s"),		);		$this->db->insert('work_experience',$data);    }	function work_experience_update()    {		$id = $this->session->Admindetail['id'];        $data=array(			'qualification'=>$_POST['qualification'],			'expertise'=>$_POST['expertise'],			'experiences'=>$_POST['experiences'],			'currently_working'=>$_POST['currently_working'],			'stat_time'=>date('H:i', strtotime($_POST['stat_time'])),			'end_time'=>date('H:i', strtotime($_POST['end_time'])),		);		$this->db->where('user_id', $id);		$this->db->update('work_experience',$data);    }	function payment_details_get()	{		$id = $this->session->Admindetail['id'];		$this->db->where('user_id', $id);		$data=$this->db->get('payment_details');		return $data->result_array(); 	}	function payment_details()    {		$id = $this->session->Admindetail['id'];        $data=array(			'user_id'=>$id,			'payment_type'=>$_POST['payment_type'],			'payment_method'=>$_POST['payment_method'],			'profile_url'=>$_POST['profile_url'],			'paypal_user_id'=>$_POST['paypal_user_id'],			'contact_no'=>$_POST['contact_no'],			'bank_name'=>$_POST['bank_name'],			'account_no'=>$_POST['account_no'],			'ifsc_code'=>$_POST['ifsc_code'],			'created_date'=>date("Y-m-d h:i:s"),		);		$this->db->insert('payment_details',$data);    }	function payment_details_null()    {		$id = $this->session->Admindetail['id'];        $data=array(			'user_id'=>$id,			'created_date'=>date("Y-m-d h:i:s"),		);		$this->db->insert('payment_details',$data);    }	function payment_details_update($data)    {		$id = $this->session->Admindetail['id'];        		$this->db->where('user_id', $id);		$this->db->update('payment_details',$data);    }	function dropdowns($tabel)	{		$this->db->order_by("name","asc");		$data=$this->db->get($tabel);		return $data->result_array(); 	}}?>