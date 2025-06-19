<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ExperienceUserController;

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

Route::get('/',  [RouteController::class, 'beranda'])->name('beranda');
Route::get('/menu',  [RouteController::class, 'menu'])->name('menu');
Route::get('/subscription',  [RouteController::class, 'subscription'])->name('subscription');
Route::get('/contact',  [RouteController::class, 'contact'])->name('menu');

// Register Post route
Route::post('/register', [RegisterController::class, 'register'])->name('register.store.custom');
 

Route::resource('experience', ExperienceUserController::class);


Auth::routes(['verify' => true]);

Route::group(['middleware' => ['verified']], function () { 
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
});

Route::group(['middleware' => ['verified', 'CheckRole:user']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::group(['middleware' => ['verified', 'CheckRole:admin']], function () {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');
});
