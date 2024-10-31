<?php
namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Notes;
use App\Models\Matieres;
use App\Models\Clasmat;

use App\Models\Groupeclasse;
use Illuminate\Http\Request;

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
    $classe = $request->input('classe','CE1A');
    $matiere = $request->input('matiere',1);
    $matieres = Matieres::all();
    $gclasses = Groupeclasse::all();
    
    // Commencer la requête pour les élèves
    $elevesQuery = Eleve::query();
    
    // Appliquer le filtre de classe uniquement si une classe est sélectionnée
    if ($classe) {
      $elevesQuery->where('eleve.CODECLAS', $classe);
    }
    
    $elevesQuery->leftJoin('notes', function ($join) use ($matiere) {
      $join->on('eleve.MATRICULE', '=', 'notes.MATRICULE');
      if ($matiere) {
        $join->where('notes.CODEMAT', $matiere);
      }
    });
    
    $getClasmat = Clasmat::where('CODEMAT', $matiere)->first();
    // Sélectionner les colonnes des deux tables pour les afficher dans la vue
    $eleves = $elevesQuery->select('eleve.*', 
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
    'notes.MS1')->get();
    return view('pages.notes.saisirnote', compact('classes', 'eleves', 'gclasses', 'matieres', 'classe', 'matiere', 'getClasmat'));
  }
  public function saisirnotefilter(Request $request)
  {
    $classes = Classe::all();
    $classe = $request->input('classe');
    $matiere = $request->input('matiere');
    $matieres = Matieres::all();
    $gclasses = Groupeclasse::all();
    
    // Commencer la requête pour les élèves
    $elevesQuery = Eleve::query();
    
    // Appliquer le filtre de classe uniquement si une classe est sélectionnée
    if ($classe) {
      $elevesQuery->where('eleve.CODECLAS', $classe);
    }
    
    $elevesQuery->leftJoin('notes', function ($join) use ($matiere) {
      $join->on('eleve.MATRICULE', '=', 'notes.MATRICULE');
      if ($matiere) {
        $join->where('notes.CODEMAT', $matiere);
      }
    });
    $getClasmat = Clasmat::where('CODEMAT', $matiere)->first();

    // Sélectionner les colonnes des deux tables pour les afficher dans la vue
    $eleves = $elevesQuery->select('eleve.*', 
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
    'notes.MS1')->get();
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
          // Ajoutez d'autres validations si nécessaire
      ]);
        // dd($validatedData['notes']);
      // Parcourir chaque élève et enregistrer les notes
      foreach ($validatedData['notes'] as $matricule => $noteData) {
          Notes::updateOrCreate(
              ['MATRICULE' => $matricule], // Critère de correspondance
              array_merge($noteData, [
                'SEMESTRE' => $request->champ1,
                'COEF' => $request->champ2,
                'CODEMAT' => $request->CODEMAT,
                'CODECLAS' => $request->CODECLAS,
            ])// Données à insérer ou mettre à jour
          );
      }

      return redirect()->back()->with('success', 'Les notes ont été enregistrées avec succès.');
  }
  
 
  public function savenote(){
    $notes = Notes::all();
    
  }
}