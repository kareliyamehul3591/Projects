<?php

namespace App\Http\Controllers;

use App\Models\Daily_news;
use App\Models\Daily_news_layout;
use App\Models\Subject;
use Illuminate\Http\Request;
use Str;
use PDF;

class DailyNewsController extends Controller
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
        return view('daily_news.list');
    }

    public function dailyNewsList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Daily_news::leftJoin('subjects', 'daily_news.subject_id', '=', 'subjects.id')
            ->leftJoin('topics', 'daily_news.topic_id', '=', 'topics.id')
            ->leftJoin('sub_topics', 'daily_news.sub_topic_id', '=', 'sub_topics.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "topic_name") {
                    $values = $values->where('topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "sub_topic_name") {
                    $values = $values->where('sub_topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('daily_news.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'daily_news.*',
                    'subjects.subject_name',
                    'topics.topic_name',
                    'sub_topics.sub_topic_name',
                ])->map(function ($recode) {
                $recode->show = route('daily_news.show', [$recode->id]);
                $recode->edit = route('daily_news.edit', [$recode->id]);
                $recode->delete = route('daily_news.delete', [$recode->id]);
                $recode->dateOfNews = $recode->date->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $subjects = Subject::where('status', '1')->get();

        return view('daily_news.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required',
            'detail' => 'required',
            'image' => 'nullable',
            'date' => 'required',
            'tag' => 'required',
            'issue_id' => 'nullable',
            'sub_topic_id' => 'nullable',
            'topic_id' => 'nullable',
            'subject_id' => 'nullable',
            'add_notes' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/news/images', $image);
            $data['image'] = asset('storage/news/images/' . $image);
        }

        $data['tag'] = implode(',', explode('+', $request->input('tag')));
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Daily_news::create($data);

        return redirect()->route('daily_news.index')->with('success', 'Daily News created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Daily_news $daily_news)
    {
        $subjects = Subject::where('status', '1')->get();

        return view('daily_news.edit', compact('daily_news', 'subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Daily_news $daily_news)
    {
        $data = $request->validate([
            'content' => 'required',
            'detail' => 'required',
            'image' => 'nullable',
            'date' => 'required',
            'tag' => 'required',
            'issue_id' => 'nullable',
            'sub_topic_id' => 'nullable',
            'topic_id' => 'nullable',
            'subject_id' => 'nullable',
            'add_notes' => 'nullable',
            'status' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/news/images', $image);
            $data['image'] = asset('storage/news/images/' . $image);
        }

        if (isset($request->add_notes)) {
            $data['add_notes'] = 1;
        } else {
            $data['add_notes'] = 0;
            $data['issue_id'] = 0;
            $data['sub_topic_id'] = 0;
            $data['topic_id'] = 0;
            $data['subject_id'] = 0;
        }

        $data['tag'] = implode(',', explode('+', $request->input('tag')));
        $data['updated_by'] = auth()->user()->id;

        $daily_news->update($data);

        return redirect()->route('daily_news.index')->with('success', 'Daily news updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Daily_news $daily_news)
    {
        return view('daily_news.show', compact('daily_news'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Daily_news $daily_news)
    {
        $daily_news->delete();

        return redirect()->route('daily_news.index')->with('success', 'Daily news deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function dailyNewsMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Daily_news::where('id', $id)->delete();
        }

        return redirect()->route('daily_news.index')->with('success', 'Daily news deleted successfully');
    }

    public function dailyNewsletter()
    {
        return view('daily_news.newsletter');
    }

    public function dailyNewsletterList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Daily_news_layout::groupBy('date');

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
                $recode->dateFormat = $recode->date->format('d-m-Y');
                $recode->show = route('daily.newsletter.show', [$recode->id]);
                $recode->edit = route('daily.newsletter.edit', [$recode->id]);
                $recode->delete = route('daily.newsletter.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    public function createDailyNewsletter(Request $request)
    {
        $data = [];
        $daily_news = [];
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'date' => 'required',
            ]);
            $daily_news = Daily_news::where('date', $data['date'])
                ->orderBy('order')
                ->get();
        }
        return view('daily_news.order', compact('data', 'daily_news'));
    }

    public function createDailyNewsletterSave(Request $request)
    {
        $rules = [
            'date' => 'required',
            'layout.*' => 'required',
            'news.*.*' => 'required',
        ];
        $data = $request->validate($rules);
        foreach ($data['layout'] as $key => $layout) {
            $rules['news.' . $key . '.' . $layout . '.*'] = 'required';
        }
        $data = $request->validate($rules);
        foreach ($data['layout'] as $key => $layout) {
            Daily_news_layout::create([
                'date' => $data['date'],
                'layout' => $layout,
                'news_id' => implode(',', $data['news'][$key][$layout]),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }
        return redirect()->route('daily.newsletter')->with('success', 'Daily Newsletter Generate !');
    }

    public function dailyNewsletterShow(Daily_news_layout $daily_news_layout)
    {
        $dailyNewsLayouts = Daily_news_layout::where('date', $daily_news_layout->date)->orderBy('id')->get();
        $DownloadAsPdf = true;
        return view('daily_news.newsletter_show', compact('daily_news_layout', 'dailyNewsLayouts', 'DownloadAsPdf'));
    }

    public function dailyNewsletterPdf(Daily_news_layout $daily_news_layout)
    {
        $dailyNewsLayouts = Daily_news_layout::where('date', $daily_news_layout->date)->orderBy('id')->get();
        $DownloadAsPdf = false;

        $pdf = PDF::loadView('daily_news.newsletter_show', compact('daily_news_layout', 'dailyNewsLayouts', 'DownloadAsPdf'));
        return $pdf->download('Newsletter.pdf');
    }

    public function dailyNewsletterEdit(Request $request, Daily_news_layout $daily_news_layout)
    {
        $dailyNewsLayouts = Daily_news_layout::where('date',$daily_news_layout->date)->orderBy('id')->get();
        $daily_news = Daily_news::where('date', $daily_news_layout->date)->orderBy('order')->get();

        return view('daily_news.newsletter_edit',compact('daily_news_layout', 'dailyNewsLayouts','daily_news'));
    }

    public function dailyNewsletterUpdate(Request $request, Daily_news_layout $daily_news_layout)
    {
        Daily_news_layout::where('date',$daily_news_layout->date)->delete();
        $data = $request->all();
        foreach ($data['layout'] as $key => $layout) {
            Daily_news_layout::create([
                'date' => $daily_news_layout->date,
                'layout' => $layout,
                'news_id' => implode(',', $data['news'][$key][$layout]),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }
        
        return redirect()->route('daily.newsletter')->with('success', 'Daily Newsletter Update Successfully !');
    }

    /**
     * Remove the specified record from storage.
     */
    public function delete(Daily_news_layout $daily_news_layout)
    {
        Daily_news_layout::where('date',$daily_news_layout->date)->delete();

        return redirect()->route('daily.newsletter')->with('success', 'Daily Newsletter deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function dailyNewsletterMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $dailyNewsLayout = Daily_news_layout::where('id', $id)->first();
            $daily_news_layouts = Daily_news_layout::where('date',$dailyNewsLayout->date)->get();
            foreach($daily_news_layouts as $daily_news_layout)
            {
                $daily_news_layout->delete();
            }
        }

        return redirect()->route('daily_news.index')->with('success', 'Daily news deleted successfully');
    }
}
