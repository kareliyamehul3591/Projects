<?php

namespace App\Http\Controllers;

use App\Models\Help_video;
use App\Models\Help_video_category;
use Illuminate\Http\Request;

class HelpVideoCategoryController extends Controller
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
        return view('help_video_category.list');
    }

    public function helpVideoCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Help_video_category;
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
                $recode->show = route('help_video_category.show', [$recode->id]);
                $recode->edit = route('help_video_category.edit', [$recode->id]);
                $recode->delete = route('help_video_category.delete', [$recode->id]);

                $help_video = Help_video::where('help_video_category_id',$recode->id)->count();
                $recode->video_count = $help_video;
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('help_video_category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'help_video_category_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Help_video_category::create($data);

        return redirect()->route('help_video_category.index')->with('success', 'Help Video Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Help_video_category $help_video_category)
    {
        return view('help_video_category.show', compact('help_video_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Help_video_category $help_video_category)
    {
        return view('help_video_category.edit', compact('help_video_category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Help_video_category $help_video_category)
    {
        $data = $request->validate([
            'help_video_category_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $help_video_category->update($data);

        return redirect()->route('help_video_category.index')->with('success', 'Help Video Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Help_video_category $help_video_category)
    {
        $help_video_category->delete();

        return redirect()->route('help_video_category.index')->with('success', 'Help Video Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function helpVideoCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Help_video_category::where('id', $id)->delete();
        }

        return redirect()->route('help_video_category.index')->with('success', 'Help Video Category deleted successfully');
    }

}
