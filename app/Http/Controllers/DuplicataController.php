<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturenormalise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Contrat;
use App\Models\Eleve;
use App\Models\Params2;
use App\Models\Facturenormaliseinscription;
use Illuminate\Support\Facades\Validator;

use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

class DuplicataController extends Controller
{      
    // Afficher le formulaire avec les données nécessaires
    public function showForm()
    {
        $eleves = Eleve::all();
        $facturesPaiement = Facturenormalise::all();
        $facturesInscription = Contrat::all();

        return view('pages.Etats.filterduplicata', [
            'eleves' => $eleves,
            'facturesPaiement' => $facturesPaiement,
            'facturesInscription' => $facturesInscription
        ]);
    }
    
    public function filterduplicata(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'eleve_id' => 'required_if:facture_type,!=,',
            'facture_type' => 'required_if:eleve_id,!=,',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $idEleve = $request->input('eleve_id');
        $factureType = $request->input('facture_type');
    
        $facture = null;
        $contrat = null;
        $message = null;
    
        // Vérification et récupération des données en fonction du type de facture
        if ($factureType) {
            if ($factureType === 'facturenormalises') {
                $facture = Facturenormalise::where('MATRICULE', $idEleve)->where('statut', 1)->get();
                if ($facture->isEmpty()) {
                    $message = 'Aucune facture de paiement trouvée pour l\'élève sélectionné.';
                }
            } elseif ($factureType === 'contrat') {
                $contrat = Facturenormaliseinscription::where('MATRICULE', $idEleve)->where('statut', 1)->get();
                // dd($contrat);
                if ($contrat->isEmpty()) {
                    $message = 'Aucun contrat trouvé pour l\'élève sélectionné.';
                }
            }
        } else {
            $message = 'Veuillez sélectionner un type de facture.';
        }
    
        $eleves = Eleve::all();
        $facturesPaiement = Facturenormalise::all();
        $facturesInscription = Facturenormaliseinscription::all();
    
        return view('pages.Etats.filterduplicata', [
            'eleves' => $eleves,
            'facturesPaiement' => $facturesPaiement,
            'facturesInscription' => $facturesInscription,
            'factures' => $facture,
            'contrats' => $contrat,
            'message' => $message,
        ]);
    }
    
    
    
    

    public function pdfduplicatacontrat($idcontrat)
    {
        $factureIns = Contrat::where('id_contrat', $idcontrat)->first();
        $infoeleves = Eleve::where('MATRICULE', $factureIns->eleve_contrat)->first();
        $nomcompeleve = $infoeleves->NOM .' '. $infoeleves->PRENOM;
        $classeeleve = $infoeleves->CODECLAS;
        $infoecole = Params2::first();
        $nomecole = $infoecole->NOMETAB;
        $ifuEcole = $infoecole->ifu;
        $logo = $infoecole->logoimage;

        Session::put('factureIns', $factureIns);
        Session::put('nomcompeleve', $nomcompeleve);
        Session::put('classeeleve', $classeeleve);
        Session::put('logo', $logo);
        Session::put('ifuEcole', $ifuEcole);
        Session::put('nomecole', $nomecole);

        return view('pages.Etats.pdfduplicatacontrat', compact('nomcompeleve', 'classeeleve', 'nomecole', 'ifuEcole', 'logo', 'factureIns'));
    }

    public function pdfduplicatapaie($counters)
    {
        $counters1 = substr_replace(preg_replace('/_/', '/', $counters, 1), ' ', -2, 0);
        // dd($counters1);


        $facturePaie = DB::table('facturenormalises')
            ->where('counters', $counters1)
            ->first();

                    $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
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

            // dd($facturePaie);

        $infoecole = DB::table('params2')->first();
        $nomecole = $infoecole->NOMETAB;
        $logo = $infoecole->logoimage;
        $jsonItem = $facturePaie->itemfacture;
        $donneItem = json_decode($jsonItem);
        // dd($facturePaie);

        return view('pages.Etats.pdfduplicatapaie', compact('nomecole', 'logo', 'facturePaie', 'donneItem', 'entete'));
    }

    public function pdfduplicatainscription($counters)
    {
        $counters1 = substr_replace(preg_replace('/_/', '/', $counters, 1), ' ', -2, 0);
        // dd($counters1);


        $facturePaie = DB::table('facturenormaliseinscription')
            ->where('counters', $counters1)
            ->first();

                    $rtfContent = Params2::first()->EnteteRecu;
        // dd($rtfContent);
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
            // dd($facturePaie);

        $infoecole = DB::table('params2')->first();
        $nomecole = $infoecole->NOMETAB;
        $logo = $infoecole->logoimage;
        // $jsonItem = $facturePaie->itemfacture;
        // $donneItem = json_decode($jsonItem);
        // dd($facturePaie);

        return view('pages.Etats.pdfduplicatainscription', compact('nomecole', 'logo', 'facturePaie', 'entete'));
    }

    public function duplicatainscription2()
    {
        $amount = Session::get('amount');
        $classe = Session::get('classe');
        $logoUrl = Session::get('logoUrl');
        $dateContrat = Session::get('dateContrat');
        $elevyo = Session::get('elevyo');
        $data = [
            'amount' => $amount,
            'classe' => $classe,
            'logoUrl' => $logoUrl,
            'dateContrat' => $dateContrat,
            'elevyo' => $elevyo,
        ];

        $factureIns = Session::get('factureIns');
        $nomcompeleve = Session::get('nomcompeleve');
        $logo = Session::get('logo');
        $classeeleve = Session::get('classeeleve');
        $ifuEcole = Session::get('ifuEcole');
        $nomecole = Session::get('nomecole');
    
        $fileName = $elevyo . time() . '.pdf';
        $filePaths = public_path('pdfs/' . $fileName);
    
        if (!file_exists(public_path('pdfs'))) {
            mkdir(public_path('pdfs'), 0755, true);
        }

        return view('pages.Etats.duplicatainscription2', [
            'amount' => $amount,
            'classe' => $classe,
            'logoUrl' => $logoUrl,
            'dateContrat' => $dateContrat,
            'elevyo' => $elevyo,
            'factureIns' => $factureIns,
            'nomcompeleve' => $nomcompeleve,
            'classeeleve' => $classeeleve,
            'logo' => $logo,
            'ifuEcole' => $ifuEcole,
            'nomecole' => $nomecole,
        ]);  
    }
}