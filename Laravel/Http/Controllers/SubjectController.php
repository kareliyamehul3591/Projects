<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Auth;
use Illuminate\Http\Request;
use Str;

class SubjectController extends Controller
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
        return view('subject.list');
    }

    public function subjectList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Subject;
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
                $recode->show = route('subject.show', [$recode->id]);
                $recode->edit = route('subject.edit', [$recode->id]);
                $recode->delete = route('subject.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('subject.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_name' => 'required|unique:subjects',
            'subject_description' => 'required',
            'short_code' => 'required',
            'color' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,webp,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/subject/images', $image);
            $data['image'] = asset('storage/subject/images/'. $image);
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Subject::create($data);

        return redirect()->route('subject.index')->with('success', 'Subject created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Subject $subject)
    {
        return view('subject.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Subject $subject)
    {
        return view('subject.edit', compact('subject'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'subject_name' => "required|unique:subjects,subject_name,$subject->id,id",
            'subject_description' => 'required',
            'short_code' => 'required',
            'color' => 'required',
            'image' => 'nullable',
            'status' => "required",
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/subject/images', $image);
            $data['image'] = asset('storage/subject/images/'. $image);
        }

        $data['updated_by'] = auth()->user()->id;

        $subject->update($data);

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subject.index')->with('success', 'Subject deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function subjectMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Subject::where('id', $id)->delete();
        }

        return redirect()->route('subject.index')->with('success', 'Subject deleted successfully');
    }
}
