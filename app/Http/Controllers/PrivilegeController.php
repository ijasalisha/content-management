<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilege;
use App\Http\Requests\StorePrivilegeRequest;

class PrivilegeController extends Controller
{
    public function index()
    {
        $privileges = Privilege::latest()->get();
        return response()->json(['data' => $privileges]);
    }

    public function store(StorePrivilegeRequest $request)
    {
        $privilege = Privilege::create($request->validated());

        return response()->json([
            'message' => 'Privilege created successfully',
            'data' => $privilege
        ], 201);
    }

    public function update(Request $request, Privilege $privilege)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:privileges,name,' . $privilege->id,
            'description' => 'nullable|string',
        ]);
        $privilege->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Privilege updated successfully',
            'data' => $privilege->fresh()
        ]);
    }
    public function destroy(Privilege $privilege)
    {
        $privilege->delete();

        return response()->json([
            'message' => 'Privilege deleted successfully'
        ]);
    }
}
