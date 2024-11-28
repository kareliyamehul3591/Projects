<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Manager_message extends MX_Controller 
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Mdl_manager_message');
	}
	public function index()
	{
		if($this->session->Admindetail['manager'] == 1)
		{
			$user = $this->Mdl_manager_message->user();
			$data=array(
				'user'=>$user,
				'main_content'=>'manager_message',
				'left_sidebar'=>'Manager Message',
			);
			$this->load->view('manager_template/template',$data);
		}else{
			redirect('Admin_login');
		}
	}
	public function message_add()
	{
		$user = $this->Mdl_manager_message->message_add();
		redirect('Manager_message?id='.$_POST['to_id']);
	}
	public function message_get()
	{
		$this->Mdl_manager_message->message_status($_POST['id']);
		$message = $this->Mdl_manager_message->message($_POST['id']);
		$data = '';	
		$i = 0;
		foreach($message as $messag)
		{
			if($i >= $_POST['count'])
			{
				$user_id = $this->Mdl_manager_message->user_id($messag['from_id']);
				if($this->session->Admindetail['id'] == $messag['from_id']){
					
					$data .= '<div class="direct-chat-msg right">
									<div class="direct-chat-info clearfix">
										<span class="direct-chat-name pull-right">'.ucfirst($user_id[0]['name']).'</span>
										<span class="direct-chat-timestamp pull-left">'.date("jS F g:i a",strtotime($messag['created_date'])).'</span>
									</div>
									<img class="direct-chat-img" src="'.base_url().'assets/dist/img/user2-160x160.jpg" alt="message user image">
									<div class="direct-chat-text">
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
									<div class="direct-chat-text">
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
	public function header_notifications_get()
	{
		$admin_notifications = $this->Mdl_manager_message->header_notifications_get();
		
		$i = 0;
		foreach($admin_notifications as $notification)
		{
			if($notification['online'] == 0)
			{
				$i++;
			}
		}
		$admin_notifications = array_slice($admin_notifications, 0, 10);
		$data = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 22px;" >
				<i class="fa fa-bell-o"></i>
				<span class="label label-warning" style="font-size: 15px;" >'.$i.'</span>
			</a>
			<ul class="dropdown-menu" >
				<li class="header">You have '.$i.' notifications</li>
				<li>
					<ul class="menu">';
					
					$admin_notifications = array_slice($admin_notifications, 0, 10);
						foreach($admin_notifications as $notifications)
						{
							$from_id = $this->Mdl_manager_message->user_id($notifications['from_id']);
							$assignment_id = $this->Mdl_manager_message->assignment($notifications['assignment_id']);
							if($notifications['assignment_id'] == 0)
							{
								$data .='<li style="';
								if($notifications['online'] == 0)
								{
									$data .='background-color: #e7e3e9 !important;';
								}
								$data .='" >
										<a>
										  <h4 style="margin-left: 10px;">
											'.ucfirst($from_id[0]['name']).' <small style="left: 65px">('.ucfirst($notifications['from_role']).')</small>
											<small><i class="fa fa-clock-o"></i> '.$this->Mdl_manager_message->time_to_hours(strtotime($notifications['created_date'])).' ago'.'</small>
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
								
								$data .='" >';
											if($notifications['status'] == 'Deleted')
											{
												$data .='<a>';
											}else{
												$data .='<a href="'.base_url().'index.php/Manager_dashboard/assignment_view/'.$notifications['assignment_id'].'">';
											}
										  $data .='<h4 style="margin-left: 10px;">
											'.ucfirst($from_id[0]['name']).' <small style="left: 65px">('.ucfirst($notifications['from_role']).')</small>
											<small><i class="fa fa-clock-o"></i> '.$this->Mdl_manager_message->time_to_hours(strtotime($notifications['created_date'])).' ago'.'</small>
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
				<li class="footer"><a href="'.base_url().'index.php/Manager_notifications">See All Notifications</a></li>
			</ul>';
		$header_notifications_get_all = $this->Mdl_manager_message->header_notifications_get_all();
		if(count($header_notifications_get_all) != $_POST['count'])
		{
			$array=array('status'=>1,'datas'=>$data,'count'=>count($header_notifications_get_all)); 
		}else{
			$array=array('status'=>0,'datas'=>$data,'count'=>count($header_notifications_get_all));
		}
		echo json_encode($array);
		
	}
	public function header_message_get(){
		$messages = $this->Mdl_manager_message->header_message_get($_POST['to_role']);
		
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
			$from_id = $this->Mdl_manager_message->user_id($messag['from_id']);
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
						<a href="'.$url.'">
							<div class="pull-left">
								<img src="'.base_url().'assets/dist/img/user1-128x128.jpg" class="img-circle" alt="User Image">
							</div>
							<h4>'.ucfirst($from_id[0]['name']).'<small style="left: 53px">('.ucfirst($messag['from_role']).') (ID '.$messag['assignment_id'].')</small>
								<small><i class="fa fa-clock-o"></i> 
									'.$this->Mdl_manager_message->time_to_hours(strtotime($messag['created_date'])).' ago'.'
								</small>
							</h4>
							<p>'.$messag['message'].'</p>
						</a>
					</li>';
		}
		
		$data .= '</ul>
			</li>
			<li class="footer"><a href="'.base_url().'index.php/Manager_message">See All Messages</a></li>
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