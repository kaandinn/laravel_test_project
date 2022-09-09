<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public endpoints
Route::apiResources([
    'register' => AuthController1::class,
]);

// Protected endpoints
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResources([
    'tickets' => TicketController::class,
    'comments' => CommentController::class,
    'users' => UserController::class,
    ]);
});
