<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Concept;
use App\Models\Concept_document_note;
use App\Models\Concept_video;
use App\Models\Subject;
use App\Models\Subject_grade;
use Illuminate\Http\Request;
use Str;

class ConceptController extends Controller
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
        return view('concept.list');
    }

    public function conceptList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Concept::join('subjects', 'concepts.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'concepts.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'concepts.chapter_id', '=', 'chapters.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['concepts.*', 'subjects.subject_name', 'subject_grades.subject_grade_name', 'chapters.chapter_name'])->map(function ($recode) {
                $recode->show = route('concept.show', [$recode->id]);
                $recode->edit = route('concept.edit', [$recode->id]);
                $recode->delete = route('concept.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $subjects = Subject::where('status', 1)->get();
        return view('concept.create', compact('subjects'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'concept_name' => 'required',
            'concept_nutshell' => 'nullable',
            'video_name.*' => 'nullable',
            'video.*' => 'nullable',
        ]);

        foreach (explode('+', $request->input('concept_name')) as $concept_name) {
            $data['concept_name'] = $concept_name;

            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            $concept = Concept::create($data);

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                foreach ($video as $key => $video_na) {
                    $name = Str::uuid() . '.' . $video_na->getClientOriginalExtension();
                    $concept->video()->create([
                        'video' => asset('storage/concept/' . $concept->id . '/video/'. $name),
                        'video_name' => $data['video_name'][$key],
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ]);
                    $video_na->storeAs('public/concept/' . $concept->id . '/video', $name);
                }
            }
        }
        return redirect()->route('concept.index')->with('success', 'Concept created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Concept $concept)
    {
        $subjects = Subject::where('status', 1)->get();
        $subject_grades = Subject_grade::where('status', 1)->get();
        $chapters = Chapter::where('status', 1)->get();
        $concept_videos = Concept_video::where('status', 1)->get();

        return view('concept.edit', compact('subject_grades', 'subjects', 'concept', 'chapters', 'concept_videos'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Concept $concept)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'concept_name' => 'required',
            'concept_nutshell' => 'nullable',
            'concept_video_id.*' => 'nullable',
            'video_name.*' => 'nullable',
            'video.*' => 'nullable',
            'status' => 'required',
        ]);
        $data['updated_by'] = auth()->user()->id;

        $concept->update($data);

        if(implode(',',$data['concept_video_id']) != "0"){
            $concept_video_id = [];
            foreach ($data['concept_video_id'] as $key => $id) {
                $upData = [
                    'video_name' => $data['video_name'][$key],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
                if($request->hasFile('video.'.$key)){
                    $video = $request->file('video.'.$key);
                    $name = Str::uuid() . '.' . $video->getClientOriginalExtension();
                    $video->storeAs('public/concept/' . $concept->id . '/video', $name);
                    $upData['video'] = asset('storage/concept/' . $concept->id . '/video/'. $name);
                }
                if($id == 0){
                    $concept_video = $concept->video()->create($upData);
                } else {
                    $concept_video = $concept->video()->find($id);
                    $concept_video->update($upData);
                }
                $concept_video_id[] = $concept_video->id;
            }
            $concept->video()->whereNotIn('id', $concept_video_id)->delete();
        }


        return redirect()->route('concept.index')->with('success', 'Concept updated successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Concept $concept)
    {
        $subject = Subject::where('id', $concept->subject_id)->first();
        $subject_grade = Subject_grade::where('id', $concept->subject_grade_id)->first();
        $chapter = Chapter::where('id', $concept->chapter_id)->first();
        $concept_video = Concept_video::where('id', $concept->video_id)->first();

        return view('concept.show', compact('subject_grade', 'subject', 'concept', 'chapter', 'concept_video'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Concept $concept)
    {
        $concept->delete();
        return redirect()->route('concept.index')->with('success', 'Concept deleted successfully');
    }

    public function conceptVideoDelete(Concept_video $concept_video)
    {
        $concept_video->delete();
        return redirect()->route('concept.edit',$concept_video->concept->id)->with('success', 'Concept Video deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function conceptMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Concept::where('id', $id)->delete();
        }

        return redirect()->route('concept.index')->with('success', 'Concept deleted successfully');
    }

    public function order(Request $request)
    {
        $subjects = Subject::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'subject_id' => 'required',
                'subject_grade_id' => 'required',
                'chapter_id' => 'required',
            ]);
            $concepts = Concept::where('subject_id', $data['subject_id'])
                ->where('subject_grade_id', $data['subject_grade_id'])
                ->where('chapter_id', $data['chapter_id'])
                ->orderBy('order')->get();
        } else {
            $data = [];
            $concepts = [];
        }
        return view('concept.order', compact('data', 'concepts', 'subjects'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Concept::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('concept.order')->with('success', 'Concept Order successfully');
    }

}
