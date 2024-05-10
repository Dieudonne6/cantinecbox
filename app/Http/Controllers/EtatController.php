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
        
        // Passer les données à la vue
        return view('pages.etat.filteretat')->with('eleves', $eleves)->with('moisContrat', $moisContrat)->with('anne', $anne)->with('class', $class);
    }
    
    
}
