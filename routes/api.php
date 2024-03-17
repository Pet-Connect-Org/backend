<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\LikePostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\LikePost;
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
    /*      USER       */
    Route::get('/user/me', [UserController::class, 'getUserByAccessToken']);

    /*      ACCOUNT       */
    Route::post('/account/change_password/{id}', [AccountController::class, 'changePassword']);

    /*      POST       */
    Route::get('/posts', [PostController::class, 'listPost']);
    Route::get('/post/{id}', [PostController::class, 'getPostById']);
    Route::post('/post', [PostController::class, 'createPost']);
    Route::put('/post/{id}', [PostController::class, 'updatePost']);
    Route::delete('/post/{id}', [PostController::class, 'deletePost']);

    Route::post('/post/like/{id}', [LikePostController::class, 'like']);
    Route::post('/post/unlike/{id}', [LikePostController::class, 'unlike']);

    /*      COMMENT    */
    Route::post('/comment', [CommentController::class, 'createComment']);
    Route::put('/comment/{id}', [CommentController::class, 'updateComment']);
    Route::delete('/comment/{id}', [CommentController::class, 'deleteComment']);
    Route::post('/comment/like/{id}', [LikeCommentController::class, 'like']);
    Route::post('/comment/unlike/{id}', [LikeCommentController::class, 'unlike']);
});

/*      AUTH       */
Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('/auth/resend_verification_code', [AuthController::class, 'resendEmailVerificationCode']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

/*      USER       */
Route::get('/user/{id}', [UserController::class, 'getUserById']);

/*      PING       */
Route::get('/', function() {
    return response()->json(['hello' => "Hello"], 200);
});
