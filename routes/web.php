<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserTokens;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\authentications\LoginBasic;
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
  $controller_path = 'App\Http\Controllers';
  Route::get('/user/dashboard', $controller_path . '\pages\HomePage@userIndex')->name('user.dashboard');
  Route::group(['prefix' => 'admin'], function () {
    $controller_path = 'App\Http\Controllers';
    Route::get('/dashboard', $controller_path . '\pages\HomePage@index')->name('admin.dashboard');
    Route::group(['prefix' => 'modules'], function () {
      Route::get('/index', [ModuleController::class, 'index'])->name('modules.index');
      Route::get('/edit/{code}', [ModuleController::class, 'edit'])->name('modules.edit');
      Route::post('/update/{code}', [ModuleController::class, 'update'])->name('modules.update');
      Route::post('/isActive/{code}', [ModuleController::class, 'updateIsActive'])->name('modules.updateIsActive');
    });

    Route::group(['prefix' => 'permissions'], function () {
      Route::get('/index', [PermissionController::class, 'index'])->name('permissions.index');
      Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
      Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
      Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
      Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
      Route::post('/delete/{id}', [PermissionController::class, 'delete'])->name('permissions.delete');
      Route::post('/isActive/{id}', [PermissionController::class, 'updateIsActive'])->name(
        'permissions.updateIsActive'
      );
    });

    Route::group(['prefix' => 'roles'], function () {
      Route::get('/index', [RoleController::class, 'index'])->name('roles.index');
      Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
      Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
      Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
      Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
      Route::post('/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
      Route::get('/isActive/{id}', [RoleController::class, 'updateIsActive'])->name('roles.updateIsActive');
    });

    Route::group(['prefix' => 'users'], function () {
      Route::get('/index', [UserController::class, 'index'])->name('users.index');
      Route::get('/create', [UserController::class, 'create'])->name('users.create');
      Route::post('/store', [UserController::class, 'store'])->name('users.store');
      Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
      Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
      Route::get('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
      Route::post('/isActive/{id}', [UserController::class, 'updateIsActive'])->name('users.updateIsActive');
      Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
      Route::post('/forced-logout', [UserController::class, 'forceLogout'])->name('users.forceLogout');
    });

    Route::get('/logout', $controller_path . '\authentications\LoginBasic@logout')->name('auth.logout');
  });
});
