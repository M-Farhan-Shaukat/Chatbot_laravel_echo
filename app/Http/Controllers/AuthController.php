<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Step 1: check phone
    public function checkPhone(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $exists = User::where('phone', $request->phone)->exists();

        if ($exists) {
            // existing user — login directly
            $user = User::where('phone', $request->phone)->first();
            Auth::login($user);
            return redirect('/app');
        }

        // new user — ask for name
        return view('auth.register', ['phone' => $request->phone]);
    }

    // Step 2: register new user
    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'name'  => 'required|string|max:100',
        ]);

        // double check not already registered
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            $user = User::create([
                'phone'    => $request->phone,
                'name'     => $request->name,
                'email'    => null,
                'password' => null,
            ]);
        }

        Auth::login($user);
        return redirect('/app');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
