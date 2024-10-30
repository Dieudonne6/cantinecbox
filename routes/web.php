<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CdController;

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ConnexionDBController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\GestionclasseController;
use App\Http\Controllers\ScolariteController;
use App\Http\Controllers\Faute;
use App\Http\Controllers\Tfautes;
use App\Http\Controllers\Absence;
use App\Http\Controllers\Matieres;
use App\Http\Controllers\GestionNotesController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\EditionController2;

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
Route::get('/get-promo/{ensigClass}', [PagesController::class, 'getPromo']);
Route::get('/get-serie/{serieClass}', [PagesController::class, 'getSerie']);

Route::get('/nouveaucontrat', [PagesController::class, 'nouveaucontrat']);
Route::get('/paiement', [PagesController::class, 'paiement']);
Route::get('/listedeseleves', [PagesController::class, 'listedeseleves'])->name('listedeseleves');
Route::get('/listeselectiveeleve', [PagesController::class, 'listeselectiveeleve'])->name('listeselectiveeleve');

// Route::get('/editions', [EditionController::class, 'listeecheancierperso'])->name('listeecheancierperso');

Route::get('/classes', [ClassesController::class, 'classe']);
Route::get('/connexiondonnees', [PagesController::class, 'connexiondonnees']);
Route::get('/', [PagesController::class, 'connexion']);
Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'filterEleve']);
Route::get('/listegeneraleeleve/{CODECLAS}', [ClassesController::class, 'listegeneraleeleve']);
// Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'getElevesByClasse']);

Route::get('/filterlisteselectiveeleve', [ClassesController::class, 'filterlisteselectiveeleve']);

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

Route::get('/Acceuil', [PagesController::class, 'Acceuil'])->name('lolo');
Route::get('/modifiereleve/{MATRICULE}', [PagesController::class, 'modifiereleve'])->name('modifiereleve');
Route::put('/modifieeleve/{MATRICULE}', [PagesController::class, 'modifieeleve']);
Route::put('/modifieleve/{MATRICULE}', [PagesController::class, 'modifieleve']);
Route::put('/modifieprofil/{MATRICULE}', [PagesController::class, 'modifieprofil']);
Route::put('/modifieecheancier/{MATRICULE}', [PagesController::class, 'modifieecheancier']);


Route::get('/profil/{MATRICULE}', [PagesController::class, 'profil'])->name('profil');

Route::get('/typesclasses', [PagesController::class, 'typesclasses']);

Route::get('/promotions', [PagesController::class, 'promotions']);

Route::get('/creerprofil', [PagesController::class, 'creerprofil']);
Route::post('/ajouterprofreduction', [PagesController::class, 'ajouterprofreduction']);
Route::put('/modifreductions', [PagesController::class, 'modifreductions']);
Route::delete('/delreductions/{codeRedu}', [PagesController::class, 'delreductions']);

Route::get('/paramcomposantes', [PagesController::class, 'paramcomposantes']);

Route::get('/duplicatarecu', [PagesController::class, 'duplicatarecu']);

Route::get('/transfert', [PagesController::class, 'transfert']);

Route::get('/importer', [PagesController::class, 'importer']);

Route::get('/verrouillage', [PagesController::class, 'verrouillage']);

Route::get('/recalculereffectifs', [PagesController::class, 'recalculereffectifs']);

Route::put('/modifierfrais/{id_paramcontrat}', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);
Route::post('/connexion', [ConnexionDBController::class, 'connexion']);
Route::post('/connexions', [PagesController::class, 'connexions']);
Route::post('/logins', [PagesController::class, 'logins']);

Route::get('/inscription', [EleveController::class, 'inscription']);
Route::get('/etatpaiement', [ClassesController::class, 'etatpaiement'])->name('etatpaiement');
Route::post('/traitementetatpaiement', [ClassesController::class, 'traitementetatpaiement'])->name('traitementetatpaiement');
Route::delete('/supprimercontrat', [ClassesController::class, 'supprimercontrat']);
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
Route::get('/echeancier/{MATRICULE}', [PagesController::class, 'echeancier'])->name('echeancier');
Route::get('/tabledesclasses', [PagesController::class, 'tabledesclasses']);
Route::get('/enrclasse', [GestionclasseController::class, 'enrclasse'])->name('enrclasse');
Route::get('/certificatsolarite/{CODECLAS?}/{matricule?}', [PagesController::class, 'certificatscolarite'])->name('certificatsolarite');

Route::post('certificatsolarite/impression', [PagesController::class, 'impression'])->name('certificatsolarite.impression');


Route::get('/droitconstate', [PagesController::class, 'droitconstate']);

Route::post('logout', [PagesController::class, 'logout'])->name('logout');

Route::get('/duplicatafacture', [PagesController::class, 'duplicatafacture']);

Route::get('/paiementeleve/{matricule}', [PagesController::class, 'paiementeleve'])->name('paiementeleve');
Route::post('/paiement/{matricule}', [PagesController::class, 'enregistrerPaiement'])->name('enregistrer.paiement');
// Route::get('/recouvrementGenerale', [PagesController::class, 'recouvrementGenerale'])->name('recouvrementGenerale');



Route::get('/duplicatainscription/{elevyo}',[ClassesController::class,'duplicatainscription']);
Route::get('/majpaiementeleve', [PagesController::class, 'majpaiementeleve'])->name('majpaiementeleve');
Route::get('/photos', [PagesController::class, 'photos']);

Route::get('/paiementdesnoninscrits', [PagesController::class, 'paiementdesnoninscrits']);

// Route::get('/facturesclasses', [PagesController::class, 'facturesclasses']);
Route::get('/reductioncollective', [PagesController::class, 'reductioncollective']);

Route::get('/discipline', [GestionclasseController::class, 'discipline'])->name('discipline');
Route::post('/faute', [GestionclasseController::class, 'Tstore'])->name('faute.store');
Route::put('/faute/{id}', [GestionclasseController::class, 'Tupdate'])->name('faute.update');
Route::delete('/faute/{id}', [GestionclasseController::class, 'Tdestroy'])->name('faute.destroy');

Route::post('/fautes', [GestionclasseController::class, 'fautestore'])->name('fautes.store');
Route::get('/eleve/{MATRICULE}/fautes', [GestionclasseController::class, 'showFaults'])->name('eleve.faults');
// Routes pour les fautes
Route::put('/fautess/{id}', [GestionclasseController::class, 'fauteupdate'])->name('fautes.update');
Route::delete('/fautes/{id}', [GestionclasseController::class, 'fautedestroy'])->name('fautes.dest');
Route::get('/fautes/imprimer', [GestionclasseController::class, 'imprimerfautes'])->name('pages.etat.imprimerfaute');
Route::get('/absences/imprimer', [GestionclasseController::class, 'imprimerabsences'])->name('pages.etat.imprimerabsence');
// web.php
Route::get('/pages/{matricule}/impression-fautes', [GestionclasseController::class, 'imprimereleveFautes'])->name('pages.etat.imprimer_fautes');
Route::get('/pages/{matricule}/impression-absences', [GestionclasseController::class, 'imprimereleveAbsence'])->name('pages.etat.imprimer_absences');


Route::get('/archive', [PagesController::class, 'archive']);
Route::get('/pagedetailarchive/{MATRICULE}', [PagesController::class, 'pagedetailarchive'])->name('pagedetailarchive');

Route::get('/editions', [EditionController::class, 'editions'])->name('editions');
Route::get('/editions2', [EditionController2::class, 'editions2'])->name('editions2');
Route::get('/editions2/fichedenotesvierge', [EditionController2::class, 'fichedenotesvierge'])->name('pages.notes.fichedenotesvierge');
Route::get('/editions2/relevesparmatiere', [EditionController2::class, 'relevesparmatiere'])->name('pages.notes.relevesparmatiere');
Route::get('/editions2/recapitulatifdenotes', [EditionController2::class, 'recapitulatifdenotes'])->name('pages.notes.recapitulatifdenotes');
Route::get('/editions2/relevespareleves', [EditionController2::class, 'relevespareleves'])->name('pages.notes.relevespareleves');
Route::get('/editions2/resultatsparpromotion', [EditionController2::class, 'resultatsparpromotion'])->name('pages.notes.resultatsparpromotion');
Route::get('/editions2/listedesmeritants', [EditionController2::class, 'listedesmeritants'])->name('pages.notes.listedesmeritants');

Route::get('/journalderecouvrement', [EditionController::class, 'journal'])->name('journal');

Route::post('/arriereconstate', [EditionController::class, 'arriereconstate'])->name('arriereconstate');

Route::get('/etatdesarrieresinscrits', [PagesController::class, 'etatdesarrieresinscrits']);
Route::get('/eleveparclasse', [PagesController::class, 'eleveparclasse'])->name('eleveparclasse');
Route::get('/eleveparclassespecifique/{classeCode}', [PagesController::class, 'eleveparclassespecifique']);

Route::get('/retardpaiementclasse', [PagesController::class, 'retardpaiementclasse'])->name('retardpaiementclasse');
Route::get('/rpaiementclassespecifique/{classeCode}', [PagesController::class, 'rpaiementclassespecifique']);

Route::get('/situationfinanceclasse', [PagesController::class, 'situationfinanceclasse'])->name('situationfinanceclasse');
Route::get('/sfinanceclassespecifique/{classeCode}', [PagesController::class, 'sfinanceclassespecifique']);
Route::get('/essai', [PagesController::class, 'eleveparclasseessai']);



Route::get('/registreeleves', [PagesController::class, 'registreeleves']);
Route::get('/registreelev', [PagesController::class, 'registreeleve']);
Route::post('/regenererecheance/{MATRICULE}', [PagesController::class, 'regenererecheance']);

Route::get('/gestionarriere', [PagesController::class, 'gestionarriere']);
Route::get('/exporter', [PagesController::class, 'exporter']);
Route::get('/listeselective', [PagesController::class, 'listeselective']);
Route::get('/pointderecouvrement', [PagesController::class, 'pointderecouvrement']);
Route::get('/etatdesrecouvrements', [PagesController::class, 'etatdesrecouvrements'])->name('etatdesrecouvrements');

Route::get('/enquetesstatistiques', [PagesController::class, 'enquetesstatistiques'])->name('enquetesstatistiques');
Route::get('/etatdelacaisse', [PagesController::class, 'etatdelacaisse'])->name('etatdelacaisse');
// Route::get('/etatdelacaisse/{chapitre?}', [PagesController::class, 'etatdelacaisse']);
Route::get('/etatdelacaisse/filter', [PagesController::class, 'filterEtatDeLaCaisse'])->name('etatdelacaisse.filter');




Route::get('/situationfinanciereglobale',[PagesController::class, 'situationfinanciereglobale'])->name('situationfinanciereglobale');
Route::get('/certificatsolarite', [PagesController::class, 'certificatsolarite'])->name('certificatsolarite');



// Controller GestionclasseController
Route::post('/savetypeclasse', [GestionclasseController::class, 'savetypeclasse'])->name('savetypeclasse');

Route::get('/groupes', [GestionclasseController::class, 'groupes']);
Route::get('/groupes/{libelle}/classes', [GestionclasseController::class, 'ClassesParGroupe']);
Route::get('/afficherTouteClasse', [GestionclasseController::class, 'afficherTouteClasse']);
Route::post('/groupes/{libelle}/classes', [GestionclasseController::class, 'ajouterClasse']);
Route::delete('/groupes/{libelle}/classes/{id}', [GestionclasseController::class, 'supprimerClasse']);
Route::delete('/supprimergroupe/{id}', [GestionclasseController::class, 'supprimergroupe']);
Route::get('/groupe', [GestionclasseController::class, 'groupe'])->name('groupe');
Route::post('/ajoutergroupe', [GestionclasseController::class, 'ajoutergroupe']);
Route::delete('/suppgroupe/{id}', [GestionclasseController::class, 'suppGroupe']);

// Promotion
Route::get('/promotions', [GestionclasseController::class, 'index'])->name('promotions.index');
Route::post('/promotions', [GestionclasseController::class, 'store'])->name('promotions.store');
Route::put('/promotions/{codePromo}', [GestionclasseController::class, 'update'])->name('promotions.update');

Route::delete('/promotions/{codePromo}', [GestionclasseController::class, 'destroy'])->name('promotions.destroy');

//Acceuil
Route::get('/Acceuil', [GestionclasseController::class, 'indexEleves'])->name('Acceuil');
Route::put('/eleves/{matricule}', [EleveController::class, 'destroy'])->name('eleves.destroy');

//Series
Route::get('/series', [GestionclasseController::class, 'series']);
Route::put('/modifierserie', [GestionclasseController::class, 'updateserie']);
Route::post('/saveserie', [GestionclasseController::class, 'saveserie'])->name('saveserie');
Route::delete('/supprimerserie', [GestionclasseController::class, 'deleteserie']);

Route::get('/typesclasses', [GestionclasseController::class, 'getclasse']);
Route::put('/modifiertypesclasses', [GestionclasseController::class, 'updateTypeClasse']);
Route::delete('/supprimertype', [GestionclasseController::class, 'deletetype']);
Route::get('/inscrireeleve', [PagesController::class, 'inscrireeleve'])->name('inscrireeleve');
Route::post('/nouveaueleve', [GestionclasseController::class, 'nouveaueleve'])->name('nouveaueleve');
Route::post('/enregistrerclasse', [GestionclasseController::class, 'enregistrerclasse'])->name('enregistrerclasse');
Route::put('/promotions/{codePromo}', [GestionclasseController::class, 'update'])->name('promotions.update');
// Route::get('/modifierclasse', [PagesController::class, 'modifierclasse']);

Route::post('/enregistrerinfo', [GestionclasseController::class, 'enregistrerinfo']);

Route::get('/tabledesclasses', [GestionclasseController::class, 'gettabledesclasses'])->name('tabledesclasses');
Route::get('/modifierclasse/{CODECLAS}', [GestionclasseController::class, 'modifierclasse'])->name('modifierclasse');
Route::delete('/supprimerclass', [GestionclasseController::class, 'deleteclass']);


Route::put('/modifieclasse/{CODECLAS}', [GestionclasseController::class, 'modifieclasse']);


Route::get('/generer-factures', [ClassesController::class, 'genererfacture']);
Route::get('/paramcomposantes', [ScolariteController::class, 'getparamcomposantes']);
// Route::put('/modifieclasse/{CODECLAS}', [GestionclasseController::class, 'modifieclasse']);

Route::get('/facturesclasses', [ScolariteController::class, 'getfacturesclasses'])->name('facturesclasses');
Route::get('/detailfacturesclasses/{CODECLAS}', [ScolariteController::class, 'detailfacturesclasses'])->name('detailfacturesclasses');
Route::post('/detailfacclasse/{CODECLAS}', [ScolariteController::class, 'detailfacclasse']);
Route::post('/detailfacnouvelleclasse', [GestionclasseController::class, 'detailfacnouvelleclasse']);
Route::get('/listedesclasses', [ClassesController::class, 'listeclasses'])->name('listedesclasses');
Route::post('/appliquereduc', [PagesController::class, 'applyReductions'])->name('apply.reductions');
Route::get('/generer-factures', [ClassesController::class, 'genererfacture']);
Route::get('/imprimer-profil-type-classe', [PagesController::class, 'imprimerProfilTypeClasse'])->name('impression.profil.type.classe');
Route::get('/listedesreductions', [PagesController::class, 'listedesreductions']);
Route::get('/pagedetail/{MATRICULE}', [GestionclasseController::class, 'pagedetail'])->name('pagedetail');
Route::get('/etatdesdroits', [PagesController::class, 'etatdesdroits'])->name('etatdesdroits');
Route::get('/etatdesarriérés', [PagesController::class, 'etatdesarriérés'])->name('etatdesarriérés');
Route::get('/recouvrementoperateur', [PagesController::class, 'recouvrementoperateur'])->name('recouvrementoperateur');
Route::get('/journaloperateur', [PagesController::class, 'journaloperateur'])->name('journaloperateur');
Route::get('/journaldetailleaveccomposante', [EditionController::class, 'journaldetailleaveccomposante'])->name('journaldetailleaveccomposante');

Route::get('/journaldetaillesanscomposante', [EditionController::class, 'journaldetaillesanscomposante'])->name('journaldetaillesanscomposante');
Route::get('/journalresumerecouvrement', [PagesController::class, 'journalresumerecouvrement'])->name('journalresumerecouvrement');
Route::get('/recouvrementgeneralenseignement', [PagesController::class, 'recouvrementParType'])->name('recouvrementgeneralenseignement');
Route::get('/recouvrementgeneral', [PagesController::class, 'recouvrementgeneral'])->name('recouvrementgeneral');
Route::get('/repartitionclassesparoperateur', [GestionNotesController::class, 'repartitionclassesparoperateur'])->name('repartitionclassesparoperateur');
Route::post('/repartitionclassesparoperateur', [GestionNotesController::class, 'repartitionclassesoperateur'])->name('repartitionclassesoperateur');



// gestion des notes
Route::get('/gestioncoefficient', [GestionNotesController::class, 'gestioncoefficient'])->name('gestioncoefficient');
Route::put('/enregistrerCoefficient', [GestionNotesController::class, 'enregistrerCoefficient'])->name('enregistrerCoefficient');
Route::get('/tabledesmatieres', [EditionController::class, 'tabledesmatieres'])->name('tabledesmatieres');

Route::post('/tabledesmatieres', [EditionController::class, 'storetabledesmatieres'])->name('storetabledesmatieres');

Route::get('/saisirnote', [CdController::class, 'saisirnote'])->name('saisirnote');
Route::get('/filternotes', [CdController::class, 'saisirnotefilter'])->name('saisirnotefilter');

Route::get('/verrouillage', [CdController::class, 'verrouillage'])->name('verrouillage');

Route::put('/tabledesmatieres',  [EditionController::class, 'updatetabledesmatieres'])->name('updatetabledesmatieres');
Route::get('/editions2/tableauanalytiqueparmatiere', [EditionController2::class, 'tableauanalytiqueparmatiere'])->name('tableauanalytiqueparmatiere');