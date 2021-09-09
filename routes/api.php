<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobVacancyController;
// use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSkillController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/projects/search/{name}', [ProjectController::class, 'search']);
Route::get('/profiles/{username}/skills', [UserSkillController::class, 'index']);

// Route::get('/recommendations/skill', []);
// Route::get('/recommendations/city', []);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // projects
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    // jobs
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    // user profile (skills)
    Route::post('/profile/skills', [UserSkillController::class, 'store']);
    Route::patch('/profile/skills/{uuid}', [UserSkillController::class, 'update']);
    Route::delete('/profile/skills/{uuid}', [UserSkillController::class, 'destroy']);
    // friendships (followings)
    Route::get('/profile/followings', [UserFolloweeController::class, 'index']);
    Route::post('/friendships/{username}/follow', [UserFolloweeController::class, 'store']);
    Route::post('/friendships/{username}/unfollow', [UserFolloweeController::class, 'destroy']);
    // friendships (followers)
    Route::get('/profile/followers', [UserFollowerController::class, 'index']);
    Route::post('/friendships/{username}/approve', [UserFollowerController::class, 'store']);
    Route::post('/friendships/{username}/ignore', [UserFollowerController::class, 'reject']);
    Route::post('/friendships/{username}/remove', [UserFollowerController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
