<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.manage');
    }

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('spark-admin.roles.index', compact('roles'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });

        return back()->with('success', 'Role created successfully.');
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('success', 'Permissions updated for ' . $role->name);
    }

    /**
     * Delete a role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot delete the Super Admin role.');
        }

        $role->delete();
        return back()->with('success', 'Role purged from the system.');
    }
}
