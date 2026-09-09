<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use illuminate\Foundations\Http\FormRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\UserController;

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

    Route::get('/roles', [RoleController::class, 'index'])->middleware('privilege:roles.view');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('privilege:roles.create');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('privilege:roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('privilege:roles.delete');

    Route::get('/privileges', [PrivilegeController::class, 'index'])->middleware('privilege:privileges.view');
    Route::post('/privileges', [PrivilegeController::class, 'store'])->middleware('privilege:privileges.create');
    Route::put('/privileges/{privilege}', [PrivilegeController::class, 'update'])->middleware('privilege:privileges.update');
    Route::delete('/privileges/{privilege}', [PrivilegeController::class, 'destroy'])->middleware('privilege:privileges.delete');

    Route::put('/roles/{role}/privileges', [RoleController::class, 'updatePrivileges'])->middleware('privilege:roles.update');

    Route::get('/users', [UserController::class, 'index'])->middleware('privilege:users.view');
    Route::post('/users', [UserController::class, 'store'])->middleware('privilege:users.create');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('privilege:users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('privilege:users.delete');

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