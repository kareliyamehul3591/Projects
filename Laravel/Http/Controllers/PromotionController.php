<?php

namespace App\Http\Controllers;

use App\Models\Institutes;
use App\Models\Package;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class PromotionController extends Controller
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
        return view('promotion.list');
    }

    public function promotionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Promotion;
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
                $recode->show = route('promotion.show', [$recode->id]);
                $recode->edit = route('promotion.edit', [$recode->id]);
                $recode->delete = route('promotion.delete', [$recode->id]);
                $recode->startDate = $recode->start_date->format('d-m-Y');
                $recode->endDate = $recode->end_date->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $packages = Package::where('status', '1')->get();
        $institutes = Institutes::where('status', '1')->get();
        return view('promotion.create', compact('packages','institutes'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'promo_code' => 'required',
            'discount' => 'required',
            'type' => 'required',
            'max_users' => 'required',
            'package_id' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'promo_image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048|dimensions:max_width=225,max_height=225',
            'active' => 'nullable',
        ]);

        if (isset($request->active)) {
            $data['active_by'] = auth()->user()->id;
            $data['active_at'] = date('Y-m-d H:i:s');
        } else {
            $data['active'] = 0;
        }

        if ($request->hasFile('promo_image')) {
            $promo_image = Str::uuid() . '.' . $request->promo_image->getClientOriginalExtension();
            $request->promo_image->storeAs('public/promo_images', $promo_image);
            $data['promo_image'] = asset('storage/promo_images/'. $promo_image);
        }

        $data['package_id'] = implode(',', $data['package_id']);
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $promotion = Promotion::create($data);

        if(($request->input('sendEmail'))){
            foreach(explode(',',$request->input('sendEmail')) as $userId)
            {
                $user = User::where('id',$userId)->first();

                $promoData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'promo_code' => $promotion->promo_code,
                    'discount' => $promotion->discount,
                    'type' => $promotion->type,
                    'start_date' => $promotion->start_date,
                    'end_date' => $promotion->end_date,
                ];
                $user->sendPromotionEmail($promoData);
            }
        }

        return redirect()->route('promotion.index')->with('success', 'Promotion created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Promotion $promotion)
    {
        $packages = Package::where('status', '1')->get();

        return view('promotion.edit', compact('promotion', 'packages'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'promo_code' => 'required',
            'discount' => 'required',
            'type' => 'required',
            'max_users' => 'required',
            'package_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'promo_image' => 'nullable|dimensions:max_width=225,max_height=225',
            'active' => 'nullable',
        ]);

        if (isset($request->active)) {
            $data['active_by'] = auth()->user()->id;
            $data['active_at'] = date('Y-m-d H:i:s');
        } else {
            $data['active'] = 0;
        }

        if ($request->hasFile('promo_image')) {
            $promo_image = Str::uuid() . '.' . $request->promo_image->getClientOriginalExtension();
            $request->promo_image->storeAs('public/promo_images', $promo_image);
            $data['promo_image'] = asset('storage/promo_images/'. $promo_image);
        }

        $data['package_id'] = implode(',', $data['package_id']);
        $data['updated_by'] = auth()->user()->id;

        $promotion->update($data);

        return redirect()->route('promotion.index')->with('success', 'Promotion updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Promotion $promotion)
    {
        return view('promotion.show', compact('promotion'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotion.index')->with('success', 'Promotion deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function promotionMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Promotion::where('id', $id)->delete();
        }

        return redirect()->route('promotion.index')->with('success', 'Promotion deleted successfully');
    }

    public function promotionCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function instituteList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('group_id',1)->where('id','!=','1');

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
                
                return $recode;
            }),
        ]);
    }

    public function adminList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::whereIn('users.institute_id',explode(',',$q[0]))
                ->where('group_id', 2);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if($opt == 'institute_id')
                {
                    $values = $values->whereIn('users.'.$opt, explode(',', $q[$key]));
                }
                else {
                    $values = $values->where('users.'.$opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                return $recode;
            }),
        ]);
    }

    public function teacherList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::whereIn('users.institute_id',explode(',',$q[0]))->where('group_id', 3);

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if($opt == 'institute_id')
                {
                    $values = $values->whereIn('users.'.$opt, explode(',', $q[$key]));
                }
                else {
                    $values = $values->where('users.'.$opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                return $recode;
            }),
        ]);
    }

    public function studentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('students', 'users.id', '=', 'students.user_id')
                        ->where('users.group_id', 4);

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if($opt == 'teacher_id')
                {
                    $values = $values->whereIn('students.'.$opt, explode(',', $q[$key]));
                }
                else {
                    $values = $values->where('users.'.$opt, 'LIKE', "%$q[$key]%");
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
                    'users.*',
                    'students.id as student_id',])
                ->map(function ($recode)
                {
                return $recode;
            }),
        ]);
    }

    public function parentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('parents', 'users.id', '=', 'parents.user_id')->where('group_id', 5);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if($opt == 'student_id')
                {
                    $values = $values->whereIn('parents.'.$opt, explode(',', $q[$key]));
                }
                else {
                    $values = $values->where('users.'.$opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['users.*', 'parents.id as parents_id'])->map(function ($recode) {
        
                return $recode;
            }),
        ]);
    }
}
