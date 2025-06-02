<?php

namespace App\Services;

use App\Models\Classes;

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
        if ($data['periode'] == '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] == '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] == '3') {
            $colonne = 'MS3';
        } else {
            $colonne = null;
        }

        $classes = Classes::with('eleves', 'promo')->get();
        $resultats = [];

        $groupes = $classes->groupBy(function ($classe) {
            return $this->getGroupLabel($classe);
        });

        foreach ($groupes as $groupeKey => $classesGroupe) {
            $elevesGroupe = collect();

            foreach ($classesGroupe as $classe) {
                $elevesFiltrés = $classe->eleves->filter(function ($eleve) use ($colonne) {
                    if (is_null($colonne)) {
                        return !is_null($eleve->MS1) && floatval($eleve->MS1) != -1 && floatval($eleve->MS1) != 21
                            && !is_null($eleve->MS2) && floatval($eleve->MS2) != -1 && floatval($eleve->MS2) != 21
                            && !is_null($eleve->MS3) && floatval($eleve->MS3) != -1 && floatval($eleve->MS3) != 21;
                    }
                    $note = $eleve->{$colonne};
                    return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                });

                $elevesGroupe = $elevesGroupe->merge($elevesFiltrés);
            }

            $elevesAvecMoyennes = $elevesGroupe->map(function ($eleve) use ($colonne) {
                if (is_null($colonne)) {
                    $moyenne = ($eleve->MS1 + $eleve->MS2 + $eleve->MS3) / 3;
                } else {
                    $moyenne = $eleve->{$colonne};
                }
                $eleve->moyenne = $moyenne;
                return $eleve;
            });

            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles  = $elevesAvecMoyennes->where('SEXE', '2');

            $maxMoyenneGarcons = $garcons->max('moyenne');
            $minMoyenneGarcons = $garcons->min('moyenne');
            $maxMoyenneFilles  = $filles->max('moyenne');
            $minMoyenneFilles  = $filles->min('moyenne');

            $intervalesResultats = $this->calculerIntervales($elevesAvecMoyennes, $data['intervales']);

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

    public function calculerSynoptique($data)
    {
        $classes = Classes::with('eleves')->get();
        $resultats = [];

        $groupes = $classes->groupBy(function ($classe) {
            return $this->getGroupLabel($classe);
        });

        foreach ($groupes as $groupLabel => $classesGroupe) {
            $elevesGroupe = collect();
            foreach ($classesGroupe as $classe) {
                $elevesGroupe = $elevesGroupe->merge($classe->eleves);
            }

            $nombreClasses = $classesGroupe->count();

            // Calcul des intervalles selon les critères spécifiques
            $intervalesResultat = [];

            // I1: Élèves avec STATUTG = 1
            $elevesI1 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUTG == 1;
            });

            $intervalesResultat['I1'] = [
                'garcons' => $elevesI1->where('SEXE', '1')->count(),
                'filles'  => $elevesI1->where('SEXE', '2')->count(),
                'total'   => $elevesI1->count(),
            ];

            // I2: Élèves avec STATUT = 0, STATUTG = 1 et note MS1
            $elevesI2 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUT == 0 &&
                    $eleve->STATUTG == 1 &&
                    !is_null($eleve->MS1) &&
                    floatval($eleve->MS1) != -1 &&
                    floatval($eleve->MS1) != 21;
            });

            $intervalesResultat['I2'] = [
                'garcons' => $elevesI2->where('SEXE', '1')->count(),
                'filles'  => $elevesI2->where('SEXE', '2')->count(),
                'total'   => $elevesI2->count(),
            ];

            // I3: Élèves avec STATUT = 1, STATUTG = 1 et note MS1
            $elevesI3 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUT == 1 &&
                    $eleve->STATUTG == 1 &&
                    !is_null($eleve->MS1) &&
                    floatval($eleve->MS1) != -1 &&
                    floatval($eleve->MS1) != 21;
            });

            $intervalesResultat['I3'] = [
                'garcons' => $elevesI3->where('SEXE', '1')->count(),
                'filles'  => $elevesI3->where('SEXE', '2')->count(),
                'total'   => $elevesI3->count(),
            ];

            // I4: Différence entre I1 et (I2 + I3)
            $totalI2I3 = $elevesI2->count() + $elevesI3->count();
            $totalI4 = max(0, $elevesI1->count() - $totalI2I3);

            // Calcul des garçons et filles pour I4
            $garconsI4 = max(0, $elevesI1->where('SEXE', '1')->count() -
                ($elevesI2->where('SEXE', '1')->count() + $elevesI3->where('SEXE', '1')->count()));

            $fillesI4 = max(0, $elevesI1->where('SEXE', '2')->count() -
                ($elevesI2->where('SEXE', '2')->count() + $elevesI3->where('SEXE', '2')->count()));

            $intervalesResultat['I4'] = [
                'garcons' => $garconsI4,
                'filles'  => $fillesI4,
                'total'   => $totalI4,
            ];

            $resultats[$groupLabel] = [
                'codePromo' => $groupLabel,
                'nbClasses' => $nombreClasses,
                'intervales' => $intervalesResultat,
                'moyenne_ref' => $data['moyenne_ref'] ?? 10.00,
            ];
        }

        return $resultats;
    }

    public function calculerEffectifs($data)
    {
        $classes = Classes::with('eleves')->get();
        $resultats = [];

        $groupes = $classes->groupBy(function ($classe) {
            return $this->getGroupLabel($classe);
        });

        foreach ($groupes as $groupLabel => $classesGroupe) {
            $elevesGroupe = collect();
            foreach ($classesGroupe as $classe) {
                $elevesGroupe = $elevesGroupe->merge($classe->eleves);
            }

            // Calcul des moyennes pour chaque élève
            $elevesAvecMoyennes = $elevesGroupe->map(function ($eleve) use ($data) {
                if ($data['periode'] == '4') {
                    $notes = collect([$eleve->MS1, $eleve->MS2, $eleve->MS3])
                        ->filter(function ($note) {
                            return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                        });

                    if ($notes->isEmpty()) {
                        return null;
                    }
                    $eleve->moyenne = $notes->avg();
                } else {
                    $colonne = 'MS' . $data['periode'];
                    $note = $eleve->{$colonne};

                    if (is_null($note) || floatval($note) == -1 || floatval($note) == 21) {
                        return null;
                    }
                    $eleve->moyenne = floatval($note);
                }
                return $eleve;
            })->filter();

            // Calcul des statistiques par sexe
            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles = $elevesAvecMoyennes->where('SEXE', '2');

            // Calcul des moyennes max et min
            $maxMoyenneGarcons = $garcons->max('moyenne');
            $minMoyenneGarcons = $garcons->min('moyenne');
            $maxMoyenneFilles = $filles->max('moyenne');
            $minMoyenneFilles = $filles->min('moyenne');

            // Calcul des effectifs par statut
            $elevesI1 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUTG == 1;
            });

            $elevesI2 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUT == 0 &&
                    $eleve->STATUTG == 1 &&
                    !is_null($eleve->MS1) &&
                    floatval($eleve->MS1) != -1 &&
                    floatval($eleve->MS1) != 21;
            });

            $elevesI3 = $elevesGroupe->filter(function ($eleve) {
                return $eleve->STATUT == 1 &&
                    $eleve->STATUTG == 1 &&
                    !is_null($eleve->MS1) &&
                    floatval($eleve->MS1) != -1 &&
                    floatval($eleve->MS1) != 21;
            });

            // Calcul des intervalles
            $intervalesResultat = [];
            foreach ($data['intervales'] as $intervalle => $valeurs) {
                $min = floatval($valeurs['min']);
                $max = floatval($valeurs['max']);

                $elevesDansIntervalle = $elevesAvecMoyennes->filter(function ($eleve) use ($min, $max) {
                    return $eleve->moyenne >= $min && $eleve->moyenne < $max;
                });

                $intervalesResultat[$intervalle] = [
                    'garcons' => $elevesDansIntervalle->where('SEXE', '1')->count(),
                    'filles'  => $elevesDansIntervalle->where('SEXE', '2')->count(),
                    'total'   => $elevesDansIntervalle->count(),
                ];
            }

            $resultats[$groupLabel] = [
                'max_moyenne_garcons' => $maxMoyenneGarcons,
                'max_moyenne_filles' => $maxMoyenneFilles,
                'min_moyenne_garcons' => $minMoyenneGarcons,
                'min_moyenne_filles' => $minMoyenneFilles,
                'total_eleves' => $elevesAvecMoyennes->count(),
                'total_garcons' => $garcons->count(),
                'total_filles' => $filles->count(),
                'effectifs_statut' => [
                    'I1' => [
                        'garcons' => $elevesI1->where('SEXE', '1')->count(),
                        'filles' => $elevesI1->where('SEXE', '2')->count(),
                        'total' => $elevesI1->count(),
                    ],
                    'I2' => [
                        'garcons' => $elevesI2->where('SEXE', '1')->count(),
                        'filles' => $elevesI2->where('SEXE', '2')->count(),
                        'total' => $elevesI2->count(),
                    ],
                    'I3' => [
                        'garcons' => $elevesI3->where('SEXE', '1')->count(),
                        'filles' => $elevesI3->where('SEXE', '2')->count(),
                        'total' => $elevesI3->count(),
                    ],
                ],
                'intervales' => $intervalesResultat,
            ];
        }

        return $resultats;
    }

    public function calculerStatistiquesDetaillees($data)
    {
        $classes = Classes::with('eleves')->get();
        $resultats = [];

        $groupes = $classes->groupBy(function ($classe) {
            return $this->getGroupLabel($classe);
        });

        foreach ($groupes as $groupLabel => $classesGroupe) {
            $elevesGroupe = collect();
            foreach ($classesGroupe as $classe) {
                $elevesGroupe = $elevesGroupe->merge($classe->eleves);
            }

            // Calcul des moyennes pour chaque élève
            $elevesAvecMoyennes = $elevesGroupe->map(function ($eleve) use ($data) {
                if ($data['periode'] == '4') {
                    $notes = collect([$eleve->MS1, $eleve->MS2, $eleve->MS3])
                        ->filter(function ($note) {
                            return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                        });

                    if ($notes->isEmpty()) {
                        return null;
                    }
                    $eleve->moyenne = $notes->avg();
                } else {
                    $colonne = 'MS' . $data['periode'];
                    $note = $eleve->{$colonne};

                    if (is_null($note) || floatval($note) == -1 || floatval($note) == 21) {
                        return null;
                    }
                    $eleve->moyenne = floatval($note);
                }
                return $eleve;
            })->filter();

            // Meilleur et plus faible élève
            $meilleurEleve = $elevesAvecMoyennes->sortByDesc('moyenne')->first();
            $plusFaibleEleve = $elevesAvecMoyennes->sortBy('moyenne')->first();

            // Calcul des abandons (élèves sans note)
            $abandons = $elevesGroupe->filter(function ($eleve) use ($data) {
                if ($data['periode'] == '4') {
                    return is_null($eleve->MS1) || is_null($eleve->MS2) || is_null($eleve->MS3);
                }
                $colonne = 'MS' . $data['periode'];
                return is_null($eleve->{$colonne});
            })->count();

            // Calcul du taux de réussite
            $elevesReussite = $elevesAvecMoyennes->filter(function ($eleve) use ($data) {
                return $eleve->moyenne >= $data['moyenne_ref'];
            });
            $tauxReussite = $elevesAvecMoyennes->count() > 0
                ? ($elevesReussite->count() / $elevesAvecMoyennes->count()) * 100
                : 0;

            // Répartition des moyennes
            $repartition = [
                'moins_6_5' => ['nombre' => 0, 'pourcentage' => 0],
                '6_5_10' => ['nombre' => 0, 'pourcentage' => 0],
                '10_12' => ['nombre' => 0, 'pourcentage' => 0],
                '12_14' => ['nombre' => 0, 'pourcentage' => 0],
                '14_16' => ['nombre' => 0, 'pourcentage' => 0],
                '16_18' => ['nombre' => 0, 'pourcentage' => 0],
                '18_20' => ['nombre' => 0, 'pourcentage' => 0],
            ];

            $totalEleves = $elevesAvecMoyennes->count();
            if ($totalEleves > 0) {
                $repartition['moins_6_5'] = [
                    'nombre' => $elevesAvecMoyennes->where('moyenne', '<', 6.5)->count(),
                    'pourcentage' => ($elevesAvecMoyennes->where('moyenne', '<', 6.5)->count() / $totalEleves) * 100
                ];
                $repartition['6_5_10'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [6.5, 10])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [6.5, 10])->count() / $totalEleves) * 100
                ];
                $repartition['10_12'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [10, 12])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [10, 12])->count() / $totalEleves) * 100
                ];
                $repartition['12_14'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [12, 14])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [12, 14])->count() / $totalEleves) * 100
                ];
                $repartition['14_16'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [14, 16])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [14, 16])->count() / $totalEleves) * 100
                ];
                $repartition['16_18'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [16, 18])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [16, 18])->count() / $totalEleves) * 100
                ];
                $repartition['18_20'] = [
                    'nombre' => $elevesAvecMoyennes->whereBetween('moyenne', [18, 20])->count(),
                    'pourcentage' => ($elevesAvecMoyennes->whereBetween('moyenne', [18, 20])->count() / $totalEleves) * 100
                ];
            }

            $resultats[$groupLabel] = [
                'effectif_total' => $elevesAvecMoyennes->count(),
                'meilleur_eleve' => [
                    'nom' => $meilleurEleve ? $meilleurEleve->NOM . ' ' . $meilleurEleve->PRENOM : '-',
                    'moyenne' => $meilleurEleve ? $meilleurEleve->moyenne : 0
                ],
                'plus_faible_eleve' => [
                    'nom' => $plusFaibleEleve ? $plusFaibleEleve->NOM . ' ' . $plusFaibleEleve->PRENOM : '-',
                    'moyenne' => $plusFaibleEleve ? $plusFaibleEleve->moyenne : 0
                ],
                'abandons' => $abandons,
                'taux_reussite' => $tauxReussite,
                'repartition' => $repartition
            ];
        }

        return $resultats;
    }
}