<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pack;
use App\Models\Package;
use App\Models\Package_module;
use App\Models\Package_price;
use App\Models\Package_strategy;
use App\Models\Package_subject_grade;
use App\Models\Student_package;
use App\Models\Subject_grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        return view('package.list',compact('status'));
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function packageList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Package::join('packs', 'packs.id', '=', 'packages.pack_id')
            ->join('package_modules', 'package_modules.id', '=', 'packages.package_module_id');
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "pack_name") {
                    $values = $values->where('packs.', 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_module_name") {
                    $values = $values->where('package_modules.', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'packages.*',
                    'package_modules.package_module_name',
                    'packs.pack_name',
                    ])->map(function ($recode) {
                $recode->show = route('package.show', [$recode->id]);
                $recode->edit = route('package.edit', [$recode->id]);
                $recode->delete = route('package.delete', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
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
        $packs = Pack::where('status', 1)->get();
        $modes = Package_module::where('status', 1)->get();
        return view('package.create', compact('packages','packs', 'modes'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'package_name' => 'required',
            'pack_id' => 'required',
            'package_module_id' => 'required',
            'expire_day' => 'required',
            'price' => 'required',
            'upgrable_to' => 'nullable',
            'target_question' => 'required|numeric',
            'target_cet' => 'required|numeric',
            'target_mock_test' => 'required|numeric',
            'target_benchmark_test' => 'required|numeric',
            'total_time' => 'required',
        ]);

        if (isset($data['upgrable_to'])) {
            $data['upgrable_to'] = implode(',', $data['upgrable_to']);
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        
        $package = Package::create($data);

        $package->package_target()->create([
            'target_question' => $data['target_question'],
            'target_cet' => $data['target_cet'],
            'target_mock_test' => $data['target_mock_test'],
            'target_benchmark_test' => $data['target_benchmark_test'],
            'total_time' => $data['total_time'],
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('package.index')->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Package $package)
    {
        $packages = Package::where('status', 1)->get();
        $packs = Pack::where('status', 1)->get();
        $modes = Package_module::where('status', 1)->get();
        return view('package.edit', compact('packs', 'package', 'modes','packages'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'package_name' => 'required',
            'pack_id' => 'required',
            'package_module_id' => 'required',
            'expire_day' => 'required',
            'price' => 'required',
            'upgrable_to' => 'nullable',
            'target_question' => 'required|numeric',
            'target_cet' => 'required|numeric',
            'target_mock_test' => 'required|numeric',
            'target_benchmark_test' => 'required|numeric',
            'total_time' => 'required',
            'status' => 'required',
        ]);

        if (isset($data['upgrable_to'])) {
            $data['upgrable_to'] = implode(',', $data['upgrable_to']);
        }
        $data['updated_by'] = auth()->user()->id;

        $package->update($data);

        $package->package_target()->update([
            'target_question' => $data['target_question'],
            'target_cet' => $data['target_cet'],
            'target_mock_test' => $data['target_mock_test'],
            'target_benchmark_test' => $data['target_benchmark_test'],
            'total_time' => $data['total_time'],
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('package.index')->with('success', 'Package updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Package $package)
    {
        return view('package.show', compact('package'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('package.index')->with('success', 'Package deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function packageMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Package::where('id', $id)->delete();
        }

        return redirect()->route('package.index')->with('success', 'Package deleted successfully');
    }

    public function order(Request $request)
    {
        $packages = Package::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'package_id' => 'required',
            ]);
            $package_subject_grades = Package_subject_grade::where('package_id', $data['package_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $package_subject_grades = [];
        }
        return view('package.subject_grade_order', compact('data', 'package_subject_grades', 'packages'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Package_subject_grade::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('subject_grade.order')->with('success', 'Subject Grade Ordered successfully');
    }

    /**
     * Show the form for creating a new record.
     */
    public function createstr()
    {
        $packages = Package::where('status', 1)->get();
        $categorys = Category::where('status', 1)->get();
        return view('package.createstr', compact('categorys', 'packages'));
    }

    /**
     * Store a newly created record.
     */
    public function storestr(Request $request)
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

        return redirect()->route('strategy.createstr')->with('success', 'Strategy created successfully.');
    }


    public function strList(Request $request, $id)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Package_strategy::join('categories', 'package_strategies.category_id', '=', 'categories.id')
            ->join('sub_categories', 'package_strategies.subcategory_id', '=', 'sub_categories.id')
            ->join('contents', 'package_strategies.content_id', '=', 'contents.id')
            ->where('package_strategies.package_id', $id);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "category_name") {
                    $values = $values->where('categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subcategory_name") {
                    $values = $values->where('sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "content_name") {
                    $values = $values->where('contents.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['package_strategies.*', 'categories.category_name', 'sub_categories.subcategory_name', 'contents.content_name'])->map(function ($recode) {
                $recode->delete = route('str.delete', [$recode->package_id, $recode->id]);
                return $recode;
            }),
        ]);
    }

    public function strdestroy(Package_strategy $package_strategy)
    {
        $package_strategy->delete();

        return redirect()->route('package.index')->with('success', 'Strategy deleted successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function strDelete($package_id, $id)
    {
        Package_strategy::where('id', $id)->delete();

        return redirect()->route('package.edit', $package_id)->with('success', 'Strategy deleted successfully');
    }

    public function strMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Package_strategy::where('id', $id)->delete();
        }

        return redirect()->route('package.index')->with('success', 'Package deleted successfully');
    }
}
