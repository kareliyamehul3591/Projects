<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraphController extends Controller{

	protected function highChart($args=[]){

		$attr=[

			'chart'=>[

				'type'=>'column'

			],

			'title'=>[

				'text'=>'Customers Order Graph'

			],

			'subtitle'=>[

				'text'=>'Source: orderpin.com'

			],

			'xAxis'=>[

		        'categories'=>[],

		        'crosshair'=>true,
		        'labels'=>[
		            'rotation'=>-45,
		            'style'=>[
		                'fontSize'=>'13px',
		                'fontFamily'=>'Verdana, sans-serif'
		            ]
		        ]

			],

			'yAxis'=>[
		        'min'=>0,
		        'title'=>[
		            'text'=>'Amount in GBP'
		        ]
			],

			'tooltip'=>[

		        'headerFormat'=>"<span style='font-size:10px'>{point.key}</span><table>",

		        'pointFormat'=>"<tr><td style='color:{series.color};padding:0'>{series.name}: </td><td style='padding:0'><b>&pound;{point.y:.1f}</b></td></tr>",

		        'footerFormat'=>"</table>",

		        'shared'=>true,

		        'useHTML'=>true

			],

			'plotOptions'=>[
		        'column'=>[
		            'pointPadding'=> 0.2,
		            'borderWidth'=> 0
		        ]
			],

			'series'=>[
				[
			        'name'=>'Total Order',
			        'data'=>[]

			    ],[
			        'name'=>'Discount',
			        'data'=>[]

			    ]
			]

		];

		$attr=array_merge($attr, $args);

		return $attr;

	}

	public function customers(Request $request, \App\Restaurant $restaurant){

		$result=\DB::table('user_details')
            ->join('coupons', 'coupons.user_id', '=', 'user_details.user_id')
            ->join('coupon_statistics', 'coupon_statistics.coupon_id', '=', 'coupons.coupon')
            ->select(\DB::raw(
            	"concat(user_details.first_name,' ',user_details.last_name) as customer_name ,
		sum(coupon_statistics.amount) as order_amount,
		sum(coupon_statistics.discount_amount) as discount_amount"
			));


		if($restaurant->id){
			$result=$result->where('coupon_statistics.restaurant_id', $restaurant->id);
		}

		$result=$result->groupBy("user_details.user_id")->orderBy("order_amount", "DESC")->get();

		$graph=$this->highChart();

		$graph['xAxis']['categories']=$result->pluck('customer_name');
		$graph['series'][0]['data']=$result->pluck('order_amount');
		$graph['series'][1]['data']=$result->pluck('discount_amount');

		return $graph;

	}

	public function order_by_month(\App\Restaurant $restaurant){

		$carbon=new \Carbon\Carbon;

		$result=$restaurant->coupon_statistics()
		->select('created_at', 'amount')
		->where('restaurant_id', $restaurant->id)
		->get();

		$data=[];

		foreach($result as $row) array_push($data, [
			$carbon->parse($row->created_at)->timestamp * 1000,
			$row->amount
		]);

		return $data;

	}

	public function order_by_postcode(\App\Restaurant $restaurant, $postcode=NULL){

		$carbon=new \Carbon\Carbon;

		$result=\DB::table('coupon_statistics')
		->select('coupon_statistics.amount', 'coupon_statistics.created_at')
		->join('coupons', 'coupons.coupon', '=', 'coupon_statistics.coupon_id')
		->join('user_details', 'user_details.user_id', '=', 'coupons.user_id')
		->where('coupon_statistics.restaurant_id', $restaurant->id);

		if($postcode){

			$result->where('user_details.post_code', 'like', '%'.$postcode.'%');
			
		}
		
		$result=$result->get();

		$data=[];

		foreach($result as $row) array_push($data, [
			$carbon->parse($row->created_at)->timestamp * 1000,
			$row->amount
		]);

		return $data;

	}

	public function monthly_sales_plot(\App\Restaurant $restaurant){

		$carbon=new \Carbon\Carbon;

		if($restaurant->exists){

			$result=$restaurant->coupon_statistics()
			->select('created_at', 'amount')
			->where('restaurant_id', $restaurant->id)
			->get();

			$data=[];

			foreach($result as $row) array_push($data, [
				$carbon->parse($row->created_at)->timestamp,
				$row->amount
			]);

			return $data;

		}

	}

}