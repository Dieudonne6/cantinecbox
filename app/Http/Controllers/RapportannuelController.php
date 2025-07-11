<?php

namespace App\Http\Controllers;

use App\Models\classes;
use App\Models\DecisionConfigAnnuel; 
use App\Models\Promo;
use App\Models\ConfigClasseSup;
use App\Models\Classe;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RapportannuelController extends Controller
{ 
   

    public function rapportAnnuel()
{
    // Pour la config des classes supérieures
    $promotions = Promo::all();
    $classes = Classe::all();
    $configs = ConfigClasseSup::all()->keyBy('codeClas');

    // Pour la config des décisions
    $promo = Classes::select('CODEPROMO', 'CYCLE')->get();
    $config = \App\Models\DecisionConfigAnnuel::latest()->first();

    return view('pages.notes.rapportannuel', [
        'promotions' => $promotions,
        'classes'    => $classes,
        'configs'    => $configs,
        'promo'      => $promo,
        'config'     => $config,
    ]);
}


    

    public function storeDecisionConfig(Request $request)
    {
        // Supprimer les anciennes décisions
        DecisionConfigAnnuel::truncate(); // efface toute la table (à utiliser si elle ne contient que la config)

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

    


  public function updateConfigClasses(Request $request)
    {
        // Valider que tous les tableaux existent et ont la même taille
        $request->validate([
            'codeClas'            => 'required|array',
            'libelle_promotion'   => 'required|array',
            'libelle_classe_sup'  => 'required|array',
            'libelle_classe_sup.*'=> 'nullable|string|max:255',
        ]);

        $codes      = $request->input('codeClas');
        $labelsProm = $request->input('libelle_promotion');
        $labelsSup  = $request->input('libelle_classe_sup');

        foreach ($codes as $i => $code) {
            // On peut faire un updateOrCreate pour éviter les doublons sur codeClas
            ConfigClasseSup::updateOrCreate(
                ['codeClas' => $code],
                [
                  'libelle_promotion'   => $labelsProm[$i]  ?? null,
                  'libelle_classe_sup'  => $labelsSup[$i]   ?? null,
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Configuration des classes supérieures enregistrée avec succès.');
    }

}
