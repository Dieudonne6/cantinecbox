@extends('layouts.master')
@section('content')

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
        /* text-align: left; */
        text-align: center;
    }

    th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .page-break {
        page-break-before: always;
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
    <div class="main-panel-10 pb-5">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" id="title"><strong>Liste des retards de paiement a la date du {{ \Carbon\Carbon::now()->format('d/m/Y') }} </strong></h4>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="" style="margin-top: 2px">Classe</label>
                            <select class="js-example-basic-multiple w-100" multiple="multiple" name="classeCode[]">
                                <option value="">Sélectionnez une classe</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mt-4">
                            <button class="btn-sm btn-primary" id="submitBtnRP">Appliquer la sélection</button>
                        </div>

                        @if ($listeGrouper && $listeGrouper->isNotEmpty())
                        <div class="col-3 mt-4 ">
                            {{-- <button class="btn-sm btn-primary" id="printBtn">Imprimer</button> --}}
                            <button type="button" class="btn-sm btn-primary" onclick="imprimerPage()">Imprimer</button>
                        </div>
                        @else
                        @endif

                    </div>

                </div>




                <div id="contenu">
                <div class="table-container">
                    @if ($listeGrouper && $listeGrouper->isNotEmpty())
                        @foreach ($listeGrouper as $classe => $eleves)  <!-- Première boucle pour chaque classe -->
                        <h4 class="mt-4 mb-4" style="text-align: center"><strong>Classe : {{ $classe }}</strong></h4>
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        
                                        <th colspan="3">Donne sur l'échéancier</th>
                                        <th colspan="2">Donne sur le paiement</th>
                                
                                        <th>Difference</th>

                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>nbEche</th>
                                        <th>Montant du</th>
                                        <th>der. date</th>
                                        <th>Montant paye</th>
                                        <th>der. date</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp



                                    @foreach ($eleves as $combinedDatasingle) <!-- Deuxième boucle pour chaque élève dans la classe -->
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $combinedDatasingle['NOM'] }}</td>
                                        <td>{{ $combinedDatasingle['PRENOM'] }}</td>
                                        <td>{{ $combinedDatasingle['nbecheance'] }}</td>
                                        <td>{{ $combinedDatasingle['montant_du'] }}</td>
                                        @if ($combinedDatasingle['derniere_date_echeance'] === null)
                                            <td>{{ $combinedDatasingle['derniere_date_echeance'] }}</td>
                                        @else
                                            <td>{{ \Carbon\Carbon::parse($combinedDatasingle['derniere_date_echeance'])->format('d/m/Y') }}</td>
                                        @endif
                                        <td>{{ $combinedDatasingle['total_montant'] }}</td>
                                        @if ($combinedDatasingle['derniere_date_scolarite'] === null)
                                            <td>{{ $combinedDatasingle['derniere_date_scolarite'] }}</td>
                                        @else
                                            <td>{{ \Carbon\Carbon::parse($combinedDatasingle['derniere_date_scolarite'])->format('d/m/Y') }}</td>
                                        @endif
                                        <td>{{ $combinedDatasingle['difference'] }}</td>
                                    </tr>
                                    @php
                                        $index++
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="page-break"></div>

                        @endforeach

                    @else
                        <p  style="text-align: center; font-size: 19px;"><strong>Aucun retard de paiement pour cette classe</strong></p>
                    @endif

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
    </script>
@endsection
