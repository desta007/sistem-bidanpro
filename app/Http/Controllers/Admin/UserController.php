<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of staff.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')
            ->when(auth()->user()->isBidan(), function ($query) {
                // Bidan only sees their own staff
                $query->where('created_by', auth()->id());
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new staff.
     */
    public function create()
    {
        return view('admin.users.form', [
            'user' => null,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Store a newly created staff.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:bidan,staff',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Staff berhasil ditambahkan.');
    }

    /**
     * Display the specified staff.
     */
    public function show(User $user)
    {
        $this->authorizeAccess($user);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the staff.
     */
    public function edit(User $user)
    {
        $this->authorizeAccess($user);

        return view('admin.users.form', [
            'user' => $user,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Update the specified staff.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAccess($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:bidan,staff',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    /**
     * Remove the specified staff.
     */
    public function destroy(User $user)
    {
        $this->authorizeAccess($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->update(['is_active' => false]);
        // Soft delete - just deactivate instead of removing
        // $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Staff berhasil dinonaktifkan.');
    }

    /**
     * Get available roles based on current user.
     */
    private function getAvailableRoles(): array
    {
        if (auth()->user()->isSuperAdmin()) {
            return [
                'bidan' => 'Bidan',
                'staff' => 'Staff',
            ];
        }

        // Bidan can only create staff
        return [
            'staff' => 'Staff',
        ];
    }

    /**
     * Check if current user can access this staff record.
     */
    private function authorizeAccess(User $user): void
    {
        // Super admin can access all
        if (auth()->user()->isSuperAdmin()) {
            return;
        }

        // Bidan can only access their own staff
        if (auth()->user()->isBidan() && $user->created_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
