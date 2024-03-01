<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth'])->group(function(){
    Route::get('/user/me', [UserController::class, 'getUserByAccessToken']);
    Route::post('/account/change_password/{id}', [AccountController::class, 'changePassword']);
});

Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('/auth/resend_verification_code', [AuthController::class, 'resendEmailVerificationCode']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::get('/user/{id}', [UserController::class, 'getUserById']);

Route::get('/', function() {
    return response()->json(['hello' => "Hello"], 200);
});


