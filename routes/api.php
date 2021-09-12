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
Route::get('/users/{id}/skills', [UserSkillController::class, 'index']);


// Route::get('/recommendations/skill', []);
// Route::get('/recommendations/city', []);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // projects
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::patch('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);


    // jobs
    Route::post('/jobs', [JobController::class, 'store']);
    Route::patch('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    // ** Alternatively, you can do this:
    // Route::apiResource('jobs', JobController::class)->except(['index', 'show']);
    // Route::apiResource('projects/{project}/jobs', JobController::class)->except(['index', 'show']);


    // user profile (skills)
    Route::post('/users/me/skills', [UserSkillController::class, 'store']);
    Route::patch('/users/me/skills/{id}', [UserSkillController::class, 'update']);
    Route::delete('/users/me/skills/{id}', [UserSkillController::class, 'destroy']);



    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
