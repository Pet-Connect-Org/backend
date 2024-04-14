<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AllergyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DewormHistoryController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\LikePostController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PetProfileController;
use App\Http\Controllers\PetTypeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaccinationHistoryController;
use App\Http\Controllers\WeightHistoryController;
use App\Models\Account;
use App\Models\LikePost;
use App\Models\VaccinationHistory;
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

Route::middleware(['auth'])->group(function () {
    /*      USER       */
    Route::get('/user/{id}', [UserController::class, 'getUserById']);
    Route::get('/user/me', [UserController::class, 'getUserByAccessToken']);
    Route::post('/user', [UserController::class, 'updateUser']);

    /*      ACCOUNT       */
    Route::post('/account/change_password/{id}', [AccountController::class, 'changePassword']);

    /*      POST       */
    // Route::get('/posts', [PostController::class, 'listPost']);
    Route::get('/post/{id}', [PostController::class, 'getPostById']);
    Route::post('/post', [PostController::class, 'createPost']);
    Route::put('/post/{id}', [PostController::class, 'updatePost']);
    Route::delete('/post/{id}', [PostController::class, 'deletePost']);
    Route::get('/posts', [PostController::class, 'listPost']);

    Route::post('/post/like/{id}', [LikePostController::class, 'like']);
    Route::post('/post/unlike/{id}', [LikePostController::class, 'unlike']);

    /*      COMMENT    */
    Route::get('/comments', [CommentController::class, 'listComments']);

    Route::post('/comment', [CommentController::class, 'createComment']);
    Route::put('/comment/{id}', [CommentController::class, 'updateComment']);
    Route::delete('/comment/{id}', [CommentController::class, 'deleteComment']);
    Route::post('/comment/like/{id}', [LikeCommentController::class, 'like']);
    Route::post('/comment/unlike/{id}', [LikeCommentController::class, 'unlike']);
    /*      FOLLOW      */
    Route::post('/follow/user/{id}', [FollowController::class, 'follow']);
    Route::post('/unfollow/user/{id}', [FollowController::class, 'unfollow']);
    /*      PET_TYPE     */
    Route::get('/pet_type', [PetTypeController::class, 'listAll']);
    Route::post('/pet_type', [PetTypeController::class, 'create']);
    Route::delete('/pet_type/{id}', [PetTypeController::class, 'deletePetType']);
    /*      MEDICAL      */


    /**     PET PROFILE        */
    Route::get('/pet_profile/{id}', [PetProfileController::class, 'getPetProfileByPetId']);
    Route::post('/pet_profile', [PetProfileController::class, 'createProfile']);

    /**     Allergy          */
    Route::post('/allergies', [AllergyController::class, 'create']);
    Route::put('/allergies/{id}', [AllergyController::class, 'update']);
    Route::delete('/allergies/{id}', [AllergyController::class, 'delete']);

    /**     Weight History       */
    Route::post('/weight_history', [WeightHistoryController::class, 'store']);
    Route::put('/weight_history/{id}', [WeightHistoryController::class, 'update']);
    Route::delete('/weight_history/{id}', [WeightHistoryController::class, 'delete']);
    
    /**     Deworm History       */
    Route::post('/deworm_history', [DewormHistoryController::class, 'store']);
    Route::put('/deworm_history/{id}', [DewormHistoryController::class, 'update']);
    Route::delete('/deworm_history/{id}', [DewormHistoryController::class, 'delete']);
    
    /**     Vaccination History       */
    Route::post('/vaccination_history', [VaccinationHistoryController::class, 'store']);
    Route::put('/vaccination_history/{id}', [VaccinationHistoryController::class, 'update']);
    Route::delete('/vaccination_history/{id}', [VaccinationHistoryController::class, 'delete']);
});

/*      AUTH       */
Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('/auth/resend_verification_code', [AuthController::class, 'resendEmailVerificationCode']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

/*      USER       */

/*      PING       */
Route::get('/', function () {
    return response()->json(['hello' => "Hello"], 200);
});

/*  TEST CONNECTION    */
Route::get('/test', function () {
    $account = Account::all();
    return response()->json(['number' => count($account)], 200);
});
