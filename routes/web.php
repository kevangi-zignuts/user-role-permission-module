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
// Reset Password Page Route
Route::get('/reset-password-link/{token}', [ResetPasswordController::class, 'showForm'])->name('resetPassword');
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'submit'])->name('resetPasswordSubmit');

Route::middleware('auth', 'adminCheck')->group(function () {
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
      Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
      Route::get('/forced-logout/{id}', [UserController::class, 'forceLogout'])->name('users.forceLogout');
    });
  });
});
Route::get('/logout', $controller_path . '\authentications\LoginBasic@logout')
  ->name('auth.logout')
  ->middleware('auth');

Route::middleware('auth', 'isUser')->group(function () {
  Route::group(['prefix' => 'user'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/edit', [DashboardController::class, 'edit'])->name('user.edit');
    Route::post('/update', [DashboardController::class, 'update'])->name('user.update');
    Route::post('/resetPassword', [DashboardController::class, 'resetPassword'])->name('user.resetPassword');

    Route::group(['prefix' => 'company'], function () {
      Route::get('/index', [CompanyController::class, 'index'])
        ->name('company.index')
        ->middleware('permission:' . 'com' . ',view_access');
      Route::get('/create', [CompanyController::class, 'create'])
        ->name('company.create')
        ->middleware('permission:' . 'com' . ',add_access');
      Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
      Route::get('/edit/{id}', [CompanyController::class, 'edit'])
        ->name('company.edit')
        ->middleware('permission:' . 'com' . ',edit_access');
      Route::post('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
      Route::get('/delete/{id}', [CompanyController::class, 'delete'])
        ->name('company.delete')
        ->middleware('permission:' . 'com' . ',delete_access');
    });

    Route::group(['prefix' => 'activityLogs'], function () {
      Route::get('/index', [ActivityLogController::class, 'index'])
        ->name('activityLogs.index')
        ->middleware('permission:' . 'act' . ',view_access');
      Route::get('/create', [ActivityLogController::class, 'create'])
        ->name('activityLogs.create')
        ->middleware('permission:' . 'act' . ',add_access');
      Route::post('/store', [ActivityLogController::class, 'store'])->name('activityLogs.store');
      Route::get('/status/{id}', [ActivityLogController::class, 'updateStatus'])->middleware(
        'permission:' . 'act' . ',view_access'
      );
      Route::get('/edit/{id}', [ActivityLogController::class, 'edit'])
        ->name('activityLogs.edit')
        ->middleware('permission:' . 'act' . ',edit_access');
      Route::post('/update/{id}', [ActivityLogController::class, 'update'])->name('activityLogs.update');
      Route::get('/delete/{id}', [ActivityLogController::class, 'delete'])
        ->name('activityLogs.delete')
        ->middleware('permission:' . 'act' . ',delete_access');
    });

    Route::group(['prefix' => 'meetings'], function () {
      Route::get('/index', [MeetingController::class, 'index'])
        ->name('meetings.index')
        ->middleware('permission:' . 'meet' . ',view_access');
      Route::get('/create', [MeetingController::class, 'create'])
        ->name('meetings.create')
        ->middleware('permission:' . 'meet' . ',add_access');
      Route::post('/store', [MeetingController::class, 'store'])->name('meetings.store');
      Route::get('/status/{id}', [MeetingController::class, 'updateStatus'])->middleware(
        'permission:' . 'meet' . ',view_access'
      );
      Route::get('/edit/{id}', [MeetingController::class, 'edit'])
        ->name('meetings.edit')
        ->middleware('permission:' . 'meet' . ',edit_access');
      Route::post('/update/{id}', [MeetingController::class, 'update'])->name('meetings.update');
      Route::get('/delete/{id}', [MeetingController::class, 'delete'])
        ->name('meetings.delete')
        ->middleware('permission:' . 'meet' . ',delete_access');
    });

    Route::group(['prefix' => 'notes'], function () {
      Route::get('/index', [NoteController::class, 'index'])
        ->name('notes.index')
        ->middleware('permission:' . 'note' . ',view_access');
      Route::get('/create', [NoteController::class, 'create'])
        ->name('notes.create')
        ->middleware('permission:' . 'note' . ',add_access');
      Route::post('/store', [NoteController::class, 'store'])->name('notes.store');
      Route::get('/edit/{id}', [NoteController::class, 'edit'])
        ->name('notes.edit')
        ->middleware('permission:' . 'note' . ',edit_access');
      Route::post('/update/{id}', [NoteController::class, 'update'])->name('notes.update');
      Route::get('/delete/{id}', [NoteController::class, 'delete'])
        ->name('notes.delete')
        ->middleware('permission:' . 'note' . ',delete_access');
    });

    Route::group(['prefix' => 'people'], function () {
      Route::get('/index', [PeopleController::class, 'index'])
        ->name('people.index')
        ->middleware('permission:' . 'peo' . ',view_access');
      Route::get('/create', [PeopleController::class, 'create'])
        ->name('people.create')
        ->middleware('permission:' . 'peo' . ',add_access');
      Route::post('/store', [PeopleController::class, 'store'])->name('people.store');
      Route::get('/status/{id}', [PeopleController::class, 'updateStatus'])->middleware(
        'permission:' . 'peo' . ',view_access'
      );
      Route::get('/edit/{id}', [PeopleController::class, 'edit'])
        ->name('people.edit')
        ->middleware('permission:' . 'peo' . ',edit_access');
      Route::post('/update/{id}', [PeopleController::class, 'update'])->name('people.update');
      Route::get('/delete/{id}', [PeopleController::class, 'delete'])
        ->name('people.delete')
        ->middleware('permission:' . 'peo' . ',delete_access');
    });
  });
});
