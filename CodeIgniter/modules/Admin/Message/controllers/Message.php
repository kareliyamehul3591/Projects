<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_message');
	}
	public function index()
	{
		if($this->session->Admindetail['admin'] == 1)
		{
			$user = $this->Mdl_message->user();
			$data=array(
				'user'=>$user,
				'main_content'=>'message',
				'left_sidebar'=>'Message',
			);
			$this->load->view('admin_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function message_add()
	{
		$user = $this->Mdl_message->message_add();
	}
	public function deletes()
	{
		$user = $this->Mdl_message->deletes($_POST['to_id']);
	}
	public function message_get()
	{
		
		$this->Mdl_message->message_status($_POST['to_id'],$_POST['to_role'],$_POST['from_role'],$_POST['assignment_id']);
		$message = $this->Mdl_message->message($_POST['to_id'],$_POST['to_role'],$_POST['from_role'],$_POST['assignment_id']);
		
		$data = '';
		$i = 0;
		foreach($message as $messag)
		{
			if($i >= $_POST['count']){
				$user_id = $this->Mdl_message->user_id($messag['from_id']);
				if($this->session->Admindetail['id'] == $messag['from_id'] && $messag['from_role'] == $_POST['from_role']){
					
					$data .= '<div class="direct-chat-msg right">
									<div class="direct-chat-info clearfix">
										<span class="direct-chat-name pull-right">'.ucfirst($user_id[0]['name']).'</span>
										<span class="direct-chat-timestamp pull-left">'.date("jS F g:i a",strtotime($messag['created_date'])).'</span>
									</div>
									<img class="direct-chat-img" src="'.base_url().'assets/dist/img/user2-160x160.jpg" alt="message user image">
									<div class="direct-chat-text" style="overflow-wrap: break-word;" >
										'.$messag['message'].'
									</div>
								</div>';
								
				}else{
					$data .= '<div class="direct-chat-msg">
									<div class="direct-chat-info clearfix">
										<span class="direct-chat-name pull-left">'.ucfirst($user_id[0]['name']).'</span>
										<span class="direct-chat-timestamp pull-right">'.date("jS F g:i a",strtotime($messag['created_date'])).'</span>
									</div>
									<img class="direct-chat-img" src="'.base_url().'assets/dist/img/user1-128x128.jpg" alt="message user image">
									<div class="direct-chat-text" style="overflow-wrap: break-word;" >
										'.$messag['message'].'
									</div>
								</div>';
				}		
			}			
			$i++;
		}
		if($data != null)
		{
			$array=array('status'=>1,'datas'=>$data,'count'=>count($message)); 
		}else{
			$array=array('status'=>0,'datas'=>'','count'=>count($message));
		}
		echo json_encode($array);
	}
	public function add_sessone()
	{
		$data = array();
		if($_POST['from_role'] == 'admin') {
			$from_role = 'message_box_admin';
		}else if($_POST['from_role'] == 'manager'){
			$from_role = 'message_box_manager';
		}else if($_POST['from_role'] == 'writer'){
			$from_role = 'message_box_writer';
		}else if($_POST['from_role'] == 'proof_reader'){
			$from_role = 'message_box_proof_reader';
		}
		foreach($this->session->$from_role as $dat)
		{
			$data[] = $dat;
		}
		$data[] = $_POST;
		$datas = array_unique($data, SORT_REGULAR);
		$this->session->set_userdata($from_role,$datas);
	}
	public function close_sessone()
	{
		$data = array();
		if($_POST['from_role'] == 'admin') {
			$from_role = 'message_box_admin';
		}else if($_POST['from_role'] == 'manager'){
			$from_role = 'message_box_manager';
		}else if($_POST['from_role'] == 'writer'){
			$from_role = 'message_box_writer';
		}else if($_POST['from_role'] == 'proof_reader'){
			$from_role = 'message_box_proof_reader';
		}
		foreach($this->session->$from_role as $dat)
		{
			if($_POST['id'] != $dat['id'])
			{
				$data[] = $dat;
			}
		}
		$datas = array_unique($data, SORT_REGULAR);
		$this->session->set_userdata($from_role,$datas);
	}
	public function clos_sessone()
	{
		$data = array();
		if($_POST['from_role'] == 'admin') {
			$from_role = 'message_box_admin';
		}else if($_POST['from_role'] == 'manager'){
			$from_role = 'message_box_manager';
		}else if($_POST['from_role'] == 'writer'){
			$from_role = 'message_box_writer';
		}else if($_POST['from_role'] == 'proof_reader'){
			$from_role = 'message_box_proof_reader';
		}
		foreach($this->session->$from_role as $dat)
		{
			$data[] = $dat;
		}
		echo json_encode($data);
	}
	public function message_remove()
	{
		$this->Mdl_message->message_remove($_POST['to_id'],$_POST['to_role'],$_POST['from_role'],$_POST['assignment_id']);
		return true; 
		die;
	}
	public function header_notifications_get()
	{
		$admin_notifications = $this->Mdl_message->header_notifications_get();
		
		$i = 0;
		foreach($admin_notifications as $notifications)
		{
			if($notifications['online'] == 0)
			{
				$i++;
			}
		}
		$admin_notifications = array_slice($admin_notifications, 0, 10);
		$data = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 22px;" >
				<i class="fa fa-bell-o"></i>
				<span class="label label-warning" style="font-size: 15px;" >'.$i.'</span>
			</a>
			<ul class="dropdown-menu">
				<li class="header">You have '.$i.' notifications</li>
				<li>
					<ul class="menu">';
						foreach($admin_notifications as $notifications)
						{
							$from_id = $this->Mdl_message->user_id($notifications['from_id']);
							$assignment_id = $this->Mdl_message->assignment($notifications['assignment_id']);
							if($notifications['assignment_id'] == 0)
							{
								$data .='<li style="';
								if($notifications['online'] == 0)
								{
									$data .='background-color: #e7e3e9 !important;';
								}
								$data .='" >
										<a href="'.base_url().'index.php/User/view_user/'.$notifications['from_id'].'">
										  <h4 style="margin-left: 10px;">
											'.ucfirst($from_id[0]['name']).' <small style="left: 99px">('.ucfirst($notifications['from_role']).')</small>
											<small><i class="fa fa-clock-o"></i> '.$this->Mdl_message->time_to_hours(strtotime($notifications['created_date'])).' ago'.'</small>
										  </h4>';
										 
										   $data .= '<p style="margin-left: 10px;" > User '.ucfirst($from_id[0]['name']).' '.$notifications['status'].'</p>';
										
										$data .= '</a>
								  </li>';
							}else{
								$data .='<li style="';
								if($notifications['online'] == 0)
								{
									$data .='background-color: #e7e3e9 !important;';
								}
								$data .='" >
										<a href="'.base_url().'index.php/Dashboard/assignment_view/'.$notifications['assignment_id'].'">
										  <h4 style="margin-left: 10px;">
											'.ucfirst($from_id[0]['name']).' <small style="left: 99px">('.ucfirst($notifications['from_role']).')</small>
											<small><i class="fa fa-clock-o"></i> '.$this->Mdl_message->time_to_hours(strtotime($notifications['created_date'])).' ago'.'</small>
										  </h4>';
										  if($notifications['status'] == 'Quality check Failed')
										  {
											   $data .= '<p style="margin-left: 10px;" > Assignment '.$notifications['assignment_id'].' '.$notifications['status'].'</p>';
										  }else{
											   $data .= '<p style="margin-left: 10px;" >'.$notifications['status'].' Assignment '.$notifications['assignment_id'].'</p>';
										  }
										
										$data .= '</a>
								  </li>';
							}
						}
						
		$data .= '</ul>
				</li>
				<li class="footer"><a href="'.base_url().'index.php/Notifications">See All Notifications</a></li>
			</ul>';
		
		$header_notifications_get_all = $this->Mdl_message->header_notifications_get_all();
		if(count($header_notifications_get_all) != $_POST['count'])
		{
			$array=array('status'=>1,'datas'=>$data,'count'=>count($header_notifications_get_all)); 
		}else{
			$array=array('status'=>0,'datas'=>$data,'count'=>count($header_notifications_get_all));
		}
		echo json_encode($array);
	}
	public function header_message_get()
	{
		$messages = $this->Mdl_message->header_message_get($_POST['to_role']);
		
		$temp_array = array();
		$key_array = array(); 
		$i = 0; 
		foreach ($messages as $k=>$v) {
			if (!in_array($v['assignment_id'], $key_array['assignment_id']) || !in_array($v['from_id'], $key_array['from_id'])  || !in_array($v['from_role'], $key_array['from_role'])) {
				$key_array['assignment_id'][$i]=$v['assignment_id'];
				$key_array['from_id'][$i]=$v['from_id'];
				$key_array['from_role'][$i]=$v['from_role'];
				$temp_array[$i]=$v;
			}
			$i++;
		}
		$message = array_slice($temp_array, 0, 10);
		$i = 0;
		foreach($message as $messag)
		{
			if($messag['status'] == 0)
			{
				$i++;
			}
		}
		
		$data = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 22px;" >
				<i class="fa fa-envelope-o"></i>
				<span class="label label-success" style="font-size: 15px;" >'.$i.'</span>
            </a>
			<ul class="dropdown-menu">
			<li class="header">You have '.$i.' messages</li>
			<li>
				<ul class="menu">';
				
		foreach($message as $messag){
			$from_id = $this->Mdl_message->user_id($messag['from_id']);
			if($messag['to_role'] == 'Manager')
			{
				$to_role = '1';
			}else if($messag['to_role'] == 'Admin')
			{
				$to_role = '2';
			}else if($messag['to_role'] == 'Writer')
			{
				$to_role = '3';
			}else if($messag['to_role'] == 'Proof Reader')
			{
				$to_role = '4';
			}else if($messag['to_role'] == 'Help Desk')
			{
				$to_role = '5';
			}

			$url = "javascript:register_popup('".$messag['from_id'].$to_role.$messag['assignment_id']."', '".ucfirst($from_id[0]['name'])."','".$messag['from_id']."','".$messag['from_role']."','".$messag['assignment_id']."');";
			
			$data .='<li style="'; 
								if($messag['status'] == 0)
								{
									$data .='background-color: #e7e3e9 !important;';
								}
								$data .='" onclick="header_message()" >
						<a href="'.$url.'" >
							<div class="pull-left">
								<img src="'.base_url().'assets/dist/img/user1-128x128.jpg" class="img-circle" alt="User Image">
							</div>
							<h4>'.ucfirst($from_id[0]['name']).'<small style="left: 53px">('.ucfirst($messag['from_role']).') (ID '.$messag['assignment_id'].')</small>
								<small><i class="fa fa-clock-o"></i> 
									'.$this->Mdl_message->time_to_hours(strtotime($messag['created_date'])).' ago'.'
								</small>
							</h4>
							<p>'.$messag['message'].'</p>
						</a>
					</li>';
		}
		
		$data .= '</ul>
			</li>
			<li class="footer"><a href="'.base_url().'index.php/Message">See All Messages</a></li>
			</ul>';
			
		if(count($messages) != $_POST['count'])
		{
			$array=array('status'=>1,'datas'=>$data,'count'=>count($messages),'sms'=>$i); 
		}else{
			$array=array('status'=>0,'datas'=>$data,'count'=>count($messages),'sms'=>$i);
		}
		echo json_encode($array);
		
	}
	
}
?> 