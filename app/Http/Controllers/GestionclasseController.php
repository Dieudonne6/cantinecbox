<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typeclasse;
use App\Models\Promo;
use App\Models\Eleve;

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
  public function groupes(){
    
    return view('pages.inscriptions.groupes');
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

}
