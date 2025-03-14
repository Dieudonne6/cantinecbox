<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NotesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $notes;
    protected $exportMoy;
    protected $exportDev1;
    protected $exportDev2;

    public function __construct(Collection $notes, $exportMoy = 1, $exportDev1 = 1, $exportDev2 = 1)
    {
        $this->notes = $notes;
        $this->exportMoy = $exportMoy == 1;
        $this->exportDev1 = $exportDev1 == 1;
        $this->exportDev2 = $exportDev2 == 1;
    }

    public function collection()
    {
        $grouped = $this->notes->groupBy('MATRICULE');
        $data = collect();
    
        foreach ($grouped as $matricule => $studentNotes) {
            // Calcul de la moyenne d'interrogation pour cet élève
            $totalInterro = $studentNotes->sum('interro');
            $countInterro = $studentNotes->count();
            $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
            $firstNote = $studentNotes->first();
    
            // On force le matricule en texte pour éviter la notation scientifique
            $row = [
                'Classe'          => $firstNote->eleve->CODECLAS,
                'MATRICULE'       => '="' . $firstNote->eleve->MATRICULEX . '"',
                'Nom et Prenom'   => $firstNote->eleve->NOM . ' ' . $firstNote->eleve->PRENOM,
            ];
    
            if ($this->exportMoy) {
                $mi = $firstNote->MI;
                // Remplacer la note par une chaîne vide si elle est égale à 21 ou -1
                $row['Moyenne Interro'] = ($mi == 21 || $mi == -1) ? '**.**' :  round($mi, 2);
            }
            if ($this->exportDev1) {
                $dev1 = $firstNote->DEV1;
                $row['DEV1'] = ($dev1 == 21 || $dev1 == -1) ? '**.**' : $dev1;
            }
            if ($this->exportDev2) {
                $dev2 = $firstNote->DEV2;
                $row['DEV2'] = ($dev2 == 21 || $dev2 == -1) ? '**.**' : $dev2;
            }
    
            $data->push($row);
        }
    
        return $data;
    }

    public function headings(): array
    {
        $headings = ['Classe', 'MATRICULE', 'Nom et Prenom'];
        if ($this->exportMoy) {
            $headings[] = 'Moyenne Interro';
        }
        if ($this->exportDev1) {
            $headings[] = 'DEV1';
        }
        if ($this->exportDev2) {
            $headings[] = 'DEV2';
        }
        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings());

        for ($i = 1; $i <= $columnCount; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            $sheet->getStyleByColumnAndRow($i, 1)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow($i, 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $rowCount = count($sheet->toArray()) + 1;
        foreach ($sheet->getRowIterator(1, $rowCount) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $cell->getStyle()->getBorders()->getAllBorders()->getColor()->setRGB('000000');
            }
        }

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFA500']],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
