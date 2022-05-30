<?php

namespace App\Http\Controllers;

use App\Models\Mentor_category;
use Illuminate\Http\Request;

class MentorCategoryController extends Controller
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
        return view('mentor_category.list');
    }

    public function mentorCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Mentor_category;
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
                $recode->show = route('mentor_category.show', [$recode->id]);
                $recode->edit = route('mentor_category.edit', [$recode->id]);
                $recode->delete = route('mentor_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('mentor_category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mentor_category_name' => 'required|unique:mentor_categories',
            'non_subject' => 'nullable',
        ]);

        foreach (explode('+', $request->input('mentor_category_name')) as $mentor_category_name) {
            $data['mentor_category_name'] = $mentor_category_name;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Mentor_category::create($data);
        }

        return redirect()->route('mentor_category.index')->with('success', 'Mentor Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Mentor_category $mentor_category)
    {
        return view('mentor_category.show', compact('mentor_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Mentor_category $mentor_category)
    {
        return view('mentor_category.edit', compact('mentor_category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Mentor_category $mentor_category)
    {
        $data = $request->validate([
            'mentor_category_name' => 'required',
            'non_subject' => 'nullable',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $mentor_category->update($data);

        return redirect()->route('mentor_category.index')->with('success', 'Mentor Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Mentor_category $mentor_category)
    {
        $mentor_category->delete();

        return redirect()->route('mentor_category.index')->with('success', 'Mentor Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function mentorCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Mentor_category::where('id', $id)->delete();
        }

        return redirect()->route('mentor_category.index')->with('success', 'Mentor Category deleted successfully');
    }
}
