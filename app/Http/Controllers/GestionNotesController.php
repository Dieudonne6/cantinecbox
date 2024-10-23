<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Users;
use App\Models\Matieres;
use App\Models\Clasmat;

class GestionNotesController extends Controller
{
    public function repartitionclassesparoperateur() {
        $users = Users::all();
        return view('pages.gestionnotes.repartitionclassesparoperateur', compact('users'));
    }

    public function gestioncoefficient() {
        $listeMatieres = Matieres::get();
        $listeClasses = Classe::get();
        return view('pages.notes.tabledesCoefficients')->with('listeMatieres',$listeMatieres ) ->with('listeClasses', $listeClasses);

    }

    public function enregistrerCoefficient(Request $request)
    {
        $coefficients = json_decode($request->input('coefficients'), true);
        // dd($coefficients);
foreach ($coefficients as $classId => $matieres) {
    foreach ($matieres as $matiereId => $data) {
        // Supprimer les ".00" de la valeur du coefficient
        $coefficientValue = $data['value'];
        
        // Vérifier si la classe et la matière existent déjà dans la table 'clasmat'
        $clasmat = Clasmat::where('CODECLAS', $classId)
                          ->where('CODEMAT', $matiereId) // Ajout de la vérification par matière
                          ->first();
        
        if ($clasmat ) {
            // dd($classId);
            // Si l'enregistrement existe, mettez à jour le coefficient et la colonne 'fondamentale'
                $clasmat->COEF = $coefficientValue;
                $clasmat->FONDAMENTALE = ($data['color'] == 'red') ? 1 : 0;
                $clasmat->update();
                
            // $clasmat->update([
            //     'COEF' => $coefficientValue,
            //     'FONDAMENTALE' => ($data['color'] == 'red') ? 1 : 0,
            // ]);
            // dd($coefficientValue);
        } else {
            // dd("kokooooo");
            // Si l'enregistrement n'existe pas, vous pouvez en créer un nouveau
            Clasmat::create([
                'CODECLAS' => $classId,
                'CODEMAT' => $matiereId,
                'COEF' => $coefficientValue,
                'FONDAMENTALE' => ($data['color'] == 'red') ? 1 : 0,
                // Ajoutez d'autres champs nécessaires ici
            ]);
        }
    }
}

        return redirect()->back()->with('status',  'Coefficients sauvegardés avec succès');
    }
    
}
