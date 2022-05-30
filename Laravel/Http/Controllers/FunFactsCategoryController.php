<?php

namespace App\Http\Controllers;

use App\Models\Fun_facts_category;
use Illuminate\Http\Request;

class FunFactsCategoryController extends Controller
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
        return view('fun_facts_category.list');
    }

    public function funFactsCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Fun_facts_category;

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
                $recode->show = route('fun_facts_category.show', [$recode->id]);
                $recode->edit = route('fun_facts_category.edit', [$recode->id]);
                $recode->delete = route('fun_facts_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('fun_facts_category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fun_category_name' => 'required',
            'show' => 'nullable',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Fun_facts_category::create($data);

        return redirect()->route('fun_facts_category.index')->with('success', 'Fun Facts Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Fun_facts_category $fun_facts_category)
    {
        return view('fun_facts_category.show', compact('fun_facts_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Fun_facts_category $fun_facts_category)
    {
        return view('fun_facts_category.edit', compact('fun_facts_category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Fun_facts_category $fun_facts_category)
    {
        $data = $request->validate([
            'fun_category_name' => 'required',
            'show' => 'nullable',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $fun_facts_category->update($data);

        return redirect()->route('fun_facts_category.index')->with('success', 'Fun Facts Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Fun_facts_category $fun_facts_category)
    {
        $fun_facts_category->delete();

        return redirect()->route('fun_facts_category.index')->with('success', 'Fun Facts Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function funFactsCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Fun_facts_category::where('id', $id)->delete();
        }

        return redirect()->route('fun_facts_category.index')->with('success', 'Fun Facts Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Fun_facts_category::orderBy('order')->get();
        return view('fun_facts_category.order', compact('categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Fun_facts_category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('fun_facts_category.order')->with('success', 'Fun Facts Category Ordered successfully');
    }

}
