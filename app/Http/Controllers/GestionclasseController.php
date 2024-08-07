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
use App\Models\Typeenseigne;

use App\Models\Promo;
use App\Models\Eleve;

class GestionclasseController extends Controller
{
      public function groupes(){
        
        return view('pages.inscriptions.groupes');
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

  //Acceuil
  public function indexEleves()
  {
      $eleves = Eleve::all(); // Récupère tous les élèves

      return view('pages.inscriptions.Acceuil', compact('eleves')); // Passe les données à la vue 'Acceuil'
  }

  //   return view('pages.inscriptions.groupes');
  // } 

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
            ->select(
                'classes.*',
                'series.LIBELSERIE as serie_libelle',
                'typeclasses.LibelleType as typeclasse_LibelleType',
                 'typeenseigne.idenseign as typeenseigne_type'
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
