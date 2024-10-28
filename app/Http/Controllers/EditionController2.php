<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Matieres;

use Illuminate\Support\Facades\DB;

class EditionController2 extends Controller
{

    public function editions2(){
        return view('pages.notes.editions2');
      }


    public function fichedenotesvierge(){
        $notes = Classes::all();
        return view('pages.notes.fichedenotesvierge', compact('notes'));
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