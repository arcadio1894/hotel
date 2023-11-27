<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployerController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('/home/room/types')->group(function (){
        Route::name('roomTypes.')->group(function () {
            Route::get('listar', [RoomTypeController::class, 'index'])->name('index');
            Route::get('listar/eliminados', [RoomTypeController::class, 'showDeletes'])->name('showDeletes');
            Route::post('', [RoomTypeController::class, 'store'])->name('store');
            Route::post('/edit/{roomType}', [RoomTypeController::class, 'update'])->name('update');
            Route::delete('/delete/{roomType}', [RoomTypeController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{roomType}', [RoomTypeController::class, 'restore'])->name('restore');
        });
    });
    Route::prefix('/home/seasons')->group(function (){
        Route::name('seasons.')->group(function () {
            Route::get('listar', [SeasonController::class, 'index'])->name('index');
            Route::get('listar/eliminados', [SeasonController::class, 'showDeletes'])->name('showDeletes');
            Route::post('', [SeasonController::class, 'store'])->name('store');
            Route::post('/edit/{season}', [SeasonController::class, 'update'])->name('update');
            Route::delete('/delete/{season}', [SeasonController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{season}', [SeasonController::class, 'restore'])->name('restore');
        });
    });
    // MANTENEDOR: EMPLEADOS
    Route::prefix('/home/employers')->group(function (){
        Route::name('employers.')->group(function () {
            Route::get('listar', [EmployerController::class, 'index'])->name('index');
            Route::get('eliminados', [EmployerController::class, 'index_eliminated'])->name('index_eliminated');
            Route::post('', [EmployerController::class, 'store'])->name('store');
            Route::post('/edit/{employer}', [EmployerController::class, 'update'])->name('update');
            Route::delete('/delete/{employer}', [EmployerController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{employer}', [EmployerController::class, 'restore'])->name('restore');
        });
    });

    //  MANTENEDOR: CLIENTES
    Route::prefix('/home/clientes')->group(function (){
        Route::name('customers.')->group(function () {
            Route::get('/listar', [CustomerController::class, 'index'])->name('index');
            Route::get('/listar/eliminados', [CustomerController::class, 'showDeletes'])->name('showDeletes');
            Route::post('/crear', [CustomerController::class, 'store'])->name('store');
            Route::post('/editar/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/borrar/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
            Route::post('/restaurar/{customer}', [CustomerController::class, 'restore'])->name('restore');
            Route::get('/reporte', [CustomerController::class, 'report'])->name('report');
            Route::get('/reporte/descargar',[CustomerController::class,'generateReport'])->name('reportExcel');

            Route::get('/get/data/{numberPage}', [CustomerController::class, 'getDataOperations']);
        });
    });


});
