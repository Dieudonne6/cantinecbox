<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Classesgroupeclass;
use App\Models\Groupeclasse;
use App\Models\Typeenseigne;
use App\Models\Promo;
use App\Models\Classes;
use App\Models\Params2;
use App\Models\Paramcontrat;
use App\Models\Eleve;

class ListemeriteController extends Controller
{
    //
    public function acceuil(){
        $classesg = Groupeclasse::select('LibelleGroupe')->distinct()->get();
        $promotions = Promo::all();
        $classes = Classes::withCount(['eleves' => function ($query) {
            $query->where('CODECLAS', '!=', '');
          }])->get();
        return view ('pages.notes.listeparmerite', compact( 'classesg', 'promotions', 'classes'));
    }

/*     public function getClassesByGroupe($type)
    {
      // dd($type);
      // Récupère les classes associées au type d'enseignement
      $classes = Classes::where('', $type)->withCount('eleves')->get();
      // $classes = Classes::where('TYPEENSEIG', $type)->get();
      
      return response()->json($classes);
    }
 */
    public function getClassesByGroup(Request $request)
{
    $libelleGroupe = $request->input('libelleGroupe');

    // Vérifier que le groupe est sélectionné
    if (!$libelleGroupe) {
        return response()->json(['error' => 'Aucun groupe sélectionné'], 400);
    }

    // Récupérer les classes avec le compte des élèves
    $classes = DB::table('classes_groupeclasse')
        ->join('classes', 'classes_groupeclasse.CODECLAS', '=', 'classes.CODECLAS')
        ->leftJoin('eleve', 'classes.CODECLAS', '=', 'eleve.CODECLAS')
        ->where('classes_groupeclasse.LibelleGroupe', $libelleGroupe)
        ->select('classes.CODECLAS', 
                DB::raw('COUNT(eleve.MATRICULE) as EFFECTIF'))
        ->groupBy('classes.CODECLAS')
        ->get();

    return response()->json($classes);
}


    public function imprimerListeMerite(Request $request)
{
    // Validation des paramètres
    $request->validate([
        'classes' => 'required',
        'periode' => 'required|integer|between:1,3'
    ]);

    // Vérification que classes n'est pas 'undefined'
    if ($request->query('classes') === 'undefined') {
        return back()->with('error', 'Veuillez sélectionner au moins une classe');
    }

    $classes = explode(',', $request->query('classes'));
    $periode = $request->query('periode');

    // Vérification que le tableau de classes n'est pas vide
    if (empty($classes)) {
        return back()->with('error', 'Veuillez sélectionner au moins une classe');
    }

    // Déterminer les colonnes de moyenne et de rang en fonction de la période
    $colonneMoyenne = 'MS' . $periode;
    $colonneRang = 'RANG' . $periode;

    $eleves = \DB::table('eleve')
        ->whereIn('CODECLAS', $classes)
        ->select(
            'NOM',
            'PRENOM',
            'CODECLAS',
            'MAN',
            $colonneMoyenne . ' as MOYENNE',
            $colonneRang . ' as RANGA'
        )
        ->orderBy('CODECLAS')
        ->orderByDesc($colonneMoyenne)
        ->get();

        $params2 = Params2::first();
        $typean = $params2->TYPEAN;

        $infoparamcontrat = Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $annescolaire = $anneencours . '-' . $annesuivante;
    return view('pages.notes.imprimerListeMerite', compact('eleves', 'periode', 'typean', 'annescolaire'));
}

}
