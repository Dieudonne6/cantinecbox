<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\Eleveplus;
use Illuminate\Support\Facades\DB;

use App\Models\Duplicatafacture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
        return view('pages.inscriptions.listedeseleves');
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
    public function profil(){
        return view('pages.inscriptions.profil');

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
    public function echeancier(){
        return view('pages.inscriptions.echeancier');
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

    public function paiementeleve(){
        if(Session::has('account')){
        // $duplicatafactures = Duplicatafacture::all();

        return view('pages.inscriptions.Paiement');
        } 
        return redirect('/');
    } 
    public function modifierclasse(){
        if(Session::has('account')){
        // $duplicatafactures = Duplicatafacture::all();

        return view('pages.inscriptions.modifierclasse');
        } 
        return redirect('/');
    } 
    public function majpaiementeleve(){
        if(Session::has('account')){
        // $duplicatafactures = Duplicatafacture::all();

        return view('pages.inscriptions.MajPaiement');
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
        
        public function certificatsolarite(){
            return view('pages.inscriptions.certificatsolarite');
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

    public function listedesreductions(){
        $eleves = Eleve::all();
        $reductions = Reduction::all();
        $classes = Classes::all();
        return view('pages.inscriptions.listedesreductions', compact('eleves', 'reductions', 'classes'));
    }

    public function discipline(){
        return view('pages.inscriptions.discipline');
    } 

    public function archive(){
        return view('pages.inscriptions.archive');
    } 

        public function editions(){
        return view('pages.inscriptions.editions');
    } 

    public function eleveparclasse(){
        $classes = Classes::get();    
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

    
            // dd($filterEleves);
        Session::put('fraiscontrats', $fraiscontrat);
        Session::put('fill', $filterEleves);
    
        return view('pages.inscriptions.eleveparclasse1')
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

    public function ajouterprofreduction(Request $request) {
        $reduction = new Reduction();
        $reduction->Codereduction = $request->input('Codereduction');
        $reduction->LibelleReduction = $request->input('LibelleReduction');

        // Convertir les pourcentages en valeurs décimales
        $reduction->Reduction_scolarite = $this->convertToDecimal($request->input('Reduction_scolarite'));
        $reduction->Reduction_arriere = $this->convertToDecimal($request->input('Reduction_arriere'));
        $reduction->Reduction_frais1 = $this->convertToDecimal($request->input('Reduction_frais1'));
        $reduction->Reduction_frais2 = $this->convertToDecimal($request->input('Reduction_frais2'));
        $reduction->Reduction_frais3 = $this->convertToDecimal($request->input('Reduction_frais3'));
        $reduction->Reduction_frais4 = $this->convertToDecimal($request->input('Reduction_frais4'));

        $reduction->mode = $request->input('mode');
        $reduction->save();

        return back()->with('status', 'Profil de reduction creer avec succes.');
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
        // $reduction->codeReduction = $request->input('Codereduction');
        $reduction->LibelleReduction = $this->convertToDecimal($request->input('LibelleReduction'));
        $reduction->Reduction_scolarite = $this->convertToDecimal($request->input('Reduction_scolarite'));
        $reduction->Reduction_arriere =$this->convertToDecimal( $request->input('Reduction_arriere'));
        $reduction->Reduction_frais1 = $this->convertToDecimal($request->input('Reduction_frais1'));
        $reduction->Reduction_frais2 = $this->convertToDecimal($request->input('Reduction_frais2'));
        $reduction->Reduction_frais3 = $this->convertToDecimal($request->input('Reduction_frais3'));
        $reduction->Reduction_frais4 = $this->convertToDecimal($request->input('Reduction_frais4'));
        $reduction->mode = $request->input('mode');

        // Sauvegarder les modifications
        $reduction->save();

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

    public function recaculereffectifs(){
        return view ('pages.inscriptions.recaculereffectifs');
    }

    public function enquetesstatistiques(){
        return view ('pages.inscriptions.enquetesstatistiques');
    }

    public function etatdelacaisse(){
        return view ('pages.inscriptions.etatdelacaisse');
    }

    public function situationfinanciereglobale(){
        return view ('pages.inscriptions.situationfinanciereglobale');
    }

    public function pointderecouvrement(){
        return view ('pages.inscriptions.pointderecouvrement');
    }
    public function paiementdesnoninscrits(){
        return view ('pages.inscriptions.paiementdesnoninscrits');
    }
    
    public function etatdesrecouvrements(){
        return view ('pages.inscriptions.etatdesrecouvrements');
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
    
            $modifieleve->MATRICULE = $request->input('numOrdre');
            $modifieleve->CodeReduction = $request->input('reduction');
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
    
}
