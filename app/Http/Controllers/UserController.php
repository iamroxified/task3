<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
 /**
     * Get the user record by userId.
     *
     * @param  string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRecord($userId)
    {
        // Fetch the user by userId
        $user = User::findOrFail($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'User record fetched successfully',
            'data' => [
                'userId' => $user->userId,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

}
