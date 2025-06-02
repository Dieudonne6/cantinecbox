<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatistiquesService;
use Illuminate\Support\Facades\Cache;
use App\Models\Params2;
use App\Models\ParamContrat;


class TableauController extends Controller
{
    protected $statistiquesService;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }

    public function tableauanalytique(Request $request)
    {
        // Affichage de la vue si le formulaire n'est pas soumis
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        // Validation des entrées du formulaire
        $data = $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            'typeEtat'          => 'required|in:tableau_analytique,tableau_synoptique,effectifs',
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales.*.max'  => 'required|numeric|min:0|max:20',
        ]);

        // dd($data['intervales']);

         // 2. Mettez la sélection en session
        session(['typeEtat' => $request->input('typeEtat')]);
        session(['periode' => $request->input('periode')]);
        // juste après validation :
        $intervalesHash = md5(json_encode($data['intervales']));

        $cacheKey = "stats_{$data['periode']}{$data['typeEtat']}{$intervalesHash}";

        // Pour le tableau analytique et les effectifs, on utilise la même méthode de calcul
        if ($data['typeEtat'] === 'tableau_analytique' || $data['typeEtat'] === 'effectifs') {
            $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
                return $this->statistiquesService->calculerStatistiques($data);
            });
        } elseif ($data['typeEtat'] === 'tableau_synoptique') {
            $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
                return $this->statistiquesService->calculerSynoptique($data);
            });
        } else {
            return response()->json(['error' => 'Type d\'état invalide.'], 400);
        }

        // On ordonne les groupes selon l'ordre souhaité
        $ordreDesGroupes = [
            '6è', '5è', '4è', '3è',
            '2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD',
            '1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD',
            'TleA1', 'TleA2', 'TleB', 'TleC', 'TleD',
        ];

        $resultatsOrdonnes = [];
        foreach ($ordreDesGroupes as $groupeVoulu) {
            if (isset($resultats[$groupeVoulu])) {
                $resultatsOrdonnes[$groupeVoulu] = $resultats[$groupeVoulu];
            }
        }

        // Définition des clés pour chaque cycle
        $cycle1Keys    = ['6è', '5è', '4è', '3è'];
        $secondesKeys  = ['2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD'];
        $premieresKeys = ['1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD'];
        $terminalesKeys = ['TleA1', 'TleA2', 'TleB', 'TleC', 'TleD'];

        // Fonction d'agrégation pour un ensemble de groupes
        $aggregateCycleStats = function ($resultats, $groupKeys) {
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
                // Pour les moyennes, on prend le maximum et le minimum global
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

        // Construction du tableau final en intégrant des agrégations pour différents cycles
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

        // Agrégation globale pour l'ÉTABLISSEMENT (Cycle 1 et Cycle II)
        $etablissementKeys = array_merge($cycle1Keys, $cycleIIKeys);
        if (array_intersect($etablissementKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['ETABLISSEMENT'] = $aggregateCycleStats($resultatsOrdonnes, $etablissementKeys);
        }

        // return view('pages.notes.tableauanalytique', [
        //     'resultats' => $finalResultats,
        //     'typeEtat'  => $data['typeEtat']
        // ]);

    // Récupérer les paramètres à afficher
    $params2 = Params2::all();
    $typeAn = $params2->first()->TYPEAN;         // ou la logique qui convient
    $nom    = ($typeAn == 1) ? 'SEMESTRE' : 'TRIMESTRE';
    // Récupérer l’objet de configuration (par exemple le premier enregistrement)
    $contrat = ParamContrat::first();
    // Supposons que la colonne s’appelle 'anneencours_paramcontrat' et contient un entier, ex. 2024
    $anneeCourante = (int) $contrat->anneencours_paramcontrat;
    // Calcul de l’année suivante
    $anneeSuivante = $anneeCourante + 1;
    // Libellé « année scolaire » : 2024/2025
    $anneeScolaire = "{$anneeCourante}-{$anneeSuivante}";

    return view('pages.notes.tableauanalytique', [
        'resultats'   => $finalResultats,
        'typeEtat'    => $data['typeEtat'],
        'intervales'  => $data['intervales'],
        'periode'     => $data['periode'],
        'moyenne_ref' => $data['moyenne_ref'],
        'params2'     => $params2,    // ← on ajoute ça
        'nom'    => $nom,
        'params2' => $params2,
        'anneeScolaire' => $anneeScolaire,
    ]);
    }

    public function tableausynoptique(Request $request)
    {
        // Affichage de la vue si le formulaire n'est pas soumis
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        // Validation des entrées (pour le tableau synoptique, seul ce type est autorisé)
        $data = $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            'typeEtat'          => 'required|in:tableau_synoptique',
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales..max'  => 'required|numeric|min:0|max:20|gt:intervales..min',
        ]);

        $cacheKey = "stats_{$data['periode']}_tableau_synoptique";
        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerSynoptique($data);
        });
        // dd($resultats);

        return view('pages.notes.tableauanalytique', [
            'resultats' => $resultats,
            'typeEtat'  => 'tableau_synoptique'
        ]);
    }

    public function effectifs(Request $request)
    {
        // Affichage de la vue si le formulaire n'est pas soumis
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        // Validation spécifique pour le type "effectifs"
        $data = $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            // On force ici le type à "effectifs"
            'typeEtat'          => 'required|in:effectifs',
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales..max'  => 'required|numeric|min:0|max:20|gt:intervales..min',
        ]);

        $cacheKey = "stats_{$data['periode']}_effectifs";
        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerStatistiques($data);
        });

        // On ordonne les groupes comme dans tableauanalytique
        $ordreDesGroupes = [
            '6è', '5è', '4è', '3è',
            '2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD',
            '1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD',
            'TleA1', 'TleA2', 'TleB', 'TleC', 'TleD',
        ];

        $resultatsOrdonnes = [];
        foreach ($ordreDesGroupes as $groupeVoulu) {
            if (isset($resultats[$groupeVoulu])) {
                $resultatsOrdonnes[$groupeVoulu] = $resultats[$groupeVoulu];
            }
        }

        // Définition des clés pour chaque cycle
        $cycle1Keys    = ['6è', '5è', '4è', '3è'];
        $secondesKeys  = ['2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD'];
        $premieresKeys = ['1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD'];
        $terminalesKeys = ['TleA1', 'TleA2', 'TleB', 'TleC', 'TleD'];

        $aggregateCycleStats = function ($resultats, $groupKeys) {
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

        $finalResultats = [];

        // Cycle 1
        foreach ($cycle1Keys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($cycle1Keys, array_keys($resultatsOrdonnes))) {
            $finalResultats['CYCLE I'] = $aggregateCycleStats($resultatsOrdonnes, $cycle1Keys);
        }

        // Secondes
        foreach ($secondesKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($secondesKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['SECONDES'] = $aggregateCycleStats($resultatsOrdonnes, $secondesKeys);
        }

        // Première
        foreach ($premieresKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($premieresKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['PREMIÈRE'] = $aggregateCycleStats($resultatsOrdonnes, $premieresKeys);
        }

        // Terminale
        foreach ($terminalesKeys as $key) {
            if (isset($resultatsOrdonnes[$key])) {
                $finalResultats[$key] = $resultatsOrdonnes[$key];
            }
        }
        if (array_intersect($terminalesKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['TERMINALE'] = $aggregateCycleStats($resultatsOrdonnes, $terminalesKeys);
        }

        // Cycle II
        $cycleIIKeys = array_merge($secondesKeys, $premieresKeys, $terminalesKeys);
        if (array_intersect($cycleIIKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['CYCLE II'] = $aggregateCycleStats($resultatsOrdonnes, $cycleIIKeys);
        }

        // Établissement
        $etablissementKeys = array_merge($cycle1Keys, $cycleIIKeys);
        if (array_intersect($etablissementKeys, array_keys($resultatsOrdonnes))) {
            $finalResultats['ETABLISSEMENT'] = $aggregateCycleStats($resultatsOrdonnes, $etablissementKeys);
        }

        return view('pages.notes.tableauanalytique', [
            'resultats' => $finalResultats,
            'typeEtat'  => 'effectifs'
        ]);
    }
}