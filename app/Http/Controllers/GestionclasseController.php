<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typeclasse;

class GestionclasseController extends Controller
{
  
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
  
  public function groupes(){
    
    return view('pages.inscriptions.groupes');
  } 
}
