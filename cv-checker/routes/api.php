<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/cv-result', [App\Http\Controllers\CvResultController::class, 'store'])
    ->middleware(\App\Http\Middleware\ValidateN8nToken::class);
