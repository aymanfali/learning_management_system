<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all()->map(function ($user) {
            return $user->only(['name', 'email', 'created_at', 'updated_at']);
        });
        return view('dashboard.users.index', compact('data'));
    }

    public function show($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function destroy($id)
    {
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
    }
}
