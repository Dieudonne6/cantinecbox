<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Serie;
use Illuminate\Http\Request;
use App\Models\Typeclasse;

class GestionclasseController extends Controller
{
      public function groupes(){
        
        return view('pages.inscriptions.groupes');
    }

    public function series(Request $request){
      $series = Serie::get();
      return view ('pages.inscriptions.series')->with('series', $series);
  }

  public function saveserie(Request $request){
    $series = new Serie();
    $series->SERIE = $request->input('SERIE');
    $series->LIBELSERIE = $request->input('LIBELSERIE');
    $series->CYCLE = $request->input('CYCLE');
    $series->save();
    return back()->with('status', 'Enregistrer avec succès');
  }

  public function updateserie(Request $request){

    $series = Serie::where('SERIE', $request->input('SERIE'))->first();
    if ($series) {
      $series->SERIE = $request->input('SERIE');
      $series->LIBELSERIE = $request->input('LIBELSERIE');
      $series->CYCLE = $request->input('CYCLE');
      $series->save();
      
      return back()->with('status', 'Modifié avec succès');
    }

    return back()->withErrors('Erreur lors de la modification.');

  }

  
  public function savetypeclasse(Request $request){
    $typeclasse = new Typeclasse();
    $typeclasse->TYPECLASSE = $request->input('TYPECLASSE');
    $typeclasse->LibelleType = $request->input('LibelleType');
    $typeclasse->OuiNonScolarite = 1;
    $typeclasse->OuiNonNotes = 1;
    $typeclasse->OuiNonStat = 1;
    $typeclasse->save();
    return back()->with('status','Enregistrer avec succes');
  }
  public function getclasse(){
    $allclasse = Typeclasse::get();
    return view('pages.inscriptions.typesclasses')->with('allclasse', $allclasse);   
  }
  public function updateTypeClasse(Request $request)
  {
    $typeclass = Typeclasse::where('TYPECLASSE', $request->input('TYPECLASSE'))->first();
    if ($typeclass) {
      $typeclass->TYPECLASSE = $request->input('TYPECLASSE');
      $typeclass->LibelleType = $request->input('LibelleType');
      $typeclass->save();
      
      return back()->with('status', 'Modifié avec succès');
    }
    
    return back()->withErrors('Erreur lors de la modification.');
  }

}
