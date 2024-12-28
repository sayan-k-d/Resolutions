<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResolutionController;
use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ResolutionController::class, 'getResolutions'])->name('dashboard');
Route::get('/search', [ResolutionController::class, 'getSearchs'])->name('search');

Route::post('/store', [ResolutionController::class, 'store'])->name('store');
Route::post('/likes/{id}', [ResolutionController::class, 'storeLikes'])->name('likes')->middleware(CheckAuth::class);
Route::put('/updateStatus/{id}', [ResolutionController::class, 'updateResolutionStatus'])->name('updateStatus')->middleware(CheckAuth::class);
Route::delete('/deleteResolution/{id}', [ResolutionController::class, 'deleteResolution'])->name('destroy')->middleware(CheckAuth::class);
Route::get('/editResolution/{id}', [ResolutionController::class, 'editResolution'])->name('edit')->middleware(CheckAuth::class);
Route::put('/updateResolution/{id}', [ResolutionController::class, 'updateResolution'])->name('update')->middleware(CheckAuth::class);

Route::get('/comments/{id}', [ResolutionController::class, 'getComments'])->name('comments');
Route::post('/storeComment/{id}', [ResolutionController::class, 'storeComments'])->name('storeComment')->middleware(CheckAuth::class);
Route::delete('/deleteComment/{id}', [ResolutionController::class, 'deleteComments'])->name('deleteComment')->middleware(CheckAuth::class);
Route::get('/editComment/{id}', [ResolutionController::class, 'editComments'])->name('editComment');
Route::put('/updateComment/{id}', [ResolutionController::class, 'updateComments'])->name('updateComment');

Route::post('/storeReply/{id}', [ResolutionController::class, 'storeReply'])->name('storeReply')->middleware(CheckAuth::class);
Route::delete('/deleteReply/{id}', [ResolutionController::class, 'deleteReply'])->name('deleteReply')->middleware(CheckAuth::class);
Route::get('/editReply/{id}', [ResolutionController::class, 'editReplies'])->name('editReply');
Route::put('/updateReply/{id}', [ResolutionController::class, 'updateReplies'])->name('updateReply');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');

Route::post('/login', [AuthController::class, 'signin'])->name('signin');
Route::post('/signup', [AuthController::class, 'register'])->name('register');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware(CheckAuth::class);
Route::get('/edit', [AuthController::class, 'edit'])->name('editProfile')->middleware(CheckAuth::class);
Route::post('/update/{id}', [AuthController::class, 'update'])->name('updateProfile')->middleware(CheckAuth::class);
Route::delete('/delete/{id}', [AuthController::class, 'deleteProfile'])->name('deleteProfile')->middleware(CheckAuth::class);

Route::get('/editPassword', [AuthController::class, 'changePassword'])->name('editPassword')->middleware(CheckAuth::class);
Route::post('/updatePassword/{id}', [AuthController::class, 'updatePassword'])->name('updatePassword')->middleware(CheckAuth::class);
