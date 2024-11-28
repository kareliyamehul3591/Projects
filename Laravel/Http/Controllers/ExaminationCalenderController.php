<?php

namespace App\Http\Controllers;

use App\Models\Examination_calender;
use App\Models\Package;
use App\Models\Sms_template;
use App\Models\Student;
use App\Models\Student_package;
use App\Models\User;
use Illuminate\Http\Request;

class ExaminationCalenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index()
    {
        return view('examination_calender.list');
    }

    public function examinationCalenderList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Examination_calender;

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                $values = $values->where($opt, 'LIKE', "%$q[$key]%");
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->show = route('examination_calender.show', [$recode->id]);
                $recode->edit = route('examination_calender.edit', [$recode->id]);
                $recode->delete = route('examination_calender.delete', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $packages = Package::where('status',1)->get();
        return view('examination_calender.create',compact('packages'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_title' => 'required',
            'exam_description' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'end_date' => 'required|date_format:Y-m-d',
            'end_time' => 'required',
            'show_in' => 'required',
            'package_id' => 'required',
            'keyword' => 'required',
        ]);
        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $examination_calender = Examination_calender::create($data);

        $student_packages = Student_package::whereIn('package_id',explode(',',$examination_calender->package_id))
            ->groupBy('user_id')
            ->pluck('user_id')
            ->toArray();
        foreach($student_packages as $student_package)
        {
            $user = User::where('id',$student_package)->first();
            $sExamData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'exam_title' => $examination_calender->name,
                'exam_content' => $examination_calender->exam_description,
            ];
            $user->examEmailStudent($sExamData);

            $student = Student::where('user_id',$user->id)->first();
            $puser = User::where('id',$student->parents()->user_id)->first();
            $pExamData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $student->user->name,
                'student_email' => $student->user->email,
                'exam_title' => $examination_calender->name,
                'parent_name' => $puser->name,
                'parent_email' => $puser->email,
            ];
            $puser->examEmailParent($pExamData);

            $data['examination_calender'] = $examination_calender->name;
            $template = Sms_template::find(19);
            $template->sendMessage($data);
        }
        return redirect()->route('examination_calender.index')->with('success', 'Examination Calender created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Examination_calender $examination_calender)
    {
        return view('examination_calender.show', compact('examination_calender'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Examination_calender $examination_calender)
    {
        $packages = Package::where('status',1)->get();
        return view('examination_calender.edit', compact('examination_calender','packages'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Examination_calender $examination_calender)
    {
        $data = $request->validate([
            'exam_title' => 'required',
            'exam_description' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'end_date' => 'required|date_format:Y-m-d',
            'end_time' => 'required',
            'show_in' => 'required',
            'package_id' => 'required',
            'keyword' => 'required',
            'status' => 'required',
        ]);

        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['updated_by'] = auth()->user()->id;

        $examination_calender->update($data);

        return redirect()->route('examination_calender.index')->with('success', 'Examination Calender updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Examination_calender $examination_calender)
    {
        $examination_calender->delete();

        return redirect()->route('examination_calender.index')->with('success', 'Examination Calender deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function examinationCalenderMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Examination_calender::where('id', $id)->delete();
        }

        return redirect()->route('examination_calender.index')->with('success', 'Examination Calender deleted successfully');
    }
}
