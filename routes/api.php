<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CampoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/****************
 Tabla Usuario
****************/
Route::get('/usuarios',[UsuarioController::class,'usuarios']);
Route::get('/verify',[UsuarioController::class,'checkUsuario']);

Route::post('añadirUsuario',[UsuarioController::class,'añadirUsuario']);

Route::delete('borrarUsuario',[UsuarioController::class,'borrarUsuario']);

/*****************
 Tabla Trabajador
****************/
Route::get('/trabajadores',[TrabajadorController::class,'trabajadores']);

Route::post('/añadirTrabajador',[TrabajadorController::class,'añadirTrabajador']);

Route::delete('/borrarTrabajador',[TrabajadorController::class,'borrarTrabajador']);


/*****************
 Tabla Maquinaria
****************/
Route::get('/maquinas',[MaquinariaController::class,'maquinas']);

Route::post('/añadirMaquina',[MaquinariaController::class,'añadirMaquina']);

Route::put('/editarMaquina',[MaquinariaController::class,'editarMaquina']);

Route::delete('/borrarMaquina',[MaquinariaController::class,'borrarMaquina']);

/*****************
 Tabla Campo
****************/
Route::get('/campos',[CampoController::class, 'campos']);

Route::post('/añadirCampo',[CampoController::class,'añadirCampo']);

Route::delete('/borrarCampo',[CampoController::class,'borrarCampo']);

/*****************
 Actividad Campo
****************/
Route::get('/actividades',[ActividadController::class,'actividades']);

Route::post('/añadirActividad', [ActividadController::class, 'añadirActividad']);

Route::delete('/borrarActividad',[ActividadController::class,'borrarActividad']);