<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

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
            'name'          => 'required|string|max:100',
            'bio'           => 'nullable|string|max:300',
            'password'      => 'nullable|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_photo'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'bio'  => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $cloudinaryUrl = env('CLOUDINARY_URL');
        if (!$cloudinaryUrl && env('CLOUDINARY_CLOUD_NAME') && env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET')) {
            $cloudinaryUrl = "cloudinary://" . env('CLOUDINARY_API_KEY') . ":" . env('CLOUDINARY_API_SECRET') . "@" . env('CLOUDINARY_CLOUD_NAME');
        }

        if ($cloudinaryUrl) {
            Configuration::instance($cloudinaryUrl);
            $uploadApi = new UploadApi();

            if ($request->hasFile('profile_photo')) {
                $result = $uploadApi->upload($request->file('profile_photo')->getRealPath(), [
                    'folder' => 'saathi/profiles',
                    'transformation' => ['width' => 400, 'height' => 400, 'crop' => 'fill']
                ]);
                $data['profile_photo'] = $result['secure_url'];
            }

            if ($request->hasFile('cover_photo')) {
                $result = $uploadApi->upload($request->file('cover_photo')->getRealPath(), [
                    'folder' => 'saathi/covers',
                    'transformation' => ['width' => 1200, 'height' => 400, 'crop' => 'fill']
                ]);
                $data['cover_photo'] = $result['secure_url'];
            }
        }

        User::where('_id', $user->_id)->update($data);

        return redirect()->route('profile.show', $user->_id)
            ->with('success', 'Profile updated successfully!');
    }
}
