<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('auth');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // First, try to authenticate with the User model
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'pengurusMajlis') {
                return redirect('/pengurusMajlis/home')->with('success', 'Welcome back!');
            } else {
                return redirect('/client/home')->with('success', 'Welcome back!');
            }
        }

        // If User authentication fails, check PengurusMajlis table
        $pengurusMajlis = \App\Models\PengurusMajlis::where('email', $credentials['email'])->first();
        if ($pengurusMajlis && Hash::check($credentials['password'], $pengurusMajlis->password)) {
            Auth::guard('pengurusMajlis')->login($pengurusMajlis, $request->remember);
            $request->session()->regenerate();

            return redirect('/pengurusMajlis/home')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:client,pengurusMajlis',
        ]);

        if ($request->role === 'pengurusMajlis') {
            // Create PengurusMajlis instead of User
            $pengurusMajlis = \App\Models\PengurusMajlis::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Auth::guard('pengurusMajlis')->login($pengurusMajlis);

            return redirect('/pengurusMajlis/home')->with('success', 'Registration successful! Welcome to QRAttend as Event Manager!');
        } else {
            // Create User for client role
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Auth::login($user);

            return redirect('/client/home')->with('success', 'Registration successful! Welcome to QRAttend!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Auth::guard('pengurusMajlis')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if this is an API request (expects JSON response)
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'You have been logged out successfully.'
            ]);
        }

        // For web requests, redirect to home
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
