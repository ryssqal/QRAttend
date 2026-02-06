<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pengurusMajlis.profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Update name
        $user->name = $request->name;

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists('profiles/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profiles/' . $user->profile_photo);
            }

            // Store new photo
            $fileName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->storeAs('profiles', $fileName, 'public');
            $user->profile_photo = $fileName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
