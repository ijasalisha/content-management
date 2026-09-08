<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use illuminate\Foundations\Http\FormRequest;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
});

Route::get('/test-page-view', function () {
    return response()->json(['message' => 'You have access to view the test page.']);
})->middleware(['auth:sanctum', 'privilege:pages.view']);

Route::delete('/test-page-delete', function () {
    return response()->json(['message' => 'You have access to delete the test page.']);
})->middleware(['auth:sanctum', 'privilege:pages.delete']);