<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ListingController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('projects', ProjectController::class)->except([
	'create', 'edit'
]);

Route::resource('listings', ListingController::class)->except([
	'create', 'edit'
]);
Route::get('/listings/search/{q}', [ListingController::class, 'search'])
				->name('listings.search');