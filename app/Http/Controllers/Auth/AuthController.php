<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Handles user registration and login/logout.
 */
class AuthController extends Controller
{
    // ─── Registration ─────────────────────────────────────────────────────────

    /** Show the registration form. */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Validate and store a new user account. */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:mongodb.users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'bio'       => '',
            'bookmarks' => [],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to the Saathi Wellness Community! 🌿');
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    /** Show the login form. */
    public function showLogin()
    {
        return view('auth.login');
    }

    /** Attempt to authenticate the user. */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back! 👋');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    /** Log the user out and invalidate the session. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
