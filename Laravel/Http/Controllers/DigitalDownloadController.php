<?php

namespace App\Http\Controllers;

use App\Models\Digital_download;
use Illuminate\Http\Request;
use Str;

class DigitalDownloadController extends Controller
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
        return view('digital_download.list');
    }

    public function digitalDownloadList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Digital_download;

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

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
                $recode->delete = route('digital_download.delete', [$recode->id]);
                $recode->digital_download = $recode->digital_download;
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('digital_download.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'digital_download' => 'required|max:2048',
        ]);

        $data['created_by'] = auth()->user()->id;

        if ($request->hasFile('digital_download')) {
            $digital_download = $request->file('digital_download');
            foreach ($digital_download as $video) {
                $name = Str::uuid() . '.' . $video->getClientOriginalExtension();
                $data['digital_download'] = asset('storage/digital_download/'. $name);
                
                Digital_download::create($data);
                $video->storeAs('public/digital_download', $name);
            }
        }

        return redirect()->route('digital_download.index')->with('success', 'Digital Download created successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Digital_download $digital_download)
    {
        $digital_download->delete();

        return redirect()->route('digital_download.index')->with('success', 'Digital Download deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function digitalDownloadMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Digital_download::where('id', $id)->delete();
        }

        return redirect()->route('digital_download.index')->with('success', 'Digital Download deleted successfully');
    }
}
