<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($user->role === 'instructor') {
            $rules['major'] = ['required', 'string', 'max:255'];
            $rules['bio'] = ['required', 'string', 'max:2000'];
            $rules['cv'] = ['required', 'file', 'mimes:pdf,doc,docx', 'max:2048'];
        }

        $validated = $request->validate($rules);
        // dd($validated);
        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle profile image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $validated['image'] = $request->file('image')->store('instructors/images', 'public');
        }

        // Handle CV upload
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($user->cv && Storage::disk('public')->exists($user->cv)) {
                Storage::disk('public')->delete($user->cv);
            }

            // Store new CV
            $validated['cv'] = $request->file('cv')->store('instructors/cvs', 'public');
        }

        $user->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
