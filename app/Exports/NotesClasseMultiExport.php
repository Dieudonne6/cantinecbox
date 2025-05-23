<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotesClasseMultiExport implements WithMultipleSheets
{
    protected $result;    // Tableau associatif : [CODEMAT => collection de notes]
    protected $matieres;  // Collection d'objets Matieres

    public function __construct(array $result, $matieres)
    {
        $this->result = $result;
        $this->matieres = $matieres;
    }

    public function sheets(): array
    {
        $sheets = [];
        // Pour chaque matière pour laquelle on a des notes, créer une feuille
        foreach ($this->result as $matiereCode => $notes) {
            $matiere = $this->matieres->firstWhere('CODEMAT', $matiereCode);
            $libelle = $matiere ? $matiere->LIBELMAT : $matiereCode;
            $sheets[] = new NotesMatiereSheetExport($notes, $libelle);
        }
        return $sheets;
    }
}
