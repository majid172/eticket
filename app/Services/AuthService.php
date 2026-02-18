<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new user and return user data + token.
     *
     * @param  array<string, mixed> $data  Already-validated input
     * @return array{user: array<string, mixed>, token: string}
     */
    public function register(array $data): array
    {
        $user  = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $this->formatUser($user),
            'token' => $token,
        ];
    }

    /**
     * Authenticate a user and return user data + token.
     *
     * @param  array{email: string, password: string} $credentials
     * @return array{user: array<string, mixed>, token: string}
     *
     * @throws ValidationException  When credentials are wrong.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException  When account is blocked.
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status === 'blocked') {
            abort(403, 'Your account has been blocked. Please contact support.');
        }

        // Single session: revoke all previous tokens.
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $this->formatUser($user),
            'token' => $token,
        ];
    }

    /**
     * Revoke the user's current access token (logout).
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Return a formatted array of the user's public profile.
     *
     * @return array<string, mixed>
     */
    public function getProfile(User $user): array
    {
        return $this->formatUser($user);
    }

    /**
     * Format a User model into a plain array for API responses.
     *
     * @return array<string, mixed>
     */
    private function formatUser(User $user): array
    {
        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'phone'  => $user->phone,
            'role'   => $user->role,
            'status' => $user->status,
        ];
    }
}
