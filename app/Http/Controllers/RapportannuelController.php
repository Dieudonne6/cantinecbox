<?php

namespace App\Http\Controllers;

use App\Models\classes;
use App\Models\DecisionConfigAnnuel; 
use App\Models\Promo;
use App\Models\ConfigClasseSup;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Rapport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RapportannuelController extends Controller
{ 
   
    public function rapportAnnuel(Request $request)
    {
        // Pour la config des classes supérieures
        $promotions = Promo::all();
        $classes = Classe::all();
        $configs = ConfigClasseSup::all()->keyBy('codeClas');

        // Pour la config des décisions
        $promo = Classes::select('CODEPROMO', 'CYCLE')->get();
        $config = \App\Models\DecisionConfigAnnuel::latest()->first();
        $codesClassesAvecRapport = Rapport::select('CODECLAS')
        ->distinct()
        ->pluck('CODECLAS');

        $classesAvecRapport = Classe::whereIn('CODECLAS', $codesClassesAvecRapport)
        ->orderBy('CODECLAS')
        ->get();

        $rapports = Rapport::all();
                                // CALCUL DES STATISTIQUES
        $effectifTotal     = $rapports->count();
        $effectifFilles    = $rapports->where('SEXE', 2)->count();

        // 'P' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $passantsTotal     = $rapports->where('STATUTF', 'P')->count();
        $passantesFilles   = $rapports->where('STATUTF', 'P')
                                    ->where('SEXE', 2)
                                    ->count();


                                    // 'R' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $redoublesTotal     = $rapports->where('STATUTF', 'R')->count();
        $redoublesFilles   = $rapports->where('STATUTF', 'R')
                                    ->where('SEXE', 2)
                                    ->count();

                                    // 'X' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $exlusesTotal     = $rapports->where('STATUTF', 'X')->count();
        $exlusFilles   = $rapports->where('STATUTF', 'X')
                                    ->where('SEXE', 2)
                                    ->count();

                                    //Abandon
        $abandonsTotal     = $rapports->where('RANG', '=', 0)->count();
        $abandonsFilles   = $rapports->where('RANG', '=', 0)
                                    ->where('SEXE', 2)
                                    ->count();

        return view('pages.notes.rapportannuel', [
            'promotions' => $promotions,
            'classes'    => $classes,
            'configs'    => $configs,
            'promo'      => $promo,
            'config'     => $config,
            'rapports'   => [],
            'classesAvecRapport'=> $classesAvecRapport,
            'effectifTotal'      => $effectifTotal,
            'effectifFilles'     => $effectifFilles,
            'passantsTotal'      => $passantsTotal,
            'passantesFilles'    => $passantesFilles,
            'redoublesFilles' => $redoublesFilles,
            'redoublesTotal' => $redoublesTotal,
            'exlusesTotal'      => $exlusesTotal,
            'exlusFilles'    => $exlusFilles,
            'abandonsTotal'      => $abandonsTotal,
            'abandonsFilles'    => $abandonsFilles,
        ]);
    }



   public function storeDecisionConfig(Request $request)
    {
        DecisionConfigAnnuel::truncate();

        $seuilPassage       = $request->input('seuil_Passage', 0);
        $minCycle1          = $request->input('min_Cycle1', 0);
        $minCycle2          = $request->input('min_Cycle2', 0);
        $seuilFelicitations = $request->input('Seuil_Félicitations', 0);
        $seuilEncouragements= $request->input('Seuil_Encouragements', 0);
        $seuilTableauHonneur= $request->input('Seuil_tableau_Honneur', 0);
        $rowCount           = $request->input('row_count');
        //dd( $seuilEncouragements);
        for ($i = 0; $i < $rowCount; $i++) {
            $promotion = $request->input("promotion_$i");
            $statut    = $request->input("statut_$i");
            $statuF    = $request->input("decision_$i");
            $cycle     = $request->input("cycle_$i");  

            if ($promotion && $statuF !== null) {
             $result =   DecisionConfigAnnuel::create([
                    'seuil_Passage'        => $seuilPassage,
                    'Min_Cycle1'           => $minCycle1,
                    'Min_Cycle2'           => $minCycle2,
                    'Seuil_Felicitations'  => $seuilFelicitations,
                    'Seuil_Encouragements' => $seuilEncouragements,
                    'Seuil_tableau_Honneur'=> $seuilTableauHonneur,
                    'Promotion'            => $promotion,
                    'Statut'               => $statut,
                    'StatutF'              => $statuF,
                    'Cycle'                => $cycle,        
                ]);
            }
           // dd( $result);
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

    

    public function creerRapport(Request $request)
    {

        $promotions = Promo::all();
        $classes    = Classe::all();
        $configs    = ConfigClasseSup::all()->keyBy('codeClas');
        $promoList  = Classe::select('CODEPROMO','CYCLE')->get();
        $configAnn  = DecisionConfigAnnuel::latest()->first();


        
        $codeClasse = $request->input('classe_code');
        // dd($codeClasse);

         // 0. Supprimer l’ancien rapport pour cette classe
         Rapport::where('CODECLAS', $codeClasse)->delete();

        $total = Eleve::where('CODECLAS', $codeClasse)->count();
         //  dd("Il y a $total élèves enregistrés");

        // Récupérer la classe et son CODEPROMO
        $classe = Classe::where('CODECLAS', $codeClasse)->firstOrFail();
        $codePromo = $classe->CODEPROMO;
        
        // Récupérer la configuration annuelle
        $config = DecisionConfigAnnuel::where('Promotion', $codePromo)->get();
        //   dd($config);
       

        // Récupérer les seuils globaux
         $seuilPassage = DecisionConfigAnnuel::where('Promotion', $codePromo)
        ->value('seuil_Passage');

        $seuilFelicitations = DecisionConfigAnnuel::where('Promotion', $codePromo)
        ->value('seuil_felicitations');
        
        $seuilEncouragements = DecisionConfigAnnuel::where('Promotion', $codePromo)
        ->value('seuil_encouragements');

        $seuilTH = DecisionConfigAnnuel::where('Promotion', $codePromo)
            ->value('seuil_tableau_honneur');

        $minCycle = $classe->CYCLE == 1 ? $config[0]->Min_Cycle1 : $config[0]->Min_Cycle2;
        //  dd($seuilEncouragements);
        
        // Préparer les 4 statutf
        $statuts = $config->pluck('StatutF');
        // dd($statuts);

        // Charger les élèves de la classe
        $eleves = Eleve::where('CODECLAS', $codeClasse)->OrderBy('RANGA')->get();
        // dd($eleves);


        foreach ($eleves as $eleve) {
            $man = $eleve->MAN;
            $statut = $eleve->STATUT;
            $ran = $eleve->RANGA;
            $matricule = $eleve->MATRICULEX;
           if (trim($matricule) === '') {
                $matricule = null;
            }

            // Déterminer STATUTF
            
            
             if ($man >= $seuilPassage) {
                $statutf = $statuts[0];
            } elseif ($man < $minCycle && $statut == 0) {
                $statutf = $statuts[1];
            } elseif ($man >= $minCycle && $man < $seuilPassage && $statut == 0) {
                $statutf = $statuts[2];
            } elseif ($man < $seuilPassage && $statut == 1) {
                $statutf = $statuts[3];
            } else {
                $statutf = null;
            }

            if ($man >= $seuilFelicitations) {
                // le plus haut : Tableau d’honneur
                $observation = 'Fél.Enc.TH';
            }
            elseif ($man >= $seuilEncouragements) {
                // deuxième palier : Félicitations pour encouragement + tableau d’honneur
                $observation = 'Enc.TH';
            }
            elseif ($man >= $seuilTH) {
                // troisième palier : Encouragements
                $observation = 'TH';
            }
            elseif ($statutf === 'X' && $statut === 0 && $man < $seuilPassage) {
                // redoublant (statut 0) en dessous du seuil de passage
                $observation = 'Insuff Travail';
            }
            elseif ($statutf === 'X' && $statut === 1 && $man < $seuilPassage) {
                // exclu/tripler (statut 1) en dessous du seuil de passage
                $observation = 'Ne peut Tripler';
            }
            else {
                $observation = '';
            }



             Rapport::create([
                'RANG' => $eleve->RANGA,
                'MATRICULE'   => $matricule,
                'NOM'         => $eleve->NOM,
                'PRENOM'      => $eleve->PRENOM,
                'MOY1'        => $eleve->MS1,
                'MOY2'        => $eleve->MS2,
                'MOY3'        => $eleve->MS3,
                'MOYAN'       => $man,
                'OBSERVATION' => $observation,
                'STATUTF'     => $statutf,
                'CODECLAS'    => $codeClasse,
                'SEXE'        => $eleve->SEXE,
                'STATUT'      => $statut,
                'ANSCOL'      => $eleve->anneeacademique,
            ]);
           //dd($a);     
        }

        // Récupère tous les CODECLAS pour lesquels il existe au moins un rapport
        $codesClassesAvecRapport = Rapport::select('CODECLAS')
            ->distinct()
            ->pluck('CODECLAS');

        $classesAvecRapport = Classe::whereIn('CODECLAS', $codesClassesAvecRapport)
            ->orderBy('CODECLAS')
            ->get();

        // 6. Charger et passer à la vue pour affichage immédiat
        $rapports = Rapport::where('CODECLAS', $codeClasse)
            ->orderBy('RANG', 'ASC')
            ->get();


                    // CALCUL DES STATISTIQUES
        $effectifTotal     = $rapports->count();
        $effectifFilles    = $rapports->where('SEXE', 2)->count();

        // 'P' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $passantsTotal     = $rapports->where('STATUTF', 'P')->count();
        $passantesFilles   = $rapports->where('STATUTF', 'P')
                                    ->where('SEXE', 2)
                                    ->count();

                                    // 'R' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $redoublesTotal     = $rapports->where('STATUTF', 'R')->count();
        $redoublesFilles   = $rapports->where('STATUTF', 'R')
                                    ->where('SEXE', 2)
                                    ->count();

                                    // 'X' = code StatutF pour les passages (à adapter si vous avez un autre code)
        $exlusesTotal     = $rapports->where('STATUTF', 'X')->count();
        $exlusFilles   = $rapports->where('STATUTF', 'X')
                                    ->where('SEXE', 2)
                                    ->count();

                                    //Abandon
        $abandonsTotal     = $rapports->where('RANG', '=', 0)->count();
        $abandonsFilles   = $rapports->where('RANG', '=', 0)
                                    ->where('SEXE', 2)
                                    ->count();

        return view('pages.notes.rapportannuel', [
            'promotions' => $promotions,
            'classes'    => $classes,
            'configs'    => $configs,
            'promo'      => $promoList,
            'config'     => $configAnn,
            'rapports'   => $rapports,       // ← votre nouvelle liste
            'classesAvecRapport'=> $classesAvecRapport,
            'selectedClasseCode' => $codeClasse,
            'effectifTotal'      => $effectifTotal,
            'effectifFilles'     => $effectifFilles,
            'passantsTotal'      => $passantsTotal,
            'passantesFilles'    => $passantesFilles,
            'redoublesFilles' => $redoublesFilles,
            'redoublesTotal' => $redoublesTotal,
            'exlusesTotal'      => $exlusesTotal,
            'exlusFilles'    => $exlusFilles,
            'abandonsTotal'      => $abandonsTotal,
            'abandonsFilles'    => $abandonsFilles,
        ])->with('success', 'Rapport créé avec succès.');  
    }

    public function deleteClasse(Request $request){
         
        $codeClasse = $request->input('classe_selectionne');
 
        Rapport::where('CODECLAS', $codeClasse)->delete();

        return redirect()->back()->with('success', 'Classes Supprimée avec succès');
    
    }


    public function imprimerParStatut($statut = null)
        {
            $query = Rapport::query();

            if ($statut === 'abandon') {
                $query->where('MOYAN', -1);
            } else {
                $query->where('statutF', strtoupper($statut))->where('MOYAN', '!=', -1);
            }

            $rapports = $query->orderBy('CODECLAS')->orderBy('RANG')->get()->groupBy('CODECLAS');

            return view('pages.notes.rapportannuel', [
                'rapportsParClasse' => $rapports,
                'statut' => $statut
            ]);

        }

        // Méthodes spécifiques :
        public function imprimerPassage() {
            return $this->imprimerParStatut('P');
        }
        public function imprimerRedoublement() {
            return $this->imprimerParStatut('R');
        }
        public function imprimerExclusion() {
            return $this->imprimerParStatut('X');
        }
        public function imprimerAbandon() {
            return $this->imprimerParStatut('abandon');
        }


}
