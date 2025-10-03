<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CdController;

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ConnexionDBController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\Etat;
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
use App\Http\Controllers\ListemeriteController;
use App\Http\Controllers\ReleveparelevesController;
use App\Http\Controllers\TableauController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\RapportannuelController;
use App\Http\Controllers\DuplicataController;
use App\Http\Controllers\GestionPersonnelController;
use App\Http\Controllers\InscrirepersonnelController;
use App\Http\Controllers\BulletinPaieController;

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
Route::get('/get-clas/{codeClass}', [PagesController::class, 'getClas']);
// Route::get('/get-clasmat/{codeClass}', [PagesController::class, 'getClasmat']);

Route::get('/listecontrat', [ClassesController::class, 'listecontrat'])->name('listecontrat');

Route::get('/nouveaucontrat', [PagesController::class, 'nouveaucontrat']);
Route::get('/paiement', [PagesController::class, 'paiement']);
Route::get('/listedeseleves', [PagesController::class, 'listedeseleves'])->name('listedeseleves');
Route::get('/listeselectiveeleve', [PagesController::class, 'listeselectiveeleve'])->name('listeselectiveeleve');

// Route::get('/editions', [EditionController::class, 'listeecheancierperso'])->name('listeecheancierperso');

Route::get('/classes', [ClassesController::class, 'classe']);
Route::get('/connexiondonnees', [PagesController::class, 'connexiondonnees']);
Route::get('/', [PagesController::class, 'connexion']);
Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'filterEleve']);
Route::get('/filteraccueil/{CODECLAS}', [GestionclasseController::class, 'filteraccueil']);
Route::get('/listegeneraleeleve/{CODECLAS}', [ClassesController::class, 'listegeneraleeleve']);
//Route::get('/eleve/{CODECLAS}', [ClassesController::class, 'getElevesByClasse']);

Route::get('/filterlisteselectiveeleve', [ClassesController::class, 'filterlisteselectiveeleve'])->name('filterlisteselectiveeleve');

// Route::get('/eleve/{CODECLAS}', 'EleveController@getElevesByClasse');
Route::post('/traiter', [ClassesController::class, 'traiter']);

Route::post('/creercontrat', [ClassesController::class, 'creercontrat']);

Route::get('/lettrederelance', [EtatController::class, 'lettrederelance']);
Route::post('/essairelance', [EtatController::class, 'essairelance']);

Route::get('/frais', [PagesController::class, 'frais']);
Route::post('/nouveaufrais', [PagesController::class, 'fraisnouveau']);
Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);


// ---------------------------------------------------------------------
// avoirfacscolarit

Route::get('/listeavoirfacscolarit', [PagesController::class, 'listeavoirfacscolarit']);

// ---------------------------------------------------------------------

Route::get('/paiementcontrat/{CODECLAS}/{MATRICULE}', [ClassesController::class, 'paiementcontrat']);
Route::post('/savepaiementcontrat', [ClassesController::class, 'savepaiementcontrat']);
Route::post('/savepaiementetinscriptioncontrat', [ClassesController::class, 'savepaiementetinscriptioncontrat']);
Route::get('/telechargerfacture', [ClassesController::class, 'telechargerfacture']);


Route::get('/pdffacture', action: [ClassesController::class, 'pdffacture'])->name('pdffacture');
Route::get('/facturenormalise/{nomcompleteleve}', [ClassesController::class, 'facturenormalise'])->name('pdffactures');
Route::get('/create', [ClassesController::class, 'create'])->name('qrcode.create');

Route::post('/modifierfrais', [PagesController::class, 'modifierfrais']);
Route::get('/dashbord', [PagesController::class, 'dashbord']);

Route::get('/statistique', [PagesController::class, 'statistique']);
Route::get('/recouvrementsM', [PagesController::class, 'recouvrementsM']);
Route::get('/hsuppression', [PagesController::class, 'hsuppression']);
Route::get('/changetrimestre', [PagesController::class, 'changetrimestre']);
Route::get('/confimpression', [PagesController::class, 'confimpression']);

Route::get('/Acceuil', [PagesController::class, 'Acceuil'])->name('accueil');
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

// --------------------------------------------------
Route::get('/duplicatarecu', [PagesController::class, 'duplicatarecu']);
Route::get('/pdfduplicatarecu/{counters}', [PagesController::class, 'pdfduplicatarecu']);
Route::match(['get', 'post'], '/listefacturescolarite', [PagesController::class, 'listefacturescolarite'])->name('listefacturescolarite');
Route::get('/avoirfacturepaiescolarite/{codemecef}', [PagesController::class, 'avoirfacturepaiescolarite'])->name('avoirfacturepaiescolarite');
Route::post('/avoirfacturescolarite/{codemecef}', [PagesController::class, 'avoirfacturescolarite'])->name('avoirfacturescolarite');
Route::get('/avoirfacturepaiescolaritemodif/{codemecef}', [PagesController::class, 'avoirfacturepaiescolaritemodif'])->name('avoirfacturepaiescolaritemodif');
Route::post('/avoirfacturescolaritmodification/{codemecef}', [PagesController::class, 'avoirfacturescolaritmodification'])->name('avoirfacturescolaritmodification');

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
Route::get('/etat', [ClassesController::class, 'etat'])->name('etat');
Route::get('/etatpaiement', [ClassesController::class, 'etatpaiement'])->name('etatpaiement');
Route::post('/traitementetatpaiement', [ClassesController::class, 'traitementetatpaiement'])->name('traitementetatpaiement');
Route::post('/supprimercontrat', [ClassesController::class, 'supprimercontrat']);
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

// Route::get('/parametre', [PagesController::class, 'parametre']);
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
Route::get('/pdfpaiementsco/{matricule}', [PagesController::class, 'afficherFacture'])->name('facturesnormalisesco');
Route::get('/facturenormalisesco/{nomcompleteleve}', [PagesController::class, 'facturenormalisesco'])->name('pdffacturesco');



Route::get('/duplicatainscription/{elevyo}', [ClassesController::class, 'duplicatainscription']);
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
Route::get('/editions2/fichedenoteviergefina/{classeCode}', [EditionController2::class, 'fichedenoteviergefina'])->name('fichedenoteviergefina');

Route::get('/editions2/relevesparmatiere', [EditionController::class, 'relevesparmatiere'])->name('pages.notes.relevesparmatiere');
Route::get('/editions2/recapitulatifdenotes', [EditionController2::class, 'recapitulatifdenotes'])->name('pages.notes.recapitulatifdenotes');
Route::get('/editions2/resultatsparpromotion', [EditionController2::class, 'resultatsparpromotion'])->name('pages.notes.resultatsparpromotion');
Route::get('/editions2/listedesmeritants', [EditionController2::class, 'listedesmeritants'])->name('pages.notes.listedesmeritants');
Route::post('/search-meritants', [EditionController2::class, 'searchMeritants']);

//Edition de scolarité
Route::get('/editionscolarite', [EditionController::class, 'editionscolarite'])->name('editionscolarite');

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




Route::get('/situationfinanciereglobale', [PagesController::class, 'situationfinanciereglobale'])->name('situationfinanciereglobale');
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
Route::post('/notes/permuter', [CdController::class, 'permuterNotes'])->name('permuter_notes');
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

Route::post('/calculermoyenne', [EditionController::class, 'calculermoyenne'])->name('calculermoyenne');

Route::post('/filtrereleveparmatiere', [EditionController::class, 'filtrereleveparmatiere'])->name('filtrereleveparmatiere');

// gestion des notes
Route::get('/gestioncoefficient', [GestionNotesController::class, 'gestioncoefficient'])->name('gestioncoefficient');
Route::post('/enregistrerCoefficient', [GestionNotesController::class, 'enregistrerCoefficient'])->name('enregistrerCoefficient');
Route::get('/tabledesmatieres', [EditionController::class, 'tabledesmatieres'])->name('tabledesmatieres');

Route::post('/tabledesmatieres', [EditionController::class, 'storetabledesmatieres'])->name('storetabledesmatieres');

Route::get('/saisirnote', [CdController::class, 'saisirnote'])->name('saisirnote');
Route::get('/filternotes', [CdController::class, 'saisirnotefilter'])->name('saisirnotefilter');

Route::get('/verrouillage', [CdController::class, 'verrouillage'])->name('verrouillage');

Route::put('/tabledesmatieres',  [EditionController::class, 'updatetabledesmatieres'])->name('updatetabledesmatieres');



Route::post('/enregistrer-notes', [CdController::class, 'enregistrerNotes'])->name('enregistrer_notes');
Route::post('/delete-notes', [CdController::class, 'deleteNote'])->name('delete-notes');

Route::get('/elevessansnote/{classCode}',  [EditionController::class, 'elevessansnote'])->name('elevessansnote');
Route::get('/editions2/tableauanalytiqueparmatiere', [EditionController2::class, 'tableauanalytiqueparmatiere'])->name('tableauanalytiqueparmatiere');

Route::get('/bulletindenotes', [BulletinController::class, 'bulletindenotes'])->name('bulletindenotes');

// retourne { non: {1:{min, max, lib}, …}, doublant: {…} } ou 404
Route::get('decisions/config/{promotion}', [BulletinController::class, 'getConfig'])
     ->name('decisions.getConfig');

Route::match(['get', 'post'], '/filtertableaunotes', [EditionController::class, 'filtertableaunotes'])->name('filtertableaunotes');
Route::get('/tableaudenotes', [EditionController::class, 'tableaudenotes'])->name('tableaudenotes');

Route::get('/filtertablenotes', [EditionController::class, 'filtertablenotes'])->name('filtertablenotes');


Route::get('/attestationdemerite', [CdController::class, 'attestationdemerite'])->name('attestationdemerite');
Route::get('/attestationfiltere', [CdController::class, 'attestationfilter'])->name('attestationfiltere');
Route::get('/attestation/print', [CdController::class, 'printCertificates'])->name('attestation.print');
Route::get('/attestation/template', [CdController::class, 'printTemplate'])->name('attestation.template');



Route::post('/bulletindenotes', [BulletinController::class, 'storebulletindenotes'])->name('storebulletindenotes');
Route::post('/bulletindenotes/config', [BulletinController::class, 'configurerDecisions'])->name('configurerDecisions');
Route::post('/printbulletindenotes', [BulletinController::class, 'printbulletindenotes'])->name('printbulletindenotes');
// Route::match(['get', 'post'], '/printbulletindenotes', [BulletinController::class, 'printbulletindenotes'])->name('printbulletindenotes');
Route::post('/optionsbulletindenotes', [BulletinController::class, 'optionsbulletindenotes'])->name('optionsbulletindenotes');
Route::get('/get-classes-by-group', [BulletinController::class, 'getClassesByType'])->name('getClassesByGroup');
// Route::get('/printbulletindenotes', [BulletinController::class, 'printbulletindenotes'])->name('printbulletindenotes');
Route::post('/archiveBulletin', [BulletinController::class, 'archiveBulletin']);


Route::post('/bulletindenotes', [BulletinController::class, 'printimagefond'])->name('bulletindenotes');



Route::get('/extrairenote', [BulletinController::class, 'extrairenote'])->name('extrairenote');
Route::post('/extractnote', [BulletinController::class, 'extractnote'])->name('extractnote');
Route::get('/export-excel', [BulletinController::class, 'exportExcel'])->name('notes.exportExcel');
// code pour selectionner plusieur matiere dans export vers educ master
Route::get('/exportMultiple', [BulletinController::class, 'exportMulti'])->name('notes.exportMulti');
Route::get('/getmatieres/{codeclasse}', [BulletinController::class, 'getMatieresParClasse']);
Route::get('/export-notes-multi', [BulletinController::class, 'exportMultiExcel'])->name('notes.exportMultiExcel');

// Afficher le formulaire de changement de mot de passe (accessible seulement si connecté)
Route::get('/profile/password', [PagesController::class, 'showChangePasswordForm'])->name('password.show');

// Traiter la mise à jour du mot de passe
Route::post('/profile/password', [PagesController::class, 'updatePassword'])->name('password.update');

// Afficher le formulaire de modifictaion du profil (accessible seulement si connecté)
Route::get('/profile/update', [PagesController::class, 'showChangeProfileForm'])->name('profile.show');

// Traiter la mise à jour du profil
Route::post('/profile/update', [PagesController::class, 'updateProfile'])->name('profile.update');

// Route::get('/vitrine', [statsController::class, 'performanceAcademique']);

Route::get('/listeparmerite',[ListemeriteController::class, 'acceuil'])->name('listeparmerite');
Route::get('/imprimer-liste-merite', [ListemeriteController::class, 'imprimerListeMerite'])->name('imprimer.liste.merite');
Route::get('/get-classes-by-group', action: [ListemeriteController::class, 'getClassesByGroup'])->name('getClassesByGroup');

Route::get('/editions2/relevespareleves', [ReleveparelevesController::class, 'relevespareleves'])->name('relevespareleves');

// Route::get('/editions2/relevespareleves', [ReleveparelevesController::class, 'getMatieresAndNotes'])->name('relevespareleves');

// Routes pour le tableau analytique
Route::get('/tableauanalytique', [TableauController::class, 'tableauanalytique'])->name('tableauanalytique');
Route::post('/tableauanalytique', [TableauController::class, 'tableauanalytique'])->name('tableauanalytique');

// Routes pour le rapport annuel
Route::get('/rapportannuel', [RapportannuelController::class, 'rapportannuel'])->name('rapportannuel');
Route::post('/decision-config/store', [RapportannuelController::class, 'storeDecisionConfig'])->name('decision.config.store');
Route::post('config/promotions/update',[RapportannuelController::class, 'updateConfigClasses'])->name('config.promotions.update');
Route::post('/rapportannuel', [RapportannuelController::class, 'creerRapport'])->name('rapportannuel');
Route::post('/classe/delete', [RapportannuelController::class, 'deleteClasse'])->name('classe.delete');
Route::get('/listeannuelle', [RapportannuelController::class, 'listeannuelle'])->name('listeannuelle');
Route::get('/rapport/passage', [RapportannuelController::class, 'imprimerPassage'])->name('rapport.passage');
Route::post('/rapport/redoublement', [RapportannuelController::class, 'imprimerRedoublement'])->name('rapport.redoublement');
Route::post('/rapport/exclusion', [RapportannuelController::class, 'imprimerExclusion'])->name('rapport.exclusion');
Route::post('/rapport/abandon', [RapportannuelController::class, 'imprimerAbandon'])->name('rapport.abandon');
Route::get('/listeRaport', [RapportannuelController::class, 'imprimerlistegeneralerapport'])->name('rapport.liste');



// Routes pour le tableau synoptique
Route::get('/tableausynoptique', [TableauController::class, 'tableausynoptique'])->name('tableausynoptique');
Route::post('/tableausynoptique', [TableauController::class, 'tableausynoptique'])->name('tableausynoptique');

// Routes pour les effectifs (en supposant que vous disposez d'une méthode dédiée dans le contrôleur)
Route::get('/effectifs', [TableauController::class, 'effectifs'])->name('effectifs');
Route::post('/effectifs', [TableauController::class, 'effectifs'])->name('effectifs');
Route::get('/importernote', [BulletinController::class, 'importernote'])->name('importernote');
// Route::post('/eleves/preview', [BulletinController::class, 'preview'])->name('eleves.preview');
// Route::post('/eleves/upload', [BulletinController::class, 'upload'])->name('eleves.upload');
Route::post('/import', [BulletinController::class, 'import'])->name('eleves.import');

Route::get('/exporternote', [BulletinController::class, 'exporternote'])->name('exporternote');

Route::get('/exporter-eleves', [BulletinController::class, 'getEleves'])->name('exporter.eleves');



Route::get('/statistiques', [TableauController::class, 'statistiques'])->name('statistiques');
Route::post('/statistiques', [TableauController::class, 'statistiques'])->name('statistiques');

Route::prefix('parametre')->group(function() {
     Route::get('/parametre.parametre', [PagesController::class, 'parametre'])->name('parametrecantine');
      Route::get('inscriptions-discipline', [ParametreController::class, 'inscriptionsDiscipline'])
         ->name('parametre.inscriptions');
    Route::get('tables', [ParametreController::class, 'tables'])
         ->name('parametre.tables');
         Route::post('/params2/updateIdentification', [ParametreController::class, 'updateIdentification'])->name('params2.updateIdentification');
         Route::get('/settings/logo/{side}', [ParametreController::class, 'showLogo'])->name('settings.logo');
         Route::get('/appreciations/edit', [ParametreController::class, 'editAppreciation'])->name('appreciations.edit');
         Route::post('/appreciations/update', [ParametreController::class, 'updateAppreciation'])->name('appreciations.update');
         Route::put('parametre/params2/updateGeneraux', [ParametreController::class, 'updateGeneraux'])->name('params2.updateGeneraux');
         Route::put('/params2/updateNumerotation', [ParametreController::class, 'updateNumerotation'])->name('params2.updateNumerotation');
         Route::post('tables/update-messages', [ParametreController::class, 'updateMessages'])->name('parametre.updateMessages');
     Route::post('parametre/tester-rtf', [ParametreController::class, 'testerRtf'])
     ->name('parametre.testerRtf');

    Route::get('bornes-exercice', [ParametreController::class, 'bornes'])
         ->name('parametre.bornes');
     Route::put('bornes-exercice/{anscol}', [ParametreController::class, 'updateExercice'])
          ->name('exercice.update');
    Route::get('op-ouverture', [ParametreController::class, 'opOuverture'])
         ->name('parametre.opouverture');
    Route::get('config-imprimante', [ParametreController::class, 'configImprimante'])
         ->name('parametre.configimprimante');
    Route::get('changement-trimestre', [ParametreController::class, 'changementTrimestre'])
         ->name('parametre.changementtrimestre');
     Route::post('changement-trimestre', [ParametreController::class, 'storePeriode'])
          ->name('changement-periode');         
});


Route::get('/factures', [ClassesController::class, 'factures']);
Route::get('/factures/{id}', [ClassesController::class, 'show'])->name('factures.show');
Route::get('/listeFacturesAvoir', [ClassesController::class, 'listeFacturesAvoir'])->name('listeFacturesAvoir');
Route::match(['get', 'post'], '/listefacture', [ClassesController::class, 'listefacture'])->name('listefacture');
Route::get('/listefacinscription', [ClassesController::class, 'listefactureinscription'])->name('listefacinscription');

Route::get('/avoirfacturepaie/{codemecef}', [ClassesController::class, 'avoirfacturepaie'])->name('avoirfacturepaie');
Route::post('/avoirfacture/{codemecef}', [ClassesController::class, 'avoirfacture'])->name('avoirfacture');

Route::get('/avoirfacturepaiemodif/{codemecef}', [ClassesController::class, 'avoirfacturepaiemodif'])->name('avoirfacturepaiemodif');
Route::post('/avoirfactureetmodification/{codemecef}', [ClassesController::class, 'avoirfactureetmodification'])->name('avoirfactureetmodification');

Route::get('/avoirfactureinscri/{codemecef}', [ClassesController::class, 'avoirfactureinscri'])->name('avoirfactureinscri');
Route::post('/avoirfactureinscription/{codemecef}', [ClassesController::class, 'avoirfactureinscription'])->name('avoirfactureinscription');



Route::get('/duplicatafacture', [PagesController::class, 'duplicatafacture']);
Route::get('/dowloadduplfac/{id}', [PagesController::class, 'dowloadduplfac']);
Route::get('/duplicatafacture', [DuplicataController::class, 'showForm'])->name('duplicata.showForm');
Route::post('/filterduplicata', [DuplicataController::class, 'filterduplicata'])->name('filterduplicata');
Route::get('/pdfduplicatacontrat/{idcontrat}', [DuplicataController::class, 'pdfduplicatacontrat']);
Route::get('/pdfduplicatapaie/{counters}', [DuplicataController::class, 'pdfduplicatapaie']);
Route::get('/pdfduplicatainscription/{counters}', [DuplicataController::class, 'pdfduplicatainscription']);
Route::get('/duplicatainscription2/{idcontrat}',[DuplicataController::class,'duplicatainscription2']);


Route::get('/paiementeleve', [PagesController::class, 'paiementeleve']);




// Routes pour le menu Gestion du Personnel******************************************************************************************************
Route::get('/updatePersonnel', [GestionPersonnelController::class, 'UpdatePersonnel']);


Route::get('/addAgent', [GestionPersonnelController::class, 'AddAgent']);
Route::post('/typeagent/store', [GestionPersonnelController::class, 'storeTypeAgent'])->name('typeagent.store');

// suppression / mise à jour via le libellé (URL encode)
Route::delete('/typeagent/libelle/{libelle}', [GestionPersonnelController::class, 'deleteByLibelle'])->name('typeagent.deleteByLibelle');
Route::put('/typeagent/libelle/{libelle}', [GestionPersonnelController::class, 'updateByLibelle'])->name('typeagent.updateByLibelle');

//Route pour la création de prime
Route::post('/prime/store', [InscrirepersonnelController::class, 'storePrime'])->name('prime.store');

//Route pour la création de nouveaux profils
Route::post('/profils', [InscrirepersonnelController::class, 'store'])->name('profils.store');

//Route pour la création de nouveaux profils
Route::post('/agents/store', [InscrirepersonnelController::class, 'storeargent'])->name('agents.store');


Route::get('/inscrirepersonnel', [InscrirepersonnelController::class, 'index']);

Route::get('/confTauxH', [GestionPersonnelController::class, 'confTauxH']);


// Route pour bulletin de paie

// --------------------Rubrique Salaire -------------------------
Route::get('/rubriquesalaire', [BulletinPaieController::class, 'rubriquesalaire'])->name('rubriquesalaire');
Route::post('/enregistrerrubriquesalaire', [BulletinPaieController::class, 'enregistrerrubriquesalaire'])->name('enregistrerrubriquesalaire');
Route::post('/supprimerrubrique', [BulletinPaieController::class, 'supprimerrubrique'])->name('supprimerrubrique');
Route::put('/modifierubrique/{code}', [BulletinPaieController::class, 'update'])->name('rubrique.update');

// --------------------Profils agents -------------------------
Route::get('/profilsagents', [BulletinPaieController::class, 'profilsagents'])->name('profilsagents');
Route::post('/enregistrerprofilagents', [BulletinPaieController::class, 'enregistrerProfilAgents'])->name('enregistrerprofilagents');
Route::put('modifierprofil/{id}', [BulletinPaieController::class, 'modifierProfil'])->name('modifierProfil');
Route::post('supprimerprofil', [BulletinPaieController::class, 'supprimerProfil'])->name('supprimerProfil');
Route::get('/profils/{numero}/primes', [BulletinPaieController::class, 'getPrimes'])
    ->name('profils.primes');
    // route pour récupérer les agents liés à un profil (retour JSON)
Route::get('profils/{numero}/agents', [BulletinPaieController::class, 'getAgents'])
    ->whereNumber('numero')
    ->name('profils.agents');

//Route pour l'affichage des matières
Route::get('/get-matieres/{matricule}', [GestionPersonnelController::class, 'getMatieres']);

//Route pour la suppression d'agent
Route::delete('/agents/{matricule}', [GestionPersonnelController::class, 'destroy'])->name('agents.destroy');

// route pour la modification

Route::get('/inscrirepersonnel/{matricule?}', [InscrirepersonnelController::class, 'index'])
    ->name('inscrirepersonnel.index');

Route::post('/agents', [InscrirepersonnelController::class, 'storeargent'])
    ->name('agents.store');

Route::put('/agents/{matricule}', [InscrirepersonnelController::class, 'updateargent'])
    ->name('agents.update');

// Optionnel : si tu veux conserver le lien /modifieragent/...
Route::get('/modifieragent/{matricule}', function($matricule){
    return redirect()->route('inscrirepersonnel.index', ['matricule' => $matricule]);
});
