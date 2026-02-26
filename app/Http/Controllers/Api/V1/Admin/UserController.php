<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * GET /api/admin/users
     * List all users.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->userService->getAllUsers());
    }

    /**
     * GET /api/admin/users/{id}
     * Show a single user.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findOrFail($id);

        return response()->json(new UserResource($user));
    }

    /**
     * PUT /api/admin/users/{id}
     * Update a user's details and role.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->findOrFail($id);

        $user = $this->userService->updateUser($user, $request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
            'data'    => new UserResource($user),
        ]);
    }

    /**
     * PUT /api/admin/users/{id}/status
     * Toggle a user's active / blocked status.
     */
    public function toggleStatus(int $id): JsonResponse
    {
        $user = $this->userService->toggleStatus($id);

        return response()->json([
            'message' => 'User status updated successfully.',
            'data'    => new UserResource($user),
        ]);
    }

    /**
     * DELETE /api/admin/users/{id}
     * Permanently delete a user.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
