<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Classesgroupeclass;
use App\Models\Groupeclasse;
use App\Models\Classes;
use App\Models\Eleve;
use App\Models\Serie;
use App\Models\Typeclasse;




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

//   fonction d'ajout d'un nouveau eleve

public function nouveaueleve (Request $request) {

    // validation des donne 
    // $request->validate([
    //     'classe' => 'required',
    //     'classeEntre' => 'required',
    //     'numOrdre' => 'required',
    //     'photo' => 'required',
    //     'reduction' => 'required',
    //     'nom' => 'required',
    //     'prenom' => 'required',
    //     'dateNaissance' => 'required',
    //     'lieuNaissance' => 'required',
    //     'dateInscription' => 'required',
    //     'departement' => 'required',
    //     'sexe' => 'required',
    //     'typeEleve' => 'required',
    //     'aptituteSport' => 'required',
    //     'adressePersonnelle' => 'required',
    //     'etablissementOrigine' => 'required',
    //     'nationalite' => 'required',
    //     'redoublant' => 'required',
    //     'adressePersonnelle' => 'required',
    //     'nomPere' => 'required',
    //     'nomMere' => 'required',
    //     'adressesParents' => 'required',
    //     'autresRenseignements' => 'required',
    //     'indicatifParent' => 'required',
    //     'telephoneParent' => 'required',
    //     'telephoneEleve' => 'required',
    // ]);

    // dd($request->input('nom'));

    // Effectuer le traitement des photo
    // $request->validate([
    //     'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    // ]);
    $imageName = $request->file('photo');
    $imageContent = file_get_contents($imageName->getRealPath());

    // Gestion des atributs MATRICULEX et CODEWEB
    // Convertir le nouveau matricule en chaîne de caractères avec des zéros devant
    $matricule = $request->input('numOrdre');
    $formateMatricule = str_pad($matricule, 8, '0', STR_PAD_LEFT);
    // dd($formateMatricule);

    // Récupérer la valeur du checkbox de redoublant
    $redoublant = $request->has('redoublant') ? 1 : 0;

    $nouveauEleve = new Eleve();
    $nouveauEleve->CODECLAS = $request->input('classe');

    if(($request->input('classeEntre')) === 'idem'){
        $nouveauEleve->ANCCLASSE = $request->input('classe');
    }else{
        $nouveauEleve->ANCCLASSE = $request->input('classeEntre');
    }

    $nouveauEleve->MATRICULE = $request->input('numOrdre'); // MATRICULE prend la valeur du champ numero d'ordre
    $nouveauEleve->PHOTO = $imageContent;
    $nouveauEleve->CodeReduction = $request->input('reduction');
    $nouveauEleve->NOM = $request->input('nom');
    $nouveauEleve->PRENOM = $request->input('prenom');
    $nouveauEleve->DATENAIS = $request->input('dateNaissance');
    $nouveauEleve->LIEUNAIS = $request->input('lieuNaissance');
    $nouveauEleve->DATEINS = $request->input('dateInscription');
    $nouveauEleve->CODEDEPT = $request->input('departement');
    $nouveauEleve->SEXE = $request->input('sexe');
    $nouveauEleve->STATUTG = $request->input('typeEleve');
    $nouveauEleve->APTE = $request->input('aptituteSport');
    $nouveauEleve->ADRPERS = $request->input('adressePersonnelle');
    $nouveauEleve->ETABORIG = $request->input('etablissementOrigine');
    $nouveauEleve->NATIONALITE = $request->input('nationalite');
    $nouveauEleve->STATUT = $redoublant;
    $nouveauEleve->NOMPERE = $request->input('nomPere');
    $nouveauEleve->NOMMERE = $request->input('nomMere');
    $nouveauEleve->ADRPAR = $request->input('adressesParents');
    $nouveauEleve->AUTRE_RENS = $request->input('autresRenseignements');
    $nouveauEleve->indicatif1 = $request->input('indicatifParent');
    $nouveauEleve->TEL = $request->input('telephoneParent'); // TELEPHONE PARRENT
    $nouveauEleve->TelEleve = $request->input('telephoneEleve'); // telephone eleve
    $nouveauEleve->MATRICULEX = $formateMatricule; // VALEUR GENERER DYNAMIQUEMENT
    $nouveauEleve->CODEWEB = $formateMatricule; // MEME VALEUR QUE MATRICULEX

    $nouveauEleve->save();

    return redirect()->route('inscrireeleve')->with('status', 'Élève enregistré avec succès');;

}

}