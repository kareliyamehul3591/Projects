<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FSAApi extends Controller
{

	public function fridgeList()
	{
		$data = DB::table('fridge')->select('*')
		->where('deletion_status', 'Not deleted')
		->where('activation_status', 'Active')
		->get();
		echo json_encode($data);
	}

	public function temperatureEntry(Request $req)
	{
		$date = date("Y-m-d",strtotime(str_replace('/','-',$req->date))) ;

		$data = DB::table('fridge_temperature')->select('*')
		->where('date', $date)
		->where('am_pm', $req->am_pm)
		->where('id_fridge', $req->id_fridge)
		->get();

		if(count($data) > 0){
			echo json_encode(array('result' => 'fail', 'message' => 'For selected date, fridge & 12-hr period data already given'));
		}else{

			$idata['date'] = $date;
			$idata['time'] = $req->time;
			$idata['id_fridge'] = $req->id_fridge;
			$idata['temperature'] = $req->temperature;
			$idata['method'] = $req->method;

			if($req->method == 'Celsius'){
				$idata['method_short_form'] = "°C";

			}else if($req->method == 'Fahrenheit'){
				$idata['method_short_form'] = "°F";
			}

			$idata['entered_by'] = 1;
			$idata['am_pm'] = $req->am_pm;
			$idata['comments'] = $req->comments;
			$idata['sign'] = $req->sign;

			$result = DB::table('fridge_temperature')->insert($idata);
			if($result){
				echo json_encode(array('result' => 'success', 'message' => 'Data saved'));
			}else{
				echo json_encode(array('result' => 'fail', 'message' => 'Something went wrong'));
			}
		}
	}


	public function hygieneTermsWithQues()
	{
		$mainArray = array();
		$termsArray = array();

		$terms = DB::table('hygiene_terms')
		->select('*')
		->get()->toArray();

		foreach ($terms as $key => $value) {
			$quesArray = array();
			$ques = DB::table('hygiene_ques')
			->select('*')
			->where('id_hygiene_terms', $value->id_hygiene_terms)
			->get()->toArray();		

			$termsArray['terms'] = $value->name;
			$termsArray['ques'] = $ques;

			array_push($mainArray, $termsArray);
		}
		echo json_encode($mainArray);
	}


	public function hygieneEntry(Request $req)
	{
		$submitted_data = $req->all();
		$hy_data = json_decode($submitted_data['hy_data']);

		$check_data['name'] = $submitted_data['name'];
		$check_data['position'] = $submitted_data['position'];
		$check_data['sign'] = $submitted_data['sign'];
		$check_data['date'] = date("Y-m-d",strtotime(str_replace('/','-',$submitted_data['date']))) ;;
		$check_data['check_type'] = $submitted_data['check_type'];


		try{
			DB::beginTransaction();

			DB::table('hygiene_check')->insert($check_data);

			$id_check = DB::getPdo()->lastInsertId();

			foreach ($hy_data as $key => $value) {
				$h_data = array();
				$h_data['id_hygiene_terms'] = $value->id_hygiene_terms;
				$h_data['id_hygiene_ques'] = $value->id_hygiene_ques;
				$h_data['sat_yes'] = $value->sat_yes;
				$h_data['sat_no'] = $value->sat_no;
				$h_data['details'] = $value->details;
				$h_data['id_hygiene_check'] = $id_check;
				DB::table('hygiene_entry')->insert($h_data);
			}

			DB::commit();
			return response()->json(array("result" => "success", "message" => 'Data saved successfully'));
		}catch(Exception $e){
			DB::rollback();
			return response()->json(array("result" => "fail", "message" => $e));
		}

	}

	public function hygieneReportNames()
	{
		$data = DB::table('hygiene_check')->select('*')->get();
		echo json_encode($data);
	}

	public function hygieneDataByReportId($id_check)
	{
		$check_data = DB::table('hygiene_check')->select('*')->where('id_hygiene_check',$id_check)->get()->toArray();
		
		$report_data = DB::table('hygiene_entry')
		->select('hygiene_entry.*', 'hygiene_terms.name', 'hygiene_ques.question')
		->rightjoin('hygiene_terms', 'hygiene_terms.id_hygiene_terms', '=', 'hygiene_entry.id_hygiene_terms')
		->rightjoin('hygiene_ques', 'hygiene_ques.id_hygiene_ques', '=', 'hygiene_entry.id_hygiene_ques')
		->where('id_hygiene_check', $id_check)
		->get();

		echo json_encode(array('check_data' => $check_data, 'report_data' => $report_data));
	}
}
