<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $data = User::all()->map(function ($user) {
                return $user->only(['name', 'email', 'created_at', 'updated_at']);
            });
            return view('dashboard.users.index', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error loading users: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to load users. Please try again.');
        }
    }

    public function show($id)
    {
        try {
            $user = \App\Models\User::findOrFail($id);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading user: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to load user. Please try again.');
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            Gate::authorize('delete', $user);

            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }

            // Delete related cv and image files
            if ($user->cv && Storage::exists($user->cv)) {
                Storage::delete($user->cv);
            }
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }

            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleteing users: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete users. Please try again.');
        }
    }
}
