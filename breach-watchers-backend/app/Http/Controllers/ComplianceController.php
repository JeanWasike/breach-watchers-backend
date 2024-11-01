<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDF; 
use Carbon\Carbon;

class ComplianceController extends Controller
{
    function getCompliance(){
        $industries = DB::table('public.Industries')->get(); 
        if(isset($industries)){
            return response()->json(['success' => true, 'message' => 'Industries fetched successfully', 'data' => $industries], 200);
        }
        return response()->json(['success' => false, 'message' => 'Industries not found'], 200);
    }
     function getStages(){
        
        $stages = DB::table('public.Stages')->get(); 
        if(isset($stages)){
            return response()->json(['success' => true, 'message' => 'Stages fetched successfully', 'data' => $stages], 200);
        }
        return response()->json(['success' => false, 'message' => 'Stages not found'], 400);
    }
    function getQuestions(Request $request, $stage)
    {
        Log::info('Getting questions');
        $userId = $request->user;
        $business = DB::table('public.Businesses')->where('user_id', $userId)->first();
    
        if (!$business) {
            return response()->json(['success' => false, 'message' => 'Business information not found'], 400);
        }
    
        $industryId = $business->industry_id;
        $questions = DB::table('public.ComplianceQuestions')
            ->where('stage_id', $stage)
            ->where(function ($query) use ($industryId) {
                $query->where('industry_id', $industryId)
                      ->orWhereNull('industry_id');
            })
            ->get();
        if ($questions->isNotEmpty()) {
            Log::info($questions);
            return response()->json(['success' => true, 'message' => 'Questions fetched successfully', 'data' => $questions], 200);
        }
    
        return response()->json(['success' => false, 'message' => 'No questions found'], 400);
    }
    function submitAnswers(Request $request)
    {
        Log::info('Submitting answers endpoint');
        Log::info($request);
        if(isset($request->user_id) && isset($request->answers)){
            foreach ($request->answers as $stages => $answers) {
                $stage = DB::table('public.Stages')->find($stages);
                $stageName = $stage->stage;
                foreach ($answers as $questionId => $choices) {
                    Log::info($questionId);
                    $question = DB::table('public.ComplianceQuestions')->find($questionId);
                  
            
                    if ($question) {
                        Log::info('question set');
                        $compliantChoices1 = json_decode($question->compliant_choices, true); // Assuming compliant_choices is an array
                        if(is_null($compliantChoices1)){
                            continue;
                        }else{
                            $compliantChoices = array_values($compliantChoices1); // This will give you a simple array of compliant choices

                            
                            $isCompliant = array_diff($choices, $compliantChoices) === [];
                            Log::info($isCompliant);
                            $report[] = [
                                'user_id' => $request->userId,
                                'stage' =>  $stageName,
                                'question' => $question->question,
                                'selected_choices' => $choices,
                                'compliant_choices' => $compliantChoices,
                                'is_compliant' => $isCompliant == 1 ? 'Compliant' : 'Non-compliant',
                            ];

                        }
                    }
                }
               
            }
        }
        $data = [
            'user_id' => $request->userId,
            'compliance_report' => $report,
        ];
    
        $pdf = PDF::loadView('report', $data);
        $filePath = storage_path("app/public/reports/compliance_report_{$request->userId}.pdf");
        $pdf->save($filePath);
        $date = Carbon::now()->format('d F Y');
        Log::info($date);
        DB::table('public.Reports')->insert([
            'user_id' => $request->user_id,
            'report_name' => 'Compliance Report '.$date,
            'report_link' => asset("storage/reports/compliance_report_{$request->userId}.pdf")
        ]);
            

         // Return the PDF URL and report data as a JSON response
    return response()->json([
        'success' => true,
        'report' => $report,
        'pdf_url' => asset("storage/reports/compliance_report_{$request->userId}.pdf"),
    ]);

   
        // $userId = $request->user;
        // $business = DB::table('public.Businesses')->where('user_id', $userId)->first();
    
        // if (!$business) {
        //     return response()->json(['success' => false, 'message' => 'Business information not found'], 400);
        // }
    
        // $industryId = $business->industry_id;
        // $questions = DB::table('public.ComplianceQuestions')
        //     ->where('stage_id', $stage)
        //     ->where(function ($query) use ($industryId) {
        //         $query->where('industry_id', $industryId)
        //               ->orWhereNull('industry_id');
        //     })
        //     ->get();
        // if ($questions->isNotEmpty()) {
        //     Log::info($questions);
        //     return response()->json(['success' => true, 'message' => 'Questions fetched successfully', 'data' => $questions], 200);
        // }
    
        // return response()->json(['success' => false, 'message' => 'No questions found'], 400);


    }
    
}
