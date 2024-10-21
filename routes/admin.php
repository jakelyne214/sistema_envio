<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnviosController;
use App\Http\Controllers\Admin\SucursalesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehiculosController;
use App\Http\Controllers\Admin\AnuncioController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->group(function(){
    Route::get('/',[DashboardController::class, 'getDashboard'])->name('dashboard');

    //VEHICULOS 
    Route::get('/vehiculos',[VehiculosController::class, 'getVehiculos'])->name('vehiculos');
    Route::post('/vehiculos',[VehiculosController::class, 'postVehiculos'])->name('add_vehiculos');
    Route::get('/vehiculos/{id}/edit',[VehiculosController::class, 'getVehiculosEdit'])->name('edit_vehiculos');
    Route::post('/vehiculos/{id}/edit',[VehiculosController::class, 'postVehiculosEdit'])->name('edit_vehiculos');
    
    //SUCURSALES
    Route::get('/sucursales',[SucursalesController::class, 'getSucursales'])->name('sucursales');
    Route::post('/sucursales',[SucursalesController::class, 'postSucursales'])->name('add_sucursales');
    Route::get('/sucursales/{id}/edit',[SucursalesController::class, 'getSucursalesEdit'])->name('edit_sucursales');
    Route::post('/sucursales/{id}/edit',[SucursalesController::class, 'postSucursalesEdit'])->name('edit_sucursales');
    /*FILTRO*/Route::get('/sucursales/filtro_departamento',[SucursalesController::class, 'filterDepartamento'])->name('sucursales');

    //ENVIOS
    Route::get('/envios',[EnviosController::class, 'getEnvios'])->name('envios');
    Route::post('/envios',[EnviosController::class, 'postEnvios'])->name('add_envios');
    Route::post('/envio/{id}/estado_auto', [EnviosController::class, 'postEstadosEnvios'])->name('add_envios_estado');
    Route::get('/envio/{id}/pdf', [EnviosController::class, 'generatePDF'])->name('envios');

    Route::get('/envio/{id}/edit',[EnviosController::class, 'getEnviosEdit'])->name('edit_envios');
    Route::post('/envio/{id}/edit',[EnviosController::class, 'postEnviosEdit'])->name('edit_envios');

    /*FILTRO*/Route::get('/envios/filtro_departamento',[EnviosController::class, 'filterDepartamentoEnvios'])->name('envios');
    /*FILTRO*/Route::get('/envios/filtro_sucursal',[EnviosController::class, 'filterSucursalEnvios'])->name('envios');

    //RECEPCIO
    Route::get('/recepcion',[EnviosController::class, 'getRecepcion'])->name('recepcion');
    Route::get('/recepcion/{id}/entregado',[EnviosController::class, 'getStadoRecepcion'])->name('recepcion_estado')   ;

    //ANUNCIOS
    Route::get('/anuncios',[AnuncioController::class, 'getAnuncio'])->name('anuncios');
    Route::post('/anuncios',[AnuncioController::class, 'postAnuncio'])->name('add_anuncios');
    Route::get('/anuncios/{id}/edit',[AnuncioController::class, 'getAnuncioEdit'])->name('edit_anuncios');
    Route::post('/anuncios/{id}/edit',[AnuncioController::class, 'postAnuncioEdit'])->name('edit_anuncios');

    //USUARIOS
    Route::get('/user',[UserController::class, 'getUsers'])->name('user_list');
    Route::post('/user',[UserController::class, 'postUsers'])->name('user_new');
    Route::get('/user/{id}/edit',[UserController::class, 'getUsersEdit'])->name('user_edit');
    Route::post('/user/{id}/edit',[UserController::class, 'posttUsersEdit'])->name('user_edit');
    Route::get('/user/{id}/permission',[UserController::class,'getUsersPermission'])->name('user_permission');
    Route::post('/user/{id}/permission',[UserController::class,'postUsersPermission'])->name('user_permission');
    Route::get('/user/{id}/banned',[UserController::class,'getUsersBanned'])->name('user_banned');

    //AUDITORIA
    Route::get('/acciones',[DashboardController::class, 'getAuditoria'])->name('auditoria');
});  