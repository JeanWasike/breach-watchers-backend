<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    function getReports(Request $request){
        $reports = DB::table('public.Reports')->where('user_id', $request->user)->get(); 
        if(isset($reports)){
            return response()->json(['success' => true, 'message' => 'Reports fetched successfully', 'data' => $reports], 200);
        }
        return response()->json(['success' => false, 'message' => 'Reports not found'], 200);
    }
}
