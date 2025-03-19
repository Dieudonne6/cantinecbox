<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NotesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents, WithCustomStartCell
{
    protected $nomMatiere;
    protected $notes;
    protected $classe;
    protected $periode;
    protected $periodLabel;
    protected $exportMoy;
    protected $exportDev1;
    protected $exportDev2;

    /**
     * @param object $nomMatiere Objet Matiere (par exemple, récupéré via Matiere::where(...)->first())
     * @param Collection $notes
     * @param string $classe
     * @param string $periode
     * @param string $periodLabel
     */
    public function __construct($nomMatiere, Collection $notes, $classe, $periode, $periodLabel, $exportMoy = 1, $exportDev1 = 1, $exportDev2 = 1)
    {
        $this->nomMatiere = $nomMatiere;
        $this->notes = $notes;
        $this->classe = $classe;
        $this->periode = $periode;
        $this->periodLabel = $periodLabel;
        $this->exportMoy = $exportMoy == 1;
        $this->exportDev1 = $exportDev1 == 1;
        $this->exportDev2 = $exportDev2 == 1;
    }

    // On ne conserve ici que les colonnes propres aux élèves (sans classe ni matière)
    public function collection()
    {
        $grouped = $this->notes->groupBy('MATRICULE');
        $data = collect();

        foreach ($grouped as $matricule => $studentNotes) {
            $totalInterro = $studentNotes->sum('interro');
            $countInterro = $studentNotes->count();
            $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
            $firstNote = $studentNotes->first();

            $row = [
                'MATRICULE'    => "\u{200B}" . $firstNote->eleve->MATRICULEX,
                'Nom et Prenom'=> $firstNote->eleve->NOM . ' ' . $firstNote->eleve->PRENOM,
            ];

            if ($this->exportMoy) {
                $mi = $firstNote->MI;
                $row['Moyenne Interro'] = ($mi == 21 || $mi == -1) ? '**.**' : round($mi, 2);
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

    // Les en-têtes commencent à partir de la ligne de données (à partir de A3)
    public function headings(): array
    {
        $headings = ['MATRICULE', 'Nom et Prenom'];
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

    // Indiquer que les données commencent à partir de A3
    public function startCell(): string
    {
        return 'A2';
    }

    // Ajouter un en-tête personnalisé au-dessus du tableau
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // Fusionner des cellules pour afficher l'en-tête sur la première ligne.
                // Ici, on fusionne A1:E1 (ajustez selon le nombre de colonnes)
                $sheet->mergeCells('A1:E1');
                $header = 'Classe : ' . $this->classe 
                        . ' | Matière : ' . $this->nomMatiere->LIBELMAT 
                        . ' | ' . $this->periodLabel . ' : ' . $this->periode;
                $sheet->setCellValue('A1', $header);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings());
        // Appliquer le style aux en-têtes de données (qui commencent à la ligne 3)
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

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '00000']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFA500']],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // On force la colonne MATRICULE en texte
        ];
    }
}
