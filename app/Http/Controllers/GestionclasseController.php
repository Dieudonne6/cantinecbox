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
  
  // public function groupes(){
    
  //   return view('pages.inscriptions.groupes');
  // } 


  
}
