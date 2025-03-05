<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\Delevea;
use App\Models\Params2;
use App\Models\Matieres;
use App\Models\Classes;
use App\Models\Notes;
use App\Models\Classe;
use App\Models\Clasmat;
use App\Models\Typeenseigne;
use Illuminate\Support\Facades\Schema;


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
    public function journaldetailleaveccomposante(Request $request) {
      // Récupérer les paramètres de filtrage depuis la requête
      $datedebut = $request->query('debut');
      $datefin = $request->query('fin');
      $typeenseign = $request->query('typeenseign');
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
      $enseign = Typeenseigne::where('idenseign', $typeenseign)->first();
      
      $libelle = Params2::first();
      // Retourner la vue avec les données
      return view('pages.inscriptions.journaldetailleaveccomposante', compact('recouvrements', 'libelle', 'enseign'));
    }
    
    public function journaldetaillesanscomposante(Request $request) {
      // Récupérer les paramètres de filtrage depuis la requête
      $datedebut = $request->query('debut');
      $datefin = $request->query('fin');
      $typeenseign = $request->query('typeenseign');
      // Requête pour récupérer les données
      $recouvrements = DB::table('scolarit')
      ->join('eleve', 'scolarit.MATRICULE', '=', 'eleve.MATRICULE') // Joindre la table des élèves
      ->join('typeenseigne', 'eleve.TYPEENSEIG', '=', 'typeenseigne.idenseign') // Joindre la table des types d'enseignement
      ->select('scolarit.DATEOP', 'scolarit.SIGNATURE', 'scolarit.NUMRECU', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type', DB::raw('SUM(scolarit.MONTANT) as total'))
      ->whereBetween('scolarit.DATEOP', [$datedebut, $datefin]) // Filtrer par dates
      ->where('eleve.TYPEENSEIG', '=', $typeenseign) // Filtrer par type d'enseignement
      ->where('scolarit.VALIDE', '=', 1) // Filtrer les enregistrements où validate est égal à 1
      ->groupBy('scolarit.DATEOP', 'scolarit.SIGNATURE', 'scolarit.NUMRECU', 'eleve.NOM', 'eleve.PRENOM', 'eleve.CODECLAS', 'typeenseigne.type') // Regrouper par date et autres champs
      ->orderBy('scolarit.DATEOP', 'asc') // Trier par date
      ->get();
      $enseign = Typeenseigne::where('idenseign', $typeenseign)->first();
      $libelle = Params2::first();
      // Retourner la vue avec les données
      return view('pages.inscriptions.journaldetaillesanscomposante', compact('recouvrements', 'libelle','enseign'));
    }
    
    public function tabledesmatieres() {
      $matiere = Matieres::all();
      $lastMatiere = Matieres::orderBy('CODEMAT', 'desc')->first();
      $nextCodeMat = $lastMatiere ? $lastMatiere->CODEMAT + 1 : 1; // Si aucune matière, commencer à 1
      return view('pages.notes.tabledesmatieres', compact('matiere',  'nextCodeMat',  'lastMatiere'));
    }
    public function filtrereleveparmatiere(Request $request) {
      $classe = $request->classe;
      $matiere = $request->matiere;
      // dd($classe);
      $notes = Notes::join('eleve', 'notes.MATRICULE', '=', 'eleve.MATRICULE')
      ->select('notes.MATRICULE', 'eleve.nom', 'eleve.prenom', 'notes.INT1', 'notes.INT2', 'notes.INT3', 'notes.INT4', 'notes.MI', 'notes.DEV1', 'notes.DEV2', 'notes.DEV3')
      ->when($classe, function ($q) use ($classe) {
        return $q->where('notes.CODECLAS', $classe);
      })
      ->when($matiere, function ($q) use ($matiere) {
        return $q->where('notes.CODEMAT', $matiere);
      })
      ->get();
      $matiere = Matieres::where('CODEMAT', $matiere)->first();
      $matiere = $matiere['LIBELMAT'];
      return view('pages.notes.filtrereleveparmatiere', compact('notes','classe','matiere'));
      
    }
    public function storetabledesmatieres(Request $request) {
      $matiere = new Matieres();
      $matiere->LIBELMAT = $request->libelle;
      $matiere->NOMCOURT = $request->nomcourt;
      $matiere->COULEUR = $request->couleur;
      $matiere->CODEMAT_LIGNE = $request->matligne;
      $lastMatiere = Matieres::orderBy('CODEMAT', 'desc')->first();
      $matiere->CODEMAT = $lastMatiere ? $lastMatiere->CODEMAT + 1 : 1;
      if ($request->input('typematiere') == 1) {
        $matiere->TYPEMAT = 1;
      }
      if ($request->input('typematiere') == 2) {
        $matiere->TYPEMAT = 2;
      } else {
        $matiere->TYPEMAT = 3;
      }
      
      // Vérification de l'écriture
      $matiere->COULEURECRIT = $request->input('ecrit') ? 0 : 16777215;
      
      $matiere->save();
      return redirect()->route('tabledesmatieres')->with('status', 'Matière enregistrée avec succès');
    }
    public function updatetabledesmatieres(Request $request) {
      // Validation des données
      $request->validate([
        'libelleModif' => 'required|string|max:255',
        'nomcourtModif' => 'required|string|max:255',
        'couleurModif' => 'required|string|max:7',
        'ecritModif' => 'boolean',
        'codemat' => 'required|integer',
      ]);
      $code = $request->input('codemat');
      $matiere = Matieres::where('CODEMAT', $code)->first();
      
      // Vérifiez si la matière existe
      if (!$matiere) {
        return redirect()->route('tabledesmatieres')->with('status', 'Matière non trouvée');
      }
      
      // Mise à jour des champs
      $matiere->LIBELMAT = $request->input('libelleModif');
      $matiere->NOMCOURT = $request->input('nomcourtModif');
      $matiere->COULEUR = $request->input('couleurModif') ?? '#FFFFFF';
      $matiere->CODEMAT_LIGNE = $request->input('matligneModif') ?? 0;
      // Mise à jour du type de matière
      $matiere->TYPEMAT = $request->input('typematiereModif') == 1 ? 1 : ($request->input('typematiereModif') == 2 ? 2 : 3); // Changer ici
      
      // Vérification de l'écriture
      $matiere->COULEURECRIT = $request->input('ecritModif') ? 0 : 16777215;
      
      // Sauvegarde des modifications
      $matiere->save();
      
      return redirect()->route('tabledesmatieres')->with('status', 'Matière mise à jour avec succès');
    }
    
    public function elevessansnote($classCode) {
      // Récupère les élèves de la classe spécifique
      $students = Eleve::where('CODECLAS', $classCode)->get();
      dd($students);
      // Formate la réponse JSON
      return response()->json([
        'students' => $students->map(function ($student) {
          return [
            'matricule' => $student->MATRICULE,
            'sexe' => $student->SEXE === 1 ? 'M' : 'F',
            'nom' => $student->NOM,
            'prenom' => $student->PRENOM,
            // 'int1' => $student->int1,
            // 'int2' => $student->int2,
            // 'int3' => $student->int3,
            // 'int4' => $student->int4,
            // 'mi' => $student->mi,
            // 'dev1' => $student->dev1,
            // 'dev2' => $student->dev2,
            // 'dev3' => $student->dev3,
            // 'compo' => $student->compo,
          ];
        })
      ]);
    }
    public function relevesparmatiere(){
      $classe = Classes::all();
      $matieres = Matieres::all();
      return view('pages.notes.relevesparmatiere', compact('classe', 'matieres'));
    }
    public function tableaudenotes(){
      $classe = Classes::all();
      $matieres = Matieres::all();
      return view('pages.notes.tableaudenotes');
    }
    public function filtertableaunotes(Request $request) {
      $intervals = [
        ['min' => $request->input('interval1'), 'max' => $request->input('interval2')],
        ['min' => $request->input('interval3'), 'max' => $request->input('interval4')],
        ['min' => $request->input('interval5'), 'max' => $request->input('interval6')],
        ['min' => $request->input('interval7'), 'max' => $request->input('interval8')],
        ['min' => $request->input('interval9'), 'max' => $request->input('interval10')],
      ];
      session(['intervals' => $intervals]);
      
      $classe = Classe::all();
      // Récupérer la classe, la période et le type d'évaluation sélectionnés
      $selectedClass = $request->input('classe','CE1A');
      $selectedPeriod = $request->input('periode',1);
      $selectedEvaluation = $request->input('note','DEV1'); // par exemple "Dev1"
      
      // Récupérer les matières de la classe sélectionnée
      $matieres = DB::table('clasmat')
      ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
      ->where('clasmat.CODECLAS', $selectedClass)
      ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
      ->get();
      
      $eleves = DB::table('eleve')
      ->where('CODECLAS', $selectedClass)
      ->select('MATRICULE', 'NOM', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
      ->get();
      
      
      $notes = DB::table('notes')
      ->where('CODECLAS', $selectedClass)
      ->where('SEMESTRE', $selectedPeriod)
      ->select('MATRICULE', 'CODEMAT', $selectedEvaluation)
      ->get()
      ->keyBy(function ($note) {
        return $note->MATRICULE . '-' . $note->CODEMAT;
      });
      $moyennes = [];
      foreach ($matieres as $matiere) {
        $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiere, $selectedEvaluation) {
          return $note->CODEMAT === $matiere->CODEMAT && isset($note->$selectedEvaluation);
        });
        $matiereScores = array_column($matiereNotes, $selectedEvaluation);
        $moyennes[$matiere->CODEMAT] = [
          'min' => count($matiereScores) > 0 ? min($matiereScores) : 0,
          'max' => count($matiereScores) > 0 ? max($matiereScores) : 0
        ];
      }
      $moyenneCounts = [];
      foreach ($matieres as $matiere) {
        $matiereCode = $matiere->CODEMAT;
        $moyenneCounts[$matiereCode] = [];
        
        // Filtrer les notes pour cette matière avec le selectedEvaluation
        $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiereCode, $selectedEvaluation) {
          return $note->CODEMAT === $matiereCode && isset($note->$selectedEvaluation);
        });
        
        // Extraire les notes pour cette matière spécifique
        $matiereScores = array_column($matiereNotes, $selectedEvaluation);
        
        // Compter les notes dans chaque intervalle
        foreach ($intervals as $interval) {
          $count = count(array_filter($matiereScores, function ($score) use ($interval) {
            return $score >= $interval['min'] && $score < $interval['max'];
          }));
          $moyenneCounts[$matiereCode][] = $count;
        }
      }
      
      return view('pages.notes.filtertableaunotes', compact('selectedPeriod','selectedClass','classe','matieres','eleves','notes','selectedEvaluation','moyennes','moyenneCounts', 'intervals'));
      
    }
    public function filtertablenotes(Request $request) {
      $intervals = session('intervals', [
        ['min' => 0, 'max' => 0],
        ['min' => 0, 'max' => 0],
        ['min' => 0, 'max' => 0],
        ['min' => 0, 'max' => 0],
        ['min' => 0, 'max' => 0],
      ]);
      // $intervals = [
      //     ['min' => $request->input('interval1'), 'max' => $request->input('interval2')],
      //     ['min' => $request->input('interval3'), 'max' => $request->input('interval4')],
      //     ['min' => $request->input('interval5'), 'max' => $request->input('interval6')],
      //     ['min' => $request->input('interval7'), 'max' => $request->input('interval8')],
      //     ['min' => $request->input('interval9'), 'max' => $request->input('interval10')],
      // ];
      $classe = Classe::all();
      // Récupérer la classe, la période et le type d'évaluation sélectionnés
      $selectedClass = $request->input('classe');
      $selectedPeriod = $request->input('periode');
      $selectedEvaluation = $request->input('note'); // par exemple "Dev1"
      
      // Récupérer les matières de la classe sélectionnée
      $matieres = DB::table('clasmat')
      ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
      ->where('clasmat.CODECLAS', $selectedClass)
      ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
      ->get();
      
      $eleves = DB::table('eleve')
      ->where('CODECLAS', $selectedClass)
      ->select('MATRICULE', 'NOM', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
      ->get();
      
      
      $notes = DB::table('notes')
      ->where('CODECLAS', $selectedClass)
      ->where('SEMESTRE', $selectedPeriod)
      ->select('MATRICULE', 'CODEMAT', $selectedEvaluation)
      ->get()
      ->keyBy(function ($note) {
        return $note->MATRICULE . '-' . $note->CODEMAT;
      });
      $moyennes = [];
      foreach ($matieres as $matiere) {
        $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiere, $selectedEvaluation) {
          return $note->CODEMAT === $matiere->CODEMAT && isset($note->$selectedEvaluation);
        });
        $matiereScores = array_column($matiereNotes, $selectedEvaluation);
        $moyennes[$matiere->CODEMAT] = [
          'min' => count($matiereScores) > 0 ? min($matiereScores) : 0,
          'max' => count($matiereScores) > 0 ? max($matiereScores) : 0
        ];
      }
      $moyenneCounts = [];
      foreach ($matieres as $matiere) {
        $matiereCode = $matiere->CODEMAT;
        $moyenneCounts[$matiereCode] = [];
        
        // Filtrer les notes pour cette matière avec le selectedEvaluation
        $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiereCode, $selectedEvaluation) {
          return $note->CODEMAT === $matiereCode && isset($note->$selectedEvaluation);
        });
        
        // Extraire les notes pour cette matière spécifique
        $matiereScores = array_column($matiereNotes, $selectedEvaluation);
        
        // Compter les notes dans chaque intervalle
        foreach ($intervals as $interval) {
          $count = count(array_filter($matiereScores, function ($score) use ($interval) {
            return $score >= $interval['min'] && $score < $interval['max'];
          }));
          $moyenneCounts[$matiereCode][] = $count;
        }
      }
      
      
      return view('pages.notes.filtertablenotes', compact('selectedPeriod','selectedClass','classe','matieres','eleves','notes','selectedEvaluation','moyennes','moyenneCounts', 'intervals'));
      
    }

    /**

    * Méthode appelée lors du clic sur "Calculer moyennes"
    */

    // Dans votre contrôleur, ajoutez cette méthode privée :
private function determineAppreciation($moyenne, $params2)
{
    if ($moyenne < $params2->Borne1) {
        return $params2->Mention1d;
    } elseif ($moyenne <= $params2->Borne2) {
        return $params2->Mention2d;
    } elseif ($moyenne <= $params2->Borne3) {
        return $params2->Mention3d;
    } elseif ($moyenne <= $params2->Borne4) {
        return $params2->Mention4d;
    } elseif ($moyenne <= $params2->Borne5) {
        return $params2->Mention5d;
    } elseif ($moyenne <= $params2->Borne6) {
        return $params2->Mention6d;
    } elseif ($moyenne <= $params2->Borne7) {
        return $params2->Mention7d;
    } else {
        return $params2->Mention8d;
    }
}


   public function calculermoyenne(Request $request)
{
    // Récupérer la classe sélectionnée
    $codeClasse = $request->input('classe');
    if (!$codeClasse) {
        return redirect()->back()->with('error', 'Veuillez sélectionner une classe.');
    }

    // Récupérer le paramétrage de l'année
    $params2 = Params2::first();
    $typean = $params2->TYPEAN; // 1 : semestres (2 périodes), 2 : trimestres (3 périodes)
    $periods = ($typean == 1) ? [1, 2] : [1, 2, 3];

    // Récupérer tous les élèves de la classe sélectionnée
    $eleves = Eleve::where('CODECLAS', $codeClasse)->get();

    $resultats = [];
    $annualAverages = [];      // [matricule => moyenneAnnuelle]
    $periodAverages = [];      // [periode => [matricule => moyennePourLaPeriode]]

    // Parcourir chaque élève
    foreach ($eleves as $eleve) {
        // Tableau pour stocker les infos par période pour cet élève
        $studentPeriods = [];

        foreach ($periods as $periode) {
            // Récupérer les enregistrements de notes de l'élève pour cette période
            $notes = Notes::where('MATRICULE', $eleve->MATRICULE)
                ->where('CODECLAS', $eleve->CODECLAS)
                ->where('SEMESTRE', $periode)
                ->get();

            // Accumulateurs pour le calcul de la moyenne de la période (pondérée)
            $totalNote = 0;
            $totalCoef = 0;

            // Accumulateurs pour le calcul des bilans (arithmétiques)
            $totalNoteLitteraire = 0;
            $countNoteLitteraire = 0;
            $totalNoteScientifique = 0;
            $countNoteScientifique = 0;
            $totalNoteFondamentale = 0;
            $countNoteFondamentale = 0;

            foreach ($notes as $note) {
                // Exclure les valeurs indésirables
                if ($note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                    $totalNote += $note->MS * $note->COEF;
                    $totalCoef += $note->COEF;
                }

                // Récupérer la matière pour déterminer son type
                $matiere = Matieres::where('CODEMAT', $note->CODEMAT)->first();
                if ($matiere) {
                    // Matière littéraire : TYPEMAT == 1
                    if ($matiere->TYPEMAT == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                        $totalNoteLitteraire += $note->MS;
                        $countNoteLitteraire++;
                    }
                    // Matière scientifique : TYPEMAT == 2
                    if ($matiere->TYPEMAT == 2 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                        $totalNoteScientifique += $note->MS;
                        $countNoteScientifique++;
                    }
                }

                // Pour la matière fondamentale, on vérifie dans la table Clasmat
                $classmat = Clasmat::where('CODECLAS', $eleve->CODECLAS)
                    ->where('CODEMAT', $note->CODEMAT)
                    ->first();
                if ($classmat && $classmat->FONDAMENTALE == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                    $totalNoteFondamentale += $note->MS;
                    $countNoteFondamentale++;
                }
            } // fin foreach notes

            // Calcul de la moyenne de la période (pondérée)
            $moyennePeriode = ($totalCoef > 0) ? round($totalNote / $totalCoef, 2) : null;
            // Appréciation pour la période
            $appreciationPeriode = ($moyennePeriode !== null) ? $this->determineAppreciation($moyennePeriode, $params2) : null;

            // Calcul des bilans arithmétiques pour la période
            $moyenneLitteraire = ($countNoteLitteraire > 0) ? round($totalNoteLitteraire / $countNoteLitteraire, 2) : null;
            $moyenneScientifique = ($countNoteScientifique > 0) ? round($totalNoteScientifique / $countNoteScientifique, 2) : null;
            $moyenneFondamentale = ($countNoteFondamentale > 0) ? round($totalNoteFondamentale / $countNoteFondamentale, 2) : null;

            // Vous pouvez également ajouter une appréciation pour chaque bilan si nécessaire, par exemple :
            $appreciationLitteraire = ($moyenneLitteraire !== null) ? $this->determineAppreciation($moyenneLitteraire, $params2) : null;
            $appreciationScientifique = ($moyenneScientifique !== null) ? $this->determineAppreciation($moyenneScientifique, $params2) : null;
            $appreciationFondamentale = ($moyenneFondamentale !== null) ? $this->determineAppreciation($moyenneFondamentale, $params2) : null;

            // Sauvegarder les infos pour cette période
            $studentPeriods[$periode] = [
                'moyenne'             => $moyennePeriode,
                'appreciation'        => $appreciationPeriode,
                'totalNotesCoeff'     => $totalNote,
                'totalCoef'           => $totalCoef,
                'bilan_litteraire'    => $moyenneLitteraire,
                'appreciation_litteraire' => $appreciationLitteraire,
                'bilan_scientifique'  => $moyenneScientifique,
                'appreciation_scientifique' => $appreciationScientifique,
                'bilan_fondamentale'  => $moyenneFondamentale,
                'appreciation_fondamentale' => $appreciationFondamentale,
            ];
        } // fin foreach périodes

        // Calcul de la moyenne annuelle (moyenne arithmétique des moyennes de période)
        $sumPeriodAverages = 0;
        $countPeriodAverages = 0;
        foreach ($studentPeriods as $periode => $data) {
            if ($data['moyenne'] !== null) {
                $sumPeriodAverages += $data['moyenne'];
                $countPeriodAverages++;
            }
        }
        $moyenneAnnuelle = ($countPeriodAverages > 0) ? round($sumPeriodAverages / $countPeriodAverages, 2) : null;
        $appreciationAnnuelle = ($moyenneAnnuelle !== null) ? $this->determineAppreciation($moyenneAnnuelle, $params2) : null;

        // Stocker pour le calcul des classements
        $annualAverages[$eleve->MATRICULE] = $moyenneAnnuelle;
        foreach ($studentPeriods as $periode => $data) {
            $periodAverages[$periode][$eleve->MATRICULE] = $data['moyenne'];
        }

        // Stocker les résultats de l'élève, y compris les bilans annuels et appréciations
        $resultats[] = [
            'eleve'                   => $eleve,
            'moyenneAnnuelle'         => $moyenneAnnuelle,
            'appreciationAnnuelle'    => $appreciationAnnuelle,
            // 'bilanLitteraireAnnuel'   => $studentPeriods, // Vous pouvez calculer un bilan annuel de façon similaire si besoin
            'periods'                 => $studentPeriods,
        ];
    } // fin foreach élèves

    // Calcul des classements (annual et par période)
    $annualRankings = $this->computeRankings($annualAverages);
    $periodRankings = [];
    foreach ($periods as $periode) {
        $periodRankings[$periode] = $this->computeRankings($periodAverages[$periode] ?? []);
    }

    foreach ($resultats as &$res) {
        $matricule = $res['eleve']->MATRICULE;
        $res['rangAnnuel'] = $annualRankings[$matricule] ?? null;
        foreach ($periods as $periode) {
            $res['periods'][$periode]['rang'] = $periodRankings[$periode][$matricule] ?? null;
        }
    }

  // Après le calcul des moyennes et des classements
  foreach ($resultats as $res) {
    $matricule = $res['eleve']->MATRICULE;
    $moyenneAnnuelle = $res['moyenneAnnuelle'];
    $rangAnnuel = $res['rangAnnuel'];
    $appan = $res['appreciationAnnuelle'];

    // Vérifier si la moyenne annuelle n'est pas null
    if ($moyenneAnnuelle !== null) {
        // Données à mettre à jour
        $data = [
            'MAN'  => $moyenneAnnuelle,
            'RANGA' => $rangAnnuel,
            'appan' => $appan,
        ];

        // Parcourir les périodes et ajouter les valeurs non nulles
        foreach ($periods as $periode) {
          $periodeData = $res['periods'][$periode] ?? null;
          if ($periodeData && $periodeData['moyenne'] !== null) {
              $data['MS' . $periode] = $periodeData['moyenne'];
              $data['RANG' . $periode] = $periodeData['rang'];
              $data['MBILANL' . $periode] = $periodeData['bilan_litteraire'];
              $data['MBILANS' . $periode] = $periodeData['bilan_scientifique'];
              $data['MoyMatFond' . $periode] = $periodeData['bilan_fondamentale'];
              $data['TotalGene' . $periode] = $periodeData['totalNotesCoeff'];
              $data['TotalCoef' . $periode] = $periodeData['totalCoef'];
              $data['app' . $periode] = $periodeData['appreciation'];
          }
        }

        // Si TYPEAN == 1 (semestres), forcer les valeurs de la période 3
        if ($typean == 1) {
          $data['MS3'] = -1;
          $data['MBILANL3'] = -1;
          $data['MBILANS3'] = -1;
          $data['MoyMatFond3'] = -1;
          $data['RANG3'] = 0;
          $data['TotalGene3'] = 0;
          $data['TotalCoef3'] = 0;
          $data['app3'] = "";
          // On ne met rien pour 'app3'
        }

        // Mettre à jour l'élève dans la base de données
        Eleve::where('MATRICULE', $matricule)->update($data);
    }
  }


    unset($res);

    // Calcul des indicateurs de la classe à partir des élèves de la classe sélectionnée
    $classesUnique = $eleves->pluck('CODECLAS')->unique();

    foreach ($classesUnique as $codeClasse) {
        // Filtrer les élèves appartenant à la classe courante
        $elevesClasse = $eleves->where('CODECLAS', $codeClasse);
        
        // Récupérer les moyennes par période en filtrant les valeurs null, -1 ou <= 0
        $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function($value) {
            return $value !== null && $value !== -1 && $value > 0;
        })->toArray();

        $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function($value) {
            return $value !== null && $value !== -1 && $value > 0;
        })->toArray();

        // Pour la période 3, on calcule seulement si TYPEAN n'est pas 1 (donc pour trimestres)
        if ($typean != 1) {
            $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function($value) {
                return $value !== null && $value !== -1 && $value > 0;
            })->toArray();
        } else {
            $moyennesP3 = []; // pour typean == 1, on force un tableau vide
        }
        
        // Calculer la plus forte et la plus faible moyenne pour chaque période
        $plusGrandeMoyenneP1enr = !empty($moyennesP1) ? max($moyennesP1) : 0;
        $plusFaibleMoyenneP1enr = !empty($moyennesP1) ? min($moyennesP1) : 0;
        
        $plusGrandeMoyenneP2enr = !empty($moyennesP2) ? max($moyennesP2) : 0;
        $plusFaibleMoyenneP2enr = !empty($moyennesP2) ? min($moyennesP2) : 0;
        
        if ($typean != 1) {
            $plusGrandeMoyenneP3enr = !empty($moyennesP3) ? max($moyennesP3) : 0;
            $plusFaibleMoyenneP3enr = !empty($moyennesP3) ? min($moyennesP3) : 0;
        } else {
            // Pour semestres (TYPEAN = 1), on force la période 3 à 0
            $plusGrandeMoyenneP3enr = 0;
            $plusFaibleMoyenneP3enr = 0;
        }
        
        // Calculer la moyenne de la classe pour chaque période
        $moyenneClasseP1enr = count($moyennesP1) > 0 ? array_sum($moyennesP1) / count($moyennesP1) : 0;
        $moyenneClasseP2enr = count($moyennesP2) > 0 ? array_sum($moyennesP2) / count($moyennesP2) : 0;
        if ($typean != 1) {
            $moyenneClasseP3enr = count($moyennesP3) > 0 ? array_sum($moyennesP3) / count($moyennesP3) : 0;
        } else {
            $moyenneClasseP3enr = 0;
        }
        
        // Calculer la moyenne globale de la classe (moyenne arithmétique des moyennes de chaque période)
        $totalMoyennes = 0;
        $nbPeriodes = 0;
        foreach ([$moyenneClasseP1enr, $moyenneClasseP2enr, $moyenneClasseP3enr] as $moy) {
            if ($moy !== null) {
                $totalMoyennes += $moy;
                $nbPeriodes++;
            }
        }
        $moyenneClasseGlobaleenr = ($nbPeriodes > 0) ? $totalMoyennes / $nbPeriodes : 0;
        
        // Mettre à jour la classe correspondante dans la table 'classe'
        $classe = Classes::where('CODECLAS', $codeClasse)->first();
        if ($classe) {
            $classe->MFaIBLE1 = $plusFaibleMoyenneP1enr;
            $classe->MFORTE1  = $plusGrandeMoyenneP1enr;
            $classe->MFaIBLE2 = $plusFaibleMoyenneP2enr;
            $classe->MFORTE2  = $plusGrandeMoyenneP2enr;
            $classe->MFaIBLE3 = $plusFaibleMoyenneP3enr;
            $classe->MFORTE3  = $plusGrandeMoyenneP3enr;
            $classe->MCLASSE1 = $moyenneClasseP1enr;
            $classe->MCLASSE2 = $moyenneClasseP2enr;
            $classe->MCLASSE3 = $moyenneClasseP3enr;
            $classe->MCLASSE  = $moyenneClasseGlobaleenr;
            $classe->save();
        }
    }

    // dd($resultats);

    // Vous pouvez renvoyer la vue avec le tableau $resultats
            return back()->with('success', 'Tous les calcules sont mis à jour avec succes pour chaque eleve de cette classe.');
  }



   /**
    * Fonction auxiliaire pour calculer les classements à partir d'un tableau associatif
    * (clé : identifiant de l'élève, valeur : moyenne)
    */
   private function computeRankings(array $averages)
   {
        // On filtre les valeurs nulles et celles égales à 21
        $filtered = array_filter($averages, function ($val) {
          return $val !== null && $val != 21;
        });
       // Tri décroissant : la meilleure moyenne en tête
       arsort($filtered);
       $rankings = [];
       $rank = 1;
       $prevValue = null;
       $counter = 0;
       foreach ($filtered as $matricule => $value) {
           $counter++;
           if ($prevValue !== null && $value < $prevValue) {
               $rank = $counter;
           }
           $rankings[$matricule] = $rank;
           $prevValue = $value;
       }
       return $rankings;
  }
    
    // public function calculermoyenne(Request $request)
    // {
      
    //         return back()->with('success', 'Tous les calcules sont mis à jour avec succes pour chaque semestre ,chaque classe et chaque éleve.');
            
    //       }
          
          
        } 