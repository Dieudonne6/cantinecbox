<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Classesgroupeclass;
use App\Models\Groupeclasse;
use App\Models\Classes;
use App\Models\Typeclasse;
use App\Models\Promo;
use App\Models\Eleve;




class GestionclasseController extends Controller
{
      public function groupes(){
        $listegroupe = Groupeclasse::all();

        // $listeclasse = Classesgroupeclass::where('LibelleGroupe', '=', $libelle);
        // dd($listegroupe);
        return view('pages.inscriptions.groupes')->with('listegroupe' , $listegroupe);
    } 

    public function ClassesParGroupe($libelle)
    {
        try {
            // Supposons que votre table ClasseGroupeClass a une colonne 'LibelleGroupe'
            $classes = Classesgroupeclass::where('LibelleGroupe', $libelle)->get();

            // Vérifiez les données récupérées
            if ($classes->isEmpty()) {
                return response()->json(['message' => 'Aucune classe trouvée pour ce groupe'], 404);
            }

            return response()->json($classes);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Erreur interne du serveur'], 500);
        }
    }

    public function afficherTouteClasse()
    {
        $classes = Classes::all();
        // dd($allClasses);
        return response()->json($classes);
    }

    public function ajouterClasse(Request $request, $libelle)
    {
        try {

    
            $classCode = $request->input('classCode');
    
            // Vérifiez si la classe existe déjà pour le groupe
            $existing = Classesgroupeclass::where('LibelleGroupe', $libelle)
                                          ->where('CODECLAS', $classCode)
                                          ->exists();
    
            if ($existing) {
                return response()->json(['message' => 'La classe existe déjà dans ce groupe'], 400);
            }
    
            // Ajoutez la classe au groupe
            $classeGroupeClass = new Classesgroupeclass();
            $classeGroupeClass->LibelleGroupe = $libelle;
            $classeGroupeClass->CODECLAS = $classCode;
            $classeGroupeClass->save();
    
            return response()->json(['message' => 'Classe ajoutée avec succès']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Erreur interne du serveur'], 500);
        }
    }
    

    public function supprimerClasse(Request $request, $libelle, $id)
{
    try {
        $classeGroupeClass = Classesgroupeclass::where('id', $id)->first();

        if (!$classeGroupeClass) {
            return response()->json(['message' => 'Classe non trouvée'], 404);
        }

        $classeGroupeClass->delete();

        return response()->json(['message' => 'Classe supprimée avec succès']);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json(['message' => 'Erreur interne du serveur'], 500);
    }
}


public function supprimerGroupe($id)
{
    try {
        // Rechercher le groupe par ID
        $groupe = Groupeclasse::find($id);

        if (!$groupe) {
            return redirect()->back()->with('error', 'Groupe non trouvé');
        }

        // Supprimer le groupe
        $groupe->delete();

        return redirect('/groupes')->with('success', 'Groupe supprimé avec succès');
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return redirect()->back()->with('error', 'Erreur interne du serveur');
    }
}

public function groupe(){

    $allgroupes = Groupeclasse::all();
    return view('pages.inscriptions.groupe')->with('allgroupes', $allgroupes);

    } 


public function ajoutergroupe (Request $request) {

    $nomgroupe = $request->nomgroupe;

    $groupeexist = Groupeclasse::where('LibelleGroupe', '=', $nomgroupe)->exists();

    if($groupeexist) {
        return redirect('/groupe')->with('error', 'le groupe existe deja');
    }

    $nouveaugroupe = new Groupeclasse();
    $nouveaugroupe->LibelleGroupe = $nomgroupe;
    $nouveaugroupe->save();


    return redirect('/groupe')->with('success', 'groupe ajouter avec success');
}


public function suppGroupe($id)
{
    try {
        // Rechercher le groupe par ID
        $groupe = Groupeclasse::find($id);

        if (!$groupe) {
            return redirect()->back()->with('error', 'Groupe non trouvé');
        }

        // Supprimer le groupe
        $groupe->delete();

        return redirect('/groupe')->with('success', 'Groupe supprimé avec succès');
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return redirect()->back()->with('error', 'Erreur interne du serveur');
    }
}




  public function series(Request $request){
    $series = DB::table('series')->select('SERIE', 'LIBELSERIE')->get();
    return view ('pages.inscriptions.series')->with('series', $series);
  }
  
  public function savetypeclasse(Request $request){
    $typeclasse = new Typeclasse();
    if(strtolower($request->input('LibelleType')) == "systeme"){
      $typeclasse->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclasse->LibelleType = $request->input('LibelleType');
      $typeclasse->OuiNonScolarite = 0;
      $typeclasse->OuiNonNotes = 0;
      $typeclasse->OuiNonStat = 0;
    } else {
      $typeclasse->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclasse->LibelleType = $request->input('LibelleType');
      $typeclasse->OuiNonScolarite = 1;
      $typeclasse->OuiNonNotes = 1;
      $typeclasse->OuiNonStat = 1;
    }
    $typeclasse->save();
    return back()->with('status','Enregistrer avec succes');
  }
  public function getclasse(){
    $allclasse = Typeclasse::get();
    return view('pages.inscriptions.typesclasses')->with('allclasse', $allclasse);   
  }
  public function updateTypeClasse(Request $request)
  {
    $typeclass = Typeclasse::find($request->idtype);
    if ($typeclass) {
      $typeclass->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclass->LibelleType = $request->input('LibelleType');
      $typeclass->save();
      return back()->with('status', 'Modifié avec succès');
    }
    return back()->withErrors('Erreur lors de la modification.');
  }
  public function deletetype(Request $request)
  {
    $deletetyp = Typeclasse::find($request->idtype);
    if ($deletetyp) {
      $deletetyp->delete();
      return back()->with('status', 'Supprimé avec succès');
    } 
    return back()->withErrors('Erreur lors de la suppression.');
  }
  
  // public function groupes(){
    

  //   return view('pages.inscriptions.groupes');
  // } 

  //Promotion
  public function index()
  {
      $promotions = Promo::all(); // Récupère toutes les promotions
      return view('pages.inscriptions.promotions', compact('promotions')); // Utilise le bon nom de la vue
  }

  public function store(Request $request)
  {
      $request->validate([
          'codePromotion' => 'required|max:4|unique:promo,CODEPROMO',
          'libellePromotion' => 'required',
          'Niveau' => 'required|integer|min:1|max:7',
          'enseignement' => 'required|integer'
      ]);

      Promo::create([
          'CODEPROMO' => $request->codePromotion,
          'LIBELPROMO' => $request->libellePromotion,
          'Niveau' => $request->Niveau,
          'TYPEENSEIG' => $request->enseignement
      ]);

      return redirect()->route('promotions.index');
  }

  public function update(Request $request, $codePromo)
  {
      $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();

      $request->validate([
          'codePromotion' => 'required|max:4',
          'libellePromotion' => 'required',
          'Niveau' => 'required|integer|min:1|max:7',
          'enseignement' => 'required|integer'
      ]);

      $promotion->update([
          'CODEPROMO' => $request->codePromotion,
          'LIBELPROMO' => $request->libellePromotion,
          'Niveau' => $request->Niveau,
          'TYPEENSEIG' => $request->enseignement
      ]);

      return redirect()->route('promotions.index');
  }

  public function destroy($codePromo)
  {
      $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();
      $promotion->delete();

      return redirect()->route('promotions.index');
  }

  //Acceuil
  public function indexEleves()
  {
      $eleves = Eleve::all(); // Récupère tous les élèves

      return view('pages.inscriptions.Acceuil', compact('eleves')); // Passe les données à la vue 'Acceuil'
  }

  //   return view('pages.inscriptions.groupes');
  // } 


}
