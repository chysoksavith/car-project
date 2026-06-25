<?php

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    // # Display specified resource
    public function show(): Response
    {
        return Inertia::render('Auth/Login');
    }

    // # Handle login action
    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate — handles rate-limiting + lockout internally.
        $request->authenticate();

        // Prevent session fixation attacks.
        $request->session()->regenerate();

        if ($request->user()->user_type === \App\Enums\UserType::FRONTEND) {
            return redirect()->intended('/');
        }

        return redirect()->intended('/admin');
    }

    // # Handle logout action
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate session and rotate CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
