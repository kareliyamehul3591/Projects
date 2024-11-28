<?php

namespace App\Http\Controllers;

use App\Models\Help_video;
use App\Models\Help_video_category;
use Illuminate\Http\Request;
use Str;

class HelpVideoController extends Controller
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
        return view('help_video.list');
    }

    public function helpVideoList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Help_video::join('help_video_categories', 'help_videos.help_video_category_id', '=', 'help_video_categories.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "help_video_category_name") {
                    $values = $values->where('help_video_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('help_videos.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['help_videos.*', 'help_video_categories.help_video_category_name'])->map(function ($recode) {
                $recode->show = route('help_video.show', [$recode->id]);
                $recode->edit = route('help_video.edit', [$recode->id]);
                $recode->delete = route('help_video.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $help_video_categorys = Help_video_category::where('status', 1)->get();
        return view('help_video.create', compact('help_video_categorys'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'help_video_category_id' => 'required',
            'video_title' => 'required',
            'video' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $help_video = Help_video::create($data);

        if ($request->hasFile('video')) {
            $video = Str::uuid() . '.' . $request->video->getClientOriginalExtension();
            $request->video->storeAs('public/help_video/' . $help_video->id, $video);
            $data['video'] = asset('storage/help_video/' . $help_video->id .'/'. $video);
        }

        return redirect()->route('help_video.index')->with('success', 'Help Video created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Help_video $help_video)
    {
        return view('help_video.show', compact('help_video'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Help_video $help_video)
    {
        $help_video_categorys = Help_video_category::where('status', 1)->get();

        return view('help_video.edit', compact('help_video', 'help_video_categorys'));
    }

     /**
     * Update the specified record.
     */
    public function update(Request $request, Help_video $help_video)
    {
        $data = $request->validate([
            'help_video_category_id' => 'required',
            'video_title' => 'required',
            'video' => 'required',
            'status' => 'required',
        ]);

        $data['video'] = $help_video->video;
        if ($request->hasFile('video')) {
            $video = Str::uuid() . '.' . $request->video->getClientOriginalExtension();
            $request->video->storeAs('public/help_video/' . $help_video->id, $video);
            $data['video'] = asset('storage/help_video/' . $help_video->id .'/'. $video);
        }

        $data['updated_by'] = auth()->user()->id;
        $help_video->update($data);
        return redirect()->route('help_video.index')->with('success', 'Help Video updated successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Help_video $help_video)
    {
        $help_video->delete();

        return redirect()->route('help_video.index')->with('success', 'Help Video deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function helpVideoMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Help_video::where('id', $id)->delete();
        }

        return redirect()->route('help_video.index')->with('success', 'Help Video deleted successfully');
    }

}
