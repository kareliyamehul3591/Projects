<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Subject_grade;
use Illuminate\Http\Request;
use Str;

class PackController extends Controller
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
        return view('pack.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function packList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Pack;
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
                $recode->show = route('pack.show', [$recode->id]);
                $recode->edit = route('pack.edit', [$recode->id]);
                $recode->delete = route('pack.delete', [$recode->id]);

                $subjectGradeID = $recode->package_subject_grade->pluck('subject_grade_id')->toArray();
    
                $subjectGrade = Subject_grade::whereIn('id', $subjectGradeID)->pluck('subject_grade_name')->toArray();
                $recode->subjectGrade = implode(', ', $subjectGrade);

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
        $subject_grades = Subject_grade::where('status', 1)->get();
        return view('pack.create', compact('subject_grades'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pack_name' => 'required',
            'subject_grade_id' => 'required',
            'logo' => 'required|image|mimes:jpeg,jpg,webp,png,gif|max:2048',
            'purchase_image' => 'required|image|mimes:jpeg,webp,jpg,png,gif|max:2048',
            'pack_details' => 'required',
            'pack_decription' => 'nullable',
            'student_interface_description' => 'nullable',
            'upgrade_decription' => 'nullable',
            'ideal_for' => 'required',
        ],[
            'logo' => 'logo image'
        ]);
        
        $data['institute_id'] = auth()->user()->institute_id;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        
        $pack = Pack::create($data);

        if ($request->hasFile('logo')) {
            $logo = Str::uuid() . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->storeAs('public/pack/' . $pack->id, $logo);
            $pack->update([
                'logo_image' => asset('storage/pack/' . $pack->id.'/'. $logo)
            ]);
        }
        if ($request->hasFile('purchase_image')) {
            $purchase_image = Str::uuid() . '.' . $request->purchase_image->getClientOriginalExtension();
            $request->purchase_image->storeAs('public/pack/' . $pack->id, $purchase_image);
            $pack->update([
                'purchase_image' => asset('storage/pack/' . $pack->id .'/'. $purchase_image)
            ]);
        }

        foreach ($data['subject_grade_id'] as $id) {
            $pack->package_subject_grade()->create([
                'subject_grade_id' => $id,
            ]);
        }

        return redirect()->route('pack.index')->with('success', 'Pack created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Pack $pack)
    {
        return view('pack.show', compact('pack'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Pack $pack)
    {
        $subject_grades = Subject_grade::where('status', 1)->get();
        $subject_grade_id = $pack->package_subject_grade()->pluck('subject_grade_id')->toArray();
        return view('pack.edit', compact('subject_grades','pack','subject_grade_id'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Pack $pack)
    {
        $data = $request->validate([
            'pack_name' => 'required',
            'subject_grade_id' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'purchase_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'pack_details' => 'required',
            'pack_decription' => 'nullable',
            'student_interface_description' => 'nullable',
            'upgrade_decription' => 'nullable',
            'ideal_for' => 'required',
            'status' => 'required',
        ],[
            'logo' => 'logo image'
        ]);

        $data['logo'] = $pack->logo;
        if ($request->hasFile('logo')) {
            $logo = Str::uuid() . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->storeAs('public/pack/' . $pack->id, $logo);
            $pack->update([
                'logo_image' => asset('storage/pack/' . $pack->id.'/'. $logo)
            ]);
        }
        $data['purchase_image'] = $pack->purchase_image;
        if ($request->hasFile('purchase_image')) {
            $purchase_image = Str::uuid() . '.' . $request->purchase_image->getClientOriginalExtension();
            $request->purchase_image->storeAs('public/pack/' . $pack->id, $purchase_image);
            $pack->update([
                'purchase_image' => asset('storage/pack/' . $pack->id .'/'. $purchase_image)
            ]);
        }

        $data['institute_id'] = auth()->user()->institute_id;
        $data['updated_by'] = auth()->user()->id;
        
        $pack->update($data);

        $subject_grade_id = [];
        foreach ($data['subject_grade_id'] as $id) {
            $subject_grade = $pack->package_subject_grade()->create([
                'subject_grade_id' => $id,
            ]);
            $subject_grade_id[] = $subject_grade->id;
        }
        $pack->package_subject_grade()->whereNotIn('id', $subject_grade_id)->delete();

        return redirect()->route('pack.index')->with('success', 'Pack updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Pack $pack)
    {
        $pack->delete();

        return redirect()->route('pack.index')->with('success', 'Pack deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function packMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Pack::where('id', $id)->delete();
        }

        return redirect()->route('pack.index')->with('success', 'Pack deleted successfully');
    }
}
