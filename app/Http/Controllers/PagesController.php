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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class PagesController extends Controller
{
 
    public function inscriptioncantine(){
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

    public function vitrine(){
        $totaleleve = Eleve::count();
        $totalcantineinscritactif = Contrat::where('statut_contrat', 1)->count();
        $totalcantineinscritinactif = Contrat::where('statut_contrat', 0)->count();

        // dd($totalcantineinscritactif);
        return view('pages.vitrine')->with('totalcantineinscritactif', $totalcantineinscritactif)->with('totalcantineinscritinactif', $totalcantineinscritinactif)->with('totaleleve', $totaleleve);

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
        $param = Paramcontrat::first();
        return view('pages.parametre.parametre', ['param' => $param]);
    } 
}
