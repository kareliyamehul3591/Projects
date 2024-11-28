<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Str;

class VideoController extends Controller
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
        return view('video.list');
    }

    public function videoList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Video;
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                $values = $values->where($opt, 'LIKE', "%$q[$key]%");
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->delete = route('video.delete', [$recode->id]);
                $recode->video_url = $recode->video_url();
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('video.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'video' => 'required|max:2048',
        ]);

        if ($request->hasFile('video')) {
            $video = Str::uuid() . '.' . $request->video->getClientOriginalExtension();
            $request->video->storeAs('public/ckfinder/files', $video);
            $data['video'] = asset('storage/ckfinder/files/'. $video);
        }

        $data['created_by'] = auth()->user()->id;

        Video::create($data);

        return redirect()->route('video.index')->with('success', 'Video created successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('video.index')->with('success', 'Video deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function videoMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Video::where('id', $id)->delete();
        }

        return redirect()->route('video.index')->with('success', 'Video deleted successfully');
    }

}
