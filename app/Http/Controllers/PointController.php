<?php

namespace App\Http\Controllers;

use App\Models\Params2;
use App\Models\Scolarite;
use App\Models\Eleve;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

use PhpOffice\PhpWord\Shared\Html;

class PointController extends Controller
{
    
    public function index($matricule)
        {
        
            $rtfContent = Params2::first()->EnteteDoc;
            $document = new Document($rtfContent);
            $formatter = new HtmlFormatter();
            $enteteNonStyle = $formatter->Format($document);
            $entete = '
            <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                <style>
                    p { margin: 0; padding: 0; line-height: 1.2; }
                    span { display: inline-block; }
                </style>
                ' . $enteteNonStyle . '
            </div>
            ';

            $eleve = Eleve::where('MATRICULE', $matricule)->first();

            if (!$eleve) {
                return redirect()->back()->with('error', 'Aucun élève trouvé pour ce matricule.');
            }

            // Récupérer les paiements valides du matricule
            $paiements = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->select('DATEOP', 'AUTREF', 'MONTANT')
                ->orderBy('DATEOP', 'asc')
                ->get();

            //  Récupérer les libellés dans Params2
            $params = Params2::first(['LIBELF1', 'LIBELF2', 'LIBELF3', 'LIBELF4']);


            // Calcul du total payé
            $totalPaye = $paiements->sum('MONTANT');

            // Calcul de la scolarité restant
            $totalScolaritePaye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 1)
                ->sum('MONTANT');

            $scolariteRestant = $eleve->APAYER - $totalScolaritePaye;

            
            // Calcul de l’arriéré restant
            $totalArrierePaye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 2)
                ->sum('MONTANT');

            $arriereRestant = $eleve->ARRIERE - $totalArrierePaye;

            // Calcul de l’APE
            $totalapePaye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 5)
                ->sum('MONTANT');

            $apeRestant = $eleve->FRAIS3 - $totalapePaye;

            // Calcul de libelf1
            $totallibelf1Paye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 3)
                ->sum('MONTANT');

            $libelF1Restant = $eleve->FRAIS1 - $totallibelf1Paye;

             // Calcul de libelf2
            $totallibelf2Paye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 4)
                ->sum('MONTANT');

            $libelF2Restant = $eleve->FRAIS2 - $totallibelf2Paye;

             // Calcul de libelf4
            $totallibelf4Paye = Scolarite::where('MATRICULE', $matricule)
                ->where('VALIDE', 1)
                ->where('AUTREF', 6)
                ->sum('MONTANT');

            $libelF4Restant = $eleve->FRAIS4 - $totallibelf4Paye;

            $totalAPayer = $eleve->FRAIS2 + $eleve->FRAIS4 + $eleve->FRAIS3 + $eleve->FRAIS1 + $eleve->ARRIERE + $eleve->APAYER;

            $resteAPayer = $libelF1Restant + $libelF2Restant + $libelF4Restant + $apeRestant + $arriereRestant + $scolariteRestant;

            return view('point', compact('paiements', 'matricule', 'totalPaye', 'entete', 'eleve', 'params', 'arriereRestant', 'scolariteRestant', 'apeRestant', 'libelF4Restant', 'libelF2Restant', 'libelF1Restant', 'totalAPayer', 'resteAPayer'));
            
        }

        
}