<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    /**
     * AuthService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function register(array $validatedData)
    {
        return $this->userRepository->create($validatedData);
    }

    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @throws ValidationException
     * @return Authenticatable
     */
    public function login(array $credentials): Authenticatable
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return Auth::user();
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
