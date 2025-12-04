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
//    public function destroy($matricule)
//    {
//        // Rechercher l'élève par son matricule
//        $eleve = Eleve::where('MATRICULE', $matricule)->first();
//        $eleveClasse = $eleve->CODECLAS;
//        $eleve->CODECLAS = 'DELETE';
//        $eleve->ANCCLASSE = $eleveClasse;
//        $eleve->update();


//        return redirect()->back()->with('status', 'Élève supprimé avec succès.');


//    }

public function destroy($matricule)
   {
       // Rechercher l'élève par son matricule
       $eleve = Eleve::where('MATRICULE', $matricule)->first();

       // Vérifier si l'élève a été trouvé
       if (!$eleve) {
           return redirect()->back()->with('error', 'Élève non trouvé.');
       }

       // Sauvegarder la classe actuelle avant de la modifier
       $eleveClasse = $eleve->CODECLAS;

       // Appliquer la logique de "soft delete"
       $eleve->CODECLAS = 'DELETE';
       $eleve->ANCCLASSE = $eleveClasse;
       $eleve->update();

       // Rediriger avec un message de succès
       return redirect()->back()->with('status', 'Élève supprimé avec succès.');
   }

}
