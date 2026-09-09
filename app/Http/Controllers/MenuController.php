<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Http\Requests\ReorderMenuRequest;

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
    public function reorder(ReorderMenuRequest $request)
    {
       
        foreach ($request->validated()['items'] as $item) {
            Menu::where('id', $item['id'])->update([
                'parent_id' => $item['parent_id'] ?? null,
                'sort_order' => $item['sort_order'],
            ]);
        }

        return response()->json([
            'message' => 'Menu reordered successfully'
        ]);
    }
    public function publicMenus()
    {
        $menus = Menu::with([
            'children',
            'pages' => function($query) {
            $query->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('publish_at')
                ->orWhere('publish_at', '<=', now());
            })
            ->latest();
            }
        ])
        ->where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get();

        return response()->json(['data' => $menus]);
    }
}
