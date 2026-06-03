<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SimpleLoginController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('auth/simple-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (
            $request->input('username') === config('app.admin_username') &&
            $request->input('password') === config('app.admin_password')
        ) {
            $request->session()->put('simple_authenticated', true);

            return redirect()->route('invoices.create');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('simple_authenticated');

        return redirect()->route('login');
    }
}
