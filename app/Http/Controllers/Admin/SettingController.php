<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        // Load clinic settings from config or database
        $settings = [
            'clinic_name' => config('app.name', 'BidanPRO'),
            'clinic_address' => '',
            'clinic_phone' => '',
            'clinic_email' => '',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'nullable|string',
            'clinic_phone' => 'nullable|string|max:20',
            'clinic_email' => 'nullable|email',
        ]);

        // TODO: Save settings to database or .env

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function profile()
    {
        $user = auth()->user();

        return view('admin.settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
