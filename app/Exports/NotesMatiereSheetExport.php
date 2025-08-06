<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NotesMatiereSheetExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnFormatting, WithEvents, WithCustomStartCell
{
    protected $notes;
    protected $matiereLibelle;

    /**
     * @param Collection $notes Les notes (avec relation "eleve") pour cette matière
     * @param string $matiereLibelle Le libellé de la matière
     */
    public function __construct(Collection $notes, $matiereLibelle)
    {
        $this->notes = $notes;
        $this->matiereLibelle = $matiereLibelle;
    }

    // Transforme les notes groupées par matricule en une collection plate pour l'export
    public function collection()
    {
        $grouped = $this->notes->groupBy('MATRICULE');
        $data = collect();

        foreach ($grouped as $matricule => $studentNotes) {
            $totalInterro = $studentNotes->sum('interro');
            $countInterro = $studentNotes->count();
            $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
            $firstNote = $studentNotes->first();
            
            $data->push([
                'MATRICULE'     => "\u{200B}" . $firstNote->eleve->MATRICULEX,
                'Nom' => $firstNote->eleve->NOM,
                'Prenom' => $firstNote->eleve->PRENOM,
                'Moyenne Interro' => ($firstNote->MI == 21 || $firstNote->MI == -1) ? '**.**' : round($firstNote->MI, 2),
                'DEV1'          => ($firstNote->DEV1 == 21 || $firstNote->DEV1 == -1) ? '**.**' : $firstNote->DEV1,
                'DEV2'          => ($firstNote->DEV2 == 21 || $firstNote->DEV2 == -1) ? '**.**' : $firstNote->DEV2,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['MATRICULE', 'Nom', 'Prenom', 'Moyenne Interro', 'DEV1', 'DEV2'];
    }

    // Début des données à partir de la cellule A2 (la ligne 1 sera utilisée pour l'en-tête personnalisé)
    public function startCell(): string
    {
        return 'A2';
    }

    // Définir le titre de la feuille, limité à 31 caractères (Excel)
    public function title(): string
    {
        return substr($this->matiereLibelle, 0, 31);
    }

    // Ajouter l'en-tête personnalisé (dans la ligne 1) pour cette feuille
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
    
                // 1) Fusion et en-tête personnalisé
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $this->matiereLibelle);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
                // 2) (Vos autres styles habituels : bordures, en-têtes, etc.)
                $columnCount = count($this->headings());
                for ($i = 1; $i <= $columnCount; $i++) {
                    $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
                    $sheet->getStyleByColumnAndRow($i, 2)->getFont()->setBold(true);
                    $sheet->getStyleByColumnAndRow($i, 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $rowCount = count($sheet->toArray());
                foreach ($sheet->getRowIterator(1, $rowCount) as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        $cell->getStyle()->getBorders()->getAllBorders()->getColor()->setRGB('000000');
                    }
                }
    
                // 3) Suppression de la toute dernière ligne si elle est vide
                $highestColumn = $sheet->getHighestColumn();   // ex. 'E'
                $highestRow    = $sheet->getHighestRow();      // ex. 25
    
                // Récupère la ligne entière sous forme de tableau
                $lastRowValues = $sheet
                    ->rangeToArray("A{$highestRow}:{$highestColumn}{$highestRow}", 
                                   null, true, false)[0];
    
                // Filtre les cellules non-vides
                $nonEmptyCells = array_filter($lastRowValues, function($cell) {
                    return !is_null($cell) && trim((string)$cell) !== '';
                });
    
                // Si aucun élément non-vide, on enlève la ligne
                if (count($nonEmptyCells) === 0) {
                    $sheet->removeRow($highestRow, 1);
                }
            },
        ];
    }
    
    

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings());
        // Appliquer le style aux en-têtes de données (ligne 2)
        for ($i = 1; $i <= $columnCount; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            $sheet->getStyleByColumnAndRow($i, 2)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow($i, 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Appliquer des bordures fines à toutes les cellules
        $rowCount = count($sheet->toArray()) + 1;
        foreach ($sheet->getRowIterator(1, $rowCount) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $cell->getStyle()->getBorders()->getAllBorders()->getColor()->setRGB('000000');
            }
        }

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Forcer la colonne MATRICULE en texte
        ];
    }
}
