<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM ĐĂNG KÝ
    |--------------------------------------------------------------------------
    */

    public function registerForm()
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | ĐĂNG KÝ
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|unique:users,email',

            'password' =>
                'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            // Không cho người dùng tự đăng ký Admin
            'role' =>
                'customer',
        ]);

        Auth::login($user);

        $request
            ->session()
            ->regenerate();

        return redirect()
            ->route('home');
    }


    /*
    |--------------------------------------------------------------------------
    | FORM ĐĂNG NHẬP
    |--------------------------------------------------------------------------
    */

    public function loginForm()
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | ĐĂNG NHẬP
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' =>
                'required|email',

            'password' =>
                'required',
        ]);

        if (Auth::attempt($credentials)) {

            $request
                ->session()
                ->regenerate();

            // Admin
            if (
                auth()->user()->role
                === 'admin'
            ) {
                return redirect()
                    ->intended(
                        route('admin.dashboard')
                    );
            }

            // Customer
            return redirect()
                ->intended(
                    route('home')
                );
        }

        return back()
            ->withErrors([
                'email' =>
                    'Email hoặc mật khẩu không đúng.'
            ])
            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | ĐĂNG XUẤT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('home');
    }
}