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
    Route::post('/rooms/updateStatus', [HomeController::class, 'updateStatus'])->name('rooms.updateStatus');
    Route::post('/rooms/register', [HomeController::class, 'roomRegister'])->name('rooms.register');
    Route::post('/buscar-documento', [HomeController::class, 'buscarDocumento'])->name('buscar.documento');

    // Para buscar la transacción activa de la habitación
    Route::get('/habitacion/{id}/buscar-transaccion', [HomeController::class, 'buscarTransaccion'])
        ->name('habitacion.buscarTransaccion');

    // Para ver el detalle en una vista blade
    Route::get('/transaccion/{id}/detalle', [HomeController::class, 'detalle'])
        ->name('transaccion.detalle');
});

// Route::group(['prefix' => 'admin'], function () {
//     Voyager::routes();
// });

Route::group(['prefix' => 'admin', 'middleware' => ['voyager.sanitize']], function () {
    Voyager::routes();
});
