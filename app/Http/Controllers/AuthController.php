<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('message', 'Вы успешно зарегистрировались!');
    }

    /**
     * Handle a login request for the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect()->route('catalog')->with('message', 'Вы успешно вошли в систему!');
    }

    /**
     * Handle a logout request for the application.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('catalog')->with('message', 'Вы успешно вышли из системы!');
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }
}
