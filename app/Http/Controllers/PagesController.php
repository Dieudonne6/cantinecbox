<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Scolarite;
use App\Models\Eleve;
use App\Models\Contrat;
use App\Models\Duplicatafacture;
use App\Models\Classe;
use App\Models\Paiementcontrat;
use App\Models\Paramcontrat;
use App\Models\Reduction;
use App\Models\Frais;
use App\Models\Echeancier;
use App\Models\Paramsfacture;
use App\Models\Facture;
use App\Models\Facturenormalise;
use App\Models\Duplicatarecu;
use App\Models\Scolariteeleve;
use App\Models\Arriere;
use App\Models\Eleveplus;
use App\Models\Nomecole;
use App\Models\Paiementscolarite;
use App\Models\User;
use App\Models\Anscol;
use App\Models\Trimestre;
use App\Models\Params2;
use App\Http\Controllers\DashboardController;
use App\Models\Classes;
use App\Models\Promo;
use App\Models\Serie;
use App\Models\Classesgroupeclass;
use App\Models\Typeclasse;
use App\Models\Echeance;
use App\Models\Echeancc;
use App\Models\Dept;
use App\Models\Departement;
use App\Models\Elevea;
use App\Models\Deleve;
use App\Models\Eleve_pont;
use App\Models\Typeenseigne;
use App\Models\Groupeclasse;
use App\Models\Chapitre;
use App\Models\Journal;
use App\Models\Facturescolarit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
// use PhpOffice\PhpWord\Shared\Html as HtmlFormatter;
use DateTime;
use App\Models\Groupe;
use Illuminate\Support\Facades\Log;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;
use Illuminate\Support\Facades\Http;



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
            $classes = Classes::where('TYPECLASSE', 1)->get();

    return view('pages.inscriptions.Acceuil')->with('classe', $classes);
    
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
        $id_usercontrat = $account->id;
        $image = $account->image;
        
        $nom_user = $account->login;
        Session::put('image', $image);
        
        $prenom_user = $account->prenomuser;
        Session::put('id_usercontrat', $id_usercontrat);
        Session::put('nom_user', $nom_user);
        Session::put('prenom_user', $prenom_user);


                $year = now()->year;

        // Labels en français pour les 12 mois
        $months = [
            'Janvier','Février','Mars','Avril','Mai','Juin',
            'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
        ];

        // Somme des montants par mois pour l'année en cours
        $raw = Scolarite::selectRaw('MONTH(DATEOP) as month, SUM(MONTANT) as total')
            ->where('VALIDE', 1)
            ->whereYear('DATEOP', $year)
            ->groupBy('month')
            ->pluck('total','month') // cle = month (1..12), value = total
            ->toArray();

        // Normaliser en tableau 12 éléments (index 0 => Janvier)
        $monthlyTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTotals[] = isset($raw[$m]) ? (float) $raw[$m] : 0;
        }

        // Dernières transactions (affichage dans la colonne de droite)
        $lastTransactions = Scolarite::orderBy('DATEOP', 'desc')->limit(7)->get();


        
        // Rediriger vers le dashboard approprié selon les permissions
        return app(DashboardController::class)->redirectToDashboard();
      } else{
        return back()->with('status', 'Mot de passe ou identifiant incorrecte');
        
      }
    } else {
        return back()->with('status', 'Mot de passe ou identifiant incorrecte');
    }
}
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
  }
  
  // Afficher le formulaire de changement de mot de passe
  public function showChangePasswordForm()
  {
      // Vérifier si l'utilisateur est connecté via la session
      $account = Session::get('account');
      if (!$account) {
          return redirect('/'); // adapte si tu as un nom de route pour la page de connexion
      }

      return view('pages.parametre.change_password', ['account' => $account]);
  }

  // Traiter la mise à jour du mot de passe
  public function updatePassword(Request $request)
  {
      $account = Session::get('account');
      if (!$account) {
          return redirect('/');
      }

      // Validation
      $request->validate([
          'current_password' => 'required|string',
          'new_password' => 'required|string|confirmed', // use field new_password_confirmation for confirmation
      ], [
          'new_password.confirmed' => "La confirmation du nouveau mot de passe ne correspond pas.",
      ]);

      // Vérifier le mot de passe actuel
      if (!Hash::check($request->current_password, $account->motdepasse)) {
          return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
      }

      // Mettre à jour le mot de passe
      $user = User::find($account->id);
      if (!$user) {
          // en cas d'incohérence
          Session::flush();
          return redirect('/')->with('status', "Erreur : compte introuvable. Connectez-vous à nouveau.");
      }

      $user->motdepasse = Hash::make($request->new_password);
      $user->save();

      // Déconnexion : effacer la session
      Session::forget('account');
      Session::forget('id_usercontrat');
      Session::forget('nom_user');
      Session::forget('prenom_user');
      Session::forget('image');
      // ou Session::flush(); // si tu veux tout vider

      // Rediriger vers la page de connexion avec message
      return redirect('/')->with('status', 'Mot de passe modifié avec succès. Veuillez vous reconnecter.');
  }



  // Afficher le formulaire de changement du profile
  public function showChangeProfileForm()
  {
      // Vérifier si l'utilisateur est connecté via la session
      $account = Session::get('account');
      if (!$account) {
          return redirect('/'); // adapte si tu as un nom de route pour la page de connexion
      }

      return view('pages.parametre.updateprofile', ['account' => $account]);
  }

  // Traiter la mise à jour du profile
  public function updateProfile(Request $request)
  {
      $account = Session::get('account');
      if (!$account) {
          return redirect('/');
      }

      // Validation
      $request->validate([
          'login' => 'required|string',
          'nom' => 'nullable|string', 
          'prenom' => "nullable|string",
      ]);


      // Mettre à jour le profile
      $user = User::find($account->id);
      if (!$user) {
          // en cas d'incohérence
          Session::flush();
          return redirect('/')->with('status', "Erreur : compte introuvable. Connectez-vous à nouveau.");
      }

      $user->login = $request->login;
      $user->nomuser = $request->nom;
      $user->prenomuser = $request->prenom;
      $user->save();

      Session::put('account', $user);
      // Déconnexion : effacer la session
      // Session::forget('account');
      // Session::forget('id_usercontrat');
      // Session::forget('nom_user');
      // Session::forget('prenom_user');
      // Session::forget('image');
      // ou Session::flush(); // si tu veux tout vider

      // Rediriger vers la page de connexion avec message
      return redirect('/vitrine')->with('status', 'Profile modifié avec succès.');
  }


public function etatdesdroits(Request $request) {
    $classe = Classes::all();
    $params = Params2::first();
    $selectedClasses = $request->input('selectedClasses', []);
    $eleves = collect(); // Par défaut, collection vide

    if ($request->ajax()) {
        if (!empty($selectedClasses)) {
            // Récupérer les élèves pour les classes sélectionnées
            $eleves = Eleve::whereIn('CODECLAS', $selectedClasses)->get();

            if ($eleves->isEmpty()) {
                return response()->json(['message' => 'Aucun élève trouvé pour les classes sélectionnées.'], 404);
            }

            $choixPlage = $request->input('choixPlage');

            $eleves->transform(function ($eleve) use ($choixPlage, $params) {
                // Mapping choixPlage → code AUTREF
                $autrefMapping = [
                    'Scolarité' => 1,
                    'Arrièrés'  => 2,
                    $params->LIBELF1 => 3,
                    $params->LIBELF2 => 4,
                    $params->LIBELF3 => 5,
                    $params->LIBELF4 => 6,
                ];

                // Déterminer le montant à payer
                if ($choixPlage === 'Scolarité') {
                    $apayer = $eleve->APAYER;
                } elseif ($choixPlage === 'Arrièrés') {
                    $apayer = $eleve->ARRIERE;
                } elseif (in_array($choixPlage, [$params->LIBELF1, $params->LIBELF2, $params->LIBELF3, $params->LIBELF4])) {
                    $apayer = $eleve->{'FRAIS' . array_search($choixPlage, [
                        $params->LIBELF1, $params->LIBELF2, $params->LIBELF3, $params->LIBELF4
                    ]) + 1};
                } elseif ($choixPlage === 'Tout') {
                    $apayer = $eleve->APAYER + $eleve->ARRIERE + $eleve->FRAIS1 + $eleve->FRAIS2 + $eleve->FRAIS3 + $eleve->FRAIS4;
                } else {
                    $apayer = 0;
                }

                // Récupérer les montants versés
                if ($choixPlage === 'Tout') {
                    $montants = Scolarite::where('MATRICULE', $eleve->MATRICULE)
                    ->where('VALIDE', '1')
                    ->get();
                } else {
                    $montants = Scolarite::where('MATRICULE', $eleve->MATRICULE)
                        ->where('VALIDE', '1')
                        ->where('AUTREF', $autrefMapping[$choixPlage] ?? null)
                        ->get();
                }

                $eleve->montants = $montants;
                $eleve->APAYER = $apayer;

                return $eleve;
            });

            return response()->json(['eleves' => $eleves]);
        } else {
            return response()->json(['message' => 'Aucune classe sélectionnée.'], 400);
        }
    }

    // Vue normale
    return view('pages.inscriptions.etatdesdroits', compact('classe', 'params', 'eleves'));
}

  public function vitrine(){
    if(Session::has('account')){
      $totaleleve = Eleve::count();
      $totalcantineinscritactif = Contrat::where('statut_contrat', 1)->count();
       $totalcantineinscritinactif = Contrat::where('statut_contrat', 0)->count();
      
               $year = now()->year;


       // Labels en français pour les 12 mois
        $months = [
            'Janvier','Février','Mars','Avril','Mai','Juin',
            'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
        ];

        // Somme des montants par mois pour l'année en cours
        $raw = Scolarite::selectRaw('MONTH(DATEOP) as month, SUM(MONTANT) as total')
            ->where('VALIDE', 1)
            ->whereYear('DATEOP', $year)
            ->groupBy('month')
            ->pluck('total','month') // cle = month (1..12), value = total
            ->toArray();

        // Normaliser en tableau 12 éléments (index 0 => Janvier)
        $monthlyTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTotals[] = isset($raw[$m]) ? (float) $raw[$m] : 0;
        }

        // Dernières transactions (affichage dans la colonne de droite)
        $lastTransactions = Scolarite::orderBy('DATEOP', 'desc')->limit(7)->get();


$sentinels = [21, -1];

$abandons = Eleve::whereNull('MAN')
    ->orWhereIn('MAN', $sentinels)
    ->count();

$exclusions = Eleve::whereNotNull('MAN')
    ->whereNotIn('MAN', $sentinels)      // <-- important
    ->where('MAN', '<', 6)
    ->count();

$redoublants = Eleve::whereNotNull('MAN')
    ->whereNotIn('MAN', $sentinels)
    ->where('MAN', '>=', 6)
    ->where('MAN', '<', 10)
    ->count();

$passants = Eleve::whereNotNull('MAN')
    ->whereNotIn('MAN', $sentinels)
    ->where('MAN', '>=', 10)
    ->where('MAN', '<', 21)
    ->count();

      // Ajouter les informations de permissions pour l'utilisateur connecté
      $account = Session::get('account');
      $permissions = $this->getUserPermissions($account->nomgroupe);
      
      // dd($totalcantineinscritactif);
      return view('pages.vitrine')
            ->with('totaleleve', $totaleleve)
            ->with('totalcantineinscritactif', $totalcantineinscritactif)
            ->with('totalcantineinscritinactif', $totalcantineinscritinactif)
            ->with('months', $months)
            ->with('lastTransactions', $lastTransactions)
            ->with('monthlyTotals', $monthlyTotals)
            ->with('exclusions', $exclusions)
            ->with('redoublants', $redoublants)
            ->with('passants', $passants)
            ->with('abandons', $abandons)
            ->with('permissions', $permissions);
    }return redirect('/');
  }

  /**
   * Obtenir toutes les permissions d'un groupe depuis la table groupe_permissions
   */
  private function getUserPermissions($nomgroupe)
  {
    $groupe = \App\Models\Groupe::where('nomgroupe', $nomgroupe)->first();
    
    if (!$groupe) {
      return [];
    }
    
    // Récupérer toutes les permissions du groupe depuis la table groupe_permissions
    $permissions = \App\Models\GroupePermission::where('nomgroupe', $nomgroupe)
      ->pluck('permissions')
      ->filter() // Enlever les valeurs null/vides
      ->toArray();
    
    return $permissions;
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
    $login->ADMINISTRATEUR = 1;
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
    $departements = Dept::all();
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
    
    return view('pages.inscriptions.inscrireeleve', compact('allClasse', 'allReduction', 'departements', 'newMatricule', 'archive'));
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



  // public function eleveparclassespecifique($classeCode)
  // {
  //     $CODECLASArray = explode(',', $classeCode);

  //     $eleves = Eleve::orderBy('NOM', 'asc')->get();
  //     $classesAExclure = ['NON', 'DELETE'];

  //     $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();
  //     $fraiscontrat = Paramcontrat::first(); 

  //     $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');

  //     // Filtrer les élèves en fonction des classes sélectionnées
  //     $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
  //         ->whereIn('CODECLAS', $CODECLASArray)
  //         // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
  //         ->orderBy('NOM', 'asc')
  //         // ->groupBy('CODECLAS')
  //         ->get();
  //         // Requête pour récupérer les élèves avec l'effectif total, le nombre de filles et le nombre de garçons par classe
  //         $statistiquesClasses = Eleve::whereIn('MATRICULE', $contratValideMatricules)
  //         ->whereIn('CODECLAS', $CODECLASArray)
  //         ->select(
  //             'CODECLAS',
  //             // Effectif total
  //             DB::raw('COUNT(*) as total'),
  //             // Nombre de garçons
  //             DB::raw('SUM(CASE WHEN SEXE = 1 THEN 1 ELSE 0 END) as total_garcons'),
  //             // Nombre de filles
  //             DB::raw('SUM(CASE WHEN SEXE = 2 THEN 1 ELSE 0 END) as total_filles'),
  //             // Nombre de redoublants
  //             DB::raw('SUM(CASE WHEN STATUT = 1 THEN 1 ELSE 0 END) as total_redoublants'),
  //             // Nombre de redoublants garçons
  //             DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_redoublants'),
  //             // Nombre de redoublants filles
  //             DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_redoublants'),
  //             // Nouveaux ou transférés (statutG = 1 pour nouveaux, statutG = 3 pour transférés)
  //             DB::raw('SUM(CASE WHEN statutG = 1 THEN 1 ELSE 0 END) as total_nouveaux'),
  //             DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_nouveaux'),
  //             DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_nouveaux'),
  //             DB::raw('SUM(CASE WHEN statutG = 3 THEN 1 ELSE 0 END) as total_transferes'),
  //             DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_transferes'),
  //             DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_transferes'),
  //             // Anciens (statutG = 2)
  //             DB::raw('SUM(CASE WHEN statutG = 2 THEN 1 ELSE 0 END) as total_anciens'),
  //             DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_anciens'),
  //             DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_anciens')
  //         )
  //         ->groupBy('CODECLAS')
  //         ->get();
  //         // requette pour recuperer le nombre d'eleve par codereduction
  //         $reductionsParClasse = DB::table('eleve')
  //         ->join('reduction', 'eleve.CodeReduction', '=', 'reduction.CodeReduction') // Liaison avec la table des réductions
  //         ->whereIn('eleve.MATRICULE', $contratValideMatricules)
  //         ->whereIn('eleve.CODECLAS', $CODECLASArray)
  //         ->select(
  //             'eleve.CODECLAS', 
  //             'reduction.libelleReduction', // Nom de la réduction
  //             DB::raw('COUNT(*) as total') // Nombre d'élèves ayant cette réduction
  //         )
  //         ->groupBy('eleve.CODECLAS', 'reduction.libelleReduction')
  //         ->get();
  //         // requette pour grouper les eleve par classe
  //         $elevesGroupes = $filterEleves->groupBy('CODECLAS');



  //     return view('pages.inscriptions.eleveparclasse1')
  //         ->with("filterEleve", $filterEleves)
  //         ->with('classe', $classes)
  //         ->with('eleve', $eleves)
  //         ->with('elevesGroupes', $elevesGroupes)
  //         ->with('statistiquesClasses', $statistiquesClasses)
  //         ->with('reductionsParClasse', $reductionsParClasse)
  //         ->with('fraiscontrats', $fraiscontrat);
  // }

public function eleveparclassespecifique($classeCode)
{
    $CODECLASArray = explode(',', $classeCode);

    $eleves = Eleve::orderBy('NOM', 'asc')->get();
    $classesAExclure = ['NON', 'DELETE'];

    $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();

    // Filtrer les élèves en fonction des classes sélectionnées
    $filterEleves = Eleve::whereIn('CODECLAS', $CODECLASArray)
        ->whereIn('CODECLAS', $CODECLASArray)
        // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
        ->orderBy('NOM', 'asc')
        // ->groupBy('CODECLAS')
        ->get();
        // Requête pour récupérer les élèves avec l'effectif total, le nombre de filles et le nombre de garçons par classe
        $statistiquesClasses = Eleve::whereIn('CODECLAS', $CODECLASArray)
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
        ->with('reductionsParClasse', $reductionsParClasse);
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
                DB::raw('FRAIS1 + FRAIS2 + FRAIS3 + FRAIS4 as total_frais'),
                DB::raw('FRAIS1 + FRAIS2 + FRAIS3 + FRAIS4 + ARRIERE as total_tous')
            )
            ->orderBy('NOM', 'asc')
            ->groupBy(
                'MATRICULE',
                'NOM',
                'PRENOM',
                'CODECLAS',
                'FRAIS1',
                'FRAIS2',
                'FRAIS3',
                'FRAIS4',
                'APAYER',
                'ARRIERE'
            )
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
    $total_du = $eleves->sum('total_du_hors_echeancier'); 
    $total_scolarite = $eleves->sum('total_tous_scolarite');

    if ($total_du > 0) {
        $recouvrement = ($total_du - $total_scolarite) / $total_du;
    } else {
        $recouvrement = 0; // ou null, selon ce que tu veux afficher
    }

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
    $departements = Dept::all();
    $Matricule = Eleve::find($MATRICULE);
    $allClasse = Classes::all();
    $allReduction = Reduction::all();
    $allDepartement = Departement::all();
    $archive = Elevea::get();
    $alleleve = Eleveplus::where('MATRICULE', $MATRICULE)->first();
    $modifieleve = Eleve::where('MATRICULE', $MATRICULE)->first();
    return view('pages.inscriptions.modifiereleve', compact('allClasse', 'allReduction', 'allDepartement', 'archive', 'alleleve', 'Matricule', 'modifieleve', 'departements'));
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

    $donneLibelle = Params2::first();
    
     return view('pages.inscriptions.creerprofil')
        ->with('reductions', $reductions)
        ->with('newCode', $newCode)
        ->with('donneLibelle', $donneLibelle);
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
      $param = Paramcontrat::first();
      $anneeCourante = (int) $param->anneencours_paramcontrat;
      $anneeSuivante =  $anneeCourante + 1;
      $anneeAcademique = "{$anneeCourante}-{$anneeSuivante}";
      $reduction = new Reduction();
      $reduction->Codereduction = $request->input('Codereduction');
      $reduction->LibelleReduction = $request->input('LibelleReduction');
      // Déterminer le type de réduction
      if ($request->input('reductionType') == 2) { // Réduction fixe
        // Enregistrer les valeurs dans les colonnes de réduction fixe
          $reduction->anneeacademique = $anneeAcademique;
          $reduction->typereduction = 'F';
          $reduction->Reduction_fixe_sco = $request->input('Reduction_scolarite');
          $reduction->Reduction_fixe_arriere = $request->input('Reduction_arriere');
          $reduction->Reduction_fixe_frais1 = $request->input('Reduction_frais1');
          $reduction->Reduction_fixe_frais2 = $request->input('Reduction_frais2');
          $reduction->Reduction_fixe_frais3 = $request->input('Reduction_frais3');
          $reduction->Reduction_fixe_frais4 = $request->input('Reduction_frais4');
      } else { // Réduction par pourcentage
          // Enregistrer les valeurs dans les colonnes de réduction par pourcentage
          $reduction->anneeacademique = $anneeAcademique;
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
     // Récupérer toutes les données
     $params2 = Params2::all();
    //  $factures = FactureScolarit::all();
      $factures = DB::table('facturescolarit')
        ->whereRaw("RIGHT(counters, 2) = 'FV'")
        ->where('statut', '1')
        ->get();

//     $uneFacture = FactureScolarit::all();
//     dd($uneFacture->toArray());
     return view('pages.inscriptions.duplicatarecu', compact('params2', 'factures'));
  }


  public function duplicatarecufacturesimple(){
     // Récupérer toutes les données
     $params2 = Params2::all();
    //  $factures = FactureScolarit::all();
      $factures = DB::table('facturescolarit')
        ->where('typefac', '1')
        ->where('statut', '1')
        ->get();

//     $uneFacture = FactureScolarit::all();
//     dd($uneFacture->toArray());
     return view('pages.inscriptions.duplicatarecufacturesimple', compact('params2', 'factures'));
  }





      public function pdfduplicatarecu($counters)
    {
        $counters1 = substr_replace(preg_replace('/_/', '/', $counters, 1), ' ', -2, 0);
        // dd($counters1);

        $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

        $facturePaie = DB::table('facturescolarit')
            ->where('counters', $counters1)
            ->first();

            // dd($facturePaie);

        $infoecole = DB::table('params2')->first();
        $nomecole = $infoecole->NOMETAB;
        $logo = $infoecole->logoimage;
        $jsonItem = $facturePaie->itemfacture;
        $donneItem = json_decode($jsonItem);
        // dd($facturePaie);

        return view('pages.Etats.pdfduplicatarecu', compact('nomecole', 'logo', 'facturePaie', 'donneItem', 'entete'));
    }
  

      public function pdfduplicatarecufactuesimple($id)
    {
        // dd($counters1);

        $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

        $facturePaie = DB::table('facturescolarit')
            ->where('id', $id)
            ->first();

            // dd($facturePaie);

        $infoecole = DB::table('params2')->first();
        $nomecole = $infoecole->NOMETAB;
        $logo = $infoecole->logoimage;
        $jsonItem = $facturePaie->itemfacture;
        $donneItem = json_decode($jsonItem);
        // dd($facturePaie);

        return view('pages.Etats.pdfduplicatarecufacturesimple', compact('nomecole', 'logo', 'facturePaie', 'donneItem', 'entete'));
    }
  

    public function listefacturescolarite() {
        $facturesPaiementscolarite = DB::table('facturescolarit')->where('statut', 1)->get();
        // $facturesInscription = DB::table('facturenormaliseinscription')->where('statut', 1)->get();
        // dd($factures);
        // return view('pages.Etats.listefacture')->with('factures', $factures);
        return view('pages.Etats.listefacturescolarite', compact('facturesPaiementscolarite'));
    }


    public function avoirfacturepaiescolarite($codemecef) {
        $factureOriginale = DB::table('facturescolarit')->where('codemecef', $codemecef)->first();
        // dd($factures);
        return view('pages.Etats.avoirfacturepaiescolarite')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef);
    }


    public function suppfacturescolaritenonnormalise($id) {
        $factureoriginale = DB::table('facturescolarit')->where('id', $id)->first();
        // dd($factures);

        DB::table('facturescolarit')
              ->where('id', $id)
              ->update(['statut' => 0]);

        Scolarite::where('NUMRECU', $factureoriginale->NUMRECU)
              ->update(['VALIDE' => 0]);

          // MISE À JOUR DES LIGNES DANS journal
          Journal::where('NUMRECU', $factureoriginale->NUMRECU)
              ->update(['VALIDE' => 0]);
      
      
          return back()->with('status', "Facture d'avoir generer avec succes");

        // return view('pages.Etats.avoirfacturepaiescolarite')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef);
    }


    public function avoirfacturescolarite(Request $request, $codemecef){
          // dd('code correct');
          $codemecefEntrer = $request->input('inputCodemecef');
        if ($codemecefEntrer == $codemecef) {
              // dd('codemecef correct');
              $infoparam = Params2::first();
              $tokenentreprise = $infoparam->token;
      
              $factureoriginale = DB::table('facturescolarit')->where('codemecef', $codemecef)->first();
              $ifuentreprise = $factureoriginale->ifuEcole;
              $montanttotal = $factureoriginale->montant_total;
              // $TOTALTVA = $factureoriginale->TOTALTVA;
              // $TOTALHT = $factureoriginale->TOTALHT;
      
              $nomcompleteleve = $factureoriginale->nom;
              // $moisConcatenes = $factureoriginale->moispayes;
              $matriculeeleve = $factureoriginale->MATRICULE;
              // $idcontratEleve = $factureoriginale->idcontrat;
              $classeeleve = $factureoriginale->classe;
              $itemFac = $factureoriginale->itemfacture;
              $itemFacdecode = json_decode($itemFac, true);
              $mode_paiement = $factureoriginale->mode_paiement;
                          // Choix du texte selon le mode
                          $libelleMode = match ($mode_paiement) {
                              1 => 'ESPECES',
                              2 => 'CHEQUES',
                              default => 'AUTRE',
                          };
              // $montantparmois = $factureoriginale->montant_par_mois;
              $dateHeure = $factureoriginale->dateHeure;
              // $typefac = $factureoriginale->typefac;
              // $montantInscription = $factureoriginale->montantInscription;
              // dd($moisConcatenes);
                          // -------------------------------
                              //  CREATION DE LA FACTURE
                          // -------------------------------
      
                              $items = $itemFacdecode; // Initialiser un tableau vide pour les éléments
      
      
                              // Préparez les données JSON pour l'API
                              $jsonData = json_encode([
                                  "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                  // "aib" => "A",
                                  "type" => 'FA',
                                  "items" => $items,
                                  "client" => [
                                      // "ifu" => '',
                                      "name"=>  $nomcompleteleve,
                                      // "contact" => "string",
                                      // "address"=> "string"
                                  ],
                                  "operator" => [
                                      "name" => "CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                  ],
                                  "payment" => [
                                      [
                                      "name" => $libelleMode,
                                        "amount" => intval($montanttotal), 
                                      ]
                                    ],
                                    "reference" => $codemecef,
                              ]);
                          // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
      
      
                          //  dd($jsonData);
      
                          // Définissez l'URL de l'API de facturation
                          $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
      
                          // Définissez le jeton d'authentification
                          // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODU5MTExMzh8VFMwMTAxMTQ3MiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyNDI1NzQyMywiZXhwIjoxNzM3NDE0MDAwLCJpYXQiOjE3MjQyNTc0MjMsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.sRcSeEbIuQNSgFebRRaxW4zPLCqlF6PQXc90e2xfHCs';
                          $token = $tokenentreprise;
      
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
      
              $decodedResponse = json_decode($response, true);
      
                  // Vérifiez si l'UID est présent dans la réponse
                  if (isset($decodedResponse['uid'])) {
                      // L'UID de la demande
                      $uid = $decodedResponse['uid'];
                      // $taxb = 0.18;
      
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
                      // dd($responseRecuperation);
                      // Vérifiez les erreurs de cURL pour la confirmation
      
          
                      // Fermez la session cURL pour la confirmation
                      curl_close($chRecuperation);
          
                  // Convertissez la réponse JSON en tableau associatif PHP
                  $decodedDonneFacture = json_decode($responseRecuperation, true);
          
                  // $facturedetaille = json_decode($jsonData, true);
                  $ifuEcoleFacture = $decodedDonneFacture['ifu'];
                  // dd($ifuEcoleFacture);
                  $itemFacture = $decodedDonneFacture['items'];
                  $jsonItems = json_encode($itemFacture);
                  $doneeDetailleItemFacture = $itemFacture['0'];
                  $nameItemFacture = $doneeDetailleItemFacture['name'];
                  $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
                  $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
                  $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
                  // $idd = $responseRecuperation.ifu;
                  $nameClient = $decodedDonneFacture['client']['name'];
                  // dd($itemFacture);
      
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
                  
                  
                      // Fermez la session cURL pour la confirmation
                      curl_close($chConfirmation);
                  
                  // Convertissez la réponse JSON en tableau associatif PHP
                  $decodedResponseConfirmation = json_decode($responseConfirmation, true);
                  // dd($decodedResponseConfirmation);
      
          
                      $codemecefavoir = $decodedResponseConfirmation['codeMECeFDGI'];
          
                      $counters = $decodedResponseConfirmation['counters'];
          
                      $nim = $decodedResponseConfirmation['nim'];
          
                      $dateTime = $decodedResponseConfirmation['dateTime'];
          
                            // Générer le code QR
                            $qrCodeString = $decodedResponseConfirmation['qrCode'];
          
                            $reffactures = $nim.'-'.$counters;
      
                            $reffacture = explode('/', $reffactures)[0];
      
                            
                
                            // dd($reffacture);
            
                        // gestion du code qr sous forme d'image
                
                        // $fileNameqrcode = $nomcompleteleve . time() . '.png';
                        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->data($qrCodeString)
                            ->size(100)
                            // ->margin(10)
                            ->build();
                
                            $qrcodecontent = $result->getString();
      
                            // dd($qrcodecontent);
              $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateTime)->format('Y-m-d H:i:s');
          // ENREGISTREMENT DE LA FACTURE
          $facturenormalise = new Facturescolarit();
          $facturenormalise->uid = $uid;
          $facturenormalise->id = $reffacture;
          $facturenormalise->codemecef = $codemecefavoir;
          $facturenormalise->codemeceffacoriginale = $codemecef;
          $facturenormalise->counters = $counters;
          $facturenormalise->nim = $nim;
          $facturenormalise->dateHeure = $dateTime;
          $facturenormalise->date_time = $dateTime;
          $facturenormalise->ifuEcole = $ifuEcoleFacture;
          $facturenormalise->MATRICULE = $matriculeeleve;
          $facturenormalise->itemfacture = $jsonItems;
          $facturenormalise->classe = $classeeleve;
          $facturenormalise->nom = $nameClient;
          $facturenormalise->tax_group = $factureoriginale->tax_group;
          // $facturenormalise->designation = $nameItemFacture;
          $facturenormalise->montant_total = $montanttotal;
          //  $facturenormalise->montant_total = $prixTotalItemFacture;
          $facturenormalise->qrcode = $qrcodecontent;
          $facturenormalise->NUMRECU = $factureoriginale->NUMRECU;
          $facturenormalise->mode_paiement = $factureoriginale->mode_paiement;
          $facturenormalise->statut = 0;
      
          $facturenormalise->save();

          DB::table('facturescolarit')
              ->where('codemecef', $codemecef)
              ->update(['statut' => 0]);
      
          //  $factureoriginale->statut = 0;
          //  $factureoriginale->update();  
      
      
      
      
          // effacer les donnes de la facture qui sont dans scolarite et journal
      
          // MISE À JOUR DES LIGNES DANS scolarite
          Scolarite::where('NUMRECU', $factureoriginale->NUMRECU)
              ->update(['VALIDE' => 0]);

          // MISE À JOUR DES LIGNES DANS journal
          Journal::where('NUMRECU', $factureoriginale->NUMRECU)
              ->update(['VALIDE' => 0]);
      
      
          return back()->with('status', "Facture d'avoir generer avec succes");
      
      
          }
      } else {
          // dd('codemecef incorrect');
          return back()->with('erreur', "Le codemecef entrer ne correspond pas a celui de la facture originale.");

      }

    }


    
    
    public function avoirfacturepaiescolaritemodif($codemecef) {
        $factureOriginale = DB::table('facturescolarit')->where('codemecef', $codemecef)->first();
        // dd($factures);
        $matricule = $factureOriginale->MATRICULE;

                $eleves = Eleve::get();

        // Retrieve the student details
            $infoeleve = Eleve::where('MATRICULE', $matricule)->first();
            $scolarite = Scolarite::where('MATRICULE', $matricule)->where('VALIDE', '1')->get();
            $libelle = Params2::first();
            $echeanche = Echeance::first();
            
            // Calculate the total amounts based on AUTREF
            $totalArriere = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '2')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalScolarite = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '1')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle1 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '3')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle2 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '4')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle3 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '5')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
            
            $totalLibelle4 = Scolarite::where('MATRICULE', $matricule)
              ->where('AUTREF', '6')
              ->where('VALIDE', '1')
              ->sum('MONTANT');
    
            // Pass the totals along with other data to the view
            return view('pages.Etats.avoirfacturepaiescolaritemodif', compact(
                'infoeleve', 'scolarite', 'libelle', 
                'totalArriere', 'totalScolarite', 'totalLibelle1', 
                'totalLibelle2', 'totalLibelle3','factureOriginale','codemecef','eleves'
            ));

        // ->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat)->with('elev', $elev)->with('moisCorrespondants', $moisCorrespondants)->with('fraismensuelle', $fraismensuelle);
        // return view('pages.Etats.avoirfacturepaiescolaritemodif')->with('factureOriginale', $factureOriginale)->with('codemecef', $codemecef)->with('eleves', $elev);
    }

    // avoire facture paiement et modification
    public function avoirfacturescolaritmodification(Request $request, $codemecef){
        // dd('code correct');
        $id_usercontrat = Session::get('id_usercontrat');
        $codemecefEntrer = $request->input('inputCodemecef');
        $typeFormulaire = $request->input('typeFormulaire');

        $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';
      if ($codemecefEntrer == $codemecef) {
            // dd('codemecef correct');
            $infoparam = Params2::first();
            $tokenentreprise = $infoparam->token;
            $taxe = $infoparam->taxe;
            $type = $infoparam->typefacture;

            
            $infoParamContrat = Paramcontrat::first();
            $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
    
            $factureoriginale = DB::table('facturescolarit')->where('codemecef', $codemecef)->first();
            $ifuentreprise = $factureoriginale->ifuEcole;
            $montanttotal = $factureoriginale->montant_total;
            // $TOTALTVA = $factureoriginale->TOTALTVA;
            // $TOTALHT = $factureoriginale->TOTALHT;
    
            $nomcompleteleve = $factureoriginale->nom;
            // $moisConcatenes = $factureoriginale->moispayes;
            $matriculeeleve = $factureoriginale->MATRICULE;
            // $idcontratEleve = $factureoriginale->idcontrat;
            $classeeleve = $factureoriginale->classe;
            // $montantparmois = $factureoriginale->montant_par_mois;
            $montantTotalFacOriginal = $factureoriginale->montant_total;
            $dateHeure = $factureoriginale->dateHeure;
            // $typefac = $factureoriginale->typefac;
            $itemFacOriginale = $factureoriginale->itemfacture;
            $itemFacOriginaledecode = json_decode($itemFacOriginale, true);
            $mode_paiement = $factureoriginale->mode_paiement;

            // $montantInscription = $factureoriginale->montantInscription;
            $datepaiementNouveau = $request->input('date');

            // convertir le tableau json itemFacOriginale en tableau php

            // $itemFacOriginaleArray = json_decode($itemFacOriginale, true);
            // foreach ($itemFacOriginaleArray as $item) {
            //     // $item['name'], $item['price'], etc.
            //     // Par exemple, pour reformer un nouveau tableau :
            //     $nouveauxItems[] = [
            //         'name'     => $item['name'],
            //         'price'    => $item['price'],
            //         'quantity' => $item['quantity'],
            //         'taxGroup' => $item['taxGroup'],
            //         // tu peux ajouter ou modifier des clés si besoin
            //     ];
            // }
            // dd($nouveauxItems);
            // dd($nouveauxItems);
                        // -------------------------------
                            //  CREATION DE LA FACTURE
                        // -------------------------------
    
                            $items = $itemFacOriginaledecode; // Initialiser un tableau vide pour les éléments
    
                                  // Choix du texte selon le mode
                                  $libelleMode = match ($mode_paiement) {
                                      1 => 'ESPECES',
                                      2 => 'CHEQUES',
                                      default => 'AUTRE',
                                  };
    
                            // Préparez les données JSON pour l'API
                            $jsonData = json_encode([
                                "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                                // "aib" => "A",
                                "type" => 'FA',
                                "items" => $items,
                                "client" => [
                                    // "ifu" => '',
                                    "name"=>  $nomcompleteleve,
                                    // "contact" => "string",
                                    // "address"=> "string"
                                ],
                                "operator" => [
                                    "name" => "CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                                ],
                                "payment" => [
                                    [
                                    "name" => $libelleMode,
                                      "amount" => intval($montanttotal), 
                                    ]
                                  ],
                                  "reference" => $codemecef,
                            ]);
                        // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);
    
    
                        //  dd($jsonData);
    
                        // Définissez l'URL de l'API de facturation
                        $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
    
                        // Définissez le jeton d'authentification
                        // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzODU5MTExMzh8VFMwMTAxMTQ3MiIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyNDI1NzQyMywiZXhwIjoxNzM3NDE0MDAwLCJpYXQiOjE3MjQyNTc0MjMsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.sRcSeEbIuQNSgFebRRaxW4zPLCqlF6PQXc90e2xfHCs';
                        $token = $tokenentreprise;
    
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
            // dd($mode_paiement);
    
            $decodedResponse = json_decode($response, true);
    
                // Vérifiez si l'UID est présent dans la réponse
                if (isset($decodedResponse['uid'])) {
                    // L'UID de la demande
                    $uid = $decodedResponse['uid'];
                    // $taxb = 0.18;
    
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
                    // dd($responseRecuperation);
                    // Vérifiez les erreurs de cURL pour la confirmation
    
        
                    // Fermez la session cURL pour la confirmation
                    curl_close($chRecuperation);
        
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedDonneFacture = json_decode($responseRecuperation, true);
        
                // $facturedetaille = json_decode($jsonData, true);
                $ifuEcoleFacture = $decodedDonneFacture['ifu'];
                // dd($ifuEcoleFacture);
                $itemFacture = $decodedDonneFacture['items'];
                $jsonItems = json_encode($itemFacture);
                $doneeDetailleItemFacture = $itemFacture['0'];
                $nameItemFacture = $doneeDetailleItemFacture['name'];
                $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
                $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
                $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
                // $idd = $responseRecuperation.ifu;
                $nameClient = $decodedDonneFacture['client']['name'];
                // dd($itemFacture);
    
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
                
                
                    // Fermez la session cURL pour la confirmation
                    curl_close($chConfirmation);
                
                // Convertissez la réponse JSON en tableau associatif PHP
                $decodedResponseConfirmation = json_decode($responseConfirmation, true);
                // dd($decodedResponseConfirmation);
    
        
                    $codemecefavoir = $decodedResponseConfirmation['codeMECeFDGI'];
        
                    $counters = $decodedResponseConfirmation['counters'];
        
                    $nim = $decodedResponseConfirmation['nim'];
        
                    $dateTime = $decodedResponseConfirmation['dateTime'];
        
                          // Générer le code QR
                          $qrCodeString = $decodedResponseConfirmation['qrCode'];
        
                          $reffactures = $nim.'-'.$counters;
    
                          $reffacture = explode('/', $reffactures)[0];
    
                          
              
                          // dd($reffacture);
          
                      // gestion du code qr sous forme d'image
              
                      // $fileNameqrcode = $nomcompleteleve . time() . '.png';
                      $result = Builder::create()
                          ->writer(new PngWriter())
                          ->data($qrCodeString)
                          ->size(100)
                          // ->margin(10)
                          ->build();
              
                          $qrcodecontent = $result->getString();
    
                        //   dd($jsonItems);
                        $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateTime)->format('Y-m-d H:i:s');
              
         // ENREGISTREMENT DE LA FACTURE
          $facturenormalise = new Facturescolarit();
          $facturenormalise->uid = $uid;
          $facturenormalise->id = $reffacture;
          $facturenormalise->codemecef = $codemecefavoir;
          $facturenormalise->codemeceffacoriginale = $codemecef;
          $facturenormalise->counters = $counters;
          $facturenormalise->nim = $nim;
          $facturenormalise->dateHeure = $dateTime;
          $facturenormalise->date_time = $dateTime;
          $facturenormalise->ifuEcole = $ifuEcoleFacture;
          $facturenormalise->MATRICULE = $matriculeeleve;
          $facturenormalise->itemfacture = $jsonItems;
          $facturenormalise->classe = $classeeleve;
          $facturenormalise->nom = $nameClient;
          $facturenormalise->tax_group = $factureoriginale->tax_group;
          // $facturenormalise->designation = $nameItemFacture;
          $facturenormalise->montant_total = $montanttotal;
          //  $facturenormalise->montant_total = $prixTotalItemFacture;
          $facturenormalise->qrcode = $qrcodecontent;
          $facturenormalise->NUMRECU = $factureoriginale->NUMRECU;
          $facturenormalise->mode_paiement = $factureoriginale->mode_paiement;
          $facturenormalise->statut = 0;
      
          $facturenormalise->save();

          DB::table('facturescolarit')
              ->where('codemecef', $codemecef)
              ->update(['statut' => 0]);



    

        // verifier le type d'action choisie 
      switch ($typeFormulaire) {
          // case 'corriger_eleve':
          //       // dd('corriger_eleve');

          //           // MISE À JOUR DES LIGNES DANS scolarite
          //           Scolarite::where('NUMRECU', $factureoriginale->NUMRECU)
          //               ->update(['VALIDE' => 0]);

          //           // MISE À JOUR DES LIGNES DANS journal
          //           Journal::where('NUMRECU', $factureoriginale->NUMRECU)
          //               ->update(['VALIDE' => 0]);

          //       // creer la meme facture pour un nouveau eleve
          //       $matriculeNouvEleve = $request->input('eleve');
          //       $informationNouvEleve = Eleve::where('MATRICULE', $matriculeNouvEleve)->first();
          //       $nomCompletNouvEleve = $informationNouvEleve->NOM .' ' . $informationNouvEleve->PRENOM;
          //       $classeNouvEleve = $informationNouvEleve->CODECLAS;


          //           $parametrefacture = Params2::first();
          //           $ifuentreprise = $parametrefacture->ifu;
          //           $tokenentreprise = $parametrefacture->token;
          //           $taxe = $parametrefacture->taxe;
          //           $type = $parametrefacture->typefacture;



          //           $infoParamContrat = Paramcontrat::first();
          //           $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
          //           $anneeSuivante = $debutAnneeEnCours + 1;
          //           $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;


          //           // Fonction pour obtenir ou générer un numéro unique
          //           $getNumero = function ($matriculeNouvEleve, $dateOp) {
          //           $existingScolarite = Scolarite::where('MATRICULE', $matriculeNouvEleve)->where('DATEOP', $dateOp)->first();


          //           // Si une entrée existe, retourner son numéro
          //           if ($existingScolarite) {
          //             return $existingScolarite->NUMERO;
          //           }
                    
          //           // Sinon, générer un nouveau numéro basé sur le maximum existant
          //           return Scolarite::max('NUMERO') + 1; // Ajustement pour générer un nouveau numéro
          //           };

          //             // generer un nouveau NUMRECU
          //             $maxNUMRECUSco = Scolarite::max('NUMRECU') ?? 0;
          //             $nouvNUMRECU = $maxNUMRECUSco + 1;


                   

          //     // Préparez les données JSON pour l'API
          //     $jsonData = json_encode([
          //       "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
          //       // "aib" => "A",
          //       "type" => $type,
          //       "items" => $nouveauxItems,

          //       "client" => [
          //           // "ifu" => '',
          //           "name"=>  $nomCompletNouvEleve,
          //           // "contact" => "string",
          //           // "address"=> "string"
          //       ],
          //       "operator" => [
          //           "name" => " CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
          //       ],
          //       "payment" => [
          //           [
          //           "name" => "ESPECES",
          //             "amount" => intval($montantTotalFacOriginal)
          //           ]
          //         ],
          //   ]);
          //   // $jsonDataliste = json_encode($jsonData, JSON_FORCE_OBJECT);


          //   // dd($jsonData);
    
          //   // Définissez l'URL de l'API de facturation
          //   $apiUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice';
            
    
          //   // Définissez le jeton d'authentification

          //       $token = $tokenentreprise;
          //   //    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjEyMDEyMDE1NzQ1MDJ8VFMwMTAxMjE5OCIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTcyOTAwMTYwNiwiZXhwIjoxOTI0OTAyMDAwLCJpYXQiOjE3MjkwMDE2MDYsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.t0VBBtOig972FWCW2aFk7jyb-SHKv1iSZ9bkM-IGc2M";
          //       // $token = $tokenentreprise;
    
          //   // Effectuez la requête POST à l'API
          //   // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          //   $ch = curl_init($apiUrl);
          //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          //   curl_setopt($ch, CURLOPT_POST, true);
          //   curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
          //   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          //       'Content-Type: application/json',
          //       'Authorization: Bearer ' . $token
          //   ));
          //   curl_setopt($ch, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
    
          //   // Exécutez la requête cURL et récupérez la réponse
          //   $response = curl_exec($ch);
          //   // dd($response);
            
          //   // Vérifiez les erreurs de cURL
          //   if (curl_errno($ch)) {
          //       // echo 'Erreur cURL : ' . curl_error($ch);
          //       return back()->with('erreur','Erreur curl , mauvaise connexion a l\'API');
          //   }
            
          //   // Fermez la session cURL
          //   curl_close($ch);
            
          //   // Affichez la réponse de l'API
          //   $decodedResponse = json_decode($response, true);
          //   // dd($decodedResponse);
            
          //   // Vérifiez si l'UID est présent dans la réponse
          //   if (isset($decodedResponse['uid'])) {
          //       // L'UID de la demande
          //       $uid = $decodedResponse['uid'];
          //       // $taxb = 0.18;
            
          //       // Affichez l'UID
          //       // echo "L'UID de la demande est : $uid";

                
          //               // -------------------------------
          //                   //  RECUPERATION DE LA FACTURE PAR SON UID
          //               // -------------------------------

          //   // Définissez l'URL de l'API de confirmation de facture
          //   $recuperationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid;
    
          //   // Configuration de la requête cURL pour la confirmation
          //   $chRecuperation = curl_init($recuperationUrl);
          //   curl_setopt($chRecuperation, CURLOPT_RETURNTRANSFER, true);
          //   curl_setopt($chRecuperation, CURLOPT_CUSTOMREQUEST, 'GET');
          //   curl_setopt($chRecuperation, CURLOPT_HTTPHEADER, [
          //       'Authorization: Bearer ' . $token,
          //       'Content-Length: 0'
          //   ]);
          //   curl_setopt($chRecuperation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));

          //   // Exécutez la requête cURL pour la confirmation
          //   $responseRecuperation = curl_exec($chRecuperation);
          //   // dd($responseRecuperation);
          //   // Vérifiez les erreurs de cURL pour la confirmation


          //   // Fermez la session cURL pour la confirmation
          //   curl_close($chRecuperation);

          //       // Convertissez la réponse JSON en tableau associatif PHP
          //       $decodedDonneFacture = json_decode($responseRecuperation, true);
          //       // dd($decodedDonneFacture);

          //       $facturedetaille = json_decode($jsonData, true);
          //       $ifuEcoleFacture = $decodedDonneFacture['ifu'];
          //       $itemFacture = $decodedDonneFacture['items'];
          //       $jsonItem = json_encode($itemFacture);
          //       $doneeDetailleItemFacture = $itemFacture['0'];
          //       $nameItemFacture = $doneeDetailleItemFacture['name'];
          //       $prixTotalItemFacture = $doneeDetailleItemFacture['price'];
          //       $quantityItemFacture = $doneeDetailleItemFacture['quantity'];
          //       $taxGroupItemFacture = $doneeDetailleItemFacture['taxGroup'];
          //       // $idd = $responseRecuperation.ifu;
          //       $nameClient = $decodedDonneFacture['client']['name'];
          //       // dd($prixTotalItemFacture);

            
          //               // -------------------------------
          //                   //  CONFIRMATION DE LA FACTURE 
          //               // -------------------------------

          //       // ACTION pour la confirmation
          //       $actionConfirmation = 'confirm';
            
          //       // Définissez l'URL de l'API de confirmation de facture
          //       $confirmationUrl = 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$uid.'/'.$actionConfirmation;
            
          //       // Configuration de la requête cURL pour la confirmation
          //       $chConfirmation = curl_init($confirmationUrl);
          //       curl_setopt($chConfirmation, CURLOPT_RETURNTRANSFER, true);
          //       curl_setopt($chConfirmation, CURLOPT_CUSTOMREQUEST, 'PUT');
          //       curl_setopt($chConfirmation, CURLOPT_HTTPHEADER, [
          //           'Authorization: Bearer ' . $token,
          //           'Content-Length: 0'
          //       ]);
          //       curl_setopt($chConfirmation, CURLOPT_CAINFO, storage_path('certificates/cacert.pem'));
            
          //       // Exécutez la requête cURL pour la confirmation
          //       $responseConfirmation = curl_exec($chConfirmation);
            
          //       // Vérifiez les erreurs de cURL pour la confirmation
          //       if (curl_errno($chConfirmation)) {
          //           // echo 'Erreur cURL pour la confirmation : ' . curl_error($chConfirmation);/
          //           return redirect('classes')->with('erreur','Erreur curl pour la confirmation');

          //       }
            
          //       // Fermez la session cURL pour la confirmation
          //       curl_close($chConfirmation);
            
          //       // Convertissez la réponse JSON en tableau associatif PHP
          //       $decodedResponseConfirmation = json_decode($responseConfirmation, true);
          //       // dd($decodedResponseConfirmation);
    
    
          //       $codemecef = $decodedResponseConfirmation['codeMECeFDGI'];

          //       $counters = $decodedResponseConfirmation['counters'];

          //       $nim = $decodedResponseConfirmation['nim'];

          //       $dateTime = $decodedResponseConfirmation['dateTime'];

          //       // Générer le code QR
          //       $qrCodeString = $decodedResponseConfirmation['qrCode'];

          //       $reffactures = $nim.'-'.$counters;

          //       $reffacture = explode('/', $reffactures)[0];

          //       // gestion du code qr sous forme d'image

          //       $fileNameqrcode = $nomCompletNouvEleve . time() . '.png';
          //       $result = Builder::create()
          //           ->writer(new PngWriter())
          //           ->data($qrCodeString)
          //           ->size(100)
          //           // ->margin(10)
          //           ->build();

          //           $qrcodecontent = $result->getString();


     
                    
                     
                     
          //       // ********************************
                     
          //            // generer une valeur aleatoire comprise entre 10000000 et 99999999 et verifier si elle existe deja dans la table.
          //           // Si la valeur est déjà présente, exists() renverra true, et la boucle continuera à s'exécuter pour générer une nouvelle valeur.
          //           // Si la valeur n'est pas présente (c'est-à-dire qu'elle est unique), la condition exists() renverra false, et la boucle s'arrêtera.
          //           do {
          //               // Génère un nombre aléatoire entre 10000000 et 99999999
          //           $valeurDynamiqueNumerique = mt_rand(10000000, 99999999);
          //           } while (DB::table('paiementglobalcontrat')->where('reference_paiementcontrat', $valeurDynamiqueNumerique)->exists());


          //           // ENREGISTREMENT DANS LA TABLE INSCRIPTIONCONTRAT
          //            // Parcourir les mois cochés et insérer chaque id de mois dans la table Inscriptioncontrat
          //           //  foreach ($moisCoches as $id_moiscontrat) {
          //           //     if ($saveMois == 0) {
          //           //         Inscriptioncontrat::create([
          //           //              // 'id_paiementcontrat ' => $valeurDynamiqueidpaiemnetcontrat, 
          //           //              'id_contrat' => $idcontratEleve,
          //           //              'id_moiscontrat' => $id_moiscontrat,
          //           //              'anne_inscription' => $debutAnneeEnCours,
                                
          //           //          ]);
          //           //     }else{
          //           //         // 
          //           //     }
          //           //  }

          //            // recuperer les nom des mois cochee

          //           // Array des noms des mois
          //           // $nomsMoisCoches = [];

          //           // Parcourir les ID des mois cochés et obtenir leur nom correspondant
          //           // foreach ($moisCoches as $id_moiscontrat) {
          //           //     // Ici, vous pouvez récupérer le nom du mois à partir de votre modèle Mois
          //           //     $mois = Moiscontrat::where('id_moiscontrat', $id_moiscontrat)->first();
                        
          //           //     // Vérifiez si le mois existe
          //           //     if ($mois) {
          //           //         // Ajouter le nom du mois à l'array
          //           //         $nomsMoisCoches[] = $mois->nom_moiscontrat;
          //           //     }
          //           // }

          //           // Convertir le tableau en une chaîne de caractères
          //           // $moisConcatenes = implode(',', $nomsMoisCoches);
          //           // dd($moisConcatenes);
          //           // Récupérer la somme des montants de paiement précédents
                   

          //           // dd($idPaiementContrat);                

          //           // ENREGISTREMENT DANS LA TABLE PAIEMENTCONTRAT

          //           // dd($soldeavant_paiementcontrat);
          //           // Créer un objet DateTime à partir de la chaîne de caractères





          //           // CALCUL DU TOTALHT ET TOTALTVA

          //           $TOTALHT = $montanttotal / 1.18;
          //           $totalHTArrondi = 0;
          //           $TOTALTVA = 0;

          //           // ********************************

          //           // enregistrer les mois dans inscriptioncontrat

                    
          //           // 1) Exploser la chaîne en noms
          //           $moisNoms = explode(',', $moisConcatenes); 
          //           // Résultat : ['Novembre', 'Janvier']

          //           // 2) Récupérer les IDs en base pour ces noms
          //           $moisIds = Moiscontrat::whereIn('nom_moiscontrat', $moisNoms)
          //               ->pluck('id_moiscontrat')  // ou 'id' selon ta colonne PK
          //               ->toArray();

          //               // dd($moisIds); 

          //               // On récupère directement les noms
          //               $moisNoms = Moiscontrat::whereIn('id_moiscontrat', $moisIds)
          //               ->pluck('nom_moiscontrat')   // champ qui contient "Janvier", "Fevrier", etc.
          //               ->toArray();

          //               // dd($moisNoms); 

          //           // 1) Charge en une seule requête le mapping ID → nom de mois
          //           $moisMap = Moiscontrat::whereIn('id_moiscontrat', $moisIds)
          //           ->pluck('nom_moiscontrat', 'id_moiscontrat')
          //           ->toArray();

          //           // 2) Parcours chaque ID de mois
          //           foreach ($moisIds as $id_moiscontrat) {
          //           // Récupère le nom correspondant
          //           $mois = $moisMap[$id_moiscontrat] ?? null;
                    
          //           if (! $mois) {
          //               // ID inconnu, on skip
          //               continue;
          //           }

          //           // 3) Calcul du montant à payer (si certains mois ont un traitement spécial)
          //           //    Ici l’exemple montre le même montant pour tous mais tu peux adapter :
          //           if (in_array($mois, ['Decembre','Septembre','Avril'], true)) {
          //               $montantAPayer = 17000;
          //           } else {
          //               $montantAPayer = 17000;
          //           }

          //           // 4) Récupère la somme déjà versée pour ce mois
          //           $totalDejaPaye = Facturenormalise::where('idcontrat', $idContratNouvEleve)
          //               ->where('moispayes', $mois)
          //               ->sum('montant_par_mois') 
          //               ?? 0;


          //           // 5) Calcule le nouveau total si on ajoute ce mois
          //           $sommeTotale = $totalDejaPaye + $montantparmois;

          //           // 6) Décide si on doit “sauver” ce mois
          //           $saveMois = $sommeTotale < $montantAPayer ? 1 : 0;

          //           // dd($saveMois);

          //           // 7) Si oui, on crée l’inscription
          //           if ($saveMois == 0) {
          //               Inscriptioncontrat::create([
          //                   'id_contrat'       => $idContratNouvEleve,
          //                   'id_moiscontrat'   => $id_moiscontrat,
          //                   'anne_inscription' => $debutAnneeEnCours,
          //                   // ajoute ici les autres champs si besoin
          //               ]);
          //           }
          //           }


        
          //           // dd($ifuEcoleFacture);
          //           $facturenormalise = new Facturenormalise();
          //           $facturenormalise->id = $reffacture;
          //           $facturenormalise->codemecef = $codemecef;
          //           $facturenormalise->counters = $counters;
          //           $facturenormalise->nim = $nim;
          //           $facturenormalise->dateHeure = $dateTime;
          //           $facturenormalise->ifuEcole = $ifuEcoleFacture;
          //           $facturenormalise->MATRICULE = $matriculeNouvEleve;
          //           $facturenormalise->idcontrat = $idContratNouvEleve;
          //           $facturenormalise->moispayes = $moisConcatenes;
          //           $facturenormalise->classe = $classeNouvEleve;
          //           $facturenormalise->nom = $nameClient;
          //           $facturenormalise->itemfacture = $jsonItem;
          //           $facturenormalise->designation = 'Frais cantine pour: '.$moisConcatenes;
          //           $facturenormalise->montant_total = $montanttotal;
          //           $facturenormalise->TOTALHT = $totalHTArrondi;
          //           $facturenormalise->TOTALTVA = $TOTALTVA;
          //           $facturenormalise->montant_par_mois = $montantparmois;
          //           $facturenormalise->datepaiementcontrat = $datepaiementcontratNouveau;
          //           $facturenormalise->qrcode = $qrcodecontent;
          //           $facturenormalise->statut = 1;
          //           // $facturenormalise->type = "FC";
        
          //           $facturenormalise->save();



          //           $paramse = Params2::first(); 

          //           $logoUrl = $paramse ? $paramse->logoimage: null; 
                
          //           $NOMETAB = $paramse->NOMETAB;

          //           Session::put('factureconfirm', $decodedResponseConfirmation);
          //           Session::put('fileNameqrcode', $fileNameqrcode);
          //           Session::put('facturedetaille', $facturedetaille);
          //           Session::put('reffacture', $reffacture);
          //           Session::put('classeeleve', $classeNouvEleve);
          //           Session::put('nomcompleteleve', $nomCompletNouvEleve);
          //           // Session::put('toutmoiscontrat', $toutmoiscontrat);
          //           Session::put('nameItemFacture', $nameItemFacture);
          //           Session::put('prixTotalItemFacture', $prixTotalItemFacture);
          //           Session::put('quantityItemFacture', $quantityItemFacture);
          //           Session::put('taxGroupItemFacture', $taxGroupItemFacture);
          //           Session::put('ifuEcoleFacture', $ifuEcoleFacture);
          //           Session::put('qrCodeString', $qrCodeString);
          //           Session::put('itemFacture', $itemFacture);
          //           Session::put('montanttotal', $montanttotal);
          //           Session::put('totalHTArrondi', $totalHTArrondi);
          //           Session::put('TOTALTVA', $TOTALTVA);
          //           Session::put('montantmoiscontrat', $montantparmois);
          //           Session::put('qrcodecontent', $qrcodecontent);
          //           Session::put('NOMETAB', $NOMETAB);
          //           Session::put('nim', $nim);
          //           Session::put('datepaiementcontrat', $datepaiementcontratNouveau);
          //           Session::put('dateTime', $dateTime);
          //           // Session::put('nometab', $nometab);
          //           // Session::put('villeetab', $villeetab);



                
          //           return view('pages.Etats.pdffacture', [
          //               'factureconfirm' => $decodedResponseConfirmation,
          //               'fileNameqrcode' => $fileNameqrcode,
          //               'facturedetaille' => $facturedetaille,
          //               'reffacture' => $reffacture,
          //               'ifuEcoleFacture' => $ifuEcoleFacture,
          //               'nameItemFacture' => $nameItemFacture,
          //               'prixTotalItemFacture' => $prixTotalItemFacture,
          //               'quantityItemFacture' => $quantityItemFacture,
          //               'taxGroupItemFacture' => $taxGroupItemFacture,
          //               'classeeleve' => $classeNouvEleve,
          //               'nomcompleteleve' => $nomCompletNouvEleve,
          //               // 'toutmoiscontrat' => $toutmoiscontrat,
          //               'qrCodeString' => $qrCodeString,
          //               'logoUrl' => $logoUrl,
          //               'itemFacture' => $itemFacture,
          //               'montanttotal' => $montanttotal,
          //               'qrcodecontent' => $qrcodecontent,
          //               'NOMETAB' => $NOMETAB,
          //               'nim' => $nim,
          //               'datepaiementcontrat' => $datepaiementcontratNouveau,
          //               'dateTime' => $dateTime,
          //               'totalHTArrondi' => $totalHTArrondi,
          //               'TOTALTVA' => $TOTALTVA,
          //               // 'villeetab' => $villeetab,
          //               // 'qrCodeImage' => $qrCodeImage,
                
          //                   ]);

          //                   }

          // break;

            // fin de la creation de la facture


            case 'corriger_paiement':
                // dd('corriger_mois');

                        // MISE À JOUR DES LIGNES DANS scolarite
                        Scolarite::where('NUMRECU', $factureoriginale->NUMRECU)
                            ->update(['VALIDE' => 0]);

                        // MISE À JOUR DES LIGNES DANS journal
                        Journal::where('NUMRECU', $factureoriginale->NUMRECU)
                            ->update(['VALIDE' => 0]);


                    // DEBUT DE LA CREATION DE LA FACTURE



                    $messages = [];
                    $errors = [];
                    $eleve = Eleve::where('MATRICULE', $matriculeeleve)->first();
                    $parametrefacture = Params2::first();
                    $ifuentreprise = $parametrefacture->ifu;
                    $tokenentreprise = $parametrefacture->token;
                    $taxe = $parametrefacture->taxe;
                    $type = $parametrefacture->typefacture;



                    $infoParamContrat = Paramcontrat::first();
                    $debutAnneeEnCours = $infoParamContrat->anneencours_paramcontrat;
                    $anneeSuivante = $debutAnneeEnCours + 1;
                    $anneeScolaireEnCours = $debutAnneeEnCours.'-'.$anneeSuivante;

                    

                    // Fonction pour obtenir ou générer un numéro unique
                    $getNumero = function ($matriculeeleve, $dateOp) {
                    $existingScolarite = Scolarite::where('MATRICULE', $matriculeeleve)->where('DATEOP', $dateOp)->first();
                    
                    // Si une entrée existe, retourner son numéro
                    if ($existingScolarite) {
                      return $existingScolarite->NUMERO;
                    }
                    
                    // Sinon, générer un nouveau numéro basé sur le maximum existant
                    return Scolarite::max('NUMERO') + 1; // Ajustement pour générer un nouveau numéro
                    };

                      // generer un nouveau NUMRECU
                      $maxNUMRECUSco = Scolarite::max('NUMRECU') ?? 0;
                      $nouvNUMRECU = $maxNUMRECUSco + 1;

                     // Enregistrer le montant de la scolarité si présent et supérieur à 0
                      if ($request->filled('scolarite') && $request->input('scolarite') > 0) {
                          $existingScolarite = Scolarite::where('MATRICULE', $matriculeeleve)
                              ->where('DATEOP', $request->input('date_operation'))
                              ->where('MONTANT', $request->input('scolarite'))
                              ->where('AUTREF', '1') // Scolarité
                              ->first();
              
                          if ($existingScolarite) {
                              $errors[] = 'Un paiement de scolarité similaire existe déjà pour cet élève.';
                          } else {
                              $scolarite = new Scolarite();
                              $scolarite->MATRICULE = $matriculeeleve;
                              $scolarite->DATEOP = $request->input('date_operation');
                              $scolarite->MODEPAIE = $request->input('mode_paiement');
                              $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                              $scolarite->ANSCOL = $eleve->anneeacademique;
                              $scolarite->NUMERO = $getNumero($matriculeeleve, $request->input('date_operation'));
                              $scolarite->NUMRECU = $nouvNUMRECU;
                              $scolarite->MONTANT = $request->input('scolarite');
                              $scolarite->AUTREF = '1'; // Scolarité
                              $scolarite->VALIDE = 1;
                              $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                              $scolarite->save();
              
                              // Enregistrement dans Journal
                              $journal = new Journal();
                              $journal->LIBELOP = 'Scolarité de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                              $journal->DATEOP = $request->input('date_operation');
                              $journal->MODEPAIE = $request->input('mode_paiement');
                              $journal->ANSCOL = $eleve->anneeacademique;
                              $journal->NUMRECU = $nouvNUMRECU;
                              $journal->DEBIT = $request->input('scolarite');
                              $journal->NumFRais = '1'; // Scolarité
                              $journal->VALIDE = 1;
                              $journal->SIGNATURE = session()->get('nom_user');
                              $journal->save();
                                              
                              $messages[] = 'Le montant de la scolarité a été enregistré avec succès.';
                          }
                      }
                      
                      // Enregistrer le montant de l'arrière si présent et supérieur à 0
                      if ($request->filled('arriere') && $request->input('arriere') > 0) {
                        $existingScolarite = Scolarite::where('MATRICULE', $matriculeeleve)
                            ->where('DATEOP', $request->input('date_operation'))
                            ->where('MONTANT', $request->input('arriere'))
                            ->where('AUTREF', '2') // Arriéré
                            ->first();

                        if ($existingScolarite) {
                            $errors[] = 'Un arriéré similaire existe déjà pour cet élève.';
                        } else {
                            $scolarite = new Scolarite();
                            $scolarite->MATRICULE = $matriculeeleve;
                            $scolarite->DATEOP = $request->input('date_operation');
                            $scolarite->MODEPAIE = $request->input('mode_paiement');
                            $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                            $scolarite->ANSCOL = $eleve->anneeacademique;
                            $scolarite->NUMERO = $getNumero($matriculeeleve, $request->input('date_operation'));
                            $scolarite->NUMRECU = $nouvNUMRECU;
                            $scolarite->MONTANT = $request->input('arriere');
                            $scolarite->AUTREF = '2'; // Arriéré
                            $scolarite->VALIDE = 1;
                            $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                            $scolarite->save();

                            // Enregistrement dans Journal
                            $journal = new Journal();
                            $journal->LIBELOP = 'Arriéré de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                            $journal->DATEOP = $request->input('date_operation');
                            $journal->MODEPAIE = $request->input('mode_paiement');
                            $journal->ANSCOL = $eleve->anneeacademique;
                            $journal->NUMRECU = $nouvNUMRECU;
                            $journal->DEBIT = $request->input('arriere');
                            $journal->VALIDE = 1;
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
                            $existingScolarite = Scolarite::where('MATRICULE', $matriculeeleve)
                                ->where('DATEOP', $request->input('date_operation'))
                                ->where('MONTANT', $libelle)
                                ->where('AUTREF', strval($i + 3)) // Type de libellé
                                ->first();

                            if ($existingScolarite) {
                                $errors[] = 'Un paiement additionnel similaire existe déjà pour cet élève (Libellé-' . $i . ').';
                            } else {
                                $scolarite = new Scolarite();
                                $scolarite->MATRICULE = $matriculeeleve;
                                $scolarite->DATEOP = $request->input('date_operation');
                                $scolarite->MODEPAIE = $request->input('mode_paiement');
                                $scolarite->DATESAISIE = $request->input('date_operation'); // Enregistrer la date actuelle
                                $scolarite->ANSCOL = $eleve->anneeacademique;
                                $scolarite->NUMERO = $getNumero($matriculeeleve, $request->input('date_operation'));
                                $scolarite->NUMRECU = $nouvNUMRECU;
                                $scolarite->MONTANT = $libelle;
                                $scolarite->VALIDE = 1;
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
                                $journal->NUMRECU = $nouvNUMRECU;
                                $journal->DEBIT = $libelle;
                                $journal->VALIDE = 1;
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
                                'numeroRecu' => $nouvNUMRECU,
                                'signature' => session()->get('nom_user'),
                                'modePaiement' => $request->input('mode_paiement'),
                                'montantdu' => $request->input('montant_total'),
                            ]);



                            // Choix du texte selon le mode
                            $libelleModeNouv = match ($request->input('mode_paiement')) {
                                1 => 'ESPECES',
                                2 => 'CHEQUES',
                                default => 'AUTRE',
                            };
                        
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
                      
                      // montant total des items 

                      // Une fois que tous les items sont ajoutés :
                      $montant_total = array_sum(array_column($items, 'price'));
                      //  dd($items); // Utilisez cette ligne pour déboguer et vérifier les données
                    
                      // Préparez les données JSON pour l'API
                      // $nomcompleteleve = $eleve->NOM . ' ' . $eleve->PRENOM;
                      // nomcompleteleve


                    $jsonData = json_encode([
                        "ifu" => $ifuentreprise, // ici on doit rendre la valeur de l'ifu dynamique
                        // "aib" => "A",
                        "type" => $type,
                        "items" => $items,

                        "client" => [
                            // "ifu" => '',
                            "name"=>  $nomcompleteleve,

                        ],
                        "operator" => [
                            "name" => "CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                        ],
                        "payment" => [
                            [
                            "name" => $libelleModeNouv,
                              "amount" => intval($montant_total)
                            ]
                          ],
                    ]);

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
                        $uid = $decodedResponse['uid'];

                        $total = $decodedResponse['total']; 

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
                        // dd($responseRecuperation);
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


                         $InfoUtilisateurConnecter =  User::where('id', $id_usercontrat)->first();
                         $idUserCont =  $InfoUtilisateurConnecter->id;
                         $idUserContInt = intval($idUserCont);

                       

                        
                        $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateTime)->format('Y-m-d H:i:s');

                    $data = [
                      'uid' => $uid,
                      'id' => $reffacture,
                      'codemecef' => $codemecef,
                      'counters' => $counters,
                      'nim' => $nim,
                      'dateHeure' => $dateTime,
                      'ifuEcole' => $ifuEcoleFacture,
                      'MATRICULE' => $matriculeeleve,
                      'nom' => $nameClient,
                      'classe' => $eleve->CODECLAS,
                      'itemfacture' => $jsonItem, // Conversion en JSON
                      'montant_total' => array_sum(array_column($itemFacture, 'price')),
                      'tax_group' => $taxGroupItemFacture,
                      'date_time' => $dateTime,
                      'qrcode' => $qrcodecontent,
                      'statut' => 1,
                      'NUMRECU' => $nouvNUMRECU,
                      'mode_paiement' => $request->input('mode_paiement'),
                  ];

                  // dd($data);
                  
                  DB::table('facturescolarit')->insert($data);

                        $paramse = Params2::first(); 

                        $logoUrl = $paramse ? $paramse->logoimage: null; 
                    
                        $NOMETAB = $paramse->NOMETAB;

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
                          'dateTime' => $dateTime,                         // Date et heure
                          'NUMRECU' => $nouvNUMRECU,                         
                          'mode_paiement' => $request->input('mode_paiement'),
                           'InfoUtilisateurConnecter'=> $InfoUtilisateurConnecter,
                            'idUserCont'=> $idUserCont,
                            'idUserContInt'=> $idUserContInt
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
                            'entete' => $entete,

                            // Optionnel : logo de l'école (si disponible)
                            'logoUrl' => $logoUrl ?? null,

                            'mode_paiement' => $request->input('mode_paiement'),

                            // Données supplémentaires si nécessaires
                            'montanttotal' => $total,
                            'datepaiementcontrat' => $datepaiementcontrat ?? null,
                            'InfoUtilisateurConnecter'=> $InfoUtilisateurConnecter,
                            'idUserCont'=> $idUserCont,
                            'idUserContInt'=> $idUserContInt,
                          ]);


                    }


                    // FIN DE LA CREATION DE LA FACTURE

      }
    
        // return back()->with('status', "Facture d'avoir generer avec succes");
    
     
        }
        } else {
            // dd('codemecef incorrect');
            return back()->with('erreur', "Le codemecef entrer ne correspond pas a celui de la facture originale.");

        }

       
        
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
    //$eleves = Eleve::with('classe', 'serie')->get();
    $eleves = Eleve::all();
    $promotions = Promo::all();
    $series = Serie::all();

     // On récupère uniquement les codes des classes
    $classes = Classes::pluck('CODECLAS');

    // Préparation des effectifs par classe
    $effectifsParClasse = $classes->mapWithKeys(function ($codeClasse) {
        // Récupération de tous les élèves de cette classe
        $elevesClasse = Eleve::where('CODECLAS', $codeClasse);

        return [
            $codeClasse => [
                'total'        => $elevesClasse->count(),
                'garcons'      => $elevesClasse->where('SEXE', 1)->count(),
                'filles'       => $elevesClasse->where('SEXE', 2)->count(),
                'redoublants'  => $elevesClasse->where('STATUTG', 1)->count(),
            ]
        ];
    });
    //dd($effectifsParClasse);
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
          $serie->SERIE => [ 
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
    $scolarites = Scolarite::where('VALIDE', '1')->get()->groupBy('MATRICULE'); // Regrouper les paiements par matricule

    $resultats = $eleves->map(function($eleve) use ($scolarites) {
        $montantPaye = $scolarites->get($eleve->MATRICULE, collect())->sum('MONTANT'); // Somme des montants payés
        $montantAPayer = ($eleve->APAYER + $eleve->FRAIS1 + $eleve->FRAIS2 + $eleve->FRAIS3 + $eleve->FRAIS4); // Montant à payer de l'élève
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
  
    //   public function paiementeleve($matricule)
    // {
    //     if (Session::has('account')) {
    //         // Retrieve the student details
    //         $eleve = Eleve::where('MATRICULE', $matricule)->first();
    //         $scolarite = Scolarite::where('MATRICULE', $matricule)->get();
    //         $libelle = Params2::first();
    //         $echeanche = Echeance::first();
            
    //         // Calculate the total amounts based on AUTREF
    //         $totalArriere = Scolarite::where('MATRICULE', $matricule)
    //             ->where('AUTREF', '2')
    //             ->sum('MONTANT');
    
    //         $totalScolarite = Scolarite::where('MATRICULE', $matricule)
    //             ->where('AUTREF', '1')
    //             ->sum('MONTANT');
    
    //         $totalLibelle1 = Scolarite::where('MATRICULE', $matricule)
    //             ->where('AUTREF', '3')
    //             ->sum('MONTANT');
    
    //         $totalLibelle2 = Scolarite::where('MATRICULE', $matricule)
    //             ->where('AUTREF', '4')
    //             ->sum('MONTANT');
    
    //         $totalLibelle3 = Scolarite::where('MATRICULE', $matricule)
    //             ->where('AUTREF', '5')
    //             ->sum('MONTANT');
            
    //         $totalLibelle4 = Scolarite::where('MATRICULE', $matricule)
    //           ->where('AUTREF', '6')
    //           ->sum('MONTANT');
    
    //         // Pass the totals along with other data to the view
    //         return view('pages.inscriptions.Paiement', compact(
    //             'eleve', 'scolarite', 'libelle', 
    //             'totalArriere', 'totalScolarite', 'totalLibelle1', 
    //             'totalLibelle2', 'totalLibelle3'
    //         ));
    //     }
    //     return redirect('/');
    // }
 
    public function paiementeleve($matricule)
    {
        if (Session::has('account')) {
            // Retrieve the student details
            $eleve = Eleve::where('MATRICULE', $matricule)->first();
            $scolarite = Scolarite::where('MATRICULE', $matricule)->where('VALIDE', '1')->get();
            $libelle = Params2::first();
            $echeanche = Echeance::first();
            
            // Calculate the total amounts based on AUTREF
            $totalArriere = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '2')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalScolarite = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '1')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle1 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '3')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle2 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '4')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
    
            $totalLibelle3 = Scolarite::where('MATRICULE', $matricule)
                ->where('AUTREF', '5')
                ->where('VALIDE', '1')
                ->sum('MONTANT');
            
            $totalLibelle4 = Scolarite::where('MATRICULE', $matricule)
              ->where('AUTREF', '6')
              ->where('VALIDE', '1')
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

          // generer un nouveau NUMRECU
            $maxNUMRECUSco = Scolarite::max('NUMRECU') ?? 0;
            $nouvNUMRECU = $maxNUMRECUSco + 1;
            // dd($sco);

          
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
                  $scolarite->NUMRECU = $nouvNUMRECU;
                  $scolarite->MONTANT = $request->input('scolarite');
                  $scolarite->AUTREF = '1'; // Scolarité
                  $scolarite->VALIDE = 1;
                  $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                  $scolarite->save();
  
                  // Enregistrement dans Journal
                  $journal = new Journal();
                  $journal->LIBELOP = 'Scolarité de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                  $journal->DATEOP = $request->input('date_operation');
                  $journal->MODEPAIE = $request->input('mode_paiement');
                  $journal->ANSCOL = $eleve->anneeacademique;
                  $journal->NUMRECU = $nouvNUMRECU;
                  $journal->DEBIT = $request->input('scolarite');
                  $journal->NumFRais = '1'; // Scolarité
                  $journal->VALIDE = 1;
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
                $scolarite->NUMRECU = $nouvNUMRECU;
                $scolarite->MONTANT = $request->input('arriere');
                $scolarite->AUTREF = '2'; // Arriéré
                $scolarite->VALIDE = 1; // Arriéré
                $scolarite->SIGNATURE = session()->get('nom_user'); // Récupérer la valeur depuis la session
                $scolarite->save();

                // Enregistrement dans Journal
                $journal = new Journal();
                $journal->LIBELOP = 'Arriéré de ' . $eleve->NOM . ' ' . $eleve->MATRICULE;
                $journal->DATEOP = $request->input('date_operation');
                $journal->MODEPAIE = $request->input('mode_paiement');
                $journal->ANSCOL = $eleve->anneeacademique;
                $journal->NUMRECU = $nouvNUMRECU;
                $journal->DEBIT = $request->input('arriere');
                $journal->NumFRais = '2'; // Arriéré
                $journal->VALIDE = 1;
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
                    $scolarite->NUMRECU = $nouvNUMRECU;
                    $scolarite->MONTANT = $libelle;
                    $scolarite->VALIDE = 1;
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
                    $journal->NUMRECU = $nouvNUMRECU;
                    $journal->DEBIT = $libelle;
                    $journal->VALIDE = 1;
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
            'numeroRecu' => $nouvNUMRECU,
            'signature' => session()->get('nom_user'),
            'modePaiement' => $request->input('mode_paiement'),
            'montantdu' => $request->input('montant_total'),
        ]);

  
          
// VERIFICATION SI LA CONNEXION ET LES INFOS TYPEFAC , TOKEN , IFU ET AUTRE SONT OK POUR NORMALIS2E LA FACTURE , SINON , ON FAIT UNE FACTURE SIMPLE QU'ON ENREGISTRE DANS LA TABLE FACTRESCOLARIT AVEC L'ATTRIBUT TYPEFAC a 1 POUR SPECIFIER QUE C'EST UNE FACTURE SIMPLE 

//--------------------------------------------------------------------------



        // Donnée commune au deux type de facture 

                    // Choix du texte selon le mode
                    $libelleMode = match ($request->input('mode_paiement')) {
                        1 => 'ESPECES',
                        2 => 'CHEQUES',
                        default => 'AUTRE',
                    };
       
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
                    
                    // montant total des items 

                    // Une fois que tous les items sont ajoutés :
                    $montant_total = array_sum(array_column($items, 'price'));
                    //  dd($items); // Utilisez cette ligne pour déboguer et vérifier les données
                  
                    // Préparez les données JSON pour l'API
                    $nomcompleteleve = $eleve->NOM . ' ' . $eleve->PRENOM;



        // 1) Vérifier qu'aucune n'est null (strict) — permet 0 ou '0' pour taxe si c'est valide
        if (is_null($ifuentreprise) || is_null($tokenentreprise) || is_null($taxe) || is_null($type)) {
            // Au moins une est manquante -> B
                      // dd('B NON OK NON CONNECTER')

                    // ref 

                    $jsonItem = json_encode($items);

                    // Générer une référence locale
                    $maxFactureCount = DB::table('facturescolarit')->where('typefac', 1)->count();
                    $reffactureLocal = 'FAC-' . sprintf('%06d', $maxFactureCount + 1);
                    
                    $dateInput = $request->input('date_operation');
                    $dateTime = Carbon::parse($dateInput)->format('Y-m-d H:i:s');
                    $data = [
                      'id' => $reffactureLocal,
                      'dateHeure' => $dateTime,
                      // 'ifuEcole' => $ifuEcoleFacture,
                      'MATRICULE' => $matricule,
                      'nom' => $nomcompleteleve,
                      'classe' => $eleve->CODECLAS,
                      'itemfacture' => $jsonItem, // Conversion en JSON
                      'montant_total' => $montant_total,
                      // 'tax_group' => $taxGroupItemFacture,
                      'date_time' => $dateTime,
                      // 'qrcode' => $qrcodecontent,
                      'statut' => 1,
                      'typefac' => 1,  // 1 pour facture simple et 0 pour facture normalisée
                      'NUMRECU' => $nouvNUMRECU,
                      'mode_paiement' => $request->input('mode_paiement'),
                  ];
                  
                  DB::table('facturescolarit')->insert($data);

                  $paramse = Params2::first(); 

                  $logoUrl = $paramse ? $paramse->logoimage: null; 
              
                  $NOMETAB = $paramse->NOMETAB;

                  $rtfContent = Params2::first()->EnteteRecu;
                  $document = new Document($rtfContent);
                  $formatter = new HtmlFormatter();
                  $enteteNonStyle = $formatter->Format($document);
                  $entete = '
                  <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                      <style>
                          p { margin: 0; padding: 0; line-height: 1.2; }
                          span { display: inline-block; }
                      </style>
                      ' . $enteteNonStyle . '
                  </div>
                  ';




                  return view('pages.inscriptions.pdfpaiementscononnormalise', [
                    // Informations principales de la facture
                    'reffacture' => $reffactureLocal,
                    'entete' => $entete,

                    // Informations sur l'élève
                    'classeeleve' => $eleve->CODECLAS,
                    'nomcompleteleve' => $nomcompleteleve,

                    // Détails des items de la facture
                    'itemFacture' => $items,

                    // Métadonnées supplémentaires
                    'NOMETAB' => $NOMETAB,
                    // 'nim' => $nim,
                    'dateTime' => $dateTime,

                    'logoUrl' => $logoUrl ?? null,

                    'mode_paiement' => $request->input('mode_paiement'),

                    // Données supplémentaires si nécessaires
                    'montanttotal' => $montant_total,
                    'datepaiementcontrat' => $datepaiementcontrat ?? null,
                  ]);


                  // dd('B NON OK NON CONNECTER');
        }

        // 2) Vérifier la connexion vers Google
        try {
            // URL rapide pour test ; timeout court recommandé
            $response = Http::timeout(100)->get('https://www.google.com/');

            if ($response->ok()) { // code 2xx (204 inclus)
              // Connexion ok et les donnees de params2 necessaire a la normalisation eistent , du coup , on fait une facture normalisée


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
                        // "ifu" => "0202380068074",
                        "name"=>  $nomcompleteleve,
                        // "contact" => "string",
                        // "address"=> "string"
                    ],
                    "operator" => [
                        "name" => "CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)"
                    ],
                    "payment" => [
                        [
                        "name" => $libelleMode,
                        // "name" => "ESPECES",
                        "amount"=> intval($montant_total)
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
              $uid = $decodedResponse['uid']; 

              $total = $decodedResponse['total']; 
              //  dd($total);
              // }


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
                                    'id' => $reffacture,
                                    'codemecef' => $codemecef,
                                    'counters' => $counters,
                                    'nim' => $nim,
                                    'dateHeure' => $dateTime,
                                    'ifuEcole' => $ifuEcoleFacture,
                                    'MATRICULE' => $matricule,
                                    'nom' => $nameClient,
                                    'classe' => $eleve->CODECLAS,
                                    'itemfacture' => $jsonItem, // Conversion en JSON
                                    'montant_total' => array_sum(array_column($itemFacture, 'price')),
                                    'tax_group' => $taxGroupItemFacture,
                                    'date_time' => $dateTime,
                                    'qrcode' => $qrcodecontent,
                                    'statut' => 1,
                                    'NUMRECU' => $nouvNUMRECU,
                                    'mode_paiement' => $request->input('mode_paiement'),
                                ];
                                
                                DB::table('facturescolarit')->insert($data);

                                // Chemin pour enregistrer l'image QR Code
                                // $path = 'qrcodes/' . $fileNameqrcode;

                                // Sauvegarder l'image
                                // Storage::disk('public')->put($path, $qrcodecontent);

                                $paramse = Params2::first(); 

                      $logoUrl = $paramse ? $paramse->logoimage: null; 
                  
                      $NOMETAB = $paramse->NOMETAB;

                      $rtfContent = Params2::first()->EnteteRecu;
                      $document = new Document($rtfContent);
                      $formatter = new HtmlFormatter();
                      $enteteNonStyle = $formatter->Format($document);
                      $entete = '
                      <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                          <style>
                              p { margin: 0; padding: 0; line-height: 1.2; }
                              span { display: inline-block; }
                          </style>
                          ' . $enteteNonStyle . '
                      </div>
                      ';

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
                'dateTime' => $dateTime,                         // Date et heure
                'NUMRECU' => $nouvNUMRECU,                         
                'mode_paiement' => $request->input('mode_paiement')
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
                'entete' => $entete,

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

                'mode_paiement' => $request->input('mode_paiement'),

                // Données supplémentaires si nécessaires
                'montanttotal' => $total,
                'datepaiementcontrat' => $datepaiementcontrat ?? null,
              ]);

              }

                // dd('A OK CONNECTER');
            }

            // reachable mais mauvaise réponse -> B, autrement dire pas de connexion , du coup on fait une facture simple
                    // ref 

                    $jsonItem = json_encode($items);

                    // Générer une référence locale
                    $maxFactureCount = DB::table('facturescolarit')->where('typefac', 1)->count();
                    $reffactureLocal = 'FAC-' . sprintf('%06d', $maxFactureCount + 1);
                    
                    $dateInput = $request->input('date_operation');
                    $dateTime = Carbon::parse($dateInput)->format('Y-m-d H:i:s');
                    $data = [
                      'id' => $reffactureLocal,
                      'dateHeure' => $dateTime,
                      // 'ifuEcole' => $ifuEcoleFacture,
                      'MATRICULE' => $matricule,
                      'nom' => $nomcompleteleve,
                      'classe' => $eleve->CODECLAS,
                      'itemfacture' => $jsonItem, // Conversion en JSON
                      'montant_total' => $montant_total,
                      // 'tax_group' => $taxGroupItemFacture,
                      'date_time' => $dateTime,
                      // 'qrcode' => $qrcodecontent,
                      'statut' => 1,
                      'typefac' => 1,  // 1 pour facture simple et 0 pour facture normalisée
                      'NUMRECU' => $nouvNUMRECU,
                      'mode_paiement' => $request->input('mode_paiement'),
                  ];
                  
                  DB::table('facturescolarit')->insert($data);

                  $paramse = Params2::first(); 

                  $logoUrl = $paramse ? $paramse->logoimage: null; 
              
                  $NOMETAB = $paramse->NOMETAB;

                  $rtfContent = Params2::first()->EnteteRecu;
                  $document = new Document($rtfContent);
                  $formatter = new HtmlFormatter();
                  $enteteNonStyle = $formatter->Format($document);
                  $entete = '
                  <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                      <style>
                          p { margin: 0; padding: 0; line-height: 1.2; }
                          span { display: inline-block; }
                      </style>
                      ' . $enteteNonStyle . '
                  </div>
                  ';




                  return view('pages.inscriptions.pdfpaiementscononnormalise', [
                    // Informations principales de la facture
                    'reffacture' => $reffactureLocal,
                    'entete' => $entete,

                    // Informations sur l'élève
                    'classeeleve' => $eleve->CODECLAS,
                    'nomcompleteleve' => $nomcompleteleve,

                    // Détails des items de la facture
                    'itemFacture' => $items,

                    // Métadonnées supplémentaires
                    'NOMETAB' => $NOMETAB,
                    // 'nim' => $nim,
                    'dateTime' => $dateTime,

                    'logoUrl' => $logoUrl ?? null,

                    'mode_paiement' => $request->input('mode_paiement'),

                    // Données supplémentaires si nécessaires
                    'montanttotal' => $montant_total,
                    'datepaiementcontrat' => $datepaiementcontrat ?? null,
                  ]);


        } catch (\Throwable $e) {
            // Exception (timeout, DNS, etc.) -> B, exception , on fait aussi une facture simple 
            Log::warning('checkAll network error: '.$e->getMessage());
                    // ref 

                    $jsonItem = json_encode($items);

                    // Générer une référence locale
                    $maxFactureCount = DB::table('facturescolarit')->where('typefac', 1)->count();
                    $reffactureLocal = 'FAC-' . sprintf('%06d', $maxFactureCount + 1);
                    
                    $dateInput = $request->input('date_operation');
                    $dateTime = Carbon::parse($dateInput)->format('Y-m-d H:i:s');
                    $data = [
                      'id' => $reffactureLocal,
                      'dateHeure' => $dateTime,
                      // 'ifuEcole' => $ifuEcoleFacture,
                      'MATRICULE' => $matricule,
                      'nom' => $nomcompleteleve,
                      'classe' => $eleve->CODECLAS,
                      'itemfacture' => $jsonItem, // Conversion en JSON
                      'montant_total' => $montant_total,
                      // 'tax_group' => $taxGroupItemFacture,
                      'date_time' => $dateTime,
                      // 'qrcode' => $qrcodecontent,
                      'statut' => 1,
                      'typefac' => 1,  // 1 pour facture simple et 0 pour facture normalisée
                      'NUMRECU' => $nouvNUMRECU,
                      'mode_paiement' => $request->input('mode_paiement'),
                  ];
                  
                  DB::table('facturescolarit')->insert($data);

                  $paramse = Params2::first(); 

                  $logoUrl = $paramse ? $paramse->logoimage: null; 
              
                  $NOMETAB = $paramse->NOMETAB;

                  $rtfContent = Params2::first()->EnteteRecu;
                  $document = new Document($rtfContent);
                  $formatter = new HtmlFormatter();
                  $enteteNonStyle = $formatter->Format($document);
                  $entete = '
                  <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                      <style>
                          p { margin: 0; padding: 0; line-height: 1.2; }
                          span { display: inline-block; }
                      </style>
                      ' . $enteteNonStyle . '
                  </div>
                  ';




                  return view('pages.inscriptions.pdfpaiementscononnormalise', [
                    // Informations principales de la facture
                    'reffacture' => $reffactureLocal,
                    'entete' => $entete,

                    // Informations sur l'élève
                    'classeeleve' => $eleve->CODECLAS,
                    'nomcompleteleve' => $nomcompleteleve,

                    // Détails des items de la facture
                    'itemFacture' => $items,

                    // Métadonnées supplémentaires
                    'NOMETAB' => $NOMETAB,
                    // 'nim' => $nim,
                    'dateTime' => $dateTime,

                    'logoUrl' => $logoUrl ?? null,

                    'mode_paiement' => $request->input('mode_paiement'),

                    // Données supplémentaires si nécessaires
                    'montanttotal' => $montant_total,
                    'datepaiementcontrat' => $datepaiementcontrat ?? null,
                  ]);
        }


        // -------------------------------
              //  CREATION DE LA FACTURE
        // -------------------------------

    




      //--------------------------------------------------------------------------



          
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



  // -------------------------------------------

  public function listeavoirfacscolarit() 
  {
    $listeavoirfacscolarit = Facturescolarit::all();

    // dd($listeavoirfacscolarit);

    return view('pages.facture.listeavoirfacscolarit', compact('listeavoirfacscolarit'));

  }
  

  // -------------------------------------------
  
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
      $modifyeleve->MATRICULEX = $request->input('matriculex');
      $modifyeleve->update();
      return back()->with('status','Modifier avec succes');
      
    } else {
      return back()->withErrors('Erreur lors de la modification.');
      
    }
    
  }
  
  public function modifieleve(Request $request, $MATRICULE) {
    $modifieleve = Eleve::find($MATRICULE);
    // $matx = $modifieleve->MATRICULEX;
    
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
      // $modifieleve->MATRICULEX = $matx;
      $modifieleve->MATRICULEX = $request->input('matriculex');
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