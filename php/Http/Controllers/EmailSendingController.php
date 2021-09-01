<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class EmailSendingController extends Controller
{
	public function __construct() {
		$this->middleware(['auth', 'authManager']);
	}

	public function index() {

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


	public function getAllCustomerGroups()
	{
		$groupData = DB::table('customer_groups')
		->select('id', 'name')
		->where('restaurant_id', $this->restaurantIdByManager())
		->get();
		return response()->json($groupData);
	}

	public function emailsendingview()
	{
		return view('emailmarketing/sendemail');
	}

	public function groupEmailSend(Request $req)
	{
		$groupId = $req->group;
		$message = trim($req->message);

		$allEmailByGroup = DB::table('group_related_customers')
		->select('email')
		->where('restaurant_id', $this->restaurantIdByManager())
		->where('customer_group_id', $groupId)
		->get();
		return response()->json($allEmailByGroup);
	}
}
