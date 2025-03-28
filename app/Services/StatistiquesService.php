<?php

namespace App\Services;

use App\Models\Classes;



class StatistiquesService
{

protected function getGroupLabel($classe)
{
    // On peut prévoir des correspondances (mapping) pour que "6EME" s'affiche "6è", etc.
    $mapPromosCycle1 = [
        '6EME' => '6è',
        '5EME' => '5è',
        '4EME' => '4è',
        '3EME' => '3è',
    ];

    // Mapping pour transformer "2ND" en "2nd", "1ERE" en "1ère", etc.
    $mapPromosCycle2 = [
        '2ND'  => '2nd',
        '1ERE' => '1ère',
        'TLE'  => 'Tle',
    ];

    // On détermine si on est en cycle 1 ou cycle 2
    // (ou plus finement, si vous avez cycle=2,3,4, etc. pour seconde, première, terminale)
    if ($classe->CYCLE == '1') {
        // Cycle I => 6è, 5è, 4è, 3è
        // On remplace avec le mapping si possible
        return $mapPromosCycle1[$classe->CODEPROMO] ?? $classe->CODEPROMO;
    } else {
        // Cycle II => On combine CODEPROMO + SERIE (ex : 2ndA1, 1èreA2, TleB, etc.)
        // Attention à la casse et à la correspondance
        $promo = $mapPromosCycle2[$classe->CODEPROMO] ?? $classe->CODEPROMO;
        $serie = $classe->SERIE; 
        // Concaténation, ex : "2ndA1"
        return $promo . $serie;
    }
}

    public function calculerStatistiques($data)
    {
        // Déterminer la colonne de moyenne selon la période
        if ($data['periode'] == '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] == '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] == '3') {
            $colonne = 'MS3';
        } else {
            $colonne = null;
        }
        
        // Récupérer toutes les classes avec leurs élèves
        $classes = Classes::with('eleves', 'promo')->get();
        $resultats = [];
    
        // Groupement conditionnel :
        // - Pour le premier cycle (ex. CYCLE == '1') on regroupe par CODEPROMO (6è, 5è, 4è, 3è)
        // - Pour le second cycle (et pour première, terminale) on regroupe par SERIE (ex. 2ndA1, 2ndA2, etc.)
        $groupes = $classes->groupBy(function($classe) {
            return $this->getGroupLabel($classe);
        });
    
        foreach ($groupes as $groupeKey => $classesGroupe) {
            $elevesGroupe = collect();
    
            foreach ($classesGroupe as $classe) {
                // Filtrer les élèves qui ont une moyenne renseignée pour la période sélectionnée
                $elevesFiltrés = $classe->eleves->filter(function ($eleve) use ($colonne) {
                    if (is_null($colonne)) {
                        return !is_null($eleve->MS1) && floatval($eleve->MS1) != -1 && floatval($eleve->MS1) != 21
                            && !is_null($eleve->MS2) && floatval($eleve->MS2) != -1 && floatval($eleve->MS2) != 21
                            && !is_null($eleve->MS3) && floatval($eleve->MS3) != -1 && floatval($eleve->MS3) != 21;
                    }
                    $note = $eleve->{$colonne};
                    return !is_null($note) && floatval($note) != -1 && floatval($note) != 21;
                });
    
                // Fusionner les élèves filtrés
                $elevesGroupe = $elevesGroupe->merge($elevesFiltrés);
            }
    
            // Calculer la moyenne pour chaque élève
            $elevesAvecMoyennes = $elevesGroupe->map(function ($eleve) use ($colonne) {
                if (is_null($colonne)) {
                    $moyenne = ($eleve->MS1 + $eleve->MS2 + $eleve->MS3) / 3;
                } else {
                    $moyenne = $eleve->{$colonne};
                }
                $eleve->moyenne = $moyenne;
                return $eleve;
            });
    
            // Séparation par sexe
            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles  = $elevesAvecMoyennes->where('SEXE', '2');
    
            // Calcul des moyennes maximales et minimales par sexe
            $maxMoyenneGarcons = $garcons->max('moyenne');
            $minMoyenneGarcons = $garcons->min('moyenne');
            $maxMoyenneFilles  = $filles->max('moyenne');
            $minMoyenneFilles  = $filles->min('moyenne');
    
            // Calcul des intervalles
            $intervalesResultats = $this->calculerIntervales($elevesAvecMoyennes, $data['intervales']);
    
            // Stockage des résultats pour ce groupe (promotion ou série)
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
            $min = $valeurs['min'];
            $max = $valeurs['max'];

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
}
