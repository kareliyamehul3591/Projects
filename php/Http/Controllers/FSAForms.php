<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FSAForms extends Controller
{
	public function __construct() {
		$this->middleware(['auth', 'authManager']);
	}


	public function temperatureReport()
	{
		return view('fsaforms/tempreport');
	}

	public function hygieneChecklistView()
	{
		return view('fsaforms/hygienechecklist');	
	}

	public function fridgeTemperatureByMothYear($month, $year)
	{
		$mainArray = array();
		
		$allDates=array();

		$fridgeData = DB::table('fridge')->select('id_fridge', 'fridge_name')
		->where('deletion_status', 'Not deleted')
		->where('activation_status', 'Active')
		->orderby('fridge.id_fridge', 'ASC')
		->get()->toarray();

		for($d=1; $d<=31; $d++)
		{
			$time=mktime(12, 0, 0, $month, $d, $year);          
			if (date('m', $time)==$month){       
				$allDates[]=date('Y-m-d', $time);
			}
		}

		foreach ($allDates as $key => $value) {
			$tempArray = array();
			$fIdx = 0;

			for($i=1; $i<= count($fridgeData)*2; $i++){
				
				$dayArray = array();
				$tData = array();

				if($i%2 != 0){
					$tData = $this->getFridgeData($value, $fridgeData[$fIdx]->id_fridge, "AM");
				}else{
					$tData = $this->getFridgeData($value, $fridgeData[$fIdx]->id_fridge, "PM");
					$fIdx++;
				}

				if(count($tData) > 0){
					$dayArray['date'] = $value;
					$dayArray['temperature'] = $tData[0]->temperature;
					$dayArray['comments'] = $tData[0]->comments;
					$dayArray['sign'] = $tData[0]->sign;
				}else{
					$dayArray['date'] = $value;
					$dayArray['temperature'] = '-';
					$dayArray['comments'] = '-';
					$dayArray['sign'] = '-';
				}

				array_push($tempArray, $dayArray);
			}
			array_push($mainArray, $tempArray);
		}

		echo json_encode(array('tempData' => $mainArray, 'fridgeNames' => $fridgeData, 'noOfFridge' => count($fridgeData)));
	}


	public function getFridgeData($date, $id_fridge, $am_pm)
	{
		$tempData = DB::table('fridge_temperature')
		->select(DB::raw("CONCAT(temperature,'',method_short_form) as temperature"), 'comments', 'sign')
		->where('date', $date)
		->where('id_fridge', $id_fridge)
		->where('am_pm', $am_pm)
		->get()->toarray();
		return $tempData;
	}

	public function getTempYears()
	{
		$results = DB::select( DB::raw("SELECT
			Year(`date`) as years
			FROM fridge_temperature
			GROUP BY Year(`date`)
			ORDER BY Year(`date`) DESC"));
		echo json_encode($results);
	}


	public function getTrainingNames()
	{
		$data = DB::table('hygiene_training_name')
		->select('*')
		->get()->toarray();
		echo json_encode($data);
	}


	public function searchEmployeeByName(Request $req)
	{
		$data = $req->all();
		$term = $data['term'];

		$result = DB::table('hygiene_trained_employee')
		->select('id', DB::raw("CONCAT(name, ' - ', date_of_employment) as value"))
		->where('name', 'like', '%'.$term.'%')
		->get()->toarray();
		echo json_encode($result);
	}

	public function trainedEmployeeAdd(Request $req)
	{
		$post_data = $req->all();
		if(trim($post_data['name']) == "" || trim($post_data['position']) == "" || trim($post_data['join_date']) == ""){
			echo json_encode(array('result' => 'fail', 'message' => 'Fill the fields properly'));
		}else{
			$post_data['date_of_employment'] = date("Y-m-d",strtotime(str_replace('/','-',$post_data['join_date']))) ;
			unset($post_data['_token']);
			unset($post_data['join_date']);
			$result = DB::table('hygiene_trained_employee')->insert($post_data);
			if($result){
				echo json_encode(array('result' => 'success', 'message' => 'Employee added successfully'));
			}else{
				echo json_encode(array('result' => 'fail', 'message' => 'Something went wrong'));
			}
		}	
	}


	public function trainingadd(Request $req)
	{
		$sub_data = $req->all();

		$data['id_employee'] = $sub_data['employee_id'];
		$data['trainer'] = $sub_data['trainer']; 
		$data['id_training_name'] = $sub_data['training'];

		$date = date_create($sub_data['date']);
		$data['date'] = date_format($date,"Y-m-d");

		$duplicate = DB::table('hygiene_training_entry')
		->select('*')
		->where('id_employee', $data['id_employee'])
		->where('id_training_name', $data['id_training_name'])
		->get()->toarray();

		if(count($duplicate)>0){
			echo json_encode(array('result' => 'fail', 'message' => 'Selected training for this employee already entered'));
			exit();
		}

			// File upload with validation start
		$file = $req->file('signature');
		if($file != ""){
			$file_extension = $file->getClientOriginalExtension();
			if($this->fileExtensionsAllowed($file_extension) == "allowed"){
				$file_size = $file->getSize();
				if($file_size <= 1100000){  // Allowed file size is 2.1 MB
					//$destinationPath = 'uploads/userimages';
					try{
						$data['signature'] = time().'.'.$file->getClientOriginalExtension();
						$file->move('uploads/trainer_signature',$data['signature']);
					}catch(Exception $e){
						echo $e;
					}
				}else{
					echo json_encode(array('result' => 'fail', 'message' => 'Please try within 1MB file size'));
				}
			}else{
				echo json_encode(array('result' => 'fail', 'message' => 'Attached file type is not allowed'));
			}
		}
		// File upload with validation end

		$result = DB::table('hygiene_training_entry')->insert($data);
		if($result){
			echo json_encode(array('result' => 'success', 'message' => 'Data saved successfully'));
		}else{
			echo json_encode(array('result' => 'fail', 'message' => 'Something went wrong'));
		}
	}

	public function trainingEntryView()
	{
		return view('fsaforms/trainingentry');	
	}

	public function trainingreportview()
	{
		return view('fsaforms/trainingreport');	
	}

	public function trainingDataByEmployeeId($id_employee)
	{
		$trainingNames = DB::table('hygiene_training_name')->select('*')->get()->toarray();
		$employee_info = DB::table('hygiene_trained_employee')->select('*')->where('id', $id_employee)->get()->toarray();

		$training_data = DB::table('hygiene_training_entry')
		->select('hygiene_training_entry.*', 'hygiene_training_name.name')
		->join('hygiene_training_name', 'hygiene_training_name.id', '=', 'hygiene_training_entry.id_training_name')
		->where('id_employee', $id_employee)
		->get()->toarray();

		$trainingArray = array();

		foreach ($trainingNames as $key => $value) {
			foreach ($training_data as $key2 => $value2) {
				if($value->id == $value2->id_training_name){
					array_push($trainingArray, $value2);	
				}
			}

			if (in_array($value->name, array_column($trainingArray, 'name'))){

			}else{
				$blank = array();
				$blank['name'] = $value->name;
				$blank['date'] = "";
				$blank['signature'] = "";
				$blank['trainer'] = "";
				array_push($trainingArray, $blank);				
			}
		}
		echo json_encode(array('employee_info' => $employee_info, 'training_data' => $trainingArray));		
	}



	public function fitnessentry()
	{
		return view('fsaforms/fitnessentry');	
	}

	public function getFitnessQuestions()
	{
		$data = DB::table('fitness_question')
		->select('*')
		->get()->toArray();
		echo json_encode($data);
	}


	public function addFitnessInfo(Request $req)
	{
		$post_data = $req->all();

		$question_answers = json_decode($post_data['question_answers'], true); 

		$date = date_create($post_data['date_of_assessment']);
		$fit_entry_data['date_of_assessment'] = date_format($date,"Y-m-d");

		$date2 = date_create($post_data['manager_date']);
		$fit_entry_data['manager_date'] = date_format($date2,"Y-m-d");

		$date3 = date_create($post_data['employee_sign_date']);
		$fit_entry_data['employee_sign_date'] = date_format($date3,"Y-m-d");

		$fit_entry_data['id_employee'] = $post_data['id_employee'];
		$fit_entry_data['reason_for_assessment'] = $post_data['reason_for_assessment'];
		$fit_entry_data['action_taken'] = $post_data['action_taken'];
		$fit_entry_data['manager'] = $post_data['manager'];

		$file = $req->file('employee_sign');
		if($file != ""){
			$file_extension = $file->getClientOriginalExtension();
			if($this->fileExtensionsAllowed($file_extension) == "allowed"){
				$file_size = $file->getSize();
				if($file_size <= 1100000){  // Allowed file size is 2.1 MB
					try{
						$fit_entry_data['employee_sign'] = time().'.'.$file->getClientOriginalExtension();
						$file->move('uploads/fitness_sign',$fit_entry_data['employee_sign']);
					}catch(Exception $e){
						echo $e;
					}
				}else{
					// echo json_encode(array('result' => 'fail', 'message' => 'Please try within 1MB file size'));
					echo "large";
					exit();
				}
			}else{
				// echo json_encode(array('result' => 'fail', 'message' => 'Attached file type is not allowed'));
				echo "wrong_type";
				exit();
			}
		}

		try{
			DB::beginTransaction();

			DB::table('fitness_entry')->insert($fit_entry_data);

			$id_fitnes_entry = DB::getPdo()->lastInsertId();

			foreach ($question_answers as $key => $value) {
				if($key != 0){
					$a_data['id_question'] = $value[1];
					$a_data['answer'] = $value[2];
					$a_data['id_fitness_entry'] = $id_fitnes_entry;
					DB::table('fitness_answer')->insert($a_data);					
				}
			}

			DB::commit();
			echo "success";
			// return response()->json(array("result" => "success", "message" => 'Data saved successfully'));
		}catch(Exception $e){
			DB::rollback();
			// return response()->json(array("result" => "fail", "message" => $e));
			echo "fail";
		}
	}


	public function fitnessreports()
	{
		return view('fsaforms/fitnessreports');	
	}

	public function fitnessreportlist()
	{
		$data = DB::table('fitness_entry')
		->select('fitness_entry.id', 'fitness_entry.date_of_assessment', 'hygiene_trained_employee.name')
		->join('hygiene_trained_employee', 'hygiene_trained_employee.id', '=', 'fitness_entry.id_employee')
		->get()->toArray();
		echo json_encode($data);	
	}


	public function fitnessReportDetails($id_fitness)
	{
		$answer_data = DB::table('fitness_answer')
		->select('fitness_answer.answer', 'fitness_question.question')
		->join('fitness_question', 'fitness_question.id', '=', 'fitness_answer.id_question')
		->where('fitness_answer.id_fitness_entry', $id_fitness)
		->get()->toArray();

		$report_data = DB::table('fitness_entry')
		->select('fitness_entry.*', 'hygiene_trained_employee.name')
		->join('hygiene_trained_employee', 'hygiene_trained_employee.id', '=', 'fitness_entry.id_employee')
		->where('fitness_entry.id', $id_fitness)
		->get()->toArray();

		echo json_encode(array('answer_data' =>  $answer_data, 'report_data' =>  $report_data));
	}


	public function fileExtensionsAllowed($file_extension)
	{
		$allowed_or_not = "";
		$allowed_extensions = array('jpg','jpeg','png');
		if (in_array($file_extension, $allowed_extensions)) 
		{ 
			$allowed_or_not = "allowed"; 
		} 
		else
		{ 
			$allowed_or_not = "not_allowed"; 
		}
		return $allowed_or_not;
	}



}
