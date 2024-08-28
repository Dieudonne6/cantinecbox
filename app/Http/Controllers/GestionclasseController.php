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
use App\Models\Typeenseigne;
use App\Models\Eleveplus;
use App\Http\Requests\inscriptionEleveRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;



use App\Models\Promo;
use App\Models\Notes;

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
            $classes = Classesgroupeclass::where('LibelleGroupe', $libelle)
                              ->orderBy('id', 'desc')
                              ->get();

            // $classes = Classesgroupeclass::where('LibelleGroupe', $libelle)->get();

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
                return response()->json(['error' => 'Cette classe est déjà associée à ce groupe.'], 409);
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

    public function series(Request $request)
    {
        $series = Serie::get();
        return view('pages.inscriptions.series')->with('series', $series);
    }

    public function saveserie(inscriptionEleveRequest $request)
    {
        $series = new Serie();
        $series->SERIE = $request->input('SERIE');
        $series->LIBELSERIE = $request->input('LIBELSERIE');
        $series->CYCLE = $request->input('CYCLE');

        if (Serie::where('SERIE', $series->SERIE)->exists()) {
            return back()->with('error', 'Cette série existe déjà.')->withInput();
        }

        $series->save();
        return back()->with('status', 'Enregistré avec succès');
    }

    public function updateserie(Request $request)
    {
        $series = Serie::find($request->SERIE); // Using SERIE as the identifier
        if ($series) {
            $series->LIBELSERIE = $request->input('LIBELSERIE');
            $series->CYCLE = $request->input('CYCLE');
            $series->save();

            return back()->with('status', 'Modifié avec succès');
        }

        return back()->withErrors('Erreur lors de la modification.');
    }

    public function deleteserie(Request $request)
    {
        $deleteserie = Serie::find($request->SERIE); // Using SERIE as the identifier
        if ($deleteserie) {
            $deleteserie->delete();
            return back()->with('status', 'Supprimé avec succès');
        }

        return back()->withErrors('Erreur lors de la suppression.');
    }
    public function deleteclass(Request $request)
    {
        $deleteclas = Classes::find($request->CODECLAS); // Using SERIE as the identifier
        if ($deleteclas) {
            $deleteclas->delete();
            return back()->with('status', 'Supprimé avec succès');
        }

        return back()->withErrors('Erreur lors de la suppression.');
    }

  
  public function savetypeclasse(inscriptionEleveRequest $request){
    $typeclas = $request->input('TYPECLASSE');
    $typeclas = Typeclasse::where('TYPECLASSE', '=', $typeclas)->exists();

    if($typeclas) {
        return back()->with('error', 'Le type de classe existe déja');
    }  

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

  
   public function enregistrerclasse(inscriptionEleveRequest $request){
    $enreclas = $request->input('nomclasse');
    $enreclas = Classes::where('CODECLAS', '=', $enreclas)->exists();

    if($enreclas) {
        return back()->with('error', 'La classe existe déja');
    }

    $enrclasse = new Classes();
    $enrclasse->CODECLAS = $request->input('nomclasse');
    $enrclasse->LIBELCLAS = $request->input('libclasse');
    $enrclasse->TypeCours = $request->input('typecours');
    $enrclasse->TYPECLASSE = $request->input('typclasse');
    $enrclasse->CYCLE = $request->input('cycle');
    $enrclasse->SERIE = $request->input('typeserie');
    $enrclasse->CODEPROMO = $request->input('typepromo');
    $enrclasse->Niveau = $request->input('numero');
    $enrclasse->TYPEENSEIG = $request->input('typeensei');
    $enrclasse->save();
    return back()->with('status','Enregistrer avec succes');
  } 
 

  public function enregistrerinfo(Request $request){
    $enreninfo = new Eleveplus();
    $enreninfo->MATRICULE = $request->input('matricule');

    $enreninfo->maladiesconnues = $request->input('maladieschroniques');
    $enreninfo->interditalimentaires = $request->input('interditalimentaires');
    $enreninfo->groupesanguin = $request->input('groupesanguin');
    $enreninfo->electroforez = $request->input('typehemoglobine');
    $enreninfo->NOMMERE = $request->input('nommere');
    $enreninfo->prenommere = $request->input('prenommere');
    $enreninfo->telmere = $request->input('telephonemere');
    $enreninfo->emailmere = $request->input('emailmere');
    $enreninfo->professionmere = $request->input('professionmere');
    $enreninfo->adremployeurmere = $request->input('adresseemployeurmere');
    $enreninfo->adrmere = $request->input('adressepersonnellemere');
    $enreninfo->NOMPERE = $request->input('nompere');
    $enreninfo->prenompere = $request->input('prenompere');
    $enreninfo->telpere = $request->input('telephonepere');
    $enreninfo->emailpere = $request->input('emailpere');
    $enreninfo->professionpere = $request->input('professionpere');
    $enreninfo->adremployeurpere = $request->input('adresseemployeurpere');
    $enreninfo->adrpere = $request->input('adressepersonnellepere');
    $enreninfo->nomtutuer = $request->input('nomtuteur');
    $enreninfo->prenomtuteur = $request->input('prenomtuteur');
    $enreninfo->teltuteur = $request->input('telephonetuteur');
    $enreninfo->emailtuteur = $request->input('emailtuteur');
    $enreninfo->adremployeurtuteur = $request->input('adresseemployeurtuteur');
    $enreninfo->adrtuteur = $request->input('adressepersonnelletuteur');
    $enreninfo->professiontuteur = $request->input('professiontuteur');
    $enreninfo->nomurgence = $request->input('nomurgence');
    $enreninfo->prenomurgence = $request->input('prenomurgence');
    $enreninfo->telurgence = $request->input('telephoneurgence');
    $enreninfo->emailpere = $request->input('emailurgence');
    $enreninfo->adrurgence = $request->input('adressepersonnelleurgence');
    $enreninfo->autorisefilm = $request->input('autorisevideo');
    $enreninfo->autoriseuseimage = $request->input('autoriseimage');

    $enreninfo->save();
    return back()->with('status','Enregistrer avec succes');
  } 
//Promotion
public function index()
{
    $promotions = Promo::all(); // Récupère toutes les promotions
    return view('pages.inscriptions.promotions', compact('promotions')); // Utilise le bon nom de la vue
}

// Méthode store
public function store(Request $request)
{
    // Validation des champs du formulaire
    $request->validate([
        'codePromotion' => 'required|max:4',
        'libellePromotion' => 'required|max:14', // Limite ajustée pour correspondre à votre validation HTML
        'Niveau' => 'required|integer|min:1|max:7',
        'enseignement' => 'required|integer'
    ]);

    // Vérifier si le code promo existe déjà
    if (Promo::where('CODEPROMO', $request->codePromotion)->exists()) {
        return redirect()->back()
                         ->withErrors(['codePromotion' => 'Le code promotion existe déjà. Veuillez en choisir un autre.'])
                         ->withInput();
    }

    // Vérifier si le libellé de la promotion existe déjà
    if (Promo::where('LIBELPROMO', $request->libellePromotion)->exists()) {
        return redirect()->back()
                         ->withErrors(['libellePromotion' => 'Le libellé de la promotion existe déjà. Veuillez en choisir un autre.'])
                         ->withInput();
    }

    // Enregistrer la nouvelle promotion
    Promo::create([
        'CODEPROMO' => $request->codePromotion,
        'LIBELPROMO' => $request->libellePromotion,
        'Niveau' => $request->Niveau,
        'TYPEENSEIG' => $request->enseignement
    ]);
    
    // Redirection avec un message de succès
    return redirect()->route('promotions.index')->with('success', 'Promotion créée avec succès !');
}

public function update(Request $request, $codePromo)
{
    $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();

    $request->validate([
        'codePromotion' => 'required|max:4',
        'libellePromotion' => 'required|max:14', // Limite ajustée pour correspondre à votre validation HTML
        'Niveau' => 'required|integer|min:1|max:7',
        'enseignement' => 'required|integer'
    ]);

    $promotion->update([
        'CODEPROMO' => $request->codePromotion,
        'LIBELPROMO' => $request->libellePromotion,
        'Niveau' => $request->Niveau,
        'TYPEENSEIG' => $request->enseignement
    ]);

    return redirect()->route('promotions.index')->with('success', 'Promotion mise à jour avec succès !');
}


public function destroy($codePromo)
{
    $promotion = Promo::where('CODEPROMO', $codePromo)->firstOrFail();
    $promotion->delete();

    return redirect()->route('promotions.index')->with('success', 'Promotion supprimée avec succès !');
}


  public function indexEleves()
{
    $eleves = Eleve::all();
    
        // Récupérer les élèves avec leurs notes
        $eleves = Eleve::with('notes')->get();

    return view('pages.inscriptions.Acceuil', compact('eleves'));
}

  //   return view('pages.inscriptions.groupes');
  // } 

//   fonction d'ajout d'un nouveau eleve

public function nouveaueleve (inscriptionEleveRequest $request) {

   
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

    return redirect()->route('inscrireeleve')->with('status', 'Élève enregistré avec succès');

}

  // public function gettableclasses(){

  //   $classes = new Classes();
  //   $classes->CODECLAS = $request->input('CODECLAS');
  //   $classes->LIBELCLAS = $request->input('LIBELCLAS');
  //   $classes->TYPECLASSE = $request->input('TYPECLASSE');
  //   $classes->CODEPROMO = $request->input('CODEPROMO');
  //   $classes->CYCLE = $request->input('CYCLE');
  //   $classes->SERIE = $request->input('SERIE');
  //   $classes->TYPEENSEIG = $request->input('TYPEENSEIG');
  //   $classes->EFFECTIF = $request->input('EFFECTIF');

  //   $series->save();

  //   return view('pages.inscriptions.tabledesclasses')->with('status', 'Enregistrer avec succès');
  // }

  public function gettabledesclasses(){
    // $classes = Classe::with('serie')->get();

    $classes = DB::table('classes')
            ->join('series', 'classes.SERIE', '=', 'series.SERIE')
            ->join('typeclasses', 'classes.TYPECLASSE', '=', 'typeclasses.TYPECLASSE')
            ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            //  ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            ->select(
                'classes.*',
                'series.LIBELSERIE as serie_libelle',
                'typeclasses.LibelleType as typeclasse_LibelleType',
                'typeenseigne.type as typeenseigne_type',
                //  'typeenseigne.idenseign as typeenseigne_type'
            )
            ->get();

 
    // dd($idserie);

    return view('pages.inscriptions.tabledesclasses', compact('classes'));

  }
  public function enrclasse(){
    if(Session::has('account')){
    // $duplicatafactures = Duplicatafacture::all();
    $typecla = Typeclasse::get();
    $serie = Serie::get();
    $promo = Promo::get();
    $typeenseigne = Typeenseigne::get();
    return view('pages.inscriptions.enregistrementclasse')->with('typecla', $typecla)->with('serie', $serie)->with('promo', $promo)->with('typeenseigne', $typeenseigne);
    } 
    return redirect('/');

  }
  public function modifierclasse($CODECLAS){
  $promo = Promo::get();
  $typeclah = Typeclasse::get();
  $serie = Serie::get();
  $promo = Promo::get();
  $typeenseigne = Typeenseigne::get();
  $typecla = Classes::where('CODECLAS', $CODECLAS)->first();
    if (!$typecla) {
      abort(404, 'Classe non trouvée');
    }
    return view('pages.inscriptions.modifierclasse')->with('typecla', $typecla)->with('serie', $serie)->with('promo', $promo)->with('typeenseigne', $typeenseigne)->with('typeclah', $typeclah);
  }
  public function modifieclasse(Request $request, $CODECLAS){
    $modifycla = Classes::find($CODECLAS);
    if ($modifycla) {
      $modifycla->LIBELCLAS = $request->input('libclasse');
      $modifycla->TypeCours = $request->input('typecours');
      $modifycla->TYPECLASSE = $request->input('typclasse');
      $modifycla->CYCLE = $request->input('cycle');
      $modifycla->SERIE = $request->input('typeserie');
      $modifycla->CODEPROMO = $request->input('typepromo');
      $modifycla->Niveau = $request->input('numero');
      $modifycla->TYPEENSEIG = $request->input('typeensei');
      $modifycla->update();
      return back()->with('status','Modifier avec succes');
    }
    else {
      return back()->withErrors('Erreur lors de la modification.');

    }
  }

}