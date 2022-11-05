<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\PasswordResetRequestController;

use App\Http\Controllers\ChangePasswordController;


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

Route::get('/users', [UserApiController::class, 'getProfile'])->middleware('myauth');
Route::post('/users', [UserApiController::class, 'updateProfile'])->middleware('myauth');

Route::get('/questions', [QuestionController::class, 'getQuestionAndAnswer']);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('verify','verify');
});

Route::post('/reset-password-request', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
    Route::post('/change-password', [PasswordResetRequestController::class, 'passwordResetProcess']);
});
