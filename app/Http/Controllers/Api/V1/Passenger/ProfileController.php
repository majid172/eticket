<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Controller;
use App\Models\PassengerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile and settings.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Ensure profile exists (first-time fetch logic)
        $profile = $user->passengerProfile()->firstOrCreate([
            'user_id' => $user->id
        ], [
            'preferences' => [
                'seatType' => 'Window',
                'busType' => 'AC',
                'boardingPoint' => ''
            ],
            'notifications' => [
                'sms' => true,
                'email' => true,
                'promo' => false,
                'delay' => true
            ],
            'config' => [
                'language' => 'English',
                'currency' => 'BDT',
                'darkMode' => false
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'profile' => [
                    'emergency_contact' => $profile->emergency_contact,
                    'preferences' => $profile->preferences,
                    'notifications' => $profile->notifications,
                    'config' => $profile->config,
                ]
            ]
        ]);
    }

    /**
     * Update the authenticated user's profile and settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->passengerProfile;

        $translated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['sometimes', 'string', Rule::unique('users')->ignore($user->id)],
            'emergency_contact' => 'nullable|string',
            'preferences' => 'sometimes|array',
            'notifications' => 'sometimes|array',
            'config' => 'sometimes|array',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update User basic info
        if ($request->has('name')) $user->name = $translated['name'];
        if ($request->has('email')) $user->email = $translated['email'];
        if ($request->has('phone')) $user->phone = $translated['phone'];

        // Password update logic
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The current password you entered is incorrect.'
                ], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Update Profile metadata
        $profile->update(array_merge(
            $request->only(['emergency_contact', 'preferences', 'notifications', 'config']),
            ['user_id' => $user->id]
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'profile' => [
                    'emergency_contact' => $profile->emergency_contact,
                    'preferences' => $profile->preferences,
                    'notifications' => $profile->notifications,
                    'config' => $profile->config,
                ]
            ]
        ]);
    }
}
