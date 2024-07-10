<?php 

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrganisationController extends Controller
{
    public function getAllOrganisations()
    {
        $user = JWTAuth::user();
        $organisations = $user->organisations;

        return response()->json([
            'status' => 'success',
            'message' => 'Organisations retrieved successfully',
            'data' => ['organisations' => $organisations],
        ], 200);
    }

    public function getOrganisation($orgId)
    {
        $user = JWTAuth::user();
        $organisation = $user->organisations()->where('orgId', $orgId)->firstOrFail();

        return response()->json([
            'status' => 'success',
            'message' => 'Organisation retrieved successfully',
            'data' => $organisation,
        ], 200);
    }

    public function createOrganisation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => collect($validator->errors()->all())->map(function ($field, $message) {
                    return ['field' => $field, 'message' => $message[0]];
                }),
            ], 422);
        }

        $organisation = Organisation::create([
            'orgId' => Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $user = JWTAuth::user();
        $user->organisations()->attach($organisation->orgId);

        return response()->json([
            'status' => 'success',
            'message' => 'Organisation created successfully',
            'data' => $organisation,
        ], 201);
    }

    public function addUserToOrganisation(Request $request, $orgId)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|string|exists:users,userId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => collect($validator->errors()->all())->map(function ($field, $message) {
                    return ['field' => $field, 'message' => $message[0]];
                }),
            ], 422);
        }

        $organisation = Organisation::findOrFail($orgId);
        $organisation->users()->attach($request->userId);

        return response()->json([
            'status' => 'success',
            'message' => 'User added to organisation successfully',
        ], 200);
    }
}
