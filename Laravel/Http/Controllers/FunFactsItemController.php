<?php

namespace App\Http\Controllers;

use App\Exports\FunItemExport;
use App\Imports\ItemImport;
use App\Models\Fun_facts_category;
use App\Models\Fun_facts_item;
use Illuminate\Http\Request;
use Str;
use Excel;

class FunFactsItemController extends Controller
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
        return view('fun_facts_item.list');
    }

    public function funFactsItemList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Fun_facts_item;

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
                $recode->show = route('fun_facts_item.show', [$recode->id]);
                $recode->edit = route('fun_facts_item.edit', [$recode->id]);
                $recode->delete = route('fun_facts_item.delete', [$recode->id]);
                $recode->dateGenerate = $recode->date->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $fun_facts_categories = Fun_facts_category::where('status',1)->get();
        return view('fun_facts_item.create',compact('fun_facts_categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fun_facts_category_id' => 'required',
            'fun_facts_sub_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'date' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/fun_fact_items', $image);
            $data['image'] = asset('storage/fun_fact_items/' . $image);
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Fun_facts_item::create($data);

        return redirect()->route('fun_facts_item.index')->with('success', 'Fun Facts Item created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Fun_facts_item $fun_facts_item)
    {
        return view('fun_facts_item.show', compact('fun_facts_item'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Fun_facts_item $fun_facts_item)
    {
        $fun_facts_categories = Fun_facts_category::where('status',1)->get();
        return view('fun_facts_item.edit', compact('fun_facts_item','fun_facts_categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Fun_facts_item $fun_facts_item)
    {
        $data = $request->validate([
            'fun_facts_category_id' => 'required',
            'fun_facts_sub_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable',
            'date' => 'required',
            'status' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/fun_fact_items', $image);
            $data['image'] = asset('storage/fun_fact_items/' . $image);
        }

        $data['updated_by'] = auth()->user()->id;

        $fun_facts_item->update($data);

        return redirect()->route('fun_facts_item.index')->with('success', 'Fun Facts Item updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Fun_facts_item $fun_facts_item)
    {
        $fun_facts_item->delete();

        return redirect()->route('fun_facts_item.index')->with('success', 'Fun Facts Item deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function funFactsItemMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Fun_facts_item::where('id', $id)->delete();
        }

        return redirect()->route('fun_facts_item.index')->with('success', 'Fun Facts Item deleted successfully');
    }

    public function itemBulkUpload()
    {
        return view('fun_facts_item.fun_facts_item_bulk_upload');
    }

    public function itemBulkUploadExport()
    {
        return Excel::download(new FunItemExport, 'Fun Items.xlsx');
    }

    public function itemBulkUploadImport(Request $request)
    {
        $mimes = 'xlsx';
        $request->validate([
            'import' => 'required|mimes:' . $mimes . '|max:2048',
        ]);

        $import_name = 'test.xlsx';
        if ($request->hasFile('import')) {
            $import_name = Str::uuid() . '.' . $request->import->getClientOriginalExtension();
            $request->import->storeAs('public/import/item', $import_name);
        }
        Excel::import(new ItemImport(1), storage_path('app/public/import/item/' . $import_name));
        return redirect()->back()->with('success', 'Item Data has been added successfully!');
    }

    public function order(Request $request)
    {
        $categorys = Fun_facts_category::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'fun_facts_category_id' => 'required',
                'fun_facts_sub_category_id' => 'required',
            ]);
            $contents = Fun_facts_item::where('fun_facts_category_id', $data['fun_facts_category_id'])
                ->where('fun_facts_sub_category_id', $data['fun_facts_sub_category_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $contents = [];
        }
        return view('fun_facts_item.order', compact('data', 'contents', 'categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Fun_facts_item::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('fun_facts_item.order')->with('success', 'Fun Facts Item Ordered successfully');
    }
}
