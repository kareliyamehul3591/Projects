<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\Sub_category;
use Illuminate\Http\Request;

class ContentController extends Controller
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
        return view('content.list');
    }

    public function contentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Content::join('categories', 'contents.category_id', '=', 'categories.id')
            ->join('sub_categories', 'contents.subcategory_id', '=', 'sub_categories.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "category_name") {
                    $values = $values->where('categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subcategory_name") {
                    $values = $values->where('sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('contents.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['contents.*', 'categories.category_name', 'sub_categories.subcategory_name'])->map(function ($recode) {
                $recode->show = route('content.show', [$recode->id]);
                $recode->edit = route('content.edit', [$recode->id]);
                $recode->delete = route('content.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $categorys = Category::where('status', '1')->get();

        return view('content.create', compact('categorys'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'content_name' => 'required',
            'content' => 'nullable',
            'need_login' => 'nullable',
            'common_user' => 'nullable',
            'share_to_friend' => 'nullable',
        ]);

        if (isset($request->need_login)) {
            $data['need_login'] = 1;
        } else {
            $data['need_login'] = 0;
        }

        if (isset($request->common_user)) {
            $data['common_user'] = 1;
        } else {
            $data['common_user'] = 0;
        }

        if (isset($request->share_to_friend)) {
            $data['share_to_friend'] = 1;
        } else {
            $data['share_to_friend'] = 0;
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Content::create($data);

        return redirect()->route('content.index')->with('success', 'Content created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Content $content)
    {
        $categorys = Category::where('status', '1')->get();

        return view('content.edit', compact('content', 'categorys'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'content_name' => 'required',
            'content' => 'nullable',
            'status' => 'required',
            'need_login' => 'nullable',
            'common_user' => 'nullable',
            'share_to_friend' => 'nullable',
        ]);

        if (isset($request->need_login)) {
            $data['need_login'] = 1;
        } else {
            $data['need_login'] = 0;
        }

        if (isset($request->common_user)) {
            $data['common_user'] = 1;
        } else {
            $data['common_user'] = 0;
        }

        if (isset($request->share_to_friend)) {
            $data['share_to_friend'] = 1;
        } else {
            $data['share_to_friend'] = 0;
        }

        $data['updated_by'] = auth()->user()->id;

        $content->update($data);

        return redirect()->route('content.index')->with('success', 'Content updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Content $content)
    {
        $categorys = Category::where('id', $content->category_id)->first();
        $sub_categorys = Sub_category::where('id', $content->subcategory_id)->first();

        return view('content.show', compact('content', 'categorys', 'sub_categorys'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()->route('content.index')->with('success', 'Content deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function contentMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Content::where('id', $id)->delete();
        }

        return redirect()->route('content.index')->with('success', 'Content deleted successfully');
    }

    public function order(Request $request)
    {
        $categorys = Category::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'category_id' => 'required',
                'subcategory_id' => 'required',
            ]);
            $contents = Content::where('category_id', $data['category_id'])
                ->where('subcategory_id', $data['subcategory_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $contents = [];
        }
        return view('content.order', compact('data', 'contents', 'categorys'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Content::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('content.order')->with('success', 'Content Ordered successfully');
    }
}
