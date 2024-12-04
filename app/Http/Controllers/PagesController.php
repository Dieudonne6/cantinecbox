<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Eleve;
use App\Models\Moiscontrat;
use App\Models\Paramcontrat;
use App\Models\Contrat;
use App\Models\Paiementcontrat;
use App\Models\Paiementglobalcontrat;
use App\Models\Usercontrat;
use App\Models\Paramsfacture;
use App\Models\User;
use App\Models\Params2;
use App\Models\Classes;
use App\Models\Departement;
use App\Models\Reduction;
use App\Models\Promo;
use App\Models\Serie;
use App\Models\Typeclasse;
use App\Models\Typeenseigne;
use App\Models\Elevea;
use App\Models\Groupeclasse;
use App\Models\Eleveplus;
use App\Models\Echeance;
use App\Models\Echeancc;
use App\Models\Scolarite;
use App\Models\Classesgroupeclass;
use App\Models\Journal;
use App\Models\Chapitre;
use App\Models\Deleve;
use App\Models\Clasmat;
use App\Models\Facturenormalise;
use App\Models\Facturescolarit;

use App\Models\Eleve_pont;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Duplicatafacture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\PDF;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use DateTime;

class PagesController extends Controller
{
  
  public function inscriptioncantine(){
    if(Session::has('account')){
      // Liste des mots à exclure
      $excludedWords = ["DELETE", 'NON'];
      
      // Construire la requête initiale
      $query = Classes::query();
      
      // Ajouter des conditions pour exclure les mots
      foreach ($excludedWords as $word) {
        $query->where('CODECLAS', 'not like', '%' . $word . '%');
      }
      
      // Récupérer les résultats
      $class = $query->get();
      
      // Retourner la vue avec les résultats
      return view('pages.inscriptioncantine')->with('class', $class);
    } 
    return redirect('/');
  }

  public function registreeleve(Request $request){
    $type = $request->query('type');
    $infoparamcontrat = Paramcontrat::first();
    $anneencours = $infoparamcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours.'-'.$annesuivante;
    if($type == 1 ){
        $infoelevenom = Eleve::orderby('NOM', 'asc')->get();
        // dd($infoelevenom);
        return view('pages.etat.registrefiche', compact('annescolaire', 'infoelevenom'));
    }else{
        $infoelevematricule = Eleve::orderby('MATRICULE', 'asc')->get();
        // dd($infoelevematricule);
        return view('pages.etat.registretableau', compact('annescolaire', 'infoelevematricule'));
    }
}
  
  public function getEleves($codeClass)
  {
    $eleves = Eleve::where('CODECLAS', $codeClass)
    ->leftJoin('contrat', 'eleve.MATRICULE', '=', 'contrat.eleve_contrat')
    ->where(function ($query) {
      $query->whereNull('contrat.eleve_contrat') // Élèves sans contrat
      ->orWhere('contrat.statut_contrat', 0); // Élèves avec contrat ayant statut 0
    })
    ->select('eleve.*')
    ->distinct()   // Assurez-vous de sélectionner uniquement les colonnes de la table eleves
    ->get();
    
    // $eleves = Eleve::where('CODECLAS', $codeClass)
    // ->whereHas('contrats', function($query) {
    //     $query->where('statut_contrat', 0);
    // })
    // ->get();
    return response()->json($eleves);
  }
  public function getMontant($codeClass)
  {
    // Suppose that the params table has one row
    $params = Paramcontrat::first();
    
    if (($codeClass === "MAT1") || ($codeClass === "MAT2")  || ($codeClass === "MAT2II")  || ($codeClass === "MAT3")  || ($codeClass === "MAT3II")  || ($codeClass === "PREMATER")) {
      $montant = $params->fraisinscription_mat;
    } else {
      $montant = $params->fraisinscription2_paramcontrat;
    }
    
    return response()->json(['montant' => $montant]);
  }
  public function getPromo($ensigClass)
  {
    $promo = Promo::where('TYPEENSEIG', $ensigClass)->get();
    return response()->json($promo);
  }
  public function getSerie($serieClass)
  {
    $serie = Serie::where('CYCLE', $serieClass)->get();
    return response()->json($serie);
  }
  // public function getClasmat($getClasmat)
  // {
  //   $getClasmat = Clasmat::where('CODEMAT', $getClasmat)->first();
  //   return response()->json($getClasmat);
  // }
  public function getClas($Class)
  {
    $Class = Classesgroupeclass::where('LibelleGroupe', $Class)->get();
    return response()->json($Class);
  }
  public function paiement(){
    return view('pages.paiement');
  } 
  
  public function nouveaucontrat(){
    return view('pages.nouveaucontrat');
  }
  public function exporter(){
    return view('pages.inscriptions.exporter');
  }
  public function listedeseleves(){
    
    $allClasse = Classes::all();
    return view('pages.inscriptions.listedeseleves', compact('allClasse'));
  }
  
  public function imprimerProfilTypeClasse(Request $request) {
    $typeClasse = $request->input('typeclasse');
    $reductions = Reduction::all();
    $typeclasse = Typeclasse::all();
    $eleves = Eleve::with('reduction') // Charge la relation 'reduction'
        ->where('TYPECLASSE', $typeClasse) // Filtrer les élèves par type de classe
        ->where('CodeReduction', '!=', null) // Filtrer les élèves ayant une réduction
        ->get(); // Récupérer tous les élèves sans pagination

    // Regrouper les élèves par CodeReduction
    $elevesParReduction = $eleves->groupBy('CodeReduction');

    return view('pages.inscriptions.profiltypeclasse', compact('typeClasse', 'reductions', 'typeclasse', 'elevesParReduction', 'eleves'));
}
  public function listeselectiveeleve(){
    $currentYear = now()->year;
    
    $minBirthDate = Eleve::min('DATENAIS');
    $maxBirthDate = Eleve::max('DATENAIS');
    
    $minAgeFromDB = $currentYear - date('Y', strtotime($maxBirthDate)); // Plus jeune élève
    $maxAgeFromDB = $currentYear - date('Y', strtotime($minBirthDate)); // Plus vieux élève
    
    $allClasse = Classes::all();
    return view('pages.inscriptions.listeselectiveeleve', compact('allClasse','minAgeFromDB','maxAgeFromDB'));
  }
  public function frais(){
    $param = Paramcontrat::first();
    return view('pages.frais', ['param' => $param]);
  }
  
  public function fraisnouveau(Request $request){
    $param = new Paramcontrat();
    $param->anneencours_paramcontrat = $request->input('anneencours_paramcontrat');
    $param->fraisinscription_paramcontrat = $request->input('fraisinscription_paramcontrat');
    $param->fraisinscription_mat = $request->input('fraisinscription_mat');
    $param->fraisinscription2_paramcontrat = $request->input('fraisinscription2_paramcontrat');
    $param->coutmensuel_paramcontrat = $request->input('coutmensuel_paramcontrat');
    $param->save();
    return back()->with('status','Enregistrer avec succes');
  }
  
  public function modifierfrais($id_paramcontrat, Request $request){
    // $test = $request->input('id_paramcontrat');
    // dd($test);
    $params = Paramcontrat::find($id_paramcontrat);
    $params->anneencours_paramcontrat = $request->input('anneencours_paramcontrat');
    $params->fraisinscription_paramcontrat = $request->input('fraisinscription_paramcontrat');
    $params->fraisinscription_mat = $request->input('fraisinscription_mat');
    
    $params->fraisinscription2_paramcontrat = $request->input('fraisinscription2_paramcontrat');
    $params->coutmensuel_paramcontrat = $request->input('coutmensuel_paramcontrat');
    $params->update();
    return back()->with('status','Modifier avec succes');
  }
  
  public function connexiondonnees(){
    return view('pages.connexiondonnees');
    
  }
  
  public function dashbord(){
    return view('pages.dashbord');
    
  }
  
  public function listedesreductions()
  {
      $eleves = Eleve::all();
      $reductions = Reduction::all();
      $classes = Classes::all();
      return view('pages.inscriptions.listedesreductions', compact('eleves', 'reductions', 'classes'));
  }
  
  public function statistique(){
    return view('pages.tableaudebord.statistique');
    
  }
  
  public function recouvrementsM(){
    return view('pages.tableaudebord.recouvrementsM');
    
  }
  
  public function hsuppression(){
    return view('pages.tableaudebord.hsuppression');
    
  }
  
  public function changetrimestre(){
    return view('pages.parametre.changetrimestre');
    
  }
  
  public function confimpression(){
    return view('pages.parametre.confimpression');
    
  }
  
  public function Acceuil(){
    return view('pages.inscriptions.Acceuil');
    
  }
  public function listedesretardsdepaiement(){
    return view('pages.inscriptions.listedesretardsdepaiement');
  }
  public function echeancier($MATRICULE){
    $eleve = Eleve::where('MATRICULE', $MATRICULE)->first();
    $elev = Eleve::with('classe')->where('MATRICULE', $MATRICULE)->firstOrFail();
    $libel = Params2::first();
    $paramcontrat = Paramcontrat::first();
    $anneencours = $paramcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours.'-'.$annesuivante;
    $claso = $eleve->CODECLAS;
    $reduction = Reduction::all();
    $classis = Classes::where('CODECLAS', $claso)->first();
    $donnee = Echeance::where('MATRICULE', $MATRICULE)->get();
    return view('pages.inscriptions.echeancier')->with('eleve', $eleve)->with('elev', $elev)->with('libel', $libel)->with('reduction', $reduction)->with('donnee',$donnee)->with('classis',$classis)->with('annescolaire', $annescolaire);
  }
  public function profil($MATRICULE){
    $eleve = Eleve::where('MATRICULE', $MATRICULE)->first();
    $elev= Eleve::with('classe')->where('MATRICULE', $MATRICULE)->firstOrFail();
    $libel = Params2::first();
    $reduction = Reduction::all();
    $totalArrieres = Echeance::where('MATRICULE', $MATRICULE)->sum('ARRIERE');
    return view('pages.inscriptions.profil')->with('eleve', $eleve)->with('elev', $elev)->with('libel', $libel)->with('reduction', $reduction)->with('totalArrieres',$totalArrieres);
    
  }
  public function regenererecheance(Request $request, $MATRICULE) {
    $echeancesData = $request->input('echeancesData');
    $echeancesDat = json_decode($echeancesData, true);
    $arie = Eleve::where('MATRICULE', $MATRICULE)->first();
    // $ari = $arie->ARRIERE;
    Eleve::where('MATRICULE', $MATRICULE)->update(['EcheancierPerso' => 1]);
  Echeance::where('MATRICULE', $MATRICULE)->delete();
  $infoparamcontrat = Paramcontrat::first();
  $anneencours = $infoparamcontrat->anneencours_paramcontrat;
  $annesuivante = $anneencours + 1;
  $annescolaire = $anneencours.'-'.$annesuivante;
  $montantInitial = $arie->ARRIERE; // Le montant que tu veux mettre sur la première ligne
    $isFirst = true; 
  foreach ($echeancesDat as $echeance) {
    $dateFormat = 'd/m/Y';
    $dateOriginal = $echeance['datepaye'];
    // $arriere = $echeance['arriere'];
    $date = Carbon::createFromFormat($dateFormat, $dateOriginal);
    $dateFormater = $date->format('Y-m-d');
    
    if ($isFirst) {
      $montantAPayer = $montantInitial;
      $isFirst = false; // Après la première ligne, changer l'état du drapeau
  } else {
      $montantAPayer = 0; // Mettre 0 pour les autres lignes
  }
    Echeance::create([
      'NUMERO' => $echeance['tranche'],
      'APAYER' => $echeance['montantpaye'],
      'DATEOP' => $dateFormater,
      'MATRICULE' => $MATRICULE,
      'anneeacademique' => $annescolaire,
      'ARRIERE' => $montantAPayer

    ]);
  } 
  return back()->with('status', 'Echeance modifier avec succes');

  }
  
  public function modifieprofil(Request $request, $MATRICULE) {
    $modifiprofil = Eleve::find($MATRICULE);
    $classe = $request->input('classe');
    $typeechean = Classes::where('CODECLAS', $classe)->first();
    $typeecheance = $typeechean->TYPEECHEANCIER;
    $typeduree = $typeechean->DUREE;
    
    $type = $request->input('type');
    $reduc = $request->input('reduction');
    $modifiecheances = Echeance::where('MATRICULE', $MATRICULE)->orderBy('NUMERO', 'desc')->get();
    $typemode = Reduction::where('CodeReduction', $reduc)->value('mode');
    $sco = $request->input('sco');
    $dure = $request->input('duree');
    $frais1 =  $request->input('frais1');
    $frais2 =  $request->input('frais2');
    $frais3 =  $request->input('frais3');
    $frais4 =  $request->input('frais4');
    $arriere = $request->input('arriere');
    $modifiecheancc = Echeancc::where('CODECLAS', $classe)->orderBy('NUMERO', 'desc')->get();
    
    if ($reduc == 0) {
      foreach ($modifiecheancc as $echeancc) {
        if ($type == 1) {
          $montant = $echeancc->APAYER; // Utiliser la colonne APAYER si type est 1
        } else {
          $montant = $echeancc->APAYER2; // Utiliser la colonne APAYER2 si type est 2
        }
        Echeance::where('NUMERO', $echeancc->NUMERO)
        ->update(['APAYER' => $montant]);
      }
    }
    else {
      if($typeecheance == 2){
        $total = $sco + $frais1 + $frais2+ $frais3 + $frais4 + $arriere;
        if($typemode == 2) {
          $montantecheance = $total / $typeduree;
          // Mettre à jour toutes les échéances avec ce montant
          foreach ($modifiecheances as $echeance) {
            // Mettre à jour la colonne APAYER avec le montant calculé
            Echeance::where('NUMERO', $echeance->NUMERO)
            ->update(['APAYER' => $montantecheance]);
          }            
        } else {
          foreach ($modifiecheancc as $echeance) {
              $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;

              if ($montant_a_payer <= $total) {
                  $total -= $montant_a_payer;
                  Echeance::where('NUMERO', $echeance->NUMERO)
                      ->update(['APAYER' => 0]);
              } else {
                  Echeance::where('NUMERO', $echeance->NUMERO)
                      ->update(['APAYER' => $montant_a_payer - $total]);
                  $total = 0; // Stopper une fois que le total est atteint
                  break;
              }
          }
        }
      } else {
        if($typemode == 2) {
          $montantecheance = $sco / $typeduree;
          // Mettre à jour toutes les échéances avec ce montant
          
          foreach ($modifiecheances as $echeance) {
            // Mettre à jour la colonne APAYER avec le montant calculé
            Echeance::where('NUMERO', $echeance->NUMERO)
            ->update(['APAYER' => $montantecheance]);
          }            
        } else {
          
            foreach ($modifiecheancc as $echeance) {
              $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;

              if ($montant_a_payer <= $sco) {
                  $sco -= $montant_a_payer;
                  Echeance::where('NUMERO', $echeance->NUMERO)
                      ->update(['APAYER' => 0]);
              } else {
                  Echeance::where('NUMERO', $echeance->NUMERO)
                      ->update(['APAYER' => $montant_a_payer - $sco]);
                  $sco = 0; // Stopper une fois que le total est atteint
                  break;
              }
          }
        }
      }
    }
    
    $modifiprofil->CodeReduction = $request->input('reduction');
    $modifiprofil->APAYER = $request->input('sco');
    $modifiprofil->FRAIS1 = $request->input('frais1');
    $modifiprofil->FRAIS2 = $request->input('frais2');
    $modifiprofil->FRAIS3 = $request->input('frais3');
    $modifiprofil->FRAIS4 = $request->input('frais4');
    $modifiprofil->ARRIERE = $request->input('arriere');
    Echeance::where('NUMERO', 1)
    ->update(['ARRIERE' => $arriere]);
    
    $modifiprofil->update();
    return back()->with('status', 'Reduction modifier avec succes');
    
  }
  public function modifieecheancier(Request $request, $MATRICULE) {
    $modifiprofil = Eleve::find($MATRICULE);
    $classe = $request->input('classe');
    $typeechean = Classes::where('CODECLAS', $classe)->first();
    $typeecheance = $typeechean->TYPEECHEANCIER;
    $typeduree = $typeechean->DUREE;
    
    $type = $request->input('type');
    $reduc = $request->input('reduction');
    $modifiecheances = Echeance::where('MATRICULE', $MATRICULE)->orderBy('NUMERO', 'desc')->get();
    $modifieCheances = Echeance::where('MATRICULE', $MATRICULE)
    ->orderBy('NUMERO', 'desc')
    ->first();
    $typeduree = $modifieCheances ? $modifieCheances->NUMERO : null;

    $typemode = Reduction::where('CodeReduction', $reduc)->value('mode');
    $sco = $request->input('sco');
    $dure = $request->input('duree');
    $frais1 =  $request->input('frais1');
    $frais2 =  $request->input('frais2');
    $frais3 =  $request->input('frais3');
    $frais4 =  $request->input('frais4');
    $arriere = $request->input('arriere');
    $modifiecheancc = Echeancc::where('CODECLAS', $classe)->orderBy('NUMERO', 'desc')->get();
    
    if ($reduc == 0) {
      foreach ($modifiecheancc as $echeancc) {
        if ($type == 1) {
          $montant = $echeancc->APAYER; // Utiliser la colonne APAYER si type est 1
        } else {
          $montant = $echeancc->APAYER2; // Utiliser la colonne APAYER2 si type est 2
        }
        Echeance::where('NUMERO', $echeancc->NUMERO)
        ->update(['APAYER' => $montant]);
      }
    }
    else {
      if($typeecheance == 2){
        $total = $sco + $frais1 + $frais2+ $frais3 + $frais4 + $arriere;
          $montantecheance = $total / $typeduree;
          // Mettre à jour toutes les échéances avec ce montant
          foreach ($modifiecheances as $echeance) {
            // Mettre à jour la colonne APAYER avec le montant calculé
            Echeance::where('NUMERO', $echeance->NUMERO)
            ->update(['APAYER' => $montantecheance]);
          }            
      } else {
        $montantecheance = $sco / $typeduree;
        // Mettre à jour toutes les échéances avec ce montant
        foreach ($modifiecheances as $echeance) {
          // Mettre à jour la colonne APAYER avec le montant calculé
          Echeance::where('NUMERO', $echeance->NUMERO)
          ->update(['APAYER' => $montantecheance]);
        }            
        
      }
    }
    
    $modifiprofil->CodeReduction = $request->input('reduction');
    $modifiprofil->APAYER = $request->input('sco');
    $modifiprofil->FRAIS1 = $request->input('frais1');
    $modifiprofil->FRAIS2 = $request->input('frais2');
    $modifiprofil->FRAIS3 = $request->input('frais3');
    $modifiprofil->FRAIS4 = $request->input('frais4');
    $modifiprofil->ARRIERE = $request->input('arriere');
    Echeance::where('NUMERO', 1)
    ->update(['ARRIERE' => $arriere]);
    
    $modifiprofil->update();
    return back()->with('status', 'Reduction modifier avec succes');
    
  }
  public function gestionarriere(){
    return view('pages.inscriptions.gestionarriere');
    
  }
  public function connexion(){
    $login = User::get();
    return view('pages.connexion', ['login' => $login]);
  }
  
  public function logins(Request $request){
    $account = User::where("login",$request->login_usercontrat)->first();
    
    if($account){
      if (Hash::check($request->password_usercontrat, $account->motdepasse)) {
        Session::put('account', $account);
        $id_usercontrat = $account->id_usercontrat;
        $image = $account->image;
        
        $nom_user = $account->nomuser;
        Session::put('image', $image);
        
        $prenom_user = $account->prenomuser;
        Session::put('id_usercontrat', $id_usercontrat);
        Session::put('nom_user', $nom_user);
        Session::put('prenom_user', $prenom_user);
        return redirect("vitrine");
      } else{
        return back()->with('status', 'Mot de passe ou email incorrecte');
        
      }
    } else {
      return back()->with('status', 'Mot de passe ou email incorrecte');
      
    }
    
  }
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
  }
  
  public function etatdesdroits(Request $request) {
    $classe = Classes::all();
    $params = Params2::first();
    $selectedClasses = $request->input('selectedClasses', []);
    $eleves = collect(); // Initialise la variable $eleves par défaut à une collection vide
  
    if ($request->ajax()) { // Vérifie si la requête est une requête AJAX
        if (!empty($selectedClasses)) {
            // Récupérer les élèves en fonction des classes sélectionnées
            $eleves = Eleve::whereIn('CODECLAS', $selectedClasses)->get();
  
            // Vérifiez si des élèves ont été trouvés
            if ($eleves->isEmpty()) {
                return response()->json(['message' => 'Aucun élève trouvé pour les classes sélectionnées.'], 404);
            }
  
            // Récupérer les montants pour chaque élève
            $choixPlage = $request->input('choixPlage');
            $eleves->transform(function ($eleve) use ($choixPlage, $params) {
                if ($choixPlage === 'Tout') {
                    // Récupérer tous les montants sans filtrer par AUTREF
                    $montants = Scolarite::where('MATRICULE', $eleve->MATRICULE)->get();
                } else {
                    // Récupérer les montants filtrés par AUTREF
                    $autrefMapping = [
                        'Scolarité' => 1,
                        'Arrièrés' => 2,
                        $params->LIBELF1 => 3,
                        $params->LIBELF2 => 4,
                        $params->LIBELF3 => 5,
                        $params->LIBELF4 => 6,
                    ];
                    $montants = Scolarite::where('MATRICULE', $eleve->MATRICULE)
                        ->where('AUTREF', $autrefMapping[$choixPlage] ?? null)
                        ->get();
                }
  
                // Ajouter les montants à l'élève
                $eleve->montants = $montants;
  
                return $eleve;
            });
        } else {
            return response()->json(['message' => 'Aucune classe sélectionnée.'], 400);
        }
  
        // Retourner les données en JSON pour les requêtes AJAX
        return response()->json([
            'eleves' => $eleves
        ]);
    }
  
    // Retourner la vue normalement pour les autres requêtes
    return view('pages.inscriptions.etatdesdroits', compact('classe', 'params', 'eleves'));
  }  
  

  public function vitrine(){
    if(Session::has('account')){
      $totaleleve = Eleve::count();
      $totalcantineinscritactif = Contrat::where('statut_contrat', 1)->count();
      $totalcantineinscritinactif = Contrat::where('statut_contrat', 0)->count();
      
      // dd($totalcantineinscritactif);
      return view('pages.vitrine')->with('totalcantineinscritactif', $totalcantineinscritactif)->with('totalcantineinscritinactif', $totalcantineinscritinactif)->with('totaleleve', $totaleleve);
    }return redirect('/');
  }
  public function paramsfacture(){
    return view('pages.paramsfacture');
  }
  
  public function paramsemecef(Request $request){
    $emcef = new Paramsfacture();
    $emcef->ifu = $request->input('ifu');
    $emcef->token = $request->input('token');
    $emcef->taxe = $request->input('taxe');
    $emcef->type = $request->input('type');
    $request->validate([
      'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    $imageName = $request->file('logo');
    $imageContent = file_get_contents($imageName->getRealPath());
    
    
    
    $emcef->logo = $imageContent;
    
    $emcef->save();
    return back()->with('status','Enregistrer avec succes');
  }
  public function inscriptions(){
    return view('pages.etat.inscriptions');
  }
  public function enregistreruser(Request $request){
    $login = new User();
    $password_crypte = Hash::make($request->password);
    $login->nomgroupe = 1;
    $login->login = $request->input('login');
    $login->nomuser = $request->input('nom');
    $login->prenomuser = $request->input('prenom');
    $imagenam = $request->file('image');
    $imageconten = file_get_contents($imagenam->getRealPath());
    $login->image = $imageconten;
    $login->motdepasse = $password_crypte;
    $login->administrateur = 1;
    $login->user_actif = 1;
    $login->save();
    // $login->motdepasse ='';
    // $login->motdepasse ='';
    
    
    // $login->motdepasse ='';
    
    $login->save();
    return back()->with('status','Enregistrer avec succes');
    
  }
  
  public function parametre(){
    if(Session::has('account')){
      $param = Paramcontrat::first();
      return view('pages.parametre.parametre', ['param' => $param]);
    }return redirect('/');
  } 
  
  public function duplicatafacture(){
    if(Session::has('account')){
      $duplicatafactures = Duplicatafacture::all();
      
      return view('pages.duplicatafacture')->with('duplicatafactures', $duplicatafactures);
    } 
    return redirect('/');
  } 
  
  // public function paiementeleve(){
  //   if(Session::has('account')){
  //     // $duplicatafactures = Duplicatafacture::all();
      
  //     return view('pages.inscriptions.Paiement');
  //   } 
  //   return redirect('/');
  // } 
  public function modifierclasse(){
    if(Session::has('account')){
      // $duplicatafactures = Duplicatafacture::all();
      
      return view('pages.inscriptions.modifierclasse');
    } 
    return redirect('/');
  } 
  public function majpaiementeleve($matricule){
    if(Session::has('account')){
        $eleve = Eleve::where('MATRICULE', $matricule)->first();
        
        // Récupérer les données de scolarité pour les paiements (AUTREF = 1)
        $scolarite = Scolarite::where('MATRICULE', $matricule)
            ->where('AUTREF', 1) // Pour les paiements
            ->get();

        // Récupérer les arriérés (AUTREF = 2)
        $arrières = Scolarite::where('MATRICULE', $matricule)
            ->where('AUTREF', 2) // Pour les arriérés
            ->get();

        // Calculer les totaux
        $totalScolarite = $scolarite->sum('MONTANT');
        $totalArrieres = $arrières->sum('MONTANT');

        // Vérifiez si l'élève existe
        if (!$eleve) {
            return redirect()->back()->withErrors(['L\'élève avec ce matricule n\'existe pas.']);
        }

        return view('pages.inscriptions.MajPaiement', compact('eleve', 'scolarite', 'arrières', 'totalScolarite', 'totalArrieres'));
    } 
    return redirect('/');
}
  
  public function tabledesclasses(){
    if(Session::has('account')){
      // $duplicatafactures = Duplicatafacture::all();
      
      return view('pages.inscriptions.tabledesclasses');
    } 
    return redirect('/');
    
  }
  
  
  
  public function groupe(){
    if(Session::has('account')){
      // $duplicatafactures = Duplicatafacture::all();
      
      return view('pages.inscriptions.groupe');
    } 
    return redirect('/');
    
  }
  
  public function inscrireeleve(){
    
    // Récupérer le dernier matricule existant
    $lastMatricule = Eleve::orderBy('MATRICULE', 'desc')->pluck('MATRICULE')->first();
    
    // Générer le nouveau matricule
    if ($lastMatricule) {
      // En supposant que le matricule est de type numérique
      $newMatricule = (int)$lastMatricule + 1;
    } else {
      // Si aucun matricule n'existe encore, initialiser à un numéro de départ
      $newMatricule = 1;
    }
    
    
    
    
    $allClasse = Classes::all();
    $allReduction = Reduction::all();
    $allDepartement = Departement::all();
    $archive = Elevea::get();
    
    return view('pages.inscriptions.inscrireeleve', compact('allClasse', 'allReduction', 'allDepartement', 'newMatricule', 'archive'));
    // return view('pages.inscriptions.inscrireeleve')->with('archive', $archive);
  } 
  
  public function certificatscolarite($CODECLAS = null, $matricule = null)
  {
      // Si un matricule est fourni, cela signifie que l'on veut générer le PDF pour un élève spécifique
      if ($matricule) {
          $eleves = Eleve::where('MATRICULE', $matricule)->first();
          $eleveplus = Eleveplus::first();
          $nomecole = Params2::first();

          return view('pages.inscriptions.pdfcertificatscolarite', compact('eleves', 'eleveplus', 'nomecole'));
      }

      // Si aucun matricule n'est fourni, on affiche la vue normale avec les classes et les élèves
      $classe = Classes::all();

      // Si une classe est sélectionnée, récupérer les élèves de cette classe
      if ($CODECLAS) {
          $eleves = Eleve::where('CODECLAS', $CODECLAS)->get();
      } else {
          $eleves = collect(); // Aucun élève si pas de classe sélectionnée
      }

      return view('pages.inscriptions.certificatsolarite', compact('classe', 'eleves'));
  }

  public function impression(Request $request)
  {
      // Récupérer les matricules des élèves sélectionnés depuis la requête
      $matricules = explode(',', $request->input('matricules', ''));
      $classe = $request->input('classe');
  
      // Récupérer l'observation depuis la requête
      $observation = $request->input('observation', '');
  
      // Vérifiez si la classe est choisie
      if (empty($classe)) {
          return redirect()->back()->with('erreur', 'Veuillez choisir une classe avant l\'impression.');
      }
  
      // Vérifiez si des matricules sont sélectionnés
      if (empty($matricules) || count($matricules) === 1 && empty($matricules[0])) {
          return redirect()->back()->with('erreur', 'Veuillez cocher au moins un élève avant l\'impression.');
      }
  
      // Récupérer les élèves sélectionnés
      $eleves = Eleve::whereIn('MATRICULE', $matricules)->get();
      $eleveplus = Eleveplus::first();
      $nomecole = Params2::first();
  
      // Vérifiez si les données ont été trouvées
      if ($eleves->isEmpty() || !$eleveplus || !$nomecole) {
          return redirect()->back()->with('erreur', 'Données non trouvées.');
      }
  
      // Générer les certificats pour les élèves sélectionnés
      return view('pages.inscriptions.pdfcertificatscolarite', compact('eleves', 'eleveplus', 'nomecole', 'observation'));
  }
  
  public function droitconstate(){
    if(Session::has('account')){
      // $duplicatafactures = Duplicatafacture::all();
      
      return view('pages.inscriptions.droitconstate');
    } 
    return redirect('/');
    
  }
  
  public function photos(){
    return view('pages.inscriptions.photos');
  } 
  
  
  
  public function facturesclasses(){
    return view('pages.inscriptions.facturesclasses');
  }
  
  public function reductioncollective(){
    $reductions = DB::table('reduction')->get();
    $eleves = Eleve::all();
    $classes = Classes::all();
    return view('pages.inscriptions.reductioncollective', compact('classes', 'eleves', 'reductions'));
  }
  
  public function recupmatricule(Request $request){
    $string = $request->input('eleves');
    $array = explode(' ', $string);
  }
  
  public function applyReductions(Request $request)
  {
    $reduction = Reduction::find($request->input('reduction'));
    
    if (!$reduction) {
      return redirect()->back()->with('error', 'La classe ou la réduction n\'existe pas');
    }
    foreach ($request->input('eleves') as $eleveData) {
      $array = explode(' ', $eleveData); // Sépare la chaîne par espaces
      $eleveId = intval(array_pop($array)); // Récupère le dernier élément et le convertit en entier
      
      // Trouver l'élève par ID
      $eleve = Eleve::find($eleveId);
      
      if ($eleve) {
        $eleve->CodeReduction = $reduction->CodeReduction;
        $eleve->update();
      }
    }
    
    return redirect()->back()->with('success', 'Réductions appliquées avec succès');
  }
  
  
  public function discipline(){
    return view('pages.inscriptions.discipline');
  } 
  
  public function archive(){
    $elevea = Elevea::all();
    $delevea = Deleve::all();
    return view('pages.inscriptions.archive', compact('elevea', 'delevea'));
  } 

  public function pagedetailarchive($MATRICULE)
  {
      // Récupérer les informations de l'élève
      $elevea = Elevea::where('MATRICULE', $MATRICULE)->first();
      // Rechercher les enregistrements de l'élève avec le matricule et une ANSCOL non nulle
      $delevea = Deleve::where('MATRICULE', $MATRICULE)
                    ->whereNotNull('ANSCOL') // S'assurer que ANSCOL est présent
                    ->get(); // Utiliser get() pour obtenir une collection
      $eleve_pont = Eleve_pont::where('MATRICULE', $MATRICULE)
                    ->whereNotNull('anneeacademique') // S'assurer que ANSCOL est présent
                    ->get(); // Utiliser get() pour obtenir une collection
      return view('pages.inscriptions.pagedetailarchive', compact('elevea', 'delevea', 'eleve_pont'));
  }
  
  
  public function etatdesarrieresinscrits(){
    // LISTE DES ELEVES DONT ARRIERE EST != 0
    $listeElevesArr = Eleve::where('ARRIERE', '!=', 0)->get();
    // Initialiser un tableau pour stocker les résultats
    $resultats = [];

    // Parcourir chaque élève
    foreach ($listeElevesArr as $eleve) {

        // Calculer la somme des a_payer où autreref est 2 et le matricule correspond à celui de l'élève
        $somme = Scolarite::where('AUTREF', 2)
                          ->where('MATRICULE', $eleve->MATRICULE)
                          ->sum('MONTANT');
        
                          // dd($somme);

          $RESTE = $eleve->ARRIERE - $somme;
        // Ajouter le résultat au tableau
        $resultats[$eleve->MATRICULE] = [
          'NOM' => $eleve->NOM,
          'PRENOM' => $eleve->PRENOM,
          'CLASSE' => $eleve->CODECLAS,
          'ARRIERE' => $eleve->ARRIERE,
          'PAYE' => $somme,
          'RESTE' => $RESTE,
        ];
    }    

    $totalDues = 0;
    $totalPayes = 0;
    $totalRestes = 0;

    foreach ($resultats as $resultat) {
        $totalDues += $resultat['ARRIERE'];
        $totalPayes += $resultat['PAYE'];
        $totalRestes += $resultat['RESTE'];
    }
    // dd($resultats);
    return view('pages.inscriptions.etatdesarrieresinscrits')->with('resultats', $resultats)->with('totalDues', $totalDues)->with('totalPayes', $totalPayes)->with('totalRestes', $totalRestes);
  } 
  
  public function eleveparclasse(){
    // $classesAExclure = ['NON', 'DELETE'];

    // $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();
    $classes = Classes::where('TYPECLASSE', 1)->get();
      return view('pages.inscriptions.eleveparclasse')->with('classes', $classes);
}



public function eleveparclassespecifique($classeCode)
{
    $CODECLASArray = explode(',', $classeCode);

    $eleves = Eleve::orderBy('NOM', 'asc')->get();
    $classesAExclure = ['NON', 'DELETE'];

    $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();
    $fraiscontrat = Paramcontrat::first(); 

    $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

    // Filtrer les élèves en fonction des classes sélectionnées
    $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
        ->whereIn('CODECLAS', $CODECLASArray)
        // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
        ->orderBy('NOM', 'asc')
        // ->groupBy('CODECLAS')
        ->get();
        // Requête pour récupérer les élèves avec l'effectif total, le nombre de filles et le nombre de garçons par classe
        $statistiquesClasses = Eleve::whereIn('MATRICULE', $contratValideMatricules)
        ->whereIn('CODECLAS', $CODECLASArray)
        ->select(
            'CODECLAS',
            // Effectif total
            DB::raw('COUNT(*) as total'),
            // Nombre de garçons
            DB::raw('SUM(CASE WHEN SEXE = 1 THEN 1 ELSE 0 END) as total_garcons'),
            // Nombre de filles
            DB::raw('SUM(CASE WHEN SEXE = 2 THEN 1 ELSE 0 END) as total_filles'),
            // Nombre de redoublants
            DB::raw('SUM(CASE WHEN STATUT = 1 THEN 1 ELSE 0 END) as total_redoublants'),
            // Nombre de redoublants garçons
            DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_redoublants'),
            // Nombre de redoublants filles
            DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_redoublants'),
            // Nouveaux ou transférés (statutG = 1 pour nouveaux, statutG = 3 pour transférés)
            DB::raw('SUM(CASE WHEN statutG = 1 THEN 1 ELSE 0 END) as total_nouveaux'),
            DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_nouveaux'),
            DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_nouveaux'),
            DB::raw('SUM(CASE WHEN statutG = 3 THEN 1 ELSE 0 END) as total_transferes'),
            DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_transferes'),
            DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_transferes'),
            // Anciens (statutG = 2)
            DB::raw('SUM(CASE WHEN statutG = 2 THEN 1 ELSE 0 END) as total_anciens'),
            DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_anciens'),
            DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_anciens')
        )
        ->groupBy('CODECLAS')
        ->get();
        // requette pour recuperer le nombre d'eleve par codereduction
        $reductionsParClasse = DB::table('eleve')
        ->join('reduction', 'eleve.CodeReduction', '=', 'reduction.CodeReduction') // Liaison avec la table des réductions
        ->whereIn('eleve.MATRICULE', $contratValideMatricules)
        ->whereIn('eleve.CODECLAS', $CODECLASArray)
        ->select(
            'eleve.CODECLAS', 
            'reduction.libelleReduction', // Nom de la réduction
            DB::raw('COUNT(*) as total') // Nombre d'élèves ayant cette réduction
        )
        ->groupBy('eleve.CODECLAS', 'reduction.libelleReduction')
        ->get();
        // requette pour grouper les eleve par classe
        $elevesGroupes = $filterEleves->groupBy('CODECLAS');



    return view('pages.inscriptions.eleveparclasse1')
        ->with("filterEleve", $filterEleves)
        ->with('classe', $classes)
        ->with('eleve', $eleves)
        ->with('elevesGroupes', $elevesGroupes)
        ->with('statistiquesClasses', $statistiquesClasses)
        ->with('reductionsParClasse', $reductionsParClasse)
        ->with('fraiscontrats', $fraiscontrat);
}

public function retardpaiementclasse(){

  // $classesAExclure = ['NON', 'DELETE'];

  // $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();

  $classes = Classes::where('TYPECLASSE', 1)->get();

  return view('pages.inscriptions.retardpaiementclasse')->with('classes', $classes);
}

public function rpaiementclassespecifique($classeCode) {
  $CODECLASArray = explode(',', $classeCode);

  // $classesAExclure = ['NON', 'DELETE'];

  $classes = Classes::where('TYPECLASSE', 1)->get();

  $contratValideMatricules = Eleve::whereIn('CODECLAS', $CODECLASArray)->pluck('MATRICULE');

  // Filtrer les élèves en fonction des classes sélectionnées
  $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
      ->whereIn('CODECLAS', $CODECLASArray)
      ->orderBy('NOM', 'asc')
      ->get()
      ->keyBy('MATRICULE');
     
    
  // recuperation des donne liee au echeance de chaque eleves
      $donneinancieress = Echeance::whereIn('MATRICULE', $contratValideMatricules)
      ->select(
          'MATRICULE',
          DB::raw('COUNT(*) as nbecheance'),    // Nombre d'échéances par élève
          DB::raw('SUM(APAYER) as montant_du'), // Somme de la colonne APAYER
          DB::raw('MAX(DATEOP) as derniere_date') // Dernière date d'échéance
      )
      ->groupBy('MATRICULE')
      ->get()
      ->keyBy('MATRICULE'); // Indexer par matricule

  

  // recuperation des donne liee au paiement de chaque eleves
    $donneesScolarite = Scolarite::whereIn('MATRICULE',  $contratValideMatricules)
      ->select(
          'MATRICULE', // Groupe par élève
          DB::raw('SUM(CASE WHEN VALIDE = 1 THEN MONTANT ELSE 0 END) as total_montant'), // Somme conditionnelle
          DB::raw('MAX(DATEOP) as derniere_datescolarit') // Dernière date de paiement
      )
      ->groupBy('MATRICULE') // Grouper les résultats par matricule d'élève
      ->get()
      ->keyBy('MATRICULE'); // Indexer par matricule

// PREMIERE METHODE (FUSIONNER LES DEUX TABLEAU EN FONCTION DU MATRICULE)
  // Fusioner les deux tableaux
  
  $combinedData = [];

  foreach ($donneinancieress as $matricule => $donneeFinanciere) {
      $combinedData[$matricule] = [
          'MATRICULE' => $matricule,
          'NOM' => $filterEleves[$matricule]->NOM,
          'PRENOM' => $filterEleves[$matricule]->PRENOM,
          'CODECLAS' => $filterEleves[$matricule]->CODECLAS,
          'nbecheance' => $donneeFinanciere->nbecheance,
          'montant_du' => $donneeFinanciere->montant_du,
          'derniere_date_echeance' => $donneeFinanciere->derniere_date ?? null,
          'total_montant' => $donneesScolarite[$matricule]->total_montant ?? 0, // Montant total de la scolarité
          'derniere_date_scolarite' => $donneesScolarite[$matricule]->derniere_datescolarit ?? null, // Dernière date de scolarité
          'difference' => ($donneeFinanciere->montant_du) - ($donneesScolarite[$matricule]->total_montant  ?? 0)
      ];
  }

  // Utiliser collect() pour créer une collection
  $listeGrouper = collect($combinedData)->groupBy('CODECLAS');
  // $listeGrouper = $combinedData->groupBy('CODECLAS');


  // dd($listeGrouper);


// DEUXIEME METHODE (UTILISER DES JOINTURES)

    // $combinedData = DB::table('echeance')
    // ->join('scolarit', 'echeance.MATRICULE', '=', 'scolarit.MATRICULE')
    // ->join('eleve', 'echeance.MATRICULE', '=', 'eleve.MATRICULE')
    // ->whereIn('echeance.MATRICULE', $contratValideMatricules)
    // ->select(
    //     'echeance.MATRICULE',
    //      'eleve.NOM',
    //      'eleve.CODECLAS',
    //      'eleve.PRENOM',
    //     DB::raw('COUNT(echeance.MATRICULE) as nbecheance'), // Nombre d'échéances
    //     DB::raw('SUM(echeance.APAYER) as montant_du'),      // Somme APAYER
    //     DB::raw('MAX(echeance.DATEOP) as derniere_date_echeance'), // Dernière date d'échéance
    //     DB::raw('SUM(CASE WHEN scolarit.VALIDE = 1 THEN scolarit.MONTANT ELSE 0 END) as total_montant'), // Somme conditionnelle scolarité
    //     DB::raw('MAX(scolarit.DATEOP) as derniere_date_scolarit') // Dernière date de paiement scolarité
    // )
    // ->groupBy('echeance.MATRICULE', 'eleve.CODECLAS')
    // ->get();


    return view('pages.inscriptions.retardpaiementclasse1')
    ->with("classeCode", $classeCode)
    ->with('classes', $classes)
    ->with('listeGrouper', $listeGrouper);


}

public function situationfinanceclasse() {
  $classes = Classes::where('TYPECLASSE', 1)->get();
  return view('pages.inscriptions.situationfinanceclasse')->with('classes', $classes);
}

public function sfinanceclassespecifique($classeCode) {

  // logique pour la lettre de relance

  $CODECLASArray = explode(',', $classeCode);

  // $classesAExclure = ['NON', 'DELETE'];

  $classes = Classes::where('TYPECLASSE', 1)->get();

  $contratValideMatricules = Eleve::whereIn('CODECLAS', $CODECLASArray)->pluck('MATRICULE');

      // Filtrer les élèves en fonction des classes sélectionnées
      $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
          ->whereIn('CODECLAS', $CODECLASArray)
          ->select(
              'MATRICULE', 
              'NOM', 
              'PRENOM', 
              'CODECLAS', 
              DB::raw('FRAIS1 + FRAIS2 + FRAIS3 + FRAIS4 as total_frais'), // Somme des colonnes frais1, frais2 et frais3
              DB::raw('APAYER + FRAIS1 + FRAIS2 + FRAIS3 + FRAIS4 + ARRIERE as total_tous'), // Somme des colonnes frais1, frais2 et frais3
              DB::raw('COUNT(*) as effectif'), 
          )
          ->orderBy('NOM', 'asc')
          ->groupBy('MATRICULE')
          ->get()
          ->keyBy('MATRICULE');

      // donne echeance
      $donneEcheanceEleve = Echeance::whereIn('MATRICULE', $contratValideMatricules)
          ->select(
              'MATRICULE',
              DB::raw('SUM(APAYER) as totalapayer'), // Somme de la colonne APAYER
              DB::raw('SUM(ARRIERE) as totalarriere'), // Somme de la colonne APAYER
          )
          ->groupBy('MATRICULE')
          ->get()
          ->keyBy('MATRICULE'); 


      // donne echeance
      $donneeScolariteEleve = Scolarite::whereIn('MATRICULE',  $contratValideMatricules)
          ->select(
              'MATRICULE', // Groupe par élève
              DB::raw('SUM(CASE WHEN VALIDE = 1 AND AUTREF = 1 THEN MONTANT ELSE 0 END) as total_scolarite'), // Somme conditionnelle
              DB::raw('SUM(CASE WHEN VALIDE = 1 AND AUTREF = 2 THEN MONTANT ELSE 0 END) as total_arriere'), // Somme conditionnelle
              DB::raw('SUM(CASE WHEN VALIDE = 1 THEN MONTANT ELSE 0 END) as total_all'), // Somme conditionnelle
              DB::raw('SUM(CASE WHEN VALIDE = 1 AND AUTREF IN (3, 4, 5, 6) THEN MONTANT ELSE 0 END) as total_autrefrais'), // Somme pour AUTREF 3, 4, 5
              DB::raw('MAX(DATEOP) as derniere_datescolarit') // Dernière date de paiement

          )
          ->groupBy('MATRICULE') // Grouper les résultats par matricule d'élève
          ->get()
          ->keyBy('MATRICULE');

  // dd($donneEcheanceEleve);

  $donneSituationFinanciere = [];

  foreach ($filterEleves as $matricule => $filterEleve) {
      $donneSituationFinanciere[$matricule] = [
          'MATRICULE' => $matricule,
          'NOM' => $filterEleve->NOM,
          'PRENOM' => $filterEleve->PRENOM,
          'CODECLAS' => $filterEleve->CODECLAS,
          'reste_echeance' => ($donneEcheanceEleve[$matricule]->totalapayer ?? 0) - ($donneeScolariteEleve[$matricule]->total_scolarite ?? 0),
          'reste_arriere' => ($donneEcheanceEleve[$matricule]->totalarriere ?? 0) - ($donneeScolariteEleve[$matricule]->total_arriere ?? 0),
          'reste_autre_frais' => ($filterEleve->total_frais ?? 0) - ($donneeScolariteEleve[$matricule]->total_autrefrais ?? 0),
          'total_du_hors_echeancier' => $filterEleve->total_tous ?? 0,
          'total_tous_scolarite' => $donneeScolariteEleve[$matricule]->total_all ?? 0,
          'derniere_date_scolarite' => $donneeScolariteEleve[$matricule]->derniere_datescolarit ?? null, // Dernière date de scolarité
      ];
  }

  $donneSituationFinanciereGroupe = collect($donneSituationFinanciere)->groupBy('CODECLAS');

  // Calcul de l'effectif et de la somme total_du_hors_echeancier par classe
  $resultatParClasse = $donneSituationFinanciereGroupe->map(function ($eleves, $classe) {
    $recouvrement = ($eleves->sum('total_du_hors_echeancier') - $eleves->sum('total_tous_scolarite')) / $eleves->sum('total_du_hors_echeancier');
    $pourcentageRecouvrement = round((1 - $recouvrement) * 100 , 2);

    return [
        'effectif' => $eleves->count(), // Effectif par classe
        'total_du_hors_echeancier' => $eleves->sum('total_du_hors_echeancier'), // Somme total_du_hors_echeancier par classe
        'total_paye' => $eleves->sum('total_tous_scolarite'), // Somme total_du_hors_echeancier par classe
        'reste' => $eleves->sum('total_du_hors_echeancier') - $eleves->sum('total_tous_scolarite'), // 
        'pourcentage_recouvrement' =>  $pourcentageRecouvrement,  
    ];
  });

  // dd($donneSituationFinanciereGroupe);


  // logique pour la lettre de relance

  // 


  $donneEcheanceEleve1 = Echeance::whereIn('MATRICULE', $contratValideMatricules)
  ->select('MATRICULE', 'DATEOP', 'APAYER', 'ARRIERE')
  ->get()
  ->groupBy('MATRICULE');

// dd($donneEcheanceEleve1);
// donne echeance
$donneeScolariteEleve1 = Scolarite::whereIn('MATRICULE',  $contratValideMatricules)
  ->select(
      'MATRICULE', // Groupe par élève
      DB::raw('SUM(CASE WHEN VALIDE = 1 AND AUTREF = 1 THEN MONTANT ELSE 0 END) as total_scolarite'), // Somme conditionnelle
      DB::raw('SUM(CASE WHEN VALIDE = 1 THEN MONTANT ELSE 0 END) as total_all'), // Somme conditionnelle

  )
  ->groupBy('MATRICULE') // Grouper les résultats par matricule d'élève
  ->get()
  ->keyBy('MATRICULE');

  $donneRelance = [];

  foreach ($filterEleves as $matricule => $filterEleve) {

      $infoparamcontrat = Paramcontrat::first();
      $anneencours = $infoparamcontrat->anneencours_paramcontrat;
      $annesuivante = $anneencours + 1;
      $annescolaire = $anneencours.'-'.$annesuivante;

      // Vérifier le typeecheancier de la classe de l'élève
      $infoClasseConcerne = Classes::where('CODECLAS', $filterEleve->CODECLAS)->first();
      $typeecheancier = $infoClasseConcerne->TYPEECHEANCIER;
  
      // Initialiser le montant total payé pour cet élève
      $montantPayer = 0;
  
      // Si c'est le premier passage, initialise `montantPayer` à la valeur correcte
      if ($typeecheancier == 1) {
          $montantPayer = $donneeScolariteEleve1[$matricule]->total_scolarite ?? 0; // Utiliser la colonne total_scolarite
      } elseif ($typeecheancier == 2) {
          $montantPayer = $donneeScolariteEleve1[$matricule]->total_all ?? 0; // Utiliser la colonne total_all
      }
  
      // Récupérer toutes les lignes d'échéance pour cet élève
      $echeances = $donneEcheanceEleve1[$matricule] ?? collect(); // Collection d'échéances
      // dd($echeances);
      // Boucle à travers chaque échéance de cet élève
      foreach ($echeances as $echeance) {
          $montantAPayer = $echeance->APAYER ?? 0; // Montant à payer de la colonne APAYER
  
          // Si l'élève a payé plus que l'échéance à payer
          if ($montantPayer >= $montantAPayer) {
              $resteAPayer = 0; // Il a déjà payé ou payé en excès pour cette échéance
              $montantPayer -= $montantAPayer; // Déduire le montant dû de son paiement total
          } else {
              // Si l'élève n'a pas assez payé pour couvrir l'échéance
              $resteAPayer = $montantAPayer - $montantPayer; // Ce qui reste à payer
              $montantPayer = 0; // Il n'a plus de crédit après cette échéance
          }
  
          // Ajouter les données dans le tableau final
          $donneRelance[$matricule][] = [
              'MATRICULE' => $matricule,
              'NOM' => $filterEleve->NOM,
              'PRENOM' => $filterEleve->PRENOM,
              'CODECLAS' => $filterEleve->CODECLAS,
              'date_echeance' => $echeance->DATEOP ?? null,
              'montant_a_payer' => $montantAPayer,
              'montant_payer' => $montantAPayer - $resteAPayer, // Ce qu'il a effectivement payé pour cette échéance
              'reste_a_payer' => $resteAPayer, // Ce qui reste à payer pour cette échéance
              'annescolaire' => $annescolaire, // Ce qui reste à payer pour cette échéance
          ];
      }
  }

  // dd($donneRelance);


  return view ('pages.inscriptions.situationfinanceclasse1')
    ->with('classes', $classes)
    ->with('donneSituationFinanciereGroupe', $donneSituationFinanciereGroupe)
    ->with('resultatParClasse', $resultatParClasse)
    ->with('donneRelance', $donneRelance)
    ->with('classeCode', $classeCode);
}


public function recouvrementGenerale() {
  return view('pages.inscriptions.recouvrementGenerale');
}

public function recouvrementgeneral(Request $request){
      $dateDebut = $request->query('debut');
      $dateFin = $request->query('fin');
      $TypeClasse = $request->query('typeclasse');
      $groupeClasse = $request->query('groupe');
      // dd($groupe);


      $classesDuGroupe = Classesgroupeclass::where('LibelleGroupe', 'Standard')->pluck('CODECLAS')->toArray();
      $classeDuType = Classes::Where('TYPECLASSE', $TypeClasse)->pluck('CODECLAS')->toArray();

      // Récupérer l'intersection des deux tableaux
      $classesCommunes = array_intersect($classesDuGroupe, $classeDuType);
      
      // dd($classesCommunes);

      // Initialisation du tableau avec toutes les classes et des valeurs par défaut de 0
      $sommeParClasse = array_fill_keys($classesCommunes, ['total_a_payer' => 0, 'total_arriere' => 0, 'total_frais1' => 0, 'total_frais2' => 0, 'total_frais3' => 0 , 'total_frais4' => 0 , 'totalPercu' => 0]);

      // Récupérer les sommes pour chaque classe avec des élèves
      $sommesParClasseAvecEleves = Eleve::whereIn('CODECLAS', $classesCommunes)
          ->select(
              'CODECLAS', 
              DB::raw('SUM(APAYER) as total_a_payer'),
              DB::raw('SUM(ARRIERE) as total_arriere'),
              DB::raw('SUM(FRAIS1) as total_frais1'),
              DB::raw('SUM(FRAIS2) as total_frais2'),
              DB::raw('SUM(FRAIS3) as total_frais3'),
              DB::raw('SUM(FRAIS4) as total_frais4'),
          )
          ->groupBy('CODECLAS')
          ->get();

      // dd($sommeParClasse);



      // Initialiser le tableau pour les sommes par classe
      $sommeParClasse = [];

      // Récupérer les élèves et faire la somme des montants pour chaque classe
      foreach ($classesCommunes as $classe) {
          // Récupérer les élèves de la classe
          $eleves = Eleve::where('CODECLAS', $classe)->pluck('MATRICULE')->toArray();

          // Initialiser les montants à 0 pour chaque classe (au cas où il n'y a pas d'élèves ou de montants)
          $sommeScolarite = 0;
          $sommeArriere = 0;
          $sommeFrais1 = 0;
          $sommeFrais2 = 0;
          $sommeFrais3 = 0;
          $sommeFrais4 = 0;

          // Si la classe a des élèves, on calcule les sommes
          if (!empty($eleves)) {
              // Calculer les montants uniquement si des élèves existent pour la classe
              $sommeScolarite = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 1)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');

              $sommeArriere = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 2)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');

              $sommeFrais1 = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 3)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');

              $sommeFrais2 = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 4)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');

              $sommeFrais3 = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 5)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');

              $sommeFrais4 = Scolarite::whereIn('MATRICULE', $eleves)
                  ->where('AUTREF', 6)
                  ->where('VALIDE', 1)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->sum('MONTANT');
          }

          // Ajouter les montants (ou 0 si aucune somme n'a été trouvée) pour chaque classe
          $sommeParClasse[$classe] = [
              'scolaritePercu' => $sommeScolarite,
              'arrierePercu' => $sommeArriere,
              'frais1Percu' => $sommeFrais1,
              'frais2Percu' => $sommeFrais2,
              'frais3Percu' => $sommeFrais3,
              'frais4Percu' => $sommeFrais4,
              'totalPercu' => $sommeScolarite + $sommeArriere + $sommeFrais1 + $sommeFrais2 + $sommeFrais3 + $sommeFrais4
          ];
      }


      // dd($sommeParClasse);


      // Mettre à jour le tableau avec les sommes réelles des classes qui ont des élèves
      foreach ($sommesParClasseAvecEleves as $somme) {

          $totalFrais = $somme->total_a_payer + $somme->total_arriere + $somme->total_frais1 + $somme->total_frais2 + $somme->total_frais3 + $somme->total_frais4;

          $classe = $somme->CODECLAS;
          $sommeParClasse[$classe]['total_a_payer'] = $somme->total_a_payer;
          $sommeParClasse[$classe]['total_arriere'] = $somme->total_arriere;
          $sommeParClasse[$classe]['total_frais1'] = $somme->total_frais1;
          $sommeParClasse[$classe]['total_frais2'] = $somme->total_frais2;
          $sommeParClasse[$classe]['total_frais3'] = $somme->total_frais3;
          $sommeParClasse[$classe]['total_frais4'] = $somme->total_frais4;
          $sommeParClasse[$classe]['totalAPercevoir'] = $totalFrais;
          
      }


      // code de fudion des deux tableaux sommeParClasseAvecEleves et sommeParClasse

      foreach ($sommesParClasseAvecEleves as $somme) {
        $classe = $somme->CODECLAS;
        
        // Si la classe existe déjà dans le tableau $sommeParClasse, on fusionne les données
        if (isset($sommeParClasse[$classe])) {
            // Ajouter les données "à percevoir"
            $sommeParClasse[$classe]['total_a_payer'] = $somme->total_a_payer;
            $sommeParClasse[$classe]['total_arriere'] = $somme->total_arriere;
            $sommeParClasse[$classe]['total_frais1'] = $somme->total_frais1;
            $sommeParClasse[$classe]['total_frais2'] = $somme->total_frais2;
            $sommeParClasse[$classe]['total_frais3'] = $somme->total_frais3;
            $sommeParClasse[$classe]['total_frais4'] = $somme->total_frais4;
            
            // Calcul du total à percevoir
            $totalFrais = $somme->total_a_payer + $somme->total_arriere + 
                          $somme->total_frais1 + $somme->total_frais2 + 
                          $somme->total_frais3 + $somme->total_frais4;

            $sommeParClasse[$classe]['totalAPercevoir'] = $totalFrais;
            $sommeParClasse[$classe]['Reste'] = $totalFrais - $sommeParClasse[$classe]['totalPercu'];
        } else {
            // Si la classe n'existe pas encore dans $sommeParClasse, on initialise avec les valeurs "à percevoir"
            $totalFrais = $somme->total_a_payer + $somme->total_arriere + 
                          $somme->total_frais1 + $somme->total_frais2 + 
                          $somme->total_frais3 + $somme->total_frais4;

            $sommeParClasse[$classe] = [
                'total_a_payer' => $somme->total_a_payer,
                'total_arriere' => $somme->total_arriere,
                'total_frais1' => $somme->total_frais1,
                'total_frais2' => $somme->total_frais2,
                'total_frais3' => $somme->total_frais3,
                'total_frais4' => $somme->total_frais4,
                'totalAPercevoir' => $totalFrais,
                'Reste' => $totalFrais - 0,
            ];
        }
      }




      // dd($sommeParClasse);

  return view('pages.inscriptions.recougenerale')->with('sommeParClasse', $sommeParClasse)->with('dateDebut', $dateDebut)->with('dateFin', $dateFin);    

}

// public function recouvrementParType(Request $request) {
//   $dateDebut = $request->query('debut');
//   $dateFin = $request->query('fin');
//   $TypeClasse = $request->query('typeclasse');
//   $groupeClasse = $request->query('groupe');

//   // Récupérer les classes qui répondent aux critères de typeclasse et groupe
//   $classesFiltrees = Classes::where('TYPECLASSE', $TypeClasse)
//       ->whereIn('CODECLAS', function($query) use ($groupeClasse) {
//           $query->select('CODECLAS')
//                 ->from('classes_groupeclasse')
//                 ->where('LibelleGroupe', $groupeClasse);
//       })
//       ->get();

//   // Débogage : vérifier les classes filtrées
//   if ($classesFiltrees->isEmpty()) {
//       dd("Aucune classe ne répond aux critères de filtrage", $classesFiltrees);
//   }

//   // Regrouper ces classes par type d'enseignement
//   $classesParTypeEnseignement = $classesFiltrees->groupBy('TYPEENSEIG');

//   // dd($classesParTypeEnseignement);
//   // Initialiser le tableau pour stocker les données finales
//   $donneesRegroupees = [];

//   foreach ($classesParTypeEnseignement as $typeEnseignementId => $classesDuType) {
//       // Récupérer les informations sur le type d'enseignement
//       $typeEnseignement = Typeenseigne::where('idenseign', $typeEnseignementId)->first();

//       // Initialiser les données pour ce type d'enseignement
//       $sommeParClasse = [];

//       foreach ($classesDuType as $classe) {
//           // Récupérer les élèves dans la classe
//           $eleves = Eleve::where('CODECLAS', $classe->CODECLAS)->pluck('MATRICULE')->toArray();

//           // Calculer les sommes pour la classe si des élèves sont présents
//           if (!empty($eleves)) {
//               $sommeScolarite = Scolarite::whereIn('MATRICULE', $eleves)
//                   ->where('AUTREF', 1)
//                   ->where('VALIDE', 1)
//                   ->whereBetween('DATEOP', [$dateDebut, $dateFin])
//                   ->sum('MONTANT');

//               $sommeArriere = Scolarite::whereIn('MATRICULE', $eleves)
//                   ->where('AUTREF', 2)
//                   ->where('VALIDE', 1)
//                   ->whereBetween('DATEOP', [$dateDebut, $dateFin])
//                   ->sum('MONTANT');

//               // Calculer les autres sommes comme pour les frais, etc.
//               $sommeFrais1 = Scolarite::whereIn('MATRICULE', $eleves)
//                   ->where('AUTREF', 3)
//                   ->where('VALIDE', 1)
//                   ->whereBetween('DATEOP', [$dateDebut, $dateFin])
//                   ->sum('MONTANT');

//               // Ajouter ces sommes pour la classe
//               $sommeParClasse[$classe->CODECLAS] = [
//                   'scolaritePercu' => $sommeScolarite,
//                   'arrierePercu' => $sommeArriere,
//                   'frais1Percu' => $sommeFrais1,
//                   'totalPercu' => $sommeScolarite + $sommeArriere + $sommeFrais1
//               ];
//           }
//       }

//       // Ajouter les données regroupées par type d'enseignement
//       $donneesRegroupees[$typeEnseignement->type] = [
//         'type' => $typeEnseignement->type,
//         'classes' => $sommeParClasse
//       ];
//     }
    
//     // Débogage final : vérifier les données regroupées
//     dd($donneesRegroupees);
// }


public function recouvrementParType(Request $request) {
  $dateDebut = $request->query('debut');
  $dateFin = $request->query('fin');
  $TypeClasse = $request->query('typeclasse');
  $groupeClasse = $request->query('groupe');

  // Récupérer les classes qui répondent aux critères de typeclasse et groupe
  $classesFiltrees = Classes::where('TYPECLASSE', $TypeClasse)
      ->whereIn('CODECLAS', function($query) use ($groupeClasse) {
          $query->select('CODECLAS')
                ->from('classes_groupeclasse')
                ->where('LibelleGroupe', $groupeClasse);
      })
      ->get();

  // Débogage : vérifier les classes filtrées
  if ($classesFiltrees->isEmpty()) {
      echo"Aucune classe ne répond aux critères de filtrage", $classesFiltrees;
  }

  // Regrouper ces classes par type d'enseignement
  $classesParTypeEnseignement = $classesFiltrees->groupBy('TYPEENSEIG');

  // Initialiser le tableau pour stocker les données finales
  $donneesRegroupees = [];

  foreach ($classesParTypeEnseignement as $typeEnseignementId => $classesDuType) {
      // Récupérer les informations sur le type d'enseignement
      $typeEnseignement = Typeenseigne::where('idenseign', $typeEnseignementId)->first();

      // Initialiser les données pour ce type d'enseignement
      $sommeParClasse = [];

      foreach ($classesDuType as $classe) {
          // Récupérer les élèves dans la classe
          $eleves = Eleve::where('CODECLAS', $classe->CODECLAS)->get();

          // Initialiser les sommes pour chaque classe
          $total_a_payer = 0;
          $total_arriere = 0;
          $total_frais1 = 0;
          $total_frais2 = 0;
          $total_frais3 = 0;
          $total_frais4 = 0;

          // Calculer les sommes pour chaque élève dans la classe
          foreach ($eleves as $eleve) {
              $total_a_payer += $eleve->APAYER;
              $total_arriere += $eleve->ARREARRE;  // Si vous avez un champ "ARREARRE" pour les arriérés
              $total_frais1 += $eleve->FRAIS1;     // Si vous avez un champ "FRAIS1"
              $total_frais2 += $eleve->FRAIS2;     // Si vous avez un champ "FRAIS2"
              $total_frais3 += $eleve->FRAIS3;     // Si vous avez un champ "FRAIS3"
              $total_frais4 += $eleve->FRAIS4;     // Si vous avez un champ "FRAIS4"
          }

          // Calcul des sommes perçues pour chaque type de frais
          $scolaritePercu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 1)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          $arrierePercu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 2)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          $frais1Percu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 3)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          $frais2Percu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 4)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          $frais3Percu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 5)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          $frais4Percu = Scolarite::whereIn('MATRICULE', $eleves->pluck('MATRICULE'))
              ->where('AUTREF', 6)
              ->where('VALIDE', 1)
              ->whereBetween('DATEOP', [$dateDebut, $dateFin])
              ->sum('MONTANT');

          // Calculer le total à percevoir pour la classe
          $totalAPercevoir = $total_a_payer + $total_arriere + $total_frais1 + $total_frais2 + $total_frais3 + $total_frais4;

          // Reste à percevoir (différence entre total à percevoir et total perçu)
          $reste = $totalAPercevoir - ($scolaritePercu + $arrierePercu + $frais1Percu + $frais2Percu + $frais3Percu + $frais4Percu);

          // Ajouter les données pour cette classe
          $sommeParClasse[$classe->CODECLAS] = [
              'scolaritePercu' => $scolaritePercu,
              'arrierePercu' => $arrierePercu,
              'frais1Percu' => $frais1Percu,
              'frais2Percu' => $frais2Percu,
              'frais3Percu' => $frais3Percu,
              'frais4Percu' => $frais4Percu,
              'totalPercu' => $scolaritePercu + $arrierePercu + $frais1Percu + $frais2Percu + $frais3Percu + $frais4Percu,
              'totalAPayer' => $total_a_payer,
              'totalArriere' => $total_arriere,
              'totalFrais1' => $total_frais1,
              'totalFrais2' => $total_frais2,
              'totalFrais3' => $total_frais3,
              'totalFrais4' => $total_frais4,
              'totalAPercevoir' => $totalAPercevoir,
              'reste' => $reste
          ];
      }

      // Ajouter les données regroupées par type d'enseignement
      $donneesRegroupees[$typeEnseignement->type] = [
          'type' => $typeEnseignement->type,
          'classes' => $sommeParClasse
      ];
  }

  // Débogage final : vérifier les données regroupées
  // dd($donneesRegroupees);
  return view('pages.inscriptions.recouParTypeenseign')->with('donneesRegroupees', $donneesRegroupees)->with('sommeParClasse', $sommeParClasse)->with('dateDebut', $dateDebut)->with('dateFin', $dateFin);
}









public function eleveparclasseessai() {
  $classeCode = Session::get('classeCode');

  $CODECLASArray = explode(',', $classeCode);

  $eleves = Eleve::orderBy('NOM', 'asc')->get();
  $classesAExclure = ['NON', 'DELETE'];

  $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();
  $fraiscontrat = Paramcontrat::first(); 

  $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

  // Filtrer les élèves en fonction des classes sélectionnées
  $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
      ->whereIn('CODECLAS', $CODECLASArray)
      // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
      ->orderBy('NOM', 'asc')
      // ->groupBy('CODECLAS')
      ->get();
      // Requête pour récupérer les élèves avec l'effectif total, le nombre de filles et le nombre de garçons par classe
      $statistiquesClasses = Eleve::whereIn('MATRICULE', $contratValideMatricules)
      ->whereIn('CODECLAS', $CODECLASArray)
      ->select(
          'CODECLAS',
          // Effectif total
          DB::raw('COUNT(*) as total'),
          // Nombre de garçons
          DB::raw('SUM(CASE WHEN SEXE = 1 THEN 1 ELSE 0 END) as total_garcons'),
          // Nombre de filles
          DB::raw('SUM(CASE WHEN SEXE = 2 THEN 1 ELSE 0 END) as total_filles'),
          // Nombre de redoublants
          DB::raw('SUM(CASE WHEN STATUT = 1 THEN 1 ELSE 0 END) as total_redoublants'),
          // Nombre de redoublants garçons
          DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_redoublants'),
          // Nombre de redoublants filles
          DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_redoublants'),
          // Nouveaux ou transférés (statutG = 1 pour nouveaux, statutG = 3 pour transférés)
          DB::raw('SUM(CASE WHEN statutG = 1 THEN 1 ELSE 0 END) as total_nouveaux'),
          DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_nouveaux'),
          DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_nouveaux'),
          DB::raw('SUM(CASE WHEN statutG = 3 THEN 1 ELSE 0 END) as total_transferes'),
          DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_transferes'),
          DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_transferes'),
          // Anciens (statutG = 2)
          DB::raw('SUM(CASE WHEN statutG = 2 THEN 1 ELSE 0 END) as total_anciens'),
          DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_anciens'),
          DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_anciens')
      )
      ->groupBy('CODECLAS')
      ->get();
      // requette pour recuperer le nombre d'eleve par codereduction
      $reductionsParClasse = DB::table('eleve')
      ->join('reduction', 'eleve.CodeReduction', '=', 'reduction.CodeReduction') // Liaison avec la table des réductions
      ->whereIn('eleve.MATRICULE', $contratValideMatricules)
      ->whereIn('eleve.CODECLAS', $CODECLASArray)
      ->select(
          'eleve.CODECLAS', 
          'reduction.libelleReduction', // Nom de la réduction
          DB::raw('COUNT(*) as total') // Nombre d'élèves ayant cette réduction
      )
      ->groupBy('eleve.CODECLAS', 'reduction.libelleReduction')
      ->get();
      // requette pour grouper les eleve par classe
      $elevesGroupes = $filterEleves->groupBy('CODECLAS');

      // dd($filterEleves);
  Session::put('fraiscontrats', $fraiscontrat);
  Session::put('fill', $filterEleves);

  return view ('pages.inscriptions.eleveparclasseessai')
  ->with("filterEleve", $filterEleves)
  ->with('classe', $classes)
  ->with('eleve', $eleves)
  ->with('elevesGroupes', $elevesGroupes)
  ->with('statistiquesClasses', $statistiquesClasses)
  ->with('reductionsParClasse', $reductionsParClasse)
  ->with('fraiscontrats', $fraiscontrat);
}
  
  public function listeselective(){
    return view('pages.inscriptions.listeselective');
  } 
  
  public function modifiereleve($MATRICULE){
    // Récupérer le dernier matricule existant
    $Matricule = Eleve::find($MATRICULE);
    $allClasse = Classes::all();
    $allReduction = Reduction::all();
    $allDepartement = Departement::all();
    $archive = Elevea::get();
    $alleleve = Eleveplus::where('MATRICULE', $MATRICULE)->first();
    $modifieleve = Eleve::where('MATRICULE', $MATRICULE)->first();
    return view('pages.inscriptions.modifiereleve', compact('allClasse', 'allReduction', 'allDepartement', 'archive', 'alleleve', 'Matricule', 'modifieleve'));
  }
  
  public function typesclasses(){
    return view ('pages.inscriptions.typesclasses');
  }
  
  public function promotions(){
    return view ('pages.inscriptions.promotions');
  }
  
  public function creerprofil(){
    
    // Récupérer le dernier code de reduction existant
    $lastCode = Reduction::orderBy('CodeReduction', 'desc')->pluck('CodeReduction')->first();
    
    // Générer le nouveau matricule
    if ($lastCode) {
      // En supposant que le matricule est de type numérique
      $newCode = (int)$lastCode + 1;
    } else {
      // Si aucun matricule n'existe encore, initialiser à un numéro de départ
      $newCode = 1;
    }
    $reductions = Reduction::all();
    return view ('pages.inscriptions.creerprofil')->with('reductions', $reductions)->with('newCode', $newCode);
  }
  
  private function convertToDecimal($percentage)
  {
    // Retirer le signe % et convertir en valeur décimale
    if (strpos($percentage, '%') !== false) {
      $percentage = str_replace('%', '', $percentage);
    }
    
    return floatval($percentage) / 100;
  }
  
  public function ajouterprofreduction(Request $request)
  {
      $reduction = new Reduction();
      $reduction->Codereduction = $request->input('Codereduction');
      $reduction->LibelleReduction = $request->input('LibelleReduction');
  
      // Déterminer le type de réduction
      if ($request->input('reductionType') == 2) { // Réduction fixe
          // Enregistrer les valeurs dans les colonnes de réduction fixe
          $reduction->typereduction = 'F';
          $reduction->Reduction_fixe_sco = $request->input('Reduction_scolarite');
          $reduction->Reduction_fixe_arriere = $request->input('Reduction_arriere');
          $reduction->Reduction_fixe_frais1 = $request->input('Reduction_frais1');
          $reduction->Reduction_fixe_frais2 = $request->input('Reduction_frais2');
          $reduction->Reduction_fixe_frais3 = $request->input('Reduction_frais3');
          $reduction->Reduction_fixe_frais4 = $request->input('Reduction_frais4');
      } else { // Réduction par pourcentage
          // Enregistrer les valeurs dans les colonnes de réduction par pourcentage
          $reduction->typereduction = 'P';
          $reduction->Reduction_scolarite = $this->convertToDecimal($request->input('Reduction_scolarite'));
          $reduction->Reduction_arriere = $this->convertToDecimal($request->input('Reduction_arriere'));
          $reduction->Reduction_frais1 = $this->convertToDecimal($request->input('Reduction_frais1'));
          $reduction->Reduction_frais2 = $this->convertToDecimal($request->input('Reduction_frais2'));
          $reduction->Reduction_frais3 = $this->convertToDecimal($request->input('Reduction_frais3'));
          $reduction->Reduction_frais4 = $this->convertToDecimal($request->input('Reduction_frais4'));
      }
  
      $reduction->mode = $request->input('mode');
      $reduction->save();
  
      return back()->with('status', 'Profil de réduction créé avec succès.');
  }

  public function modifreductions(Request $request)
  {
      // Validation des données
      // $request->validate([
      //     'codeReduction' => 'required|string|max:255',
      //     'libelleReduction' => 'required|string|max:255',
      //     'reductionScolarite' => 'required|numeric',
      //     'reductionArriere' => 'required|numeric',
      //     'reductionFrais1' => 'required|numeric',
      //     'reductionFrais2' => 'required|numeric',
      //     'reductionFrais3' => 'required|numeric',
      //     'reductionFrais4' => 'required|numeric',
      //     'reductionApplication' => 'required|integer',
      // ]);

      $codereduc = $request->input('CodeReduction');
      // Récupérer la réduction à modifier
      $reduction = Reduction::where('CodeReduction', $codereduc)->first();
      // dd($reduction);
      // Mettre à jour les champs de la réduction
      if ($request->input('reductionType') == 2) { // Réduction fixe
          // Enregistrer les valeurs dans les colonnes de réduction fixe
          $reduction->typereduction = 'F';
          $reduction->Reduction_fixe_sco = $request->input('Reduction_scolarite');
          $reduction->Reduction_fixe_arriere = $request->input('Reduction_arriere');
          $reduction->Reduction_fixe_frais1 = $request->input('Reduction_frais1');
          $reduction->Reduction_fixe_frais2 = $request->input('Reduction_frais2');
          $reduction->Reduction_fixe_frais3 = $request->input('Reduction_frais3');
          $reduction->Reduction_fixe_frais4 = $request->input('Reduction_frais4');
      } else { // Réduction par pourcentage
          // Enregistrer les valeurs dans les colonnes de réduction par pourcentage
          $reduction->typereduction = 'P';
          $reduction->Reduction_scolarite = $this->convertToDecimal($request->input('Reduction_scolarite'));
          $reduction->Reduction_arriere = $this->convertToDecimal($request->input('Reduction_arriere'));
          $reduction->Reduction_frais1 = $this->convertToDecimal($request->input('Reduction_frais1'));
          $reduction->Reduction_frais2 = $this->convertToDecimal($request->input('Reduction_frais2'));
          $reduction->Reduction_frais3 = $this->convertToDecimal($request->input('Reduction_frais3'));
          $reduction->Reduction_frais4 = $this->convertToDecimal($request->input('Reduction_frais4'));
      }
  
      $reduction->mode = $request->input('mode');
      $reduction->save();

      // Sauvegarder les modifications

      // Retourner une réponse JSON
      return back()->with('status', 'Réduction modifiée avec succès.');
  }

  
  public function delreductions($codeRedu) {
    // Trouver la réduction par CodeReduction
    $reduct = Reduction::where('CodeReduction', $codeRedu)->first();
    // dd($reduct);
    // Supprimer la réduction
    $reduct->delete();
    
    // Rediriger avec un message de succès
    return back()->with('status', 'Réduction supprimée avec succès.');
  }
  
  public function paramcomposantes(){
    return view ('pages.inscriptions.paramcomposantes');
  }
  
  public function duplicatarecu(){
    return view ('pages.inscriptions.duplicatarecu');
  }
  
  public function transfert(){
    return view ('pages.inscriptions.transfert');
  }
  
  public function importer(){
    return view ('pages.inscriptions.importer');
  }
  
  public function verrouillage(){
    return view ('pages.inscriptions.verrouillage');
  }
  
  public function recalculereffectifs() {
    $eleves = Eleve::with('classe', 'serie')->get();
    $promotions = Promo::all();
    $series = Serie::all();
    $effectifsParClasse = $eleves->groupBy('CODECLAS')->map(function($groupe) {
        return [
            'total' => $groupe->count(),
            'garcons' => $groupe->where('SEXE', 1)->count(),
            'filles' => $groupe->where('SEXE', 2)->count(),
            'redoublants' => $groupe->where('STATUTG', 1)->count(),
        ];
    });
    $effectifsParPromotion = $promotions->mapWithKeys(function($promo) use ($eleves) {
        $classes = Classes::where('CODEPROMO', $promo->CODEPROMO)->pluck('CODECLAS'); 
        $elevesPromo = $eleves->whereIn('CODECLAS', $classes);

        return [
            $promo->CODEPROMO => [ 
                'total' => $elevesPromo->count(),
                'totalClasses' => $classes->count(),
                'garcons' => $elevesPromo->where('SEXE', 1)->count(),
                'filles' => $elevesPromo->where('SEXE', 2)->count(),
            ],
        ];
    });
    
    $effectifsParSerie = $series->mapWithKeys(function($serie) use ($eleves) {
      $elevesSerie = $eleves->where('SERIE', $serie->SERIE);

      return [
          $serie->SERIE => [ // Assurez-vous que 'name' est le bon attribut pour le nom de la série
              'totalClasses' => $elevesSerie->pluck('CODECLAS')->unique()->count(),
              'total' => $elevesSerie->count(),
              'garcons' => $elevesSerie->where('SEXE', 1)->count(),
              'filles' => $elevesSerie->where('SEXE', 2)->count(),
          ],
      ];
  });
    
    $effectifsAlphabetiques = [];
    foreach ($eleves as $eleve) {
        $premiereLettre = strtoupper(substr($eleve->NOM, 0, 1));
        if (!isset($effectifsAlphabetiques[$premiereLettre])) {
            $effectifsAlphabetiques[$premiereLettre] = 0;
        }
        $effectifsAlphabetiques[$premiereLettre]++;
    }

    return view('pages.inscriptions.recalculereffectifs', compact('effectifsParClasse', 'effectifsParPromotion', 'effectifsParSerie', 'effectifsAlphabetiques'));
}

  
  public function enquetesstatistiques(){
    return view ('pages.inscriptions.enquetesstatistiques');
  }
  
  public function etatdelacaisse(){
    $chapitres = Chapitre::all();
    $journals = Journal::all();
    
    
    return view ('pages.inscriptions.etatdelacaisse',compact('chapitres','journals'));
  }   

public function filterEtatDeLaCaisse(Request $request) {
    // Récupérer les filtres
    $journals = Journal::all();
    $chapitre = $request->input('chapitre');
    $type = $request->input('type');
    $month = $request->input('month');
    $dateArret = $request->input('date_arret');
    $totalRecettes = $journals->sum('DEBIT'); // Calculer le total des recettes

    // Vérifier si le type est "Relevé mensuel des ordres de recettes"
    if ($type == "Relevé mensuel des ordres de recettes") {
      // Filtrer les journaux en fonction du chapitre, du mois, et de la date d'arrêt
      $journals = Journal::query();

      if ($chapitre) {
          $journals->where('CHAPITRE', $chapitre);
      }

      if ($month) {
          $journals->whereMonth('DATEOP', '=', date('m', strtotime($month)))
                   ->whereYear('DATEOP', '=', date('Y', strtotime($month)));
      }

      if ($dateArret) {
          $journals->whereDate('DATEOP', '<=', $dateArret);
      }

      $journals = $journals->get();

      // Passer les journaux filtrés et les détails au rapport
      return view('pages.inscriptions.etatdelacaisse_print', compact('journals', 'type', 'month', 'dateArret', 'totalRecettes', 'chapitre'));
    }

    if ($type == "Bordereaux des paiements") {
      return redirect()->back()->withErrors('Non disponible');
    }

    if ($type == "Bordereau de caisse") {
      return redirect()->back()->withErrors('Non disponible');
    }

    return redirect()->back();
}


  
  public function situationfinanciereglobale(){
    $eleves = Eleve::with('classe')->get(); // Récupérer tous les élèves avec leur classe
    $scolarites = Scolarite::all()->groupBy('MATRICULE'); // Regrouper les paiements par matricule

    $resultats = $eleves->map(function($eleve) use ($scolarites) {
        $montantPaye = $scolarites->get($eleve->MATRICULE, collect())->sum('MONTANT'); // Somme des montants payés
        $montantAPayer = $eleve->APAYER; // Montant à payer de l'élève
        $reste = $montantAPayer - $montantPaye; // Calculer le reste à payer

        return [
            'MATRICULE' => $eleve->MATRICULE,
            'NOM' => $eleve->NOM,
            'PRENOM' => $eleve->PRENOM,
            'CLASSE' => $eleve->CODECLAS,
            'PAYE' => $montantPaye, // Ajout du montant payé
            'RESTE' => $reste > 0 ? $reste : 0, // Assurer que le reste ne soit pas négatif
        ];
    });

    // Calcul des totaux
    $totalAPayer = $resultats->sum('RESTE') + $resultats->sum('PAYE');
    $totalPaye = $resultats->sum('PAYE');
    $totalReste = $resultats->sum('RESTE');

    return view('pages.inscriptions.situationfinanciereglobale', compact('resultats', 'totalAPayer', 'totalPaye', 'totalReste'));
}
  
  public function pointderecouvrement(){
    return view ('pages.inscriptions.pointderecouvrement');
  }
  public function paiementdesnoninscrits(){
    return view ('pages.inscriptions.paiementdesnoninscrits');
  }
  
      public function paiementeleve($matricule)
    {
        if (Session::has('account')) {
            // Retrieve the student details
            $eleve = Eleve::where('MATRICULE', $matricule)->first();
            $scolarite = Scolarite::where('MATRICULE', $matricule)->get();
            $libelle = Params2::first();
            $echeanche = Echeance::first();
            
            // Calculate the total amounts based on AUTREF
            $totalArriere = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '2')
                ->sum('MONTANT');
    
            $totalScolarite = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '1')
                ->sum('MONTANT');
    
            $totalLibelle1 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '3')
                ->sum('MONTANT');
    
            $totalLibelle2 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '4')
                ->sum('MONTANT');
    
            $totalLibelle3 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '5')
                ->sum('MONTANT');
            
            $totalLibelle4 = Scolarite::where('MATRICULE', $matricule)
              ->where('AUTREF', '6')
              ->sum('MONTANT');
    
            // Pass the totals along with other data to the view
            return view('pages.inscriptions.Paiement', compact(
                'eleve', 'scolarite', 'libelle', 
                'totalArriere', 'totalScolarite', 'totalLibelle1', 
                'totalLibelle2', 'totalLibelle3'
            ));
        }
        return redirect('/');
    }
    
    public function enregistrerPaiement(Request $request, $matricule)
    {
        $messages = [];
        $errors = [];
        $eleve = Eleve::where('MATRICULE', $matricule)->first();
        $parametrefacture = Params2::first();
        $ifuentreprise = $parametrefacture->ifu;
        $tokenentreprise = $parametrefacture->token;
        $taxe = $parametrefacture->taxe;
        $type = $parametrefacture->typefacture;

        // Vérifiez si l'élève existe
        if (!$eleve) {
            return redirect()
                ->back()
                ->withErrors(['L\'élève avec ce matricule n\'existe pas.'])
                ->withInput();
        }

        // Fonction pour obtenir ou générer un numéro unique
            $getNumero = function ($matricule, $dateOp) {
            $existingScolarite = Scolarite::where('MATRICULE', $matricule)->where('DATEOP', $dateOp)->first();
            
            // Si une entrée existe, retourner son numéro
            if ($existingScolarite) {
              return $existingScolarite->NUMERO;
            }
            
            // Sinon, générer un nouveau numéro basé sur le maximum existant
            return Scolarite::max('NUMERO') + 1; // Ajustement pour générer un nouveau numéro
          };
          
          // Enregistrer le montant de la scolarité si présent et supérieur à 0
          if ($request->filled('scolarite') && $request->input('scolarite') > 0) {
              $existingScolarite = Scolarite::where('MATRICULE', $matricule)
                  ->where('DATEOP', $request->input('date_operation'))
                  ->where('MONTANT', $request->input('scolarite'))
                  ->where('AUTREF', '1') // Scolarité
                  ->first();
  
              if ($existingScolarite) {
                  $errors[] = 'Un paiement de scolarité similaire existe déjà pour cet élève.';
              } else {
                  $scolarite = new Scolarite();
                  $scolarite->MATRICULE = $matricule;
                  $scolarite->DATEOP = $request->input('date_operation');
                  $scolarite->MODEPAIE = $request->input('mode_paiement');
                  $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                  $scolarite->ANSCOL = $eleve->anneeacademique;
                  $scolarite->NUMERO = $getNumero($matricule, $request->input('date_operation'));
                  $scolarite->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                  $scolarite->MONTANT = $request->input('scolarite');
                  $scolarite->AUTREF = '1'; // Scolarité
                  $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                  $scolarite->save();
  
                  // Enregistrement dans Journal
                  $journal = new Journal();
                  $journal->LIBELOP = 'Scolarité de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                  $journal->DATEOP = $request->input('date_operation');
                  $journal->MODEPAIE = $request->input('mode_paiement');
                  $journal->ANSCOL = $eleve->anneeacademique;
                  $journal->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                  $journal->DEBIT = $request->input('scolarite');
                  $journal->NumFRais = '1'; // Scolarité
                  $journal->SIGNATURE = session()->get('nom_user');
                  $journal->save();
                                  
                  $messages[] = 'Le montant de la scolarité a été enregistré avec succès.';
              }
          }
          
          // Enregistrer le montant de l'arrière si présent et supérieur à 0
          if ($request->filled('arriere') && $request->input('arriere') > 0) {
            $existingScolarite = Scolarite::where('MATRICULE', $matricule)
                ->where('DATEOP', $request->input('date_operation'))
                ->where('MONTANT', $request->input('arriere'))
                ->where('AUTREF', '2') // Arriéré
                ->first();

            if ($existingScolarite) {
                $errors[] = 'Un arriéré similaire existe déjà pour cet élève.';
            } else {
                $scolarite = new Scolarite();
                $scolarite->MATRICULE = $matricule;
                $scolarite->DATEOP = $request->input('date_operation');
                $scolarite->MODEPAIE = $request->input('mode_paiement');
                $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                $scolarite->ANSCOL = $eleve->anneeacademique;
                $scolarite->NUMERO = $getNumero($matricule, $request->input('date_operation'));
                $scolarite->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                $scolarite->MONTANT = $request->input('arriere');
                $scolarite->AUTREF = '2'; // Arriéré
                $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                $scolarite->save();

                // Enregistrement dans Journal
                $journal = new Journal();
                $journal->LIBELOP = 'Arriéré de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                $journal->DATEOP = $request->input('date_operation');
                $journal->MODEPAIE = $request->input('mode_paiement');
                $journal->ANSCOL = $eleve->anneeacademique;
                $journal->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                $journal->DEBIT = $request->input('arriere');
                $journal->NumFRais = '2'; // Arriéré
                $journal->SIGNATURE = session()->get('nom_user');
                $journal->save();
                
                $messages[] = 'Le montant de l\'arriéré a été enregistré avec succès.';
            }
          }

        // Tableau pour stocker les montants enregistrés récemment
        $recentMontants = [];
        $hiddenMontants = [];

        // Enregistrer les montants additionnels (libelle-1, libelle-2, etc.) supérieurs à 0
        for ($i = 0; $i <= 3; $i++) {
            $libelle = $request->input('libelle_' . $i);
            if ($libelle !== null && $libelle > 0) {
                $existingScolarite = Scolarite::where('MATRICULE', $matricule)
                    ->where('DATEOP', $request->input('date_operation'))
                    ->where('MONTANT', $libelle)
                    ->where('AUTREF', strval($i + 3)) // Type de libellé
                    ->first();

                if ($existingScolarite) {
                    $errors[] = 'Un paiement additionnel similaire existe déjà pour cet élève (Libellé-' . $i . ').';
                } else {
                    $scolarite = new Scolarite();
                    $scolarite->MATRICULE = $matricule;
                    $scolarite->DATEOP = $request->input('date_operation');
                    $scolarite->MODEPAIE = $request->input('mode_paiement');
                    $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                    $scolarite->ANSCOL = $eleve->anneeacademique;
                    $scolarite->NUMERO = $getNumero($matricule, $request->input('date_operation'));
                    $scolarite->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                    $scolarite->MONTANT = $libelle;
                    $scolarite->AUTREF = strval($i + 3); // Différencier les libellés
                    $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                    $scolarite->save();

                    // Enregistrement dans Journal
                    $journal = new Journal();
                    $libelles = Params2::first();
                    $libelleField = 'LIBELF' . $i+1;  // Concaténation de 'LIBELF' avec $i pour obtenir le champ correct
                    $journal->LIBELOP = $libelles->$libelleField . ' de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                    $journal->DATEOP = $request->input('date_operation');
                    $journal->MODEPAIE = $request->input('mode_paiement');
                    $journal->ANSCOL = $eleve->anneeacademique;
                    $journal->NUMRECU = $getNumero($matricule, $request->input('date_operation'));
                    $journal->DEBIT = $libelle;
                    $journal->NumFRais = strval($i + 3); // Différencier les libellés
                    $journal->SIGNATURE = session()->get('nom_user');
                    $journal->save();

                    // Ajouter le montant enregistré à la liste des montants récents
                    $recentMontants['libelle_' . $i] = $libelle;

                    $messages[] = 'Le montant additionnel (Libellé-' . $i . ') a été enregistré avec succès.';
                }
            }
        }

        // Stocker les montants récemment enregistrés dans la session
        session()->put('recent_montants', $recentMontants);

        // Si des erreurs existent, ajouter à la session et retourner
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Si aucun doublon n'est rencontré et tout est sauvegardé, ajouter les messages de succès
        if (!empty($messages)) {
            session()->flash('messages', $messages);
        }
        
        // Stockage des informations dans la session pour future référence
        Session::put([
            'eleve' => $eleve,
            'montantPaye' => $request->input('montant_paye'),
            'scolarite' => $request->input('scolarite'),
            'scolarite_hidden' => $request->input('scolarite_hidden'),
            'arriere' => $request->input('arriere'),
            'arriere_hidden' => $request->input('arriere_hidden'),
            'reliquat' => $request->input('reliquat_hidden'),
            'numeroRecu' => $getNumero($matricule, $request->input('date_operation')),
            'signature' => session()->get('nom_user'),
            'modePaiement' => $request->input('mode_paiement'),
            'montantdu' => $request->input('montant_total'),
        ]);

        // -------------------------------
              //  CREATION DE LA FACTURE
        // -------------------------------
        
    $items = []; // Tableau pour stocker les informations des paiements
    
            // Enregistrement des paiements de scolarité
            if ($request->filled('scolarite') && $request->input('scolarite') > 0) {
              $scolariteMontant = intval($request->input('scolarite'));
              $items[] = [
                  'name' => 'Scolarité',
                  'price' => $scolariteMontant,
                  'quantity' => 1,
                  'taxGroup' => $taxe,
              ];
          }

    // Enregistrement des paiements d'arriérés
    if ($request->filled('arriere') && $request->input('arriere') > 0) {
        $arriereMontant = intval($request->input('arriere'));
        $items[] = [
            'name' => 'Arriéré',
            'price' => $arriereMontant,
            'quantity' => 1,
            'taxGroup' => $taxe,
        ];
    }

    // Enregistrement des montants additionnels (libelle_0, libelle_1, etc.)
   

    
    
    // Boucle sur les libellés
    for ($i = 0; $i <= 3; $i++) {
        $libelle = $request->input('libelle_' . $i);
        if ($libelle !== null && $libelle > 0) {
            $libelleMontant = intval($libelle);
    
            // Récupérer le libellé correspondant depuis la table params2
            $columnName = 'LIBELF' . ($i + 1);
            $libelleName = DB::table('params2')->value($columnName);
    
            // Ajouter les données au tableau items
            $items[] = [
                'name' => $libelleName ?? 'Libellé-' . ($i + 1),
                'price' => $libelleMontant,
                'quantity' => 1,
                'taxGroup' => $taxe,
            ];
        }
    }
    
    //  dd($items); // Utilisez cette ligne pour déboguer et vérifier les données
  
    // Préparez les données JSON pour l'API
    $nomcompleteleve = $eleve->NOM . ' ' . $eleve->PRENOM;

    $jsonData = json_encode([
      "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
      // "aib" => "A",
      "type" => $type,
      "items" => $items,
      // "items" => [
      //         [
      //             'name' => 'Frais cantine pour :'.$$mois,
      //             // 'price' => intval($infocontrateleve->montant_paiementcontrat),
      //             'price' => intval($montantmoiscontrat), 
      //             'quantity' => 1,
      //             'taxGroup' => $taxe,
      //         ]                    
         
      // ],
      "client" => [
          "ifu" => "0202380068074",
          "name"=>  $nomcompleteleve,
          // "contact" => "string",
          // "address"=> "string"
      ],
      "operator" => [
          "name" => "CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
      ],
      "payment" => [
          [
          "name" => "ESPECES",
          //   "amount": 0
          ]
        ],
  ]);
  // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


  // dd($jsonData);

  // Définissez l'URL de l'API de facturation
  $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';

  // Définissez le jeton d'authentification

      $token = $tokenentreprise;
  //    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjEyMDEyMDE1NzQ1MDJ8VFMwMTAxMjE5OCIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyOTAwMTYwNiwiZXhwIjoxOTI0OTAyMDAwLCJpYXQiOjE3MjkwMDE2MDYsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.t0VBBtOig972FWCW2aFk7jyb-SHKv1iSZ9bkM-IGc2M";
      // $token = $tokenentreprise;

  // Effectuez la requête POST à l'API
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $ch = curl_init($apiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer ' . $token
  ));
  curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

  // Exécutez la requête cURL et récupérez la réponse
$response = curl_exec($ch);
// dd($response);

// Vérifiez les erreurs de cURL
if (curl_errno($ch)) {
// echo 'Erreur cURL : ' . curl_error($ch);
return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
}

// Fermez la session cURL
curl_close($ch);

// Affichez la réponse de l'API
$decodedResponse = json_decode($response, true);
// dd($decodedResponse);

// Vérifiez si l'UID est présent dans la réponse
if (isset($decodedResponse['uid'])) {
// L'UID de la demande
$uid = $decodedResponse['uid']; }


// -------------------------------
                    //  RECUPERATION DE LA FACTURE PAR SON UID
                // -------------------------------

            // Définissez l'URL de l'API de confirmation de facture
            $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
            // Configuration de la requête cURL pour la confirmation
            $chRecuperation = curl_init($recuperationUrl);
            curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Length: 0'
            ]);
            curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

            // Exécutez la requête cURL pour la confirmation
            $responseRecuperation = curl_exec($chRecuperation);
            // dd($responseConfirmation);
            // Vérifiez les erreurs de cURL pour la confirmation


            // Fermez la session cURL pour la confirmation
            curl_close($chRecuperation);

        // Convertissez la réponse JSON en tableau associatif PHP
        $decodedDonneFacture = json_decode($responseRecuperation, true);
        // dd($decodedDonneFacture);

        $facturedetaille = json_decode($jsonData, true);
        $ifuEcoleFacture = $decodedDonneFacture['ifu'];
        $itemFacture = $decodedDonneFacture['items'];
        $jsonItem = json_encode($itemFacture);
        $doneeDetailleItemFacture = $itemFacture['0'];
        $nameItemFacture = $doneeDetailleItemFacture['name'];
        $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
        $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
        $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
        // $idd = $responseRecuperation.ifu;
        $nameClient = $decodedDonneFacture['client']['name'];
        // dd($prixTotalItemFacture);

// -------------------------------
                    //  CONFIRMATION DE LA FACTURE 
                // -------------------------------

        // ACTION pour la confirmation
        $actionConfirmation = 'confirm';
    
        // Définissez l'URL de l'API de confirmation de facture
        $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
    
        // Configuration de la requête cURL pour la confirmation
        $chConfirmation = curl_init($confirmationUrl);
        curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Length: 0'
        ]);
        curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
        // Exécutez la requête cURL pour la confirmation
        $responseConfirmation = curl_exec($chConfirmation);
    
        // Vérifiez les erreurs de cURL pour la confirmation
        if (curl_errno($chConfirmation)) {
            // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
            return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

        }
    
        // Fermez la session cURL pour la confirmation
        curl_close($chConfirmation);
    
    // Convertissez la réponse JSON en tableau associatif PHP
    $decodedResponseConfirmation = json_decode($responseConfirmation, true);
    // dd($decodedResponseConfirmation);
    
    
        $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

        $counters = $decodedResponseConfirmation['counters'];

        $nim = $decodedResponseConfirmation['nim'];

        $dateTime = $decodedResponseConfirmation['dateTime'];

        // Générer le code QR
        $qrCodeString = $decodedResponseConfirmation['qrCode'];

        $reffactures = $nim.'-'.$counters;

        $reffacture = explode('/', $reffactures)[0];

                // gestion du code qr sous forme d'image

                $fileNameqrcode = $nomcompleteleve . time() . '.png';
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrCodeString)
                    ->size(100)
                    // ->margin(10)
                    ->build();

                    $qrcodecontent = $result->getString();
                    
                    $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateTime)->format('Y-m-d H:i:s');
                    $data = [
                      'uid' => $uid,
                      'ifu_ecole' => $ifuEcoleFacture,
                      'client_name' => $nameClient,
                      'client_classe' => $eleve->CODECLAS,
                      'client_matricule' => $matricule,
                      'client_ifu' => $decodedDonneFacture['client']['ifu'] ?? null,
                      'items' => json_encode($itemFacture), // Conversion en JSON
                      'total_price' => array_sum(array_column($itemFacture, 'price')),
                      'tax_group' => $taxGroupItemFacture,
                      'counters' => $counters,
                      'nim' => $nim,
                      'date_time' => $dateTime,
                      'qr_code' => $qrCodeString,
                      'qr_code_image_path' => '/path/to/saved/qrcode/' . $fileNameqrcode, // Chemin de sauvegarde de l'image
                      'confirmation_code' => $codemecef,
                  ];
                  
                  DB::table('facturescolarit')->insert($data);

                  // Chemin pour enregistrer l'image QR Code
                  $path = 'qrcodes/' . $fileNameqrcode;

                  // Sauvegarder l'image
                  Storage::disk('public')->put($path, $qrcodecontent);

                  $paramse = Params2::first(); 

        $logoUrl = $paramse ? $paramse->logoimage: null; 
    
        $NOMETAB = $paramse->NOMETAB;

    // dd($NOMETAB);
            // $id = $fileNameqrcode;
            // $qrcodesin = Qrcode::find($id);
    
            // $qrcodecontent = $qrcodesin->qrcode;
    
        // Mettre les données principales dans la session
Session::put([
  'factureconfirm' => $decodedResponseConfirmation, // Confirmation de la facture
  'fileNameqrcode' => $fileNameqrcode,             // Nom du fichier QR code
  'facturedetaille' => $facturedetaille,           // Détails de la facture
  'reffacture' => $reffacture,                     // Référence de la facture
  'ifuEcoleFacture' => $ifuEcoleFacture,           // IFU de l'école
  'qrCodeString' => $qrCodeString,                 // Données du QR code
  'qrcodecontent' => $qrcodecontent,               // Contenu du QR code
  'NOMETAB' => $NOMETAB,                           // Nom de l'établissement
  'nim' => $nim,                                   // Identifiant NIM
  'dateTime' => $dateTime                          // Date et heure
]);

// Ajouter les données liées à l'élève
Session::put([
  'classeeleve' => $eleve->CODECLAS,               // Classe de l'élève
  'nomcompleteleve' => $nomcompleteleve           // Nom complet de l'élève
]);

// Ajouter les informations sur les items de la facture
Session::put([
  'itemFacture' => $itemFacture,                   // Liste des items de la facture
  'prixTotalItemFacture' => $prixTotalItemFacture, // Prix total
  'quantityItemFacture' => $quantityItemFacture,   // Quantité
  'taxGroupItemFacture' => $taxGroupItemFacture    // Groupe de taxes
]);

// Ajouter le logo de l'établissement, si disponible
if ($logoUrl) {
  Session::put('logoUrl', $logoUrl);
}

    
return view('pages.inscriptions.pdfpaiementsco', [
  // Informations principales de la facture
  'factureconfirm' => $decodedResponseConfirmation,
  'fileNameqrcode' => $fileNameqrcode,
  'facturedetaille' => $facturedetaille,
  'reffacture' => $reffacture,
  'ifuEcoleFacture' => $ifuEcoleFacture,
  'qrCodeString' => $qrCodeString,
  'qrcodecontent' => $qrcodecontent,

  // Informations sur l'élève
  'classeeleve' => $eleve->CODECLAS,
  'nomcompleteleve' => $nomcompleteleve,

  // Détails des items de la facture
  'itemFacture' => $itemFacture,
  'prixTotalItemFacture' => $prixTotalItemFacture,
  'quantityItemFacture' => $quantityItemFacture,
  'taxGroupItemFacture' => $taxGroupItemFacture,

  // Métadonnées supplémentaires
  'NOMETAB' => $NOMETAB,
  'nim' => $nim,
  'dateTime' => $dateTime,

  // Optionnel : logo de l'école (si disponible)
  'logoUrl' => $logoUrl ?? null,

  // Données supplémentaires si nécessaires
  'montanttotal' => $montanttotal ?? null,
  'datepaiementcontrat' => $datepaiementcontrat ?? null,
]);

          
        // Redirection avec un message global de succès
        // return redirect()->back()->with('success', 'Paiement enregistré avec succès !');
    }

    public function facturenormalisesco() {
      $qrcodecontent = Session::get('qrcodecontent');
      $decodedResponseConfirmation = Session::get('factureconfirm');
      $facturedetaille = Session::get('facturedetaille');
      $reffacture = Session::get('reffacture');
      $classeeleve = Session::get('classeeleve');
      $nomcompleteleve = Session::get('nomcompleteleve');
      $toutmoiscontrat = Session::get('toutmoiscontrat');
      $qrCodeString = Session::get('qrCodeString');
      $fileNameqrcode = Session::get('fileNameqrcode');
     
      $paramse = Paramsfacture::first(); 

      $logoUrl = $paramse ? $paramse->logo: null; 
      // $villeetab = Session::get('villeetab');
      // $nometab = Session::get('nometab');


                  // // Générer le PDF

                  $data = [
                      'decodedResponseConfirmation' => $decodedResponseConfirmation,
                      'facturedetaille' => $facturedetaille,
                      'reffacture' => $reffacture,
                      'classeeleve' => $classeeleve,
                      'nomcompleteleve' => $nomcompleteleve,
                      'toutmoiscontrat' => $toutmoiscontrat,
                      'qrCodeString' => $qrCodeString,
                      'fileNameqrcode' => $fileNameqrcode,
                      'logoUrl' => $logoUrl,
                      'qrcodecontent' => $qrcodecontent,
                  ];

                  $datepaiement = $decodedResponseConfirmation['dateTime'];
                  // dd($datepaiement);
              
                  // Spécifiez le nom du fichier avec un timestamp pour garantir l'unicité
                  $fileName = $nomcompleteleve . time() . '.pdf';
              
                  // Spécifiez le chemin complet vers le sous-dossier pdfs dans public
                  $filePaths = public_path('pdfs/' . $fileName);
              
                  // Assurez-vous que le répertoire pdfs existe, sinon créez-le
                  if (!file_exists(public_path('pdfs'))) {
                      mkdir(public_path('pdfs'), 0755, true);
                  }
              
                  // Générer et enregistrer le PDF dans le sous-dossier pdfs
                  $pdf = PDF::loadView('pages.Etats.facturenormalisesco', $data)->save($filePaths);
              
              
                     // Enregistrer le chemin du PDF dans la base de données
                                  $duplicatafacture = new Duplicatafacture();
                                  $duplicatafacture->url = $fileName;
                                  $duplicatafacture->nomeleve = $nomcompleteleve;
                                  $duplicatafacture->classe = $classeeleve;
                                  $duplicatafacture->reference = 'Facture de paiement';
                                  $duplicatafacture->datepaiement = $datepaiement;
                                  $duplicatafacture->save();


     // dd($fileName);
      return view('pages.Etats.facturenormalisesco',  [
          'factureconfirm' => $decodedResponseConfirmation,
          'facturedetaille' => $facturedetaille, 
          'reffacture' => $reffacture,
          'classeeleve' => $classeeleve,
          'nomcompleteleve' => $nomcompleteleve,
          'toutmoiscontrat' => $toutmoiscontrat,
          'qrCodeString' => $qrCodeString,
          'logoUrl' => $logoUrl,
          'fileNameqrcode' => $fileNameqrcode,
          'qrcodecontent' => $qrcodecontent,

          // 'nometab' => $nometab,
          // 'villeetab' => $villeetab,
      ]);        
  }
  
    public function etatdesrecouvrements(){
      $typeclasse = Typeclasse::all();
      $typeenseign = Typeenseigne::all();
      $groupeclasse = Groupeclasse::all();
      return view ('pages.inscriptions.etatdesrecouvrements', compact('typeclasse', 'typeenseign', 'groupeclasse'));
    }
  public function modifieeleve(Request $request, $MATRICULE){
    $modifyeleve = Eleveplus::find($MATRICULE);
    if ($modifyeleve) {
      $modifyeleve->maladiesconnues = $request->input('maladieschroniques');
      $modifyeleve->interditalimentaires = $request->input('interditalimentaires');
      $modifyeleve->groupesanguin = $request->input('groupesanguin');
      $modifyeleve->electroforez = $request->input('typehemoglobine');
      $modifyeleve->NOMMERE = $request->input('nommere');
      $modifyeleve->prenommere = $request->input('prenommere');
      $modifyeleve->telmere = $request->input('telephonemere');
      $modifyeleve->emailmere = $request->input('emailmere');
      $modifyeleve->professionmere = $request->input('professionmere');
      $modifyeleve->adremployeurmere = $request->input('adresseemployeurmere');
      $modifyeleve->adrmere = $request->input('adressepersonnellemere');
      $modifyeleve->NOMPERE = $request->input('nompere');
      $modifyeleve->prenompere = $request->input('prenompere');
      $modifyeleve->telpere = $request->input('telephonepere');
      $modifyeleve->emailpere = $request->input('emailpere');
      $modifyeleve->professionpere = $request->input('professionpere');
      $modifyeleve->adremployeurpere = $request->input('adresseemployeurpere');
      $modifyeleve->adrpere = $request->input('adressepersonnellepere');
      $modifyeleve->nomtutuer = $request->input('nomtuteur');
      $modifyeleve->prenomtuteur = $request->input('prenomtuteur');
      $modifyeleve->teltuteur = $request->input('telephonetuteur');
      $modifyeleve->emailtuteur = $request->input('emailtuteur');
      $modifyeleve->adremployeurtuteur = $request->input('adresseemployeurtuteur');
      $modifyeleve->adrtuteur = $request->input('adressepersonnelletuteur');
      $modifyeleve->professiontuteur = $request->input('professiontuteur');
      $modifyeleve->nomurgence = $request->input('nomurgence');
      $modifyeleve->prenomurgence = $request->input('prenomurgence');
      $modifyeleve->telurgence = $request->input('telephoneurgence');
      $modifyeleve->emailpere = $request->input('emailurgence');
      $modifyeleve->adrurgence = $request->input('adressepersonnelleurgence');
      $modifyeleve->autorisefilm = $request->input('autorisevideo');
      $modifyeleve->autoriseuseimage = $request->input('autoriseimage');
      $modifyeleve->update();
      return back()->with('status','Modifier avec succes');
      
    } else {
      return back()->withErrors('Erreur lors de la modification.');
      
    }
    
  }
  
  public function modifieleve(Request $request, $MATRICULE) {
    $modifieleve = Eleve::find($MATRICULE);
    
    if ($modifieleve) {
      // Gestion des attributs de l'élève à modifier
      $imageContent = null;
      if ($request->hasFile('photo')) {
        $imageName = $request->file('photo');
        $imageContent = file_get_contents($imageName->getRealPath());
        $modifieleve->PHOTO = $imageContent;
      }
      
      $redoublant = $request->has('redoublant') ? 1 : 0;
      $formateMatricule = str_pad($request->input('numOrdre'), 8, '0', STR_PAD_LEFT);
      $modifieleve->DATESOR = $request->input('datesortante');
      $modifieleve->CLASSESOR = $request->input('classesortant');
      $modifieleve->CODECLAS = $request->input('classe');

      $modifieleve->MATRICULE = $request->input('numOrdre');
      $modifieleve->NOM = $request->input('nom');
      $modifieleve->PRENOM = $request->input('prenom');
      $modifieleve->DATENAIS = $request->input('dateNaissance');
      $modifieleve->LIEUNAIS = $request->input('lieuNaissance');
      $modifieleve->DATEINS = $request->input('dateInscription');
      $modifieleve->CODEDEPT = $request->input('departement');
      $modifieleve->SEXE = $request->input('sexe');
      $modifieleve->STATUTG = $request->input('typeEleve');
      $modifieleve->APTE = $request->input('aptituteSport');
      $modifieleve->ADRPERS = $request->input('adressePersonnelle');
      $modifieleve->ETABORIG = $request->input('etablissementOrigine');
      $modifieleve->NATIONALITE = $request->input('nationalite');
      $modifieleve->STATUT = $redoublant;
      $modifieleve->NOMPERE = $request->input('nomPere');
      $modifieleve->NOMMERE = $request->input('nomMere');
      $modifieleve->ADRPAR = $request->input('adressesParents');
      $modifieleve->AUTRE_RENS = $request->input('autresRenseignements');
      $modifieleve->indicatif1 = $request->input('indicatifParent');
      $modifieleve->TEL = $request->input('telephoneParent');
      $modifieleve->TelEleve = $request->input('telephoneEleve');
      $modifieleve->MATRICULEX = $formateMatricule;
      $modifieleve->CODEWEB = $formateMatricule;
      
      $modifieleve->update();
      
      return back()->with('status', 'Modification effectuée avec succès');
    }
    
    return back()->withErrors('Erreur lors de la modification.');
  }
  public function etatdesarriérés() {
    $archive = Elevea::select('MATRICULE', 'NOM', 'PRENOM')->get();
    $delevea = Deleve::where('MONTANTARRIERE', '!=', 0)
                      ->select('MATRICULE', 'MONTANTARRIERE', 'CODECLAS', 'MONTANTENAVANCE')
                      ->get();
    $eleve = Eleve::select('MATRICULE')->get();
    return view('pages.inscriptions.etatdesarrieres', compact('archive', 'delevea', 'eleve'));
}
public function recouvrementoperateur(Request $request) {
  $params = Params2::all();
  
  $dateDebut = $request->input('debut');
  $dateFin = $request->input('fin');

  $scolarite = DB::table('scolarit')
                  ->select('SIGNATURE',
                           DB::raw('SUM(CASE WHEN AUTREF = 1 THEN MONTANT ELSE 0 END) as Scolarite'),
                           DB::raw('SUM(CASE WHEN AUTREF = 2 THEN MONTANT ELSE 0 END) as Arriere'),
                           DB::raw('SUM(CASE WHEN AUTREF = 3 THEN MONTANT ELSE 0 END) as Insc'),
                           DB::raw('SUM(CASE WHEN AUTREF = 4 THEN MONTANT ELSE 0 END) as Frais2'),
                           DB::raw('SUM(CASE WHEN AUTREF = 5 THEN MONTANT ELSE 0 END) as Frais3'),
                           DB::raw('SUM(CASE WHEN AUTREF = 6 THEN MONTANT ELSE 0 END) as Frais4'),
                           DB::raw('SUM(MONTANT) as Total'))
                  ->where('VALIDE', '!=', 0)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->groupBy('SIGNATURE')
                  ->get();

    return view('pages.inscriptions.recouvrementoperateur', compact('scolarite', 'params', 'dateDebut', 'dateFin'));
  }
  
  public function journaloperateur(Request $request) {
    $dateDebut = $request->input('debut');
    $dateFin = $request->input('fin');
    $scolarite = DB::table('scolarit')
                  ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE')
                  ->select('scolarit.NUMRECU', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'scolarit.MONTANT', 'scolarit.SIGNATURE', 'scolarit.DATEOP')
                  ->whereBetween('scolarit.DATEOP', [$dateDebut, $dateFin])
                  ->where('scolarit.VALIDE', '!=', 0)
                  ->orderBy('scolarit.SIGNATURE')
                  ->get()
                  ->groupBy(['SIGNATURE', function($item) {
                      return \Carbon\Carbon::parse($item->DATEOP)->format('Y-m-d');
                  }]);
    return view('pages.inscriptions.journaloperateur', compact('scolarite', 'dateDebut', 'dateFin'));
  }

  public function journalresumerecouvrement(Request $request) {
    $dateDebut = $request->input('debut');
    $params = Params2::all();
    $dateFin = $request->input('fin');
    $scolarite = DB::table('scolarit')
                  ->select('DATEOP', 'AUTREF', DB::raw('SUM(MONTANT) as Montant'))
                  ->where('VALIDE', '!=', 0)
                  ->whereBetween('DATEOP', [$dateDebut, $dateFin])
                  ->groupBy('DATEOP', 'AUTREF')
                  ->get();
    return view('pages.inscriptions.journalresumerecouvrement', compact('scolarite', 'dateDebut', 'dateFin', 'params'));
}
}