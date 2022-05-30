<?php

namespace App\Http\Controllers;

use App\Exports\ReferenceExamExport;
use App\Imports\ReferenceExamImport;
use App\Models\Reference_exam;
use Illuminate\Http\Request;
use Excel;
use Str;

class ReferenceExamController extends Controller
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
        return view('reference_exam.list');
    }

    public function referenceExamList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Reference_exam;
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
                $recode->show = route('reference_exam.show', [$recode->id]);
                $recode->edit = route('reference_exam.edit', [$recode->id]);
                $recode->delete = route('reference_exam.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('reference_exam.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reference_exam_name' => 'required',
            'import' => 'required|mimes:xlxs,xlsx|max:2048',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $reference_exam = Reference_exam::create($data);

        if ($request->hasFile('import')) {
            Excel::import(new ReferenceExamImport($reference_exam),request()->file('import'));
        }

        return redirect()->route('reference_exam.index')->with('success', 'Reference Exam created successfully.');
    }

    public function referenceExamExport()
    {
        return Excel::download(new ReferenceExamExport, 'ReferenceExam.xlsx');
    }

    /**
     * View the specified record.
     */
    public function show(Reference_exam $reference_exam)
    {
        return view('reference_exam.show', compact('reference_exam'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Reference_exam $reference_exam)
    {
        return view('reference_exam.edit', compact('reference_exam'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Reference_exam $reference_exam)
    {
        $data = $request->validate([
            'reference_exam_name' => 'required',
            'import' => 'required|mimes:xlxs,xlsx|max:2048',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $reference_exam->update($data);

        $reference_exam->reference_exam_data()->delete();

        if ($request->hasFile('import')) {
            Excel::import(new ReferenceExamImport($reference_exam),request()->file('import'));
        }

        return redirect()->route('reference_exam.index')->with('success', 'Reference Exam updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Reference_exam $reference_exam)
    {
        $reference_exam->delete();

        return redirect()->route('reference_exam.index')->with('success', 'Reference Exam deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function referenceExamMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Reference_exam::where('id', $id)->delete();
        }

        return redirect()->route('reference_exam.index')->with('success', 'Reference Exam deleted successfully');
    }
}
