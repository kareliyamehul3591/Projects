<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class ManagerReportsController extends Controller
{
	public function __construct() {
		$this->middleware(['auth', 'authManager']);
	}

	public function index() {
	}

	public function topAverageView()
	{
		return view('reports/top_average');
	}

	public function chartView()
	{
		return view('reports/chart_reports');
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

	public function topCustomersOnSpend()
	{
		$restaurant_id = $this->restaurantIdByManager();
		$data = DB::table('orders')
		->select('orders.user_id', 'users.name','users.email', DB::raw("SUM(orders.total_price) as totalSpend"), DB::raw("COUNT(orders.id) as totalOrders"), DB::raw("ROUND((SUM(orders.total_price) / COUNT(orders.id)),2) as avgorederval"))
		->leftjoin('users', 'users.id', '=', 'orders.user_id')
		->where('orders.restaurant_id', $restaurant_id)
		->groupby('orders.user_id')
		->orderby('totalSpend','DESC')
		->limit(20)
		->get();
		return response()->json($data);
	}

	public function regularCustomersOnOrders()
	{
		$restaurant_id = $this->restaurantIdByManager();
		$data = DB::table('orders')
		->select('orders.user_id', 'users.name','users.email', DB::raw("SUM(orders.total_price) as totalSpend"), DB::raw("COUNT(orders.id) as totalOrders"), DB::raw("COUNT(orders.id) as totalOrders"), DB::raw("ROUND((SUM(orders.total_price) / COUNT(orders.id)),2) as avgorederval"))
		->leftjoin('users', 'users.id', '=', 'orders.user_id')
		->where('orders.restaurant_id', $restaurant_id)
		->groupby('orders.user_id')
		->orderby('totalOrders','DESC')
		->limit(20)
		->get();
		return response()->json($data);
	}


	public function activeCustomerZone($postCodeOrCity)
	{
		if($postCodeOrCity == "postcode"){
			$restaurant_id = $this->restaurantIdByManager();
			$data = DB::table('orders')
			->select('orders.post_code', DB::raw("COUNT(orders.post_code) as noOfOrders"), DB::raw("SUM(orders.total_price) as totalEarn"), DB::raw("ROUND((SUM(orders.total_price) / COUNT(orders.post_code)),2) as avgorederval"))
			->where('orders.restaurant_id', $restaurant_id)
			->groupby('orders.post_code')
			->orderby('noOfOrders','DESC')
			->limit(20)
			->get();
			return response()->json($data);
		}
	}


	public function averageOfAll()
	{
		$restaurant_id = $this->restaurantIdByManager();
		$data = DB::table('orders')
		->select(DB::raw("COUNT(orders.id) as noOfOrders"), DB::raw("SUM(orders.total_price) as totalEarn"), DB::raw("ROUND((SUM(orders.total_price) / COUNT(orders.id)),2) as avgorederval"))
		->where('orders.restaurant_id', $restaurant_id)
		->orderby('noOfOrders','DESC')
		->get();
		return response()->json($data);
	}


	public function getLastTwoWeekReports()
	{

		$now = Carbon::now();
		$weekStartDate = $now->startOfWeek()->format('Y-m-d');
		$weekEndDate = $now->endOfWeek()->format('Y-m-d');

		$daysAgoWeekStartDate = date('Y-m-d', strtotime('-1 days', strtotime($weekStartDate)));
		$days7AgoWeekStartDate = date('Y-m-d', strtotime('-7 days', strtotime($weekStartDate)));


		$lastWeekDates = $this->getDatesFromRange($weekStartDate, $weekEndDate);
		$beforeLastWeekDates = $this->getDatesFromRange($days7AgoWeekStartDate, $daysAgoWeekStartDate);


		$lastWeekArr = array();
		$beforeLastWeekArr = array();

		$thisWeekOrderSum = array();
		$lastWeekOrderSum = array();

		foreach ($lastWeekDates as $key => $value) {
			array_push($lastWeekArr, $this->countOrderNoByDate($value));
			array_push($thisWeekOrderSum, $this->sumOfOrderByDate($value));
		}

		foreach ($beforeLastWeekDates as $key => $value) {
			array_push($beforeLastWeekArr, $this->countOrderNoByDate($value));
			array_push($lastWeekOrderSum, $this->sumOfOrderByDate($value));
		}
		return response()->json(array(
			'lastweek' => $lastWeekArr, 'beforelastweek' => $beforeLastWeekArr,
			'thisweeksum' => $thisWeekOrderSum, 'lastweeksum' => $lastWeekOrderSum));
	}


	public function getLastMonthSaleComparison()
	{
		$now = Carbon::now();
		$monthStartDate = $now->startOfMonth()->format('Y-m-d');
		$monthEndDate = $now->endOfMonth()->format('Y-m-d');

		$lastMonthStartDate = new Carbon('first day of last month');
		$lastMonthEndDate = new Carbon('last day of last month');

		$lastMonthStartDate = \Carbon\Carbon::parse($lastMonthStartDate)->format('Y-m-d');
		$lastMonthEndDate = \Carbon\Carbon::parse($lastMonthEndDate)->format('Y-m-d');

		$thismonthdates = $this->getDatesFromRange($monthStartDate, $monthEndDate);
		$lastmonthdates = $this->getDatesFromRange($lastMonthStartDate, $lastMonthEndDate);


		$thismontharray = array();
		$lastmaontharray = array();

		foreach ($thismonthdates as $key => $value) {
			array_push($thismontharray, $this->sumOfOrderByDate($value));
		}

		foreach ($lastmonthdates as $key => $value) {
			array_push($lastmaontharray, $this->sumOfOrderByDate($value));
		}

		$date = \Carbon\Carbon::now();
		return response()->json(array(
			'thismonthsales' => $thismontharray, 'lastmonthsales' => $lastmaontharray,
			'thismonthname' => $date->format('F'), 'lastmonthname' => $date->subMonth()->format('F'),
		));
	}


	public function countOrderNoByDate($date)
	{
		$restaurant_id = $this->restaurantIdByManager();
		$data = DB::table('orders')
		->select(DB::raw("COUNT(orders.id) as noOfOrders"))
		->where('orders.restaurant_id', $restaurant_id)
		->where('orders.created_at', 'like', $date.'%')
		->get();
		return $data[0]->noOfOrders;
	}


	public function sumOfOrderByDate($date)
	{
		$restaurant_id = $this->restaurantIdByManager();
		$data = DB::table('orders')
		->select(DB::raw("SUM(orders.total_price) as total"))
		->where('orders.restaurant_id', $restaurant_id)
		->where('orders.created_at', 'like', $date.'%')
		->get();
		return $data[0]->total;
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


	public function customersOrderedThanMonthAgo()
	{
		$now = Carbon::now();

		$today = $now->today()->format('Y-m-d')." 00:00:00";
		$thirtydaybefore = date('Y-m-d', strtotime('-30 days', strtotime($today)))." 23:59:59";
		$thirtyOneDayBefore = date('Y-m-d', strtotime('-31 days', strtotime($today)))." 00:00:00";
		$hundredDayBefore = date('Y-m-d', strtotime('-100 days', strtotime($today)))." 23:59:59";

		$lastThirtyDaysCustomer = $this->getCustomerOrdersBetweenDates($today, $thirtydaybefore);
		$last31To100DaysCustomer = $this->getCustomerOrdersBetweenDates($thirtyOneDayBefore, $hundredDayBefore);

		foreach ($last31To100DaysCustomer as $key => $value) {
			foreach ($lastThirtyDaysCustomer as $key2 => $value2) {
				if($value->user_id == $value2->user_id){
					unset($last31To100DaysCustomer[$key]);
				}
			}
		}
		return response()->json($last31To100DaysCustomer);
	} 


	public function getCustomerOrdersBetweenDates($startDate, $endDate)
	{
		$restaurant_id = $this->restaurantIdByManager();

		$data = DB::table('orders')
		->select('orders.user_id', 'users.name','users.email', DB::raw("SUM(orders.total_price) as totalSpend"), DB::raw("COUNT(orders.id) as totalOrders"), 
			DB::raw('DATE_FORMAT(orders.created_at, "%d %b %Y")  as lastOrdered'))
		->leftjoin('users', 'users.id', '=', 'orders.user_id')
		->where('orders.restaurant_id', $restaurant_id)
		->where('orders.created_at', '>=', DB::raw("'".$endDate."'"))
		->where('orders.created_at', '<=', DB::raw("'".$startDate."'"))
		->groupby('orders.user_id')
		->orderby('totalOrders','DESC')
		->get()->toArray();
		return $data;
	}


	public function topSellingItems()
	{
		$token = "iu7jnXpgcJYxBmsMkXe0acidmEdbO5D0xzZk7Y9qpDbiFfMrkHuYhsnaJxrQwgwdo8n7NkroioFHz55k";
		$ch = curl_init('http://orderpin.co.uk/apps/restaurant_api/public/api/top_selling_items');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token
		));
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$data = json_decode($data, true, JSON_UNESCAPED_SLASHES);
		return $data;
	}


}
