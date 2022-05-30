<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Institutes;
use App\Models\Institute_package;
use App\Models\Package;
use App\Models\Package_price;
use App\Models\Question;
use App\Models\State;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstituteController extends Controller
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
        return view('institute.list');
    }

    public function instituteList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Institutes::where('id','!=',1);

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
                $recode->show = route('institute.show', [$recode->id]);
                $recode->edit = route('institute.edit', [$recode->id]);
                $recode->permit = route('institute.permit', [$recode->id]);
                $recode->delete = route('institute.delete', [$recode->id]);
                $recode->cart = route('institute.cart', [$recode->id]);

                $user = User::where('id',$recode->user_id)->first();
                $recode->superAdmin = $user->email;
                $recode->parents = $recode->total_students;
                return $recode;
            }),
        ]);
    }

    public function institutePackageList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Institute_package::join('packages', 'institute_packages.package_id', '=', 'packages.id')
            ->join('package_modules', 'institute_packages.package_module_id', '=', 'package_modules.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_module_name") {
                    $values = $values->where('package_modules.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_id") {
                    $values = $values->where('institute_packages.institutes_id', $q[$key]);
                } else {
                    $values = $values->where('institute_packages.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'institute_packages.*',
                'package_modules.package_module_name',
                'packages.package_name',
            ])->map(function ($recode) {
                $recode->created_date = $recode->created_at->format('Y-m-d H:i:s');
                $recode->expiry_date_status = ($recode->expiry_date < Carbon::now()->format('Y-m-d')) ? '1' : '0';
                $recode->upgrade_url = route('institute.package.upgrade', $recode->id);
                $recode->delete_url = route('institute.soft.delete', $recode->id);
                return $recode;
            }),
        ]);
    }

    public function institutePackageUpgrade(Institute_package $institute_package)
    {
        return view('institute.package_upgrade', compact('institute_package'));
    }

    public function instituteAdminList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('group_id', 2);
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "institute_id") {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
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

                $recode->approved = 'Yes';
                $recode->activate = 'Yes';
                return $recode;
            }),
        ]);
    }

    public function instituteTeacherList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Teacher::join('users', 'teachers.user_id', '=', 'users.id')
                ->leftJoin('qualifications', 'teachers.qualification_id', '=', 'qualifications.id');
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.name', $q[$key]);
                } else if ($opt == "email") {
                    $values = $values->where('users.email', $q[$key]);
                } else if ($opt == "mobile") {
                    $values = $values->where('users.mobile', $q[$key]);
                } else if ($opt == "qualification_name") {
                    $values = $values->where('qualifications.qualification_name', $q[$key]);
                } else if ($opt == "institute_id") {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else {
                    $values = $values->where('teachers.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'users.name',
                'users.email',
                'users.mobile',
                'qualifications.qualification_name',
                'teachers.*'
            ])->map(function ($recode) {

                $recode->approved = 'Yes';
                $recode->activate = 'Yes';
                return $recode;
            }),
        ]);
    }

    public function instituteStudentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('group_id', 4);
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "institute_id") {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
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

                $recode->approved = 'Yes';
                $recode->activate = 'Yes';
                return $recode;
            }),
        ]);
    }

    public function instituteQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Question;
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "institute_id") {
                    $values = $values->where('questions.institute_id', $q[$key]);
                } else {
                    $values = $values->where('questions.' . $opt, 'LIKE', "%$q[$key]%");
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
                $recode->title = route('question.edit', [$recode->id]);

                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();

        return view('institute.create', compact('countries'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'institute_name' => 'required|unique:institutes',
            'domain' => 'required',
            'address' => 'required',
            'pincode' => 'required|numeric|min:5',
            'country_id' => 'required',
            'state_id' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|required_with:password|same:password',
            'total_admins' => 'required|numeric',
            'total_examiners' => 'required|numeric',
            'total_students' => 'required|numeric',
            'total_questions' => 'required|numeric',
            'mobile_app_code' => 'required',
            'mobile_app_header' => 'required',
            'support_ticket_prefix' => 'required',
        ]);

        $user = User::create([
            'group_id' => 1,
            'privileges' => 4,
            'name' => $request->input('institute_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
            'institute_id' => config('app.institute_id'),
            'status' => ($request->input('status') == "on") ? 1 : 0,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        
        $institute = $user->institutes()->create([
            'user_id' => $user->id,
            'institute_name' => $request->input('institute_name'),
            'description' => $request->input('description'),
            'domain' => $request->input('domain'),
            'address' => $request->input('address'),
            'pincode' => $request->input('pincode'),
            'country_id' => $request->input('country_id'),
            'state_id' => $request->input('state_id'),
            'total_admins' => $request->input('total_admins'),
            'total_examiners' => $request->input('total_examiners'),
            'total_students' => $request->input('total_students'),
            'total_questions' => $request->input('total_questions'),
            'mobile_app_code' => $request->input('mobile_app_code'),
            'mobile_app_header' => $request->input('mobile_app_header'),
            'support_ticket_prefix' => $request->input('support_ticket_prefix'),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'is_westkutt_institute' => ($request->input('is_westkutt_institute') == "on") ? 1 : 0,
            'status' => ($request->input('status') == "on") ? 1 : 0,
        ]);

        $user->admin()->create();
        $user->institute_id = $institute->id;
        $user->save();

        $instituteData = [
            'institute_name' => $user->name,
            'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
            'super_admin_email' => $user->email,
        ];

        $user->sendInstituteRegisterEmail($instituteData);

        return redirect()->route('institute.index')->with('success', 'Institute created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Institutes $institute)
    {
        $user = User::where('id', $institute->user_id)->first();

        $countries = Country::where('status', 1)->get();

        $teachers = User::where('group_id', 3)->where('institute_id', $institute->id)
            ->where('status', 1)
            ->get();

        $states = State::where(['country_id' => $institute->country_id, 'status' => 1])->get();

        return view('institute.edit', compact('institute', 'user', 'countries', 'states', 'teachers'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Institutes $institute)
    {
        $request->validate([
            'institute_name' => "required|unique:institutes,institute_name,$institute->id,id",
            'domain' => 'required',
            'address' => 'required',
            'pincode' => 'required|numeric|min:5',
            'country_id' => 'required',
            'state_id' => 'required',
            'email' => "required|email|unique:users,email,$institute->user_id,id",
            'mobile' => "required|unique:users,mobile,$institute->user_id,id",
            'confirm_password' => 'required_with:password|same:password',
            'total_admins' => 'required|numeric',
            'total_examiners' => 'required|numeric',
            'total_students' => 'required|numeric',
            'total_questions' => 'required|numeric',
            'mobile_app_code' => 'required',
            'mobile_app_header' => 'required',
            'support_ticket_prefix' => 'required',
        ]);
        $teacher = Teacher::where('user_id', $institute->default_teacher_id)->first();
        if ($teacher) {
            $teacher->is_default_teacher = 0;
            $teacher->save();
        }
        $institute->update([
            'institute_name' => $request->input('institute_name'),
            'description' => $request->input('description'),
            'domain' => $request->input('domain'),
            'address' => $request->input('address'),
            'pincode' => $request->input('pincode'),
            'country_id' => $request->input('country_id'),
            'state_id' => $request->input('state_id'),
            'total_admins' => $request->input('total_admins'),
            'total_examiners' => $request->input('total_examiners'),
            'total_students' => $request->input('total_students'),
            'total_questions' => $request->input('total_questions'),
            'mobile_app_code' => $request->input('mobile_app_code'),
            'mobile_app_header' => $request->input('mobile_app_header'),
            'support_ticket_prefix' => $request->input('support_ticket_prefix'),
            'updated_by' => auth()->user()->id,
            'is_westkutt_institute' => ($request->input('is_westkutt_institute') == "on") ? 1 : 0,
            'status' => ($request->input('status') == "on") ? 1 : 0,
            'default_teacher_id' => $request->input('default_teacher_id'),
        ]);

        $teacher = Teacher::where('user_id', $institute->default_teacher_id)->first();
        if ($teacher) {
            $teacher->is_default_teacher = 1;
            $teacher->save();
        }

        $userdata = [
            'name' => $request->input('institute_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'status' => ($request->input('status') == "on") ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ];
        if ($request->input('password')) {
            $userdata['password'] = Hash::make($request->input('password'));
        }
        $institute->user()->update($userdata);

        return redirect()->route('institute.index')->with('success', 'Institute updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Institutes $institute)
    {
        $user = User::where('id', $institute->user_id)->first();
        $questions = Question::where('institute_id', $institute->id)->get();

        return view('institute.show', compact('institute', 'user', 'questions'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Institutes $institute)
    {
        $admins = User::where('group_id',2)->where('institute_id',$institute->id)->count();
        if($admins > 0)
        {
            return redirect()->route('institute.index')->with('success', 'Institute can not be delete');
        } else {
            $institute->delete();
            return redirect()->route('institute.index')->with('success', 'Institute deleted successfully');
        }
    }

    public function institutePackageDelete(Institute_package $institute_package)
    {
        $institute_package->delete();

        return redirect()->route('institute.index')->with('success', 'Institute Package deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function instituteMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');
        $admins = User::where('group_id',2)->whereIn('institute_id',$ids)->count();

        if($admins > 0)
        {
            return redirect()->route('institute.index')->with('success', 'Institute can not be delete');
        } else {
            foreach ($ids as $id) {
                Institutes::where('id', $id)->delete();
            }
            return redirect()->route('institute.index')->with('success', 'Institute deleted successfully');
        }
    }

    public function institutePackagePurchase(Institutes $institute)
    {
        $packages = Package::where('status', 1)->get();
        return view('institute.cart', compact('institute', 'packages'));
    }

    public function institutePackage(Request $request)
    {
        $id = $request->input('id');
        $package_price = Package_price::where('id', $id)->first();
        return $package_price;
    }

    public function institutePackageStore(Request $request, Institutes $institute)
    {
        $data = $request->validate([
            'package_id.*' => 'required',
            'module_id.*' => 'required|distinct',
            'package_count.*' => 'required',
            'discount' => 'nullable',
            'grand_total' => 'nullable',
            'invoice' => 'nullable',
        ], [
            'package_id.*.required' => 'The Package field is required.',
            'module_id.*.required' => 'The Package module field is required.',
            'module_id.*.distinct' => 'The Package module field has a duplicate value.',
            'package_count.*.required' => 'The Package count field is required.',
        ]);

        foreach ($data['module_id'] as $key => $moduleId) {

            $package_price = Package_price::where('id', $moduleId)->first();

            $institute_pac = $institute->institute_package()->create([
                'invoice_number' => '123',
                'package_id' => $package_price->package_id,
                'package_module_id' => $package_price->package_module_id,
                'duration' => $package_price->expire_day,
                'expiry_date' => Carbon::now()->addDay($package_price->expire_day),
                'price' => $package_price->price,
                'package_count' => $data['package_count'][$key],
                'sub_total' => ($package_price->price) * ($data['package_count'][$key]),
                'discount' => $data['discount'],
                'grand_total' => $data['grand_total'],
                'payment_status' => 'Completed',
            ]);
        }

        if ($request->input('invoice') == 1) {
            $invoiceData = [
                'time' => Carbon::now()->format('d-m-y H:i:s'),
                'admin_name' => $institute->user->name,
                'admin_email' => $institute->user->email,
            ];
            $institute->user->sendInvoiceEmailAdmin($invoiceData);
        }

        return redirect()->route('institute.index')->with('success', 'Package Purchsed Successfully !');
    }

    public function institutePermit($id)
    {
        $institute = Institutes::where('id',$id)->first();
        $user = User::where('id', $institute->user_id)->first();

        if(!$user){
            return redirect()->route('institute.index')->with('success', 'User not exist');
        }

        $group_id = $user->group_id;
        $institute_id = $user->institute_id;
        $privileges = explode(',', $user->privileges);
        return view('institute.privileges', compact('user','group_id', 'privileges','institute_id'));
    }

    public function institutePermitStore($id, Request $request)
    {
        $user = User::where('id', $id)->first();

        if(!$user){
            return redirect()->route('institute.index')->with('success', 'User not exist');
        }
        $user->privileges = implode(',',array_unique($request->input('privileges')));
        $user->save();

        return redirect()->route('institute.index')->with('success', 'Institute Previlegs Add successfully');
    }

}
