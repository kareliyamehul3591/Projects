<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class Fridge extends Controller
{
	public function __construct() {
		$this->middleware(['auth', 'authManager']);
	}

	public function fridgeview()
	{
		$data = DB::table('fridge')
		->select(DB::raw("COUNT(fridge.id_fridge) as totalFridge"))
		->get();

		$data=array('totalFridge'=>$data[0]->totalFridge);
		return view('fsaforms/fridge', $data);
	}

	public function addFridge(Request $req)
	{
		if($req->has('number_of_fridge')){
			
			$fridgeNo = $req->number_of_fridge;

			$data = array();  
			$count = 0;
			for($i = 1; $i<=$fridgeNo; $i++){
				$data['fridge_name'] = "Fridge no. ".$i;
				DB::table('fridge')->insert($data);
				$count++;
			}

			if($count == $fridgeNo){
				echo "success";
			}else{
				echo "fail";
			}

		}else{
			$data['fridge_name'] = $req->fridge_name;
			$data['remark'] = $req->remark;
			$result = DB::table('fridge')->insert($data);
			if($result){
				echo "success";
			}else{
				echo "fail";
			}
		}
	}


	public function fridgeList()
	{
		$data = DB::table('fridge')->select('*')->where('deletion_status', 'Not deleted')->get();
		echo json_encode($data);
	}


	public function activeInactiveFridge($id_fridge)
	{
		$update_data = [];
		$fridge_data = $this->getFridgeInfoById($id_fridge);
		if($fridge_data[0]->activation_status == "Active"){
			$update_data['activation_status'] = 'Inactive';
		}else{
			$update_data['activation_status'] = 'Active';
		}
		try{
			DB::table('fridge')
			->where('id_fridge', $id_fridge)
			->update($update_data);
			echo "success";
		}catch(Exception $e) {
			echo "fail";
		}
	}


	public function deleteFridge($id_fridge)
	{
		$update_data['deletion_status'] = 'Deleted';
		try{
			DB::table('fridge')
			->where('id_fridge', $id_fridge)
			->update($update_data);
			echo "success";
		}catch(Exception $e) {
			echo "fail";
		}
	}

	public function getFridgeInfoById($id_fridge)
	{
		$data = DB::table('fridge')->select('*')
		->where('id_fridge', $id_fridge)->get();
		return $data;
	}
}
