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
                Session::put('id_usercontrat', $id_usercontrat);
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
        $fileNameWhiteExt = $request->file('logo')->getClientOriginalName();
        $fileName = pathinfo($fileNameWhiteExt, PATHINFO_FILENAME);
        $ext = $request->file('logo')->getClientOriginalExtension();
        $fileNameToStore = $fileName."_".time().".".$ext;


        $path = $request->file('logo')->storeAs("public/logo", $fileNameToStore);
        $emcef->logo = $fileNameToStore;

        $emcef->save();
        return back()->with('status','Enregistrer avec succes');
    }
    public function inscriptions(){
        return view('pages.etat.inscriptions');
    }
    public function enregistreruser(Request $request){
        $login = new User();
        $password_crypte = Hash::make($request->password);
        $login->login = $request->input('login');
        $login->nomuser = $request->input('nom');
        $login->prenomuser = $request->input('prenom');
        $login->motdepasse = $password_crypte;
        $login->nomgroupe = '';
        $login->administrateur = 1;
        $login->user_actif = 1;
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

    public function enrclasse(){
        if(Session::has('account')){
        // $duplicatafactures = Duplicatafacture::all();

        return view('pages.inscriptions.enregistrementclasse');
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
        return view('pages.inscriptions.inscrireeleve');
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
        return view('pages.inscriptions.reductioncollective');
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
        return view('pages.inscriptions.eleveparclasse');
    } 

    public function listeselective(){
        return view('pages.inscriptions.listeselective');
    } 

    public function modifiereleve(){
        return view ('pages.inscriptions.modifiereleve');
    }

    public function typesclasses(){
        return view ('pages.inscriptions.typesclasses');
    }

    public function series(){
        return view ('pages.inscriptions.series');
    }

    public function promotions(){
        return view ('pages.inscriptions.promotions');
    }

    public function creerprofil(){
        return view ('pages.inscriptions.creerprofil');
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

}
