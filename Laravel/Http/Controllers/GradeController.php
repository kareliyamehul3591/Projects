<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
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
        return view('grade.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function gradeList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Grade;

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
                $recode->show = route('grade.show', [$recode->id]);
                $recode->edit = route('grade.edit', [$recode->id]);
                $recode->delete = route('grade.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('grade.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Grade::create($data);

        return redirect()->route('grade.index')->with('success', 'Grade created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Grade $grade)
    {
        return view('grade.edit', compact('grade'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'grade_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $grade->update($data);

        return redirect()->route('grade.index')->with('success', 'Grade updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Grade $grade)
    {
        return view('grade.show', compact('grade'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grade.index')->with('success', 'Grade deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function gradeMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Grade::where('id', $id)->delete();
        }

        return redirect()->route('grade.index')->with('success', 'Grade deleted successfully');
    }

}
