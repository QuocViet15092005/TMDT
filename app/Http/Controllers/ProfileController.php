<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Hiển thị trang thông tin cá nhân
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        auth()->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Cập nhật thông tin thành công.');
    }

    // Hiển thị form đổi mật khẩu
    public function passwordForm()
    {
        return view('profile.change-password');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Đổi mật khẩu thành công.');
    }
}
