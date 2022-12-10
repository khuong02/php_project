<?php

use App\Http\Controllers\AuthUI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\AccountManegementController;
use App\Http\Controllers\topic\Topic;
use App\Http\Controllers\question\QUestions;
use App\Http\Controllers\web\AccountUserManagementController;
use App\Http\Controllers\web\AccountAdminManagementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Main Page Route
Route::get('/', [HomeController::class, 'index'])->name('dashboard-analytics')->middleware('hendletoken');

// authentication
Route::get('/auth/login', [AuthUI::class, 'loginPage'])->name('auth-login-basic')->middleware('authadmin');
Route::post('/auth/login', [UserAdminController::class, 'loginAdmin'])->name('login-admin');
Route::get('/auth/forgot-password', [AuthUI::class, 'ForgotPassworPage'])->name('auth-reset-password')->middleware('authadmin');
Route::get('/auth/logout', [UserAdminController::class, 'adminLogOut'])->name('logout-admin');
Route::get('/change-password/{token}', [ChangePasswordController::class, 'getFormResetPassword']);
Route::post('/change-password', [ChangePasswordController::class, 'passwordReset'])->name('change-password');
Route::get('/account', [AccountManegementController::class, 'updateProfileUser'])->name('account-profile')->middleware('hendletoken');
Route::post('/account/update-profile', [UserAdminController::class, 'upProfile'])->name('update-profile-admin')->middleware('hendletoken');



// Account Management user
Route::controller(AccountUserManagementController::class)->group(function () {
    Route::get('/account/user', 'index')->name('account-user')->middleware('hendletoken');
    Route::get('/account/userlist', 'getUserList');
    Route::get('/account/user/update/{id}', 'getUiUpdateUser')->middleware('hendletoken');
    Route::put('/account/user/{id}', 'update')->name('edit-account-user')->middleware('hendletoken');
    Route::delete('/account/user/{id}', 'delete')->middleware('hendletoken');
});

// Account Management admin
Route::controller(AccountAdminManagementController::class)->group(function () {
    Route::get('/account/admin', 'index')->name('account-admin')->middleware('hendletoken');
    Route::get('/account/adminlist', 'getAdminList');
    Route::get('/account/admin/update/{id}', 'getUiUpdateUser')->middleware('hendletoken');
    Route::put('/accout/admin/{id}', 'update')->name('edit-account-admin')->middleware('hendletoken');
    Route::post('/account/admin', 'store')->middleware('hendletoken');
    Route::delete('/account/admin/{id}', 'delete')->middleware('hendletoken');
});

// Topic Management
Route::controller(Topic::class)->group(function () {
    Route::get('topics', 'index')->name('topics')->middleware('hendletoken');
    Route::get('topiclist', 'getTopicList');
    Route::post('topics', 'store')->name('topic-store')->middleware('hendletoken');
    Route::get('topics/{id}', 'edit')->name('topic-edit')->middleware('hendletoken');
    Route::put('topics/{id}', 'update')->middleware('hendletoken');
    Route::delete('topics/{id}', 'delete')->middleware('hendletoken');
});


// Questions Management
Route::controller(Questions::class)->group(function () {
    Route::get('questions', 'index')->name('questions')->middleware('hendletoken');
    Route::get('questionlist', 'getQuestionList');
    Route::get('questions/update/{id}', 'getUiUpdateQuestion')->name('questions-edit')->middleware('hendletoken');
    Route::put('questions/{id}', 'update')->name('update-question')->middleware('hendletoken');
    Route::post('questions', 'store')->name('questions-store')->middleware('hendletoken');
    Route::delete('questions/{id}', 'delete')->name('questions-delete')->middleware('hendletoken');
});
