<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Str;

class ImageController extends Controller
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
        return view('image.list');
    }

    public function imageList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Image;
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
                $recode->delete = route('image.delete', [$recode->id]);
                $recode->image_url = $recode->image_url();
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('image.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/ckfinder/images', $image);
            $data['image'] = asset('storage/ckfinder/images/'. $image);
        }

        $data['created_by'] = auth()->user()->id;

        Image::create($data);

        return redirect()->route('image.index')->with('success', 'Image created successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Image $image)
    {
        $image->delete();

        return redirect()->route('image.index')->with('success', 'Image Video deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function imageMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Image::where('id', $id)->delete();
        }

        return redirect()->route('image.index')->with('success', 'Image deleted successfully');
    }
}
