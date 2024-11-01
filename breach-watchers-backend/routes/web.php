<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['success' => true]);
});
require __DIR__.'/auth.php';