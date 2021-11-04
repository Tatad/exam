<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Auth::routes(['register' => false]);


Route::get('/users/invite', [App\Http\Controllers\UsersController::class, 'invite_view'])->name('invite_view');
Route::post('/users/invite', [App\Http\Controllers\UsersController::class, 'process_invites'])->name('process_invite');
Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users');
Route::get('/registration/{token}', [App\Http\Controllers\UsersController::class, 'registration_view'])->name('registration');
Route::post('/verify', [App\Http\Controllers\UsersController::class, 'verify'])->name('verify');
Route::post('/update-user', [App\Http\Controllers\UsersController::class, 'update'])->name('update-user');

Route::post('/registration/', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('accept');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

