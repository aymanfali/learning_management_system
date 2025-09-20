<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewInstructorRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,instructor'],
            'bio' => ['required_if:role,instructor', 'string', 'max:1000'],
            'cv' => ['required_if:role,instructor', 'file', 'mimes:pdf,doc,docx', 'max:2048'], // 2MB max
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bio' => $validated['role'] === 'instructor' ? $validated['bio'] : null,
            'cv' => $validated['role'] === 'instructor'
                ? $request->file('cv')->store('instructors/cvs', 'public')
                : null,
        ]);

        if ($user->role === 'instructor') {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new NewInstructorRegistered($user));
            }
        }

        event(new Registered($user));
        Log::info('User Registered successfully.', now());
        
        Auth::login($user);
        Log::info('User logged-in successfully.', now());

        return redirect(route('home', absolute: false));
    }
}
