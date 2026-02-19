<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Operator\BusController;
use App\Http\Controllers\Api\V1\Operator\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Role Separation:
|   user     → Can only register, login, search schedules, and book tickets.
|   operator → Can manage their own buses, routes, schedules, and bookings.
|   admin    → Full platform control (users, companies, all bookings, settings).
|
| Passengers (user role) have NO access to operator or admin panels.
*/

// ─── Public Routes (No Auth Required) ────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Shared auth endpoints (all roles)
    Route::prefix('auth')->group(function () {
        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // ─── Passenger (User) Routes ──────────────────────────────────────────────
    // Passengers can ONLY search schedules and manage their own bookings.
    // They have NO access to operator or admin routes.
    Route::middleware('role:passenger')->prefix('passenger')->group(function () {
        // Search available schedules/buses
        Route::get('/schedules',              fn() => response()->json(['message' => 'Search schedules here']));
        Route::get('/schedules/{id}/seats',   fn() => response()->json(['message' => 'View available seats']));

        // Booking management (own bookings only)
        Route::get('/bookings',               fn() => response()->json(['message' => 'My bookings']));
        Route::post('/bookings',              fn() => response()->json(['message' => 'Create a booking']));
        Route::get('/bookings/{id}',          fn() => response()->json(['message' => 'View booking details']));
        Route::delete('/bookings/{id}',       fn() => response()->json(['message' => 'Cancel booking']));
    });

    // ─── Operator Routes ──────────────────────────────────────────────────────
    // Operators manage their own company resources. Users cannot access these.
    Route::middleware('role:operator')->prefix('operator')->group(function () {
        // Company profile
        Route::get('/company',  [CompanyController::class, 'show']);
        Route::post('/company', [CompanyController::class, 'store']);
        Route::put('/company',  [CompanyController::class, 'update']);
       

        // Bus management (operator's own fleet only)
        Route::get('/buses',           [BusController::class, 'index']);
        Route::post('/buses',          [BusController::class, 'store']);
        Route::get('/buses/{id}',      [BusController::class, 'show']);
        Route::put('/buses/{id}',      [BusController::class, 'update']);
        Route::delete('/buses/{id}',   [BusController::class, 'destroy']);

        // Route management
        Route::get('/routes',                 fn() => response()->json(['message' => 'My routes']));
        Route::post('/routes',                fn() => response()->json(['message' => 'Add a route']));

        // Schedule management
        Route::get('/schedules',              fn() => response()->json(['message' => 'My schedules']));
        Route::post('/schedules',             fn() => response()->json(['message' => 'Create a schedule']));
        Route::put('/schedules/{id}',         fn() => response()->json(['message' => 'Update schedule']));
        Route::delete('/schedules/{id}',      fn() => response()->json(['message' => 'Delete schedule']));

        // Booking management (view passengers on their buses)
        Route::get('/bookings',               fn() => response()->json(['message' => 'Bookings on my buses']));
        Route::put('/bookings/{id}/status',   fn() => response()->json(['message' => 'Update booking status']));
    });

    // ─── Admin Routes ─────────────────────────────────────────────────────────
    // Full platform control. Only admin role can access.
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // User management
        Route::get('/users',                  fn() => response()->json(['message' => 'All users']));
        Route::put('/users/{id}/status',      fn() => response()->json(['message' => 'Block/unblock user']));
        Route::delete('/users/{id}',          fn() => response()->json(['message' => 'Delete user']));

        // Company/Operator approval
        Route::get('/companies',              fn() => response()->json(['message' => 'All companies']));
        Route::put('/companies/{id}/status',  fn() => response()->json(['message' => 'Approve/block company']));

        // Platform-wide bookings
        Route::get('/bookings',               fn() => response()->json(['message' => 'All bookings']));

        // Routes & Schedules oversight
        Route::get('/routes',                 fn() => response()->json(['message' => 'All routes']));
        Route::get('/schedules',              fn() => response()->json(['message' => 'All schedules']));
    });
});

