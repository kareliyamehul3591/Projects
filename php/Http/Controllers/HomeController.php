<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Restaurant;
use App\CouponDiscounts;

class HomeController extends Controller {

    public function __construct(){

        $this->middleware(['auth', 'authManager'], ['only' => ['test']]);

    }

    public function index() {
        return view('home.index');
    }

    public function apply() {
        return view('home.apply');
    }

    public function work() {
        return view('home.work');
    }

    public function price() {
        return view('home.price');
    }

    public function team() {
        return view('home.team');
    }

    public function contact() {
        return view('home.contact');
    }

    public function saveContact() {

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'msg' => 'required'
        ]);

        Mail::to('salim@serviceontheweb.co.uk')
                ->queue(new \App\Mail\ContactUs(request('name'), request('email'), request('msg')));

        return back()->with('success', 'Form submitted successfully.');
    }

    public function migrate() {
        $this->fix_name('NAWABSKITCHEN_STARTFORD','NAWABSKITCHEN_STRATFORD');
        $this->fix_name('BANGKOKDINNER_STARTFORD','BANGKOKDINNER_STRATFORD');
        $this->set_up_restaurant('SAKURASUSHI_DAGENHAM');
        $this->set_up_restaurant('SAKURASUSHI_STRATFORD');
        $this->set_up_restaurant('BLUEBURGER_DAGENHAM');
        $this->set_up_restaurant('BLUEBURGER_STRATFORD');
        $this->set_up_restaurant('BANGKOKDINNER_STRATFORD');
        $this->set_up_restaurant('BANGKOKDINNER_DAGENHAM');
        $this->set_up_restaurant('NAWABSKITCHEN_STRATFORD');
        $this->set_up_restaurant('NAWABSKITCHEN_DAGENHAM');
        $this->set_up_restaurant('INTERNETPOS');
    }
    
    private function fix_name($restaurant_code,$correct_retaurant_code){
        $restaurant = Restaurant::where('code_name', $restaurant_code)->first();
        if ($restaurant) {
            $restaurant->code_name = $correct_retaurant_code;
            $restaurant->save();
        }
    }
    
    private function set_up_restaurant($restaurant_code){
        $find = Restaurant::where('code_name', $restaurant_code)->first();
        if (!$find) {
            $restaurant = new Restaurant();
            $restaurant->code_name = $restaurant_code;
            $restaurant->name = str_replace('_', ' ', $restaurant_code);
            $restaurant->postcode = 'E15 2SP';
            $restaurant->discount_type_id = 1;
            $restaurant->is_active = 1;
            $restaurant->save();
            $restaurant_id = $restaurant->id;
        } else {
            $restaurant_id = $find->id;
        }
        $find = CouponDiscounts::where([['restaurant_id', $restaurant_id], ['coupon_group_id', 1]])
                ->first();
        if(!$find){
            $restaurant_discount = new CouponDiscounts();
            $restaurant_discount->coupon_group_id = 1;
            $restaurant_discount->percentage = 20;
            $restaurant_discount->restaurant_id = $restaurant_id;
            $restaurant_discount->is_active = 1;
            $restaurant_discount->save();
        }else{
            $find->restaurant_id = $restaurant_id;
            $find->save();
        }
    }

    public function test(){

    	//dd(public_path());

        return view('home.test');

    }

}
