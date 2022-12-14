<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cloudinary\Transformation\Rotate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Models\Friend;

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
Route::post('/users/buycredit', [UserApiController::class, 'buyCredit'])->middleware('myauth');
Route::post('/users/changepass', [UserApiController::class, 'changePassword'])->middleware('myauth');

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


Route::controller(FriendController::class)->group(function () {
    Route::get('/get-list-friend', 'getListFriend')->middleware('myauth');
    Route::get('/get-list-pending', 'getListFriendPending')->middleware('myauth');
    Route::get('/get-list-stranger', 'getUserStranger')->middleware('myauth');
    Route::post('/addFriend', 'addFriend')->middleware('myauth');
    Route::post('/unFriend', 'unFriend')->middleware('myauth');
    Route::post('/respond2invitation', 'respond2FriendRequest')->middleware('myauth');
});

// Route::post('/addFriend', [FriendController::class, 'addFriend'])->middleware('myauth');
// Route::post('/respond2invitation', [FriendController::class, 'respond2FriendRequest'])->middleware('myauth');
// Route::post('/unFriend', [FriendController::class, 'unFriend'])->middleware('myauth');
// Route::get('/get-list-friend', [FriendController::class, 'getListFriend'])->middleware('myauth');
// Route::get('/get-list-pending', [FriendController::class, 'getListFriendPending'])->middleware('myauth');


// Route::get('/get-list-stranger', [FriendController::class, 'getUserStranger'])->middleware('myauth');
