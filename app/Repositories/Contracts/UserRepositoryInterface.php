<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Return all users, newest first.
     */
    public function all(): Collection;

    /**
     * Find a single user by primary key. Returns null if not found.
     */
    public function findById(int $id): ?User;

    /**
     * Find a user by their email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create and persist a new user.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): User;

    /**
     * Update an existing user's attributes.
     *
     * @param array<string, mixed> $data
     */
    public function update(User $user, array $data): User;

    /**
     * Toggle a user's status between 'active' and 'blocked'.
     */
    public function toggleStatus(User $user): User;

    /**
     * Permanently delete a user.
     */
    public function delete(User $user): void;
}
