<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classes;
use App\Models\Contrat;
use App\Models\Paramcontrat;

use Illuminate\Support\Facades\Session;
class ClassesController extends Controller
{
    public function classe(Request $request){
        $eleves = Eleve::get();
        $classes = Classes::get();
        $fraiscontrat = Paramcontrat::first();
        return view('pages.classes')->with('eleve', $eleves)->with('classe', $classes)->with('fraiscontrats', $fraiscontrat);
    }
    
    public function filterEleve($CODECLAS){
        $eleves = Eleve::get();
        $classes = Classes::get();
        $filterEleves = Eleve::where('CODECLAS', $CODECLAS)->get();
        return view('pages.filterEleve')->with("filterEleve", $filterEleves)->with('classe', $classes)->with('eleve', $eleves);
    }
    public function creercontrat(Request $request){
        $matricules = $request->input('matricules');
        $existingContrat = Contrat::where('eleve_contrat', $matricules)->exists();
            if($existingContrat) {
            return back()->with('status', 'Un contrat existe déjà pour cet élève.');
        }
        $contra = new Contrat();
        $contra->eleve_contrat = $matricules;
        $contra->cout_contrat = $request->input('montant');
        $contra->datecreation_contrat = $request->input('date');
        $contra->save();
    
        return back()->with('status','Contrat enregistré avec succès');
    }
    
   


}
