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

use App\Models\Promo;
use App\Models\Notes;

class GestionclasseController extends Controller
{
      public function groupes(){
        $listegroupe = Groupeclasse::all();
        return view('pages.inscriptions.groupes')->with('listegroupe', $listegroupe);
    }

    public function series(Request $request){
      $series = Serie::get();
      // dd($series);
      return view ('pages.inscriptions.series')->with('series', $series);
  }

  public function saveserie(Request $request){
    $series = new Serie();
    $series->SERIE = $request->input('SERIE');
    $series->LIBELSERIE = $request->input('LIBELSERIE');
    $series->CYCLE = $request->input('CYCLE');

    if (Serie::where('SERIE', $series->SERIE)->exists()) {
    return back()->with('status', 'Cette série existe déjà.');
  }
    $series->save();

    return back()->with('status', 'Enregistrer avec succès');
  }

  public function updateserie(Request $request){

    $series = Serie::find($request->idcycle);
    if ($series) {
      $series->SERIE = $request->input('SERIE');
      $series->LIBELSERIE = $request->input('LIBELSERIE');
      $series->CYCLE = $request->input('CYCLE');
      $series->save();
      
      return back()->with('status', 'Modifié avec succès');
    }

    return back()->withErrors('Erreur lors de la modification.');

  }

  public function deleteserie(Request $request)
  {
    $deleteserie = Serie::find($request->idcycle);
    if ($deleteserie) {
      $deleteserie->delete();
      return back()->with('status', 'Supprimé avec succès');
    } 
    return back()->withErrors('Erreur lors de la suppression.');
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

  
   public function enregistrerclasse(Request $request){
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
    $modifycla = Classes::where('CODECLAS', $CODECLAS)->firstOrFail();
    if ($modifycla) {
      $modifycla->CODECLAS = $request->input('nomclasse');
      $modifycla->LIBELCLAS = $request->input('libclasse');
      $modifycla->TypeCours = $request->input('typecours');
      $modifycla->TYPECLASSE = $request->input('typclasse');
      $modifycla->CYCLE = $request->input('cycle');
      $modifycla->SERIE = $request->input('typeserie');
      $modifycla->CODEPROMO = $request->input('typepromo');
      $modifycla->Niveau = $request->input('numero');
      $modifycla->TYPEENSEIG = $request->input('typeensei');
      $modifycla->save();
      return view('pages.inscriptions.tabledesclasses');

    }
    return back()->withErrors('Erreur lors de la modification.');

  }
  
}

