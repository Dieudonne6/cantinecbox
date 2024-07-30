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
}
