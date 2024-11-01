<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterBusinessController extends Controller
{
    function registerBusiness(Request $request){
        Log::info('Register business endpoint');
        Log::info($request->all());

        if(isset($request)){
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $businessName = $request->businessName;
            $address = $request->address;
            $industryId = $request->industry;
            $industry = DB::table('public.Industries')->find($industryId);
            DB::table('public.Businesses')->insert([
                'owner_name' => $firstName .' '. $lastName,
                'business_name' => $businessName,
                'address' => $address,
                'industry_id' => $industryId,
                'user_id' => $request->user
            ]);
            return response()->json(['success' => true, 'message' => 'Business registered successfully'], 200);
        }
        return response()->json(['success' => false, 'message' => 'There was an error registering the business'], 400);
    }
}
