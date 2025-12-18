<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserManagement\StoreUserRequest;
use App\Http\Requests\Admin\UserManagement\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->get('user_type'));
        }

        // Filter by admin status
        if ($request->filled('is_admin')) {
            $isAdmin = $request->get('is_admin');
            $query->where('is_admin', $isAdmin === '1' || $isAdmin === 1);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.user-management.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin', false);

        User::create($validated);

        return redirect()->route('admin.user-management.index')
            ->with('success', __('admin.User created successfully.'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user_management): View
    {
        $user_management->load(['donor', 'bloodRequests', 'approvedBloodRequests']);

        return view('admin.user-management.show', ['user' => $user_management]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user_management): View
    {
        return view('admin.user-management.edit', ['user' => $user_management]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user_management): RedirectResponse
    {
        $validated = $request->validated();

        if (isset($validated['password']) && ! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->has('is_admin')) {
            $validated['is_admin'] = $request->boolean('is_admin');
        }

        $user_management->update($validated);

        return redirect()->route('admin.user-management.index')
            ->with('success', __('admin.User updated successfully.'));
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user_management): RedirectResponse
    {
        $user_management->delete();

        return redirect()->route('admin.user-management.index')
            ->with('success', __('admin.User deleted successfully.'));
    }

    /**
     * Toggle admin status of the user.
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        $user->is_admin = ! $user->is_admin;
        $user->save();

        return redirect()->route('admin.user-management.index')
            ->with('success', __('admin.Admin status updated successfully.'));
    }
}
