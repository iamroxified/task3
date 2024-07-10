<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::prefix('auth')->group(function () {
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

});
Route::middleware('auth:jwt')->group(function () {
    Route::get('/users/{id}', [AuthController::class, 'getUser']);
    Route::get('/organisations', [OrganisationController::class, 'getAllOrganisations']);
    Route::get('/organisations/{orgId}', [OrganisationController::class, 'getOrganisation']);
    Route::post('/organisations', [OrganisationController::class, 'createOrganisation']);
    Route::post('/organisations/{orgId}/users', [OrganisationController::class, 'addUserToOrganisation']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('users/{userId}', [UserController::class, 'getUserRecord']);
});
