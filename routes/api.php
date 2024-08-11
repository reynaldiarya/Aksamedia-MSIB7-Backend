<?php

use App\Http\Controllers\ApiController;
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

Route::post('/login', [ApiController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->get('/divisions', [ApiController::class, 'apiGetAllDataDivision']);
Route::middleware('auth:sanctum')->get('/employees', [ApiController::class, 'apiGetAllDataKaryawan']);
Route::middleware('auth:sanctum')->post('/employees', [ApiController::class, 'apiCreateDataKaryawan']);
Route::middleware('auth:sanctum')->put('/employees/{uuid}', [ApiController::class, 'apiUpdateDataKaryawan']);
Route::middleware('auth:sanctum')->delete('/employees/{uuid}', [ApiController::class, 'apiDeleteDataKaryawan']);
Route::middleware('auth:sanctum')->post('/logout', [ApiController::class, 'apiLogout']);