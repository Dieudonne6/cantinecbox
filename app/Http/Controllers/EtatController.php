<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiementcontrat;
use Illuminate\Support\Facades\Session;

use App\Models\Eleve;
use App\Models\Contrat;
use App\Models\Classes;
use App\Models\Moiscontrat;
class EtatController extends Controller
{
    public function etatdroits(){
        $annees = Paiementcontrat::distinct()->pluck('anne_paiementcontrat'); 
        $classes = Classes::get();
        
        return view('pages.etat.etatdroits')->with('annee', $annees)->with('classe', $classes);
        
    }
    
    public function filteretat(Request $request){
        $anne = Paiementcontrat::distinct()->pluck('anne_paiementcontrat'); 
        $class = Classes::get();
        $query = Eleve::query();
        $annee = $request->annee;
        $classe = $request->classe;
        
        // Filtrer par année
        if ($request->has('annee')) {
            $query->whereHas('contrats', function($q) use ($request) {
                $q->whereHas('paiements', function($q) use ($request) {
                    $q->where('anne_paiementcontrat', $request->annee);
                });
            });
        }
        
        // Filtrer par classe
        if ($request->has('classe')) {
            $query->where('CODECLAS', $request->classe);
        }
        
        // Récupérer les mois de contrat
        $moisContrat = Moiscontrat::all();
        
        // Exécuter la requête
        $eleves = $query->with(['contrats' => function($query) use ($request) {
            $query->with(['paiements' => function($query) use ($request) {
                if ($request->has('annee')) {
                    $query->where('anne_paiementcontrat', $request->annee);
                }
            }]);
        }])->get();
       Session::put('eleves', $eleves);
       Session::put('moisContrat', $moisContrat);
       Session::put('anne', $anne);
       Session::put('class', $class);
        

        // Ajouter 1 à l'année
        $anneesuivant = $annee + 1;
        // Passer les données à la vue
        return view('pages.etat.filteretat')->with('eleves', $eleves)->with('moisContrat', $moisContrat)->with('anne', $anne)->with('class', $class)->with('annee', $annee)->with('classe', $classe)->with('anneesuivant', $anneesuivant);
    }
    
    public function lettrederelance(){
        
        $relance = Eleve::whereHas('contrats', function($query) {
            $query->where('statut_contrat', 1);
        })->get();
        return view('pages.etat.lettrederelance')->with('relance', $relance);           
    }
    public function relance(){
     
        return view('pages.etat.relance')->with('relance', $relance);           
    }

}
