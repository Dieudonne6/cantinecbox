<?php

namespace App\Services;

use App\Models\Classes;
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

    public function calculerSynoptique(array $data)
    {
        // 1. Choix dynamique de la colonne selon la période
        if ($data['periode'] === '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] === '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] === '3') {
            $colonne = 'MS3';
        } else {
            // Annuel
            $colonne = 'MAN';
        }
    
        $classes   = Classes::with('eleves')->get();
        $resultats = [];
    
        // 2. Regroupement par groupe
        $groupes = $classes->groupBy(fn($classe) => $this->getGroupLabel($classe));
        
        foreach ($groupes as $groupLabel => $classesGroupe) {
            // 3. Fusionner tous les élèves du groupe
            $elevesGroupe = $classesGroupe->flatMap->eleves;
            
            /* dd(count($elevesGroupe)); */
            // 4. I1 : élèves inscrits (STATUTG = 1)
            $elevesI1 = $elevesGroupe->filter(fn($e) => $e->STATUTG == 1);
    
            // 5. I2 : STATUT = 0, STATUTG = 1 et note valide en $colonne
            $elevesI2 = $elevesI1->filter(fn($e) =>
                $e->STATUT  == 0 &&
                !is_null($e->{$colonne}) &&
                floatval($e->{$colonne}) !== 21
            );
    
            // 6. I3 : STATUT = 1, STATUTG = 1 et note valide en $colonne
            $elevesI3 = $elevesI1->filter(fn($e) =>
                $e->STATUT  == 1 &&
                !is_null($e->{$colonne}) &&
                floatval($e->{$colonne}) !== 21
            );
    
            $elevesI4 = $elevesI1->filter(function ($e) use ($colonne) {
                $note = $e->{$colonne};
                return is_null($note)
                    || floatval($note) === -1.0
                    || floatval($note) === 21.0;
            });
                /* dd(count($elevesI4)); */
            // 8. Construction du tableau des intervalles
            $intervalesResultat = [
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
                'I4' => [
                    'garcons' => $elevesI4->where('SEXE','1')->count(),
                    'filles'  => $elevesI4->where('SEXE','2')->count(),
                    'total'   => $elevesI4->count(),
                ],
            ];
    
            // 9. Sauvegarde du résultat pour ce groupe
            $resultats[$groupLabel] = [
                'codePromo'   => $groupLabel,
                'nbClasses'   => $classesGroupe->count(),
                'intervales'  => $intervalesResultat,
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
            ->where('CODEPROMO', '!=', '1')
            ->where('CODEPROMO', '!=', '00')
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
            ];
        }
    
        return $resultats;
    }
    
}