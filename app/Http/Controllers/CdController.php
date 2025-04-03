<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Notes;
use App\Models\Matieres;
use App\Models\Clasmat;
use App\Models\Params2;

use App\Models\Groupeclasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CdController extends Controller
{
  // public function indexEleves()
  // {
  //     // Récupérer le total des élèves
  //     $total = Classe::sum('EFFECTIF');

  //     // Récupérer le nombre de filles et de garçons
  //     $filles = Eleve::where('SEXE', 'F')->count();
  //     $garcons = Eleve::where('SEXE', 'M')->count();

  //     // Récupérer les élèves redoublants
  //     $totalRed = Eleve::where('Reduction', '>', 0)->count();
  //     $fillesRed = Eleve::where('SEXE', 'F')->where('Reduction', '>', 0)->count();
  //     $garconsRed = Eleve::where('SEXE', 'M')->where('Reduction', '>', 0)->count();

  //     // Passer les données à la vue
  //     return view('pages.inscriptions.acceuil', compact('total', 'filles', 'garcons', 'totalRed', 'fillesRed', 'garconsRed'));
  // }

  //     public function recalculerEffectifs()
  //     {
  //         $total = Classe::sum('EFFECTIF');
  //         $filles = Eleve::where('SEXE', 'F')->count();
  //         $garcons = Eleve::where('SEXE', 'M')->count();
  //         $totalRed = Eleve::where('Reduction', '>', 0)->count();
  //         $fillesRed = Eleve::where('SEXE', 'F')->where('Reduction', '>', 0)->count();
  //         $garconsRed = Eleve::where('SEXE', 'M')->where('Reduction', '>', 0)->count();

  //         return response()->json([
  //             'total' => $total,
  //             'filles' => $filles,
  //             'garcons' => $garcons,
  //             'totalRed' => $totalRed,
  //             'fillesRed' => $fillesRed,
  //             'garconsRed' => $garconsRed
  //         ]);
  //     }

  public function verrouillage()
  {
    $classes = Classe::all();
    // Passer les données à la vue
    return view('pages.notes.verrouillage', compact('classes'));
  }

  // public function saisirnote()
  // {
  //   $classes = Classe::all();
  //   $eleves = Eleve::all();
  //   $notes = Notes::all();
  //   $gclasses = Groupeclasse::all();
  //   $matieres = Matieres::all();
  //   return view('pages.notes.saisirnote', compact('classes','notes','gclasses','matieres', 'eleves'));
  // }
  public function saisirnote(Request $request)
  {
    $classes = Classe::all();
    $classe = $request->input('classe', 'CE1A');
    $matiere = $request->input('matiere', 1);
    $periode = $request->input('periode', 1);
    $matieres = Matieres::all();
    $gclasses = Groupeclasse::all();

    // Commencer la requête pour les élèves
    $elevesQuery = Eleve::query();

    // Appliquer le filtre de classe uniquement si une classe est sélectionnée
    if ($classe) {
      $elevesQuery->where('eleve.CODECLAS', $classe);
    }

    $elevesQuery->leftJoin('notes', function ($join) use ($matiere, $periode) {
      $join->on('eleve.MATRICULE', '=', 'notes.MATRICULE');

      if ($matiere) {
        $join->where('notes.CODEMAT', $matiere);
      }

      if ($periode) {
        $join->where('notes.SEMESTRE', $periode);
      }
    });

    $getClasmat = Clasmat::where([
      ['CODECLAS', '=', $classe],
      ['CODEMAT', '=', $matiere]
    ])->first();    // Sélectionner les colonnes des deux tables pour les afficher dans la vue
    $eleves = $elevesQuery->select(
      'eleve.*',
      'notes.INT1',
      'notes.INT2',
      'notes.INT3',
      'notes.INT4',
      'notes.INT5',
      'notes.INT6',
      'notes.INT7',
      'notes.INT8',
      'notes.INT9',
      'notes.INT10',
      'notes.MI',
      'notes.DEV1',
      'notes.DEV2',
      'notes.DEV3',
      'notes.MS',
      'notes.TEST',
      'notes.MS1'
    )->get();
    return view('pages.notes.saisirnote', compact('classes', 'eleves', 'gclasses', 'matieres', 'classe', 'matiere', 'getClasmat'));
  }
  public function saisirnotefilter(Request $request)
  {
    $classes = Classe::all();
    $classe = $request->input('classe');
    $matiere = $request->input('matiere');
    $periode = $request->input('periode', 1);
    $matieres = Matieres::all();
    $gclasses = Groupeclasse::all();

    // Commencer la requête pour les élèves
    $elevesQuery = Eleve::query();

    // Appliquer le filtre de classe uniquement si une classe est sélectionnée
    if ($classe) {
      $elevesQuery->where('eleve.CODECLAS', $classe);
    }

    $elevesQuery->leftJoin('notes', function ($join) use ($matiere, $periode) {
      $join->on('eleve.MATRICULE', '=', 'notes.MATRICULE');

      if ($matiere) {
        $join->where('notes.CODEMAT', $matiere);
      }

      if ($periode) {
        $join->where('notes.SEMESTRE', $periode);
      }
    });

    // $getClasmat = Clasmat::where('CODEMAT', $matiere)->first();
    $getClasmat = Clasmat::where([
      ['CODECLAS', '=', $classe],
      ['CODEMAT', '=', $matiere]
    ])->first();
    // Sélectionner les colonnes des deux tables pour les afficher dans la vue
    $eleves = $elevesQuery->select(
      'eleve.*',
      'notes.INT1',
      'notes.INT2',
      'notes.INT3',
      'notes.INT4',
      'notes.INT5',
      'notes.INT6',
      'notes.INT7',
      'notes.INT8',
      'notes.INT9',
      'notes.INT10',
      'notes.MI',
      'notes.DEV1',
      'notes.DEV2',
      'notes.DEV3',
      'notes.MS',
      'notes.TEST',
      'notes.MS1'
    )->get();
    return view('pages.notes.saisirnotefilter', compact('classes', 'eleves', 'gclasses', 'matieres', 'classe', 'matiere', 'getClasmat'));
  }

  public function enregistrerNotes(Request $request)
  {
    // Valider les données entrantes
    $validatedData = $request->validate([
      'champ1' => 'required|integer',
      'champ2' => 'required|integer',
      'CODEMAT' => 'required|integer',
      'CODECLAS' => 'required|string',
      'notes' => 'required|array',
      'notes.*.INT1' => 'nullable|numeric',
      'notes.*.INT2' => 'nullable|numeric',
      'notes.*.INT3' => 'nullable|numeric',
      'notes.*.INT4' => 'nullable|numeric',
      'notes.*.INT5' => 'nullable|numeric',
      'notes.*.INT6' => 'nullable|numeric',
      'notes.*.INT7' => 'nullable|numeric',
      'notes.*.INT8' => 'nullable|numeric',
      'notes.*.INT9' => 'nullable|numeric',
      'notes.*.INT10' => 'nullable|numeric',
      'notes.*.MI' => 'nullable|numeric',
      'notes.*.DEV1' => 'nullable|numeric',
      'notes.*.DEV2' => 'nullable|numeric',
      'notes.*.DEV3' => 'nullable|numeric',
      'notes.*.MS' => 'nullable|numeric',
      'notes.*.TEST' => 'nullable|numeric',
      'notes.*.MS1' => 'nullable|numeric',
    ]);

    // Parcourir chaque élève et enregistrer les notes
    foreach ($validatedData['notes'] as $matricule => $noteData) {
      // Rechercher si une note existe déjà pour cet élève, matière, classe et semestre
      $noteExistante = Notes::where('MATRICULE', $matricule)
        ->where('SEMESTRE', $request->champ1)
        ->where('CODEMAT', $request->CODEMAT)
        ->where('CODECLAS', $request->CODECLAS)
        ->first();

      if ($noteExistante) {
        // Si la note existe, on la met à jour
        $noteExistante->update(array_merge($noteData, [
          'COEF' => $request->champ2,
        ]));
      } else {
        // Sinon, on crée une nouvelle entrée
        Notes::create(array_merge($noteData, [
          'MATRICULE' => $matricule,
          'SEMESTRE' => $request->champ1,
          'COEF' => $request->champ2,
          'CODEMAT' => $request->CODEMAT,
          'CODECLAS' => $request->CODECLAS,
        ]));
      }
    }

    return redirect()->back()->with('success', 'Les notes ont été enregistrées avec succès.');
  }

  public function deleteNote(Request $request)
  {
    // Récupérer les valeurs de 'classe' et 'matiere' depuis le formulaire
    $classe = $request->input('classe');
    $matiere = $request->input('matiere');

    // Mettre à jour les champs de notes à NULL pour les enregistrements correspondants
    Notes::where('CODECLAS', $classe)
      ->where('CODEMAT', $matiere)
      ->update([
        'INT1' => null,
        'INT2' => null,
        'INT3' => null,
        'INT4' => null,
        'INT5' => null,
        'INT6' => null,
        'INT7' => null,
        'INT8' => null,
        'INT9' => null,
        'INT10' => null,
        'MI' => null,
        'DEV1' => null,
        'DEV2' => null,
        'DEV3' => null,
        'MS' => null,
        'TEST' => null,
        'MS1' => null
      ]);

    // Redirection ou réponse de confirmation
    return redirect()->back()->with('success', 'Les notes ont été supprimées.');
  }

  public function attestationdemerite()
  {
    $classes = Classe::all();
    $eleves = Eleve::all();
    $params = Params2::first();
    // Passer les données à la vue
    return view('pages.notes.attestationdemerite', compact('classes', 'eleves', 'params'));
  }

  public function attestationfilter(Request $request)
  {
    $codeClasse = $request->input('CODECLAS');
    $trimestre = $request->input('trimestre');
    $meritMode = $request->input('meritMode');

    $classes = Classe::all();
    $params = Params2::first();

    $elevesQuery = Eleve::where('CODECLAS', $codeClasse);

    if ($trimestre) {
      $trimestreColumn = "MS" . $trimestre;

      // Vérifie quel filtre appliquer en fonction du mode de mérite sélectionné
      if ($meritMode === 'allMerits') {
        // Filtrer les élèves dont la note pour le trimestre est supérieure ou égale au mérite
        $elevesQuery = $elevesQuery->where($trimestreColumn, '>=', $params->NoteTH);
      } elseif ($meritMode === 'meritOnly') {
        // Récupère l'élève ayant la note la plus élevée dans la classe pour le trimestre choisi
        $elevesQuery = $elevesQuery->orderBy($trimestreColumn, 'desc')->limit(1);
      }
    }

    $eleves = $elevesQuery->get();

    return view('pages.notes.attestationfiltere', compact('eleves', 'classes', 'params', 'trimestre'));
  }

  public function printCertificates(Request $request)
  {
    try {
      $selectedEleves = $request->input('eleves');
      $selectedElevesArray = explode(',', $selectedEleves);

      if (empty($selectedEleves)) {
        return redirect()->back()->withErrors(['message' => 'Veuillez sélectionner au moins un élève pour l\'impression.']);
      }

      $eleves = Eleve::whereIn('MATRICULE', $selectedElevesArray)->get();

      $merit = $request->input('merit');
      $trimestre = $request->input('trimestre');
      $signChoice = $request->input('signChoice');
      $params = Params2::first();

      return view('pages.notes.print_certificates', compact('eleves', 'merit', 'trimestre', 'signChoice', 'params'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'impression des certificats.');
    }
  }

  public function printTemplate()
  {
    return view('pages.notes.template_certificate');
  }
}
