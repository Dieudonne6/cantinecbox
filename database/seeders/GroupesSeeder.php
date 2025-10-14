<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Groupe;

class GroupesSeeder extends Seeder
{
    public function run()
    {
        $groupes = [
            'ADMINISTRATEUR',
            'Directeur',
            'Secrétaire Général',
            'Comptable',
            'Secrétaire Académique',
            'Enseignant',
            'Surveillant',
            'Agent d\'Accueil',
            'Opérateur de Saisie'
        ];

        foreach ($groupes as $nomGroupe) {
            Groupe::firstOrCreate([
                'nomgroupe' => $nomGroupe
            ]);
        }

        echo "✅ Groupes de base créés avec succès!\n";
    }
}
