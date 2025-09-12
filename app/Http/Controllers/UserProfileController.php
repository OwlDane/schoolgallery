<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$user->id],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'current_password' => ['nullable','string'],
            'password' => ['nullable','string','min:6','confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars','public');
            $user->avatar = $path;
        }

        // If changing password, require current_password to match
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.'])->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}


