<?php

use App\Http\Controllers\BoxeOpeningController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
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

    Route::get('/clients', [ContactController::class, 'index'])->name('clients');
    Route::get('/clients/list', [ContactController::class, 'list'])->name('clients.list');
    Route::post('/clients/store', [ContactController::class, 'store'])->name('clients.store');
    Route::post('/clients/edit', [ContactController::class, 'edit'])->name('clients.edit');
    Route::post('/clients/update', [ContactController::class, 'update'])->name('clients.update');
    Route::post('/clients/delet', [ContactController::class, 'destroy'])->name('clients.delet');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
    Route::get('/suppliers/list', [SupplierController::class, 'list'])->name('suppliers.list');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::post('/suppliers/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::post('/suppliers/update', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::post('/suppliers/delet', [SupplierController::class, 'destroy'])->name('suppliers.delet');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/reservations/list', [ReservationController::class, 'list'])->name('reservations.list');
    Route::post('/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::post('/reservations/update', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/delet', [ReservationController::class, 'destroy'])->name('reservations.delet');
    Route::post('/reservations/disponibles', [ReservationController::class, 'getDisponibles']);

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::get('/expenses/list', [ExpenseController::class, 'list'])->name('expenses.list');
     Route::get('/expenses/form-data', [ExpenseController::class, 'getFormData'])->name('expenses.form.data');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('/expenses/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::post('/expenses/update', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::post('/expenses/delet', [ExpenseController::class, 'destroy'])->name('expenses.delet');

    Route::get('/expenses/cat', [ExpenseCategoryController::class, 'index'])->name('expensescat');
    Route::get('/expenses/cat/list', [ExpenseCategoryController::class, 'list'])->name('expensescat.list');
    Route::post('/expenses/cat/store', [ExpenseCategoryController::class, 'store'])->name('expensescat.store');
    Route::post('/expenses/cat/edit', [ExpenseCategoryController::class, 'edit'])->name('expensescat.edit');
    Route::post('/expenses/cat/update', [ExpenseCategoryController::class, 'update'])->name('expensescat.update');
    Route::post('/expenses/cat/delet', [ExpenseCategoryController::class, 'destroy'])->name('expensescat.delet');

    Route::get('/pos', [PosController::class, 'index'])->name('pos');

    Route::get('/opening', [BoxeOpeningController::class, 'index'])->name('openings');
    Route::get('/opening/list', [BoxeOpeningController::class, 'list'])->name('openings.list');
    Route::post('/opening/view', [BoxeOpeningController::class, 'view'])->name('openings.view');
    Route::post('/opening/store', [BoxeOpeningController::class, 'store'])->name('openings.store');
    Route::get('/opening/form-data', [BoxeOpeningController::class, 'getFormData'])->name('opening.form.data');

    Route::get('/caja/{id}/preview-cierre', [BoxeOpeningController::class, 'previewCierre']);
    Route::post('/caja/{id}/cerrar', [BoxeOpeningController::class, 'cerrarCaja']);
    Route::get('/caja/closure/{id}/print', [BoxeOpeningController::class, 'print'])->name('caja.print');

    Route::post('/sale/store', [SalesController::class, 'store'])->name('sales.store');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases');
    Route::get('/purchases/list', [PurchaseController::class, 'list'])->name('purchases.list');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases/store', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('/purchases/delet', [PurchaseController::class, 'destroy'])->name('purchases.delet');
    Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('/purchases/{id}/update', [PurchaseController::class, 'update'])->name('purchases.update');


    Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');

    Route::post('/comprobantes/generar', [MovementController::class, 'generar']);
    

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
