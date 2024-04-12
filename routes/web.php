<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;

use App\Http\Controllers\ClassesController;

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


Route::get('/inscription', [PagesController::class, 'inscription']);

Route::get('/nouveaucontrat', [PagesController::class, 'nouveaucontrat']);
Route::get('/paiement', [PagesController::class, 'paiement']);

Route::get('/', [ClassesController::class, 'classe']);
Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'filterEleve']);

Route::get('/connexiondonnées', [PagesController::class, 'connexiondonnées']);

Route::get('/frais', [PagesController::class, 'frais']);