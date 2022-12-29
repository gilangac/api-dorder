<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;// manggil controller sesuai bawaan laravel 8

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
Route::get('forget-password', [AuthController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.post');
Route::get('password/reset/{token}', [AuthController::class, 'resetPasswordView'])->name('password.reset');

Route::get('/', function () {
    return view('welcome');
});
