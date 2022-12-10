<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\UserAdminController;

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LeaderBoardController;
use Cloudinary\Transformation\Rotate;
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
Route::post('/users/settings', [UserApiController::class, 'updateSetting'])->middleware('myauth');

Route::get('/questions', [QuestionController::class, 'getQuestionAndAnswer'])->middleware('myauth');
Route::get('/questionsrank', [QuestionController::class, 'getQuestionAndAnswerRankMode'])->middleware('myauth');


Route::get('/quizz', [TopicController::class, 'getTopics'])->middleware('myauth');

Route::post('/leaderboard', [LeaderBoardController::class, 'SetLeaderBoard'])->middleware('myauth');
Route::get('/leaderboard', [LeaderBoardController::class, 'getList'])->middleware('myauth');


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('verify', 'verify');
});

Route::post('/reset-password-request', [PasswordResetRequestController::class, 'sendPasswordResetEmailUser']);
Route::post('/reset-admin-password-request', [PasswordResetRequestController::class, 'sendEmailPasswordResetAdmin']);

Route::post('/registerAdmin', [UserAdminController::class, 'createAccountAdmin']);
