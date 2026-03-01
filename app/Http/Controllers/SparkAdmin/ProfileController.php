<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the admin profile settings.
     */
    public function index()
    {
        $admin = auth('admin')->user();
        return view('spark-admin.profile.index', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin theme preference via AJAX.
     */
    public function updateTheme(Request $request)
    {
        $admin = auth('admin')->user();
        $request->validate([
            'theme' => 'required|string|in:voodoo,alpine',
        ]);

        $admin->theme_preference = $request->theme;
        $admin->save();

        return response()->json(['success' => true]);
    }
}
