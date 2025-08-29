@extends('layouts.master')
@section('content')

    @php
        use Carbon\Carbon;

        $dateDebutString = $dateDebut;
        $dateFinString = $dateFin;

        // Convertir les chaînes en objets Carbon
        $dateDebutFormat = Carbon::parse($dateDebutString);
        $dateFinFormat = Carbon::parse($dateFinString);
    @endphp

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
                    </style>
                    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <br>
                    <br>                                     
                </div>
                <div class="card-body">
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <button type="button" class="btn-sm btn-primary" onclick="impimerRecouvrement()">Imprimer</button>
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


                </style>

                <div id="contenu">
                    <h3 class="title" style="text-align: center;">Situation des recouvrements</h3>
                    <p style="text-align: center">Période du <strong>{{ $dateDebutFormat->format('d/m/Y') }}</strong> au <strong>{{ $dateFinFormat->format('d/m/Y') }}</strong></p>
                    <div class="table-container">
                        <table>
                            <thead>
                                <!-- Première ligne de l'en-tête -->
                                <tr>
                                    <th rowspan="2">Classe</th>
                                    <th colspan="2">Scolarité</th>
                                    <th colspan="2">Arriéré</th>
                                    <th colspan="2">Frais1</th>
                                    <th colspan="2">Frais2</th>
                                    <th colspan="2">Frais3</th>
                                    <th colspan="2">Frais4</th>
                                    <th colspan="2">Total</th>
                                    <th colspan="1">Reste</th>
                                </tr>
                                
                                <!-- Deuxième ligne de l'en-tête (sous-colonnes) -->
                                <tr>
                                    {{-- <th>classe</th> --}}
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                    <th>Perçu</th>
                                    <th>À percevoir</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    // Initialisation des variables pour les totaux
                                    // Scolarite
                                    $totalAPercevoirScolarite = 0;
                                    $totalPercuScolarite = 0;
                                    // Arriere
                                    $totalAPercevoirArriere = 0;
                                    $totalPercuArriere = 0;
                                    // Frais1
                                    $totalAPercevoirFrais1 = 0;
                                    $totalPercuFrais1 = 0;
                                    // Frais2
                                    $totalAPercevoirFrais2 = 0;
                                    $totalPercuFrais2 = 0;
                                    // Frais3
                                    $totalAPercevoirFrais3 = 0;
                                    $totalPercuFrais3 = 0;
                                    // Frais4
                                    $totalAPercevoirFrais4 = 0;
                                    $totalPercuFrais4 = 0;
                                    // Total
                                    $totalAPercevoirTotal = 0;
                                    $totalPercuTotal = 0;
                                    // Reste
                                    $totalAPercevoirReste = 0;
                                @endphp
                                @foreach ($sommeParClasse as $classe => $donneclasse)
                                    
                                    <tr>
                                        <td><strong>{{ $classe }}</strong></td>    
                                        {{-- donne scolarite --}}
                                        {{-- <td>{{ isset($donneclasse['Scolarite']) ? number_format($donneclasse['Scolarite'], 0, ',', '.') : 0 }}</td> --}}
                                        <td>{{ isset($donneclasse['total_a_payer'])  ? number_format($donneclasse['total_a_payer'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['scolaritePercu'])  ? number_format($donneclasse['scolaritePercu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne arriere --}}
                                        <td>{{ isset($donneclasse['total_arriere'])  ? number_format($donneclasse['total_arriere'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['arrierePercu'])  ? number_format($donneclasse['arrierePercu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne frais1 --}}
                                        <td>{{ isset($donneclasse['total_frais1'])  ? number_format($donneclasse['total_frais1'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['frais1Percu'])  ? number_format($donneclasse['frais1Percu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne frais2 --}}
                                        <td>{{ isset($donneclasse['total_frais2'])  ? number_format($donneclasse['total_frais2'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['frais2Percu'])  ? number_format($donneclasse['frais2Percu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne frais3 --}}
                                        <td>{{ isset($donneclasse['total_frais3'])  ? number_format($donneclasse['total_frais3'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['frais3Percu'])  ? number_format($donneclasse['frais3Percu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne frais4 --}}
                                        <td>{{ isset($donneclasse['total_frais4'])  ? number_format($donneclasse['total_frais4'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['frais4Percu'])  ? number_format($donneclasse['frais4Percu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne total --}}
                                        <td>{{ isset($donneclasse['totalAPercevoir'])  ? number_format($donneclasse['totalAPercevoir'], 0, ',', '.') : 0 }}</td>                
                                        <td>{{ isset($donneclasse['totalPercu'])  ? number_format($donneclasse['totalPercu'], 0, ',', '.') : 0 }}</td>  
                                        {{-- donne reste --}}
                                        <td>{{ isset($donneclasse['Reste'])  ? number_format($donneclasse['Reste'], 0, ',', '.') : 0 }}</td>                

                                    </tr>

                                    @php
                                        // Ajouter les montants de chaque classe aux totaux
                                        // Scolarite
                                        $totalAPercevoirScolarite += $donneclasse['total_a_payer'] ?? 0;
                                        $totalPercuScolarite += $donneclasse['scolaritePercu'] ?? 0;
                                        // Arriere
                                        $totalAPercevoirArriere += $donneclasse['total_arriere'] ?? 0;
                                        $totalPercuArriere += $donneclasse['arrierePercu'] ?? 0;
                                        // Frais1
                                        $totalAPercevoirFrais1 += $donneclasse['total_frais1'] ?? 0;
                                        $totalPercuFrais1 += $donneclasse['frais1Percu'] ?? 0;
                                        // Frais2
                                        $totalAPercevoirFrais2 += $donneclasse['total_frais2'] ?? 0;
                                        $totalPercuFrais2 += $donneclasse['frais2Percu'] ?? 0;
                                        // Frais3
                                        $totalAPercevoirFrais3 += $donneclasse['total_frais3'] ?? 0;
                                        $totalPercuFrais3 += $donneclasse['frais3Percu'] ?? 0;
                                        // Frais4
                                        $totalAPercevoirFrais4 += $donneclasse['total_frais4'] ?? 0;
                                        $totalPercuFrais4 += $donneclasse['frais4Percu'] ?? 0;
                                        // Total
                                        $totalAPercevoirTotal += $donneclasse['totalAPercevoir'] ?? 0;
                                        $totalPercuTotal += $donneclasse['totalPercu'] ?? 0;
                                        // Reste
                                        $totalAPercevoirReste += $donneclasse['Reste'] ?? 0;
                                    @endphp

                                @endforeach
                                    <!-- Affichage des totaux après la boucle -->
                                    
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        {{-- Scolarite --}}
                                        {{-- number_format($sommeTotalHorsEcheancier, 0, ',', '.'); --}}
                                        <td><strong>{{ number_format($totalAPercevoirScolarite, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuScolarite, 0, ',', '.') }}</strong></td>
                                        {{-- Arriere --}}
                                        <td><strong>{{ number_format($totalAPercevoirArriere, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuArriere, 0, ',', '.') }}</strong></td>
                                        {{-- Frais1 --}}
                                        <td><strong>{{ number_format($totalAPercevoirFrais1, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuFrais1, 0, ',', '.') }}</strong></td>
                                        {{-- Frais2 --}}
                                        <td><strong>{{ number_format($totalAPercevoirFrais2, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuFrais2, 0, ',', '.') }}</strong></td>
                                        {{-- Frais3 --}}
                                        <td><strong>{{ number_format($totalAPercevoirFrais3, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuFrais3, 0, ',', '.') }}</strong></td>
                                        {{-- Frais4 --}}
                                        <td><strong>{{ number_format($totalAPercevoirFrais4, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuFrais4, 0, ',', '.') }}</strong></td>
                                        {{-- Total --}}
                                        <td><strong>{{ number_format($totalAPercevoirTotal, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ number_format($totalPercuTotal, 0, ',', '.') }}</strong></td>
                                        {{-- Reste --}}
                                        <td><strong>{{ number_format($totalAPercevoirReste, 0, ',', '.') }}</strong></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="page-break"></div> --}}
                </div>
        </div>
    </div>

    <script>
        function impimerRecouvrement() {

            const titre = "Situation des recouvrements";

            
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
                                
                            .page-break {
                                page-break-before: always;
                            }

                        }
                    </style>
                </head>
                <body>
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
    </script>


@endsection