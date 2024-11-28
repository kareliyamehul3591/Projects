<?php

namespace App\Http\Controllers;

use App\Models\Sub_topic;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubTopicController extends Controller
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
        return view('sub_topic.list');
    }

    public function subTopicList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Sub_topic::join('subjects', 'sub_topics.subject_id', '=', 'subjects.id')
            ->join('topics', 'sub_topics.topic_id', '=', 'topics.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "topic_name") {
                    $values = $values->where('topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('sub_topics.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['sub_topics.*', 'subjects.subject_name','topics.topic_name'])->map(function ($recode) {
                $recode->show = route('sub_topic.show', [$recode->id]);
                $recode->edit = route('sub_topic.edit', [$recode->id]);
                $recode->delete = route('sub_topic.delete', [$recode->id]);
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

        return view('sub_topic.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sub_topic_name' => 'required',
            'topic_id' => 'required',
            'subject_id' => 'required',
        ]);

        foreach (explode('+', $request->input('sub_topic_name')) as $sub_topic_name) {
            $data['sub_topic_name'] = $sub_topic_name;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Sub_topic::create($data);
        }

        return redirect()->route('sub_topic.index')->with('success', 'Sub Topic created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Sub_topic $sub_topic)
    {
        $subjects = Subject::where('status', '1')->get();

        return view('sub_topic.edit', compact('sub_topic', 'subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Sub_topic $sub_topic)
    {
        $data = $request->validate([
            'sub_topic_name' => 'required',
            'topic_id' => 'required',
            'subject_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $sub_topic->update($data);

        return redirect()->route('sub_topic.index')->with('success', 'Sub Topic updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Sub_topic $sub_topic)
    {
        return view('sub_topic.show', compact('sub_topic'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Sub_topic $sub_topic)
    {
        $sub_topic->delete();

        return redirect()->route('sub_topic.index')->with('success', 'Sub Topic deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function subTopicMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Sub_topic::where('id', $id)->delete();
        }

        return redirect()->route('sub_topic.index')->with('success', 'Sub Topic deleted successfully');
    }

}
