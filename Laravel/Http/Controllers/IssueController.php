<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Subject;
use Illuminate\Http\Request;
use Str;

class IssueController extends Controller
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
        return view('issue.list');
    }

    public function issueList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Issue::join('subjects', 'issues.subject_id', '=', 'subjects.id')
            ->join('topics', 'issues.topic_id', '=', 'topics.id')
            ->join('sub_topics', 'issues.sub_topic_id', '=', 'sub_topics.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "topic_name") {
                    $values = $values->where('topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "sub_topic_name") {
                    $values = $values->where('sub_topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('issues.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'issues.*',
                    'subjects.subject_name',
                    'topics.topic_name',
                    'sub_topics.sub_topic_name'
                    ])->map(function ($recode) {
                $recode->show = route('issue.show', [$recode->id]);
                $recode->edit = route('issue.edit', [$recode->id]);
                $recode->delete = route('issue.delete', [$recode->id]);
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

        return view('issue.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'background' => 'required',
            'sub_topic_id' => 'required',
            'topic_id' => 'required',
            'subject_id' => 'required',
            'issue_name' => 'required',
            'issue_image' => 'required',
        ]);

        if ($request->hasFile('issue_image')) {
            $issue_image = Str::uuid() . '.' . $request->issue_image->getClientOriginalExtension();
            $request->issue_image->storeAs('public/issue_images', $issue_image);
            $data['issue_image'] = asset('storage/issue_images/'. $issue_image);
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Issue::create($data);

        return redirect()->route('issue.index')->with('success', 'Issue created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Issue $issue)
    {
        $subjects = Subject::where('status', '1')->get();

        return view('issue.edit', compact('issue', 'subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Issue $issue)
    {
        $data = $request->validate([
            'background' => 'required',
            'sub_topic_id' => 'required',
            'topic_id' => 'required',
            'subject_id' => 'required',
            'issue_name' => 'required',
            'issue_image' => 'required',
            'status' => 'required',
        ]);

        if ($request->hasFile('issue_image')) {
            $issue_image = Str::uuid() . '.' . $request->issue_image->getClientOriginalExtension();
            $request->issue_image->storeAs('public/issue_images', $issue_image);
            $data['issue_image'] = asset('storage/issue_images/'. $issue_image);
        }

        $data['updated_by'] = auth()->user()->id;

        $issue->update($data);

        return redirect()->route('issue.index')->with('success', 'Issue updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Issue $issue)
    {
        return view('issue.show', compact('issue'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('issue.index')->with('success', 'Issue deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function subTopicMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Issue::where('id', $id)->delete();
        }

        return redirect()->route('issue.index')->with('success', 'Issue deleted successfully');
    }

}
