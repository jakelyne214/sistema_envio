<?php

use App\Http\Controllers\ConnectContreoller;
use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EnviosController;

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
Route::get('/',[InicioController::class, 'getInicio']);
Route::get('/consulta',[InicioController::class, 'getConsulta']);
Route::get('/login',[ConnectContreoller::class, 'getLogin']);
Route::post('/login',[ConnectContreoller::class, 'postLogin'])->name('login');

Route::get('/register',[ConnectContreoller::class, 'getRegister']);
Route::post('/register',[ConnectContreoller::class, 'postRegister']);



Route::get('/logout',[ConnectContreoller::class, 'getLogout']);

Route::get('/escanear/{codigo_qr}', [EnviosController::class, 'escanearQr'])->name('envio.escanear');
Route::patch('/envios/{envio}/actualizar-estado', [EnviosController::class, 'getStadoRecepcion'])->name('envio.actualizarEstado');