<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Classesgroupeclass;
use App\Models\Groupeclasse;
use App\Models\Classes;



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

}
