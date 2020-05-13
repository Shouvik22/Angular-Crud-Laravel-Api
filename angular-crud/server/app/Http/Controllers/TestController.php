<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\test;
class TestController extends Controller
{
    
    

    public function store(Request $request)
    {
    	$test 				= new test;
    	$test->firstName	= $request->firstName;
    	$test->email 		= $request->email;
    	$test->save();

    	return $test; 
    }
}
