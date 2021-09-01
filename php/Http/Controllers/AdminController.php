<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;

class AdminController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('authAdmin', ['except' => 'index']);
    }

    public function index(Request $request) {

        //Authenticated user object assign to variable for better access
        $auth_user = \Auth::user();

        //initializing $data variable
        $data['carbon'] = new Carbon;

        //On user role type the below data will pass to the view

        if ($auth_user->hasRole('admin')) {

            $data['total_purchase'] = \App\CouponStatistics::sum('amount');
            $data['coupon_discount'] = \App\CouponStatistics::sum('discount_amount');
            $data['restaurant_dropdown'] = \App\Restaurant::all()->pluck('code_name', 'id');
            //$data['coupon_dropdown']=\App\CouponStatistics::groupBy('coupon_id')->pluck('coupon_id','coupon_id');

            $used_coupons = \App\CouponStatistics::groupBy('coupon_id')->pluck('coupon_id');

            $data['user_dropdown'] = \App\User::whereHas('coupon', function($query) use($used_coupons) {
                        $query->whereIn('coupon', $used_coupons);
                    });
            $data['dashboard_title'] = '';
            //dd($data['user_dropdown']->pluck('id'));
            //Fetching related postcode via user_details -> users -> coupons -> coupon_statistics
            $data['postcode_dropdown'] = \App\UserDetails::whereIn('user_id', $data['user_dropdown']->pluck('id'));
        } elseif ($auth_user->hasRole('restaurant_manager')) {
            
            $data['dashboard_title'] = $auth_user->manager_profile->restaurant->name;
            $data['total_purchase'] = $auth_user->manager_profile->restaurant->coupon_statistics()->sum('amount');
            $data['coupon_discount'] = $auth_user->manager_profile->restaurant->coupon_statistics()->sum('discount_amount');
            //$data['coupon_dropdown']=$auth_user->manager_profile->restaurant->coupon_statistics()->groupBy('coupon_id')->pluck('coupon_id','coupon_id');

            $used_coupons = $auth_user->manager_profile->restaurant->coupon_statistics()->groupBy('coupon_id')->pluck('coupon_id');

            $data['user_dropdown'] = \App\User::whereHas('coupon', function($query) use($used_coupons) {
                        $query->whereIn('coupon', $used_coupons);
                    });

            //Fetching related postcode via user_details -> users -> coupons -> coupon_statistics for user managed restaurant
            $data['postcode_dropdown'] = \App\UserDetails::whereIn('user_id', $data['user_dropdown']->pluck('id'));
        } else {
            $data['dashboard_title'] = '';
            $data['total_purchase'] = 0.00;
            $data['coupon_discount'] = 0.00;
        }

        //List of reports name
        $data['coupon_statistics'] = null;
        $data['particular_coupon_report'] = null;
        $data['cumulative_data_on_customer'] = null;
        $data['cumulative_data_on_postcode'] = null;
        $data['best_selling_items'] = null;
        $data['review_statistics'] = null;

        //Code for report generation
        if ($request->isMethod('post')) {

            //Proccess requested report according to report name
            if ($request->has('coupon_statistics')) {

                $this->validate($request, [
                    'start_date' => 'required|date',
                    'end_date' => 'required|date'
                ]);

                //dd($request->has('restaurant_id'));

                if ($auth_user->hasRole('admin')) {

                    $coupon_statistics = \App\CouponStatistics::whereBetween('created_at', [Carbon::parse(request('start_date')), Carbon::parse(request('end_date'))]);

                    //Check whether restaurant select field select a restaurant or not
                    if ($request->has('restaurant_id'))
                        $coupon_statistics = $coupon_statistics->where('restaurant_id', request('restaurant_id'))->with('restaurant');

                    $data['coupon_statistics'] = $coupon_statistics;
                }elseif ($auth_user->hasRole('restaurant_manager')) {

                    $data['coupon_statistics'] = $auth_user->manager_profile->restaurant->coupon_statistics()->whereBetween('created_at', [Carbon::parse(request('start_date')), Carbon::parse(request('end_date'))])->with('restaurant');
                }
            } elseif ($request->has('particular_coupon_report')) {

                $this->validate($request, [
                    'coupon_number' => 'required'
                ]);

                if ($auth_user->hasRole('admin')) {

                    $data['particular_coupon_report'] = \App\CouponStatistics::where(
                                    'coupon_id', request('coupon_number')
                    );
                } elseif ($auth_user->hasRole('restaurant_manager')) {

                    $data['particular_coupon_report'] = $auth_user->manager_profile->restaurant
                                    ->coupon_statistics()->where(
                            'coupon_id', request('coupon_number')
                    );
                }
            } elseif ($request->has('cumulative_data_on_customer')) {

                $this->validate($request, [
                    'start_date_form3' => 'required|date',
                    'end_date_form3' => 'required|date'
                        ], [ //Custome error message
                    'required' => 'This field can not be blank.',
                    'date' => 'Insert valid date'
                ]);

                $coupon_id = null;
                if ($request->has('user_id'))
                    $coupon_id = \App\User::find(request('user_id'))->coupon->coupon;

                if ($auth_user->hasRole('admin')) {

                    if ($coupon_id) {
                        $coupon_statistics = \App\CouponStatistics::where('coupon_id', $coupon_id)->whereBetween('created_at', [Carbon::parse(request('start_date_form3')), Carbon::parse(request('end_date_form3'))]);
                    } else {
                        $coupon_statistics = \App\CouponStatistics::whereBetween('created_at', [Carbon::parse(request('start_date_form3')), Carbon::parse(request('end_date_form3'))]);
                    }
                } elseif ($auth_user->hasRole('restaurant_manager')) {

                    if ($coupon_id) {
                        $coupon_statistics = $auth_user->manager_profile->restaurant->coupon_statistics()->where('coupon_id', $coupon_id)->whereBetween('created_at', [Carbon::parse(request('start_date_form3')), Carbon::parse(request('end_date_form3'))]);
                    } else {
                        $coupon_statistics = $auth_user->manager_profile->restaurant->coupon_statistics()->whereBetween('created_at', [Carbon::parse(request('start_date_form3')), Carbon::parse(request('end_date_form3'))]);
                    }
                }

                $data['cumulative_data_on_customer'] = $coupon_statistics;
            } elseif ($request->has('cumulative_data_on_postcode')) {

                $this->validate($request, [
                    'start_date_form4' => 'required|date',
                    'end_date_form4' => 'required|date',
                        //'post_code'=>'required'
                        ], [ //Custome error message
                    'required' => 'This field can not be blank.',
                    'date' => 'Insert valid date'
                ]);

                $post_code = request('post_code');

                if ($post_code) {

                    $postcode_related_user = \App\User::whereHas('detail', function($query) use($post_code) {
                                $query->where('post_code', $post_code);
                            });

                    $user_related_coupon = \App\Coupon::whereIn('user_id', $postcode_related_user->pluck('id'));
                }

                if ($auth_user->hasRole('admin')) {

                    if ($post_code) {
                        $coupon_statistics = \App\CouponStatistics::whereIn('coupon_id', $user_related_coupon->pluck('coupon'))->whereBetween('created_at', [Carbon::parse(request('start_date_form4')), Carbon::parse(request('end_date_form4'))]);
                    } else {
                        $coupon_statistics = \App\CouponStatistics::whereBetween('created_at', [Carbon::parse(request('start_date_form4')), Carbon::parse(request('end_date_form4'))]);
                    }
                } elseif ($auth_user->hasRole('restaurant_manager')) {

                    if ($post_code) {
                        $coupon_statistics = $auth_user->manager_profile->restaurant->coupon_statistics()->whereIn('coupon_id', $user_related_coupon->pluck('coupon'))->whereBetween('created_at', [Carbon::parse(request('start_date_form4')), Carbon::parse(request('end_date_form4'))]);
                    } else {
                        $coupon_statistics = $auth_user->manager_profile->restaurant->coupon_statistics()->whereBetween('created_at', [Carbon::parse(request('start_date_form4')), Carbon::parse(request('end_date_form4'))]);
                    }
                }

                $data['cumulative_data_on_postcode'] = $coupon_statistics;
            } elseif ($request->has('best_selling_items')) {

                $this->validate($request, [
                        //'start_date_form5'=>'date',
                        //'end_date_form5'=>'date',
                        //'restaurant_id'=>'required|integer'
                        ], [ //Custome error message
                    'required' => 'This field can not be blank.',
                    'date' => 'Insert valid date'
                ]);

                if ($auth_user->hasRole('admin')) {

                    $restaurant = \App\Restaurant::find(request('restaurant_id'));
                } elseif ($auth_user->hasRole('restaurant_manager')) {

                    $restaurant = $auth_user->manager_profile->restaurant;
                }

                $data['best_selling_items'] = null;

                //Check wheather restaurant website exists or not
                if ($restaurant->website) {
                    //dd($restaurant->website);
                    //Used GuzzelHttp pakage to fetch bestselling item from restaurant related endpoint
                    //Testing request time format
                    //dd('From: '.Carbon::parse(request('start_date_form5'))->toDateString().' To: '.Carbon::parse(request('end_date_form5'))->toDateString());
                    //

                    $query_string['limit'] = $request->limit;

                    //dd($query_string);

                    if ($request->start_date_form5 && $request->end_date_form5) {

                        $query_string['start_date'] = Carbon::parse(request('start_date_form5'))->toDateString();
                        $query_string['end_date'] = Carbon::parse(request('end_date_form5'))->toDateString();
                    }

                    //dd($restaurant->website);

                    $guzzleHttp = new Client(['base_uri' => $restaurant->website]);
                    //$guzzleHttp=new Client(['base_uri'=>'http://localhost/http/get_request.php']);

                    $httpRequest = $guzzleHttp->request(
                            'GET', 'order_details/bestSellingItems', [
                        'auth' => ['orderpin', 'orderpin_31641'],
                        'query' => $query_string,
                        'http_errors' => false
                            ]
                    );

                    //dd(json_decode($httpRequest->getBody()));

                    if (json_decode($httpRequest->getBody())) {

                        $data['best_selling_items'] = json_decode($httpRequest->getBody());
                    }
                }
            } elseif ($request->has('review_statistics')) {

                $this->validate($request, [
                    'start_date_form6' => 'required|date',
                    'end_date_form6' => 'required|date',
                        //'restaurant_id'=>'required|integer'
                        ], [ //Custome error message
                    'required' => 'This field can not be blank.',
                    'date' => 'Insert valid date'
                ]);

                if ($auth_user->hasRole('admin')) {

                    if ($request->has('restaurant_id')) {
                        $data['review_statistics'] = \App\OrderReview::where('restaurant_id', request('restaurant_id'))->whereBetween('created_at', [Carbon::parse(request('start_date_form6')), Carbon::parse(request('end_date_form6'))]);
                    } else {
                        $data['review_statistics'] = \App\OrderReview::whereBetween('created_at', [Carbon::parse(request('start_date_form6')), Carbon::parse(request('end_date_form6'))]);
                    }
                } elseif ($auth_user->hasRole('restaurant_manager')) {

                    $data['review_statistics'] = $auth_user->manager_profile->restaurant->order_reviews()->whereBetween('created_at', [Carbon::parse(request('start_date_form6')), Carbon::parse(request('end_date_form6'))]);
                }

                //dd($data['order_reviews']->get());
            }//end of elseif($request->has('review_statistics'))
        } else {

            if ($auth_user->hasRole('admin')) {

                $data['coupon_statistics'] = new \App\CouponStatistics;
            } elseif ($auth_user->hasRole('restaurant_manager')) {

                $data['coupon_statistics'] = $auth_user->manager_profile->restaurant->coupon_statistics()->with('restaurant');
            }
        }//end of $request->isMethod('post')

        return view('admin.index', $data);
    }

//End of index function

    /* Old index action deactivated due to the fact requirement has been changed D_19-10-2017
      public function index(){

      $data['coupon_used_by_restaurants']=DB::table('coupon_statistics')
      ->join('restaurants','coupon_statistics.restaurant_id','=','restaurants.id')
      ->select('restaurants.code_name',DB::raw('COUNT(coupon_statistics.coupon_id) as coupon_used'))->groupBy('restaurants.code_name')->get();

      //dd($data['coupon_used_by_restaurants']);

      $data['coupon_used']=DB::table('coupon_statistics')->select('coupon_id',DB::raw('COUNT(coupon_id) as coupon_used'))->groupBy('coupon_id')->get();

      $data['coupon_statistics']=DB::table('coupon_statistics')->select('coupon_id',DB::raw('COUNT(coupon_id) as coupon_used'),DB::raw('SUM(amount) as purchase_amount'),DB::raw('SUM(discount_amount) as discount_amount'))->groupBy('coupon_id')->get();

      $data['table']=\App\CouponStatistics::with('restaurant')->get();

      $data['carbon']=new \Carbon\Carbon;

      return view('admin.index',$data);
      }
     */

    public function users() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\User', ['id' => 'ID', 'name' => 'Name', 'email' => 'Email Address', 'created_at' => 'Created At'])
        ];
        return view('admin.users', $data);
    }

    public function add_user() {

        $data['user'] = new \App\User;
        $data['roles'] = \App\Role::all()->pluck('name', 'id')->forget(3);
        return view('admin.add_user', $data);
    }

    public function store_user() {

        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array'
        ]);

        $user = new \App\User;
        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));

        if ($user->save()) {
            $user->roles()->sync(request('roles'));
            return back()->with(['success' => 'User created successfully!.']);
        }return back()->with(['failed' => 'Sorry!, unable to create user, try again later.']);
    }

    public function user_detail(\App\User $id) {

        $data = [
            'user' => $id,
            'restaurant' => \App\Restaurant::pluck('name', 'id')
        ];

        return view('admin.user_detail', $data);
    }

    public function update_user_detail(\App\User $id) {

        //dd(request()->all());

        /* $this->validate(request(),[
          'restaurant_id'=>'',
          'first_name'=>'',
          'last_name'=>'',
          'gender'=>'',
          'address_line1'=>'',
          'address_line2'=>'',
          'post_code'=>'',
          'city'=>'',
          'country'=>'',
          'phone'=>'',
          'mobile'=>'',
          'birthday'=>'',
          'partner_birthday'=>'',
          'anniversary_date'=>'',
          'special_day'=>''
          ]); */

        $userDetail = $id->detail;

        //dd($userDetail);

        $userDetail->restaurant_id = request('restaurant_id');
        $userDetail->first_name = request('first_name');
        $userDetail->last_name = request('last_name');
        $userDetail->gender = request('gender');
        $userDetail->address_line1 = request('address_line1');
        $userDetail->address_line2 = request('address_line2');
        $userDetail->post_code = request('post_code');
        $userDetail->city = request('city');
        $userDetail->country = request('country');
        $userDetail->phone = request('phone');
        $userDetail->mobile = request('mobile');
        $userDetail->birthday = request('birthday');
        $userDetail->partner_birthday = request('partner_birthday');
        $userDetail->anniversary_date = request('anniversary_date');
        $userDetail->special_day = request('special_day');

        if ($userDetail->save())
            return back()->with('success', 'Client edited successfully.');
        else
            return back()->with('failed', 'Form submisssion failed!.');
    }

    public function users_details() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\UserDetails', ['id' => 'ID', 'first_name' => 'First Name', 'last_name' => 'Last Name', 'gender' => 'Gender', 'post_code' => 'Post Code', 'is_active' => 'Active'])
        ];
        return view('admin.users_details', $data);
    }

    public function coupons() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\Coupon', ['id' => 'ID', 'coupon' => 'Coupon Number', 'is_active' => 'Active', 'created_at' => 'Created At'])
        ];
        return view('admin.coupons', $data);
    }

    public function coupon_groups() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\CouponGroup', ['id' => 'ID', 'name' => 'Group Name', 'description' => 'Description', 'is_active' => 'Active', 'created_at' => 'Created At'])
        ];
        return view('admin.coupon_groups', $data);
    }

    public function coupon_discounts() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\CouponDiscounts', ['id' => 'ID', 'expiration_date' => 'Expiration Date', 'percentage' => 'Percentage', 'is_active' => 'Active', 'created_at' => 'Created At'])
        ];
        return view('admin.coupon_discounts', $data);
    }

    public function coupon_statistics() {
        $data = [
            'carbon' => new \Carbon\Carbon,
            'paginate' => new \App\Helpers\Paginate('App\CouponStatistics', ['id' => 'ID', 'coupon_id' => 'Coupon Number', 'discount_percentage' => 'Discount Percentage', 'amount' => 'Amount', 'discount_amount' => 'Discount Amount', 'is_active' => 'Active', 'created_at' => 'Created At'])
        ];
        return view('admin.coupon_statistics', $data);
    }

    public function users_statistics() {


        $data['users_details'] = \App\UserDetails::with(['user', 'restaurant'])->get();

        $data['users_group_by_city'] = \App\UserDetails::select(DB::raw('COUNT(`id`) as users'), 'city')->groupBy('city')->get();

        $data['user_group_by_restaurants'] = DB::table('user_details')
                        ->join('restaurants', 'user_details.restaurant_id', '=', 'restaurants.id')
                        ->select('restaurants.code_name as restaurant_code_name', DB::raw('COUNT(user_details.id) as total_user'))->groupBy('restaurants.code_name')->get();

        //dd($data['user_group_by_restaurants']);

        $data['carbon'] = new \Carbon\Carbon;

        return view('admin.users_statistics', $data);
    }

    public function add_edit_submenu_form(\App\SubMenu $id) {

        if ($id)
            $data['sub_menu'] = $id;
        else
            $data['sub_menu'] = new \App\SubMenu;
        $data['menus'] = \App\Menu::all()->pluck('title', 'name');
        //dd($data);
        return view('admin.submenu_add_edit_form', $data);
    }

    public function add_submenu() {

        $this->validate(request(), [
            'menu_id' => 'integer',
            'name' => 'required|max:191|unique:sub_menus',
            'title' => 'required|max:191',
            'sorting_order' => 'required|integer',
            'url' => 'required|max:191'
        ]);

        $submenu = new \App\SubMenu;

        if (request()->has('menu_id')) {
            $menu = \App\Menu::find(request('menu_id'));
            $submenu->menu()->associate($menu);
        }

        $submenu->name = request('name');
        $submenu->title = request('title');
        $submenu->sorting_order = request('sorting_order');
        $submenu->url = request('url');

        if ($submenu->save())
            return back()->with(['success' => 'Sub Menu Created Successfully!.']);
        return back()->with(['failed' => 'Sorry! form submission failed, try again later.']);
    }

    public function edit_submenu() {
        
    }

}
