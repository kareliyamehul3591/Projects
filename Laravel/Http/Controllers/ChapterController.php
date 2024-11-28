<?php

namespace App\Http\Controllers;

use App\Models\Advanced_study;
use App\Models\Chapter;
use App\Models\Chapter_document_note;
use App\Models\Concept;
use App\Models\Concept_evaluation_test;
use App\Models\Concept_evaluation_test_question;
use App\Models\Detailed_study;
use App\Models\Sms_template;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Subject_grade;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class ChapterController extends Controller
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
        return view('chapter.list');
    }

    public function chapterList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Chapter::join('subjects', 'chapters.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'chapters.subject_grade_id', '=', 'subject_grades.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'chapters.*',
                    'subjects.subject_name',
                    'subject_grades.subject_grade_name'
                ])->map(function ($recode) {
                $recode->show = route('chapter.show', [$recode->id]);
                $recode->edit = route('chapter.edit', [$recode->id]);
                $recode->delete = route('chapter.delete', [$recode->id]);

                $cet = Concept_evaluation_test::where('chapter_id',$recode->id)->pluck('id')->toArray();
                $CETQueCount = Concept_evaluation_test_question::whereIn('concept_evaluation_test_id',$cet)->count();
                $recode->CETQueCount = $CETQueCount;

                $DP = Detailed_study::where('chapter_id',$recode->id)->sum('total_question');
                $recode->DP = $DP;

                $AP = Advanced_study::where('chapter_id',$recode->id)->sum('total_question');
                $recode->AP = $AP;

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
        $concept_evaluation_tests = Concept_evaluation_test::where('status', 1)->get();
        $concepts = Concept::where('status', 1)->get();
        return view('chapter.create', compact('subjects', 'concept_evaluation_tests','concepts'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'chapter_name' => 'required',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_description' => 'nullable',
            'analysis_importance' => 'nullable',
            'additional_information' => 'nullable',
            'external_resources' => 'nullable',
            'priority_icon' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'chapter_pdf' => 'nullable',
            'concept_evaluation_test_id' => 'nullable',
            'document_note_id.*' => 'nullable',
            'description.*' => 'nullable',
            'concept_id.*' => 'nullable',
            'document_note.*' => 'nullable',
            'document_note_id_podcast.*' => 'nullable',
            'description_podcast.*' => 'nullable',
            'concept_id_podcast.*' => 'nullable',
            'document_note_podcast.*' => 'nullable',
        ]);

        if ($request->hasFile('priority_icon')) {
            $priority_icon = Str::uuid() . '.' . $request->priority_icon->getClientOriginalExtension();
            $data['priority_icon'] = $priority_icon;
        }
        foreach (explode('+', $request->input('chapter_name')) as $chapter_name) {
            $data['chapter_name'] = $chapter_name;
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            $chapter = Chapter::create($data);

            if ($request->hasFile('priority_icon')) {
                $request->priority_icon->storeAs('public/chapter/' . $chapter->id, $priority_icon);
                $chapter->update([
                    'priority_icon' => asset('storage/chapter/' . $chapter->id . '/' . $priority_icon),
                ]);
            }
        }
        // foreach($data['document_note_id'] as $key){
        //     $upData = [
        //         'category' => 'Document Note',
        //         'description' => $data['description'][$key],
        //         'concept_id' => (isset($data['concept_id'][$key])) ? $data['concept_id'][$key] : '0',
        //         'created_by' => auth()->user()->id,
        //         'updated_by' => auth()->user()->id,
        //     ];
        //     if($request->hasFile('document_note.'.$key)){
        //         $document_note = $request->file('document_note.'.$key);
        //         $name = Str::uuid() . '.' . $document_note->getClientOriginalExtension();
        //         $document_note->storeAs('public/chapter/' . $chapter->id . '/document', $name);
        //         $upData['document_note'] = asset('storage/chapter/' . $chapter->id . '/document/'. $name);
        //     }
        //     $chapter->document_note()->create($upData);
        // }
        if ($request->hasFile('document_note')) {
            $document_note = $request->file('document_note');
            foreach ($document_note as $key => $document) {
                $name = Str::uuid() . '.' . $document->getClientOriginalExtension();
                $chapter->document_note()->create([
                    'category' => 'Document Note',
                    'document_note' => asset('storage/chapter/' . $chapter->id . '/document/'. $name),
                    'description' => $data['description'][$key],
                    'concept_id' => (isset($data['concept_id'][$key])) ? $data['concept_id'][$key] : '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                $document->storeAs('public/chapter/' . $chapter->id . '/document', $name);
            }
        }

        // foreach($data['document_note_id_podcast'] as $key){
        //     $upData = [
        //         'category' => 'Podcast',
        //         'description' => $data['description_podcast'][$key],
        //         'concept_id' => (isset($data['concept_id_podcast'][$key])) ? $data['concept_id_podcast'][$key] : '0',
        //         'created_by' => auth()->user()->id,
        //         'updated_by' => auth()->user()->id,
        //     ];
        //     if($request->hasFile('document_note_podcast.'.$key)){
        //         $document_note_podcast = $request->file('document_note_podcast.'.$key);
        //         $name = Str::uuid() . '.' . $document_note_podcast->getClientOriginalExtension();
        //         $document_note_podcast->storeAs('public/chapter/' . $chapter->id . '/podcast', $name);
        //         $upData['document_note'] = asset('storage/chapter/' . $chapter->id . '/podcast/'. $name);
        //     }
        //     $chapter->document_note()->create($upData);
        // }
        if ($request->hasFile('document_note_podcast')) {
            $document_note_podcast = $request->file('document_note_podcast');
            foreach ($document_note_podcast as $key => $document) {
                $name = Str::uuid() . '.' . $document->getClientOriginalExtension();
                $chapter->document_note()->create([
                    'category' => 'Podcast',
                    'document_note' => asset('storage/chapter/' . $chapter->id . '/podcast/'. $name),
                    'description' => $data['description_podcast'][$key],
                    'concept_id' => (isset($data['concept_id_podcast'][$key])) ? $data['concept_id_podcast'][$key] : '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                $document->storeAs('public/chapter/' . $chapter->id . '/podcast', $name);
            }
        }
        return redirect()->route('chapter.index')->with('success', 'Chapter created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Chapter $chapter)
    {
        $subject = Subject::where('id', $chapter->subject_id)->first();
        $subject_grade = Subject_grade::where('id', $chapter->subject_grade_id)->first();
        $chapter_document_note = Chapter_document_note::where('id', $chapter->document_note_id)->first();
        $concept_evaluation_test = Concept_evaluation_test::where('id', $chapter->concept_evolution_test_id)->first();

        return view('chapter.show', compact('concept_evaluation_test', 'subject_grade', 'subject', 'chapter', 'chapter_document_note'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Chapter $chapter)
    {
        $subjects = Subject::where('status', 1)->get();
        $subject_grades = Subject_grade::where('status', 1)->get();
        $chapter_document_notes = Chapter_document_note::where('status', 1)->get();
        $concept_evaluation_tests = Concept_evaluation_test::where('status', 1)->get();
        return view('chapter.edit', compact('concept_evaluation_tests', 'subject_grades', 'subjects', 'chapter', 'chapter_document_notes'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'chapter_name' => 'required',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_description' => 'nullable',
            'analysis_importance' => 'nullable',
            'additional_information' => 'nullable',
            'external_resources' => 'nullable',
            'priority_icon' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'chapter_pdf' => 'nullable',
            'concept_evaluation_test_id' => 'nullable',
            'status' => 'required',
            'document_note_id.*' => 'nullable',
            'description.*' => 'nullable',
            'concept_id.*' => 'nullable',
            'document_note.*' => 'nullable',
            'document_note_id_podcast.*' => 'nullable',
            'description_podcast.*' => 'nullable',
            'concept_id_podcast.*' => 'nullable',
            'document_note_podcast.*' => 'nullable',
        ]);
        
        $data['priority_icon'] = $chapter->priority_icon;
        if ($request->hasFile('priority_icon')) {
            $priority_icon = Str::uuid() . '.' . $request->priority_icon->getClientOriginalExtension();
            $request->priority_icon->storeAs('public/chapter/' . $chapter->id, $priority_icon);
            $data['priority_icon'] = asset('storage/chapter/' . $chapter->id . '/' . $priority_icon);
        }
        $data['updated_by'] = auth()->user()->id;
        $chapter->update($data);

        if(isset($data['document_note_id']))
        {
            $document_note_ids = [];
            foreach ($data['document_note_id'] as $key => $id) {
                $upData = [
                    'category' => 'Document Note',
                    'description' => $data['description'][$key],
                    'concept_id' => (isset($data['concept_id'][$key])) ? $data['concept_id'][$key] : '0',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
                if($request->hasFile('document_note.'.$key)){
                    $document_note = $request->file('document_note.'.$key);
                    $name = Str::uuid() . '.' . $document_note->getClientOriginalExtension();
                    $document_note->storeAs('public/chapter/' . $chapter->id . '/document', $name);
                    $upData['document_note'] = asset('storage/chapter/' . $chapter->id . '/document/'. $name);
                }
                if($id == 0){
                    $chapter_document = $chapter->document_note()->create($upData);
                } else {
                    $chapter_document = $chapter->document_note()->find($id);
                    $chapter_document->update($upData);
                }
                $document_note_ids[] = $chapter_document->id;
            }
            $chapter->document_note()->whereNotIn('id', $document_note_ids)->delete();
        }
        if(isset($data['document_note_id_podcast']))
        {
            $document_note_id_podcasts = [];
            foreach ($data['document_note_id_podcast'] as $key => $id) {
                $upData = [
                    'category' => 'Podcast',
                    'description' => $data['description_podcast'][$key],
                    'concept_id' => (isset($data['concept_id_podcast'][$key])) ? $data['concept_id_podcast'][$key] : '0',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
                if($request->hasFile('document_note_podcast.'.$key)){
                    $document_note_podcast = $request->file('document_note_podcast.'.$key);
                    $name = Str::uuid() . '.' . $document_note_podcast->getClientOriginalExtension();
                    $document_note_podcast->storeAs('public/chapter/' . $chapter->id . '/podcast', $name);
                    $upData['document_note'] = asset('storage/chapter/' . $chapter->id . '/podcast/'. $name);
                }
                if($id == 0){
                    $chapter_document_podcast = $chapter->document_note()->create($upData);
                } else {
                    $chapter_document_podcast = $chapter->document_note()->find($id);
                    $chapter_document_podcast->update($upData);
                }
                $document_note_id_podcasts[] = $chapter_document_podcast->id;
            }
            $chapter->document_note()->whereNotIn('id', $document_note_id_podcasts)->delete();
        }
        

        return redirect()->route('chapter.index')->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('chapter.index')->with('success', 'Chapter deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function chapterMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Chapter::where('id', $id)->delete();
        }

        return redirect()->route('chapter.index')->with('success', 'Chapter deleted successfully');
    }

    public function order(Request $request)
    {
        $subjects = Subject::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'subject_id' => 'required',
                'subject_grade_id' => 'required',
            ]);
            $chapters = Chapter::where('subject_id', $data['subject_id'])
                ->where('subject_grade_id', $data['subject_grade_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $chapters = [];
        }
        return view('chapter.order', compact('data', 'chapters', 'subjects'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Chapter::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('chapter.order')->with('success', 'Chapter Ordered successfully');
    }

    public function lock(Request $request)
    {
        $subjects = Subject::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'subject_id' => 'required',
                'subject_grade_id' => 'required',
            ]);
            $chapters = Chapter::where('subject_id', $data['subject_id'])
                ->where('subject_grade_id', $data['subject_grade_id'])
                ->orderBy('order')
                ->get();
        } else {
            $data = [];
            $chapters = [];
        }
        return view('chapter.lock', compact('data', 'subjects', 'chapters'));
    }

    public function rowLock(Request $request)
    {
        $chapterData = [];
        foreach ($request->lock as $id => $lock) {
            $data = Chapter::where('id', $id)->first();
            if($data->lock != $lock && $lock == 0){
                $chapterData['chapter_name'][] = $data->chapter_name;
                $chapterData['subject_grade'][] = $data->subject_grade->subject_grade_name;
            }
            $data->lock = $lock;
            $data->save();
        }
        if(count($chapterData) > 0){
            $chapterData['chapter_name'] = implode(', ', $chapterData['chapter_name']);
            $chapterData['subject_grade'] = implode(', ', $chapterData['subject_grade']);
            $chapterData['institute_name'] = auth()->user()->institutes->institute_name;
            $chapterData['institute_logo'] = 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp';
            (new Chapter)->sendEmailUnlock($chapterData);

            // $data['chapter_name'] = $data->chapter_name;
            // $template = Sms_template::find(7);
            // $template->sendMessage($data);
        }
        return redirect()->route('chapter.lock')->with('success', 'Chapter Lock successfully');
    }

    public function chapterUpload()
    {
        return view('chapter.upload_list');
    }

    public function chapterUploadList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Chapter_document_note::join('chapters', 'chapter_document_notes.chapter_id', '=', 'chapters.id')
            ->leftJoin('concepts', 'chapter_document_notes.concept_id', '=', 'concepts.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('chapter_document_notes.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'chapter_document_notes.*',
                    'chapters.chapter_name',
                    'concepts.concept_name',
                ])->map(function ($recode) {
                $recode->delete = route('chapter.upload.delete', [$recode->id]);
                $recode->document_note = $recode->document_note();

                return $recode;
            }),
        ]);
    }

    public function chapterUploadCreate()
    {
        $subjects = Subject::where('status', 1)->get();
        return view('chapter.upload',compact('subjects'));
    }

    public function chapterUploadStore(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'document_note_id' => 'required|array',
            'document_note_id.*' => 'required',
            'description' => 'required|array',
            'description.*' => 'required',
            'concept_id' => 'required|array',
            'concept_id.*' => 'required',
            'document_note' => 'required|array',
            'document_note.*' => 'required',
            'document_note_id_podcast' => 'required|array',
            'document_note_id_podcast.*' => 'required',
            'description_podcast' => 'required|array',
            'description_podcast.*' => 'required',
            'concept_id_podcast' => 'required|array',
            'concept_id_podcast.*' => 'required',
            'document_note_podcast' => 'required|array',
            'document_note_podcast.*' => 'required',
        ], [], [
            'description.*' => 'description',
            'concept_id' => 'concept',
            'concept_id.*' => 'concept',
            'document_note' => 'file',
            'document_note.*' => 'file',
            'description_podcast.*' => 'description',
            'concept_id_podcast' => 'concept',
            'concept_id_podcast.*' => 'concept',
            'document_note_podcast' => 'file',
            'document_note_podcast.*' => 'file',
        ]);

        foreach($data['document_note_id'] as $key)
        {
            $upData = [
                'category' => 'Document Note',
                'description' => $data['description'][$key],
                'concept_id' => (isset($data['concept_id'][$key])) ? $data['concept_id'][$key] : '0',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ];
            
            if($request->hasFile('document_note.'.$key)){
                $document_note = $request->file('document_note.'.$key);
                $name = Str::uuid() . '.' . $document_note->getClientOriginalExtension();
                $document_note->storeAs('public/chapter/' . $data['chapter_id'] . '/document', $name);
                $upData['document_note'] = asset('storage/chapter/' . $data['chapter_id'] . '/document/'. $name);
            }

            $upData['chapter_id'] = $data['chapter_id'];
            Chapter_document_note::create($upData);
        }
        foreach($data['document_note_id_podcast'] as $key)
        {
            $upData = [
                'category' => 'Podcast',
                'description' => $data['description_podcast'][$key],
                'concept_id' => (isset($data['concept_id_podcast'][$key])) ? $data['concept_id_podcast'][$key] : '0',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ];
            
            if($request->hasFile('document_note_podcast.'.$key)){
                $document_note_podcast = $request->file('document_note_podcast.'.$key);
                $name = Str::uuid() . '.' . $document_note_podcast->getClientOriginalExtension();
                $document_note_podcast->storeAs('public/chapter/' . $data['chapter_id'] . '/podcast', $name);
                $upData['document_note'] = asset('storage/chapter/' . $data['chapter_id'] . '/podcast/'. $name);
            }

            $upData['chapter_id'] = $data['chapter_id'];
            Chapter_document_note::create($upData);
        }
        return redirect()->route('chapter.upload')->with('success','Chapter Upload Successfully !');
    }

    /**
     * Remove the specified record from storage.
     */
    public function chapterUploadDestroy(Chapter_document_note $chapter_document_note)
    {
        $chapter_document_note->delete();
        return redirect()->route('chapter.upload')->with('success', 'Chapter Document deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function chapterUploadMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Chapter_document_note::where('id', $id)->delete();
        }

        return redirect()->route('chapter.upload')->with('success', 'Chapter Documents deleted successfully');
    }
}
