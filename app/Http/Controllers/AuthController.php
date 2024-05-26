<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // Login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($validated)) {
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'message' => 'Invalid credentials',
        ]);
    }
}
