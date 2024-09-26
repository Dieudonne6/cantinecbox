<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ClasseController;
use Carbon\Carbon;

use App\Models\Compte;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Params2;
use App\Models\Echeancec;
use App\Models\Echeance;
use App\Models\Reduction;
use App\Models\Paramcontrat;

class ScolariteController extends Controller
{
    //
    

    public function getparamcomposantes(){
        $comptes = Compte::get();

        return view ('pages.inscriptions.paramcomposantes')->with('comptes', $comptes);
    }

    public function getfacturesclasses(){

        $factures = DB::table('classes')
            ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            //  ->join('typeenseigne', 'classes.TYPEENSEIG', '=', 'typeenseigne.idenseign')
            ->select(
                'classes.*',
                'typeenseigne.type as typeenseigne_type',
                )
            ->get();

        return view ('pages.inscriptions.facturesclasses', compact('factures'));
    }

    public function detailfacturesclasses($CODECLAS) {

        $donneClasse = Classe::where('CODECLAS', $CODECLAS)->first();
        $donneLibelle = Params2::first();
        // dd($donneClasse);
        return view('pages.inscriptions.factureclassesdetail', compact('CODECLAS', 'donneClasse', 'donneLibelle'));
    }

    public function detailfacclasse (Request $request, $CODECLAS ) {

                    // Récupérer les données des échéances envoyées sous forme de JSON
                    $echeancesData = $request->input('echeancesData');
                    $echeances = json_decode($echeancesData, true);
        
                    // dd($echeances);
                        // Recuperer la classe et supprimer les lignes exixtantes dans echeancec
                        $classe = $request->input('classe'); // Récupérer la classe concernée à partir de la requête

                        // dd($classe);
                        // Vérifier si des lignes existent déjà pour cette classe
                        $existeLignes = DB::table('echeancc')->where('CODECLAS', $classe)->exists();

                        if ($existeLignes) {
                            // Si des lignes existent pour cette classe, on les supprime
                            DB::table('echeancc')->where('CODECLAS', $classe)->delete();
                        }


                    foreach ($echeances as $echeance) {
                        $dateFormat = 'd/m/Y'; // Le format d'origine de la date
                        $dateOriginal = $echeance['date_paiement'];
                        // dd($dateOriginal);
                        // Convertir la date en format Carbon
                        $date = Carbon::createFromFormat($dateFormat, $dateOriginal);

                        // Reformater la date au format souhaité 'Y-m-d'
                        $dateFormater = $date->format('Y-m-d');

                        // Insérer chaque ligne dans la base de données ou la traiter comme vous le souhaitez
                        DB::table('echeancc')->insert([
                            'NUMERO' => $echeance['tranche'],
                            'FRACTION1' => $echeance['pourcentage_nouveau'],
                            'FRACTION2' => $echeance['pourcentage_ancien'],
                            'APAYER' => $echeance['montant_nouveau'],
                            'APAYER2' => $echeance['montant_ancien'],
                            'DATEOP' => $dateFormater,
                            'DATEOP2' => $dateFormater,
                            'CODECLAS' => $echeance['classe'],
                            // 'created_at' => now(),
                            // 'updated_at' => now()
                        ]);
                    }

                        // dd($request->input('flexRadioDefault'));


        // ENREGISTREMENT OU MODIFICATION DES VALEURS DANS LA TABLE CLASSE
        $classeCorrespondante = Classe::where('CODECLAS', $CODECLAS)->first();

        $classeCorrespondante->DUREE = $request->input('nbEcheances');
        $classeCorrespondante->PERIODICITE = $request->input('periodicite');
        $classeCorrespondante->DATEDEB = $request->input('dateDebut');
        $classeCorrespondante->TYPEECHEANCIER = $request->input('flexRadioDefault');


        // dd($request->input('dateDebut'));
        $classeCorrespondante->APAYER = $request->input('APAYER');
        $classeCorrespondante->APAYER2 = $request->input('APAYER2');

        $classeCorrespondante->FRAIS1 = $request->input('FRAIS1');
        $classeCorrespondante->FRAIS1_A = $request->input('FRAIS1_A');

        $classeCorrespondante->FRAIS2 = $request->input('FRAIS2');
        $classeCorrespondante->FRAIS2_A = $request->input('FRAIS2_A');

        $classeCorrespondante->FRAIS3 = $request->input('FRAIS3');
        $classeCorrespondante->FRAIS3_A = $request->input('FRAIS3_A');

        $classeCorrespondante->FRAIS4 = $request->input('FRAIS4');
        $classeCorrespondante->FRAIS4_A = $request->input('FRAIS4_A');

        $classeCorrespondante->save();

        // ENREGISTREMENT OU MODIFICATION DES VALEURS DANS LA TABLE ELEVE

        $listeEleveDeLaClasses = Eleve::where('CODECLAS', $CODECLAS)->get();
        // dd($listeEleveDeLaClasses);

        foreach($listeEleveDeLaClasses as $chaqueEleve){
            // dd($chaqueEleve->STATUTG);
            $infoReduction = Reduction::where('CodeReduction', $chaqueEleve->CodeReduction)->first();
            if($chaqueEleve->STATUTG == 2) {
                $nApayer_a = $request->input('APAYER2');
                $nfrais1_a = $request->input('FRAIS1_A');
                $nfrais2_a = $request->input('FRAIS2_A');
                $nfrais3_a = $request->input('FRAIS3_A');
                $nfrais4_a = $request->input('FRAIS4_A');
                if ($infoReduction->typereduction === 'P') {
                    $chaqueEleve->FRAIS1 = $nfrais1_a - ($nfrais1_a * $infoReduction->Reduction_frais1);
                    $chaqueEleve->FRAIS2 = $nfrais2_a - ($nfrais2_a * $infoReduction->Reduction_frais2);
                    $chaqueEleve->FRAIS3 = $nfrais3_a - ($nfrais3_a * $infoReduction->Reduction_frais3);
                    $chaqueEleve->FRAIS4 = $nfrais4_a - ($nfrais4_a * $infoReduction->Reduction_frais4);
                    $chaqueEleve->APAYER = $nApayer_a - ($nApayer_a * $infoReduction->Reduction_scolarite);
                    $chaqueEleve->save();
                  } else if ($infoReduction->typereduction === 'F') {
                    $chaqueEleve->FRAIS1 = $nfrais1_a -  $infoReduction->Reduction_fixe_frais1;
                    $chaqueEleve->FRAIS2 = $nfrais2_a -  $infoReduction->Reduction_fixe_frais2;
                    $chaqueEleve->FRAIS3 = $nfrais3_a -  $infoReduction->Reduction_fixe_frais3;
                    $chaqueEleve->FRAIS4 = $nfrais4_a -  $infoReduction->Reduction_fixe_frais4;
                    $chaqueEleve->APAYER = $nApayer_a -  $infoReduction->Reduction_fixe_sco;
                    $chaqueEleve->save();
                  }


                // $chaqueEleve->APAYER = $request->input('APAYER2');
                // $chaqueEleve->FRAIS1 = $request->input('FRAIS1_A');
                // $chaqueEleve->FRAIS2 = $request->input('FRAIS2_A');
                // $chaqueEleve->FRAIS3 = $request->input('FRAIS3_A');
                // $chaqueEleve->FRAIS4 = $request->input('FRAIS4_A');
                // $chaqueEleve->save();
            } elseif (($chaqueEleve->STATUTG == 1)) {
                $nApayer = $request->input('APAYER');
                $nfrais1 = $request->input('FRAIS1');
                $nfrais2 = $request->input('FRAIS2');
                $nfrais3 = $request->input('FRAIS3');
                $nfrais4 = $request->input('FRAIS4');
                // dd($infoReduction->typereduction);
                if ($infoReduction->typereduction === 'P') {
                    $chaqueEleve->FRAIS1 = $nfrais1 - ($nfrais1 * $infoReduction->Reduction_frais1);
                    $chaqueEleve->FRAIS2 = $nfrais2 - ($nfrais2 * $infoReduction->Reduction_frais2);
                    $chaqueEleve->FRAIS3 = $nfrais3 - ($nfrais3 * $infoReduction->Reduction_frais3);
                    $chaqueEleve->FRAIS4 = $nfrais4 - ($nfrais4 * $infoReduction->Reduction_frais4);
                    $chaqueEleve->APAYER = $nApayer - ($nApayer * $infoReduction->Reduction_scolarite);
                    $chaqueEleve->save();
                  } else if ($infoReduction->typereduction === 'F') {
                    $chaqueEleve->FRAIS1 = $nfrais1 - $infoReduction->Reduction_fixe_frais1;
                    $chaqueEleve->FRAIS2 = $nfrais2 -  $infoReduction->Reduction_fixe_frais2;
                    $chaqueEleve->FRAIS3 = $nfrais3 -  $infoReduction->Reduction_fixe_frais3;
                    $chaqueEleve->FRAIS4 = $nfrais4 -  $infoReduction->Reduction_fixe_frais4;
                    $chaqueEleve->APAYER = $nApayer -  $infoReduction->Reduction_fixe_sco;
                    $chaqueEleve->save();
                  }
                // $chaqueEleve->APAYER = $request->input('APAYER');
                // $chaqueEleve->FRAIS1 = $request->input('FRAIS1');
                // $chaqueEleve->FRAIS2 = $request->input('FRAIS2');
                // $chaqueEleve->FRAIS3 = $request->input('FRAIS3');
                // $chaqueEleve->FRAIS4 = $request->input('FRAIS4');
                // $chaqueEleve->save();

            }

        }


        // NOUVELLE BOUCLE SUR LES ELEVES DE CETTE CLASSE POUR POUVOIR ENREGISTRER LES DONNE DANS ECHEANCE

        $listeEleveDeLaClasses1 = Eleve::where('CODECLAS', $CODECLAS)->get();

        // Parcourir chaque élève de la classe
        // foreach ($listeEleveDeLaClasses1 as $eleve) {
        //     // Récupérer les informations de réduction de l'élève (si applicable)
        //     $reduction = $eleve->CodeReduction; // Adaptez le nom de la colonne si nécessaire
        //     $infoReduction = Reduction::where('CodeReduction', $reduction)->first();
        //     dd($infoReduction);
        //     // Calculer le montant de l'échéance en tenant compte de la réduction
        //     $montantTotal = 1000; // Exemple de montant total
        //     $montantApresReduction = $montantTotal - ($montantTotal * $reduction / 100);

        //     // Insérer les données dans la table echeance
        //     DB::table('echeance')->insert([
        //         'MATRICULE' => $eleve->MATRICULE,
        //         'NOM' => $eleve->NOM,
        //         'CODECLAS' => $CODECLAS,
        //         'MONTANT' => $montantApresReduction,
        //         'DATEOP' => Carbon::now(), // Date actuelle pour l'opération
        //         'FRACTION' => 1, // Exemple de fraction, ajustez si nécessaire
        //         'DATEECHEANCE' => Carbon::now()->addMonths(1), // Exemple de date d'échéance, ajustez selon vos besoins
        //     ]);
        // }


        foreach ($listeEleveDeLaClasses1 as $eleve) {
            // Suppression des anciennes échéances de l'élève
            Echeance::where('MATRICULE', $eleve->MATRICULE)->delete();
            $frais1 = $eleve->FRAIS1;
            $frais2 = $eleve->FRAIS2;
            $frais3 = $eleve->FRAIS3;
            $frais4 = $eleve->FRAIS4;
            $sco = $eleve->APAYER;
            $typeduree = intval($request->input('nbEcheances'));
            $typeecheance = intval($request->input('flexRadioDefault'));

            $infoparamcontrat = Paramcontrat::first();
            $anneencours = $infoparamcontrat->anneencours_paramcontrat;
            $annesuivante = $anneencours + 1;
            $annescolaire = $anneencours.'-'.$annesuivante;

            // dd($eleve->MATRICULE);
            if ($eleve->STATUG == 1) {
              if ($typeecheance == 2) {
                $total = $sco + $frais1 + $frais2 + $frais3 + $frais4;
                $montantparecheance = $total / $typeduree;
    
                // Insérer les nouvelles échéances
                // for ($i = 1; $i <= $typeduree; $i++) {
                  foreach ($echeances as $index => $echeanceData) {
                  Echeance::create([
                      'MATRICULE' => $eleve->MATRICULE,
                      'NUMERO' => $echeanceData['tranche'], // Numérotation des échéances
                      'APAYER' => $total * $echeanceData['pourcentage_nouveau'],
                      'ARRIERE' => $eleve->ARRIERE, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                      'DATEOP' => Carbon::createFromFormat('d/m/Y', $echeanceData['date_paiement'])->toDateString(), // Date spécifique de l'échéance,
                      'anneeacademique' => $annescolaire,
                  ]);
                }
    
              } else {
                $total = $sco;
                $montantparecheance = $total / $typeduree;
                // dd($typeduree);
    
                // Insérer les nouvelles échéances
                // for ($i = 1; $i <= $typeduree; $i++) {
                  foreach ($echeances as $index => $echeanceData) {
                  Echeance::create([
                      'MATRICULE' => $eleve->MATRICULE,
                      'NUMERO' => $echeanceData['tranche'], // Numérotation des échéances
                      'APAYER' => $total * $echeanceData['pourcentage_nouveau'],
                      'ARRIERE' => $eleve->ARRIERE, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                      'DATEOP' => Carbon::createFromFormat('d/m/Y', $echeanceData['date_paiement'])->toDateString(), // Date spécifique de l'échéance,
                      'anneeacademique' => $annescolaire,
                  ]);
                }
              }
            } else {
              if ($typeecheance == 2) {
                $total = $sco + $frais1 + $frais2 + $frais3 + $frais4;
                $montantparecheance = $total / $typeduree;
    
                // Insérer les nouvelles échéances
                // for ($i = 1; $i <= $typeduree; $i++) {
                  foreach ($echeances as $index => $echeanceData) {
                  Echeance::create([
                      'MATRICULE' => $eleve->MATRICULE,
                      'NUMERO' => $echeanceData['tranche'], // Numérotation des échéances
                      'APAYER' => $total * $echeanceData['pourcentage_ancien'],
                      'ARRIERE' => $eleve->ARRIERE, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                      'DATEOP' => Carbon::createFromFormat('d/m/Y', $echeanceData['date_paiement'])->toDateString(), // Date spécifique de l'échéance,
                      'anneeacademique' => $annescolaire,
                  ]);
                }
    
              } else {
                $total = $sco;
                $montantparecheance = $total / $typeduree;
                // dd($typeduree);
    
                // Insérer les nouvelles échéances
                // for ($i = 1; $i <= $typeduree; $i++) {
                  foreach ($echeances as $index => $echeanceData) {
                  Echeance::create([
                      'MATRICULE' => $eleve->MATRICULE,
                      'NUMERO' => $echeanceData['tranche'], // Numérotation des échéances
                      'APAYER' => $total * $echeanceData['pourcentage_ancien'],
                      'ARRIERE' => $eleve->ARRIERE, // Tu peux ajouter ici la logique pour gérer les arriérés si nécessaire
                      'DATEOP' => Carbon::createFromFormat('d/m/Y', $echeanceData['date_paiement'])->toDateString(), // Date spécifique de l'échéance,
                      'anneeacademique' => $annescolaire,
                  ]);
                }
              }            
            }

        }



    // foreach ($listeEleveDeLaClasses1 as $eleve) {
    //     // GESTION DE ECHEANCE POUR LES ELEVES DE LA CLASSE

    //     // dd($eleve->MATRICULE);
    //     $echeances = Echeance::where('MATRICULE', $eleve->MATRICULE)->get();
    //     $reduc = $eleve->CodeReduction;
    //     $eleveClasse = $eleve->CODECLAS;
    //     $infoReduc = Reduction::where('CodeReduction', $reduc)->first();
    //     $typemode = $infoReduc->mode;
    //     $frais1 = $eleve->FRAIS1;
    //     $frais2 = $eleve->FRAIS2;
    //     $frais3 = $eleve->FRAIS3;
    //     $frais4 = $eleve->FRAIS4;
    //     $sco = $eleve->APAYER;
    //     $arriere = $eleve->ARRIERE;
    //     $typeecheanc = $request->input('flexRadioDefault');
    //     $typeecheance = intval($typeecheanc);
    //     $modifiecheancc = Echeancec::where('CODECLAS', $eleveClasse)->orderBy('NUMERO', 'desc')->get();
    //     $type = $eleve->STATUTG;
    //     $typeduree = $request->input('nbEcheances');
    //     // dd($reduc);


    //     // Vous pouvez ensuite utiliser $echeances pour chaque élève
    //       if ($reduc == 0) {
    //         foreach ($modifiecheancc as $echeancc) {
    //             // Traitez ou affichez les données d'échéances ici
    //             if ($type == 1) {
    //               $montant = $echeancc->APAYER; // Utiliser la colonne APAYER si type est 1
    //             } else {
    //               $montant = $echeancc->APAYER2; // Utiliser la colonne APAYER2 si type est 2
    //             }
    //             Echeance::where('NUMERO', $echeancc->NUMERO)
    //             ->update(['APAYER' => $montant]);

    //         }
    //       }
    //       else {
    //         // dd($typeecheance);
    //         if($typeecheance == 2){
    //           $total = $sco + $frais1 + $frais2+ $frais3 + $frais4 + $arriere;
    //           if($typemode == 2) {
    //             $montantecheance = $total / $typeduree;
    //             // dd($montantecheance);
    //             // Mettre à jour toutes les échéances avec ce montant
    //             foreach ($echeances as $echeance) {
    //               // Mettre à jour la colonne APAYER avec le montant calculé
    //               Echeance::where('NUMERO', $echeance->NUMERO)
    //               ->update(['APAYER' => $montantecheance]);
    //             }            
    //           } else {
    //             foreach ($modifiecheancc as $echeance) {
    //               $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;

    //               if ($montant_a_payer <= $total) {
    //                   $total -= $montant_a_payer;
    //                   Echeance::where('NUMERO', $echeance->NUMERO)
    //                       ->update(['APAYER' => 0]);
    //               } else {
    //                   Echeance::where('NUMERO', $echeance->NUMERO)
    //                       ->update(['APAYER' => $montant_a_payer - $total]);
    //                   $total = 0; // Stopper une fois que le total est atteint
    //                   break;
    //               }
    //             }
    //           }
    //         } else {
    //             // dd($typemode);
    //           if($typemode == 2) {
    //             $montantecheance = $sco / $typeduree;
    //             dd($montantecheance);
    //             // Mettre à jour toutes les échéances avec ce montant
                
    //             foreach ($echeances as $echeance) {
    //               // Mettre à jour la colonne APAYER avec le montant calculé
    //               Echeance::where('NUMERO', $echeance->NUMERO)
    //               ->update(['APAYER' => $montantecheance]);
    //             }            
    //           } else {
    //             // dd($type);
    //               foreach ($modifiecheancc as $echeance) {
    //                 $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;
    //                 dd($sco);
    //                 if ($montant_a_payer <= $sco) {
    //                     $sco -= $montant_a_payer;
    //                     Echeance::where('NUMERO', $echeance->NUMERO)
    //                         ->update(['APAYER' => 0]);
    //                 } else {
    //                     Echeance::where('NUMERO', $echeance->NUMERO)
    //                         ->update(['APAYER' => $montant_a_payer - $sco]);
    //                     $sco = 0; // Stopper une fois que le total est atteint
    //                     break;
    //                 }
    //               }
    //           }
    //         }
    //       }

    //           // Echeance::where('NUMERO', 1)
    //           //  ->update(['ARRIERE' => $arriere]);

    // }

    return back()->with('status', 'enregistrement effectuer avec succes');

}


// public function modifieprofil(Request $request, $MATRICULE) {
//     $modifiprofil = Eleve::find($MATRICULE);
//     $classe = $request->input('classe');
//     $typeechean = Classes::where('CODECLAS', $classe)->first();
//     $typeecheance = $typeechean->TYPEECHEANCIER;
//     $typeduree = $typeechean->DUREE;
    
//     $type = $request->input('type');
//     $reduc = $request->input('reduction');
//     $modifiecheances = Echeance::where('MATRICULE', $MATRICULE)->orderBy('NUMERO', 'desc')->get();
//     $typemode = Reduction::where('CodeReduction', $reduc)->value('mode');
//     $sco = $request->input('sco');
//     $dure = $request->input('duree');
//     $frais1 =  $request->input('frais1');
//     $frais2 =  $request->input('frais2');
//     $frais3 =  $request->input('frais3');
//     $frais4 =  $request->input('frais4');
//     $arriere = $request->input('arriere');
//     $modifiecheancc = Echeancc::where('CODECLAS', $classe)->orderBy('NUMERO', 'desc')->get();
    
//     if ($reduc == 0) {
//       foreach ($modifiecheancc as $echeancc) {
//         if ($type == 1) {
//           $montant = $echeancc->APAYER; // Utiliser la colonne APAYER si type est 1
//         } else {
//           $montant = $echeancc->APAYER2; // Utiliser la colonne APAYER2 si type est 2
//         }
//         Echeance::where('NUMERO', $echeancc->NUMERO)
//         ->update(['APAYER' => $montant]);
//       }
//     }
//     else {
//       if($typeecheance == 2){
//         $total = $sco + $frais1 + $frais2+ $frais3 + $frais4 + $arriere;
//         if($typemode == 2) {
//           $montantecheance = $total / $typeduree;
//           // Mettre à jour toutes les échéances avec ce montant
//           foreach ($modifiecheances as $echeance) {
//             // Mettre à jour la colonne APAYER avec le montant calculé
//             Echeance::where('NUMERO', $echeance->NUMERO)
//             ->update(['APAYER' => $montantecheance]);
//           }            
//         } else {
//           foreach ($modifiecheancc as $echeance) {
//               $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;

//               if ($montant_a_payer <= $total) {
//                   $total -= $montant_a_payer;
//                   Echeance::where('NUMERO', $echeance->NUMERO)
//                       ->update(['APAYER' => 0]);
//               } else {
//                   Echeance::where('NUMERO', $echeance->NUMERO)
//                       ->update(['APAYER' => $montant_a_payer - $total]);
//                   $total = 0; // Stopper une fois que le total est atteint
//                   break;
//               }
//           }
//         }
//       } else {
//         if($typemode == 2) {
//           $montantecheance = $sco / $typeduree;
//           // Mettre à jour toutes les échéances avec ce montant
          
//           foreach ($modifiecheances as $echeance) {
//             // Mettre à jour la colonne APAYER avec le montant calculé
//             Echeance::where('NUMERO', $echeance->NUMERO)
//             ->update(['APAYER' => $montantecheance]);
//           }            
//         } else {
          
//             foreach ($modifiecheancc as $echeance) {
//               $montant_a_payer = ($type == 1) ? $echeance->APAYER : $echeance->APAYER2;

//               if ($montant_a_payer <= $sco) {
//                   $sco -= $montant_a_payer;
//                   Echeance::where('NUMERO', $echeance->NUMERO)
//                       ->update(['APAYER' => 0]);
//               } else {
//                   Echeance::where('NUMERO', $echeance->NUMERO)
//                       ->update(['APAYER' => $montant_a_payer - $sco]);
//                   $sco = 0; // Stopper une fois que le total est atteint
//                   break;
//               }
//           }
//         }
//       }
//     }
    
//     $modifiprofil->CodeReduction = $request->input('reduction');
//     $modifiprofil->APAYER = $request->input('sco');
//     $modifiprofil->FRAIS1 = $request->input('frais1');
//     $modifiprofil->FRAIS2 = $request->input('frais2');
//     $modifiprofil->FRAIS3 = $request->input('frais3');
//     $modifiprofil->FRAIS4 = $request->input('frais4');
//     $modifiprofil->ARRIERE = $request->input('arriere');
//     Echeance::where('NUMERO', 1)
//     ->update(['ARRIERE' => $arriere]);
    
//     $modifiprofil->update();
//     return back()->with('status', 'Reduction modifier avec succes');
    
// }

}
