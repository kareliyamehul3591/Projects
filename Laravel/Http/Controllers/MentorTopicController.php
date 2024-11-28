<?php

namespace App\Http\Controllers;

use App\Models\Mentor_category;
use App\Models\Mentor_topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorTopicController extends Controller
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
        return view('mentor_topic.list');
    }

    public function mentorTopicList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Mentor_topic::join('mentor_categories', 'mentor_topics.mentor_category_id', '=', 'mentor_categories.id')
            ->join('mentor_sub_categories', 'mentor_topics.mentor_sub_category_id', '=', 'mentor_sub_categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "mentor_category_name") {
                    $values = $values->where('mentor_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "mentor_sub_category_name") {
                    $values = $values->where('mentor_sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('mentor_topics.' . $opt, 'LIKE', "%$q[$key]%");
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
                        'mentor_topics.*',
                        'mentor_categories.mentor_category_name',
                        'mentor_sub_categories.mentor_sub_category_name'
                    ])->map(function ($recode) {
                $recode->show = route('mentor_topic.show', [$recode->id]);
                $recode->edit = route('mentor_topic.edit', [$recode->id]);
                $recode->delete = route('mentor_topic.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $mentor_categories = Mentor_category::where('status', '1')->get();

        return view('mentor_topic.create', compact('mentor_categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mentor_category_id' => 'required',
            'mentor_sub_category_id' => 'required',
            'mentor_topic' => 'required|unique:mentor_topics,mentor_topic',
        ]);

        foreach (explode('+', $request->input('mentor_topic')) as $mentor_topic) {
            $validator = Validator::make(['mentor_topic' => $mentor_topic], [
                'mentor_topic' => 'required|unique:mentor_topics,mentor_topic',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        foreach (explode('+', $request->input('mentor_topic')) as $mentor_topic) {
            $data['mentor_topic'] = $mentor_topic;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Mentor_topic::create($data);
        }

        return redirect()->route('mentor_topic.index')->with('success', 'Mentor Topic created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Mentor_topic $mentor_topic)
    {
        $mentor_categories = Mentor_category::where('status', '1')->get();

        return view('mentor_topic.edit', compact('mentor_topic', 'mentor_categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Mentor_topic $mentor_topic)
    {
        $data = $request->validate([
            'mentor_category_id' => 'required',
            'mentor_sub_category_id' => 'required',
            'mentor_topic' => "required||unique:mentor_topics,mentor_topic,$mentor_topic->id,id",
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $mentor_topic->update($data);

        return redirect()->route('mentor_topic.index')->with('success', 'Mentor Topic updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Mentor_topic $mentor_topic)
    {
        return view('mentor_topic.show', compact('mentor_topic'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Mentor_topic $mentor_topic)
    {
        $mentor_topic->delete();

        return redirect()->route('mentor_topic.index')->with('success', 'Mentor Topic deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function mentorTopicMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Mentor_topic::where('id', $id)->delete();
        }

        return redirect()->route('mentor_topic.index')->with('success', 'Mentor Topic deleted successfully');
    }
}
