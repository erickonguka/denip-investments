<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
     use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'mfa_enabled' => 'boolean',
            'profile_photo' => 'nullable|image|max:5120|mimes:jpeg,jpg,png,gif,webp',
        ]);

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'mfa_enabled' => $validated['mfa_enabled'] ?? false,
            'status' => 'active',
            'profile_photo' => $profilePhotoPath,
        ]);

        $user->roles()->sync([$validated['role_id']]);
        
        \App\Models\ActivityLog::log('created', $user, 'User created: ' . $user->name);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    public function edit(User $user)
    {
        $this->authorize('view', $user);
        
        if (request()->wantsJson()) {
            $user->load('roles');
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_id' => $user->roles->first()?->id,
                    'mfa_enabled' => $user->mfa_enabled,
                    'status' => $user->status,
                    'profile_photo' => $user->profile_photo,
                ]
            ]);
        }
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'mfa_enabled' => 'boolean',
            'status' => 'required|in:active,inactive',
            'profile_photo' => 'nullable|image|max:5120|mimes:jpeg,jpg,png,gif,webp',
        ]);

        // Handle profile photo upload
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'mfa_enabled' => $validated['mfa_enabled'] ?? false,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $updateData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update($updateData);
        $user->roles()->sync([$validated['role_id']]);
        
        \App\Models\ActivityLog::log('updated', $user, 'User updated: ' . $user->name, $user->getChanges());

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load(['roles.permissions', 'sessions']);
        $roles = Role::all();
        return view('users.show', compact('user', 'roles'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete your own account'
            ], 422);
        }
        
        \App\Models\ActivityLog::log('deleted', $user, 'User deleted: ' . $user->name);
        
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}