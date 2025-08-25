<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    public function edit()
    {
        $profile = SchoolProfile::getProfile();
        return view('admin.school-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'map_embed' => 'nullable|string',
        ]);

        $profile = SchoolProfile::first();
        
        $data = $request->except(['school_logo']);

        if ($request->hasFile('school_logo')) {
            if ($profile && $profile->school_logo) {
                Storage::disk('public')->delete($profile->school_logo);
            }
            $data['school_logo'] = $request->file('school_logo')->store('school', 'public');
        }

        if ($profile) {
            $profile->update($data);
        } else {
            SchoolProfile::create($data);
        }

        return redirect()->back()->with('success', 'School profile updated successfully.');
    }
}