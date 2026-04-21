<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * ProfileController — view and update user profiles.
 */
class ProfileController extends Controller
{
    /** Show a user's public profile. */
    public function show(string $id)
    {
        $profileUser = User::findOrFail($id);
        $posts = Post::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.show', compact('profileUser', 'posts'));
    }

    /** Show the edit form for the authenticated user's profile. */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /** Update the authenticated user's profile. */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'bio'      => 'nullable|string|max:300',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'bio'  => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        User::where('_id', $user->_id)->update($data);

        return redirect()->route('profile.show', $user->_id)
            ->with('success', 'Profile updated successfully! ✅');
    }
}
