<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Users;
use App\Models\Matieres;
use App\Models\Clasmat;
use Illuminate\Support\Facades\Log;

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
        $coefficients = $request->input('coefficients', []);
        $fondamentales = $request->input('fondamentale', []);

        // Logs de débogage
        Log::info('Coefficients reçus:', $coefficients);
        Log::info('Fondamentales reçus:', $fondamentales);

        if (empty($coefficients)) {
            return redirect()->back()->with('error', 'Aucune donnée de coefficient reçue.');
        }

        foreach ($coefficients as $codeclas => $matieres) {
            foreach ($matieres as $codemat => $coef) {
                $isFondamentale = isset($fondamentales[$codeclas][$codemat]) ? (int)$fondamentales[$codeclas][$codemat] : 0;
                
                Log::info("Traitement: CODECLAS=$codeclas, CODEMAT=$codemat, COEF=$coef, FONDAMENTALE=$isFondamentale");

                if ($coef !== null && $coef !== '') {
                    // Vérifier si l'enregistrement existe déjà
                    $clasmat = Clasmat::where('CODECLAS', $codeclas)
                                    ->where('CODEMAT', $codemat)
                                    ->first();

                    if ($clasmat) {
                        // Mettre à jour l'enregistrement existant
                        $clasmat->COEF = $coef;
                        $clasmat->FONDAMENTALE = $isFondamentale;
                        $clasmat->save();
                        Log::info("Mis à jour: ID={$clasmat->id}, FONDAMENTALE={$clasmat->FONDAMENTALE}");
                    } else {
                        // Créer un nouvel enregistrement
                        $newClasmat = new Clasmat();
                        $newClasmat->CODECLAS = $codeclas;
                        $newClasmat->CODEMAT = $codemat;
                        $newClasmat->COEF = $coef;
                        $newClasmat->FONDAMENTALE = $isFondamentale;
                        $newClasmat->save();
                        Log::info("Créé: CODECLAS={$codeclas}, CODEMAT={$codemat}, FONDAMENTALE={$isFondamentale}");
                    }
                } else {
                    // Si le champ est vidé, on supprime l'enregistrement existant.
                    Clasmat::where('CODECLAS', $codeclas)->where('CODEMAT', $codemat)->delete();
                    Log::info("Supprimé: CODECLAS={$codeclas}, CODEMAT={$codemat}");
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