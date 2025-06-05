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
    protected $ordreDesGroupes;
    protected $cycleKeys;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;

        // Définition de l'ordre des groupes
        $this->ordreDesGroupes = [
            '6è',
            '5è',
            '4è',
            '3è',
            '2ndA1',
            '2ndA2',
            '2ndB',
            '2ndC',
            '2ndD',
            '1èreA1',
            '1èreA2',
            '1èreB',
            '1èreC',
            '1èreD',
            'TleA1',
            'TleA2',
            'TleB',
            'TleC',
            'TleD',
        ];

        // Définition des clés pour chaque cycle
        $this->cycleKeys = [
            'CYCLE I' => ['6è', '5è', '4è', '3è'],
            'SECONDES' => ['2ndA1', '2ndA2', '2ndB', '2ndC', '2ndD'],
            'PREMIÈRE' => ['1èreA1', '1èreA2', '1èreB', '1èreC', '1èreD'],
            'TERMINALE' => ['TleA1', 'TleA2', 'TleB', 'TleC', 'TleD'],
        ];
    }

    protected function validateRequest(Request $request, string $typeEtat)
    {
        return $request->validate([
            'moyenne_ref'       => 'required|numeric|min:0|max:20',
            'periode'           => 'required|string',
            'typeEtat'          => 'required|in:' . $typeEtat,
            'intervales'        => 'required|array',
            'intervales.*.min'  => 'required|numeric|min:0|max:20',
            'intervales.*.max'  => 'required|numeric|min:0|max:20|gte:intervales.*.min',
        ]);
    }

    protected function aggregateCycleStats($resultats, $groupKeys, $isSynoptique = false)
    {
        $aggregated = [
            'intervales' => [],
        ];

        if (!$isSynoptique) {
            $aggregated['max_moyenne_garcons'] = null;
            $aggregated['min_moyenne_garcons'] = null;
            $aggregated['max_moyenne_filles'] = null;
            $aggregated['min_moyenne_filles'] = null;
        } else {
            $aggregated['nbClasses'] = 0;
        }

        $first = true;
        foreach ($groupKeys as $key) {
            if (!isset($resultats[$key])) {
                continue;
            }
            $stats = $resultats[$key];

            if ($isSynoptique) {
                $aggregated['nbClasses'] += $stats['nbClasses'];
            }

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

            if (!$isSynoptique) {
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
        }
        return $aggregated;
    }

    protected function processResults($resultats, $isSynoptique = false)
    {
        $finalResultats = [];

        // Ajout des résultats individuels
        foreach ($this->ordreDesGroupes as $groupe) {
            if (isset($resultats[$groupe])) {
                $finalResultats[$groupe] = $resultats[$groupe];
            }
        }

        // Agrégation par cycle
        foreach ($this->cycleKeys as $cycleName => $groupKeys) {
            if (array_intersect($groupKeys, array_keys($resultats))) {
                $finalResultats[$cycleName] = $this->aggregateCycleStats($resultats, $groupKeys, $isSynoptique);
            }
        }

        // Cycle II (Secondes + Première + Terminale)
        $cycleIIKeys = array_merge(
            $this->cycleKeys['SECONDES'],
            $this->cycleKeys['PREMIÈRE'],
            $this->cycleKeys['TERMINALE']
        );
        if (array_intersect($cycleIIKeys, array_keys($resultats))) {
            $finalResultats['CYCLE II'] = $this->aggregateCycleStats($resultats, $cycleIIKeys, $isSynoptique);
        }

        // Établissement (Cycle I + Cycle II)
        $etablissementKeys = array_merge(
            $this->cycleKeys['CYCLE I'],
            $cycleIIKeys
        );
        if (array_intersect($etablissementKeys, array_keys($resultats))) {
            $finalResultats['ETABLISSEMENT'] = $this->aggregateCycleStats($resultats, $etablissementKeys, $isSynoptique);
        }

        return $finalResultats;
    }

    public function tableauanalytique(Request $request)
    {
        if (!$request->isMethod('post')) {
            $params2 = Params2::all();
            return view('pages.notes.tableauanalytique', ['params2' => $params2]);
        }

        $data = $this->validateRequest($request, 'tableau_analytique,tableau_synoptique,effectifs,statistique');
        $hashIntervalles = md5(json_encode($data['intervales']));
        $cacheKey = "stats_{$data['periode']}_{$data['typeEtat']}_{$hashIntervalles}";


        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            if ($data['typeEtat'] === 'tableau_synoptique') {
                return $this->statistiquesService->calculerSynoptique($data);
            }elseif($data['typeEtat'] === 'statistique'){
                return $this->statistiquesService->calculerStatistiquesDetaillees($data);
            }else{
                return $this->statistiquesService->calculerStatistiques($data);
            }
        });
        

        $finalResultats = $this->processResults($resultats, $data['typeEtat'] === 'tableau_synoptique');

        // Récupérer les paramètres à afficher
        $params2 = Params2::all();
        $typeAn = $params2->first()->TYPEAN;         // ou la logique qui convient
        $nom    = ($typeAn == 1) ? 'SEMESTRE' : 'TRIMESTRE';
        // Récupérer l'objet de configuration (par exemple le premier enregistrement)
        $contrat = ParamContrat::first();
        // Supposons que la colonne s'appelle 'anneencours_paramcontrat' et contient un entier, ex. 2024
        $anneeCourante = (int) $contrat->anneencours_paramcontrat;
        // Calcul de l'année suivante
        $anneeSuivante = $anneeCourante + 1;
        // Libellé « année scolaire » : 2024/2025
        $anneeScolaire = "{$anneeCourante}-{$anneeSuivante}";

        return view('pages.notes.tableauanalytique', [
            'resultats' => $finalResultats,
            'typeEtat'  => $data['typeEtat'],
            'intervales'  => $data['intervales'],
            'periode'     => $data['periode'],
            'moyenne_ref' => $data['moyenne_ref'],
            'params2'     => $params2,    // ← on ajoute ça
            'nom'    => $nom,
            'anneeScolaire' => $anneeScolaire,
        ]);
    }

    public function tableausynoptique(Request $request)
    {
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        $data = $this->validateRequest($request, 'tableau_synoptique');
        $cacheKey = "stats_{$data['periode']}_tableau_synoptique";

        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerSynoptique($data);
        });

        $finalResultats = $this->processResults($resultats, true);

        // Récupérer les paramètres à afficher
        $params2 = Params2::all();
        $typeAn = $params2->first()->TYPEAN;         // ou la logique qui convient
        $nom    = ($typeAn == 1) ? 'SEMESTRE' : 'TRIMESTRE';
        // Récupérer l'objet de configuration (par exemple le premier enregistrement)
        $contrat = ParamContrat::first();
        // Supposons que la colonne s'appelle 'anneencours_paramcontrat' et contient un entier, ex. 2024
        $anneeCourante = (int) $contrat->anneencours_paramcontrat;
        // Calcul de l'année suivante
        $anneeSuivante = $anneeCourante + 1;
        // Libellé « année scolaire » : 2024/2025
        $anneeScolaire = "{$anneeCourante}-{$anneeSuivante}";

        return view('pages.notes.tableauanalytique', [
            'resultats' => $finalResultats,
            'typeEtat'  => 'tableau_synoptique',
            'intervales'  => $data['intervales'],
            'periode'     => $data['periode'],
            'moyenne_ref' => $data['moyenne_ref'],
            'params2'     => $params2,    // ← on ajoute ça
            'nom'    => $nom,
            'params2' => $params2,
            'anneeScolaire' => $anneeScolaire,
        ]);
    }

    public function effectifs(Request $request)
    {
        if (!$request->isMethod('post')) {
            return view('pages.notes.tableauanalytique');
        }

        $data = $this->validateRequest($request, 'effectifs');
        $cacheKey = "stats_{$data['periode']}_effectifs";

        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerStatistiques($data);
        });

        $finalResultats = $this->processResults($resultats);

        // Récupérer les paramètres à afficher
        $params2 = Params2::all();
        $typeAn = $params2->first()->TYPEAN;         // ou la logique qui convient
        $nom    = ($typeAn == 1) ? 'SEMESTRE' : 'TRIMESTRE';
        // Récupérer l'objet de configuration (par exemple le premier enregistrement)
        $contrat = ParamContrat::first();
        // Supposons que la colonne s'appelle 'anneencours_paramcontrat' et contient un entier, ex. 2024
        $anneeCourante = (int) $contrat->anneencours_paramcontrat;
        // Calcul de l'année suivante
        $anneeSuivante = $anneeCourante + 1;
        // Libellé « année scolaire » : 2024/2025
        $anneeScolaire = "{$anneeCourante}-{$anneeSuivante}";

        return view('pages.notes.tableauanalytique', [
            'resultats' => $finalResultats,
            'typeEtat'  => 'effectifs',
            'intervales'  => $data['intervales'],
            'periode'     => $data['periode'],
            'moyenne_ref' => $data['moyenne_ref'],
            'params2'     => $params2,    // ← on ajoute ça
            'nom'    => $nom,
            'params2' => $params2,
            'anneeScolaire' => $anneeScolaire,
        ]);
    }

    public function statistiques(Request $request)
    {
        if (!$request->isMethod('post')) {
            $params2 = Params2::all();
            return view('pages.notes.tableauanalytique', ['params2' => $params2]);
        }

        $data = $this->validateRequest($request, 'statistique');
        $cacheKey = "stats_{$data['periode']}_statistique";

        $resultats = Cache::remember($cacheKey, 300, function () use ($data) {
            return $this->statistiquesService->calculerStatistiquesDetaillees($data);
        });

        // Récupérer les paramètres à afficher
        $params2 = Params2::all();
        $typeAn = $params2->first()->TYPEAN;
        $nom = ($typeAn == 1) ? 'SEMESTRE' : 'TRIMESTRE';
        $contrat = ParamContrat::first();
        $anneeCourante = (int) $contrat->anneencours_paramcontrat;
        $anneeSuivante = $anneeCourante + 1;
        $anneeScolaire = "{$anneeCourante}-{$anneeSuivante}";

        return view('pages.notes.tableauanalytique', [
            'resultats' => $resultats,
            'typeEtat' => 'statistique',
            'intervales' => $data['intervales'],
            'periode' => $data['periode'],
            'moyenne_ref' => $data['moyenne_ref'],
            'params2' => $params2,
            'nom' => $nom,
            'anneeScolaire' => $anneeScolaire,
        ]);
    }
}