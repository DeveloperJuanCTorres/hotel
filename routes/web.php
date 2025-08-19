<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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



Auth::routes();



Route::middleware(['auth'])->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/rooms', [HomeController::class, 'room'])->name('rooms');
    Route::get('/rooms/list', [HomeController::class, 'list'])->name('rooms.list');
});

// Route::group(['prefix' => 'admin'], function () {
//     Voyager::routes();
// });

Route::group(['prefix' => 'admin', 'middleware' => ['voyager.sanitize']], function () {
    Voyager::routes();
});
