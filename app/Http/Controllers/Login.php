<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    // 1. Show Login Page
    public function index() {
        return view("login");
    }

    // 2. Handle Login Logic
    public function login(Request $request) {

        $validation = $request->validate([
            'username' => 'required', // Removed trailing pipe
            'password' => 'required',
        ]);

        // Check if user exists manually (optional, Auth::attempt handles this too)
        $users = DB::table('users')
                    ->where('username', $request->input('username'))
                    ->first();

        if(!$users) {
            return back()->with('failed', "Wrong credentials!")
                        ->withInput($request->only('username'));
        }

        // Attempt to log in
        if(Auth::attempt($validation)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Fallback if attempt fails
        return back()->with('failed', "Authentication failed.");
    }

    // 3. Handle Logout Logic (This was missing or misplaced)
    public function logout(Request $request)
    {
        // Clear the session data
        $request->session()->flush();

        // Logout the user via Facade
        Auth::logout();

        // Redirect to login route
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}