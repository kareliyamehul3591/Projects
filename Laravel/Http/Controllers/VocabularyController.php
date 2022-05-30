<?php

namespace App\Http\Controllers;

use App\Exports\VocabularyExport;
use App\Imports\VocabularyImport;
use App\Models\Vocabulary;
use App\Models\Vocabulary_level;
use Illuminate\Http\Request;
use Excel;
use Str;

class VocabularyController extends Controller
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
        return view('vocabulary.list');
    }

    public function vocabularyList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Vocabulary::join('vocabulary_levels','vocabularies.level_id','=','vocabulary_levels.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "level_name") {
                    $values = $values->where('vocabulary_levels' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('vocabularies' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['vocabularies.*','vocabulary_levels.level_name'])->map(function ($recode) {
                $recode->show = route('vocabulary.show', [$recode->id]);
                $recode->edit = route('vocabulary.edit', [$recode->id]);
                $recode->delete = route('vocabulary.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $levels = Vocabulary_level::get();
        return view('vocabulary.create',compact('levels'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'meaning' => 'required',
            'usage' => 'required',
            'antonym' => 'required',
            'days' => 'required',
            'level_id' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Vocabulary::create($data);

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Vocabulary $vocabulary)
    {
        return view('vocabulary.show', compact('vocabulary'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Vocabulary $vocabulary)
    {
        $levels = Vocabulary_level::get();
        return view('vocabulary.edit', compact('vocabulary','levels'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Vocabulary $vocabulary)
    {
        $data = $request->validate([
            'name' => 'required',
            'meaning' => 'required',
            'usage' => 'required',
            'antonym' => 'required',
            'days' => 'required',
            'level_id' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $vocabulary->update($data);

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Vocabulary $vocabulary)
    {
        $vocabulary->delete();

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function vocabularyMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Vocabulary::where('id', $id)->delete();
        }

        return redirect()->route('vocabulary.index')->with('success', 'Vocabulary deleted successfully');
    }

    public function vocabularyBulkUpload()
    {
        return view('vocabulary.vocabulary_bulk_upload');
    }

    public function vocabularyBulkUploadExport()
    {
        return Excel::download(new VocabularyExport, 'Vocabulary.xlsx');
    }

    public function vocabularyBulkUploadImport(Request $request)
    {
        $mimes = 'xlsx';
        $request->validate([
            'import' => 'required|mimes:' . $mimes . '|max:2048',
        ]);

        $import_name = 'test.xlsx';
        if ($request->hasFile('import')) {
            $import_name = Str::uuid() . '.' . $request->import->getClientOriginalExtension();
            $request->import->storeAs('public/import/vocabulary', $import_name);
        }
        Excel::import(new VocabularyImport(1), storage_path('app/public/import/vocabulary/' . $import_name));
        return redirect()->back()->with('success', 'Vocabulary Data has been added successfully!');
    }

}
