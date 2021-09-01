<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Schema;

class XeditController extends Controller{

    public function __construct(){
    	$this->middleware('authAdmin');
    }

    public function index(){
    	list($table,$id)=explode(':', request('pk'));
    	$field=request('name');
    	$value=request('value');

        logger(request()->all());
    	
        /*logger(['table'=>$table,'id'=>$id,'field'=>$field,'value'=>$value]);
    	
    	if(!\Schema::hasColumn($table,$field)) return response('Field not exists!', 400);
    	if(\DB::table($table)->where('id',$id)->update([$field=>$value])) return response('Field updated successfully!', 201);
    	else return response('Insert all required field.', 400);*/

        return response('Suspanded!', 400);
    }

}