<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MultipleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier et insérer des données dans la première table
        if (DB::table('moiscontrat')->count() == 0) {
            DB::table('moiscontrat')->insert([
                ['nom_moiscontrat' => 'Janvier'],
                ['nom_moiscontrat' => 'Fevrier'],
                ['nom_moiscontrat' => 'Mars'],
                ['nom_moiscontrat' => 'Avril'],
                ['nom_moiscontrat' => 'Mai'],
                ['nom_moiscontrat' => 'Juin'],
                ['nom_moiscontrat' => 'Juillet'],
                ['nom_moiscontrat' => 'Aout'],
                ['nom_moiscontrat' => 'Septembre'],
                ['nom_moiscontrat' => 'Octobre'],
                ['nom_moiscontrat' => 'Novembre'],
                ['nom_moiscontrat' => 'Decembre'],
                ['nom_moiscontrat' => 'Inscription'],
            ]);
        }

        // Vérifier et insérer des données dans la deuxième table
        if (DB::table('paramsfactures')->count() == 0) {
            DB::table('paramsfactures')->insert([
                ['type' => 'FV', 'taxe' => 'D'],
            ]);
        }
    }
}
