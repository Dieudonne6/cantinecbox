<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiementcontrat;
use App\Models\Paiementglobalcontrat;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

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
    public function relance(Request $request){
        $relan = $request->daterelance;
        return view('pages.etat.relance')->with('relan', $relan);           
    }









    public function essairelance ( Request $request ){
        $allpaiementglobalcontrat = Paiementglobalcontrat::distinct()->pluck('mois_paiementcontrat'); 
        $daterelance = $request->daterelance;

            // Récupérer les mois payable
            $moisContrat = Moiscontrat::all();

            // Filtrer pour exclure les mois de juillet et d'août
            $moisContratFiltered = $moisContrat->filter(function ($mois) {
                return $mois->nom_moiscontrat != 'Juillet' && $mois->nom_moiscontrat != 'Aout';
            });

            // Convertir en tableau avec les ids comme clés
            $moisContratPayable = $moisContratFiltered->pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();
 
            // dd($moisContratPayable);

            // Créer un objet Carbon
            $date = Carbon::parse($daterelance);

            // Extraire le mois et le convertir en entier
            $mois = (int) $date->format('m');

            // Afficher le mois
            // dd($mois);

            // Tableau pour stocker les mois précédents au mois spécifié
            $moisPrecedents = [];

            // Parcourir les mois payables et ajouter les mois précédents au mois spécifié
            foreach ($moisContratPayable as $key => $value) {
                if ($key >= 9 && $key <= $mois) { // Inclure les mois de septembre à décembre si le mois spécifié est avant juin
                    $moisPrecedents[$key] = $value;
                }
            }

            // Afficher les mois précédents
            dd($moisPrecedents);

            // recuperer la liste des paiements dont les mois de paiement ne corresponde pas a tout les mois qui devraient etre paye

            // Récupérer les paiements dont les mois ne correspondent pas aux mois précédents
            $paiementsExclus = Paiementglobalcontrat::whereNotIn('mois_paiementcontrat', $moisPrecedents)->pluck('mois_paiementcontrat');
                // Récupérer les paiements dont les mois ne correspondent pas aux mois précédents
    $paiements = Paiementglobalcontrat::whereIn('mois_paiementcontrat', $moisPrecedents)
    ->where('statut_paiementcontrat', '=', 0) // Supposant que le statut 0 signifie impayé
    ->get();

            
            dd($paiements);
            
        
      
    }
}
