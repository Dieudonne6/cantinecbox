<?php
namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Notes;
use App\Models\Matieres;
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

  public function saisirnote()
  {
    $classes = Classe::all();
    $eleves = Eleve::all();
    $notes = Notes::all();
    $gclasses = Groupeclasse::all();
    $matieres = Matieres::all();
    // Passer les données à la vue
    return view('pages.notes.saisirnote', compact('classes','notes','gclasses','matieres', 'eleves'));
  }
  public function savenote(){
    $notes = Notes::all();

  }
}