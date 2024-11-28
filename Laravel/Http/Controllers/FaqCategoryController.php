<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq_category;

class FaqCategoryController extends Controller
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
        return view('faq_category.list');
    }

    public function faqCategoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Faq_category;
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
                $recode->show = route('faq_category.show', [$recode->id]);
                $recode->edit = route('faq_category.edit', [$recode->id]);
                $recode->delete = route('faq_category.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('faq_category.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'faq_category_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Faq_category::create($data);

        return redirect()->route('faq_category.index')->with('success', 'FAQ Category created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Faq_category $faq_category)
    {
        return view('faq_category.show', compact('faq_category'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Faq_category $faq_category)
    {
        return view('faq_category.edit', compact('faq_category'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Faq_category $faq_category)
    {
        $data = $request->validate([
            'faq_category_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $faq_category->update($data);

        return redirect()->route('faq_category.index')->with('success', 'FAQ Category updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Faq_category $faq_category)
    {
        $faq_category->delete();

        return redirect()->route('faq_category.index')->with('success', 'FAQ Category deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function faqCategoryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Faq_category::where('id', $id)->delete();
        }

        return redirect()->route('faq_category.index')->with('success', 'FAQ Category deleted successfully');
    }

}
