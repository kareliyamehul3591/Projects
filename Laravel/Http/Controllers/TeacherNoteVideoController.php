<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Concept;
use App\Models\Subject;
use App\Models\Subject_grade;
use App\Models\Teacher_note_video;
use Illuminate\Http\Request;

class TeacherNoteVideoController extends Controller
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
        return view('teacher_note_video.list');
    }

    public function teacherNoteVideoList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Teacher_note_video::join('subjects', 'teacher_note_videos.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'teacher_note_videos.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'teacher_note_videos.chapter_id', '=', 'chapters.id')
            ->join('concepts', 'teacher_note_videos.concept_id', '=', 'concepts.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('teacher_note_videos.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['teacher_note_videos.*', 'subjects.subject_name', 'subject_grades.subject_grade_name', 'chapters.chapter_name', 'concepts.concept_name'])->map(function ($recode) {
                $recode->show = route('teacher_note_video.show', [$recode->id]);
                $recode->edit = route('teacher_note_video.edit', [$recode->id]);
                $recode->delete = route('teacher_note_video.delete', [$recode->id]);
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
        return view('teacher_note_video.create', compact('subjects'));
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
            'concept_id' => 'required',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Teacher_note_video::create($data);

        return redirect()->route('teacher_note_video.index')->with('success', 'Teacher note & video created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Teacher_note_video $teacher_note_video)
    {
        return view('teacher_note_video.show', compact('teacher_note_video'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Teacher_note_video $teacher_note_video)
    {
        $subjects = Subject::where('status', 1)->get();
        $subject_grades = Subject_grade::where('status', 1)->get();
        $chapters = Chapter::where('status', 1)->get();
        $concepts = Concept::where('status', 1)->get();
        return view('teacher_note_video.edit', compact('teacher_note_video', 'subjects', 'subject_grades', 'chapters', 'concepts'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Teacher_note_video $teacher_note_video)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'concept_id' => 'required',
            'name' => 'required',
            'description' => 'nullable',
            'status' => "required",
        ]);

        $data['updated_by'] = auth()->user()->id;

        $teacher_note_video->update($data);

        return redirect()->route('teacher_note_video.index')->with('success', 'Teacher_note_video updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Teacher_note_video $teacher_note_video)
    {
        $teacher_note_video->delete();

        return redirect()->route('teacher_note_video.index')->with('success', 'Teacher_note_video deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function teacherNoteVideoMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Teacher_note_video::where('id', $id)->delete();
        }

        return redirect()->route('teacher_note_video.index')->with('success', 'Teacher_note_video deleted successfully');
    }
}
