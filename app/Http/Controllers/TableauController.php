<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatistiquesService;
use Illuminate\Support\Facades\Cache;

class TableauController extends Controller
{
    protected $statistiquesService;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }

    public function tableauanalytique(Request $request)
    {
        // Si le formulaire n'est pas encore soumis, on affiche la vue vide
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }
    
        // Validation stricte des entrées du formulaire
        $data = $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            'typeEtat'          => 'required|in:tableau_analytique',
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales.*.max'  => 'required|numeric|min:0|max:20|gt:intervales.*.min',
        ]);
    
        // Vérification du type d'état (ici tableau analytique)
        if ($data['typeEtat'] !== 'tableau_analytique') {
            return response()->json(['error' => 'Type d\'état invalide.'], 400);
        }
    
        // Mise en cache des statistiques pour éviter les recalculs inutiles
        $cacheKey = "stats_{$data['periode']}";
        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerStatistiques($data);
        });
    
        // Définition de l'ordre souhaité des groupes
        $ordreDesGroupes = [
            '6è', '5è', '4è', '3è',
            '2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD',
            '1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD',
            'TleA1', 'TleA2', 'TleB', 'TleC', 'TleD',
        ];
    
        // Création d'un tableau ordonné ne contenant que les groupes existants
        $resultatsOrdonnes = [];
        foreach ($ordreDesGroupes as $groupeVoulu) {
            if (isset($resultats[$groupeVoulu])) {
                $resultatsOrdonnes[$groupeVoulu] = $resultats[$groupeVoulu];
            }
        }
    
        // Définition des clés correspondant à chaque cycle
        $cycle1Keys    = ['6è', '5è', '4è', '3è'];
        $secondesKeys  = ['2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD'];
        $premieresKeys = ['1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD'];
        $terminalesKeys = ['TleA1', 'TleA2', 'TleB', 'TleC', 'TleD'];
    
        // Fonction d'agrégation des statistiques pour un ensemble de groupes
        $aggregateCycleStats = function($resultats, $groupKeys) {
            $aggregated = [
                'max_moyenne_garcons' => null,
                'min_moyenne_garcons' => null,
                'max_moyenne_filles'  => null,
                'min_moyenne_filles'  => null,
                'intervales'          => [],
            ];
            $first = true;
            foreach ($groupKeys as $key) {
                if (!isset($resultats[$key])) {
                    continue;
                }
                $stats = $resultats[$key];
                // Agrégation des intervalles
                foreach ($stats['intervales'] as $interval => $data) {
                    if (!isset($aggregated['intervales'][$interval])) {
                        $aggregated['intervales'][$interval] = [
                            'garcons' => 0,
                            'filles'  => 0,
                            'total'   => 0,
                        ];
                    }
                    $aggregated['intervales'][$interval]['garcons'] += $data['garcons'];
                    $aggregated['intervales'][$interval]['filles']  += $data['filles'];
                    $aggregated['intervales'][$interval]['total']   += $data['total'];
                }
                // Pour les moyennes, on prend le max global et le min global
                if ($first) {
                    $aggregated['max_moyenne_garcons'] = $stats['max_moyenne_garcons'];
                    $aggregated['min_moyenne_garcons'] = $stats['min_moyenne_garcons'];
                    $aggregated['max_moyenne_filles']  = $stats['max_moyenne_filles'];
                    $aggregated['min_moyenne_filles']  = $stats['min_moyenne_filles'];
                    $first = false;
                } else {
                    $aggregated['max_moyenne_garcons'] = max($aggregated['max_moyenne_garcons'], $stats['max_moyenne_garcons']);
                    $aggregated['min_moyenne_garcons'] = min($aggregated['min_moyenne_garcons'], $stats['min_moyenne_garcons']);
                    $aggregated['max_moyenne_filles']  = max($aggregated['max_moyenne_filles'], $stats['max_moyenne_filles']);
                    $aggregated['min_moyenne_filles']  = min($aggregated['min_moyenne_filles'], $stats['min_moyenne_filles']);
                }
            }
            return $aggregated;
        };
    
        // Construction du tableau final en intégrant les lignes d'agrégation
        $finalResultats = [];
    
        // Cycle 1 (6è, 5è, 4è, 3è)
        foreach ($cycle1Keys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($cycle1Keys, array_keys($resultatsOrdonnes))) {
            $finalResultats['CYCLE I'] = $aggregateCycleStats($resultatsOrdonnes, $cycle1Keys);
        }
    
        // Secondes (2ndA1, 2ndA2, 2ndB, 2ndC, 2ndD)
        foreach ($secondesKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($secondesKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['SECONDES'] = $aggregateCycleStats($resultatsOrdonnes, $secondesKeys);
        }
    
        // Première (1èreA1, 1èreA2, 1èreB, 1èreC, 1èreD)
        foreach ($premieresKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($premieresKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['PREMIÈRE'] = $aggregateCycleStats($resultatsOrdonnes, $premieresKeys);
        }
    
        // Terminale (TleA1, TleA2, TleB, TleC, TleD)
        foreach ($terminalesKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($terminalesKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['TERMINALE'] = $aggregateCycleStats($resultatsOrdonnes, $terminalesKeys);
        }
    
        // Agrégation globale pour le CYCLE II (Secondes, Première, Terminale)
        $cycleIIKeys = array_merge($secondesKeys, $premieresKeys, $terminalesKeys);
        if (array_intersect($cycleIIKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['CYCLE II'] = $aggregateCycleStats($resultatsOrdonnes, $cycleIIKeys);
        }
    
        // Agrégation globale pour l'ETABLISSEMENT (Cycle 1 et Cycle II)
        $etablissementKeys = array_merge($cycle1Keys, $cycleIIKeys);
        if (array_intersect($etablissementKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['ETABLISSEMENT'] = $aggregateCycleStats($resultatsOrdonnes, $etablissementKeys);
        }
    
        return view('pages.notes.tableauanalytique', [
            'resultats' => $finalResultats
        ]);
    }
    
    
    
}
