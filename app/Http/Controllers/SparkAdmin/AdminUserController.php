<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.manage');
    }

    /**
     * Display a listing of admin users.
     */
    public function index()
    {
        $admins = Admin::with('roles')->get();
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        return view('spark-admin.admins.index', compact('admins', 'roles'));
    }

    /**
     * Store a new admin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Look up the role by ID, filtered to admin guard
        $role = Role::where('id', $request->role_id)
                     ->where('guard_name', 'admin')
                     ->first();

        if ($role) {
            $admin->assignRole($role->name);
        }

        return back()->with('success', 'Admin account created and role assigned.');
    }

    /**
     * Update an admin user's role.
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $role = Role::where('id', $request->role_id)
                     ->where('guard_name', 'admin')
                     ->first();

        if ($role) {
            $admin->syncRoles([$role->name]);
        }

        return back()->with('success', 'Admin privileges updated.');
    }

    /**
     * Remove an admin user.
     */
    public function destroy(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'Cannot delete yourself.');
        }

        $admin->delete();
        return back()->with('success', 'Admin removed from the system.');
    }
}
