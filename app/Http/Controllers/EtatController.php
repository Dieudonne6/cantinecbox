<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiementcontrat;
use App\Models\Paiementglobalcontrat;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Models\Eleve;
use App\Models\Contrat;
use App\Models\Params2;

use App\Models\Classes;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\Moiscontrat;
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
    $dateselectionne = $request->input('daterelance');

    $date = Carbon::parse($dateselectionne);
    $dateFormatee = $date->format('d/m/Y');

    $selectedDate = Carbon::createFromFormat('Y-m-d', $dateselectionne);
    // dd($dateselectionne);

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

    $elevematricule = DB::table('eleve')
    ->whereIn('MATRICULE', $unpaidEleves->values())
    ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
    ->get();

    
    $matricules = $elevematricule->mapWithKeys(function ($elevematricule) {
        return [$elevematricule->MATRICULE => ['nom_complet' => $elevematricule->NOM . ' ' . $elevematricule->PRENOM, 'classe' => $elevematricule->CODECLAS]];
    });

    $results = [];

    foreach ($unpaidEleves as $id_contrat => $id_eleve) {
        $results[] = [
            'nometclasse' => $matricules[$id_eleve],
            'mois_impayes' => $unpaidContrats[$id_contrat],
            'datebuttoire' => $dateFormatee
        ];
    }
    
    // dd($results);

    $paramse = Params2::all();

    return view('pages.etat.relance')->with('results', $results)->with('paramse', $paramse);
// return view('pages.etat.relance')->with('results', $results);


}

    





    public function essairelance ( Request $request ){
        $allpaiementglobalcontrat = Paiementglobalcontrat::distinct()->pluck('mois_paiementcontrat'); 
        $daterelance = $request->daterelance;

            // Récupérer les mois payable
            $moisContrat = Moiscontrat::all();

            // Filtrer pour exclure les mois de juillet et d'août
            // $moisContratFiltered = $moisContrat->filter(function ($mois) {
            //     return $mois->nom_moiscontrat != 'Juillet' && $mois->nom_moiscontrat != 'Aout';
            // });

            // // Convertir en tableau avec les ids comme clés
            // $moisContratPayable = $moisContratFiltered->pluck('nom_moiscontrat', 'id_moiscontrat')->toArray();
 
            // // dd($moisContratPayable);

            // // Créer un objet Carbon
            // $date = Carbon::parse($daterelance);

            // // Extraire le mois et le convertir en entier
            // $mois = (int) $date->format('m');

            // // Afficher le mois
            // // dd($mois);

            // // Tableau pour stocker les mois précédents au mois spécifié
            // $moisPrecedents = [];

            // // Parcourir les mois payables et ajouter les mois précédents au mois spécifié
            // foreach ($moisContratPayable as $key => $value) {
            //     if ($key >= 9 && $key <= $mois) { // Inclure les mois de septembre à décembre si le mois spécifié est avant juin
            //         $moisPrecedents[$key] = $value;
            //     }
            // }

            // // Afficher les mois précédents
            // dd($moisPrecedents);

            // // recuperer la liste des paiements dont les mois de paiement ne corresponde pas a tout les mois qui devraient etre paye

            // // Récupérer les paiements dont les mois ne correspondent pas aux mois précédents
            // $paiementsExclus = Paiementglobalcontrat::whereNotIn('mois_paiementcontrat', $moisPrecedents)->pluck('mois_paiementcontrat');
            // // Récupérer les paiements dont les mois ne correspondent pas aux mois précédents
            // $paiements = Paiementglobalcontrat::whereNotIn('mois_paiementcontrat', $moisPayablesIds)
            // ->where('statut_paiementcontrat', '=', 1) // Supposant que le statut 1 signifie payé
            // ->get();

            
            // dd($paiements);

            // Exemple de mois payables, vous pouvez ajuster cela en fonction de votre logique
            $moisContratPayable = [
                1 => "Janvier",
                2 => "Février",
                3 => "Mars",
                4 => "Avril",
                5 => "Mai",
                6 => "Juin",
                9 => "Septembre",
                10 => "Octobre",
                11 => "Novembre",
                12 => "Décembre"
            ];

            
                // Créer un objet Carbon
            $date = Carbon::parse($daterelance);

            // Extraire le mois et le convertir en entier
            $mois = (int) $date->format('m');
      
            $moisPrecedents = [];

            foreach ($moisContratPayable as $key => $value) {
                if ($key >= 9 && $key <= $mois) { // Inclure les mois de septembre à octobre
                    $moisPrecedents[$key] = $value;
                }
            }

            // $stringMoisPrecedents = implode(', ', $moisPrecedents);


            // Récupérer les IDs des mois précédents
            // $moisPrecedentsIds = array_keys($moisPrecedents);
            // $moisPrecedentsNoms = array_values($moisPrecedents);
            // $moisPrecedentsNomss = array_values($moisPrecedents);

            // $string = '';
            // foreach ($moisPrecedentsNoms as $key => $value) {
            //     $string .= $value . ', ';
            // }

            // // Supprimer la dernière virgule et l'espace
            // $string = rtrim($string, ', ');
            
            // $moisPrecedentsTrouver = explode(', ', $string);


            // dd($stringMoisPrecedents);

           // Récupérez les paiements déjà effectués pour ces mois
    $paiementsEffectues = Paiementglobalcontrat::whereIn('mois_paiementcontrat', $moisPrecedents)
    ->pluck('id_contrat');

// Récupérez les contrats impayés pour ces mois
$contratsImpayes = Paiementglobalcontrat::whereNotIn('id_contrat', $paiementsEffectues)
    ->whereNotIn('mois_paiementcontrat', $moisPrecedents)
    ->pluck('mois_paiementcontrat');

            dd ($contratsImpayes);
    }


   

}
