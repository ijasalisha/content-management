<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use illuminate\Foundations\Http\FormRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });

    Route::get('/pages', [PageController::class, 'index'])->middleware('privilege:pages.view');
    Route::get('/pages/{page}', [PageController::class, 'show'])->middleware('privilege:pages.view');
    Route::post('/pages', [PageController::class, 'store'])->middleware('privilege:pages.create');
    Route::put('/pages/{page}', [PageController::class, 'update'])->middleware('privilege:pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->middleware('privilege:pages.delete');
    Route::post('/pages/{id}/restore', [PageController::class, 'restore'])->middleware('privilege:pages.delete');

    Route::get('/menus', [MenuController::class, 'index'])->middleware('privilege:menus.view');
    Route::post('/menus', [MenuController::class, 'store'])->middleware('privilege:menus.create');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->middleware('privilege:menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('privilege:menus.delete');
    Route::post('/menus/reorder', [MenuController::class, 'reorder'])->middleware('privilege:menus.update');
});

Route::get('/public/pages', [PageController::class, 'publicPages']);
Route::get('/public/pages/{id}', [PageController::class, 'publicShow']);
Route::get('/public/menus', [MenuController::class, 'publicMenus']);
Route::get('/test-page-view', function () {
    return response()->json(['message' => 'You have access to view the test page.']);
})->middleware(['auth:sanctum', 'privilege:pages.view']);

Route::delete('/test-page-delete', function () {
    return response()->json(['message' => 'You have access to delete the test page.']);
})->middleware(['auth:sanctum', 'privilege:pages.delete']);