<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;

class EleveController extends Controller
{
    public function inscription(){
        return view('pages.inscriptions.inscrireeleve');
    }
    
    // Méthode pour supprimer un élève
   public function destroy($matricule)
   {
       // Rechercher l'élève par son matricule
       $eleve = Eleve::where('MATRICULE', $matricule)->first();

       if ($eleve) {
           // Supprimer l'élève
           $eleve->delete();

           return redirect()->back()->with('status', 'Élève supprimé avec succès.');
       }

       return redirect()->back()->with('status', 'Élève non trouvé.');
   }

}
