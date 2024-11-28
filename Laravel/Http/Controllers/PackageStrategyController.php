<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\Package_strategy;
use App\Models\Content;
use App\Models\Sub_category;
use Illuminate\Http\Request;

class PackageStrategyController extends Controller
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
        return view('set_strategy.list');
    }

    public function setStrategyList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Package_strategy::join('packages', 'package_strategies.package_id', '=', 'packages.id')
            ->join('categories', 'package_strategies.category_id', '=', 'categories.id')
            ->join('sub_categories', 'package_strategies.subcategory_id', '=', 'sub_categories.id')
            ->join('contents', 'package_strategies.content_id', '=', 'contents.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "category_name") {
                    $values = $values->where('categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subcategory_name") {
                    $values = $values->where('sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "content_list") {
                    $values = $values->where('contents.content_name', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('package_strategies.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'package_strategies.*', 'packages.package_name',
                'categories.category_name',
                'sub_categories.subcategory_name',
                'contents.content_name'
            ])->map(function ($recode) {
                $recode->show = route('set_strategy.show', [$recode->id]);
                $recode->edit = route('set_strategy.edit', [$recode->id]);
                $recode->delete = route('set_strategy.delete', [$recode->id]);
                $content = Content::whereIn('id',explode(',',$recode->content_id))->pluck('content_name')->toArray();
                $recode->content_list = implode(', ',$content);
                return $recode;
            }),
        ]);
    }
    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $packages = Package::where('status', 1)->get();
        $categorys = Category::where('status', 1)->get();
        return view('set_strategy.create', compact('categorys', 'packages'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'package_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'content_id' => 'required',
        ]);

        $data['content_id'] = implode(',', $data['content_id']);
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Package_strategy::create($data);

        return redirect()->route('set_strategy.index')->with('success', 'Strategy created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show($id)
    {
        $package_strategy = Package_strategy::where('id', $id)->first();
        $package = Package::where('id', $package_strategy->package_id)->first();
        $category = Category::where('id', $package_strategy->category_id)->first();
        $sub_category = Sub_category::where('id', $package_strategy->subcategory_id)->first();
        $contents = Content::whereIn('id', explode(',',$package_strategy->content_id))->get();
        return view('set_strategy.show', compact('package_strategy','package','category','sub_category','contents'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit($id)
    {
        $package_strategy = Package_strategy::where('id', $id)->first();
        $packages = Package::where('status', 1)->get();
        $categorys = Category::where('status', 1)->get();
        return view('set_strategy.edit', compact('package_strategy','packages','categorys'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'package_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'content_id' => 'required',
            'status' => 'required',
        ]);
        $package_strategy = Package_strategy::where('id', $id)->first();

        $data['content_id'] = implode(',',$data['content_id']);
        $data['updated_by'] = auth()->user()->id;

        $package_strategy->update($data);

        return redirect()->route('set_strategy.index')->with('success', 'Strategy updated successfully');
    }

    public function destroy($id)
    {
        $package_strategy = Package_strategy::where('id', $id)->first();
        $package_strategy->delete();

        return redirect()->route('set_strategy.index')->with('success', 'Strategy deleted successfully');
    }

    public function setStrategyMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Package_strategy::where('id', $id)->delete();
        }

        return redirect()->route('set_strategy.index')->with('success', 'Package deleted successfully');
    }
}
