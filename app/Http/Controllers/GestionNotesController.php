<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Users;
use App\Models\Matieres;
use App\Models\Clasmat;

class GestionNotesController extends Controller
{
    public function repartitionclassesparoperateur() {
        $users = Users::select('login')->get();
        $classes = Classes::select('CODECLAS', 'SIGNATURE')->get(); // Ajout de 'SIGNATURE'
        $classes_operateur = Classes::select('CODECLAS', 'SIGNATURE')->where('SIGNATURE', '!=', null)->get();
        return view('pages.notes.repartitionclassesparoperateur', compact('users', 'classes', 'classes_operateur'));
    }
    public function repartitionclassesoperateur(Request $request) {
        $classes = Classes::select('CODECLAS', 'SIGNATURE')->get(); // Ajout de 'SIGNATURE'
        foreach ($request->login as $codeclas => $signature) {
            Classes::where('CODECLAS', $codeclas)->update(['SIGNATURE' => $signature]);
        }
        return redirect()->route('repartitionclassesparoperateur');
    }

    public function gestioncoefficient() {
        $listeMatieres = Matieres::get();
        $listeClasses = Classes::get();
        $coefficients = Clasmat::all()->keyBy(function($item) {
            return $item->CODECLAS . '-' . $item->CODEMAT;
        });
    
        return view('pages.notes.tabledesCoefficients')
            ->with('listeMatieres', $listeMatieres)
            ->with('listeClasses', $listeClasses)
            ->with('coefficients', $coefficients);
    }


    public function enregistrerCoefficient(Request $request)
    {
        // Récupération et décodage du JSON des coefficients
        $coefficients = json_decode($request->input('coefficients'), true);
        
        // Vérifier si les coefficients sont bien envoyés
        if (empty($coefficients)) {
            return back()->withErrors(['message' => 'Aucun coefficient à sauvegarder.']);
        }
    
        // Boucler à travers chaque classe
        foreach ($coefficients as $classId => $matieres) {
            // Boucler à travers chaque matière de la classe
            foreach ($matieres as $matiereId => $data) {
                // Vérifier que les valeurs existent et ne sont pas nulles
                $value = isset($data['value']) ? $data['value'] : 0;  // Définit 0 si la valeur est vide
    
                // Convertir la couleur "red" en 1 et "default" en 0
                $isFondamentale = ($data['color'] === 'red') ? 1 : 0;
    
                // Assurez-vous que la valeur n'est pas vide ou incorrecte avant la sauvegarde
                if (is_numeric($value)) {
                    // Rechercher manuellement l'enregistrement pour la combinaison CODECLAS et CODEMAT
                    $clasmat = Clasmat::where('CODECLAS', $classId)
                        ->where('CODEMAT', $matiereId)
                        ->first();
    
                    if ($clasmat) {
                        // Mise à jour de l'enregistrement existant
                        $clasmat->update([
                            'COEF' => $value,
                            'FONDAMENTALE' => $isFondamentale
                        ]);
                    } else {
                        // Création d'un nouvel enregistrement si non trouvé
                        Clasmat::create([
                            'CODECLAS' => $classId,
                            'CODEMAT' => $matiereId,
                            'COEF' => $value,
                            'FONDAMENTALE' => $isFondamentale
                        ]);
                    }
                } else {
                    return back()->withErrors(['message' => "Valeur non valide pour la matière ID: $matiereId de la classe ID: $classId."]);
                }
            }
        }
    
        return redirect()->back()->with('status', 'Coefficients sauvegardés avec succès.');
    }
    


    // public function enregistrerCoefficient(Request $request)
    // {
    //     // $coefficients = $request->input('coefficients');

    //     $coefficients = json_decode($request->input('coefficients'), true);
    //     dd($coefficients);
    //     foreach ($coefficients as $classId => $matieres) {
    //         foreach ($matieres as $matiereId => $data) {
    //             $coefficientValue = $data['value'];
                
    //             // Vérifier si la classe et la matière existent déjà dans la table 'clasmat'
    //             $clasmat = Clasmat::where('CODECLAS', $classId)
    //                             ->where('CODEMAT', $matiereId) // Ajout de la vérification par matière
    //                             ->first();
                
    //             if ($clasmat ) {
    //                 // Si l'enregistrement existe, mettez à jour le coefficient et la colonne 'fondamentale'
    //                     $clasmat->COEF = $coefficientValue;
    //                     $clasmat->FONDAMENTALE = ($data['color'] == 'red') ? 1 : 0;
    //                     $clasmat->update();
    //             } else {
    //                 // Si l'enregistrement n'existe pas, vous pouvez en créer un nouveau
    //                 Clasmat::create([
    //                     'CODECLAS' => $classId,
    //                     'CODEMAT' => $matiereId,
    //                     'COEF' => $coefficientValue,
    //                     'FONDAMENTALE' => ($data['color'] == 'red') ? 1 : 0,
    //                     // Ajoutez d'autres champs nécessaires ici
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->back()->with('status',  'Coefficients sauvegardés avec succès');
    // }
    
}
