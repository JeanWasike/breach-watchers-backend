<?php

use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\RegisterBusinessController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Log;
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/user', function (Request $request, SupabaseService $supabaseService) {
    // Get the authenticated user's ID
    Log::info($request);
    $userId = $request->user()->id;

    // Fetch user details from Supabase using the service
    $user = $supabaseService->getUserById($userId);

    if ($user) {
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'User not found',
    ], 404);
});
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
//Fetch Industries
Route::get('/get-industries', [IndustryController::class, 'getIndustries']);
//Fetch Compliance stages
Route::get('/get-stages', [ComplianceController::class, 'getStages']);
Route::post('/register-business', [RegisterBusinessController::class, 'registerBusiness']);
Route::post('/get-questions/{stage}', [ComplianceController::class, 'getQuestions']);
Route::post('/submit-answers', [ComplianceController::class, 'submitAnswers']);
Route::post('/recent-reports', [ReportController::class, 'getReports']);

