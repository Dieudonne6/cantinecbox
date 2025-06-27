<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notes;

class RecomputeSem2MI_MS extends Command
{
    protected $signature   = 'notes:recompute-sem2-mi-ms';
    protected $description = 'Recalcule MI et MS pour SEMESTRE=2 (NULL->21 et moyennes selon vos règles)';

    public function handle()
    {
        $this->info("Début du recalcul MI / MS pour SEMESTRE = 2…");

        Notes::where('SEMESTRE', 2)
            ->chunkById(200, function($rows) {
                foreach ($rows as $note) {
                    // 1) Remplacer NULL par 21 pour INT1..INT10, DEV1, DEV2
                    foreach (array_merge(
                        array_map(fn($i) => "INT{$i}", range(1,10)),
                        ['DEV1','DEV2','DEV3','TEST']
                    ) as $col) {
                        if (is_null($note->$col)) {
                            $note->$col = 21;
                        }
                    }
                    // 2) Remplacer DEV3 et TEST qui valent 0 par 21
                    foreach (['DEV3','TEST'] as $col) {
                        if ($note->$col == 0) {
                            $note->$col = 21;
                        }
                    }

                    // 3) Calcul MI = moyenne des INTx valides (≠ -1, ≠ 21), ou 21 si aucune
                    $valsInt = [];
                    for ($i = 1; $i <= 10; $i++) {
                        $v = $note->{"INT{$i}"};
                        if ($v != -1 && $v != 21) {
                            $valsInt[] = $v;
                        }
                    }
                    $mi = count($valsInt)
                        ? round(array_sum($valsInt) / count($valsInt), 2)
                        : 21;

                    // 4) Collecte des devoirs valides (DEV1, DEV2, DEV3)
                    $devVals = [];
                    foreach (['DEV1','DEV2','DEV3'] as $d) {
                        $v = $note->$d;
                        if ($v !== null && $v != -1 && $v != 21) {
                            $devVals[] = $v;
                        }
                    }
                    $devCount = count($devVals);
                    $sumDevs  = array_sum($devVals);

                    // 5) Calcul MS: si ni interro valide ni devoir valide → 21
                    if ($mi === 21 && $devCount === 0) {
                        $ms = 21;
                    } elseif ($mi === 21 && $devCount === 1) {
                        // cas de conduite
                        $ms = $sumDevs;
                    } else {
                        // MS = (MI + somme devoirs) / (devCount + 1)
                        $ms = round(($mi + $sumDevs) / ($devCount + 1), 2);
                    }

                    // 6) Mise à jour et sauvegarde
                    $note->MI = $mi;
                    $note->MS = $ms;
                    $note->MS1 = $ms;
                    $note->save();
                }
            });

        $this->info("Recalcul terminé !");
    }
}
