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
use App\Models\Paramcontrat;
use App\Models\PeriodeSave;

use Illuminate\Support\Facades\Schema;


use Illuminate\Support\Facades\DB;


class EditionController extends Controller
{
  public function editions()
  {

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

    return view('pages.inscriptions.editions', compact('eleves', 'resultats', 'totalDues', 'totalPayes', 'totalRestes'));
  }

  public function arriereconstate(Request $request)
  {
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
      $resultats->map(function ($resultat) {
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
  public function journaldetailleaveccomposante(Request $request)
  {
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

  public function journaldetaillesanscomposante(Request $request)
  {
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
    return view('pages.inscriptions.journaldetaillesanscomposante', compact('recouvrements', 'libelle', 'enseign'));
  }

  public function tabledesmatieres()
  {
    $matiere = Matieres::all();
    $lastMatiere = Matieres::orderBy('CODEMAT', 'desc')->first();
    $nextCodeMat = $lastMatiere ? $lastMatiere->CODEMAT + 1 : 1; // Si aucune matière, commencer à 1
    return view('pages.notes.tabledesmatieres', compact('matiere',  'nextCodeMat',  'lastMatiere'));
  }
  public function filtrereleveparmatiere(Request $request)
  {
    $classe = $request->classe;
    $matiere = $request->matiere;
    // dd($classe);
    $notes = Notes::join('eleve', 'notes.MATRICULE', '=', 'eleve.MATRICULE')
      ->select('notes.MATRICULE', 'eleve.MATRICULEX', 'eleve.nom', 'eleve.prenom', 'notes.INT1', 'notes.INT2', 'notes.INT3', 'notes.INT4', 'notes.MI', 'notes.DEV1', 'notes.DEV2', 'notes.DEV3')
      ->when($classe, function ($q) use ($classe) {
        return $q->where('notes.CODECLAS', $classe);
      })
      ->when($matiere, function ($q) use ($matiere) {
        return $q->where('notes.CODEMAT', $matiere);
      })
      ->get();
    $matiere = Matieres::where('CODEMAT', $matiere)->first();
    $matiere = $matiere['LIBELMAT'];
    return view('pages.notes.filtrereleveparmatiere', compact('notes', 'classe', 'matiere'));
  }
  public function storetabledesmatieres(Request $request)
  {
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
  public function updatetabledesmatieres(Request $request)
  {
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

  public function elevessansnote($classCode)
  {
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
  public function relevesparmatiere()
  {
    $classe = Classes::all();
    $matieres = Matieres::all();
    return view('pages.notes.relevesparmatiere', compact('classe', 'matieres'));
  }
  public function tableaudenotes()
  {
    $classe = Classes::all();
    $matieres = Matieres::all();
    return view('pages.notes.tableaudenotes');
  }
  public function filtertableaunotes(Request $request)
  {
    $intervals = [
      ['min' => $request->input('interval1'), 'max' => $request->input('interval2')],
      ['min' => $request->input('interval3'), 'max' => $request->input('interval4')],
      ['min' => $request->input('interval5'), 'max' => $request->input('interval6')],
      ['min' => $request->input('interval7'), 'max' => $request->input('interval8')],
      ['min' => $request->input('interval9'), 'max' => $request->input('interval10')],
    ];
    session(['intervals' => $intervals]);

    $classe = Classe::all();
    $current = PeriodeSave::where('key', 'active')->value('periode');
    // Récupérer la classe, la période et le type d'évaluation sélectionnés
    $selectedClass = $request->input('classe', 'CE1A');
    $selectedPeriod = $request->input('periode', 1);
    $selectedEvaluation = $request->input('note', 'DEV1'); // par exemple "Dev1"

    // Récupérer les matières de la classe sélectionnée
    $matieres = DB::table('clasmat')
      ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
      ->where('clasmat.CODECLAS', $selectedClass)
      ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
      ->get();

    $eleves = DB::table('eleve')
      ->where('CODECLAS', $selectedClass)
      ->select('MATRICULE', 'MATRICULEX', 'NOM', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
      ->orderBy('NOM')->get();;

    // dd($eleves);


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

    return view('pages.notes.filtertableaunotes', compact('selectedPeriod', 'selectedClass', 'classe', 'matieres', 'eleves', 'notes', 'selectedEvaluation', 'moyennes', 'moyenneCounts', 'intervals', 'current'));
  }
  public function filtertablenotes(Request $request)
  {
    $infoparamcontrat = Paramcontrat::first();
    $anneencours = $infoparamcontrat->anneencours_paramcontrat;
    $annesuivante = $anneencours + 1;
    $annescolaire = $anneencours . '-' . $annesuivante;
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
    // dd($selectedPeriod);
    $params2 = Params2::first();
    $typean = $params2->TYPEAN;

    // Récupérer les matières de la classe sélectionnée
    $matieres = DB::table('clasmat')
      ->join('matieres', 'clasmat.CODEMAT', '=', 'matieres.CODEMAT')
      ->where('clasmat.CODECLAS', $selectedClass)
      ->select('matieres.CODEMAT', 'matieres.NOMCOURT', 'clasmat.COEF')
      ->get();

    $eleves = DB::table('eleve')
      ->where('CODECLAS', $selectedClass)
      ->select('MATRICULE', 'MATRICULEX', 'NOM', 'MAN', 'PRENOM', 'MS' . $selectedPeriod . ' AS MSEM', 'RANG' . $selectedPeriod . ' AS RANG')
      ->orderBy('NOM')->get();;


    // $notes = DB::table('notes')
    // ->where('CODECLAS', $selectedClass)
    // ->where('SEMESTRE', $selectedPeriod)
    // ->select('MATRICULE', 'CODEMAT', $selectedEvaluation)
    // ->get()
    // ->keyBy(function ($note) {
    //   return $note->MATRICULE . '-' . $note->CODEMAT;
    // });

    $columns = ['MATRICULE', 'CODEMAT', 'DEV1', 'DEV2', 'DEV3'];
    // Si l'évaluation sélectionnée n'est pas un devoir, on la rajoute
    if (!in_array($selectedEvaluation, ['DEV1', 'DEV2', 'DEV3'])) {
      $columns[] = $selectedEvaluation;
    }

    $notes = DB::table('notes')
      ->where('CODECLAS', $selectedClass)
      ->where('SEMESTRE', $selectedPeriod)
      ->select($columns)
      ->get()
      ->keyBy(function ($note) {
        return $note->MATRICULE . '-' . $note->CODEMAT;
      });


    // Filtrer pour obtenir uniquement les notes M.SEM valides
    $validMSEM = $eleves->pluck('MSEM')->filter(function ($value) {
      return $value != -1 && $value != 21;
    });





    $moyennes = [];
    foreach ($matieres as $matiere) {
      $matiereNotes = array_filter($notes->toArray(), function ($note) use ($matiere, $selectedEvaluation) {
        return $note->CODEMAT === $matiere->CODEMAT && isset($note->$selectedEvaluation);
      });
      $matiereScores = array_column($matiereNotes, $selectedEvaluation);

      // Filtrer pour exclure les scores égaux à 21
      $filteredScores = array_filter($matiereScores, function ($score) {
        return $score != 21;
      });

      $moyennes[$matiere->CODEMAT] = [
        'min' => count($filteredScores) > 0 ? min($filteredScores) : 0,
        'max' => count($filteredScores) > 0 ? max($filteredScores) : 0,
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

    // Calculer les agrégats pour M.SEM
    $moyennesMSEM = [
      'min' => $validMSEM->isNotEmpty() ? $validMSEM->min() : 0,
      'max' => $validMSEM->isNotEmpty() ? $validMSEM->max() : 0,
    ];



    // (Optionnel) Calculer le nombre de M.SEM dans chaque intervalle si vous souhaitez faire le même affichage que pour les matières
    $moyenneCountsMSEM = [];
    foreach ($intervals as $index => $interval) {
      $moyenneCountsMSEM[$index] = $validMSEM->filter(function ($score) use ($interval) {
        return $score >= $interval['min'] && $score < $interval['max'];
      })->count();
    }

    // Initialisation par défaut pour éviter l'erreur
    $moyennesMAN = [];
    $moyenneCountsMAN = [];

    // Calcul pour M.AN s'il est affiché
    if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3)) {
      $validMAN = $eleves->pluck('MAN')->filter(function ($value) {
        return $value != -1 && $value != 21;
      });
      $moyennesMAN = [
        'min' => $validMAN->isNotEmpty() ? $validMAN->min() : 0,
        'max' => $validMAN->isNotEmpty() ? $validMAN->max() : 0,
      ];

      // Calcul des intervalles pour M.AN (si nécessaire)
      $moyenneCountsMAN = [];
      foreach ($intervals as $index => $interval) {
        $moyenneCountsMAN[$index] = $validMAN->filter(function ($score) use ($interval) {
          return $score >= $interval['min'] && $score < $interval['max'];
        })->count();
      }
    }

    return view('pages.notes.filtertablenotes', compact('typean', 'selectedPeriod', 'selectedClass', 'classe', 'matieres', 'eleves', 'notes', 'selectedEvaluation', 'moyennes', 'moyenneCounts', 'intervals', 'moyennesMSEM', 'moyennesMAN', 'moyenneCountsMSEM', 'moyenneCountsMAN', 'annescolaire'));
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


  // public function calculermoyenne(Request $request)
  // {

  //   // Récupérer le paramétrage de l'année
  //   $params2 = Params2::first();
  //   $typean = $params2->TYPEAN; // 1 : semestres (2 périodes), 2 : trimestres (3 périodes)
  //   $periods = ($typean == 1) ? [1, 2] : [1, 2, 3];

  //   // Récupérer la période (on suppose qu'elle est passée dans le request)
  //   // $periode = $request->input('periode');
  //   // if (!$periode) {
  //   //     return redirect()->back()->with('error', 'Veuillez sélectionner une période.');
  //   // }

  //   //  dd($periods);


  //   // Récupérer toutes les classes distinctes présentes dans la table "eleve"
  //   $classes = DB::table('eleve')->select('CODECLAS')->distinct()->get();

  //   foreach ($classes as $classeObj) {
  //     $codeClasse = $classeObj->CODECLAS;

  //     // Récupérer tous les élèves de cette classe
  //     $eleves = Eleve::where('CODECLAS', $codeClasse)->get();

  //     $resultats = [];
  //     $annualAverages = [];   // [matricule => moyenneAnnuelle]
  //     $periodAverages = [];   // [periode => [matricule => moyennePourLaPeriode]]

  //     // Parcourir chaque élève de la classe
  //     foreach ($eleves as $eleve) {
  //       // Tableau pour stocker les infos par période pour cet élève
  //       $studentPeriods = [];

  //       foreach ($periods as $periodeCourante) {
  //         // Récupérer les enregistrements de notes de l'élève pour cette période
  //         $notes = Notes::where('MATRICULE', $eleve->MATRICULE)
  //           ->where('CODECLAS', $eleve->CODECLAS)
  //           ->where('SEMESTRE', $periodeCourante)
  //           ->get();

  //         // Initialiser les accumulateurs
  //         $totalNote = 0;
  //         $totalCoef = 0;
  //         $totalNoteLitteraire = 0;
  //         $countNoteLitteraire = 0;
  //         $totalNoteScientifique = 0;
  //         $countNoteScientifique = 0;
  //         $totalNoteFondamentale = 0;
  //         $countNoteFondamentale = 0;

  //         foreach ($notes as $note) {
  //           // Exclure les valeurs indésirables
  //           if ($note->MS !== null && $note->MS != -1 && $note->MS != 21) {
  //             $totalNote += $note->MS * $note->COEF;
  //             $totalCoef += $note->COEF;
  //           }

  //           // Récupérer la matière correspondante
  //           $matiere = Matieres::where('CODEMAT', $note->CODEMAT)->first();
  //           if ($matiere) {
  //             // Matière littéraire : TYPEMAT == 1
  //             if ($matiere->TYPEMAT == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
  //               $totalNoteLitteraire += $note->MS;
  //               $countNoteLitteraire++;
  //             }
  //             // Matière scientifique : TYPEMAT == 2
  //             if ($matiere->TYPEMAT == 2 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
  //               $totalNoteScientifique += $note->MS;
  //               $countNoteScientifique++;
  //             }
  //           }

  //           // Matière fondamentale (vérification dans Clasmat)
  //           $classmat = Clasmat::where('CODECLAS', $eleve->CODECLAS)
  //             ->where('CODEMAT', $note->CODEMAT)
  //             ->first();
  //           if ($classmat && $classmat->FONDAMENTALE == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
  //             $totalNoteFondamentale += $note->MS;
  //             $countNoteFondamentale++;
  //           }
  //         } // fin foreach notes

  //         // Calcul de la moyenne de la période (pondérée)
  //         $moyennePeriode = ($totalCoef > 0) ? round($totalNote / $totalCoef, 2) : null;
  //         // Appréciation pour la période
  //         $appreciationPeriode = ($moyennePeriode !== null) ? $this->determineAppreciation($moyennePeriode, $params2) : null;
  //         // Calcul des bilans arithmétiques
  //         $moyenneLitteraire = ($countNoteLitteraire > 0) ? round($totalNoteLitteraire / $countNoteLitteraire, 2) : null;
  //         $moyenneScientifique = ($countNoteScientifique > 0) ? round($totalNoteScientifique / $countNoteScientifique, 2) : null;
  //         $moyenneFondamentale = ($countNoteFondamentale > 0) ? round($totalNoteFondamentale / $countNoteFondamentale, 2) : null;
  //         $appreciationLitteraire = ($moyenneLitteraire !== null) ? $this->determineAppreciation($moyenneLitteraire, $params2) : null;
  //         $appreciationScientifique = ($moyenneScientifique !== null) ? $this->determineAppreciation($moyenneScientifique, $params2) : null;
  //         $appreciationFondamentale = ($moyenneFondamentale !== null) ? $this->determineAppreciation($moyenneFondamentale, $params2) : null;

  //         $studentPeriods[$periodeCourante] = [
  //           'moyenne'             => $moyennePeriode,
  //           'appreciation'        => $appreciationPeriode,
  //           'totalNotesCoeff'     => $totalNote,
  //           'totalCoef'           => $totalCoef,
  //           'bilan_litteraire'    => $moyenneLitteraire,
  //           'appreciation_litteraire' => $appreciationLitteraire,
  //           'bilan_scientifique'  => $moyenneScientifique,
  //           'appreciation_scientifique' => $appreciationScientifique,
  //           'bilan_fondamentale'  => $moyenneFondamentale,
  //           'appreciation_fondamentale' => $appreciationFondamentale,
  //         ];
  //       } // fin foreach périodes

  //      // Calcul de la moyenne annuelle (moyenne arithmétique des moyennes de période)
  //       $sumPeriodAverages = 0;
  //       $countPeriodAverages = 0;
  //       // dd($studentPeriods);
  //       foreach ($studentPeriods as $periodeCourante => $data) {
  //         if ($data['moyenne'] !== null) {
  //           $sumPeriodAverages += $data['moyenne'];
  //           $countPeriodAverages++;
  //         }
  //       }
  //       // Récupération des moyennes des périodes 1 et 2
  //       $moyenneP1 = isset($studentPeriods[1]['moyenne']) ? $studentPeriods[1]['moyenne'] : null;
  //       $moyenneP2 = isset($studentPeriods[2]['moyenne']) ? $studentPeriods[2]['moyenne'] : null;

  //       // Calcul de la moyenne annuelle pondérée
  //       if ($moyenneP1 !== null && $moyenneP2 !== null) {
  //           $moyenneAnnuelle = round((2 * $moyenneP2 + $moyenneP1) / 3, 2);
  //       } else {
  //           $moyenneAnnuelle = null;
  //       }

  //       $appreciationAnnuelle = ($moyenneAnnuelle !== null) ? $this->determineAppreciation($moyenneAnnuelle, $params2) : null;

  //       // Sauvegarde des données
  //       $annualAverages[$eleve->MATRICULE] = $moyenneAnnuelle;

  //       foreach ($studentPeriods as $periodeCourante => $data) {
  //           $periodAverages[$periodeCourante][$eleve->MATRICULE] = $data['moyenne'];
  //       }

  //       $resultats[] = [
  //           'eleve'                => $eleve,
  //           'moyenneAnnuelle'      => $moyenneAnnuelle,
  //           'appreciationAnnuelle' => $appreciationAnnuelle,
  //           'periods'              => $studentPeriods,
  //       ];
  //     } // fin foreach élèves

  //     // Calcul des classements (annuel et par période)
  //     $annualRankings = $this->computeRankings($annualAverages);
  //     $periodRankings = [];
  //     foreach ($periods as $periodeCourante) {
  //       $periodRankings[$periodeCourante] = $this->computeRankings($periodAverages[$periodeCourante] ?? []);
  //     }
  //     foreach ($resultats as &$res) {
  //       $matricule = $res['eleve']->MATRICULE;
  //       $res['rangAnnuel'] = $annualRankings[$matricule] ?? null;
  //       foreach ($periods as $periodeCourante) {
  //         $res['periods'][$periodeCourante]['rang'] = $periodRankings[$periodeCourante][$matricule] ?? null;
  //       }
  //     }
  //     unset($res);

  //     // Mise à jour des enregistrements des élèves
  //     foreach ($resultats as $res) {
  //       $matricule = $res['eleve']->MATRICULE;
  //       $moyenneAnnuelle = $res['moyenneAnnuelle'];
  //       $rangAnnuel = $res['rangAnnuel'];
  //       $appan = $res['appreciationAnnuelle'];
  //       if ($moyenneAnnuelle !== null) {
  //         $data = [
  //           'MAN'   => $moyenneAnnuelle,
  //           'RANGA' => $rangAnnuel,
  //           'appan' => $appan,
  //         ];
  //         foreach ($periods as $periodeCourante) {
  //           $periodeData = $res['periods'][$periodeCourante] ?? null;
  //           if ($periodeData && $periodeData['moyenne'] !== null) {
  //             $data['MS' . $periodeCourante] = $periodeData['moyenne'];
  //             $data['RANG' . $periodeCourante] = $periodeData['rang'];
  //             $data['MBILANL' . $periodeCourante] = $periodeData['bilan_litteraire'];
  //             $data['MBILANS' . $periodeCourante] = $periodeData['bilan_scientifique'];
  //             $data['MoyMatFond' . $periodeCourante] = $periodeData['bilan_fondamentale'];
  //             $data['TotalGene' . $periodeCourante] = $periodeData['totalNotesCoeff'];
  //             $data['TotalCoef' . $periodeCourante] = $periodeData['totalCoef'];
  //             $data['app' . $periodeCourante] = $periodeData['appreciation'];
  //           }
  //           // dd($periodeData['moyenne']);
  //         }
  //         if ($typean == 1) {
  //           $data['MS3'] = -1;
  //           $data['MBILANL3'] = -1;
  //           $data['MBILANS3'] = -1;
  //           $data['MoyMatFond3'] = -1;
  //           $data['RANG3'] = 0;
  //           $data['TotalGene3'] = 0;
  //           $data['TotalCoef3'] = 0;
  //           $data['app3'] = "";
  //         }

  //         Eleve::where('MATRICULE', $matricule)->update($data);
  //       }
  //     }
  //     unset($res);

  //     // Mise à jour des indicateurs de la classe
  //     $classesUnique = $eleves->pluck('CODECLAS')->unique();
  //     foreach ($classesUnique as $codeClasseInner) {
  //       $elevesClasse = $eleves->where('CODECLAS', $codeClasseInner);
  //       $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function ($value) {
  //         return $value !== null && $value !== -1 && $value > 0;
  //       })->toArray();
  //       $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function ($value) {
  //         return $value !== null && $value !== -1 && $value > 0;
  //       })->toArray();
  //       if ($typean != 1) {
  //         $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function ($value) {
  //           return $value !== null && $value !== -1 && $value > 0;
  //         })->toArray();
  //       } else {
  //         $moyennesP3 = [];
  //       }
  //       $plusGrandeMoyenneP1enr = !empty($moyennesP1) ? max($moyennesP1) : 0;
  //       $plusFaibleMoyenneP1enr = !empty($moyennesP1) ? min($moyennesP1) : 0;
  //       $plusGrandeMoyenneP2enr = !empty($moyennesP2) ? max($moyennesP2) : 0;
  //       $plusFaibleMoyenneP2enr = !empty($moyennesP2) ? min($moyennesP2) : 0;
  //       if ($typean != 1) {
  //         $plusGrandeMoyenneP3enr = !empty($moyennesP3) ? max($moyennesP3) : 0;
  //         $plusFaibleMoyenneP3enr = !empty($moyennesP3) ? min($moyennesP3) : 0;
  //       } else {
  //         $plusGrandeMoyenneP3enr = 0;
  //         $plusFaibleMoyenneP3enr = 0;
  //       }
  //       $moyenneClasseP1enr = count($moyennesP1) > 0 ? array_sum($moyennesP1) / count($moyennesP1) : 0;
  //       $moyenneClasseP2enr = count($moyennesP2) > 0 ? array_sum($moyennesP2) / count($moyennesP2) : 0;
  //       if ($typean != 1) {
  //         $moyenneClasseP3enr = count($moyennesP3) > 0 ? array_sum($moyennesP3) / count($moyennesP3) : 0;
  //       } else {
  //         $moyenneClasseP3enr = 0;
  //       }
  //       $totalMoyennes = 0;
  //       $nbPeriodes = 0;
  //       foreach ([$moyenneClasseP1enr, $moyenneClasseP2enr, $moyenneClasseP3enr] as $moy) {
  //         if ($moy !== null) {
  //           $totalMoyennes += $moy;
  //           $nbPeriodes++;
  //         }
  //       }
  //       $moyenneClasseGlobaleenr = ($nbPeriodes > 0) ? $totalMoyennes / $nbPeriodes : 0;

  //       $classeToUpdate = Classes::where('CODECLAS', $codeClasseInner)->first();
  //       if ($classeToUpdate) {
  //         $classeToUpdate->MFaIBLE1 = $plusFaibleMoyenneP1enr;
  //         $classeToUpdate->MFORTE1  = $plusGrandeMoyenneP1enr;
  //         $classeToUpdate->MFaIBLE2 = $plusFaibleMoyenneP2enr;
  //         $classeToUpdate->MFORTE2  = $plusGrandeMoyenneP2enr;
  //         $classeToUpdate->MFaIBLE3 = $plusFaibleMoyenneP3enr;
  //         $classeToUpdate->MFORTE3  = $plusGrandeMoyenneP3enr;
  //         $classeToUpdate->MCLASSE1 = $moyenneClasseP1enr;
  //         $classeToUpdate->MCLASSE2 = $moyenneClasseP2enr;
  //         $classeToUpdate->MCLASSE3 = $moyenneClasseP3enr;
  //         $classeToUpdate->MCLASSE  = $moyenneClasseGlobaleenr;
  //         $classeToUpdate->save();
  //       }
  //     }
  //   }
  //   return back()->with('success', 'Tous les calculs ont été mis à jour avec succès pour chaque élève de toutes les classes.');
  // }

  public function calculermoyenne(Request $request)
  {
    set_time_limit(0); 
      // Récupérer le paramétrage de l'année
      $params2  = Params2::first();
      $typean   = $params2->TYPEAN; // 1 : semestres (2 périodes), 2 : trimestres (3 périodes)
      $periods  = ($typean == 1) ? [1, 2] : [1, 2, 3];

      // Récupérer toutes les classes distinctes présentes dans la table "eleve"
      $classes = DB::table('eleve')
                  ->select('CODECLAS')
                  ->distinct()
                  ->get();

      foreach ($classes as $classeObj) {
          $codeClasse = $classeObj->CODECLAS;

          // Récupérer tous les élèves de cette classe
          $eleves = Eleve::where('CODECLAS', $codeClasse)->get();

          $resultats        = [];
          $annualAverages   = [];   // [matricule => moyenneAnnuelle]
          $periodAverages   = [];   // [periode => [matricule => moyennePourLaPeriode]]

          // Parcourir chaque élève de la classe
          foreach ($eleves as $eleve) {
              // Tableau pour stocker les infos par période pour cet élève
              $studentPeriods = [];

              foreach ($periods as $periodeCourante) {
                  // Récupérer les enregistrements de notes de l'élève pour cette période
                  $notes = Notes::where('MATRICULE', $eleve->MATRICULE)
                                ->where('CODECLAS', $eleve->CODECLAS)
                                ->where('SEMESTRE', $periodeCourante)
                                ->get();

                  // Initialiser les accumulateurs
                  $totalNote             = 0;
                  $totalCoef             = 0;
                  $totalNoteLitteraire   = 0;
                  $countNoteLitteraire   = 0;
                  $totalNoteScientifique = 0;
                  $countNoteScientifique = 0;
                  $totalNoteFondamentale = 0;
                  $countNoteFondamentale = 0;

                  // Nouveau compteur : nombre de matières académiques avec une note valide
                  $countValidSubjects = 0;
                  $totalNote          = 0;
                  $totalCoef          = 0;
                  // $countValidSubjects = 0;

                  foreach ($notes as $note) {
                      // 1) Exclure explicitement la Conduite (ou toute autre matière à ignorer)
                      // if ($note->CODEMAT == 21) {
                      //     continue;
                      // }

                      $hasValidDevoir = (
                            isset($note->DEV1) 
                            && $note->DEV1 !== null 
                            && $note->DEV1 != -1 
                            && $note->DEV1 != 21
                        ) || (
                            isset($note->DEV2) 
                            && $note->DEV2 !== null 
                            && $note->DEV2 != -1 
                            && $note->DEV2 != 21
                        );

                        // On ne prend la matière en compte que si MS est valide ET qu'il y a au moins un devoir valide
                        if (
                            $note->MS !== null
                            && $note->MS != -1
                            && $note->MS != 21
                            && $hasValidDevoir
                        ) {
                            $totalNote += $note->MS * $note->COEF;
                            $totalCoef += $note->COEF;
                            $countValidSubjects++;
                        }

                      // 2) Si MS est défini et différent de -1 et 21, on l'inclut
                      // if ($note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                      //     // a) Accumulation pour la moyenne pondérée
                      //     $totalNote += $note->MS * $note->COEF;
                      //     $totalCoef += $note->COEF;

                      //     // b) Incrémenter le compteur de matières valides
                      //     $countValidSubjects++;
                      // }

                      // 3) Calcul des bilans littéraire / scientifique
                      $matiere = Matieres::where('CODEMAT', $note->CODEMAT)->first();
                      if ($matiere) {
                          if ($matiere->TYPEMAT == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                              $totalNoteLitteraire += $note->MS;
                              $countNoteLitteraire++;
                          }
                          if ($matiere->TYPEMAT == 2 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                              $totalNoteScientifique += $note->MS;
                              $countNoteScientifique++;
                          }
                      }

                      // 4) Calcul du bilan fondamentale via Clasmat
                      $classmat = Clasmat::where('CODECLAS', $eleve->CODECLAS)
                                        ->where('CODEMAT', $note->CODEMAT)
                                        ->first();
                      if ($classmat && $classmat->FONDAMENTALE == 1 && $note->MS !== null && $note->MS != -1 && $note->MS != 21) {
                          $totalNoteFondamentale += $note->MS;
                          $countNoteFondamentale++;
                      }
                  } // fin foreach notes

                  // Calcul de la moyenne de la période (pondérée) 
                  // uniquement si au moins 4 matières académiques ont une note valide ET totalCoef > 0
                  if ($countValidSubjects >= 4 && $totalCoef > 0) {
                      $moyennePeriode = number_format($totalNote / $totalCoef, 2);
                  } else {
                      // Moins de 4 notes valides => pas de moyenne semestrielle
                      $moyennePeriode = 21;
                  }

                  // Appréciation pour la période
                  $appreciationPeriode = ($moyennePeriode !== null)
                      ? $this->determineAppreciation($moyennePeriode, $params2)
                      : null;

                  // Calcul des bilans arithmétiques
                  $moyenneLitteraire  = ($countNoteLitteraire > 0)
                      ? number_format($totalNoteLitteraire / $countNoteLitteraire, 2)
                      : null;
                  $moyenneScientifique = ($countNoteScientifique > 0)
                      ? number_format($totalNoteScientifique / $countNoteScientifique, 2)
                      : null;
                  $moyenneFondamentale = ($countNoteFondamentale > 0)
                      ? number_format($totalNoteFondamentale / $countNoteFondamentale, 2)
                      : null;

                  $appreciationLitteraire   = ($moyenneLitteraire !== null)
                      ? $this->determineAppreciation($moyenneLitteraire, $params2)
                      : null;
                  $appreciationScientifique = ($moyenneScientifique !== null)
                      ? $this->determineAppreciation($moyenneScientifique, $params2)
                      : null;
                  $appreciationFondamentale = ($moyenneFondamentale !== null)
                      ? $this->determineAppreciation($moyenneFondamentale, $params2)
                      : null;

                  $studentPeriods[$periodeCourante] = [
                      'moyenne'                   => $moyennePeriode,
                      'appreciation'              => $appreciationPeriode,
                      'totalNotesCoeff'           => $totalNote,
                      'totalCoef'                 => $totalCoef,
                      'bilan_litteraire'          => $moyenneLitteraire,
                      'appreciation_litteraire'   => $appreciationLitteraire,
                      'bilan_scientifique'        => $moyenneScientifique,
                      'appreciation_scientifique' => $appreciationScientifique,
                      'bilan_fondamentale'        => $moyenneFondamentale,
                      'appreciation_fondamentale' => $appreciationFondamentale,
                  ];
              } // fin foreach périodes

              // Calcul de la moyenne annuelle (moyenne arithmétique des moyennes de période)
              $sumPeriodAverages   = 0;
              $countPeriodAverages = 0;
              foreach ($studentPeriods as $periodeCourante => $data) {
                  if ($data['moyenne'] !== null && $data['moyenne'] !== 21) {
                      $sumPeriodAverages += $data['moyenne'];
                      $countPeriodAverages++;
                  }
              }

              // Récupération des moyennes des périodes 1 et 2
              $moyenneP1 = isset($studentPeriods[1]['moyenne']) && $studentPeriods[1]['moyenne'] !== 21
                  ? $studentPeriods[1]['moyenne']
                  : null;
              $moyenneP2 = isset($studentPeriods[2]['moyenne']) && $studentPeriods[2]['moyenne'] !== 21
                  ? $studentPeriods[2]['moyenne']
                  : null;
              // Si on est en trimestres, on récupère aussi la période 3
              if ($typean == 2) {
                  $moyenneP3 = isset($studentPeriods[3]['moyenne']) && $studentPeriods[3]['moyenne'] !== 21 ? $studentPeriods[3]['moyenne'] : null;
              } else {
                  $moyenneP3 = 21; // Pas utilisé pour typean == 1
              }

              // Calcul de la moyenne annuelle pondérée (pour les semestres uniquement)
              if ($typean == 1) {
                  // ——— Mode semestres (2 périodes) ———
                  if ($moyenneP1 !== null && $moyenneP1 !== 21 && $moyenneP2 !== null && $moyenneP2 !== 21) {
                      // Pondération 1/3 pour P1, 2/3 pour P2
                      $moyenneAnnuelle = number_format((2 * $moyenneP2 + $moyenneP1) / 3, 2);
                  // } elseif ($moyenneP1 !== null && $moyenneP1 !== 21) {
                  //     // Seule P1 existe
                  //     $moyenneAnnuelle = $moyenneP1;
                  } elseif ($moyenneP2 !== null && $moyenneP2 !== 21) {
                      // Seule P2 existe
                      $moyenneAnnuelle = $moyenneP2;
                  } else {
                      // Aucune période valide
                      $moyenneAnnuelle = 21;
                  }
              } else {
                  // ——— Mode trimestres (3 périodes) ———
                  // On ne retient QUE les moyennes non-null
                  $listeValides = [];
                  if ($moyenneP1 !== null && $moyenneP1 !== 21) {
                      $listeValides[] = $moyenneP1;
                  }
                  if ($moyenneP2 !== null && $moyenneP2 !== 21) {
                      $listeValides[] = $moyenneP2;
                  }
                  if ($moyenneP3 !== null && $moyenneP3 !== 21) {
                      $listeValides[] = $moyenneP3;
                  }

                  $countValides = count($listeValides);
                  if ($countValides > 0) {
                      // Si au moins une période valide, on fait la moyenne arithmétique des valeurs présentes
                      $somme = array_sum($listeValides);
                      $moyenneAnnuelle = number_format($somme / $countValides, 2);
                  } else {
                      // Aucune note sur les 3 périodes
                      $moyenneAnnuelle = 21;
                  }
              }


              $appreciationAnnuelle = ($moyenneAnnuelle !== null && $moyenneAnnuelle !== 21)
                  ? $this->determineAppreciation($moyenneAnnuelle, $params2)
                  : null;

              // Sauvegarde des données pour le classement
              $annualAverages[$eleve->MATRICULE] = $moyenneAnnuelle;
              foreach ($studentPeriods as $periodeCourante => $data) {
                  $periodAverages[$periodeCourante][$eleve->MATRICULE] = $data['moyenne'];
              }

              $resultats[] = [
                  'eleve'                => $eleve,
                  'moyenneAnnuelle'      => $moyenneAnnuelle,
                  'appreciationAnnuelle' => $appreciationAnnuelle,
                  'periods'              => $studentPeriods,
              ];
          } // fin foreach élèves

          // Calcul des classements (annuel et par période)
          $annualRankings = $this->computeRankings($annualAverages);
          $periodRankings = [];
          foreach ($periods as $periodeCourante) {
              $periodRankings[$periodeCourante] = $this->computeRankings($periodAverages[$periodeCourante] ?? []);
          }
          foreach ($resultats as &$res) {
              $matricule = $res['eleve']->MATRICULE;
              $res['rangAnnuel'] = $annualRankings[$matricule] ?? null;
              foreach ($periods as $periodeCourante) {
                  $res['periods'][$periodeCourante]['rang'] = $periodRankings[$periodeCourante][$matricule] ?? null;
              }
          }
          unset($res);

          // Mise à jour des enregistrements des élèves
          foreach ($resultats as $res) {
              $matricule      = $res['eleve']->MATRICULE;
              $moyenneAnnuelle = $res['moyenneAnnuelle'];
              $rangAnnuel      = $res['rangAnnuel'];
              $appan           = $res['appreciationAnnuelle'];

              if ($moyenneAnnuelle !== null) {
                  $data = [
                      'MAN'   => $moyenneAnnuelle,
                      'RANGA' => $rangAnnuel,
                      'appan' => $appan,
                  ];
                  foreach ($periods as $periodeCourante) {
                      $periodeData = $res['periods'][$periodeCourante] ?? null;
                      if ($periodeData && $periodeData['moyenne'] !== null) {
                          $data['MS' . $periodeCourante]        = $periodeData['moyenne'];
                          $data['RANG' . $periodeCourante]      = $periodeData['rang'];
                          $data['MBILANL' . $periodeCourante]   = $periodeData['bilan_litteraire'];
                          $data['MBILANS' . $periodeCourante]   = $periodeData['bilan_scientifique'];
                          $data['MoyMatFond' . $periodeCourante]= $periodeData['bilan_fondamentale'];
                          $data['TotalGene' . $periodeCourante] = $periodeData['totalNotesCoeff'];
                          $data['TotalCoef' . $periodeCourante] = $periodeData['totalCoef'];
                          $data['app' . $periodeCourante]       = $periodeData['appreciation'];
                      }
                  }
                  if ($typean == 1) {
                      $data['MS3']          = -1;
                      $data['MBILANL3']     = -1;
                      $data['MBILANS3']     = -1;
                      $data['MoyMatFond3']  = -1;
                      $data['RANG3']        = 0;
                      $data['TotalGene3']   = 0;
                      $data['TotalCoef3']   = 0;
                      $data['app3']         = "";
                  }

                  Eleve::where('MATRICULE', $matricule)->update($data);
              }
          }
          unset($res);

          // Mise à jour des indicateurs de la classe
          $classesUnique = $eleves->pluck('CODECLAS')->unique();
          foreach ($classesUnique as $codeClasseInner) {
              $elevesClasse = $eleves->where('CODECLAS', $codeClasseInner);
              $moyennesP1 = $elevesClasse->pluck('MS1')->filter(function ($value) {
                  return $value !== null && $value !== -1 && $value > 0;
              })->toArray();
              $moyennesP2 = $elevesClasse->pluck('MS2')->filter(function ($value) {
                  return $value !== null && $value !== -1 && $value > 0;
              })->toArray();
              if ($typean != 1) {
                  $moyennesP3 = $elevesClasse->pluck('MS3')->filter(function ($value) {
                      return $value !== null && $value !== -1 && $value > 0;
                  })->toArray();
              } else {
                  $moyennesP3 = [];
              }

              $plusGrandeMoyenneP1enr = !empty($moyennesP1) ? max($moyennesP1) : 0;
              $plusFaibleMoyenneP1enr  = !empty($moyennesP1) ? min($moyennesP1) : 0;
              $plusGrandeMoyenneP2enr = !empty($moyennesP2) ? max($moyennesP2) : 0;
              $plusFaibleMoyenneP2enr  = !empty($moyennesP2) ? min($moyennesP2) : 0;
              if ($typean != 1) {
                  $plusGrandeMoyenneP3enr = !empty($moyennesP3) ? max($moyennesP3) : 0;
                  $plusFaibleMoyenneP3enr  = !empty($moyennesP3) ? min($moyennesP3) : 0;
              } else {
                  $plusGrandeMoyenneP3enr = 0;
                  $plusFaibleMoyenneP3enr  = 0;
              }

              $moyenneClasseP1enr = count($moyennesP1) > 0
                  ? array_sum($moyennesP1) / count($moyennesP1)
                  : 0;
              $moyenneClasseP2enr = count($moyennesP2) > 0
                  ? array_sum($moyennesP2) / count($moyennesP2)
                  : 0;
              if ($typean != 1) {
                  $moyenneClasseP3enr = count($moyennesP3) > 0
                      ? array_sum($moyennesP3) / count($moyennesP3)
                      : 0;
              } else {
                  $moyenneClasseP3enr = 0;
              }

              $totalMoyennes = 0;
              $nbPeriodes    = 0;
              foreach ([$moyenneClasseP1enr, $moyenneClasseP2enr, $moyenneClasseP3enr] as $moy) {
                  if ($moy !== null) {
                      $totalMoyennes += $moy;
                      $nbPeriodes++;
                  }
              }
              $moyenneClasseGlobaleenr = ($nbPeriodes > 0)
                  ? $totalMoyennes / $nbPeriodes
                  : 0;

              $classeToUpdate = Classes::where('CODECLAS', $codeClasseInner)->first();
              if ($classeToUpdate) {
                  $classeToUpdate->MFaIBLE1 = $plusFaibleMoyenneP1enr;
                  $classeToUpdate->MFORTE1  = $plusGrandeMoyenneP1enr;
                  $classeToUpdate->MFaIBLE2 = $plusFaibleMoyenneP2enr;
                  $classeToUpdate->MFORTE2  = $plusGrandeMoyenneP2enr;
                  $classeToUpdate->MFaIBLE3 = $plusFaibleMoyenneP3enr;
                  $classeToUpdate->MFORTE3  = $plusGrandeMoyenneP3enr;
                  $classeToUpdate->MCLASSE1 = $moyenneClasseP1enr;
                  $classeToUpdate->MCLASSE2 = $moyenneClasseP2enr;
                  $classeToUpdate->MCLASSE3 = $moyenneClasseP3enr;
                  $classeToUpdate->MCLASSE  = $moyenneClasseGlobaleenr;
                  $classeToUpdate->save();
              }
          }
      }

      return back()->with('success', 'Tous les calculs ont été mis à jour avec succès pour chaque élève de toutes les classes.');
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
