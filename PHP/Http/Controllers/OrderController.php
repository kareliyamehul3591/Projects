<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller {

    public function saveOrderReview() {

        /*
          This action is to take input from API endpoint saveOrderReview
          and save it to database.
          INPUT:
          restaurants_order_id (required) (restaurants_order_id for save field)
          restaurant_code (required)
          user_email (required)
          food_quality (required)
          service_quality (required)
          delivery_time (required)
          comment (optional)
          OUTPUT:
          conformation message if successful ortherwise return error message.
         */

        $rules = [
            'restaurants_order_id' => 'required|integer',
            'restaurant_code' => 'required|exists:restaurants,code_name',
            'user_email' => 'required|email|exists:users,email',
            'food_quality' => 'required|integer|max:5',
            'service_quality' => 'required|integer|max:5',
            'delivery_time' => 'required|integer|max:5'
        ];

        $validator = \Validator::make(request()->all(), $rules);


        //pass validator errors as errors object for ajax response
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        $restaurant = \App\Restaurant::where('code_name', request('restaurant_code'))->first();
        $user = \App\User::where('email', request('user_email'))->first();

        $order_review = new \App\OrderReview;

        $order_review->restaurants_order_id = request('restaurants_order_id');
        $order_review->restaurant()->associate($restaurant);
        $order_review->user()->associate($user);
        $order_review->food_quality = request('food_quality');
        $order_review->service_quality = request('service_quality');
        $order_review->delivery_time = request('delivery_time');
        $order_review->comment = request('comment');

        if ($order_review->save())
            return response()->json(['message' => 'order review saved successfully'], 200);
        return response()->json(['errors' => 'failed! to save order review try again later'], 422);
    }

//End of saveOrderReview() function

    public function saveOrderInfo() {

        /* Orderpin endpoint to save customers submitted order related information

          METHOD: POST

          ENDPOINT: saveOrderInfo

          INPUT:
          restaurant_code (required)
          restaurants_order_id (required)
          user_email (required)
          coupon (required)
          order_type (required)
          delivery_timestamp (required)
          item_price (required)
          delivery_charge (required)
          discount_amount (rquired)
          total_price (required)
          payment_method (required)
          order_status (required)
          order_instruction (optional)

          OUTPUT:
          On Success: 200 {'message': 'order information saved successfully.'}
          On Error: 422 {validation error messages}
         */

        $validation_rules = [
            'restaurant_code' => 'required|exists:restaurants,code_name',
            'restaurants_order_id' => 'required|integer',
            'user_email' => 'required|email|exists:users,email',
            //'coupon'=>'required|exists:coupons,coupon',
            'order_type' => 'required|exists:order_types,name',
            'delivery_timestamp' => 'required|date',
            'item_price' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|exists:payment_methods,name',
            'order_status' => 'required|exists:order_status,name',
                //'post_code' => 'rquired'
                //'order_instruction'=>'required'
        ];

        $validator = \Validator::make(request()->all(), $validation_rules);

        //pass validator errors as errors object for ajax response
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        //Initiating request related ORM to save data
        $restaurant = \App\Restaurant::where('code_name', request('restaurant_code'))->first();
        $customer = \App\User::where('email', request('user_email'))->first();
        $order_type = \App\OrderType::where('name', request('order_type'))->first();
        $payment_method = \App\PaymentMethod::where('name', request('payment_method'))->first();
        $order_status = \App\OrderStatus::where('name', request('order_status'))->first();

        //Checking whether requested coupon related to requested user or not
        //if($customer->coupon->coupon!=request('coupon')) return response()->json(['errors'=>['coupon'=>'invalid coupon number']], 422);
        //Initiation empty order elequent object to save order information
        $order = new \App\Order;

        $order->restaurant()->associate($restaurant);
        $order->restaurants_order_id = request('restaurants_order_id');
        $order->user()->associate($customer);
        $order->order_type()->associate($order_type);
        $order->delivery_timestamp = Carbon::parse(request('delivery_timestamp'));
        $order->item_price = request('item_price');
        $order->delivery_charge = request('delivery_charge');
        $order->discount_amount = request('discount_amount');
        $order->total_price = request('total_price');
        $order->payment_method()->associate($payment_method);
        $order->order_status()->associate($order_status);
        if (request()->has('post_code')){
            $order->post_code = request('post_code');
        }
        

        if (request()->has('order_instruction'))
            $order->order_instruction = request('order_instruction');

        if ($order->save())
            return response()->json(['message' => 'order information saved successfully.'], 201);
        return response()->json(['errors' => ['message' => 'unable to save order information, try again later.']], 422);
    }

}
