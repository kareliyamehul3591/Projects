<?php

namespace App\Http\Controllers;

use App\Models\Mentor_category;
use App\Models\Mentor_sub_category;
use Illuminate\Http\Request;

class MentorSubCategoryController extends Controller
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
        return view('mentor_sub_category.list');
    }

    public function mentorSubCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Mentor_sub_category::join('mentor_categories', 'mentor_sub_categories.mentor_category_id', '=', 'mentor_categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "mentor_category_name") {
                    $values = $values->where('mentor_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('mentor_sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['mentor_sub_categories.*', 'mentor_categories.mentor_category_name'])->map(function ($recode) {
                $recode->show = route('mentor_sub_category.show', [$recode->id]);
                $recode->edit = route('mentor_sub_category.edit', [$recode->id]);
                $recode->delete = route('mentor_sub_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $mentor_categories = Mentor_category::where('status', 1)->get();
        return view('mentor_sub_category.create', compact('mentor_categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mentor_sub_category_name' => 'required',
            'mentor_category_id' => 'required'
        ]);

        foreach (explode('+', $request->input('mentor_sub_category_name')) as $mentor_sub_category_name) {
            $data['mentor_sub_category_name'] = $mentor_sub_category_name;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Mentor_sub_category::create($data);
        }

        return redirect()->route('mentor_sub_category.index')->with('success', 'Mentor Sub Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Mentor_sub_category $mentor_sub_category)
    {
        return view('mentor_sub_category.show', compact('mentor_sub_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Mentor_sub_category $mentor_sub_category)
    {
        $mentor_categories = Mentor_category::where('status', 1)->get();

        return view('mentor_sub_category.edit', compact('mentor_sub_category', 'mentor_categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Mentor_sub_category $mentor_sub_category)
    {
        $data = $request->validate([
            'mentor_sub_category_name' => 'required',
            'mentor_category_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $mentor_sub_category->update($data);

        return redirect()->route('mentor_sub_category.index')->with('success', 'Mentor Sub Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Mentor_sub_category $mentor_sub_category)
    {
        $mentor_sub_category->delete();

        return redirect()->route('mentor_sub_category.index')->with('success', 'Mentor Sub Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function mentorSubCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Mentor_sub_category::where('id', $id)->delete();
        }

        return redirect()->route('mentor_sub_category.index')->with('success', 'Mentor Sub Category deleted successfully');
    }
}
