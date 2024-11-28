<?php

namespace App\Http\Controllers;

use App\Exports\ChapterAnalysisExport;
use Illuminate\Http\Request;
use App\Imports\ChapterAnalysisImport;
use Excel;
use App\Models\Chapter_analysis;

class ChapterAnalysisController extends Controller
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
        $chapter_analysis = Chapter_analysis::get();
        $analysis = $years = $chapter_name = [];
        foreach($chapter_analysis as $value){
            $analysis[$value->chapter_id][$value->examination_type][$value->year] = $value->count;
            $years[$value->year] = $value->year;
            $chapter_name[$value->chapter_id] = ((isset($value->chapter->chapter_name))?$value->chapter->chapter_name:'');
        }
        return view('chapter_analysis.list',compact('analysis','years','chapter_name'));
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('chapter_analysis.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'analysis_excel' => 'required|mimes:xlxs,xlsx|max:2048',
        ]);
        if ($request->hasFile('analysis_excel')) {
            Excel::import(new ChapterAnalysisImport,request()->file('analysis_excel'));
        }
        return redirect()->route('chapter_analysis.index')->with('success', 'Excel Uplode successfully.');
    }

    public function chapterAnalysisExport()
    {
        return Excel::download(new ChapterAnalysisExport, 'Chapter Analysis.xlsx');
    }
}
