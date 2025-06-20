<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ExperienceUserController;
use App\Http\Controllers\SubscriptionController;

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
Route::post('/subscription',  [RouteController::class, 'storeSubscription'])->name('subscription.store');
Route::get('/contact',  [RouteController::class, 'contact'])->name('contact');

// Register Post route
Route::post('/register', [RegisterController::class, 'register'])->name('register.store.custom');


Route::resource('experience', ExperienceUserController::class);


// Home subscription management routes
Route::get('/home/subscriptions', [SubscriptionController::class, 'manage'])->name('subscription.manage');
Route::patch('/subscription/{id}/status', [SubscriptionController::class, 'updateStatus'])->name('subscription.updateStatus');
Route::post('/subscription/{id}/pause', [SubscriptionController::class, 'pauseSubscription'])->name('subscription.pause');
Route::post('/subscription/{id}/resume', [SubscriptionController::class, 'resumeSubscription'])->name('subscription.resume');


Auth::routes(['verify' => true]);

Route::group(['middleware' => ['verified']], function () {
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

    Route::patch('/subscriptions/{id}/approve', [App\Http\Controllers\SubscriptionController::class, 'approve'])->name('subscription.approve');
    Route::patch('/subscriptions/{id}/reject', [App\Http\Controllers\SubscriptionController::class, 'reject'])->name('subscription.reject');
    Route::get('/subscriptions/{id}/details', [App\Http\Controllers\SubscriptionController::class, 'getDetails'])->name('subscription.details');
    Route::patch('/subscriptions/{id}/pause', [App\Http\Controllers\SubscriptionController::class, 'pauseSubscription'])->name('subscription.pause');
    Route::patch('/subscriptions/{id}/resume', [App\Http\Controllers\SubscriptionController::class, 'resumeSubscription'])->name('subscription.resume');
});

Route::group(['middleware' => ['verified', 'CheckRole:user']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::group(['middleware' => ['verified', 'CheckRole:admin']], function () {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');
});
