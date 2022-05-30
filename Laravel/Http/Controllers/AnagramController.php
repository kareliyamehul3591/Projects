<?php

namespace App\Http\Controllers;

use App\Models\Anagram;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class AnagramController extends Controller
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
        return view('anagram.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function anagramList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Anagram::join('grades','anagrams.grade_id','=','grades.id')
                ->join('subjects','anagrams.subject_id','=','subjects.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "grade_name") {
                    $values = $values->where('grades', 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_name") {
                    $values = $values->where('subjects', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('anagrams.'. $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['anagrams.*','grades.grade_name','subjects.subject_name'])->map(function ($recode) {
                $recode->show = route('anagram.show', [$recode->id]);
                $recode->edit = route('anagram.edit', [$recode->id]);
                $recode->delete = route('anagram.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $grades = Grade::where('status',1)->get();
        $subjects = Subject::where('status',1)->get();
        return view('anagram.create',compact('grades','subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_id' => 'required',
            'subject_id' => 'required',
            'anagram' => 'required',
            'clue' => 'required',
            'answer' => 'required',
            'day' => 'required',
            'detail' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Anagram::create($data);

        return redirect()->route('anagram.index')->with('success', 'Anagram created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Anagram $anagram)
    {
        $grades = Grade::where('status',1)->get();
        $subjects = Subject::where('status',1)->get();
        return view('anagram.edit', compact('anagram','grades','subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Anagram $anagram)
    {
        $data = $request->validate([
            'grade_id' => 'required',
            'subject_id' => 'required',
            'anagram' => 'required',
            'clue' => 'required',
            'answer' => 'required',
            'day' => 'required',
            'detail' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $anagram->update($data);

        return redirect()->route('anagram.index')->with('success', 'Anagram updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Anagram $anagram)
    {
        return view('anagram.show', compact('anagram'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Anagram $anagram)
    {
        $anagram->delete();

        return redirect()->route('anagram.index')->with('success', 'Anagram deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function anagramMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Anagram::where('id', $id)->delete();
        }

        return redirect()->route('anagram.index')->with('success', 'Anagram deleted successfully');
    }

}
