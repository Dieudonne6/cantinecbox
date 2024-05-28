<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiementcontrat;
use Illuminate\Support\Facades\Session;

use App\Models\Eleve;
use App\Models\Contrat;
use App\Models\Params2;

use App\Models\Classes;
use App\Models\Paiementglobalcontrat;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\Moiscontrat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
   
   


public function relance(Request $request)
{
    //lolo
    // Date sélectionnée par l'utilisateur
    $selectedDate = Carbon::createFromFormat('Y-m-d', $request->input('daterelance'));

    // Liste des mois en français dans l'ordre scolaire (septembre à août)
    $months = ["Septembre", "Octobre", "Novembre", "Decembre", "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout"];
    
    // Convertir la date sélectionnée en mois et année
    $selectedMonthName = $selectedDate->format('F'); // Mois en anglais
    $selectedYear = $selectedDate->format('Y');

    // Mapper les mois anglais aux mois français pour correspondre avec notre tableau
    $monthsMap = [
        'January' => 'Janvier', 'February' => 'Fevrier', 'March' => 'Mars', 
        'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 
        'July' => 'Juillet', 'August' => 'Aout', 'September' => 'Septembre', 
        'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Decembre'
    ];
    
    $selectedMonth = $monthsMap[$selectedMonthName];

    // Trouver l'index du mois sélectionné dans le tableau des mois scolaires
    $selectedMonthIndex = array_search($selectedMonth, $months);

    // Trouver les mois précédant le mois sélectionné dans l'année scolaire
    $previousMonths = array_slice($months, 0, $selectedMonthIndex + 1);
    // Récupérer tous les paiements
    $paiements = Paiementglobalcontrat::all();

    // Grouper les mois payés par id_contrat
    $contratPayments = [];

    foreach ($paiements as $paiement) {
        $id_contrat = $paiement->id_contrat;
        $paidMonths = preg_split('/[\s,]+/', $paiement->mois_paiementcontrat);

        if (!isset($contratPayments[$id_contrat])) {
            $contratPayments[$id_contrat] = [];
        }

        $contratPayments[$id_contrat] = array_merge($contratPayments[$id_contrat], $paidMonths);
    }

    // Initialiser un tableau pour stocker les id_contrat non payés
    $unpaidContrats = [];

    // Parcourir les paiements regroupés pour vérifier les mois impayés
    foreach ($contratPayments as $id_contrat => $paidMonths) {
        $paidMonths = array_map('trim', $paidMonths); // Supprimer les espaces éventuels
        $paidMonths = array_unique($paidMonths); // Supprimer les doublons
        $unpaidMonths = [];
        foreach ($previousMonths as $month) {
            if (!in_array($month, $paidMonths)) {
                $unpaidMonths[] = $month;
            }
        }

        if (!empty($unpaidMonths)) {
            $unpaidContrats[$id_contrat] = $unpaidMonths;
        }
    }
    $unpaidEleves = DB::table('contrat')
    ->whereIn('id_contrat', array_keys($unpaidContrats))
    ->pluck('eleve_contrat', 'id_contrat');


    $eleveDetails = DB::table('eleve')
    ->whereIn('MATRICULE', $unpaidEleves->values())
    ->get()
    ->keyBy('MATRICULE');
    $results = [];

    foreach ($unpaidEleves as $id_contrat => $id_eleve) {
        $results[] = [ 
            'details' => $eleveDetails[$id_eleve],
            'mois_impayes' => $unpaidContrats[$id_contrat]
        ];
    }
    $paramse = Params2::all();

    return view('pages.etat.relance')->with('results', $results)->with('paramse', $paramse);
    // Retourner les résultats dans une vue
// return view('pages.etat.relance')->with('results', $results);
}

}
