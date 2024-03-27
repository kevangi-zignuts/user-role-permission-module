<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\authentications\LoginBasic;
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
Route::get('/', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
// Route::get('/login', $controller_path . '\authentications\LoginBasic@login')->name('auth-login');
Route::post('/', $controller_path . '\authentications\LoginBasic@login')->name('login');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name(
  'auth-register-basic'
);
Route::middleware('auth')->group(function () use ($controller_path) {
  // Main Page Route
  Route::get('/dashboard', $controller_path . '\pages\HomePage@index')->name('pages-home');
  Route::group(['prefix' => 'modules'], function () {
    Route::get('/index', [ModuleController::class, 'index'])->name('pages-page-2');
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
    Route::post('/isActive/{id}', [PermissionController::class, 'updateIsActive'])->name('permissions.updateIsActive');
  });

  Route::group(['prefix' => 'roles'], function () {
    Route::get('/index', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::post('/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
    Route::post('/isActive/{id}', [RoleController::class, 'updateIsActive'])->name('roles.updateIsActive');
  });

  Route::group(['prefix' => 'users'], function () {
    Route::get('/index', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('/isActive/{id}', [UserController::class, 'updateIsActive'])->name('users.updateIsActive');
    Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::post('/forced-logout/{id}', [UserController::class, 'forceLogout'])->name('users.forceLogout');
  });

  Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

  // Route::get(
  //   '/forger-password-link',
  //   $controller_path . '\authentications\ForgetPasswordController@howForgetPasswordForm'
  // )->name('forgetPassword');
  Route::post(
    '/email/forgetPassword',
    $controller_path . '\authentications\ForgetPasswordController@submitForgetPasswordForm'
  )->name('email.forget.password');
});
Route::get('/forget-password-link', [ForgetPasswordController::class, 'showForgetPasswordForm'])->name(
  'forgetPassword'
);
Route::post('/forget-password', [ForgetPasswordController::class, 'submitForgetPasswordForm'])->name(
  'forgetPasswordForm'
);
Route::get('/reset-password-link/{id}', [ForgetPasswordController::class, 'resetPasswordForm'])->name('resetPassword');
Route::post('/reset-password/{id}', [ForgetPasswordController::class, 'resetPassword'])->name('resetPasswordSubmit');
