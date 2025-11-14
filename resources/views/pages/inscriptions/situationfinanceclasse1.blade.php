@extends('layouts.master')
@section('content')
@php
    use Carbon\Carbon; // Assurez-vous d'importer la classe Carbon
    $dateDuJour = Carbon::now()->locale('fr')->isoFormat('LL'); // Obtenir la date du jour au format souhaité
@endphp
    <style>
        p{
            font-size: 20px;
        }
    </style>
    <div class="main-panel-10">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div>
                    <style>
                        .btn-arrow {
                            position: absolute;
                            top: 0px;
                            /* Ajustez la position verticale */
                            left: 0px;
                            /* Positionnez à gauche */
                            background-color: transparent !important;
                            border: 1px !important;
                            text-transform: uppercase !important;
                            font-weight: bold !important;
                            cursor: pointer !important;
                            font-size: 17px !important;
                            /* Taille de l'icône */
                            color: #b51818 !important;
                            /* Couleur de l'icône */
                        }
                
                        .btn-arrow:hover {
                            color: #b700ff !important;
                            /* Couleur au survol */
                        }

                        .select2-container--default .select2-selection--multiple .select2-selection__rendered { 
                            width: 200px;
                        }
                    </style>
                    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <br>
                    <br>                                     
                </div>
                <div class="card-body">
                    <h4 class="card-title" id="title"><strong>Situation financière selon échéancier a la date du {{ \Carbon\Carbon::now()->format('d/m/Y') }} </strong> </h4>
                    <div class="row mb-1">
                        <div class="col-3">
                            <label for="">Classe</label>
                           <select class="js-example-basic-multiple w-100" multiple="multiple" name="classeCode[]">
                                <option value="">Sélectionnez une classe</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-8 mt-4">
                            <button class="btn-sm btn-primary" id="submitBtnSFE">Appliquer la sélection</button>
                            <button type="button" class="btn-sm btn-success ml-3" onclick="imprimerPage()">Imprimer</button>
                            <button type="button" class="btn-sm btn-secondary ml-3" onclick="imprimerPageRelance()">Relance</button>
                            <button type="button" class="btn-sm btn-primary ml-3" onclick="exportToExcel()">Exporter vers Excel</button>
                        </div>
                       
                    </div>

                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f8f9fa;
                        }

                        .table-container {
                            width: 100%;
                            overflow-x: auto;
                            margin: 20px auto;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            min-width: 800px;
                            background-color: #fff;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }

                        th,
                        td {
                            padding: 10px 15px;
                            border: 1px solid #ddd;
                            text-align: center;
                        }

                        th {
                            background-color: #f4f4f4;
                            font-weight: bold;
                        }

                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }

                        @media (max-width: 768px) {
                            th,
                            td {
                                padding: 8px 10px;
                            }
                        }

                        @media (max-width: 480px) {
                            table {
                                display: block;
                                overflow-x: auto;
                            }

                            th,
                            td {
                                font-size: 12px;
                                padding: 5px;
                            }
                        }
                        @media print {
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 13px;
                        }

                        .lettres-container {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: space-between;
                            gap: 10px;
                        }

                        .lettre-relance {
                            width: 48%;
                            border: 0px solid #000;
                            padding: 8px;
                            box-sizing: border-box;
                            margin-bottom: 10px;
                            page-break-inside: avoid;
                        }

                        .lettre-relance p {
                            font-size: 13px;
                            margin: 3px 0;
                        }

                        .lettre-relance table {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 11px;
                            margin-top: 5px;
                        }

                        .lettre-relance th,
                        .lettre-relance td {
                            border: 1px solid #000;
                            padding: 3px;
                            text-align: center;
                        }

                        h1 {
                            font-size: 16px;
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        @page {
                            size: A4 landscape;
                            margin: 10mm;
                        }

                        .page-break {
                            page-break-after: always;
                        }
                        }



                    </style>

                    <div id="contenu">  
                        @if ($donneSituationFinanciereGroupe && $donneSituationFinanciereGroupe->isNotEmpty())
                            {{-- Boucle principale pour afficher les données par classe --}}
                            @foreach ($donneSituationFinanciereGroupe as $classeCode => $eleves)
                                <h4 class="mt-5 mb-4" style="text-align: center"><strong>Classe : {{ $classeCode }}</strong></h4>

                                {{-- Effectif et somme du total hors échéancier --}}
                                <div class="col1 mb-4">
                                    @php
                                        $effectifClasse = $resultatParClasse[$classeCode]['effectif'] ?? 0;
                                        $sommeTotalHorsEcheancier = $resultatParClasse[$classeCode]['total_du_hors_echeancier'] ?? 0;
                                        $reste = $resultatParClasse[$classeCode]['reste'] ?? 0;
                                        $pourcentage_recouvrement = $resultatParClasse[$classeCode]['pourcentage_recouvrement'] ?? 0;
                                    @endphp
                                    <p class="sectionEffectifetAutre"><strong>[ Effectif : {{ $effectifClasse }} ] [ Total du : {{ number_format($sommeTotalHorsEcheancier, 2,',', '.') }} ] [ Reste : {{ number_format($reste, 2, ',', '.') }}  ]  [ Recouvrement : {{ $pourcentage_recouvrement }} % ]</strong></p>
                                    {{-- <p><strong>Somme totale hors échéancier : {{ number_format($sommeTotalHorsEcheancier, 2) }} FCFA</strong></p>
                                    <p><strong>Reste : {{ number_format($reste, 2) }} FCFA</strong></p>
                                    <p><strong>Pourcentage Recouvrement : {{ $pourcentage_recouvrement }} %</strong></p> --}}
                                </div>

                                {{-- Tableau des élèves --}}
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nom et Prénom</th>
                                                {{-- <th>Classe</th> --}}
                                                <th>Reste Échéancier</th>
                                                <th>Reste Arriéré</th>
                                                <th>Reste Autres Frais</th>
                                                {{-- <th>Total restant du hors echeancier</th> --}}
                                                {{-- <th>Date dern paiement</th> --}}
                                                <th>Décision</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleves as $index => $eleve)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td style="text-align: justify">{{ $eleve['NOM'] }} {{ $eleve['PRENOM'] }}</td>
                                                    {{-- <td>{{ $eleve['CODECLAS'] }}</td> --}}
                                                    <td>{{ number_format($eleve['reste_echeance'], 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($eleve['reste_arriere'], 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($eleve['reste_autre_frais'], 0, ',', ' ') }}</td>
                                                    {{-- <td>{{ number_format($eleve['total_du_hors_echeancier'], 0, ',', ' ') }}</td> --}}
                                                    {{-- <td>
                                                        {{ $eleve['derniere_date_scolarite'] 
                                                            ? \Carbon\Carbon::parse($eleve['derniere_date_scolarite'])->format('d/m/Y') 
                                                            : '' }}
                                                    </td>                                             --}}
                                                    <td>
                                                        @if ($eleve['reste_echeance'] == 0 && $eleve['reste_arriere'] == 0 && $eleve['reste_autre_frais'] == 0)
                                                            À jour
                                                        @elseif($eleve['reste_echeance'] < 0 || $eleve['reste_arriere'] < 0 || $eleve['reste_autre_frais'] < 0)

                                                        @else
                                                            Non à jour
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="page-break"></div>

                            @endforeach

                        @else
                            <p  style="text-align: center; font-size: 19px; margin-top:5rem;"><strong>Aucune situation financiere pour cette classe</strong></p>
                        @endif
                    </div>
                
                    <div id="contenuRelance" style="display:none;">
                        <div class="container">
                            <div class="lettres-container">
                                @foreach ($donneRelance as $classe => $elevesRelance)
                                    <!-- <h4 style="width:100%; text-align:center;">Classe : {{ $classe }}</h4> -->
                                    @foreach ($elevesRelance as $donne)
                                        @php $meta = $donne['meta'] ?? []; $echeances = $donne['echeances'] ?? []; $total_reste = $donne['total_reste'] ?? 0; @endphp

                                        <div class="lettre-relance">
                                            <div class="header-flex" style="display:flex; align-items:center; gap:15px;">
                                                @if($logo)
                                                    <img src="data:image/jpeg;base64,{{ base64_encode($logo) }}" alt="Logo" style="width:25%; height: 75%; margin-top: -5rem;">
                                                @endif

                                                <div>
                                                    {!! $entete !!}
                                                </div>
                                            </div>

                                            <div style="text-align: right; margin-top: -2.5rem;">
                                                <p>{{$ville->VILLE}}, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                                <p>Aux parents de</p>
                                                <p><strong>{{ $meta['NOM'] ?? '' }} {{ $meta['PRENOM'] ?? '' }}</strong></p>
                                                <p>{{ $meta['CODECLAS'] ?? '' }}</p>
                                            </div>

                                            <p>Chers parents,</p>
                                            <p class="texte">
                                                Sauf erreur ou omission, vous restez nous devoir
                                                <strong>{{ number_format($total_reste, 0, ',', ' ') }} FCFA</strong>
                                                au titre des échéances échues de l’année scolaire
                                                <strong>{{ $meta['annescolaire'] ?? '' }}</strong>. <br>
                                                    Voici dans le tableau suivant, le détail des échéances correspondantes :
                                            </p>

                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Date échéance</th>
                                                        <th>Montant à payer</th>
                                                        <th>Montant payé</th>
                                                        <th>Reste</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($echeances as $i => $ech)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>{{ $ech['date'] }}</td>
                                                            <td>{{ number_format($ech['montant_apayer'], 0, ',', ' ') }}</td>
                                                            <td>{{ number_format($ech['montant_paye'], 0, ',', ' ') }}</td>
                                                            <td>{{ number_format($ech['reste'], 0, ',', ' ') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" style="text-align:center;">Aucune échéance détaillée disponible</td>
                                                        </tr>
                                                    @endforelse

                                                    <tr style="font-weight: bold; background-color: #f2f2f2;">
                                                        <td colspan="3" style="text-align: right;">Total Général Restant à Payer:</td>
                                                        <td colspan="2">{{ number_format($meta['total_general_restant'] ?? 0, 0, ',', ' ') }} FCFA</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <br>

                                            <p>Veuillez régler ce solde au plus tard dans les meilleurs délais.<br>
                                            Veuillez agréer, chers parents, l'expression de nos sentiments distingués. <br>                                        
                                            </p>
                                            <p style="text-align:right; margin-right: 3rem;">  <strong>La direction</strong></p> <br><br><br><br>
                                        </div>
                                    @endforeach
                                    <div class="page-break"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function imprimerPage() {

            const titre = document.getElementById('title').textContent;

            
            // Utiliser un titre par défaut si le champ est vide
        
            // Récupérer le contenu à imprimer (le tableau dans la div avec id "contenu")
            let contenuImpression = document.getElementById('contenu').innerHTML;
        
            // Créer une nouvelle fenêtre pour l'impression
            let printWindow = window.open('', '', 'height=700,width=1600');
        
            printWindow.document.write(`
                <html>
                <head>
                    <title>${titre}</title>
                    <style>
                        @page { size: landscape; }
                        @media print {
                            /* Style général pour l'impression */
                            body {
                                font-family: Arial, sans-serif;
                                font-size: 15px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                page-break-inside: auto;
                                border: 1px solid #000;
                            }
                            table th, table td {
                                font-size: 13px;
                                padding: 5px;
                                border: 1px solid #000;
                            }
                            tr {
                                page-break-inside: avoid;
                                page-break-after: auto;
                            }
                            tbody tr:nth-child(even) {
                                background-color: #f1f3f5;
                            }
                            tbody tr:nth-child(odd) {
                                background-color: #fff;
                            }
                            .effred{
                                display: flex;
                                margin-bottom: 2rem;
                            }
                            .col1{
                                width: 38%;
                                margin-left: 2rem;
                            }
                            .col2{
                                width: 38%;
                                margin-left: 12rem;
                            }

                            .sectionEffectifetAutre{
                                width: 100%;
                            }
        
                            .page-break {
                                page-break-before: always;
                            }
                        }
                    </style>
                </head>
                <body>
                    <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">${titre}</h1>
                    ${contenuImpression}
                </body>
                </html>
            `);
        
            // Attendre que le contenu soit chargé, puis imprimer
            printWindow.document.close();
            printWindow.focus();
        
            printWindow.onload = function () {
                printWindow.print();
            };
        
            // Fermer la fenêtre après l'impression
            printWindow.addEventListener('afterprint', function () {
                printWindow.close();
            });
        
            // Restaurer le titre de la page après impression
            document.title = titre;
        }

        // relance
        function imprimerPageRelance() {
            const titre = "";
            let contenuImpressions = document.getElementById('contenuRelance').innerHTML;

            let printWindow = window.open('', '', 'height=700,width=1600');

            printWindow.document.write(`
                <html>
                <head>
                    <title>${titre}</title>
                    <style>
                        @page { size: A4 landscape; margin: 10mm; }
                        @media print {
                            body {
                                font-family: Arial, sans-serif;
                                font-size: 16px;
                            }

                            .lettres-container {
                                display: flex;
                                flex-wrap: wrap;
                                justify-content: space-between;
                                align-items: stretch;
                            }

                            .lettre-relance {
                                width: 48%;
                                // border: 1px solid #000;
                                padding: 18px;
                                box-sizing: border-box;
                                margin-bottom: 0;
                                page-break-inside: avoid;
                                min-height: 60vh;
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                            }

                            /* Chaque paire = une page */
                            .lettre-relance:nth-child(2n) {
                                page-break-after: always;
                            }

                            .lettre-relance p {
                                font-size: 14px;
                                margin: 6px 0;
                            }

                            .lettre-relance table {
                                width: 100%;
                                border-collapse: collapse;
                                font-size: 12px;
                                margin-top: 10px;
                            }

                            .lettre-relance th,
                            .lettre-relance td {
                                border: 1px solid #000;
                                padding: 4px;
                                text-align: center;
                            }

                            h1 {
                                font-size: 18px;
                                text-align: center;
                                margin-bottom: 15px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <h1 style="text-transform: uppercase;">${titre}</h1>
                    ${contenuImpressions}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            printWindow.onload = function () {
                printWindow.print();
            };

            printWindow.addEventListener('afterprint', function () {
                printWindow.close();
            });

            document.title = titre;
        }   

        function exportToExcel() {
            const contentElement = document.getElementById('contenu');
            
            if (!contentElement) {
                alert('Aucune liste à exporter. Veuillez d\'abord créer la liste.');
                return;
            }

            
            // Cloner le contenu pour ne pas modifier l'original
            const clone = contentElement.cloneNode(true);


            // style Excel plus propre
            const style = `
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 5px;
                        text-align: center;
                        font-size: 20px;
                        line-height: 1.5rem;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                    td.mat {
                        mso-number-format:"0";
                    }
                </style>
            `;

            // Construire le HTML complet pour Excel
            const html = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:x="urn:schemas-microsoft-com:office:excel"
                    xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta charset="UTF-8">
                    ${style}
                </head>
                <body>
                    
                    ${clone.innerHTML}
                </body>
                </html>
            `;

            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = situation_financiere.xls;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

    </script>
    
    <!-- <script>
            $(window).on('load', function() {
            $('.js-example-basic-multiple').select2({
                width: 'resolve' // ou '100%'
            });
        });
    </script> -->
    

@endsection