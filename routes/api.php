<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [AuthController::class, 'getAuthUser']);
    Route::post('post/create', [PostController::class, 'store']);
    Route::get('posts', [PostController::class, 'index']);
    Route::get('post/{post_id}', [PostController::class, 'show']);
    Route::post('post/like/{post_id}', [PostController::class, 'like']);
    Route::get('posts/get-my-posts', [PostController::class, 'getMyPosts']);
    Route::post('user/follow-user/{user_id}', [UserController::class, 'toggleFollowUser']);
    Route::get('user/get-my-followings', [UserController::class, 'getMyFollowings']);
    Route::get('user/get-my-followers', [UserController::class, 'getMyFollowers']);
    Route::get('user/get-liked-posts', [UserController::class, 'getLikedPosts']);
    Route::get('user/get-liked-post-ids', [UserController::class, 'getLikedPostIds']);
});