<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Matieres;
use App\Models\Eleve;
use App\Models\Paramcontrat;
use App\Models\Params2;

use App\Models\Notes;
use App\Models\Trimencours;
use App\Models\Eleves;
use App\Models\Typeenseigne;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;

class EditionController2 extends Controller
{

    public function editions2(){
        $matieres = Matieres::all();
        return view('pages.notes.editions2', compact('matieres'));
      }


    public function fichedenotesvierge(){
        $classes = Classes::where('TYPECLASSE', 1)->get();
        return view('pages.notes.fichedenotesvierge', compact('classes'));
    }

    public function fichedenoteviergefina($classeCode) {
        $CODECLASArray = explode(',', $classeCode);

        $eleves = Eleve::orderBy('NOM', 'asc')->get();
        $classesAExclure = ['NON', 'DELETE'];
    
        $classes = Classes::where('TYPECLASSE', 1)->get();
        // $fraiscontrat = Paramcontrat::first(); 
    
        // $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');
    
        // Filtrer les élèves en fonction des classes sélectionnées
        $filterEleves = Eleve::whereIn('CODECLAS', $CODECLASArray)
            // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
            ->orderBy('NOM', 'asc')
            // ->groupBy('CODECLAS')
            ->get();

        $elevesGroupes = $filterEleves->groupBy('CODECLAS');

        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours.'-'.$annesuivante;


        // dd($elevesGroupes);

        return view('pages.notes.fichedesnotesvierge1')->with('elevesGroupes', $elevesGroupes)->with('classes', $classes)->with('filterEleves', $filterEleves)->with('annescolaire', $annescolaire);
    }
        
    public function recapitulatifdenotes(){
        return view('pages.notes.recapitulatifdenotes');
    }

    public function tableauanalytiqueparmatiere(Request $request){
        $trimestres = Trimencours::all();
        
        $matieres = match($request->matiere) {
            'all' => Matieres::all(),
            'litteraires' => (function() use ($request) {
                $request->typeenseig = 3;
                return Matieres::where('TYPEMAT', 1)->get();
            })(),
            'scientifiques' => (function() use ($request) {
                $request->typeenseig = 3;
                return Matieres::where('TYPEMAT', 2)->get();
            })(),
            'technique' => (function() use ($request) {
                $request->typeenseig = 4;
                return Matieres::where('TYPEMAT', 3)->get();
            })(),
            default => Matieres::where('CODEMAT', $request->matiere)->get(),
        };
        
        $classes = $request->typeenseig == 'tous' ? Classes::all() : Classes::where('TYPEENSEIG', $request->typeenseig == 'general' ? 3 : 4)->get();
        $stats = []; // Initialiser un tableau pour les statistiques

        foreach ($classes as $classe) {
            foreach ($matieres as $matiere) {
                $notesStats = Notes::select(
                    DB::raw('count(distinct MATRICULE) as nombre_eleves'),
                    DB::raw('max(MS) as moyenne_max'),
                    DB::raw('min(MS) as moyenne_min'),
                    DB::raw('count(case when MS < 10 then 1 end) as nombre_moyenne_inf_10'),
                    DB::raw('count(case when MS >= 10 then 1 end) as nombre_moyenne_sup_10')
                )
                ->where('CODECLAS', $classe->CODECLAS)
                ->where('CODEMAT', $matiere->CODEMAT)
                ->groupBy('CODECLAS')
                ->first();
                if ($notesStats) {
                    $stats[$classe->CODECLAS][$matiere->CODEMAT] = [
                        'notesStats' => $notesStats,
                        'pourcentage_moyenne_sup_10' => $notesStats->nombre_moyenne_sup_10 / $notesStats->nombre_eleves * 100
                    ];
                }
            }
        }

        return view('pages.notes.tableauanalytiqueparmatiere', compact('trimestres', 'classes', 'matieres', 'stats'));
    }    

    public function resultatsparpromotion(){
        
        return view('pages.notes.resultatsparpromotion');
    }    
    
    public function listedesmeritants() {
        $classes = Classes::all();
        $promotions = Promo::all();
        $matieres = Matieres::all();
        $params = Params2::first();
        $eleves = []; // Tableau vide initial
        $notes = []; // Tableau vide initial
        return view('pages.notes.listedesmeritants', compact('classes', 'promotions', 'matieres', 'eleves', 'notes', 'params'));
    }
    
    public function searchMeritants(Request $request)
    {
        $filtre = $request->input('filtre');
    
        // Appliquer les filtres sur les élèves (classe, promotion, cycle)
        $eleveQuery = Eleve::query();
        if ($filtre == 'classe' && $request->has('classes')) {
            $eleveQuery->whereIn('CODECLAS', $request->input('classes'));
        } elseif ($filtre == 'promotion' && $request->has('promotions')) {
            $classesPromo = Classes::whereIn('CODEPROMO', $request->input('promotions'))
                ->pluck('CODECLAS')
                ->toArray();
            $eleveQuery->whereIn('CODECLAS', $classesPromo);
        } elseif ($filtre == 'cycle' && $request->has('cycle')) {
            $classesCycle = Classes::where('cycle', $request->input('cycle'))
                ->pluck('CODECLAS')
                ->toArray();
            $eleveQuery->whereIn('CODECLAS', $classesCycle);
        }
    
        // Filtrer sur les champs présents dans la table eleves
        if ($request->filled('conduite_min')) {
            $eleveQuery->where('conduite', '>=', $request->input('conduite_min'));
        }
        if ($request->filled('sexe') && $request->input('sexe') != 'Aucune') {
            $eleveQuery->where('SEXE', $request->input('sexe'));
        }
    
        // Si une matière spécifique est demandée, filtrer via la table notes
        if ($request->filled('matiere') && $request->input('matiere') != 'moyenne_generale') {
            $matiere = $request->input('matiere');
            $semestre = $request->input('periode');
    
            // Récupérer les matricules correspondant aux filtres sur les élèves
            $matricules = $eleveQuery->pluck('MATRICULE')->toArray();
    
            // Requête sur la table notes avec eager loading pour récupérer les infos de l'élève
            $notesQuery = Notes::query();
            $notesQuery->with('eleve');
            $notesQuery->whereIn('MATRICULE', $matricules)
                       ->where('CODEMAT', $matiere)
                       ->where('SEMESTRE', $semestre)
                       ->orderBy('MS1', 'desc');
            
            if ($request->filled('nombre')) {
                $notesQuery->limit($request->input('nombre'));
            }
            
            $notes = $notesQuery->get();
            return response()->json($notes);
        } else {
            // Cas de la moyenne générale : filtrer directement sur la table eleves
            $periode = $request->input('periode');
            $orderField = ($periode === 'AN') ? 'MAN' : 'MS' . $periode;
            $eleveQuery->orderBy($orderField, 'desc');
    
            if ($request->filled('nombre')) {
                $eleveQuery->limit($request->input('nombre'));
            }
            $eleves = $eleveQuery->get();
            return response()->json($eleves);
        }
    }
    
// public function searchMeritants(Request $request)
// {
//     $filtre = $request->input('filtre');
//     $query = Eleve::query();

//     // Filtrage selon le critère sélectionné
//     if ($filtre == 'classe' && $request->has('classes')) {
//         $query->whereIn('CODECLAS', $request->input('classes'));
//     } elseif ($filtre == 'promotion' && $request->has('promotions')) {
//         $classesPromo = Classes::whereIn('CODEPROMO', $request->input('promotions'))
//                                 ->pluck('CODECLAS')
//                                 ->toArray();
//         $query->whereIn('CODECLAS', $classesPromo);
//     } elseif ($filtre == 'cycle' && $request->has('cycle')) {
//         $classesCycle = Classes::where('cycle', $request->input('cycle'))
//                                 ->pluck('CODECLAS')
//                                 ->toArray();
//         $query->whereIn('CODECLAS', $classesCycle);
//     }
    
//     // Filtrage par matière ou moyenne générale
//     if ($request->filled('matiere') && $request->input('matiere') != 'moyenne_generale') {
//         // Pour une matière, on filtre sur le semestre (colonne SEMESTRE)
//         $matiere = $request->input('matiere');
//         $semestre = $request->input('periode'); // Valeur du semestre à filtrer
        
//         // Jointure sur la table notes en filtrant sur le code de la matière et le semestre
//         $query->join('notes', function($join) use ($matiere, $semestre) {
//             $join->on('eleves.MATRICULE', '=', 'notes.MATRICULE')
//                  ->where('notes.CODEMAT', '=', $matiere)
//                  ->where('notes.SEMESTRE', '=', $semestre);
//         });
//         // On sélectionne toujours la colonne MS1 comme note
//         $query->select('eleves.*', 'notes.MS1 as NOTE');
//         $query->orderBy('NOTE', 'desc');
//     } else {
//         // Pour la moyenne générale, le tri dépend de la période
//         $periode = $request->input('periode');
//         $orderField = ($periode === 'AN') ? 'MAN' : 'MS' . $periode;
//         $query->orderBy($orderField, 'desc');
//     }

//     // Autres filtres éventuels
//     if ($request->filled('conduite_min')) {
//         $query->where('conduite', '>=', $request->input('conduite_min'));
//     }
//     if ($request->filled('sexe') && $request->input('sexe') != 'Aucune') {
//         $query->where('SEXE', $request->input('sexe'));
//     }

//     // Limitation du nombre de résultats
//     if ($request->filled('nombre')) {
//         $query->limit($request->input('nombre'));
//     }
    
//     $eleves = $query->get();
//     return response()->json($eleves);
// }
}