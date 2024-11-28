<?php

namespace App\Http\Controllers;

use App\Models\Fun_facts_category;
use App\Models\Fun_facts_sub_category;
use Illuminate\Http\Request;

class FunFactsSubCategoryController extends Controller
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
        return view('fun_facts_sub_category.list');
    }

    public function funFactsSubCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Fun_facts_sub_category::join('fun_facts_categories', 'fun_facts_sub_categories.fun_facts_category_id', '=', 'fun_facts_categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "fun_category_name") {
                    $values = $values->where('fun_facts_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('fun_facts_sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['fun_facts_sub_categories.*', 'fun_facts_categories.fun_category_name'])->map(function ($recode) {
                $recode->show = route('fun_facts_sub_category.show', [$recode->id]);
                $recode->edit = route('fun_facts_sub_category.edit', [$recode->id]);
                $recode->delete = route('fun_facts_sub_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $categories = Fun_facts_category::where('status', 1)->get();
        return view('fun_facts_sub_category.create', compact('categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fun_sub_category_name' => 'required',
            'fun_facts_category_id' => 'required',
        ]);
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Fun_facts_sub_category::create($data);

        return redirect()->route('fun_facts_sub_category.index')->with('success', 'Fun Facts Sub Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Fun_facts_sub_category $fun_facts_sub_category)
    {
        return view('fun_facts_sub_category.show', compact('fun_facts_sub_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Fun_facts_sub_category $fun_facts_sub_category)
    {
        $categories = Fun_facts_category::where('status', 1)->get();

        return view('fun_facts_sub_category.edit', compact('fun_facts_sub_category', 'categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Fun_facts_sub_category $fun_facts_sub_category)
    {
        $data = $request->validate([
            'fun_sub_category_name' => 'required',
            'fun_facts_category_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $fun_facts_sub_category->update($data);

        return redirect()->route('fun_facts_sub_category.index')->with('success', 'Fun Facts Sub Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Fun_facts_sub_category $fun_facts_sub_category)
    {
        $fun_facts_sub_category->delete();

        return redirect()->route('fun_facts_sub_category.index')->with('success', 'Fun Facts Sub Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function funFactsSubCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Fun_facts_sub_category::where('id', $id)->delete();
        }

        return redirect()->route('fun_facts_sub_category.index')->with('success', 'Fun Facts Sub Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Fun_facts_category::where('status', 1)->get();

        if ($request->isMethod('post')) 
        {
            $data = $request->validate([
                'fun_facts_category_id' => 'required',
            ]);

            $subcategorys = Fun_facts_sub_category::where('fun_facts_category_id', $data['fun_facts_category_id'])
                ->orderBy('order')
                ->get();

        } 
        else 
        {
            $data = [];
            $subcategorys = [];
        }
        return view('fun_facts_sub_category.order', compact('data', 'categorys', 'subcategorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Fun_facts_sub_category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('fun_facts_sub_category.order')->with('success', 'Fun Facts Sub Category Ordered successfully');
    }
}
