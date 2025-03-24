<?php

namespace App\Services;

use App\Models\Classes;

class StatistiquesService
{
    public function calculerStatistiques($data)
    {
        // Déterminer la colonne de moyenne selon la période
        // Pour Annuel (4), vous pouvez décider de calculer une moyenne globale ou choisir une colonne existante
        if ($data['periode'] == '1') {
            $colonne = 'MS1';
        } elseif ($data['periode'] == '2') {
            $colonne = 'MS2';
        } elseif ($data['periode'] == '3') {
            $colonne = 'MS3';
        } else {
            // Exemple de calcul pour l'annuel : moyenne de MS1, MS2 et MS3
            $colonne = null;
        }
        
        // Récupérer toutes les classes avec leurs élèves, en incluant CODEPROMO
        $classes = Classes::with('eleves', 'promo')->get();
        $resultats = [];

        // Regrouper les élèves par CODEPROMO
        $groupesParPromo = $classes->groupBy('CODEPROMO');

        foreach ($groupesParPromo as $codePromo => $classesPromo) {
            $elevesPromo = collect();

            foreach ($classesPromo as $classe) {
                // Filtrer les élèves qui ont une moyenne renseignée pour la période sélectionnée
                $elevesFiltrés = $classe->eleves->filter(function ($eleve) use ($colonne, $data) {
                    // Pour une moyenne annuelle, on s'assure que MS1, MS2 et MS3 sont renseignés
                    if (is_null($colonne)) {
                        return !is_null($eleve->MS1) && !is_null($eleve->MS2) && !is_null($eleve->MS3);
                    }
                    return !is_null($eleve->{$colonne});
                });
                
                // Ajouter les élèves filtrés à la collection de la promo
                $elevesPromo = $elevesPromo->merge($elevesFiltrés);
            }

            // Attribuer à chaque élève sa moyenne en fonction de la période
            $elevesAvecMoyennes = $elevesPromo->map(function ($eleve) use ($colonne) {
                if (is_null($colonne)) {
                    // Calcul de la moyenne annuelle
                    $moyenne = ($eleve->MS1 + $eleve->MS2 + $eleve->MS3) / 3;
                } else {
                    $moyenne = $eleve->{$colonne};
                }
                $eleve->moyenne = $moyenne;
                return $eleve;
            });

            // Séparer par sexe
            $garcons = $elevesAvecMoyennes->where('SEXE', '1');
            $filles  = $elevesAvecMoyennes->where('SEXE', '2');

            // Comparer pour obtenir la moyenne la plus forte et la plus faible
            $maxMoyenneGarcons = $garcons->max('moyenne');
            $minMoyenneGarcons = $garcons->min('moyenne');
            $maxMoyenneFilles  = $filles->max('moyenne');
            $minMoyenneFilles  = $filles->min('moyenne');

            // Calculer le nombre d'élèves dans chaque intervalle
            $intervalesResultats = $this->calculerIntervales($elevesAvecMoyennes, $data['intervales']);

            // Stocker les résultats pour cette promotion
            $resultats[$codePromo] = [
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
