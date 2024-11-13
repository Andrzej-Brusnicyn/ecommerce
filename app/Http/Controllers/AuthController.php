<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated());

        return redirect()->route('login')->with('message', 'Вы успешно зарегистрировались!');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, 200);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

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
