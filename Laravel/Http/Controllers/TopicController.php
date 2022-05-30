<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
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
        return view('topic.list');
    }

    public function topicList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Topic::join('subjects', 'topics.subject_id', '=', 'subjects.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('topics.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['topics.*', 'subjects.subject_name'])->map(function ($recode) {
                $recode->show = route('topic.show', [$recode->id]);
                $recode->edit = route('topic.edit', [$recode->id]);
                $recode->delete = route('topic.delete', [$recode->id]);
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

        return view('topic.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_name' => 'required|unique:topics,topic_name',
            'subject_id' => 'required',
        ]);

        foreach (explode('+', $request->input('topic_name')) as $topic_name) {
            $validator = Validator::make(['topic_name' => $topic_name], [
                'topic_name' => 'required|unique:topics,topic_name',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        foreach (explode('+', $request->input('topic_name')) as $topic_name) {
            $data['topic_name'] = $topic_name;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Topic::create($data);
        }

        return redirect()->route('topic.index')->with('success', 'Topic created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Topic $topic)
    {
        $subjects = Subject::where('status', '1')->get();

        return view('topic.edit', compact('topic', 'subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'topic_name' => "required|unique:topics,topic_name,$topic->id,id",
            'subject_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $topic->update($data);

        return redirect()->route('topic.index')->with('success', 'Topic updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Topic $topic)
    {
        return view('topic.show', compact('topic'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('topic.index')->with('success', 'Topic deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function topicMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Topic::where('id', $id)->delete();
        }

        return redirect()->route('topic.index')->with('success', 'Topic deleted successfully');
    }

}
