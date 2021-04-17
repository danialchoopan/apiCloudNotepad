<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
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

Route::group([
    'middleware' => 'JwtCheckToken',
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    Route::get('checkToken', [AuthController::class, 'checkToken']);
    Route::post('logout', [AuthController::class, 'logout']);
});

 Route::group([
     'middleware' => 'JwtCheckToken'
 ], function () {
     Route::apiResource('note', NoteController::class);
     Route::get('noteuser', [NoteController::class, 'userNote']);
     Route::post('changenotestatus/{noteid}', [NoteController::class, 'changeNoteStatus']);
 });

