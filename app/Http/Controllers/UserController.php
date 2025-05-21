<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import

class UserController extends Controller
{
    use AuthorizesRequests; // Add this line

    public function index()
    {
        $this->authorize('full access');
        $users = User::with('roles', 'permissions')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorize('full access');
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $this->authorize('full access');
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $this->authorize('full access');
        $roles = Role::all();
        $permissions = Permission::all();

        return response()->json([
            'user'             => $user,
            'roles'            => $roles,
            'permissions'      => $permissions,
            'user_role'        => $user->roles->pluck('name'),
            'user_permissions' => $user->permissions->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('full access');
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:6', // ← أضف هذا
            'role'        => 'required|exists:roles,name',
            'permissions' => 'array',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }


        $user->save();

        $user->syncRoles([$request->role]);
        $user->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true, 'message' => 'تم التحديث بنجاح']);
    }

}
