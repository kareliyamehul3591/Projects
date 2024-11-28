<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
class Dashboard extends Controller
{
	public function __construct() {
		$this->middleware(['auth', 'authManager']);
	}


	public function dashboard()
	{
		return view('dashboard');
	}

	public function revenuechartdatainit()
	{
		$main_array = array();
		$amount_array = array();
		$day_array = array();

		$today_raw = date("Y-m-d");
		$today_raw = date('Y-m-d', strtotime('1 days', strtotime($today_raw)));
		$thirty_day = date('Y-m-d', strtotime('-31 days', strtotime($today_raw)));

		$datesarray = $this->getDatesFromRange($thirty_day, $today_raw);

		foreach ($datesarray as $key => $value) {
			$date_data = strtotime($value);

			$data = $this->sumOfOrderByDate($value, "All", "All");
			array_push($amount_array, $data[0]->total);
			array_push($day_array, date("d-m", $date_data));
		}

		$main_array['amount'] = $amount_array;
		$main_array['days'] = $day_array;
		
		return response()->json(array(
			'first_chart_array' => $main_array
		));
	}

	public function restaurantIdByManager()
	{
		$user = Auth::user();
		$id_manager = $user->id;

		$restaurantData = DB::table('manager_profiles')
		->select("restaurant_id")
		->where('user_id', $id_manager)
		->get();
		return $restaurantData[0]->restaurant_id;
	}

	public function revenuechartdata(Request $req)
	{
		$subdata = $req->all();

		if(isset($subdata['compare_check'])){
			if($subdata['range_select'] != $subdata['c_range_select']){
				echo "compare_not_same";
				exit();				
			}
		}

		$range_select = $subdata['range_select'];
		$date_from = $subdata['date_from'];
		$date_to = $subdata['date_to'];
		$year_month_range_year = $subdata['year_month_range_year'];
		$month = $subdata['month'];
		$year_range_year = $subdata['year_range_year'];
		$order_type = $subdata['order_type'];
		$order_source = $subdata['order_source'];
		
		$chart_data = $this->genRevChartData($range_select, $date_from, $date_to, $year_month_range_year, 
			$month, $year_range_year, $order_type, $order_source);

		if(isset($subdata['compare_check'])){
			$range_select2 = $subdata['c_range_select'];
			$date_from2 = $subdata['c_date_from'];
			$date_to2 = $subdata['c_date_to'];
			$year_month_range_year2 = $subdata['c_year_month_range_year'];
			$month2 = $subdata['c_month'];
			$year_range_year2 = $subdata['c_year_range_year'];
			$order_type2 = $subdata['c_order_type'];
			$order_source2 = $subdata['c_order_source'];

			$chart_data2 = $this->genRevChartData($range_select2, $date_from2, $date_to2, $year_month_range_year2, 
				$month2, $year_range_year2, $order_type2, $order_source2);

			return response()->json(array(
				'first_chart_array' => $chart_data,
				'second_chart_array' => $chart_data2
			));	

		}else{
			$blank_array['amount'] = "";
			$blank_array['days'] = "";
			return response()->json(array(
				'first_chart_array' => $chart_data,
				'second_chart_array' => $blank_array
			));	
		}
	}

	public function genRevChartData($range_select, $date_from, $date_to, $year_month_range_year, 
		$month, $year_range_year, $order_type, $order_source)
	{
		$main_array = array();
		$amount_array = array();
		$day_array = array();


		if($range_select == "daterange" || $range_select == "yearmonth"){

			$datesarray = [];
			if($range_select == "yearmonth"){
				$date_from = new Carbon('first day of '.$month.' '.$year_month_range_year);
				$date_to = new Carbon("last day of ".$month." ".$year_month_range_year);
				$datesarray = $this->getDatesFromRange($date_from, $date_to);
			}else{
				$date_from=date_create($date_from);
				$date_to=date_create($date_to);

				$date_from = date_format($date_from,"Y-d-m");
				$date_to = date_format($date_to,"Y-d-m");
				$datesarray = $this->getDatesFromRange($date_from, $date_to);

			}

			foreach ($datesarray as $key => $value) {
				$date_data = strtotime($value);

				$data = $this->sumOfOrderByDate($value, $order_type, $order_source);
				array_push($amount_array, $data[0]->total);
				array_push($day_array, date("d-m", $date_data));
			}
		}else if($range_select == "year"){
			$monthArray = ['01','02','03','04','05','06','08','09','10','11','12'];

			foreach ($monthArray as $key => $value) {
				$month = date("M", mktime(0, 0, 0, $value, 10));

				$data = $this->sumOfOrderByMonth($year_range_year, $value, $order_type, $order_source);
				array_push($amount_array, $data[0]->total);
				array_push($day_array, $month);
			}
		}

		$main_array['amount'] = $amount_array;
		$main_array['days'] = $day_array;
		return $main_array;
	}

	public function sumOfOrderByDate($date, $order_type, $order_source)
	{
		$restaurant_id = $this->restaurantIdByManager();
		$query = DB::table('orders')
		->select(DB::raw("SUM(orders.total_price) as total"), 
			DB::raw("DATE_FORMAT(orders.created_at, '%d %b') as dates"));

		if($order_type != "All"){
			$query->where('order_type', $order_type);
		}

		if($order_source != "All"){
			$query->where('order_source', $order_source);
		}

		$query->where('orders.created_at', 'like', $date.'%');
		$result = $query->get();
		return $result;
	}

	public function sumOfOrderByMonth($year, $month, $order_type, $order_source)
	{
		$restaurant_id = $this->restaurantIdByManager();
		$query = DB::table('orders')
		->select(DB::raw("SUM(orders.total_price) as total"));

		if($order_type != "All"){
			$query->where('order_type', $order_type);
		}

		if($order_source != "All"){
			$query->where('order_source', $order_source);
		}

		$query->where('orders.created_at', 'like', $year.'-'.$month.'%');
		$result = $query->get();
		return $result;
	}

	function getDatesFromRange($start, $end) { 
		$array = array(); 
		$Variable1 = strtotime($start); 
		$Variable2 = strtotime($end); 

		for ($currentDate = $Variable1; $currentDate <= $Variable2;  
			$currentDate += (86400)) { 

			$Store = date('Y-m-d', $currentDate); 
			$array[] = $Store; 
		} 
		return $array;
	}
}
