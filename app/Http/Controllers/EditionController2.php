<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Matieres;
use App\Models\Eleve;
use App\Models\Paramcontrat;

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
    public function listedesmeritants(){
        $classes = Classes::all();
        $promotions = Promo::all();
        $matieres = Matieres::all();
        $eleves = Eleve::all();
        return view('pages.notes.listedesmeritants', compact('classes', 'promotions', 'matieres', 'eleves'));
    }
//     public function listedesmeritants(Request $request)
// {
//     // Récupération des filtres envoyés depuis le formulaire
//     $filtre      = $request->input('filtre', 'classe');
//     $nombre      = $request->input('nombre', 10);
//     $moyenneMin  = $request->input('moyenne_min', 0);
//     $sexe        = $request->input('sexe', 'Aucune');

//     // Démarrage de la requête sur les élèves
//     $elevesQuery = Eleve::query();

//     // Application du filtre choisi
//     if ($filtre === 'classe') {
//         $classesSelectionnees = $request->input('classes', []);
//         if (!empty($classesSelectionnees)) {
//             $elevesQuery->whereIn('CODECLAS', $classesSelectionnees);
//         }
//     } elseif ($filtre === 'promotion') {
//         $promotionsSelectionnees = $request->input('promotions', []);
//         if (!empty($promotionsSelectionnees)) {
//             $elevesQuery->whereIn('CODEPROMO', $promotionsSelectionnees);
//         }
//     } elseif ($filtre === 'cycle') {
//         $cycle = $request->input('cycle', 1);
//         // On suppose ici que la colonne "cycle" existe dans la table ou qu'une jointure est nécessaire
//         $elevesQuery->where('cycle', $cycle);
//     }
//     // Pour "tout", aucun filtre additionnel n'est appliqué

//     // Filtrer par sexe si nécessaire (en adaptant selon votre convention en BDD)
//     if ($sexe !== "Aucune") {
//         $elevesQuery->where('SEXE', ($sexe == 'Filles' ? 'F' : 'M'));
//     }

//     // Appliquer le filtre sur la moyenne (ici on suppose que la moyenne est un champ ou un attribut calculé)
//     $elevesQuery->where('moyenne', '>=', $moyenneMin);

//     // Trier par moyenne décroissante et limiter le nombre d'élèves récupérés
//     $eleves = $elevesQuery->orderBy('moyenne', 'desc')
//                           ->limit($nombre)
//                           ->get();

//     // Récupérer les données pour les listes déroulantes
//     $classes    = Classes::all();
//     $promotions = Promo::all();
//     $matieres   = Matieres::all();

//     return view('pages.notes.listedesmeritants', compact('eleves', 'classes', 'promotions', 'matieres'));
// }


    
}