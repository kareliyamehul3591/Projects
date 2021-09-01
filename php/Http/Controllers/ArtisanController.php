<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtisanController extends Controller{

    public function __construct(){

        $this->middleware('auth');

    }

	public function index(){

    	return view('artisan.index');

    }

    public function execute(){

    	$command=request('artisan_command');
    	$key=request('key');
    	$value=request('value');

    	if($key && $value) \Artisan::call($command, [$key=>$value]);
    	else \Artisan::call($command);
    	$result=\Artisan::output();
    	\Log::debug($result);
    	if($result) return back()->with('success', $result);
        else return back()->with('failed', 'Command execution failed!.');

    }

}
