<?php

namespace App\Http\Controllers;

use App\Models\Motivation_cms_category;
use App\Models\Motivation_cms_sub_category;
use Illuminate\Http\Request;

class MotivationCmsSubCategoryController extends Controller
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
        return view('motivation_cms_sub_category.list');
    }

    public function cmsSubCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Motivation_cms_sub_category::join('motivation_cms_categories', 'motivation_cms_sub_categories.motivation_cms_category_id', '=', 'motivation_cms_categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "motivation_cms_category_name") {
                    $values = $values->where('motivation_cms_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('motivation_cms_sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['motivation_cms_sub_categories.*', 'motivation_cms_categories.motivation_cms_category_name'])->map(function ($recode) {
                $recode->show = route('motivation_cms_sub_category.show', [$recode->id]);
                $recode->edit = route('motivation_cms_sub_category.edit', [$recode->id]);
                $recode->delete = route('motivation_cms_sub_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $categories = Motivation_cms_category::where('status', 1)->get();
        return view('motivation_cms_sub_category.create', compact('categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'motivation_cms_sub_category_name' => 'required',
            'motivation_cms_category_id' => 'required',
        ]);
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Motivation_cms_sub_category::create($data);

        return redirect()->route('motivation_cms_sub_category.index')->with('success', 'Motivation CMS Sub Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Motivation_cms_sub_category $motivation_cms_sub_category)
    {
        return view('motivation_cms_sub_category.show', compact('motivation_cms_sub_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Motivation_cms_sub_category $motivation_cms_sub_category)
    {
        $categories = Motivation_cms_category::where('status', 1)->get();

        return view('motivation_cms_sub_category.edit', compact('motivation_cms_sub_category', 'categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Motivation_cms_sub_category $motivation_cms_sub_category)
    {
        $data = $request->validate([
            'motivation_cms_sub_category_name' => 'required',
            'motivation_cms_category_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $motivation_cms_sub_category->update($data);

        return redirect()->route('motivation_cms_sub_category.index')->with('success', 'Motivation CMS Sub Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Motivation_cms_sub_category $motivation_cms_sub_category)
    {
        $motivation_cms_sub_category->delete();

        return redirect()->route('motivation_cms_sub_category.index')->with('success', 'Motivation CMS Sub Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function cmsSubCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Motivation_cms_sub_category::where('id', $id)->delete();
        }

        return redirect()->route('cms_sub_category.index')->with('success', 'Motivation CMS Sub Category deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Motivation_cms_category::where('status', 1)->get();

        if ($request->isMethod('post')) 
        {
            $data = $request->validate([
                'motivation_cms_category_id' => 'required',
            ]);

            $subcategorys = Motivation_cms_sub_category::where('motivation_cms_category_id', $data['motivation_cms_category_id'])
                ->orderBy('order')
                ->get();

        } 
        else 
        {
            $data = [];
            $subcategorys = [];
        }
        return view('motivation_cms_sub_category.order', compact('data', 'categorys', 'subcategorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Motivation_cms_sub_category::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('motivation_cms_sub_category.order')->with('success', 'Motivation CMS Sub Category Ordered successfully');
    }
}
