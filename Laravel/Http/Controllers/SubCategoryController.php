<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sub_category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
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
        return view('subcategory.list');
    }

    public function subCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Sub_category::join('categories', 'sub_categories.category_id', '=', 'categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "category_name") {
                    $values = $values->where('categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['sub_categories.*', 'categories.category_name'])->map(function ($recode) {
                $recode->show = route('subcategory.show', [$recode->id]);
                $recode->edit = route('subcategory.edit', [$recode->id]);
                $recode->delete = route('subcategory.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('subcategory.create', compact('categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subcategory_name' => 'required',
            'category_id' => 'required',
            'need_login' => 'nullable',
        ]);

        if (!isset($data['need_login'])) {
            $data['need_login'] = 0;
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Sub_category::create($data);

        return redirect()->route('subcategory.index')->with('success', 'Sub Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Sub_category $subcategory)
    {
        $category = Category::where('id', $subcategory->category_id)->first();

        return view('subcategory.show', compact('subcategory', 'category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Sub_category $subcategory)
    {
        $categories = Category::where('status', 1)->get();

        return view('subcategory.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Sub_category $subcategory)
    {
        $data = $request->validate([
            'subcategory_name' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'need_login' => 'nullable',
        ]);

        if (!isset($data['need_login'])) {
            $data['need_login'] = 0;
        }
        $data['updated_by'] = auth()->user()->id;

        $subcategory->update($data);

        return redirect()->route('subcategory.index')->with('success', 'Sub Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Sub_category $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('subcategory.index')->with('success', 'Sub Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function subCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Sub_category::where('id', $id)->delete();
        }

        return redirect()->route('subcategory.index')->with('success', 'Sub Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Category::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'category_id' => 'required',
            ]);
            $subcategorys = Sub_category::where('category_id', $data['category_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $subcategorys = [];
        }
        return view('subcategory.order', compact('data', 'categorys', 'subcategorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Sub_category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('subcategory.order')->with('success', 'Sub category Ordered successfully');
    }
}
