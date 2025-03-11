@extends('layouts.master')
@section('content')

<div class="container">

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

                            <h4 class="card-title">Etat des arrièrés (Elèves inscrits)</h4>

                            <button type="button" class="btn-sm btn-primary" onclick="imprimerPage()">Imprimer</button>
                    </div>
                    <div id="contenu">
                    <div class="table table-bordered print-table">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Num</th>
                                    <th>Nom </th>
                                    <th>Prénom</th>
                                    <th>Classe</th>
                                    <th>MONTANT DU</th>
                                    <th>PAYE</th>
                                    <th>RESTE</th>
                                </tr>
                            </thead>
                            @php
                                $index = 0;
                            @endphp
                            <tbody>
                                @foreach ($resultats as $resultat)
                                    <tr class="eleve">
                                        <td>
                                            {{ $index + 1 }}                                        </td>
                                        <td>
                                            {{ $resultat['NOM'] }} 
                                        </td>
                                        <td>
                                            {{ $resultat['PRENOM'] }}                                        </td>
                                        <td>
                                            {{ $resultat['CLASSE'] }}
                                        </td>
                                        <td>
                                            {{ $resultat['ARRIERE'] }}
                                        </td>
                                        <td>
                                            {{ $resultat['PAYE'] }}
                                        </td>
                                        <td>
                                            {{ $resultat['RESTE'] }}
                                        </td>

                                    </tr>
                                    @php
                                        $index++;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="4" style="text-align: right;"><strong>Total :</strong></td>
                                    <td><strong>{{ $totalDues }}</strong></td>
                                    <td><strong>{{ $totalPayes }}</strong></td>
                                    <td><strong>{{ $totalRestes }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Tableau imprimé uniquement -->


                </div>
            </div>
    </div>    
</div>



<script>
    function imprimerPage() {
        let originalTitle = document.title;
        document.title = 'Etat des arrièrés (Elèves inscrits)';
        
    
        // Récupérer le contenu à imprimer (le tableau dans la div avec id "contenu")
        let contenuImpression = document.getElementById('contenu').innerHTML;
    
        // Créer une nouvelle fenêtre pour l'impression
        let printWindow = window.open('', '', 'height=700,width=1600');
    
        printWindow.document.write(`
            <html>
            <head>
                <title>Etat des arrièré (Elèves inscrits)</title>
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


                    }
                </style>
            </head>
            <body>
                <h1 style="font-size: 20px; text-align: center;">Etat des arrièré (Elèves inscrits)</h1>
                <h3 style="font-size: 20px; text-align: center;">Etat des arrièré (Elèves inscrits)</h3>
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
        document.title = originalTitle;
    }
</script>
@endsection
