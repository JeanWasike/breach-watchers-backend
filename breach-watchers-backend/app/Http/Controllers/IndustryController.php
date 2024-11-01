<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndustryController extends Controller
{
    function getIndustries(){
        $industries = DB::table('public.Industries')->get(); 
        if(isset($industries)){
            return response()->json(['success' => true, 'message' => 'Industries fetched successfully', 'data' => $industries], 200);
        }
        return response()->json(['success' => false, 'message' => 'Industries not found'], 200);
    }
}
