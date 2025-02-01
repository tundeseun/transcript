<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AdminUser;
class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.index'); // Ensures it loads the admin layout
    }

    public function store(Request $request)
{
    // Validate inputs
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('username', 'password');

    // Use the 'admin_users' guard for authentication
    if (Auth::guard('admin')->attempt($credentials)) {
        $user = AdminUser::where('username', $credentials['username'])->first();

        // Store user data in the session
        Session::put('admin_user', $user ? $user : null);
        Session::put('admin_username', $credentials['username']);

        return redirect()->route('admin.dashboard');
    }

    // Authentication failed
    return back()->withErrors(['message' => 'Invalid Username or Password'])->withInput();
}

public function destroy()
{
    Auth::logout();
    Session::forget('admin_user');
    Session::forget('admin_username');
    return redirect()->route('admin.login');
}

}
