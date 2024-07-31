<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ConnexionDBController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\GestionclasseController;

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



Route::get('/inscriptioncantine', [PagesController::class, 'inscriptioncantine']);
Route::get('/get-eleves/{codeClass}', [PagesController::class, 'getEleves']);
Route::get('/get-montant/{codeClass}', [PagesController::class, 'getMontant']);

Route::get('/nouveaucontrat', [PagesController::class, 'nouveaucontrat']);
Route::get('/paiement', [PagesController::class, 'paiement']);
Route::get('/listedeseleves', [PagesController::class, 'listedeseleves']);

Route::get('/classes', [ClassesController::class, 'classe']);
Route::get('/connexiondonnees', [PagesController::class, 'connexiondonnees']);
Route::get('/', [PagesController::class, 'connexion']);

Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'filterEleve']);
// Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'getElevesByClasse']);

// Route::get('/eleve/{CODECLAS}', 'EleveController@getElevesByClasse');
Route::post('/traiter', [ClassesController::class, 'traiter']);

Route::post('/creercontrat', [ClassesController::class, 'creercontrat']);

Route::get('/lettrederelance', [EtatController::class, 'lettrederelance']);
Route::post('/essairelance', [EtatController::class, 'essairelance']);

Route::get('/frais', [PagesController::class, 'frais']);
Route::post('/nouveaufrais', [PagesController::class, 'fraisnouveau']);
Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);

Route::get('/paiementcontrat/{CODECLAS}/{MATRICULE}', [ClassesController::class, 'paiementcontrat']);
Route::post('/savepaiementcontrat', [ClassesController::class, 'savepaiementcontrat']);
Route::get('/telechargerfacture', [ClassesController::class, 'telechargerfacture']);


Route::get('/pdffacture',[ClassesController::class,'pdffacture'])->name('pdffacture');
Route::get('/facturenormalise/{nomcompleteleve}',[ClassesController::class,'facturenormalise'])->name('pdffactures');
Route::get('/create',[ClassesController::class,'create'])->name('qrcode.create');

Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);

Route::get('/statistique', [PagesController::class, 'statistique']);
Route::get('/recouvrementsM', [PagesController::class, 'recouvrementsM']);
Route::get('/hsuppression', [PagesController::class, 'hsuppression']);
Route::get('/changetrimestre', [PagesController::class, 'changetrimestre']);
Route::get('/confimpression', [PagesController::class, 'confimpression']);

Route::get('/Acceuil', [PagesController::class, 'Acceuil']);
Route::get('/inscrireeleve', [PagesController::class, 'inscrireeleve']);
Route::get('/modifiereleve', [PagesController::class, 'modifiereleve']);
Route::get('/profil', [PagesController::class, 'profil']);

Route::get('/typesclasses', [PagesController::class, 'typesclasses']);

Route::get('/series', [PagesController::class, 'series']);

Route::get('/promotions', [PagesController::class, 'promotions']);

Route::get('/creerprofil', [PagesController::class, 'creerprofil']);

Route::get('/paramcomposantes', [PagesController::class, 'paramcomposantes']);

Route::get('/duplicatarecu', [PagesController::class, 'duplicatarecu']);

Route::get('/transfert', [PagesController::class, 'transfert']);

Route::get('/importer', [PagesController::class, 'importer']);

Route::get('/verrouillage', [PagesController::class, 'verrouillage']);

Route::get('/recaculereffectifs', [PagesController::class, 'recaculereffectifs']);

Route::put('/modifierfrais/{id_paramcontrat}', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);
Route::post('/connexion', [ConnexionDBController::class, 'connexion']);
Route::post('/connexions', [PagesController::class, 'connexions']);
Route::post('/logins', [PagesController::class, 'logins']);

Route::get('/inscription', [EleveController::class, 'inscription']);
Route::get('/etatpaiement', [ClassesController::class, 'etatpaiement'])->name('etatpaiement');
Route::post('/traitementetatpaiement', [ClassesController::class, 'traitementetatpaiement'])->name('traitementetatpaiement');
Route::delete('/supprimercontrat/{MATRICULE}', [ClassesController::class, 'supprimercontrat']);
Route::delete('/supprimerpaiement/{id_paiementcontrat}', [ClassesController::class, 'supprimerpaiement']);
Route::get('/etatpaiement1', [ClassesController::class, 'etatpaiement1']);
Route::get('/essaipdf', [ClassesController::class, 'essaipdf']);

Route::get('/vitrine', [PagesController::class, 'vitrine']);

Route::get('/modifparam', [PagesController::class, 'modifparam']);

Route::get('/relance', [PagesController::class, 'relance']);

Route::get('http://localhost:38917/info');


Route::get('/etatdroits', [EtatController::class, 'etatdroits']);
Route::post('/filteretat', [EtatController::class, 'filteretat']);
Route::post('/relance', [EtatController::class, 'relance']);

Route::get('/paramsfacture', [PagesController::class, 'paramsfacture']);
Route::post('/paramsemecef', [PagesController::class, 'paramsemecef']);
Route::get('/imprimerfiche/{id_paiementcontrat}', [ClassesController::class, 'imprimerfiche']);
Route::get('/inscriptions', [PagesController::class, 'inscriptions']);
Route::post('/enregistreruser', [PagesController::class, 'enregistreruser']);
Route::get('/listedesretardsdepaiement', [PagesController::class, 'listedesretardsdepaiement']);

Route::get('/parametre', [PagesController::class, 'parametre']);
Route::get('/echeancier', [PagesController::class, 'echeancier']);
Route::get('/tabledesclasses', [PagesController::class, 'tabledesclasses']);
Route::get('/enrclasse', [PagesController::class, 'enrclasse']);
Route::get('/certificat', [PagesController::class, 'certificatsolarite']);
Route::get('/droitconstate', [PagesController::class, 'droitconstate']);

Route::post('logout', [PagesController::class, 'logout'])->name('logout');

Route::get('/duplicatafacture', [PagesController::class, 'duplicatafacture']);

Route::get('/paiementeleve', [PagesController::class, 'paiementeleve']);

Route::get('/groupe', [PagesController::class, 'groupe']);
Route::get('/duplicatainscription/{elevyo}',[ClassesController::class,'duplicatainscription']);
Route::get('/majpaiementeleve', [PagesController::class, 'majpaiementeleve']);
Route::get('/photos', [PagesController::class, 'photos']);

Route::get('/paiementdesnoninscrits', [PagesController::class, 'paiementdesnoninscrits']);

Route::get('/facturesclasses', [PagesController::class, 'facturesclasses']);
Route::get('/reductioncollective', [PagesController::class, 'reductioncollective']);
Route::get('/discipline', [PagesController::class, 'discipline']);
Route::get('/archive', [PagesController::class, 'archive']);
Route::get('/editions', [PagesController::class, 'editions']);
Route::get('/eleveparclasse', [PagesController::class, 'eleveparclasse']);

Route::get('/gestionarriere', [PagesController::class, 'gestionarriere']);
Route::get('/exporter', [PagesController::class, 'exporter']);
Route::get('/listeselective', [PagesController::class, 'listeselective']);
Route::get('/pointderecouvrement', [PagesController::class, 'pointderecouvrement']);
Route::get('/etatdesrecouvrements', [PagesController::class, 'etatdesrecouvrements']);

Route::get('/enquetesstatistiques', [PagesController::class, 'enquetesstatistiques']);
Route::get('/etatdelacaisse', [PagesController::class, 'etatdelacaisse']);
Route::get('/situationfinanciereglobale',[PagesController::class, 'situationfinanciereglobale']);
Route::get('/certificatsolarite', [PagesController::class, 'certificatsolarite']);



// Controller GestionclasseController
Route::post('/savetypeclasse', [GestionclasseController::class, 'savetypeclasse']);

Route::get('/groupes', [GestionclasseController::class, 'groupes']);

Route::get('/typesclasses', [GestionclasseController::class, 'getclasse']);
Route::put('/modifiertypesclasses', [GestionclasseController::class, 'updateTypeClasse']);
Route::delete('/supprimertype', [GestionclasseController::class, 'deletetype']);
