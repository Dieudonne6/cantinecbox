<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Eleve;
use App\Models\Notes;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer tous les élèves
        $eleves = Eleve::all();

        foreach ($eleves as $eleve) {
            // Vérifier si l'élève a déjà des notes
            $existingNotes = Notes::where('matricule', $eleve->MATRICULE)->exists();

            if (!$existingNotes) {
                // Ajouter des notes initiales à zéro pour l'élève
                Notes::create([
                    'matricule' => $eleve->MATRICULE,
                    'CODEMAT' => 'MAT001', // Assurez-vous d'utiliser les bons codes de matière
                    'MI' => 0,
                    'DEV1' => 0,
                    'DEV2' => 0,
                    'DEV3' => 0,
                    'TEST' => 0,
                    'MS' => 0,
                ]);
            }
        }
    }
}
