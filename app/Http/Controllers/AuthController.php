<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginOrRegister(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        // 1. Find user by phone
        $user = User::where('phone', $request->phone)->first();

        // 2. If not exists → create new user
        if (!$user) {
            $user = User::create([
                'phone' => $request->phone,
                'name' => $request->name ?? 'User',
                'email' => null,
                'password' => null,
            ]);
        }

        // 3. Login user
        Auth::login($user);

        return redirect('/app');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
