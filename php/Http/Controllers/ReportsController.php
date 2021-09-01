<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use App\Order;
use DateTime;
use Illuminate\Support\Facades\DB;
use Auth;

class ReportsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'authManager']);
    }

    public function index() {
        $user = Auth::user();
        if ($user->hasRole("admin")) {
            
            return $user->detail;
            $data = [
                'carbon' => new \Carbon\Carbon,
                'paginate' => new \App\Helpers\Paginate('App\Restaurant', ['id' => 'ID', 'name' => 'Name', 'id' => 'alias_id'])
            ];
            return view('graph.restaurants', $data);
        } else {
            $data = [
                'restaurant' => Restaurant::find($user->manager_profile->restaurant_id)
            ];
            return view('graph.managers_restaurant', $data);
        }
    }

    public function monthly_sales(Request $request, $restaurant_id = 'all', $year = null) {
        $user = Auth::user();
        if(!$user->hasRole("admin")){
            $restaurant_id = $user->manager_profile->restaurant_id;
        }
        $year = (int) $year;
        if (!($year > 1000)) {
            $year = date('Y');
        }
        $start_month = 1;
        $end_month = 12;
        $orders = [];
        if ($restaurant_id !== 'all') {
            $restaurant = Restaurant::findOrFail($restaurant_id);
        } else {
            $restaurant = null;
        }
        for ($count = $start_month; $count <= $end_month; $count++) {
            $date = $year . '-' . $count;
            if ($restaurant) {
                $order = Order::whereRaw('DATE_FORMAT(created_at, "%Y-%c") = ?', [$date])
                                ->where('restaurant_id', $restaurant_id)->sum('total_price');
            } else {
                $order = Order::whereRaw('DATE_FORMAT(created_at, "%Y-%c") = ?', [$date])->sum('total_price');
            }
            $orders[] = $order;
        }
        $selects[] = 'count(id) as no_records';
        $selects[] = 'IFNULL(sum(total_price),0) as total';
        $selects[] = 'IFNULL(round(avg(total_price),2),0) as average';
        if ($restaurant) {
            $stats = DB::table('orders')->selectRaw(implode(',', $selects))
                            ->where('restaurant_id', $restaurant_id)
                            ->whereRaw('DATE_FORMAT(created_at, "%Y") = ?', [$year])->first();
        } else {
            $stats = DB::table('orders')->selectRaw(implode(',', $selects))
                            ->whereRaw('DATE_FORMAT(created_at, "%Y") = ?', [$year])->first();
        }

        $data['restaurant_id'] = $restaurant_id;
        $data['sales_data'] = $orders;
        $data['year'] = $year;
        $data['previous_year'] = $year - 1;
        $data['next_year'] = $year + 1;
        $data['stats'] = $stats;
        return view('graph.monthly_sales', $data);
    }

    public function weekly_sales(Request $request, $restaurant_id = 'all', $date = null) {
        $user = Auth::user();
        if(!$user->hasRole("admin")){
            $restaurant_id = $user->manager_profile->restaurant_id;
        }
        $required_format = 'Y-m-d';
        $d = DateTime::createFromFormat($required_format, $date);
        if (!($d && $d->format($required_format) == $date)) {
            $date = date('Y-m-d');
        }
        $day = 0; //0 for starting day = sunday


        $dayofweek = date('w', strtotime($date));
        $start_date = date('Y-m-d', strtotime(($day - $dayofweek) . ' day', strtotime($date)));
        $next_date = date('Y-m-d', strtotime($start_date . ' +7 days'));
        if ($restaurant_id !== 'all') {
            $restaurant = Restaurant::findOrFail($restaurant_id);
        } else {
            $restaurant = null;
        }
        $orders = [];
        for ($count = 0; $count < 7; $count++) {
            $date_counter = date('Y-m-d', strtotime($start_date . ' +' . $count . ' days'));
            if ($restaurant) {
                $orders[] = Order::whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") = ?', [$date_counter])
                        ->where('restaurant_id', $restaurant_id)
                        ->sum('total_price');
            } else {
                $orders[] = Order::whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") = ?', [$date_counter])
                        ->sum('total_price');
            }
        }
        $selects[] = 'count(id) as no_records';
        $selects[] = 'IFNULL(sum(total_price),0) as total';
        $selects[] = 'IFNULL(round(avg(total_price),2),0) as average';


        if ($restaurant) {
            $stats = DB::table('orders')->selectRaw(implode(',', $selects))
                    ->where('restaurant_id', $restaurant_id)
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= ?', [$start_date])
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") < ?', [$next_date])
                    ->first();
        } else {
            $stats = DB::table('orders')->selectRaw(implode(',', $selects))
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= ?', [$start_date])
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") < ?', [$next_date])
                    ->first();
        }

        $data['restaurant_id'] = $restaurant_id;
        $data['sales_data'] = $orders;
        $data['date'] = $start_date;
        $data['previous_date'] = date('Y-m-d', strtotime($start_date . ' -7 days'));
        $data['next_date'] = $next_date;
        $data['stats'] = $stats;
        return view('graph.weekly_sales', $data);
    }

    public function post_code($restaurant_id = 'all') {
        $user = Auth::user();
        if(!$user->hasRole("admin")){
            $restaurant_id = $user->manager_profile->restaurant_id;
        }
        if ($restaurant_id !== 'all') {
            $restaurant = Restaurant::findOrFail($restaurant_id);
        } else {
            $restaurant = null;
        }
        $limit = 5;
        if ($restaurant) {
            $orders = DB::table('orders')
                    ->select(\DB::raw("COUNT('id') AS total_orders,LEFT(post_code,4) as outer_post_code,SUM(total_price) as total"))
                    ->where('orders.post_code', '!=', '')
                    ->where('restaurant_id', $restaurant_id)
                    ->orderBy('total_orders', 'desc')
                    ->groupBy('outer_post_code')
                    ->take($limit)
                    ->get();
        } else {
            $orders = DB::table('orders')
                    ->select(\DB::raw("COUNT('id') AS total_orders,LEFT(post_code,4) as outer_post_code"))
                    ->where('orders.post_code', '!=', '')
                    ->orderBy('total_orders', 'desc')
                    ->groupBy('outer_post_code')
                    ->take($limit)
                    ->get();
        }
        $stats['labels'] = [];
        $stats['data'] = [];
        foreach ($orders as $order) {
            $stats['labels'][] = $order->outer_post_code;
            $stats['data'][] = $order->total_orders;
        }
        $data['stats'] = $stats;
        $data['limit'] = count($orders);
        $data['orders'] = $orders;
        return view('graph.postcode_stats', $data);
    }

    public function seed() {
        return; // no more needed and can be deleted safely
        $start_date = '1483228800';
        $end_date = '1522281600';


        $restaurant_code = 'INTERNETPOS';
        $restaurant = Restaurant::where('code_name', $restaurant_code)->first();
        $restaurant_id = $restaurant->id;
        $data = [];
        for ($x = 0; $x < 1000; $x ++) {
            $item_price = rand('50', '300');
            $discount_amount = ($item_price * 10 ) / 100;
            $total_price = $item_price - $discount_amount;
            $order_type_id = rand(1, 3);
            $created_at_stamp = rand($start_date, $end_date);
            $created_at = date('Y-m-d H:i:s', $created_at_stamp);
            $data[] = [
                'restaurant_id' => $restaurant_id, 'item_price' => $item_price,
                'discount_amount' => $discount_amount, 'total_price' => $total_price,
                'order_type_id' => $order_type_id, 'created_at' => $created_at
            ];
        }

        DB::table('orders')->insert(
                $data
        );
    }

    public function set_post_codes() {
        $random_post_codes = explode(',', 'CV8 3BF,UB3 2LH,GL50 9UG,TW15 3PG,LA23 1JE,TA22 9RQ,GU6 8AG,TS14 6QD,CM1 1SF,W1U 3SF,BA6 9TA,N11 3PX,G65 0LF,L33 7RJ,TF3 5GA,BS4 2TS,TN8 6JS,NG5 5RL,M27 8RF,SK6 8EH,CH62 1HW,GL52 6HZ,GL17 0EZ,WD7 9DG,CA8 9EL,CH43 3BN,RG21 7RH,CT6 7ED,NW1 4LA,OL15 8HB,CT6 7EA,BB18 6YB,SN2 7RX,HG2 9AY,BD15 9BX,LS28 9GB,DY10 2JL,HG4 1TT,WD23 3DY,SE20 8JD,BT41 4JU,HP3 8JR,NR7 9NT,HA3 7QT,EH11 2LX,NG22 0JD,N1 8SB,NR19 2EH,G78 2JU,WR3 8ET');

        $restaurant_code = 'INTERNETPOS';
        $restaurant = Restaurant::where('code_name', $restaurant_code)->first();
        $restaurant_id = $restaurant->id;

        $orders = Order::where('restaurant_id', $restaurant_id)->get();

        foreach ($orders as $order) {
            $random_key = rand(0, count($random_post_codes) - 1);
            $order->post_code = $random_post_codes[$random_key];
            $order->save();
        }
        return $orders;
    }

}
