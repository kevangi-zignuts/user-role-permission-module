<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserTokens;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\users\NoteController;
use App\Http\Controllers\users\PeopleController;
use App\Http\Controllers\users\CompanyController;
use App\Http\Controllers\users\MeetingController;
use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\users\ActivityLogController;
use App\Http\Controllers\authentications\ResetPasswordController;
use App\Http\Controllers\authentications\ForgetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
$controller_path = 'App\Http\Controllers';
// login page Route
Route::get('/', $controller_path . '\authentications\LoginBasic@loginForm')->name('auth-login-basic');
Route::post('/', $controller_path . '\authentications\LoginBasic@login')->name('login');
// Forget Password Page Route
Route::get('/forget-password-link', [ForgetPasswordController::class, 'showForm'])->name('forgetPassword');
Route::post('/forget-password', [ForgetPasswordController::class, 'submit'])->name('forgetPasswordForm');
// Reset Password Page Route when user invite
Route::get('/invite/reset-password-link/{token}', [ResetPasswordController::class, 'showForm'])->name('resetPassword');
Route::post('/invite/reset-password/{token}', [ResetPasswordController::class, 'submit'])->name('resetPasswordSubmit');
// Reset Password Page Route when user forgot password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPasswordForm'])->name('resetPasswordForm');
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'submitForm'])->name('resetPasswordSubmitForm');

Route::middleware('auth', 'access')->group(function () {
  // Main Page Route

  Route::group(['prefix' => 'admin'], function () {
    $controller_path = 'App\Http\Controllers';
    Route::get('/dashboard', $controller_path . '\pages\HomePage@index')->name('admin.dashboard');

    Route::group(['prefix' => 'modules'], function () {
      Route::get('/index', [ModuleController::class, 'index'])->name('modules.index');
      Route::get('/edit/{code}', [ModuleController::class, 'edit'])->name('modules.edit');
      Route::post('/update/{code}', [ModuleController::class, 'update'])->name('modules.update');
      Route::get('/status/{code}', [ModuleController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'permissions'], function () {
      Route::get('/index', [PermissionController::class, 'index'])->name('permissions.index');
      Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
      Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
      Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
      Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
      Route::get('/delete/{id}', [PermissionController::class, 'delete'])->name('permissions.delete');
      Route::get('/status/{id}', [PermissionController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'roles'], function () {
      Route::get('/index', [RoleController::class, 'index'])->name('roles.index');
      Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
      Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
      Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
      Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
      Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
      Route::get('/status/{id}', [RoleController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'users'], function () {
      Route::get('/index', [UserController::class, 'index'])->name('users.index');
      Route::get('/create', [UserController::class, 'create'])->name('users.create');
      Route::post('/store', [UserController::class, 'store'])->name('users.store');
      Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
      Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
      Route::get('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
      Route::get('/status/{id}', [UserController::class, 'updateStatus']);
      Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
      Route::get('/forced-logout/{id}', [UserController::class, 'forceLogout'])->name('users.forceLogout');
    });

    Route::get('/logout', $controller_path . '\authentications\LoginBasic@logout')->name('auth.logout');
  });

  Route::group(['prefix' => 'user'], function () {
    // $controller_path = 'App\Http\Controllers';
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/edit', [DashboardController::class, 'edit'])->name('user.edit');
    Route::post('/update', [DashboardController::class, 'update'])->name('user.update');
    Route::post('/resetPassword', [DashboardController::class, 'resetPassword'])->name('user.resetPassword');

    Route::group(['prefix' => 'company'], function () {
      Route::get('/index', [CompanyController::class, 'index'])->name('company.index');
    });

    Route::group(['prefix' => 'activityLogs'], function () {
      Route::get('/index', [ActivityLogController::class, 'index'])->name('activityLogs.index');
    });

    Route::group(['prefix' => 'meetings'], function () {
      Route::get('/index', [MeetingController::class, 'index'])->name('meetings.index');
    });

    Route::group(['prefix' => 'notes'], function () {
      Route::get('/index', [NoteController::class, 'index'])->name('notes.index');
    });

    Route::group(['prefix' => 'people'], function () {
      Route::get('/index', [PeopleController::class, 'index'])->name('people.index');
    });
  });
});
