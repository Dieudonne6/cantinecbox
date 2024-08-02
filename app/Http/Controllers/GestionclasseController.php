<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Serie;
use Illuminate\Http\Request;
use App\Models\Typeclasse;
use App\Models\Classes;


class GestionclasseController extends Controller
{
  public function groupes(){
    return view('pages.inscriptions.groupes');
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

  
   public function enregistrerclasse(Request $request){
    $enrclasse = new Classes();
    $enrclasse->CODECLAS = $request->input('nomclasse');
    $enrclasse->LIBELCLAS = $request->input('libclasse');
    $enrclasse->TypeCours = $request->input('typecours');
    $enrclasse->TYPECLASSE = $request->input('typclasse');
    $enrclasse->CYCLE = $request->input('cycle');
    $enrclasse->SERIE = $request->input('typeserie');
    $enrclasse->SERIE = $request->input('typeserie');

    $enrclasse->save();
    return back()->with('status','Enregistrer avec succes');
  } 
  // public function groupes(){
    
  //   return view('pages.inscriptions.groupes');
  // } 

}
