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
    

    public function relevespareleves(){
        return view('pages.notes.relevespareleves');
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
        
        return view('pages.notes.listedesmeritants');
    }

    
}