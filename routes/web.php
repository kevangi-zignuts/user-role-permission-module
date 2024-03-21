<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\ForgetPasswordController;
use App\Http\Controllers\ModuleController;

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
Route::get('/login', $controller_path . '\authentications\LoginBasic@login')->name('auth-login');
Route::post('/login', $controller_path . '\authentications\LoginBasic@login')->name('auth-login');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name(
  'auth-register-basic'
);
Route::middleware('auth')->group(function () {
  $controller_path = 'App\Http\Controllers';
  // Main Page Route
  Route::get('/dashboard', $controller_path . '\pages\HomePage@index')->name('pages-home');
  // Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');
  Route::group(['prefix' => 'modules'], function () {
    Route::get('/index', [ModuleController::class, 'index'])->name('pages-page-2');
    Route::get('/edit/{code}', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::post('/update/{code}', [ModuleController::class, 'update'])->name('modules.update');
    Route::post('/isActive/{code}', [ModuleController::class, 'updateIsActive'])->name('modules.updateIsActive');
  });

  Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

  // authentication
  // Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');

  Route::get(
    '/forger-password-link',
    $controller_path . '\authentications\ForgetPasswordController@howForgetPasswordForm'
  )->name('forgetPassword');
  Route::post(
    '/email/forgetPassword',
    $controller_path . '\authentications\ForgetPasswordController@submitForgetPasswordForm'
  )->name('email.forget.password');
});

// pages
Route::get('/sample', function () {
  return view('sample');
});
