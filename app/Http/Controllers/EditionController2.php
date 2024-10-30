<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Matieres;
use App\Models\Eleve;
use App\Models\Paramcontrat;

use Illuminate\Support\Facades\DB;

class EditionController2 extends Controller
{

    public function editions2(){
        return view('pages.notes.editions2');
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
    public function tableauanalytiqueparmatiere(){
        $matieres = Matieres::all();
        return view('pages.notes.tableauanalytiqueparmatiere', compact('matieres'));
    }    
    public function resultatsparpromotion(){
        
        return view('pages.notes.resultatsparpromotion');
    }    
    public function listedesmeritants(){
        
        return view('pages.notes.listedesmeritants');
    }

    
}