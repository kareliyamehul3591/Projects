<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Package;
use App\Models\Student;
use App\Models\Student_package;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        return view('news.list',compact('status'));
    }

    public function newsList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new News;
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
                $recode->show = route('news.show', [$recode->id]);
                $recode->edit = route('news.edit', [$recode->id]);
                $recode->delete = route('news.delete', [$recode->id]);
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
        $packages = Package::where('status',1)->get();
        return view('news.create',compact('packages'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'display_from' => 'required|date_format:Y-m-d',
            'display_to' => 'required|date_format:Y-m-d',
            'show_in' => 'required',
            'package_id' => 'required',
            'keyword' => 'required',
            'news_image' => 'required'
        ]);
        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('news_image')) {
            $news_image = Str::uuid() . '.' . $request->news_image->getClientOriginalExtension();
            $request->news_image->storeAs('public/news', $news_image);
            $data['news_image'] = asset('storage/news/'. $news_image);
        }

        $news = News::create($data);

        $student_packages = Student_package::whereIn('package_id',explode(',',$news->package_id))
            ->groupBy('user_id')
            ->pluck('user_id')
            ->toArray();
        foreach($student_packages as $student_package)
        {
            $user = User::where('id',$student_package)->first();
            $sNewsData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'newsletter_date' => $news->name,
            ];
            $user->newsEmailStudent($sNewsData);

            $student = Student::where('user_id',$user->id)->first();
            $puser = User::where('id',$student->parents()->user_id)->first();
            $pNewsData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $student->user->name,
                'student_email' => $student->user->email,
                'newsletter_date' => $news->name,
                'parent_name' => $puser->name,
                'parent_email' => $puser->email,
            ];
            $puser->newsEmailParent($pNewsData);
        }

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(News $news)
    {
        $packages = Package::where('status',1)->get();
        return view('news.edit', compact('news','packages'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'display_from' => 'required|date_format:Y-m-d',
            'display_to' => 'required|date_format:Y-m-d',
            'show_in' => 'required',
            'package_id' => 'required',
            'keyword' => 'required',
            'status' => 'required',
            'news_image' => 'nullable'
        ]);

        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('news_image')) {
            $news_image = Str::uuid() . '.' . $request->news_image->getClientOriginalExtension();
            $request->news_image->storeAs('public/news', $news_image);
            $data['news_image'] = asset('storage/news/'. $news_image);
        }
        
        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function newsMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            News::where('id', $id)->delete();
        }

        return redirect()->route('news.index')->with('success', 'News deleted successfully');
    }
}
