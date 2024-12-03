<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
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
     * Get the authenticated user's ID.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return auth()->id();
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
        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return auth()->user();
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
