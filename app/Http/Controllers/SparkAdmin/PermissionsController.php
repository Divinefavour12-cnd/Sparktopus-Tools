<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission.manage');
    }

    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('spark-admin.permissions.index', compact('permissions'));
    }

    /**
     * Store a new permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group' => 'required'
        ]);

        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => 'admin'
        ]);

        return back()->with('success', 'Permission capability added.');
    }
}
