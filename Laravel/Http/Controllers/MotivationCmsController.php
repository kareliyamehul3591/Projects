<?php

namespace App\Http\Controllers;

use App\Exports\CmsExport;
use App\Imports\CmsImport;
use App\Models\Motivation_cms;
use App\Models\Motivation_cms_category;
use Illuminate\Http\Request;
use Str;
use Excel;

class MotivationCmsController extends Controller
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
        return view('motivation_cms.list');
    }

    public function cmsList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Motivation_cms;

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
                $recode->show = route('motivation_cms.show', [$recode->id]);
                $recode->edit = route('motivation_cms.edit', [$recode->id]);
                $recode->delete = route('motivation_cms.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $motivation_cms_categories = Motivation_cms_category::where('status',1)->get();
        return view('motivation_cms.create',compact('motivation_cms_categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'motivation_cms_category_id' => 'required',
            'motivation_cms_sub_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/motivation_cms', $image);
            $data['image'] = asset('storage/motivation_cms/' . $image);
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Motivation_cms::create($data);

        return redirect()->route('motivation_cms.index')->with('success', 'Motivation CMS created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Motivation_cms $motivation_cm)
    {
        return view('motivation_cms.show', compact('motivation_cm'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Motivation_cms $motivation_cm)
    {
        $motivation_cms_categories = Motivation_cms_category::where('status',1)->get();
        return view('motivation_cms.edit', compact('motivation_cm','motivation_cms_categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Motivation_cms $motivation_cm)
    {
        $data = $request->validate([
            'motivation_cms_category_id' => 'required',
            'motivation_cms_sub_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable',
            'status' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/motivation_cms', $image);
            $data['image'] = asset('storage/motivation_cms/' . $image);
        }

        $data['updated_by'] = auth()->user()->id;

        $motivation_cm->update($data);

        return redirect()->route('motivation_cms.index')->with('success', 'Motivation CMS updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Motivation_cms $motivation_cm)
    {
        $motivation_cm->delete();

        return redirect()->route('motivation_cms.index')->with('success', 'Motivation CMS deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function cmsMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Motivation_cms::where('id', $id)->delete();
        }

        return redirect()->route('motivation_cms.index')->with('success', 'Motivation CMS deleted successfully');
    }

    public function cmsBulkUpload()
    {
        return view('motivation_cms.cms_bulk_upload');
    }

    public function cmsBulkUploadExport()
    {
        return Excel::download(new CmsExport, 'Motivation CMS.xlsx');
    }

    public function cmsBulkUploadImport(Request $request)
    {
        $mimes = 'xlsx';
        $request->validate([
            'import' => 'required|mimes:' . $mimes . '|max:2048',
        ]);

        $import_name = 'test.xlsx';
        if ($request->hasFile('import')) {
            $import_name = Str::uuid() . '.' . $request->import->getClientOriginalExtension();
            $request->import->storeAs('public/import/motivation_cms', $import_name);
        }
        Excel::import(new CmsImport(1), storage_path('app/public/import/motivation_cms/' . $import_name));
        return redirect()->back()->with('success', 'Motivation CMS Data has been added successfully!');
    }

    public function order(Request $request)
    {
        $categorys = Motivation_cms_category::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'motivation_cms_category_id' => 'required',
                'motivation_cms_sub_category_id' => 'required',
            ]);
            $contents = Motivation_cms::where('motivation_cms_category_id', $data['motivation_cms_category_id'])
                ->where('motivation_cms_sub_category_id', $data['motivation_cms_sub_category_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $contents = [];
        }
        return view('motivation_cms.order', compact('data', 'contents', 'categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Motivation_cms::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('motivation_cms.order')->with('success', 'Motivation CMS Ordered successfully');
    }
}
