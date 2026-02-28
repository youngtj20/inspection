<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get user from database
        $user = DB::table('sys_user')
            ->where('email', $credentials['email'])
            ->where('status', 1)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        // Log the user in
        Auth::loginUsingId($user->id, $request->filled('remember'));

        // Log activity
        DB::table('sys_action_log')->insert([
            'name' => 'User Login',
            'type' => 1,
            'ipaddr' => $request->ip(),
            'message' => "User {$user->nickname} logged in",
            'oper_name' => $user->nickname,
            'oper_by' => $user->id,
            'create_date' => now(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Log activity
            DB::table('sys_action_log')->insert([
                'name' => 'User Logout',
                'type' => 1,
                'ipaddr' => $request->ip(),
                'message' => "User {$user->nickname} logged out",
                'oper_name' => $user->nickname,
                'oper_by' => $user->id,
                'create_date' => now(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
