<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRolePrivilegesRequest;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    #[OA\Get(
    path: '/api/role',
    summary: 'List roles',
    tags: ['Role'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success'
        )
    ]
)]
    public function index()
    {
        $roles = Role::with('privileges')->latest()->get();
        return response()->json(['data' => $roles]);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role
        ], 201);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);
        $role->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $role->fresh()
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }

    public function updatePrivileges(UpdateRolePrivilegesRequest $request, Role $role)
    {
        $role->privileges()->sync($request->validated('privilege_ids'));

        return response()->json([
            'message' => 'Role privileges updated successfully',
            'data' => $role->load('privileges')
        ]);
    }
}
