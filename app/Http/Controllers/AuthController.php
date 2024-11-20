<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request->validated());

        return redirect()->route('login')->with('message', 'You have successfully registered!');
    }

    /**
     * Authenticate a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, 200);
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect()->route('catalog')->with('message', 'You have successfully logged out!');
    }

    /**
     * Show the registration form.
     *
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('register');
    }

    /**
     * Show the login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('login');
    }
}
