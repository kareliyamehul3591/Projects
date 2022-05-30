<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
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
        return view('category.list');
    }

    public function categoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Category;

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

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
                $recode->show = route('category.show', [$recode->id]);
                $recode->edit = route('category.edit', [$recode->id]);
                $recode->delete = route('category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|unique:categories',
            'category_image' => 'required',
            'description' => 'nullable',
            'cms' => 'nullable',
            'strategy' => 'nullable',
            'do_it' => 'nullable',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('category_image')) {
            $category_image = Str::uuid() . '.' . $request->category_image->getClientOriginalExtension();
            $request->category_image->storeAs('public/category_images', $category_image);
            $data['category_image'] = asset('storage/category_images/'. $category_image);
        }
        // dd($data);
        Category::create($data);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Category $category)
    {
        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'category_name' => 'required',
            'category_image' => 'nullable',
            'description' => 'nullable',
            'cms' => 'nullable',
            'strategy' => 'nullable',
            'do_it' => 'nullable',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        if (!isset($data['cms'])) {
            $data['cms'] = 0;
        }
        if (!isset($data['strategy'])) {
            $data['strategy'] = 0;
        }
        if (!isset($data['do_it'])) {
            $data['do_it'] = 0;
        }
        if ($request->hasFile('category_image')) {
            $category_image = Str::uuid() . '.' . $request->category_image->getClientOriginalExtension();
            $request->category_image->storeAs('public/category_images', $category_image);
            $data['category_image'] = asset('storage/category_images/'. $category_image);
        }
        $category->update($data);

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function categoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Category::where('id', $id)->delete();
        }

        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Category::orderBy('order')->get();
        return view('category.order', compact('categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('category.order')->with('success', 'Category Ordered successfully');
    }

}
