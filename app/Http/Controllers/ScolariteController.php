<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ClasseController;

use App\Models\Compte;
use App\Models\Classe;
use App\Models\Eleve;

class ScolariteController extends Controller
{
    //

    public function getparamcomposantes(){
        $comptes = Compte::get();

        return view ('pages.inscriptions.paramcomposantes')->with('comptes', $comptes);
    }

    public function getfacturesclasses(){

        $factures = DB::table('classes')
            ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            //  ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            ->select(
                'classes.*',
                'typeenseigne.type as typeenseigne_type',
                )
            ->get();

        return view ('pages.inscriptions.facturesclasses', compact('factures'));
    }

    public function detailfacturesclasses($CODECLAS) {

        $donneClasse = Classe::where('CODECLAS', $CODECLAS)->first();
        // dd($donneClasse);
        return view('pages.inscriptions.factureclassesdetail', compact('CODECLAS', 'donneClasse'));
    }

    public function detailfacclasse (Request $request, $CODECLAS ) {

        // ENREGISTREMENT OU MODIFICATION DES VALEURS DANS LA TABLE CLASSE
        $classeCorrespondante = Classe::where('CODECLAS', $CODECLAS)->first();

        $classeCorrespondante->APAYER = $request->input('APAYER');
        $classeCorrespondante->APAYER2 = $request->input('APAYER2');

        $classeCorrespondante->FRAIS1 = $request->input('FRAIS1');
        $classeCorrespondante->FRAIS1_A = $request->input('FRAIS1_A');

        $classeCorrespondante->FRAIS2 = $request->input('FRAIS2');
        $classeCorrespondante->FRAIS2_A = $request->input('FRAIS2_A');

        $classeCorrespondante->FRAIS3 = $request->input('FRAIS3');
        $classeCorrespondante->FRAIS3_A = $request->input('FRAIS3_A');

        $classeCorrespondante->FRAIS4 = $request->input('FRAIS4');
        $classeCorrespondante->FRAIS4_A = $request->input('FRAIS4_A');

        $classeCorrespondante->save();

        // ENREGISTREMENT OU MODIFICATION DES VALEURS DANS LA TABLE ELEVE

        $listeEleveDeLaClasses = Eleve::where('CODECLAS', $CODECLAS)->get();
        // dd($listeEleveDeLaClasses);

        foreach($listeEleveDeLaClasses as $chaqueEleve){
            // dd($chaqueEleve->STATUTG);
            if($chaqueEleve->STATUTG == 2) {
                $chaqueEleve->APAYER = $request->input('APAYER2');
                $chaqueEleve->FRAIS1 = $request->input('FRAIS1_A');
                $chaqueEleve->FRAIS2 = $request->input('FRAIS2_A');
                $chaqueEleve->FRAIS3 = $request->input('FRAIS3_A');
                $chaqueEleve->FRAIS4 = $request->input('FRAIS4_A');
                $chaqueEleve->save();
            } elseif (($chaqueEleve->STATUTG == 1)) {
                $chaqueEleve->APAYER = $request->input('APAYER');
                $chaqueEleve->FRAIS1 = $request->input('FRAIS1');
                $chaqueEleve->FRAIS2 = $request->input('FRAIS2');
                $chaqueEleve->FRAIS3 = $request->input('FRAIS3');
                $chaqueEleve->FRAIS4 = $request->input('FRAIS4');
                $chaqueEleve->save();
            }
        }
        //if($listeEleveDeLaClasses->STATUTG = 2) // CAS OU L'ELEVE EST UN ANCIEN
        
        // {

        //     foreach($listeEleveDeLaClasses as $eleveAncien) {
        //         $eleveAncien->APAYER = $request->input('APAYER2');
        //         $eleveAncien->FRAIS1 = $request->input('FRAIS1_A');
        //         $eleveAncien->FRAIS2 = $request->input('FRAIS2_A');
        //         $eleveAncien->FRAIS3 = $request->input('FRAIS3_A');
        //         $eleveAncien->FRAIS4 = $request->input('FRAIS4_A');
        //         $eleveAncien->save();
        //     }


        // } else {  // CAS OU L'ELEVE EST UN NOUVEAU OU PEUT ETRE TRANSFERER
        
        //     foreach($listeEleveDeLaClasses as $eleveNouveau) {
        //         $eleveNouveau->APAYER = $request->input('APAYER');
        //         $eleveNouveau->FRAIS1 = $request->input('FRAIS1');
        //         $eleveNouveau->FRAIS2 = $request->input('FRAIS2');
        //         $eleveNouveau->FRAIS3 = $request->input('FRAIS3');
        //         $eleveNouveau->FRAIS4 = $request->input('FRAIS4');
        //         $eleveNouveau->save();
        //     }

        // }

    return back()->with('status', 'enregistrement effectuer avec succes');

}

}
