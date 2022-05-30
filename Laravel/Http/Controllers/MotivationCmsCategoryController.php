<?php

namespace App\Http\Controllers;

use App\Models\Motivation_cms_category;
use Illuminate\Http\Request;

class MotivationCmsCategoryController extends Controller
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
        return view('motivation_cms_category.list');
    }

    public function cmsCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Motivation_cms_category;

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
                $recode->show = route('motivation_cms_category.show', [$recode->id]);
                $recode->edit = route('motivation_cms_category.edit', [$recode->id]);
                $recode->delete = route('motivation_cms_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('motivation_cms_category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'motivation_cms_category_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Motivation_cms_category::create($data);

        return redirect()->route('motivation_cms_category.index')->with('success', 'Motivation CMS Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Motivation_cms_category $motivation_cms_category)
    {
        return view('motivation_cms_category.show', compact('motivation_cms_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Motivation_cms_category $motivation_cms_category)
    {
        return view('motivation_cms_category.edit', compact('motivation_cms_category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Motivation_cms_category $motivation_cms_category)
    {
        $data = $request->validate([
            'motivation_cms_category_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $motivation_cms_category->update($data);

        return redirect()->route('motivation_cms_category.index')->with('success', 'Motivation CMS Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Motivation_cms_category $motivation_cms_category)
    {
        $motivation_cms_category->delete();

        return redirect()->route('motivation_cms_category.index')->with('success', 'Motivation CMS Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function cmsCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Motivation_cms_category::where('id', $id)->delete();
        }

        return redirect()->route('motivation_cms_category.index')->with('success', 'Motivation CMS Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Motivation_cms_category::orderBy('order')->get();
        return view('motivation_cms_category.order', compact('categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Motivation_cms_category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('motivation_cms_category.order')->with('success', 'Motivation CMS Category Ordered successfully');
    }
}
