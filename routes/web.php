<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');
    Route::get('/products/form-data', [ProductController::class, 'getFormData'])->name('products.form.data');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/delet', [ProductController::class, 'destroy'])->name('products.delet');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/list', [CategoryController::class, 'list'])->name('categories.list');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/delet', [CategoryController::class, 'destroy'])->name('categories.delet');
    

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
