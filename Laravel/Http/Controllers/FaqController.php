<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Faq_category;
use Illuminate\Http\Request;

class FaqController extends Controller
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
        return view('faq.list');
    }

    public function faqList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Faq::join('faq_categories','faqs.faq_category_id','=','faq_categories.id');
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "faq_category_name") {
                    $values = $values->where('faq_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('faqs.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['faqs.*','faq_categories.faq_category_name'])->map(function ($recode) {
                $recode->show = route('faq.show', [$recode->id]);
                $recode->edit = route('faq.edit', [$recode->id]);
                $recode->delete = route('faq.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $faq_categorys = Faq_category::where('status',1)->get();
        return view('faq.create',compact('faq_categorys'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'faq_category_id' => 'required',
            'show_in' => 'required',
        ]);
        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Faq::create($data);

        return redirect()->route('faq.index')->with('success', 'Faq created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */

    public function edit(Faq $faq)
    {
        $faq_categorys = Faq_category::where('status',1)->get();
        return view('faq.edit', compact('faq','faq_categorys'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'faq_category_id' => 'required',
            'show_in' => 'required',
            'status' => 'required',
        ]);
        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['updated_by'] = auth()->user()->id;

        $faq->update($data);

        return redirect()->route('faq.index')->with('success', 'Faq Update successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Faq $faq)
    {
        $faq_category = Faq_category::where('id', $faq->faq_category_id)->first();
        return view('faq.show', compact('faq','faq_category'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'Faq deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function faqMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Faq::where('id', $id)->delete();
        }

        return redirect()->route('faq.index')->with('success', 'Faq deleted successfully');
    }

    public function order(Request $request)
    {
        $faq_categorys = Faq_category::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'faq_category_id' => 'required',
            ]);
            $faqs = Faq::where('faq_category_id', $data['faq_category_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $faqs = [];
        }
        return view('faq.order', compact('data', 'faq_categorys', 'faqs'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Faq::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('faq.order')->with('success', 'Faq Ordered successfully');
    }
}
