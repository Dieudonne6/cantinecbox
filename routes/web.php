<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ConnexionDBController;
use App\Http\Controllers\EleveController;

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

Route::get('/classes', [ClassesController::class, 'classe']);
Route::get('/connexiondonnees', [PagesController::class, 'connexiondonnees']);
Route::get('/', [PagesController::class, 'connexion']);

Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'filterEleve']);
// Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'getElevesByClasse']);

// Route::get('/eleve/{CODECLAS}', 'EleveController@getElevesByClasse');
Route::post('/traiter', [ClassesController::class, 'traiter']);

Route::post('/creercontrat', [ClassesController::class, 'creercontrat']);


Route::get('/frais', [PagesController::class, 'frais']);
Route::post('/nouveaufrais', [PagesController::class, 'fraisnouveau']);
Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);

Route::get('/paiementcontrat/{CODECLAS}/{MATRICULE}', [ClassesController::class, 'paiementcontrat']);
Route::post('/savepaiementcontrat', [ClassesController::class, 'savepaiementcontrat']);
Route::get('/telechargerfacture', [ClassesController::class, 'telechargerfacture']);


Route::get('/pdffacture',[ClassesController::class,'pdffacture'])->name('pdffacture');
Route::get('/facturenormalise/{nomcompleteleve}',[ClassesController::class,'facturenormalise'])->name('pdffacture');
Route::get('/create',[ClassesController::class,'create'])->name('qrcode.create');

Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);

Route::get('/statistique', [PagesController::class, 'statistique']);
Route::get('/recouvrementsM', [PagesController::class, 'recouvrementsM']);
Route::get('/hsuppression', [PagesController::class, 'hsuppression']);
Route::get('/changetrimestre', [PagesController::class, 'changetrimestre']);
Route::get('/confimpression', [PagesController::class, 'confimpression']);

Route::get('/Acceuil', [PagesController::class, 'Acceuil']);

Route::put('/modifierfrais/{id_paramcontrat}', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);
Route::post('/connexion', [ConnexionDBController::class, 'connexion']);
Route::post('/connexions', [PagesController::class, 'connexions']);
Route::post('/logins', [PagesController::class, 'logins']);

Route::get('/inscription', [EleveController::class, 'inscription']);
