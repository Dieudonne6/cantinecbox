<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Matieres;
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
        $notes = Classes::all();
        return view('pages.notes.fichedenotesvierge', compact('notes'));
    }
    
    public function relevesparmatiere(){
        $releves = Classes::all();
        $matieres = Matieres::all();
        return view('pages.notes.relevesparmatiere', compact('releves', 'matieres'));
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
