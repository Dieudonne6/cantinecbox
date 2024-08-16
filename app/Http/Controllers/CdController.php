<?php
namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Http\Request;

class CdController extends Controller
{
public function indexEleves()
{
    // Récupérer le total des élèves
    $total = Classe::sum('EFFECTIF');
    
    // Récupérer le nombre de filles et de garçons
    $filles = Eleve::where('SEXE', 'F')->count();
    $garcons = Eleve::where('SEXE', 'M')->count();

    // Récupérer les élèves redoublants
    $totalRed = Eleve::where('Reduction', '>', 0)->count();
    $fillesRed = Eleve::where('SEXE', 'F')->where('Reduction', '>', 0)->count();
    $garconsRed = Eleve::where('SEXE', 'M')->where('Reduction', '>', 0)->count();

    // Passer les données à la vue
    return view('pages.inscriptions.acceuil', compact('total', 'filles', 'garcons', 'totalRed', 'fillesRed', 'garconsRed'));
}


    public function recalculerEffectifs()
    {
        $total = Classe::sum('EFFECTIF');
        $filles = Eleve::where('SEXE', 'F')->count();
        $garcons = Eleve::where('SEXE', 'M')->count();
        $totalRed = Eleve::where('Reduction', '>', 0)->count();
        $fillesRed = Eleve::where('SEXE', 'F')->where('Reduction', '>', 0)->count();
        $garconsRed = Eleve::where('SEXE', 'M')->where('Reduction', '>', 0)->count();

        return response()->json([
            'total' => $total,
            'filles' => $filles,
            'garcons' => $garcons,
            'totalRed' => $totalRed,
            'fillesRed' => $fillesRed,
            'garconsRed' => $garconsRed
        ]);
    }
}
