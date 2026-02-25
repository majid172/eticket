<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Return all users, newest first.
     */
    public function all(): Collection
    {
        return User::latest()->get();
    }

    /**
     * Find a single user by primary key.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a user by their email address.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create and persist a new user.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'role'     => $data['role'] ?? 'passenger',
            'status'   => 'active',
        ]);
    }

    /**
     * Update an existing user's attributes.
     *
     * @param array<string, mixed> $data
     */
    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Toggle a user's status between 'active' and 'blocked'.
     */
    public function toggleStatus(User $user): User
    {
        $user->update([
            'status' => $user->status === 'active' ? 'blocked' : 'active',
        ]);

        return $user->fresh();
    }

    /**
     * Permanently delete a user.
     */
    public function delete(User $user): void
    {
        $user->tokens()->delete(); // revoke Sanctum tokens first
        $user->delete();
    }
}
