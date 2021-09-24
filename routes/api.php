<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatInviteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectLikeController;
use App\Http\Controllers\ProjectInviteController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\MeetupController;
use App\Http\Controllers\UserSkillController;
use App\Http\Controllers\UserLanguageController;

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
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/projects/{id}/likes', [ProjectLikeController::class, 'index']);
Route::get('/projects/search/{name}', [ProjectController::class, 'search']);
Route::get('/users/{id}/skills', [UserSkillController::class, 'index']);
Route::get('/job-vacancies', [JobVacancyController::class, 'index']);
Route::get('/job-vacancies/{id}', [JobVacancyController::class, 'show']);
Route::get('/meetups', [MeetupController::class, 'index']);
Route::get('/meetups/{id}', [MeetupController::class, 'show']);


// Route::get('/recommendations/skill', []);
// Route::get('/recommendations/city', []);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // friendships
    Route::post('/friendships/users/{id}/follow', [FriendshipController::class, 'follow']);
    Route::post('/friendships/users/{id}/unfollow', [FriendshipController::class, 'unfollow']);

    // chats
    // Route::post('/chats', [PrivateChatController::class, 'store']);
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/{id}', [ChatController::class, 'show']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::patch('/chats/{id}', [ChatController::class, 'update']);
    Route::delete('/chats/{id}', [ChatController::class, 'destroy']);

    // chats (invites)
    Route::post('/chats/{chatId}/invites', [ChatInviteController::class, 'store']);
    Route::patch('/chats/{chatId}/invites/{id}', [ChatInviteController::class, 'update']);
    Route::delete('/chats/{chatId}/invites/{id}', [ChatInviteController::class, 'destroy']);

    // chats (messages)
    Route::post('/chats/{id}/messages', [MessageController::class, 'store']);
    Route::patch('/chats/{chatId}/messages/{id}', [MessageController::class, 'update']);
    Route::delete('/chats/{chatId}/messages/{id}', [MessageController::class, 'destroy']);

    // projects
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::patch('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // project likes
    Route::post('/projects/{id}/likes', [ProjectLikeController::class, 'store']);
    Route::delete('/projects/{id}/likes', [ProjectLikeController::class, 'destroy']);

    // project invites
    Route::get('/users/me/project-invites', [ProjectInviteController::class, 'index']);
    Route::get('/projects/{projectId}/invites/{id}', [ProjectInviteController::class, 'show']);
    Route::post('/projects/{projectId}/invites', [ProjectInviteController::class, 'store']);
    Route::patch('/projects/{projectId}/invites/{id}', [ProjectInviteController::class, 'update']);
    Route::delete('/projects/{projectId}/invites/{id}', [ProjectInviteController::class, 'destroy']);


    // jobs
    Route::post('/jobs', [JobController::class, 'store']);
    Route::patch('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    // ** Alternatively, you can do this:
    // Route::apiResource('jobs', JobController::class)->except(['index', 'show']);
    // Route::apiResource('projects/{project}/jobs', JobController::class)->except(['index', 'show']);


    // job vacancies
    Route::post('/job-vacancies', [JobVacancyController::class, 'store']);
    Route::patch('/job-vacancies/{id}', [JobVacancyController::class, 'update']);
    Route::delete('/job-vacancies/{id}', [JobVacancyController::class, 'destroy']);

    // meetups
    Route::post('/meetups', [MeetupController::class, 'store']);
    Route::patch('/meetups/{id}', [MeetupController::class, 'update']);
    Route::delete('/meetups/{id}', [MeetupController::class, 'destroy']);

    // meetup RSVPs


    // user skills
    Route::post('/users/me/skills', [UserSkillController::class, 'store']);
    Route::patch('/users/me/skills/{id}', [UserSkillController::class, 'update']);
    Route::delete('/users/me/skills/{id}', [UserSkillController::class, 'destroy']);

    // user languages
    Route::post('/users/me/languages/{id}', [UserLanguageController::class, 'store']);
    Route::delete('/users/me/languages/{id}', [UserLanguageController::class, 'destroy']);



    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
