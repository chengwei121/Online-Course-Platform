<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\UpdateAdminRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $admins = $query->orderBy('created_at', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        // Statistics
        $stats = [
            'total' => User::where('role', 'admin')->count(),
            'verified' => User::where('role', 'admin')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'admin')->whereNull('email_verified_at')->count(),
            'recent' => User::where('role', 'admin')->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.admins.index', compact('admins', 'stats'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified_at' => now(), // Auto-verify admin accounts
        ]);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Administrator created successfully.');
    }

    /**
     * Display the specified administrator.
     */
    public function show(User $admin)
    {
        // Ensure we're only showing admin users
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(User $admin)
    {
        // Ensure we're only editing admin users
        if ($admin->role !== 'admin') {
            abort(404);
        }

        // Prevent editing the current admin's own account through this interface
        if ($admin->id === Auth::id()) {
            return redirect()->route('admin.admins.index')
                           ->with('error', 'You cannot edit your own account through this interface.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(UpdateAdminRequest $request, User $admin)
    {
        // Ensure we're only updating admin users
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update email verification status
        if (isset($validated['email_verified_at'])) {
            $updateData['email_verified_at'] = $validated['email_verified_at'] ? now() : null;
        }

        $admin->update($updateData);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Administrator updated successfully.');
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(User $admin)
    {
        // Ensure we're only deleting admin users
        if ($admin->role !== 'admin') {
            abort(404);
        }

        // Prevent deleting the current admin's own account
        if ($admin->id === Auth::id()) {
            return redirect()->route('admin.admins.index')
                           ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last admin
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.admins.index')
                           ->with('error', 'Cannot delete the last administrator account.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Administrator deleted successfully.');
    }

    /**
     * Toggle email verification status
     */
    public function toggleVerification(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $wasVerified = $admin->email_verified_at !== null;
        
        $admin->update([
            'email_verified_at' => $admin->email_verified_at ? null : now()
        ]);

        if ($wasVerified) {
            $message = "Administrator '{$admin->name}' has been marked as unverified.";
        } else {
            $message = "Administrator '{$admin->name}' has been successfully verified! ✅";
        }
        
        return redirect()->route('admin.admins.index')
                        ->with('success', $message);
    }
}