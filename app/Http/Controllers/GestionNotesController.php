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
        return redirect()->route('repartitionclassesparoperateur')->with('status', 'Répartition des classes par opérateur effectuée avec succès.');
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
        // dd($coefficients);
        // Vérifier si les coefficients sont bien envoyés
        if (empty($coefficients)) {
            return redirect()->back()->with('status', 'Aucun coefficient à sauvegarder.');
        }
        // Boucler à travers chaque classe
        foreach ($coefficients as $classId => $matieres) {
            // Boucler à travers chaque matière de la classe
            foreach ($matieres as $matiereId => $data) {
                // Vérifier que les valeurs existent et ne sont pas nulles
                if (isset($data['value']) && $data['value'] !== '') {
                    $value = $data['value'];
                    // Convertir la couleur "red" en 1 et "default" en 0
                    $isFondamentale = ($data['color'] === 'red') ? 1 : 0;
    
                    // Vérifier si la valeur a changé par rapport à sa valeur initiale avant de sauvegarder
                    $clasmat = Clasmat::where('CODECLAS', $classId)
                                       ->where('CODEMAT', $matiereId)
                                       ->first();
                    
                    if ($clasmat && ($clasmat->COEF != $value || $clasmat->FONDAMENTALE != $isFondamentale)) {
                        // Mise à jour de l'enregistrement existant si la valeur a changé
                        $clasmat->update([
                            'COEF' => $value,
                            'FONDAMENTALE' => $isFondamentale
                        ]);
                    } elseif (!$clasmat) {
                        // Création d'un nouvel enregistrement si non trouvé

                        Clasmat::create([
                            'CODECLAS' => $classId,
                            'CODEMAT' => $matiereId,
                            'COEF' => $value,
                            'FONDAMENTALE' => $isFondamentale
                        ]);
                    }
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
