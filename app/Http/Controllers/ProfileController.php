<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'about'  => 'nullable|string|max:200',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $data = ['name' => $request->name, 'about' => $request->about];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated');
    }
}
