<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('children')->whereNull('parent_id')
        ->orderBy('sort_order')->get();

        return response()->json(['data' => $menus]);
    }

    public function store(StoreMenuRequest $request)
    {
        $menu = Menu::create($request->validated());

        return response()->json([
        'message' => 'Menu created successfully',
        'data' => $menu
        ], 201);
    }
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        return response()->json([
            'message' => 'Menu updated successfully',
            'data' => $menu->fresh()
        ]);
    }
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'message' => 'Menu deleted successfully'
        ]);
    }
}
