<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\Params2;
use App\Models\Eleve;
use App\Models\Params2;
use App\Models\DecisionConfigAnnuel;
use Illuminate\Support\Facades\DB;


class StatistiquesService
{
    protected function getGroupLabel($classe)
    {
        $mapPromosCycle1 = [
            '6EME' => '6è',
            '5EME' => '5è',
            '4EME' => '4è',
            '3EME' => '3è',
        ];

        $mapPromosCycle2 = [
            '2ND'  => '2nd',
            '1ERE' => '1ère',
            'TLE'  => 'Tle',
        ];

        if ($classe->CYCLE == '1') {
            return $mapPromosCycle1[$classe->CODEPROMO] ?? $classe->CODEPROMO;
        } else {
            $promo = $mapPromosCycle2[$classe->CODEPROMO] ?? $classe->CODEPROMO;
            $serie = $classe->SERIE;
            return $promo . $serie;
        }
    }

    public function calculerStatistiques($data)
    {
        // 1. Choix de la colonne selon la période
        if ($data['periode'] == '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] == '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] == '3') {
            $colonne = 'MS3';
        } else {
            // Période “Annuel” → on lit directement la moyenne annuelle
            $colonne = 'MAN';
        }
    
        $classes = Classes::with('eleves', 'promo')->get();
        $resultats = [];
    
        // 2. Regroupement par label de groupe comme avant
        $groupes = $classes->groupBy(function ($classe) {
            return $this->getGroupLabel($classe);
        });
    
        foreach ($groupes as $groupeKey => $classesGroupe) {
            $elevesGroupe = collect();
    
            // 3. Fusion de tous les élèves valides dans le groupe
            foreach ($classesGroupe as $classe) {
                $elevesFiltres = $classe->eleves->filter(function ($eleve) use ($colonne) {
                    $note = $eleve->{$colonne};
                    return !is_null($note) 
                        && floatval($note) != -1 
                        && floatval($note) != 21;
                });
                $elevesGroupe = $elevesGroupe->merge($elevesFiltres);
            }
    
            // 4. On fixe directement la propriété moyenne à partir de la colonne
            $elevesAvecMoyennes = $elevesGroupe->map(function ($eleve) use ($colonne) {
                $eleve->moyenne = floatval($eleve->{$colonne});
                return $eleve;
            });
    
            // 5. Séparation garçons / filles et calcul des extrêmes
            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles  = $elevesAvecMoyennes->where('SEXE', '2');
    
            $maxMoyenneGarcons = $garcons->max('moyenne');
            $minMoyenneGarcons = $garcons->min('moyenne');
            $maxMoyenneFilles  = $filles->max('moyenne');
            $minMoyenneFilles  = $filles->min('moyenne');
    
            // 6. Répartition dans les intervalles telle que vous l'aviez déjà
            $intervalesResultats = $this->calculerIntervales(
                $elevesAvecMoyennes,
                $data['intervales']
            );
    
            // 7. Stockage du résultat
            $resultats[$groupeKey] = [
                'max_moyenne_garcons' => $maxMoyenneGarcons,
                'min_moyenne_garcons' => $minMoyenneGarcons,
                'max_moyenne_filles'  => $maxMoyenneFilles,
                'min_moyenne_filles'  => $minMoyenneFilles,
                'intervales'          => $intervalesResultats,
            ];
        }
    
        return $resultats;
    }
    

    protected function calculerIntervales($eleves, $intervales)
    {
        $resultat = [];
        foreach ($intervales as $intervalle => $valeurs) {
            $min = floatval($valeurs['min']);
            $max = floatval($valeurs['max']);

            // Si les deux valeurs sont 0, on considère quand même l'intervalle
            if ($min == 0 && $max == 0) {
                $resultat[$intervalle] = [
                    'garcons' => 0,
                    'filles'  => 0,
                    'total'   => 0,
                ];
                continue;
            }

            $elevesDansIntervalle = $eleves->filter(function ($eleve) use ($min, $max) {
                return $eleve->moyenne >= $min && $eleve->moyenne < $max;
            });

            $resultat[$intervalle] = [
                'garcons' => $elevesDansIntervalle->where('SEXE', '1')->count(),
                'filles'  => $elevesDansIntervalle->where('SEXE', '2')->count(),
                'total'   => $elevesDansIntervalle->count(),
            ];
        }
        return $resultat;
    }

    // public function calculerSynoptique(array $data)
    // {
    //     // 1. Choix dynamique de la colonne selon la période
    //     if ($data['periode'] === '1') {
    //         $colonne = 'MS1';
    //     } elseif ($data['periode'] === '2') {
    //         $colonne = 'MS2';
    //     } elseif ($data['periode'] === '3') {
    //         $colonne = 'MS3';
    //     } else {
    //         // Annuel
    //         $colonne = 'MAN';
    //     }
    
    //     // 2. On eager‑load les élèves **et** leurs notes
    //     $classes = Classes::with(['eleves.notes'])->get();

    //     $resultats = [];
    
    //     // 2. Regroupement par groupe
    //     $groupes = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));
        
    //     foreach ($groupes as $groupLabel => $classesGroupe) {
    //         // 3. Fusionner tous les élèves du groupe
    //         $elevesGroupe = $classesGroupe->flatMap->eleves;
            
    //         /* dd(count($elevesGroupe)); */
    //         // 4. I1 : élèves ayant au moins une note INT1 valide
    //         $elevesI1 = $elevesGroupe->filter(function ($e) {
    //             return $e->notes->contains(function ($note) {
    //                 return ! is_null($note->INT1)
    //                     && floatval($note->INT1) !== 21
    //                     && floatval($note->INT1) !== -1;
    //             });
    //         });
    
    //         // 5. I2 : STATUT = 0, STATUTG = 1 et note valide en $colonne
    //         $elevesI2 = $elevesI1->filter(fn($e) =>
    //             $e->STATUT  == 0 &&
    //             !is_null($e->{$colonne}) &&
    //             floatval($e->{$colonne}) !== 21
    //         );
    
    //         // 6. I3 : STATUT = 1, STATUTG = 1 et note valide en $colonne
    //         $elevesI3 = $elevesI1->filter(fn($e) =>
    //             $e->STATUT  == 1 &&
    //             !is_null($e->{$colonne}) &&
    //             floatval($e->{$colonne}) !== 21
    //         );
    
    //         $elevesI4 = $elevesI1->filter(function ($e) use ($colonne) {
    //             $note = $e->{$colonne};
    //             return is_null($note)
    //                 || floatval($note) === -1.0
    //                 || floatval($note) === 21.0;
    //         });
    //             /* dd(count($elevesI4)); */
    //         // 8. Construction du tableau des intervalles
    //         $intervalesResultat = [
    //             'I1' => [
    //                 'garcons' => $elevesI1->where('SEXE','1')->count(),
    //                 'filles'  => $elevesI1->where('SEXE','2')->count(),
    //                 'total'   => $elevesI1->count(),
    //             ],
    //             'I2' => [
    //                 'garcons' => $elevesI2->where('SEXE','1')->count(),
    //                 'filles'  => $elevesI2->where('SEXE','2')->count(),
    //                 'total'   => $elevesI2->count(),
    //             ],
    //             'I3' => [
    //                 'garcons' => $elevesI3->where('SEXE','1')->count(),
    //                 'filles'  => $elevesI3->where('SEXE','2')->count(),
    //                 'total'   => $elevesI3->count(),
    //             ],
    //             'I4' => [
    //                 'garcons' => $elevesI4->where('SEXE','1')->count(),
    //                 'filles'  => $elevesI4->where('SEXE','2')->count(),
    //                 'total'   => $elevesI4->count(),
    //             ],
    //         ];
    
    //         // 9. Sauvegarde du résultat pour ce groupe
    //         $resultats[$groupLabel] = [
    //             'codePromo'   => $groupLabel,
    //             'nbClasses'   => $classesGroupe->count(),
    //             'intervales'  => $intervalesResultat,
    //             'moyenne_ref' => $data['moyenne_ref'] ?? 10.00,
    //         ];
    //     }
    
    //     return $resultats;
    // }

    // public function calculerSynoptique(array $data)
    // {
    //     $periode = (int) ($data['periode'] ?? 1);
    //     // $classes = Classes::with(['eleves.notes'])->get();
    //     // $resultats = [];
    //     // $groupes = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));

    //         // ON NE CHARGE QUE LES CLASSES AYANT AU MOINS UN élève
    //     $classes = Classes::with(['eleves.notes'])
    //         ->whereHas('eleves')     // <— filtre SQL : effectif > 0
    //         ->get();

    //     $resultats = [];
    //     $groupes   = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));


    //     foreach ($groupes as $groupLabel => $classesGroupe) {
    //         $eleves = $classesGroupe->flatMap->eleves;

    //         // Helper pour vérifier la validité d'une note
    //         $isValid = function($note) {
    //             if (is_null($note)) {
    //                 return false;
    //             }
    //             $v = floatval($note);
    //             return $v !== -1.0 && $v !== 21.0;
    //         };

    //         // Pré-filtrages fréquents
    //         $hasMS1 = $eleves->filter(fn($e) => $isValid($e->MS1));
    //         $hasMS2 = $eleves->filter(fn($e) => $isValid($e->MS2));
    //         $hasMAN = $eleves->filter(fn($e) => $isValid($e->MAN));

    //         switch ($periode) {

    //             case 1:
                    
    //                 // I1
    //                 // $elevesInscritsDebutAnnee = $eleves->filter(function($e) use ($isValid) {
    //                 //         return
    //                 //          ($isValid($e->INT1) && $e->SEMESTRE == 1) 
    //                 //         || ($isValid($e->INT2 ) && $e->SEMESTRE == 1) 
    //                 //         || ($isValid($e->INT3 ) && $e->SEMESTRE == 1)
    //                 //         || ($isValid($e->INT4 ) && $e->SEMESTRE == 1)
    //                 //         || ($isValid($e->DEV1 ) && $e->SEMESTRE == 1)
    //                 //         || ($isValid($e->DEV2 ) && $e->SEMESTRE == 1);
    //                 //     });

                    
    //                 // 2) Filtrage avec la relation 'notes'
    //                 $elevesInscritsDebutAnnee = $eleves
    //                     ->filter(function($eleve) {
    //                     return $eleve->notes
    //                             ->where('SEMESTRE', '1')
    //                             ->filter(function($note) {
    //                                 foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
    //                                     $val = $note->$col;
    //                                     if (! is_null($val) && $val !== -1 && $val !== 21) {
    //                                         return true;
    //                                     }
    //                                 }
    //                                 return false;
    //                             })
    //                             ->isNotEmpty();
    //                     });

    //                 $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
    //                 $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
    //                 $countI1 = $countI1Garcons + $countI1Filles;

    //                 // I2 / I3 : MS1 valide + STATUT
    //                 $elevesI2 = $hasMS1->where('STATUT', '0');
    //                 $elevesI3 = $hasMS1->where('STATUT', '1');

                

    //                 // dd($elevesI2);
    //                 // I4 

    //                     // 4) parmis tous les eleves inscrit en debut d'anne , ne garder que ceux qui n'ont pas MS1
    //                     $elevesAbandonSem1 = $elevesInscritsDebutAnnee->filter(function($e) use ($isValid) {
    //                         return 
    //                             ! ($isValid($e->MS1));
    //                     });

    //                     // 5) comptage par sexe et total
    //                     $countI4Garcons = $elevesAbandonSem1->where('SEXE', '1')->count();
    //                     $countI4Filles  = $elevesAbandonSem1->where('SEXE', '2')->count();
    //                     $countI4        = $countI4Garcons + $countI4Filles;


    //                 $intervales = [
    //                     'I1' => [
    //                         'garcons' => $countI1Garcons,
    //                         'filles'  => $countI1Filles,
    //                         'total'   => $countI1,
    //                     ],
    //                     'I2' => [
    //                         'garcons' => $elevesI2->where('SEXE','1')->count(),
    //                         'filles'  => $elevesI2->where('SEXE','2')->count(),
    //                         'total'   => $elevesI2->count(),
    //                     ],
    //                     'I3' => [
    //                         'garcons' => $elevesI3->where('SEXE','1')->count(),
    //                         'filles'  => $elevesI3->where('SEXE','2')->count(),
    //                         'total'   => $elevesI3->count(),
    //                     ],
    //                     'I4' => [
    //                         'garcons' => $countI4Garcons,
    //                         'filles'  => $countI4Filles,
    //                         'total'   => $countI4,
    //                     ],
    //                 ];
    //             break;

    //             case 2:

    //                 // I1
    //                 // 2) Filtrage avec la relation 'notes'
    //                 $elevesInscritsDebutAnnee = $eleves
    //                     ->filter(function($eleve) {
    //                     return $eleve->notes
    //                             ->where('SEMESTRE', '1')
    //                             ->filter(function($note) {
    //                                 foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
    //                                     $val = $note->$col;
    //                                     if (! is_null($val) && $val !== -1 && $val !== 21) {
    //                                         return true;
    //                                     }
    //                                 }
    //                                 return false;
    //                             })
    //                             ->isNotEmpty();
    //                     });

    //                 $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
    //                 $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
    //                 $countI1 = $countI1Garcons + $countI1Filles;
                    
    //                 // I2 / I3 : MS2 valide + STATUT
    //                 $elevesI2 = $hasMS2->where('STATUT', '0');
    //                 $elevesI3 = $hasMS2->where('STATUT', '1');

    //                 // I4 = élèves sans MAN
    //                     $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
    //                         return 
    //                             ! ($isValid($e->MAN));
    //                     });

    //                     // 5) comptage par sexe et total
    //                     $countI4Garcons = $elevesAbandonAnne->where('SEXE', '1')->count();
    //                     $countI4Filles  = $elevesAbandonAnne->where('SEXE', '2')->count();
    //                     $countI4        = $countI4Garcons + $countI4Filles;


    //                 $intervales = [
    //                     'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
    //                     'I2' => ['garcons' => $elevesI2->where('SEXE','1')->count(), 'filles' => $elevesI2->where('SEXE','2')->count(), 'total' => $elevesI2->count()],
    //                     'I3' => ['garcons' => $elevesI3->where('SEXE','1')->count(), 'filles' => $elevesI3->where('SEXE','2')->count(), 'total' => $elevesI3->count()],
    //                     'I4' => ['garcons' => $countI4Garcons, 'filles' => $countI4Filles, 'total' => $countI4],
    //                 ];
    //             break;

    //             case 4:
    //                 // I2 / I3 : MS2 valide + STATUT
    //                 $DecisionConfigAnnuel = DecisionConfigAnnuel::first();
    //                 $seuilPassage = $DecisionConfigAnnuel->seuil_Passage;

    //                 // I2

    //                 $elevesI2   = [];
    //                 $garconsI2  = 0;
    //                 $fillesI2   = 0;

    //                 foreach ($hasMAN as $singleStudent) {
    //                     if ($singleStudent->MAN >= $seuilPassage) {

    //                         $elevesI2[] = $singleStudent;

    //                         if ($singleStudent->SEXE === 1) {
    //                             $garconsI2++;
    //                         } else {
    //                             $fillesI2++;
    //                         }
    //                     }
    //                 }

    //                 // I3

    //                 $elevesI3  = [];
    //                 $garconsI3  = 0;
    //                 $fillesI3   = 0;

    //                 foreach ($hasMAN as $singleStudent) {
    //                     // On récupère le cycle de la promo de l'élève
    //                     // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
    //                     $cycle = $singleStudent->classe->promo->CYCLE;

    //                     // On choisit le seuil min correspondant
    //                     $seuilMin = ($cycle === '1')
    //                         ? $DecisionConfigAnnuel->MinCycle1
    //                         : $DecisionConfigAnnuel->MinCycle2;

    //                     // On filtre selon les nouvelles bornes et le statut
    //                     if (
    //                         $singleStudent->MAN >= $seuilMin &&
    //                         $singleStudent->MAN <  $seuilPassage &&
    //                         $singleStudent->STATUT === 0
    //                     ) {
    //                         // Ajout à la liste I3
    //                         $elevesI3[] = $singleStudent;
    //                     }
    //                 }
    //                 // maintenant on filtre la liste I3 pour compter
    //                 $garconsI3 = count(array_filter(
    //                     $elevesI3,
    //                     fn($e) => $e->SEXE === 1
    //                 ));
    //                 $fillesI3 = count($elevesI3) - $garconsI3;


    //                     // I4 = élèves sans MAN
    //                     $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
    //                         return 
    //                             ! ($isValid($e->MAN));
    //                     });

    //                     // 5) comptage par sexe et total
    //                     $garconsI4 = $elevesAbandonAnne->where('SEXE', '1')->count();
    //                     $fillesI4  = $elevesAbandonAnne->where('SEXE', '2')->count();
    //                     $elevesI4        = $garconsI4 + $fillesI4;


                
    //                 // I5
    //                 $elevesI5  = [];
    //                 $garconsI5  = 0;
    //                 $fillesI5   = 0;

    //                 foreach ($hasMAN as $singleStudent) {
    //                     // On récupère le cycle de la promo de l'élève
    //                     // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
    //                     $cycle = $singleStudent->classe->promo->CYCLE;

    //                     // On choisit le seuil min correspondant
    //                     $seuilMin = ($cycle === '1')
    //                         ? $DecisionConfigAnnuel->MinCycle1
    //                         : $DecisionConfigAnnuel->MinCycle2;

    //                     // On filtre selon les nouvelles bornes et le statut
    //                     if (
    //                         ($singleStudent->MAN < $seuilMin && $singleStudent->STATUT === 0) || 
    //                         ($singleStudent->MAN < $seuilPassage && $singleStudent->STATUT === 1)
    //                     ) {
    //                         // Ajout à la liste I3
    //                         $elevesI5[] = $singleStudent;
    //                     }
    //                 }
    //                 // maintenant on filtre la liste I3 pour compter
    //                 $garconsI5 = count(array_filter(
    //                     $elevesI5,
    //                     fn($e) => $e->SEXE === 1
    //                 ));
    //                 $fillesI5 = count($elevesI5) - $garconsI5;

                    
    //                 // $elevesI2 = $hasMAN  >= ;
    //                 // $elevesI3 = $hasMS2->where('STATUT', 1);

    //                 // I4 = élèves sans MAN
    //                 // $countI4Garcons = $eleves->where('SEXE','1')->count() - $hasMAN->where('SEXE','1')->count();
    //                 // $countI4Filles  = $eleves->where('SEXE','2')->count() - $hasMAN->where('SEXE','2')->count();
    //                 // $countI4 = $countI4Garcons + $countI4Filles;

    //                 // I1 = même répartition que période 1


    //                 // I1 : effictif total de la classe
    //                 $countI1 = $eleves->count();

    //                 $countI1Garcons = $eleves->where('SEXE','1')->count();
    //                 $countI1Filles  = $eleves->where('SEXE','2')->count();
                            

    //                 $intervales = [
    //                     'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
    //                     'I2' => ['garcons' => $garconsI2, 'filles'  => $fillesI2, 'total' => count($elevesI2)],
    //                     'I3' => ['garcons' => $garconsI3, 'filles' => $fillesI3, 'total' => count($elevesI3)],
    //                     'I4' => ['garcons' => $garconsI4, 'filles' => $fillesI4, 'total' => $elevesI4],
    //                     'I5' => ['garcons' => $garconsI5, 'filles' => $fillesI5, 'total' => count($elevesI5)],
    //                 ];
    //             break;

    //             default:
    //                 // $intervales = $this->ancienneLogique($eleves, $periode, $data['moyenne_ref'] ?? 10.0);
    //                 $intervales = "yoyooyoy";
    //                 break;
    //         }

    //         $resultats[$groupLabel] = [
    //             'codePromo'   => $groupLabel,
    //             'nbClasses'   => $classesGroupe->count(),
    //             'intervales'  => $intervales,
    //             'moyenne_ref' => $data['moyenne_ref'] ?? 10.00,
    //         ];
    //     }

    //     return $resultats;
    // }

    public function calculerSynoptique(array $data)
    {
        $periode = (int) ($data['periode'] ?? 1);
        // $classes = Classes::with(['eleves.notes'])->get();
        // $resultats = [];
        // $groupes = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));

            // ON NE CHARGE QUE LES CLASSES AYANT AU MOINS UN élève
        $classes = Classes::with(['eleves.notes'])
            ->whereHas('eleves')     // <— filtre SQL : effectif > 0
            ->get();

        $params2 = Params2::all();
        $typean =  $params2->first()->TYPEAN;

        $resultats = [];
        $groupes   = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));


        foreach ($groupes as $groupLabel => $classesGroupe) {
            $eleves = $classesGroupe->flatMap->eleves;

            // Helper pour vérifier la validité d'une note
            $isValid = function($note) {
                if (is_null($note)) {
                    return false;
                }
                $v = floatval($note);
                return $v !== -1.0 && $v !== 21.0;
            };

            // Pré-filtrages fréquents
            $hasMS1 = $eleves->filter(fn($e) => $isValid($e->MS1));
            $hasMS2 = $eleves->filter(fn($e) => $isValid($e->MS2));
            $hasMS3 = $eleves->filter(fn($e) => $isValid($e->MS3));
            $hasMAN = $eleves->filter(fn($e) => $isValid($e->MAN));

            if ($typean == 1) {
                // SEMESTRE
                switch ($periode) {

                    case 1:
                        
                        // I1
                        // $elevesInscritsDebutAnnee = $eleves->filter(function($e) use ($isValid) {
                        //         return
                        //          ($isValid($e->INT1) && $e->SEMESTRE == 1) 
                        //         || ($isValid($e->INT2 ) && $e->SEMESTRE == 1) 
                        //         || ($isValid($e->INT3 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->INT4 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->DEV1 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->DEV2 ) && $e->SEMESTRE == 1);
                        //     });

                        
                        // 2) Filtrage avec la relation 'notes'
                        $elevesInscritsDebutAnnee = $eleves
                            ->filter(function($eleve) {
                            return $eleve->notes
                                    ->where('SEMESTRE', '1')
                                    ->filter(function($note) {
                                        foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
                                            $val = $note->$col;
                                            if (! is_null($val) && $val !== -1 && $val !== 21) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->isNotEmpty();
                            });

                        $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
                        $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
                        $countI1 = $countI1Garcons + $countI1Filles;

                        // I2 / I3 : MS1 valide + STATUT
                        $elevesI2 = $hasMS1->where('STATUT', '0');
                        $elevesI3 = $hasMS1->where('STATUT', '1');

                    

                        // dd($elevesI2);
                        // I4 

                            // 4) parmis tous les eleves inscrit en debut d'anne , ne garder que ceux qui n'ont pas MS1
                            $elevesAbandonSem1 = $elevesInscritsDebutAnnee->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MS1));
                            });

                            // 5) comptage par sexe et total
                            $countI4Garcons = $elevesAbandonSem1->where('SEXE', '1')->count();
                            $countI4Filles  = $elevesAbandonSem1->where('SEXE', '2')->count();
                            $countI4        = $countI4Garcons + $countI4Filles;


                        $intervales = [
                            'I1' => [
                                'garcons' => $countI1Garcons,
                                'filles'  => $countI1Filles,
                                'total'   => $countI1,
                            ],
                            'I2' => [
                                'garcons' => $elevesI2->where('SEXE','1')->count(),
                                'filles'  => $elevesI2->where('SEXE','2')->count(),
                                'total'   => $elevesI2->count(),
                            ],
                            'I3' => [
                                'garcons' => $elevesI3->where('SEXE','1')->count(),
                                'filles'  => $elevesI3->where('SEXE','2')->count(),
                                'total'   => $elevesI3->count(),
                            ],
                            'I4' => [
                                'garcons' => $countI4Garcons,
                                'filles'  => $countI4Filles,
                                'total'   => $countI4,
                            ],
                        ];
                    break;

                    case 2:

                        // I1
                        // 2) Filtrage avec la relation 'notes'
                        $elevesInscritsDebutAnnee = $eleves
                            ->filter(function($eleve) {
                            return $eleve->notes
                                    ->where('SEMESTRE', '1')
                                    ->filter(function($note) {
                                        foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
                                            $val = $note->$col;
                                            if (! is_null($val) && $val !== -1 && $val !== 21) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->isNotEmpty();
                            });

                        $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
                        $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
                        $countI1 = $countI1Garcons + $countI1Filles;
                        
                        // I2 / I3 : MS2 valide + STATUT
                        $elevesI2 = $hasMS2->where('STATUT', '0');
                        $elevesI3 = $hasMS2->where('STATUT', '1');

                        // I4 = élèves sans MAN
                            $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MAN));
                            });

                            // 5) comptage par sexe et total
                            $countI4Garcons = $elevesAbandonAnne->where('SEXE', '1')->count();
                            $countI4Filles  = $elevesAbandonAnne->where('SEXE', '2')->count();
                            $countI4        = $countI4Garcons + $countI4Filles;


                        $intervales = [
                            'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
                            'I2' => ['garcons' => $elevesI2->where('SEXE','1')->count(), 'filles' => $elevesI2->where('SEXE','2')->count(), 'total' => $elevesI2->count()],
                            'I3' => ['garcons' => $elevesI3->where('SEXE','1')->count(), 'filles' => $elevesI3->where('SEXE','2')->count(), 'total' => $elevesI3->count()],
                            'I4' => ['garcons' => $countI4Garcons, 'filles' => $countI4Filles, 'total' => $countI4],
                        ];
                    break;

                    case 4:
                        // I2 / I3 : MS2 valide + STATUT
                        $DecisionConfigAnnuel = DecisionConfigAnnuel::first();
                        $seuilPassage = $DecisionConfigAnnuel->seuil_Passage;

                        // I2

                        $elevesI2   = [];
                        $garconsI2  = 0;
                        $fillesI2   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            if ($singleStudent->MAN >= $seuilPassage) {

                                $elevesI2[] = $singleStudent;

                                if ($singleStudent->SEXE === 1) {
                                    $garconsI2++;
                                } else {
                                    $fillesI2++;
                                }
                            }
                        }

                        // I3

                        $elevesI3  = [];
                        $garconsI3  = 0;
                        $fillesI3   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            // On récupère le cycle de la promo de l'élève
                            // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
                            $cycle = $singleStudent->classe->promo->CYCLE;

                            // On choisit le seuil min correspondant
                            $seuilMin = ($cycle === '1')
                                ? $DecisionConfigAnnuel->MinCycle1
                                : $DecisionConfigAnnuel->MinCycle2;

                            // On filtre selon les nouvelles bornes et le statut
                            if (
                                $singleStudent->MAN >= $seuilMin &&
                                $singleStudent->MAN <  $seuilPassage &&
                                $singleStudent->STATUT === 0
                            ) {
                                // Ajout à la liste I3
                                $elevesI3[] = $singleStudent;
                            }
                        }
                        // maintenant on filtre la liste I3 pour compter
                        $garconsI3 = count(array_filter(
                            $elevesI3,
                            fn($e) => $e->SEXE === 1
                        ));
                        $fillesI3 = count($elevesI3) - $garconsI3;


                            // I4 = élèves sans MAN
                            $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MAN));
                            });

                            // 5) comptage par sexe et total
                            $garconsI4 = $elevesAbandonAnne->where('SEXE', '1')->count();
                            $fillesI4  = $elevesAbandonAnne->where('SEXE', '2')->count();
                            $elevesI4        = $garconsI4 + $fillesI4;


                    
                        // I5
                        $elevesI5  = [];
                        $garconsI5  = 0;
                        $fillesI5   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            // On récupère le cycle de la promo de l'élève
                            // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
                            $cycle = $singleStudent->classe->promo->CYCLE;

                            // On choisit le seuil min correspondant
                            $seuilMin = ($cycle === '1')
                                ? $DecisionConfigAnnuel->MinCycle1
                                : $DecisionConfigAnnuel->MinCycle2;

                            // On filtre selon les nouvelles bornes et le statut
                            if (
                                ($singleStudent->MAN < $seuilMin && $singleStudent->STATUT === 0) || 
                                ($singleStudent->MAN < $seuilPassage && $singleStudent->STATUT === 1)
                            ) {
                                // Ajout à la liste I3
                                $elevesI5[] = $singleStudent;
                            }
                        }
                        // maintenant on filtre la liste I3 pour compter
                        $garconsI5 = count(array_filter(
                            $elevesI5,
                            fn($e) => $e->SEXE === 1
                        ));
                        $fillesI5 = count($elevesI5) - $garconsI5;

                        
                        // $elevesI2 = $hasMAN  >= ;
                        // $elevesI3 = $hasMS2->where('STATUT', 1);

                        // I4 = élèves sans MAN
                        // $countI4Garcons = $eleves->where('SEXE','1')->count() - $hasMAN->where('SEXE','1')->count();
                        // $countI4Filles  = $eleves->where('SEXE','2')->count() - $hasMAN->where('SEXE','2')->count();
                        // $countI4 = $countI4Garcons + $countI4Filles;

                        // I1 = même répartition que période 1


                        // I1 : effictif total de la classe
                        $countI1 = $eleves->count();

                        $countI1Garcons = $eleves->where('SEXE','1')->count();
                        $countI1Filles  = $eleves->where('SEXE','2')->count();
                                

                        $intervales = [
                            'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
                            'I2' => ['garcons' => $garconsI2, 'filles'  => $fillesI2, 'total' => count($elevesI2)],
                            'I3' => ['garcons' => $garconsI3, 'filles' => $fillesI3, 'total' => count($elevesI3)],
                            'I4' => ['garcons' => $garconsI4, 'filles' => $fillesI4, 'total' => $elevesI4],
                            'I5' => ['garcons' => $garconsI5, 'filles' => $fillesI5, 'total' => count($elevesI5)],
                        ];
                    break;

                    default:
                        // $intervales = $this->ancienneLogique($eleves, $periode, $data['moyenne_ref'] ?? 10.0);
                        $intervales = "yoyooyoy";
                        break;
                }
            } else {
                // TRIMESTRE
                switch ($periode) {

                    case 1:
                        
                        // I1
                        // $elevesInscritsDebutAnnee = $eleves->filter(function($e) use ($isValid) {
                        //         return
                        //          ($isValid($e->INT1) && $e->SEMESTRE == 1) 
                        //         || ($isValid($e->INT2 ) && $e->SEMESTRE == 1) 
                        //         || ($isValid($e->INT3 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->INT4 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->DEV1 ) && $e->SEMESTRE == 1)
                        //         || ($isValid($e->DEV2 ) && $e->SEMESTRE == 1);
                        //     });

                        
                        // 2) Filtrage avec la relation 'notes'
                        $elevesInscritsDebutAnnee = $eleves
                            ->filter(function($eleve) {
                            return $eleve->notes
                                    ->where('SEMESTRE', '1')
                                    ->filter(function($note) {
                                        foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
                                            $val = $note->$col;
                                            if (! is_null($val) && $val !== -1 && $val !== 21) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->isNotEmpty();
                            });

                        $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
                        $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
                        $countI1 = $countI1Garcons + $countI1Filles;

                        // I2 / I3 : MS1 valide + STATUT
                        $elevesI2 = $hasMS1->where('STATUT', '0');
                        $elevesI3 = $hasMS1->where('STATUT', '1');

                    

                        // dd($elevesI2);
                        // I4 

                            // 4) parmis tous les eleves inscrit en debut d'anne , ne garder que ceux qui n'ont pas MS1
                            $elevesAbandonSem1 = $elevesInscritsDebutAnnee->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MS1));
                            });

                            // 5) comptage par sexe et total
                            $countI4Garcons = $elevesAbandonSem1->where('SEXE', '1')->count();
                            $countI4Filles  = $elevesAbandonSem1->where('SEXE', '2')->count();
                            $countI4        = $countI4Garcons + $countI4Filles;


                        $intervales = [
                            'I1' => [
                                'garcons' => $countI1Garcons,
                                'filles'  => $countI1Filles,
                                'total'   => $countI1,
                            ],
                            'I2' => [
                                'garcons' => $elevesI2->where('SEXE','1')->count(),
                                'filles'  => $elevesI2->where('SEXE','2')->count(),
                                'total'   => $elevesI2->count(),
                            ],
                            'I3' => [
                                'garcons' => $elevesI3->where('SEXE','1')->count(),
                                'filles'  => $elevesI3->where('SEXE','2')->count(),
                                'total'   => $elevesI3->count(),
                            ],
                            'I4' => [
                                'garcons' => $countI4Garcons,
                                'filles'  => $countI4Filles,
                                'total'   => $countI4,
                            ],
                        ];
                    break;

                    case 2:

                        // I1
                        // 2) Filtrage avec la relation 'notes'
                        $elevesInscritsDebutAnnee = $eleves
                            ->filter(function($eleve) {
                            return $eleve->notes
                                    ->where('SEMESTRE', '1')
                                    ->filter(function($note) {
                                        foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
                                            $val = $note->$col;
                                            if (! is_null($val) && $val !== -1 && $val !== 21) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->isNotEmpty();
                            });

                        $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
                        $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
                        $countI1 = $countI1Garcons + $countI1Filles;
                        
                        // I2 / I3 : MS2 valide + STATUT
                        $elevesI2 = $hasMS2->where('STATUT', '0');
                        $elevesI3 = $hasMS2->where('STATUT', '1');

                        // I4 = élèves sans MS2
                            $elevesAbandonSem2 = $eleves->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MS2));
                            });

                            // 5) comptage par sexe et total
                            $countI4Garcons = $elevesAbandonSem2->where('SEXE', '1')->count();
                            $countI4Filles  = $elevesAbandonSem2->where('SEXE', '2')->count();
                            $countI4        = $countI4Garcons + $countI4Filles;


                        $intervales = [
                            'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
                            'I2' => ['garcons' => $elevesI2->where('SEXE','1')->count(), 'filles' => $elevesI2->where('SEXE','2')->count(), 'total' => $elevesI2->count()],
                            'I3' => ['garcons' => $elevesI3->where('SEXE','1')->count(), 'filles' => $elevesI3->where('SEXE','2')->count(), 'total' => $elevesI3->count()],
                            'I4' => ['garcons' => $countI4Garcons, 'filles' => $countI4Filles, 'total' => $countI4],
                        ];
                    break;

                    case 3:

                        // I1
                        // 2) Filtrage avec la relation 'notes'
                        $elevesInscritsDebutAnnee = $eleves
                            ->filter(function($eleve) {
                            return $eleve->notes
                                    ->where('SEMESTRE', '1')
                                    ->filter(function($note) {
                                        foreach (['INT1','INT2','INT3','INT4','DEV1','DEV2'] as $col) {
                                            $val = $note->$col;
                                            if (! is_null($val) && $val !== -1 && $val !== 21) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    })
                                    ->isNotEmpty();
                            });

                        $countI1Garcons = $elevesInscritsDebutAnnee->where('SEXE','1')->count();
                        $countI1Filles  = $elevesInscritsDebutAnnee->where('SEXE','2')->count();
                        $countI1 = $countI1Garcons + $countI1Filles;
                        
                        // I2 / I3 : MS2 valide + STATUT
                        $elevesI2 = $hasMS3->where('STATUT', '0');
                        $elevesI3 = $hasMS3->where('STATUT', '1');

                        // I4 = élèves sans MAN
                            $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MAN));
                            });

                            // 5) comptage par sexe et total
                            $countI4Garcons = $elevesAbandonAnne->where('SEXE', '1')->count();
                            $countI4Filles  = $elevesAbandonAnne->where('SEXE', '2')->count();
                            $countI4        = $countI4Garcons + $countI4Filles;


                        $intervales = [
                            'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
                            'I2' => ['garcons' => $elevesI2->where('SEXE','1')->count(), 'filles' => $elevesI2->where('SEXE','2')->count(), 'total' => $elevesI2->count()],
                            'I3' => ['garcons' => $elevesI3->where('SEXE','1')->count(), 'filles' => $elevesI3->where('SEXE','2')->count(), 'total' => $elevesI3->count()],
                            'I4' => ['garcons' => $countI4Garcons, 'filles' => $countI4Filles, 'total' => $countI4],
                        ];
                    break;

                    case 4:
                        // I2 / I3 : MS2 valide + STATUT
                        $DecisionConfigAnnuel = DecisionConfigAnnuel::first();
                        $seuilPassage = $DecisionConfigAnnuel->seuil_Passage;

                        // I2

                        $elevesI2   = [];
                        $garconsI2  = 0;
                        $fillesI2   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            if ($singleStudent->MAN >= $seuilPassage) {

                                $elevesI2[] = $singleStudent;

                                if ($singleStudent->SEXE === 1) {
                                    $garconsI2++;
                                } else {
                                    $fillesI2++;
                                }
                            }
                        }

                        // I3

                        $elevesI3  = [];
                        $garconsI3  = 0;
                        $fillesI3   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            // On récupère le cycle de la promo de l'élève
                            // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
                            $cycle = $singleStudent->classe->promo->CYCLE;

                            // On choisit le seuil min correspondant
                            $seuilMin = ($cycle === '1')
                                ? $DecisionConfigAnnuel->MinCycle1
                                : $DecisionConfigAnnuel->MinCycle2;

                            // On filtre selon les nouvelles bornes et le statut
                            if (
                                $singleStudent->MAN >= $seuilMin &&
                                $singleStudent->MAN <  $seuilPassage &&
                                $singleStudent->STATUT === 0
                            ) {
                                // Ajout à la liste I3
                                $elevesI3[] = $singleStudent;
                            }
                        }
                        // maintenant on filtre la liste I3 pour compter
                        $garconsI3 = count(array_filter(
                            $elevesI3,
                            fn($e) => $e->SEXE === 1
                        ));
                        $fillesI3 = count($elevesI3) - $garconsI3;


                            // I4 = élèves sans MAN
                            $elevesAbandonAnne = $eleves->filter(function($e) use ($isValid) {
                                return 
                                    ! ($isValid($e->MAN));
                            });

                            // 5) comptage par sexe et total
                            $garconsI4 = $elevesAbandonAnne->where('SEXE', '1')->count();
                            $fillesI4  = $elevesAbandonAnne->where('SEXE', '2')->count();
                            $elevesI4        = $garconsI4 + $fillesI4;


                    
                        // I5
                        $elevesI5  = [];
                        $garconsI5  = 0;
                        $fillesI5   = 0;

                        foreach ($hasMAN as $singleStudent) {
                            // On récupère le cycle de la promo de l'élève
                            // (adaptez le chemin de relation si besoin : ici on suppose classe->promo)
                            $cycle = $singleStudent->classe->promo->CYCLE;

                            // On choisit le seuil min correspondant
                            $seuilMin = ($cycle === '1')
                                ? $DecisionConfigAnnuel->MinCycle1
                                : $DecisionConfigAnnuel->MinCycle2;

                            // On filtre selon les nouvelles bornes et le statut
                            if (
                                ($singleStudent->MAN < $seuilMin && $singleStudent->STATUT === 0) || 
                                ($singleStudent->MAN < $seuilPassage && $singleStudent->STATUT === 1)
                            ) {
                                // Ajout à la liste I3
                                $elevesI5[] = $singleStudent;
                            }
                        }
                        // maintenant on filtre la liste I3 pour compter
                        $garconsI5 = count(array_filter(
                            $elevesI5,
                            fn($e) => $e->SEXE === 1
                        ));
                        $fillesI5 = count($elevesI5) - $garconsI5;

                        
                        // $elevesI2 = $hasMAN  >= ;
                        // $elevesI3 = $hasMS2->where('STATUT', 1);

                        // I4 = élèves sans MAN
                        // $countI4Garcons = $eleves->where('SEXE','1')->count() - $hasMAN->where('SEXE','1')->count();
                        // $countI4Filles  = $eleves->where('SEXE','2')->count() - $hasMAN->where('SEXE','2')->count();
                        // $countI4 = $countI4Garcons + $countI4Filles;

                        // I1 = même répartition que période 1


                        // I1 : effictif total de la classe
                        $countI1 = $eleves->count();

                        $countI1Garcons = $eleves->where('SEXE','1')->count();
                        $countI1Filles  = $eleves->where('SEXE','2')->count();
                                

                        $intervales = [
                            'I1' => ['garcons' => $countI1Garcons, 'filles' => $countI1Filles, 'total' => $countI1],
                            'I2' => ['garcons' => $garconsI2, 'filles'  => $fillesI2, 'total' => count($elevesI2)],
                            'I3' => ['garcons' => $garconsI3, 'filles' => $fillesI3, 'total' => count($elevesI3)],
                            'I4' => ['garcons' => $garconsI4, 'filles' => $fillesI4, 'total' => $elevesI4],
                            'I5' => ['garcons' => $garconsI5, 'filles' => $fillesI5, 'total' => count($elevesI5)],
                        ];
                    break;

                    default:
                        // $intervales = $this->ancienneLogique($eleves, $periode, $data['moyenne_ref'] ?? 10.0);
                        $intervales = "yoyooyoy";
                        break;
                }
            }



            $resultats[$groupLabel] = [
                'codePromo'   => $groupLabel,
                'nbClasses'   => $classesGroupe->count(),
                'intervales'  => $intervales,
                'moyenne_ref' => $data['moyenne_ref'] ?? 10.00,
            ];
        }

        return $resultats;
    }
    

    public function calculerEffectifs(array $data)
    {
        // 1. Choix de la colonne selon la période
        if ($data['periode'] === '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] === '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] === '3') {
            $colonne = 'MS3';
        } else {
            // Période annuelle
            $colonne = 'MAN';
        }

        $classes    = Classes::with('eleves')->get();
        $resultats  = [];

        // 2. Regroupement par label de groupe
        $groupes = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));

        foreach ($groupes as $groupLabel => $classesGroupe) {
            // 3. Fusion de tous les élèves du groupe
            $elevesGroupe = $classesGroupe->flatMap->eleves;

            // 4. Filtrer et assigner la moyenne directement depuis $colonne
            $elevesAvecMoyennes = $elevesGroupe
                ->filter(function($eleve) use ($colonne) {
                    $note = $eleve->{$colonne};
                    return !is_null($note)
                        && floatval($note) !== -1
                        && floatval($note) !== 21;
                })
                ->map(function($eleve) use ($colonne) {
                    $eleve->moyenne = floatval($eleve->{$colonne});
                    return $eleve;
                });

            // 5. Séparer par sexe et calculer max/min
            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles  = $elevesAvecMoyennes->where('SEXE', '2');

            $maxGar = $garcons->max('moyenne');
            $minGar = $garcons->min('moyenne');
            $maxFil = $filles->max('moyenne');
            $minFil = $filles->min('moyenne');

            // 6. Statuts I1, I2, I3 (I2 & I3 utilisent désormais $colonne)
            $elevesI1 = $elevesGroupe->filter(fn($e) => $e->STATUTG == 1);

            $elevesI2 = $elevesGroupe->filter(function($e) use ($colonne) {
                return $e->STATUT  == 0
                    && $e->STATUTG == 1
                    && !is_null($e->{$colonne});
            });

            $elevesI3 = $elevesGroupe->filter(function($e) use ($colonne) {
                return $e->STATUT  == 1
                    && $e->STATUTG == 1
                    && !is_null($e->{$colonne});
            });

            // 7. Répartition dans les intervalles
            $intervalesResultat = [];
            foreach ($data['intervales'] as $cle => $valeurs) {
                $min = floatval($valeurs['min']);
                $max = floatval($valeurs['max']);

                $dans = $elevesAvecMoyennes->filter(fn($e) =>
                    $e->moyenne >= $min && $e->moyenne < $max
                );

                $intervalesResultat[$cle] = [
                    'garcons' => $dans->where('SEXE','1')->count(),
                    'filles'  => $dans->where('SEXE','2')->count(),
                    'total'   => $dans->count(),
                ];
            }

            // 8. Assemblage du résultat pour ce groupe
            $resultats[$groupLabel] = [
                'max_moyenne_garcons' => $maxGar,
                'min_moyenne_garcons' => $minGar,
                'max_moyenne_filles'  => $maxFil,
                'min_moyenne_filles'  => $minFil,
                'total_eleves'        => $elevesAvecMoyennes->count(),
                'total_garcons'       => $garcons->count(),
                'total_filles'        => $filles->count(),
                'effectifs_statut'    => [
                    'I1' => [
                        'garcons' => $elevesI1->where('SEXE','1')->count(),
                        'filles'  => $elevesI1->where('SEXE','2')->count(),
                        'total'   => $elevesI1->count(),
                    ],
                    'I2' => [
                        'garcons' => $elevesI2->where('SEXE','1')->count(),
                        'filles'  => $elevesI2->where('SEXE','2')->count(),
                        'total'   => $elevesI2->count(),
                    ],
                    'I3' => [
                        'garcons' => $elevesI3->where('SEXE','1')->count(),
                        'filles'  => $elevesI3->where('SEXE','2')->count(),
                        'total'   => $elevesI3->count(),
                    ],
                ],
                'intervales' => $intervalesResultat,
            ];
        }

        return $resultats;
    }


    public function calculerStatistiquesDetaillees(array $data)
    {
        // $classes = Classes::with('eleves')->where('CODEPROMO', '!=', '1')->get();
        $classes = Classes::with('eleves')
            ->where('TYPEENSEIG', '!=', '2')
            ->where('TYPEENSEIG', '!=', '1')
            ->get();
        $resultats  = [];
    
        foreach ($classes as $classe) {
            $eleves            = $classe->eleves;
            $effectifTotal     = $eleves->count();
            $compteursIntervalles = [];
    
            // Initialisation des compteurs par intervalle
            foreach ($data['intervales'] as $intervalle => $valeurs) {
                $compteursIntervalles[$intervalle] = 0;
            }
    
            $meilleurEleve    = null;
            $plusFaibleEleve  = null;
            $abandons         = 0;
            $elevesAvecMoyenne = 0;
            $elevesReussite   = 0;
    
            foreach ($eleves as $eleve) {
                // Choix de la colonne selon la période
                if ($data['periode'] === '4') {
                    // Pour l'annuel : lecture directe de MAN
                    $noteAnnuelle = $eleve->MAN;
                    if (is_null($noteAnnuelle) 
                        || floatval($noteAnnuelle) == -1 
                        || floatval($noteAnnuelle) == 21) {
                        $abandons++;
                        continue;
                    }
                    $moyenne = floatval($noteAnnuelle);
                } else {
                    // Trimestres / semestres
                    $colonne = 'MS' . $data['periode'];
                    $note    = $eleve->{$colonne};
                    if (is_null($note) 
                        || floatval($note) == -1 
                        || floatval($note) == 21) {
                        $abandons++;
                        continue;
                    }
                    $moyenne = floatval($note);
                }
    
                $elevesAvecMoyenne++;
    
                // Classement dans l'intervalle approprié
                foreach ($data['intervales'] as $intervalle => $valeurs) {
                    $min = floatval($valeurs['min']);
                    $max = floatval($valeurs['max']);
                    if ($moyenne >= $min && $moyenne < $max) {
                        $compteursIntervalles[$intervalle]++;
                        break;
                    }
                }
    
                // Meilleur / plus faible
                if ($meilleurEleve === null || $moyenne > $meilleurEleve['moyenne']) {
                    $meilleurEleve = [
                        'nom'     => $eleve->NOM . ' ' . $eleve->PRENOM,
                        'moyenne' => $moyenne,
                    ];
                }
                if ($plusFaibleEleve === null || $moyenne < $plusFaibleEleve['moyenne']) {
                    $plusFaibleEleve = [
                        'nom'     => $eleve->NOM . ' ' . $eleve->PRENOM,
                        'moyenne' => $moyenne,
                    ];
                }
    
                // Succès selon la moyenne de référence
                if ($moyenne >= $data['moyenne_ref']) {
                    $elevesReussite++;
                }
            }
    
            // Taux de réussite
            $tauxReussite = $elevesAvecMoyenne > 0
                ? ($elevesReussite / $elevesAvecMoyenne) * 100
                : 0;
    
            $resultats[$classe->LIBELCLAS] = [
                'effectif_total'    => $effectifTotal,
                'intervales'        => $compteursIntervalles,
                'meilleur_eleve'    => $meilleurEleve,
                'plus_faible_eleve' => $plusFaibleEleve,
                'abandons'          => $abandons,
                'taux_reussite'     => $tauxReussite,
                'codepromo'         => $classe->CODEPROMO, // Ajout du CODEPROMO
            ];
        }
    
        return $resultats;
    }
    
    
}