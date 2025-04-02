<?php

namespace App\Imports;

use App\Models\Eleve;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Classes; // Assure-toi d'importer le modèle Classe



// use Maatwebsite\Excel\Imports\HeadingRowFormatter;

// HeadingRowFormatter::default('none');

class ElevesImport implements ToModel, WithHeadingRow
{
    /**
     * Cette méthode mappe une ligne du fichier Excel vers une instance de modèle Eleve.
     *
     * Assurez-vous que votre fichier Excel contient des entêtes correspondantes aux clés ci-dessous.
     * Par exemple, vos colonnes pourraient s'appeler : 
     * 'matricule', 'nom', 'prenom', 'sexe', 'redoublant', 'classe', 'date_naiss', 'lieu_de_naissance'
     */

    //  public function headingRow(): int
    // {
    //     return 1;
    // }


    private static $ordre = null;

    private function convertDate($date)
    {
        try {
            if (!empty(trim($date))) {
                // Vérifier si la date est un nombre (Excel stocke parfois les dates comme un timestamp)
                if (is_numeric($date)) {
                    $convertedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)
                        ->format('Y-m-d');
                } else {
                    // Vérifier les séparateurs de date
                    if (strpos($date, '/') !== false) {
                        $convertedDate = Carbon::createFromFormat('d/m/Y', trim($date))->format('Y-m-d');
                    } elseif (strpos($date, '-') !== false) {
                        $convertedDate = Carbon::createFromFormat('Y-m-d', trim($date))->format('Y-m-d');
                    } else {
                        throw new \Exception("Format de date inconnu");
                    }
                }
    
                Log::info("Date convertie : $convertedDate");
                return $convertedDate;
            }
        } catch (\Exception $e) {
            Log::error("Erreur de conversion de la date : " . $e->getMessage() . " | Valeur reçue : " . json_encode($date));
            return null;
        }
        return null;
    }

    public function model(array $row)
    {
        if (self::$ordre === null) {
            // Récupère le dernier numéro d'ordre existant en base
            self::$ordre = Eleve::max('MATRICULE') ?? 0;
        }
    
        return !empty(array_filter($row)) ? new Eleve([
            'MATRICULE'  => ++self::$ordre,
            'MATRICULEX' => isset($row['matricule']) ? (string) trim($row['matricule']) : null,
            'NOM'        => $row['nom'] ?? null,
            'PRENOM'     => $row['prenom'] ?? null,
            'SEXE'       => isset($row['sexe']) 
                ? (strtolower(trim($row['sexe'])) == 'féminin' ? 2 : 1) 
                : null,                
            'STATUT'     => isset($row['redoublant']) ? intval(trim($row['redoublant'])) : null,
            'CODECLAS'   => $row['classe'] ?? null, 
            'DATENAIS'   => $this->convertDate($row['date_naiss'] ?? null),
            'LIEUNAIS'   => $row['lieu_de_naissance'] ?? null,
        ]) : null;
    }


    
}
