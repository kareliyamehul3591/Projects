<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Subject_grade;
use Auth;
use Illuminate\Http\Request;

class SubjectGradeController extends Controller
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
        return view('subjectgrade.list');
    }

    public function subjectGradeList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Subject_grade::join('subjects', 'subject_grades.subject_id', '=', 'subjects.id');
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['subject_grades.*', 'subjects.subject_name'])->map(function ($recode) {
                $recode->show = route('subject_grade.show', [$recode->id]);
                $recode->edit = route('subject_grade.edit', [$recode->id]);
                $recode->delete = route('subject_grade.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $subjects = Subject::where('status', 1)->get();

        return view('subjectgrade.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_grade_name' => 'required',
            'subject_id' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Subject_grade::create($data);

        return redirect()->route('subject_grade.index')->with('success', 'Subject Grade created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Subject_grade $subject_grade)
    {
        $subject = Subject::where('id', $subject_grade->subject_id)->first();

        return view('subjectgrade.show', compact('subject_grade', 'subject'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Subject_grade $subject_grade)
    {
        $subjects = Subject::where('status', 1)->get();

        return view('subjectgrade.edit', compact('subject_grade', 'subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Subject_grade $subject_grade)
    {
        $data = $request->validate([
            'subject_grade_name' => 'required',
            'subject_id' => 'required',
            'status' => "required",
        ]);

        $data['updated_by'] = auth()->user()->id;

        $subject_grade->update($data);

        return redirect()->route('subject_grade.index')->with('success', 'Subject Grade updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Subject_grade $subject_grade)
    {
        $subject_grade->delete();

        return redirect()->route('subject_grade.index')->with('success', 'Subject Grade deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function subjectGradeMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Subject_grade::where('id', $id)->delete();
        }

        return redirect()->route('subject_grade.index')->with('success', 'Subject Grade deleted successfully');
    }
}
