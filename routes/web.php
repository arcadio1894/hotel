<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\ReservationController;

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomPriceController;
use App\Http\Controllers\RoomController;

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
            Route::get('/get/data/{numberPage}', [RoomTypeController::class, 'getDataRoomType']);
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
            Route::get('/get/data/{numberPage}', [SeasonController::class, 'getDataSeason']);
            //Route::get('/get/typeahead', [SeasonController::class, 'getSeasonTypeahead']);
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

            Route::get('/get/data/{numberPage}', [EmployerController::class, 'getDataOperations']);
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

        //  MANTENEDOR: RESERVAS
        Route::prefix('/home/reservas')->group(function (){
            Route::name('reservations.')->group(function () {
                Route::get('/listar', [ReservationController::class, 'index'])->name('index');
                Route::get('/get/data/{numberPage}', [ReservationController::class, 'getDataReservation']);

                Route::get('/lista', [ReservationController::class, 'indexReservations'])->name('indexReservations');
                Route::get('/get/get/data/{numberPage}', [ReservationController::class, 'getDataReservations']);
                Route::post('/crear', [ReservationController::class, 'storeReservations'])->name('storeReservations');
                Route::get('/crear/nueva/reserva', [ReservationController::class, 'create'])->name('create');
                Route::get('/get/rooms/{numberPage}', [ReservationController::class, 'getDataRooms']);
                Route::get('/lista/{reservation_id}', [ReservationController::class, 'listAssignRooms'])->name('listAssignRooms');
                Route::get('/buscar-cliente',[ReservationController::class, 'buscarCliente']);
                Route::post('/generar/costo',[ReservationController::class, 'generarCosto']);
            });
        });


    //RUTAS DE PERMISOS
    Route::prefix('/home/permissions')->group(function(){
        Route::name('permissions.')->group(function(){
            Route::get('/listar',[PermissionController::class,'index'])->name('index');
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::post('/edit/{permission}', [PermissionController::class,'update'])->name('update');
            Route::delete('/delete/{permission}', [PermissionController::class,'destroy'])->name('destroy');
            Route::get('/all', [PermissionController::class,'getPermissions']);

            Route::get('/get/data/{numberPage}', [PermissionController::class, 'getDataOperations']);
        });
    });

    //RUTAS DE ROLES
    Route::prefix('/home/roles')->group(function (){
        Route::name('roles.')->group(function () {
            Route::get('/listar', [RoleController::class, 'index'])->name('index');
            Route::post('', [RoleController::class, 'store'])->name('store');
            Route::post('/edit/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/delete/{role}', [RoleController::class, 'destroy'])->name('destroy');
            Route::get('/permisos/{role}', [RoleController::class,'editPermissions'])->name('editPermissions');
            Route::post('/permisos/{role}', [RoleController::class,'savePermissions'])->name('savePermissions');

            Route::get('/get/data/{numberPage}', [RoleController::class, 'getDataOperations']);
        });
    });

    Route::prefix('/home/room/prices')->group(function (){
        Route::name('roomPrices.')->group(function () {
            Route::get('listar', [RoomPriceController::class, 'index'])->name('index');
            Route::get('/get/data/{numberPage}', [RoomPriceController::class, 'getDataRoomPrice']);
            Route::get('listar/eliminados', [RoomPriceController::class, 'showDeletes'])->name('showDeletes');
            Route::post('', [RoomPriceController::class, 'store'])->name('store');
            Route::post('/edit/{room}', [RoomPriceController::class, 'update'])->name('update');
            Route::delete('/delete/{room}', [RoomPriceController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('/home/rooms')->group(function (){
        Route::name('rooms.')->group(function () {
            Route::get('listar', [RoomController::class, 'index'])->name('index');
            Route::get('/get/data/{numberPage}', [RoomController::class, 'getDataRoom']);
            Route::get('listar/eliminados', [RoomController::class, 'showDeletes'])->name('showDeletes');
            Route::post('', [RoomController::class, 'store'])->name('store');
            Route::post('/edit/{room}', [RoomController::class, 'update'])->name('update');
            Route::delete('/delete/{room}', [RoomController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{room}', [RoomController::class, 'restore'])->name('restore');
        });
    });
});
