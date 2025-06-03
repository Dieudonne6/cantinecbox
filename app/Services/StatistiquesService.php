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
                    $notes = collect([$eleve->MS1, $eleve->MS2, $eleve->MS3])
                        ->filter(function ($note) {
                            return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                        });

                    if ($notes->isEmpty()) {
                        return null;
                    }
                    $eleve->moyenne = $notes->avg();
                } else {
                    $note = $eleve->{$colonne};
                    if (is_null($note) || floatval($note) == -1 || floatval($note) == 21) {
                        return null;
                    }
                    $eleve->moyenne = floatval($note);
                }
                return $eleve;
            })->filter();

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

        foreach ($classes as $classe) {
            $eleves = $classe->eleves;
            $effectifTotal = $eleves->count();

            // Initialiser les compteurs pour chaque intervalle
            $compteursIntervalles = [];
            foreach ($data['intervales'] as $intervalle => $valeurs) {
                $compteursIntervalles[$intervalle] = 0;
            }

            $meilleurEleve = null;
            $plusFaibleEleve = null;
            $abandons = 0;
            $elevesAvecMoyenne = 0;
            $elevesReussite = 0;

            foreach ($eleves as $eleve) {
                // Déterminer la colonne de moyenne à utiliser selon la période
                if ($data['periode'] == '4') {
                    // Pour l'annuel, on calcule la moyenne des 3 périodes
                    $notes = collect([$eleve->MS1, $eleve->MS2, $eleve->MS3])
                        ->filter(function ($note) {
                            return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                        });

                    if ($notes->isEmpty()) {
                        $abandons++;
                        continue;
                    }
                    $moyenne = $notes->avg();
                } else {
                    $colonne = 'MS' . $data['periode'];
                    $moyenne = $eleve->{$colonne};

                    if (is_null($moyenne) || floatval($moyenne) == -1 || floatval($moyenne) == 21) {
                        $abandons++;
                        continue;
                    }
                    $moyenne = floatval($moyenne);
                }

                $elevesAvecMoyenne++;

                // Classer la moyenne dans l'intervalle approprié
                foreach ($data['intervales'] as $intervalle => $valeurs) {
                    $min = floatval($valeurs['min']);
                    $max = floatval($valeurs['max']);
                    if ($moyenne >= $min && $moyenne < $max) {
                        $compteursIntervalles[$intervalle]++;
                        break;
                    }
                }

                // Mettre à jour le meilleur et plus faible élève
                if ($meilleurEleve === null || $moyenne > $meilleurEleve['moyenne']) {
                    $meilleurEleve = [
                        'nom' => $eleve->NOM . ' ' . $eleve->PRENOM,
                        'moyenne' => $moyenne
                    ];
                }
                if ($plusFaibleEleve === null || $moyenne < $plusFaibleEleve['moyenne']) {
                    $plusFaibleEleve = [
                        'nom' => $eleve->NOM . ' ' . $eleve->PRENOM,
                        'moyenne' => $moyenne
                    ];
                }

                // Compter les élèves ayant réussi
                if ($moyenne >= $data['moyenne_ref']) {
                    $elevesReussite++;
                }
            }

            // Calculer le taux de réussite
            $tauxReussite = $elevesAvecMoyenne > 0 ? ($elevesReussite / $elevesAvecMoyenne) * 100 : 0;

            $resultats[$classe->LIBELCLAS] = [
                'effectif_total' => $effectifTotal,
                'intervales' => $compteursIntervalles,
                'meilleur_eleve' => $meilleurEleve,
                'plus_faible_eleve' => $plusFaibleEleve,
                'abandons' => $abandons,
                'taux_reussite' => $tauxReussite
            ];
        }

        return $resultats;
    }
}