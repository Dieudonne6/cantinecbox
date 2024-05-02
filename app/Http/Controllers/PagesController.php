<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Moiscontrat;
use App\Models\Paramcontrat;
use App\Models\Contrat;
use App\Models\Usercontrat;
use Illuminate\Support\Facades\Session;
class PagesController extends Controller
{
    public function inscription(){
        return view('pages.inscription');
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
        $login = Usercontrat::get();
        return view('pages.connexion', ['login' => $login]);
    }

    public function logins(Request $request){
        $account = Usercontrat::where("login_usercontrat",$request->login_usercontrat)->first();
        if($account){
            if($account->password_usercontrat == $request->password_usercontrat){
                Session::put('account', $account);
                return redirect("classes");
            } else{
                return back()->with('status', 'Mot de passe ou email incorrecte');

            }
        } else {
            return back()->with('status', 'Mot de passe ou email incorrecte');

        }

    }

    public function vitrine(){
        return view('pages.vitrine');

    }

}
