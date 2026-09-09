<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        return response()->json(['data' => $users]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $roles = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if (!empty($roles)) {
            $user->roles()->sync($roles);
        }

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user->load('roles')
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
{
    $data = $request->validated();

    $roleIds = $data['role_ids'] ?? null;
    unset($data['role_ids']);

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    if ($roleIds !== null) {
        $user->roles()->sync($roleIds);
    }

    return response()->json([
        'message' => 'User updated successfully',
        'data' => $user->load('roles')
    ]);
}

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
