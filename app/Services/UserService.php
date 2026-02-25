<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Return all users as a resource collection.
     */
    public function getAllUsers(): AnonymousResourceCollection
    {
        return UserResource::collection($this->userRepository->all());
    }

    /**
     * Find a user by ID or fail with a 404.
     */
    public function findOrFail(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (! $user) {
            abort(404, 'User not found.');
        }

        return $user;
    }

    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data  Already-validated input
     */
    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user's profile.
     *
     * @param array<string, mixed> $data  Already-validated input
     */
    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    /**
     * Toggle a user's active / blocked status.
     */
    public function toggleStatus(int $id): User
    {
        $user = $this->findOrFail($id);

        return $this->userRepository->toggleStatus($user);
    }

    /**
     * Permanently delete a user.
     */
    public function deleteUser(int $id): void
    {
        $user = $this->findOrFail($id);

        $this->userRepository->delete($user);
    }
}
