<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request; 
	use DB;
	class ManagerController extends Controller{
		
		protected $restaurant;
		
		public function __construct(){
			
			$this->middleware(['auth','authManager']);
			
		}
		
		protected function restaurant(){
			
			if(empty($this->restaurant)){
				return $this->restaurant=\Auth::user()->manager_profile->restaurant;
			}
			
			return $this->restaurant;
			
		}
		
		public function order_reports(){
			
			$data=[
			
			'dashboard_title'=>$this->restaurant()->name
			
			];
			
			return view('manager.order_reports', $data);
		}
		
		public function coupons(){
			
			$data['restaurant']=$this->restaurant();
			
			$data['carbon']=new \Carbon\Carbon;
			
			$data['coupons']=\App\Coupon::
			join('coupon_groups', 'coupon_groups.id', '=', 'coupons.coupon_group_id')
			->join('coupon_discounts', 'coupon_discounts.coupon_group_id', '=', 'coupon_groups.id')
			->where(['coupon_discounts.restaurant_id'=>$this->restaurant()->id, 'coupons.is_active'=>1])
			->paginate(15);
			
			return view('manager.coupons.list', $data);
			
		}
		
		public function couponForm(){
			
			return view('manager.coupons.form');
			
		}
		
		public function addCoupon(Request $request){
			
			$this->validate($request, [
			'group_name'=>'required|max:190',
			'active'=>'required|boolean',
			'expires_after'=>'nullable|integer',
			'percentage'=>'required|numeric',
			'quantity'=>'required|integer'
			]);
			
			\DB::beginTransaction();
			
			$couponGroup=new \App\CouponGroup;
			
			$couponGroup->name=$request->input('group_name');
			$couponGroup->is_active=$request->input('active');
			
			if($request->input('description')){
				$couponGroup->description=$request->input('description');
			}
			
			if(!$couponGroup->save()){
				DB::rollBack();
				return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			}
			
			$couponDiscount=new \App\CouponDiscounts;
			
			$couponDiscount->coupon_group_id=$couponGroup->id;
			
			if($request->input('expires_after')){
				$couponDiscount->expiration_date=\Carbon\Carbon::now()
				->addDays($request->input('expires_after'));
			}
			
			$couponDiscount->restaurant_id=$this->restaurant()->id;
			$couponDiscount->percentage=$request->input('percentage');
			$couponDiscount->is_active=$request->input('active');
			
			if(!$couponDiscount->save()){
				DB::rollBack();
				return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);	
			}
			
			$newCoupons=$this->generateCouponData(
			$request->input('quantity'),
			$couponGroup->id,
			$request->input('active')
			);
			
			//dd($newCoupons);
			
			$coupons=\App\Coupon::insert($newCoupons);
			
			if(!$coupons){
				DB::rollBack();
				return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);	
			}
			
			\DB::commit();
			return back()->with(['success'=>'Coupons added successfully!.']);
			
		}
		
		protected function generateCouponData($count, $coupon_group_id, $is_active=1){
			
			function iterateUniqueCoupon($randomInteger){
				
				if(\App\Coupon::where('coupon', $randomInteger)->exists()){
					
					return iterateUniqueCoupon(mt_rand(100000, 999999));
					
				}else return $randomInteger;
				
			}
			
			$data=[];
			
			for($i = 0; $i < $count; $i++){
				
				array_push($data, [
				'coupon_group_id'=>$coupon_group_id,
				'coupon'=>iterateUniqueCoupon(mt_rand(100000, 999999)),
				'is_active'=>1
				]);
				
			}
			
			return $data;
			
		}
		
		public function users(){
			
			$users=\App\User::wherehas('detail', function($query){
				$query->where('restaurant_id', $this->restaurant()->id);
			});
			
			//dd($users);
			
			
			$data=[
			'carbon'=> new \Carbon\Carbon,
			'paginate'=> new \App\Helpers\Paginate($users, [
			'id'=>'ID',
			'name'=>'Name',
			'email'=>'Email Address',
			'created_at'=>'Created At'
			])
			];
			
			return view('manager.users.list',$data);
			
		}
		
		public function deleteUser(\App\User $user){
			
			$user->detail()->delete();
			$user->orders()->delete();
			$user->purchase_points()->delete();
			$user->coupon()->delete();
			$user->order_reviews()->delete();
			
			if($user->delete()) return back()->with(['success'=>'User information deleted successfully!.']);
			return back()->with(['failed'=>'Sorry!, unable to submit request, try again later.']);
			
		}
		
		public function externalCustomers(){
			
			$externalCustomers=$this->restaurant()->externalCustomers();
			
			$data=[
			'carbon'=> new \Carbon\Carbon,
			'paginate'=> new \App\Helpers\Paginate($externalCustomers, [
			'id'=>'ID',
			'name'=>'Name',
			'email'=>'Email Address',
			'postcode'=>'Postcode',
			'mobile'=>'Mobile',
			'phone'=>'Phone',
			'created_at'=>'Created At'
			])
			];
			
			return view('manager.external_customers.list', $data);
			
		}
		
		public function externalCustomerForm(){
			
			return view('manager.external_customers.form');
			
		}
		
		public function storeExternalCustomer(Request $request){
			
			$this->validate($request, [
			'name'=>'required|max:191',
			'email'=>'nullable|email|max:191',
			'postcode'=>'nullable|max:191',
			'mobile'=>'nullable|numeric',
			'phone'=>'nullable|numeric',
			]);
			
			$exists=\App\ExternalCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'email'=>$request->input('email')
			])->exists();
			
			if($exists) return back()->with(['failed'=>'Sorry!, submitted email address already exists in our records.'])->withInput();
			
			$exists=\App\ExternalCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'mobile'=>$request->input('mobile')
			])->exists();
			
			if($exists) return back()->with(['failed'=>'Sorry!, submitted mobile number already exists in our records.'])->withInput();
			
			$exists=\App\ExternalCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'phone'=>$request->input('phone')
			])->exists();
			
			if($exists) return back()->with(['failed'=>'Sorry!, submitted phone number already exists in our records.'])->withInput();
			
			$externalCustomer=new \App\ExternalCustomer;
			$externalCustomer->fill($request->all());
			$externalCustomer->restaurant_id=$this->restaurant()->id;
			
			if($externalCustomer->save()) return back()->with(['success'=>'External customer added successfully!.']);
			
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function storeExternalCustomers(Request $request){
			
			$this->validate($request, [
			'csv_list'=>'required|file|mimes:csv,txt'
			]);
			
			$path= $request->csv_list->path();
			$extension= $request->csv_list->extension();
			$carbon=new \Carbon\Carbon;
			
			$reader=\Excel::load($path);
			
			$csv_data=$reader->toArray();
			
			$total_row=count($csv_data);
			$inserted_row=0;
			$validated_rows=[];
			
			foreach ($csv_data as $row){
				
				$validator = \Validator::make($row, [
				'name'=>'required|max:191',
				'email'=>'nullable|email|max:191',
				'postcode'=>'nullable|max:191',
				'mobile'=>'nullable|numeric',
				'phone'=>'nullable|numeric'
				]);
				
				if($validator->fails()) continue;
				else{
					
					if($row['email']){
						
						$exists=\App\ExternalCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'email'=>$row['email']
						])->exists();
						
						if($exists) continue;
						
					}
					
					if($row['mobile']){
						
						$exists=\App\ExternalCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'mobile'=>$row['mobile']
						])->exists();
						
						if($exists) continue;
						
					}
					
					if($row['phone']){
						
						$exists=\App\ExternalCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'phone'=>$row['phone']
						])->exists();
						
						if($exists) continue;
						
					}
					
					$row['restaurant_id']=$this->restaurant()->id;
					$row['created_at']=$carbon->now();
					array_push($validated_rows, $row);
					$inserted_row++;
					
				}
			}
			
			$result=\App\ExternalCustomer::insert($validated_rows);
			
			if($result) return back()->with(['success'=>'Inserted '.$inserted_row.' row from requested '.$total_row.' rows some row might get ommited due to the duplicate issues.']);
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function deleteExternalCustomer(\App\ExternalCustomer $external_customer){
			
			if($external_customer->delete()) return back()->with(['success'=>'Selected external customers deleted successfully.']);
			return back()->with(['failed'=>'Sorry!, unable to submit delete request, try again later.']);
			
		}
		
		public function exportExternalCustomers(){
			
			$data=$this->restaurant()
			->externalCustomers()
			->select('name', 'email', 'postcode', 'mobile', 'phone', 'address')
			->get();
			
			//return $data;
			
			\Excel::create('external_customers', function($excel) use($data) {
				
				$excel->sheet('Sheetname', function($sheet) use($data) {
					
					$sheet->fromModel($data);
					
				});
				
			})->download('csv');
			
		}
		
		public function sms_marketing_form($customer_type){
			
			
			if(!in_array($customer_type, ['internal-customers', 'external-customers', 'group-related-customers'])){
				return redirect('/');
			}
			
			$data['customer_type']=$customer_type;
			
			$data['customer_groups']=\App\CustomerGroup::where([
			'restaurant_id'=>$this->restaurant()->id,
			'master_group'=>0
			])->get()->pluck('name', 'id');
			
			return view('manager.sms_marketing_form', $data);
			
		}
		
		public function email_marketing_form($customer_type){
			
			/* $curl = curl_init();
				
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.sendgrid.com/v3/templates?generations=legacy",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "{}",
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer <<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>>"
				),
				));
				
				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				
				if ($err) {
				echo "cURL Error #:" . $err;
				} else {
				echo $response;
				}
			die; */
			if(!in_array($customer_type, ['internal-customers', 'external-customers', 'group-related-customers'])){
				return redirect('/');
			}
			
			$data['customer_type']=$customer_type;
			
			$data['customer_groups']=\App\CustomerGroup::where([
			'restaurant_id'=>$this->restaurant()->id,
			'master_group'=>0
			])->get()->pluck('name', 'id');
			
			return view('manager.email_marketing_form', $data);
			
		}
		
		public function send_bulk_email(Request $request){
			
			if($request->input('customer_type')=='internal-customers'){
				
				echo "single email";
				
				require '../vendor/autoload.php';
				require '../vendor/sendgrid-php/sendgrid-php.php';
				
				$email = new \SendGrid\Mail\Mail();
				$email->setFrom("chothaniviral3@gmail.com", "Test");
				$email->setSubject("Demo");
				$email->addTo("dineshpatil3332577@gmail.com", "Test");
				$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent(
				"text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
				); 
				$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				try {
					$response = $sendgrid->send($email);
					echo "<pre>";
					print $response->statusCode() . "\n";
					print_r($response->headers());
					print $response->body() . "\n";
					echo "</pre>";
					} catch (Exception $e) {
					echo 'Caught exception: '. $e->getMessage() ."\n";
				} 
				
				}else{
				
				echo "multiple email";
			}
			die;
			
			
		}
		public function send_bulk_sms(Request $request){
			$this->validate($request, [
			//'subject'=>'required',
			'message'=>'required',
			'customer_type'=>'required|in:internal-customers,external-customers,group-related-customers'
			]);
			
			// dd($request->all());
			
			
			$to=NULL;
			$from = '+447788981913';
			$message=$request->input('message');
			$total_sms_send=0;
			
			if($request->input('customer_type')=='internal-customers'){
				
				$customers=\App\User::wherehas('detail', function($query){
					$query->where('restaurant_id', $this->restaurant()->id)
					->whereNotNull('mobile')
					->orWhereNotNull('phone');
				})->get();
				
				foreach($customers as $row){
					
					if($row->detail->mobile) $to=$row->detail->mobile;
					elseif($row->detail->phone) $to=$row->detail->phone;
					
					if($to){
						
						if(strpos($to, '+')===FALSE) $to='+44'.$to;
						$this->sendSms($from, $to, $message);
						$total_sms_send++;
					}
					
				}
				
				}elseif($request->input('customer_type')=='external-customers'){
				
				$customers=$this->restaurant()
				->externalCustomers()
				->select('mobile', 'phone')
				->whereNotNull('mobile')
				->orWhereNotNull('phone')
				->get();
				
				foreach($customers as $row){
					
					$to=NULL;
					
					if($row->mobile) $to=$row->mobile;
					elseif($row->phone) $to=$row->phone;
					
					if($to){
						
						if(strpos($to, '+')===FALSE) $to='+44'.$to;
						$this->sendSms($from, $to, $message);
						$total_sms_send++;
						
					}
					
				}
				
				}elseif($request->input('customer_type')=='group-related-customers'){
				
				$customer_group=\App\CustomerGroup::find($request->input('customer_group_id'));
				
				$customers=$customer_group->related_customers()
				->select('mobile', 'phone')
				->whereNotNull('mobile')
				->orWhereNotNull('phone')
				->get();
				
				foreach($customers as $row){
					
					$to=NULL;
					
					if($row->mobile) $to=$row->mobile;
					elseif($row->phone) $to=$row->phone;
					
					if($to){
						
						if(strpos($to, '+')===FALSE) $to='+44'.$to;
						$this->sendSms($from, $to, $message);
						$total_sms_send++;
						
					}
					
				}
				
			}
			
			return back()->with(['success'=>"SMS send to {$total_sms_send} customers."]);
			
		}
		
		public function sendSms($from, $to, $message)
		{
			
			// $from = '447788981914';
			// $to = '447788981913';
			// $message = "Test SMS from Orderpin";	
			
			$api_key = "dGVzdGFjYzoxcWF6MndzeA==";
			$url = "https://smsgrid.net/portal/sms/api?action=send-sms&api_key".$api_key."=&to=".$to."&from=".$from."&sms=".$message;
			
			// echo $url;
			// exit();
			
			try {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				$output = curl_exec($ch);
				
				if (curl_errno($ch)) {
					$output = curl_error($ch);
				}
				curl_close($ch);
				
				// var_dump($output);
				
				}catch (Exception $exception){
				// echo $exception->getMessage();
				return back()->with(['fail'=>$exception->getMessage()]);
			}
		}
		
		//Customer List Start
		public function customer_list()
		{ 
			
			$customer = new \App\User();  
			
			$data['paginate']= new \App\Helpers\Paginate($customer,[ 
			'id'=>'ID',
			'name'=>'Name',
			'email'=>'Email', 
			]);   
			
			return view('manager.customer.list', $data);
			
		}
		
		public function filter_customer_list(Request $request)
		{  
			$phpdate = strtotime($request->input('date_from'));
			$date_from  = date( 'Y-m-d', $phpdate ); 
			if($date_from == '1970-01-01')
			{
				$date_from = '';
			}  
			$phpdate = strtotime($request->input('date_to'));
			$date_to  = date( 'Y-m-d', $phpdate ); 
			if($date_to == '1970-01-01')
			{
				$date_to = '';
			} 
			$order_type = $request->input('order_type');
			$order_source = $request->input('order_source');
			$complaint_status = $request->input('complaint_status');
			$a = "";
			$b = "";
			$c = "";
			$d = "";
			$e = "";
			if($date_from == null && $date_to == null && $order_type == null && $order_source == null && $complaint_status == null)
			{
				return redirect()->route('customer-list');
			} 
			if($date_from != null)
			{  
				
				if($date_to != null)
				{
					$date_from = "created_at >= '$date_from 00:00:00' and created_at <= '$date_to 00:00:00'"; 
				}else{
				
				$date_from = "created_at  LIKE '%$date_from%'"; 
				}
				 
				$a = "and";
			} 
			if($date_to != null)
			{
				
				if($date_from == null)
				{
				$date_to = "created_at  LIKE '%$date_to%'";   
				$b = "and";
				}else{
				$date_to = "";
				}
				
			}   
			if($order_type != null)
			{ 
				$order_type = "order_type = '$order_type'";
				$c = "and";
			} 
			if($order_source != null)
			{
				$order_source = "order_source = '$order_source'";
				$d = "and";
			} 
			if($complaint_status != null)
			{
				$complaint_status = "complaint_status = '$complaint_status'";
				$e = "and";
			} 
			$da = "$date_from $a $date_to $b $order_type $c $order_source $d $complaint_status $e"; 
			$sa = preg_replace('/\W\w+\s*(\W*)$/', '$1', $da);  
			 
			$data = \App\Order::whereRaw("$sa")->get();  
			$datass =array();
			foreach($data as $key => $value)
			{ 
				$datas = \App\User::where('id','=',$value['user_id'])->orderBy('id', 'ASC')->get(); 
				$datass[] = $datas;
			}
			 
			 
			/* $dataa= new \App\Helpers\Paginate($datass,[ 
			'id'=>'ID',
			'name'=>'Name',
			'email'=>'Email', 
			]);  */ 
			return view('manager.customer.filter_list')->with('paginate',$datass);
			
		}
		//Customer List End
		
		public function customer_groups(){
			
			$customer_groups=\App\CustomerGroup::where('restaurant_id', $this->restaurant()->id);
			
			$data['paginate']= new \App\Helpers\Paginate($customer_groups, [
			
			'id'=>'ID',
			'key'=>'KEY',
			'name'=>'Name',
			'created_at'=>'Created At'
			
			]);
			
			$data['restaurant']=$this->restaurant();
			
			$data['carbon']=new \Carbon\Carbon;
			
			return view('manager.customer_groups.list', $data);
			
		}
		
		public function customer_group_form(\App\CustomerGroup $customer_group){
			
			$data=[
			'customer_group'=>$customer_group,
			
			'master_groups'=>\App\CustomerGroup::where([
			
			'master_group'=>1,
			'restaurant_id'=>$this->restaurant()->id
			
			])->get()->pluck('name', 'id'),
			
			'child_groups'=>\App\CustomerGroup::where([
			
			'master_group'=>0,
			'restaurant_id'=>$this->restaurant()->id
			
			])->whereNull('master_group_id')->get()->pluck('name', 'id')
			];
			
			return view('manager.customer_groups.form', $data);
			
		}
		
		public function store_customer_group(Request $request){
			
			$this->validate($request, [
			'key'=>'required|alpha_dash|max:191',
			'name'=>'required|max:191',
			'master_group'=>'required|boolean',
			'master_group_id'=>'nullable|integer',
			'child_id'=>'nullable|array'
			]);
			
			$key_exists=\App\CustomerGroup::where([
			'key'=>$request->input('key'),
			'restaurant_id'=>$this->restaurant()->id
			])->exists();
			
			if($key_exists){
				return back()->with(['failed'=>'Sorry!, this key already exists for this restaurant.'])->withInput();
			}
			
			$customer_group=new \App\CustomerGroup;
			
			$customer_group->fill($request->except('child_id'));
			
			$customer_group->restaurant_id=$this->restaurant()->id;
			
			if($request->input('master_group')==1) $customer_group->master_group_id=NULL;
			
			if($customer_group->save()){
				
				if($request->input('child_id')){
					
					$child_id=$request->input('child_id');
					
					foreach($child_id as $row){
						
						$temp_customer_group=\App\CustomerGroup::find($row);
						$temp_customer_group->parent_group()->associate($customer_group);
						$temp_customer_group->save();
						
					}
				}
				
				return back()->with(['success'=>'User group added successfully!.']);
			}
			
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function update_customer_group(Request $request, \App\CustomerGroup $customer_group){
			
			if($customer_group->restaurant_id!=$this->restaurant()->id) return back()->with(['failed'=>'Sorry!, you are not authorised to perform this action.'])->withInput();
			
			$this->validate($request, [
			'key'=>'required|alpha_dash|max:191',
			'name'=>'required|max:191',
			'master_group'=>'required|boolean',
			'master_group_id'=>'nullable|integer',
			'child_id'=>'nullable|array'
			]);
			
			$key_exists=\App\CustomerGroup::where([
			'key'=>$request->input('key'),
			'restaurant_id'=>$this->restaurant()->id,
			['id', '<>', $customer_group->id]
			])->exists();
			
			if($key_exists){
				return back()->with(['failed'=>'Sorry!, this key already exists for this restaurant.'])->withInput();
			}
			
			$customer_group->fill($request->except('child_id'));
			
			$customer_group->restaurant_id=$this->restaurant()->id;
			
			if($request->input('master_group')==1) $customer_group->master_group_id=NULL;
			
			if($customer_group->save()){
				
				if($request->input('child_id')){
					
					$child_id=$request->input('child_id');
					
					foreach($child_id as $row){
						
						$temp_customer_group=\App\CustomerGroup::find($row);
						$temp_customer_group->parent_group()->associate($customer_group);
						$temp_customer_group->save();
					}
				}
				
				return back()->with(['success'=>'User group updated successfully!.']);
			}
			
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function group_related_customers(\App\CustomerGroup $customer_group){
			
			if($customer_group->restaurant_id!=$this->restaurant()->id) return abrot(404);
			
			if($customer_group->master_group==1){
				
				$customers=\App\GroupRelatedCustomer::whereHas('customer_group', function($query) use ($customer_group){
					$query->where('master_group_id', $customer_group->id);
				});		
				
			}else $customers=$customer_group->related_customers();
			
			//dd($users);
			
			$data['customer_group']=$customer_group;
			
			$data['paginate']= new \App\Helpers\Paginate($customers, [
			
			'id'=>'ID',
			'name'=>'Name',
			'email'=>'Email',
			'postcode'=>'Postcode',
			'mobile'=>'Mobile',
			'phone'=>'Phone',
			'address'=>'Address'
			
			]);
			
			$data['restaurant']=$this->restaurant();
			
			$data['carbon']=new \Carbon\Carbon;
			
			return view('manager.group_related_customers.list', $data);
			
		}
		
		public function groupRelatedCustomerForm(){
			
			$data['customer_groups']=\App\CustomerGroup::where([
			'restaurant_id'=>$this->restaurant()->id,
			'master_group'=>0
			])->get()->pluck('name', 'id');
			
			return view('manager.group_related_customers.form', $data);
			
		}
		
		public function storeGroupRelatedCustomer(Request $request){
			
			$this->validate($request, [
			'customer_group_id'=>'required|integer',
			'name'=>'required|max:191',
			'email'=>'nullable|email|max:191',
			'postcode'=>'nullable|max:191',
			'mobile'=>'nullable|numeric',
			'phone'=>'nullable|numeric'
			]);
			
			$exists=\App\GroupRelatedCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'email'=>$request->input('email')
			])->exists();
			
			if($request->input('email') && $exists) return back()->with(['failed'=>'Sorry!, submitted email address already exists in our records.'])->withInput();
			
			$exists=\App\GroupRelatedCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'mobile'=>$request->input('mobile')
			])->exists();
			
			if($request->input('mobile') && $exists) return back()->with(['failed'=>'Sorry!, submitted mobile number already exists in our records.'])->withInput();
			
			$exists=\App\GroupRelatedCustomer::where([
			'restaurant_id'=>$this->restaurant()->id,
			'phone'=>$request->input('phone')
			])->exists();
			
			if($request->input('phone') && $exists) return back()->with(['failed'=>'Sorry!, submitted phone number already exists in our records.'])->withInput();
			
			$groupRelatedCustomer=new \App\GroupRelatedCustomer;
			$groupRelatedCustomer->fill($request->all());
			$groupRelatedCustomer->restaurant_id=$this->restaurant()->id;
			
			if($groupRelatedCustomer->save()) return back()->with(['success'=>'Group related customer added successfully!.']);
			
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function storeGroupRelatedCustomers(Request $request){
			
			$this->validate($request, [
			'customer_group_id'=>'required|integer',
			'csv_list'=>'required|file|mimes:csv,txt'
			]);
			
			$path= $request->csv_list->path();
			$extension= $request->csv_list->extension();
			$carbon=new \Carbon\Carbon;
			
			$reader=\Excel::load($path);
			
			$csv_data=$reader->toArray();
			
			$total_row=count($csv_data);
			$inserted_row=0;
			$validated_rows=[];
			
			foreach ($csv_data as $row){
				
				$validator = \Validator::make($row, [
				'name'=>'required|max:191',
				'email'=>'nullable|email|max:191',
				'postcode'=>'nullable|max:191',
				'mobile'=>'nullable|numeric',
				'phone'=>'nullable|numeric'
				]);
				
				if($validator->fails()) continue;
				else{
					
					if($row['email']){
						
						$exists=\App\GroupRelatedCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'email'=>$row['email']
						])->exists();
						
						if($exists) continue;
						
					}
					
					if($row['mobile']){
						
						$exists=\App\GroupRelatedCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'mobile'=>$row['mobile']
						])->exists();
						
						if($exists) continue;
						
					}
					
					if($row['phone']){
						
						$exists=\App\GroupRelatedCustomer::where([
						'restaurant_id'=>$this->restaurant()->id,
						'phone'=>$row['phone']
						])->exists();
						
						if($exists) continue;
						
					}
					
					$row['restaurant_id']=$this->restaurant()->id;
					$row['customer_group_id']=$request->input('customer_group_id');
					$row['created_at']=$carbon->now();
					array_push($validated_rows, $row);
					$inserted_row++;
					
				}
				
				
				\App\GroupRelatedCustomer::create($row);
				
			}
			
			//$result=\App\GroupRelatedCustomer::insert($validated_rows);
			
			/*if($result)*/ return back()->with(['success'=>'Inserted '.$inserted_row.' row from requested '.$total_row.' rows some row might get ommited due to the duplicate issues.']);
			return back()->with(['failed'=>'Sorry!, unable to submit form, try again later.']);
			
		}
		
		public function deleteGroupRelatedCustomer(\App\GroupRelatedCustomer $group_related_customer){
			
			if($group_related_customer->restaurant_id!=$this->restaurant()->id) return back()->with(['failed'=>'Sorry!, you are not authorised to perform this action.']);
			
			if($group_related_customer->delete()) return back()->with(['success'=>'Selected group related customer deleted successfully.']);
			
			return back()->with(['failed'=>'Sorry!, unable to submit delete request, try again later.']);
			
		}
		
		public function exportGroupRelatedCustomers(\App\CustomerGroup $customer_group){
			
			if($customer_group->master_group==1){
				
				$customers=\App\GroupRelatedCustomer::whereHas('customer_group', function($query) use ($customer_group){
					$query->where('master_group_id', $customer_group->id);
				});		
				
			}else $customers=$customer_group->related_customers();
			
			$data=$customers
			->select('name', 'email', 'postcode', 'mobile', 'phone', 'address')
			->get();
			
			//return $data;
			
			\Excel::create('external_customers', function($excel) use($data) {
				
				$excel->sheet('Sheetname', function($sheet) use($data) {
					
					$sheet->fromModel($data);
					
				});
				
			})->download('csv');
			
		}
		// segmentation start
		public function segmentation()
		{ 

			return view('manager.segmentation.segmentation');
		}
		//segmentation end 
		
	}
