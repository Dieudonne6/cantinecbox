<?php

namespace App\Http\Controllers;

use App\Models\classes;
use App\Models\DecisionConfigAnnuel; 
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RapportannuelController extends Controller
{ 
   

    public function Rapportannuel()
    {
        $promo = Classes::select('CODEPROMO', 'CYCLE')->get();

        // Récupère la dernière configuration s'il y en a, sinon retourne null
        $config = \App\Models\DecisionConfigAnnuel::orderBy('id', 'desc')->first();

        return view('pages.notes.rapportannuel', [
            'promo' => $promo,
            'config' => $config 
        ]);
    }

    

    public function storeDecisionConfig(Request $request)
    {
        // Supprimer les anciennes décisions s'il y en a
        DecisionConfigAnnuel::truncate(); 

        $seuilPassage = $request->input('seuil_Passage', 0);
        $minCycle1 = $request->input('min_Cycle1', 0);
        $minCycle2 = $request->input('min_Cycle2', 0);

        $seuilFelicitations = $request->input('Seuil_Félicitations', 0);
        $seuilEncouragements = $request->input('Seuil_Encouragements', 0);
        $seuilTableauHonneur = $request->input('Seuil_tableau_Honneur', 0);

        $rowCount = $request->input('row_count');

        for ($i = 0; $i < $rowCount; $i++) {
            $promotion = $request->input("promotion_$i");
            $statut = $request->input("statut_$i");
            $statuF = $request->input("decision_$i");

            if ($promotion && $statuF) {
                DecisionConfigAnnuel::create([
                    'seuil_Passage' => $seuilPassage,
                    'Min_Cycle1' => $minCycle1,
                    'Min_Cycle2' => $minCycle2,
                    'seuil_Felicitations' => $seuilFelicitations,
                    'seuil_Encouragements' => $seuilEncouragements,
                    'seuil_tableau_Honneur' => $seuilTableauHonneur,
                    'Promotion' => $promotion,
                    'Statut' => $statut,
                    'StatutF' => $statuF,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Décisions configurées avec succès.');
    }




}
