<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\AuthController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
//});

Route::post('/solicitudes', [QuejaController::class, 'store']);
Route::get('/solicitudes/{folio}', [QuejaController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->group(function () {
  Route::get('/solicitudes', [QuejaController::class, 'index']);
  Route::put('/solicitudes/{folio}/reasignar', [QuejaController::class, 'reasignarDepartamento']);
  Route::put('/solicitudes/{folio}/estado', [QuejaController::class, 'cambiarEstado']);
  Route::get('/estadisticas/departamentos', [QuejaController::class, 'estadisticasPorDepartamento']);
});
