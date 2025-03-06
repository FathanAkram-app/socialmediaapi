<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Routes
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/profile', [UserController::class, 'getMyProfile']);
    Route::get('/users/{userId}', [UserController::class, 'getOtherUserProfile']);
    Route::get('/users/search/{name}', [UserController::class, 'searchUserByName']);
    Route::post('/users/{userId}/follow', [UserController::class, 'followUser']);
    Route::post('/users/{userId}/unfollow', [UserController::class, 'unfollowUser']);
    Route::get('/users/{userId}/followers', [UserController::class, 'getFollowers']);
    Route::get('/users/{userId}/following', [UserController::class, 'getFollowing']);

    // Post Routes
    Route::post('/posts', [PostController::class, 'addPost']);
    Route::put('/posts/{postId}', [PostController::class, 'editPost']);
    Route::delete('/posts/{postId}', [PostController::class, 'deletePost']);
    Route::get('/posts', [PostController::class, 'getAllPosts']);
    Route::get('/posts/user/{userId}', [PostController::class, 'getPostsByUserId']);
    Route::get('/posts/following', [PostController::class, 'getPostsByFollowingUsers']);
    Route::get('/posts/{postId}', [PostController::class, 'getSpecificPost']);
    Route::post('/posts/{postId}/like', [PostController::class, 'likePost']);
    Route::post('/posts/{postId}/unlike', [PostController::class, 'unlikePost']);
    Route::post('/posts/{postId}/comments', [PostController::class, 'addComment']);
    Route::delete('/comments/{commentId}', [PostController::class, 'deleteComment']);
    Route::get('/posts/liked', [PostController::class, 'getMyLikedPosts']);
});
