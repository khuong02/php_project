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
$controller_path = 'App\Http\Controllers';


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
Route::get('/account/admin', [AccountManegementController::class, 'accountManegementAdmin'])->name('account-admin')->middleware('hendletoken');
Route::get('/account/user', [AccountManegementController::class, 'accountManegementUser'])->name('account-user')->middleware('hendletoken');
Route::post('/account-admin/delete', [AccountManegementController::class, 'deleteAccountAdmin'])->name('delete-account-admin')->middleware('hendletoken');
Route::post('/account-user/delete', [AccountManegementController::class, 'deleteAccountUser'])->name('delete-account-user')->middleware('hendletoken');
Route::get('/account-admin/edit/{id}', [AccountManegementController::class, 'editAccountAdmin'])->middleware('hendletoken');
Route::get('/account-user/edit/{id}', [AccountManegementController::class, 'editAccountUser'])->middleware('hendletoken');

Route::post('/account-admin/edit', [AccountManegementController::class, 'editAccountAdminPost'])->name('edit-account-admin')->middleware('hendletoken');
Route::post('/account-user/edit', [AccountManegementController::class, 'editAccountUserPost'])->name('edit-account-user')->middleware('hendletoken');



Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');

// layout
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');



// cards
// Route::get('/topics', $controller_path . '\topic\Topic@index')->name('topics');

Route::controller(Topic::class)->group(function () {
    Route::get('topics', 'index')->name('topics');
    Route::get('topiclist', 'getTopicList');
    Route::post('topics', 'store')->name('topic-store');
    Route::get('topics/{id}','edit')->name('topic-edit');
    Route::delete('topics/{id}', 'delete');
    Route::put('topics/{id}', 'update');
});

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/questions', $controller_path . '\question\Questions@index')->name('questions');
Route::get('/answers', $controller_path . '\question\Answers@index')->name('answers');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');

// form elements
Route::get('/user-manage/users', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');

// tables
Route::get('/user-manage/admin', $controller_path . '\tables\Basic@index')->name('tables-basic');
