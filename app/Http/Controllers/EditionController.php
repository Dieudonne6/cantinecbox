<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\Delevea;
use App\Models\Params2;

use Illuminate\Support\Facades\DB;


class EditionController extends Controller
{
    public function editions(){
    
          // LISTE DES ELEVES DONT ARRIERE EST != 0
    $listeElevesArr = Eleve::where('ARRIERE', '!=', 0)->get();
    // Initialiser un tableau pour stocker les résultats
    $resultats = [];

    // Parcourir chaque élève
    foreach ($listeElevesArr as $eleve) {

        // Calculer la somme des a_payer où autreref est 2 et le matricule correspond à celui de l'élève
        $somme = Scolarite::where('AUTREF', 2)
                          ->where('MATRICULE', $eleve->MATRICULE)
                          ->sum('MONTANT');
        
                          // dd($somme);

          $RESTE = $eleve->ARRIERE - $somme;
        // Ajouter le résultat au tableau
        $resultats[$eleve->MATRICULE] = [
          'NOM' => $eleve->NOM,
          'PRENOM' => $eleve->PRENOM,
          'CLASSE' => $eleve->CODECLAS,
          'ARRIERE' => $eleve->ARRIERE,
          'PAYE' => $somme,
          'RESTE' => $RESTE,
        ];
    }    

    $totalDues = 0;
    $totalPayes = 0;
    $totalRestes = 0;

    foreach ($resultats as $resultat) {
        $totalDues += $resultat['ARRIERE'];
        $totalPayes += $resultat['PAYE'];
        $totalRestes += $resultat['RESTE'];
    }
    // dd($resultats);
        $eleves = Eleve::where('EcheancierPerso', 1)
        ->orderBy('CODECLAS')
        ->get();

        return view('pages.inscriptions.editions', compact('eleves','resultats','totalDues','totalPayes','totalRestes'));
      } 

      public function arriereconstate(Request $request) {
          // Récupérer les dates du formulaire
          $datedebut = $request->input('datedebut');
          $datefin = $request->input('datefin');
      
          // Vérifier que les dates ont bien été soumises
          if ($datedebut && $datefin) {
              // Récupérer les lignes de scolarite avec jointure sur la table eleve
              $resultats = Scolarite::select(
                      'scolarit.DATEOP',
                      'scolarit.MONTANT',
                      'scolarit.MATRICULE',
                      'eleve.NOM',
                      'eleve.PRENOM',
                      'eleve.CODECLAS',
                      'scolarit.SIGNATURE'
                  )
                  ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE')
                  ->where('scolarit.AUTREF', 2)
                  ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin])
                  ->orderBy('scolarit.DATEOP')
                  ->get();
      
              // Pour chaque résultat, récupérer la classe précédente depuis la table 'elevea' de la base de données 'scoracine'
              $resultats->map(function($resultat) {
                  // Récupérer la classe précédente (dernier enregistrement dans la table 'elevea' pour le matricule)
                  $classePrecedente = DB::connection('mysql2')
                      ->table('delevea')
                      ->where('MATRICULE', $resultat->MATRICULE)
                      ->orderBy('ANSCOL', 'desc') // Trier pour obtenir la dernière entrée
                      ->value('CODECLAS'); // Récupérer le code de la classe
      
                  // Ajouter la classe précédente au résultat
                  $resultat->classe_precedente = $classePrecedente;
      
                  return $resultat;
              });
      
              // Regrouper par DATEOP
              $groupedResultats = $resultats->groupBy('DATEOP');
      
              // Retourner la vue avec les résultats groupés
              return view('pages.inscriptions.arriereconstate', compact('groupedResultats', 'datedebut', 'datefin'));
          } else {
              return back()->with('error', 'Veuillez fournir une date de début et une date de fin.');
          }
      }
      public function journal(Request $request) {
        // Récupérer les paramètres de filtrage depuis la requête
        // $datedebut = $request->input('datedebut');
        // $datefin = $request->input('datefin');
        // $typeenseign = $request->input('typeenseign');
            $datedebut = '2022-01-09';
        $datefin = '2024-10-09';
        $typeenseign = 2;
        // Requête pour récupérer les données
        $recouvrements = DB::table('scolarit')
    ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE') // Joindre la table des élèves
    ->join('typeenseigne', 'eleve.TYPEENSEIG', '=', 'typeenseigne.idenseign') // Joindre la table des types d'enseignement
    ->select('scolarit.DATEOP', 'scolarit.AUTREF', 'scolarit.EDITE', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type', DB::raw('SUM(scolarit.MONTANT) as total'))
    ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin]) // Filtrer par dates
    ->where('eleve.TYPEENSEIG', '=', $typeenseign) // Filtrer par type d'enseignement
    ->where('scolarit.VALIDE', '=', 1) // Filtrer les enregistrements où validate est égal à 1
    ->groupBy('scolarit.DATEOP', 'scolarit.AUTREF', 'scolarit.EDITE', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type') // Regrouper par date et autres champs
    ->orderBy('scolarit.DATEOP', 'asc') // Trier par date
    ->get();

    $libelle = Params2::first();
        // Retourner la vue avec les données
        return view('pages.inscriptions.journalderecouvrement', compact('recouvrements', 'libelle'));
    }
    
    
}

