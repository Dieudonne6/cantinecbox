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
                            <label for="" style="margin-top: 2px">Classe</label>
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
                      
                        {{-- <div class="col-2 mt-4">
                            <button type="button" class="btn-sm btn-primary" onclick="imprimerPage()">Relance</button>
                        </div> --}}
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

                <div id="contenuRelance" class="d-none">
                    <div class="container">
                        @foreach ($donneRelance as $matricule => $donne)
                        @php
                            $sommeReste = 0; // Initialise la somme du reste à payer pour chaque élève
                        @endphp
                        <div class="row">
                            <div class="col-10">
                                GGG
                            </div>
                            <div class="col-2">
                                <p>ccc , le {{$dateDuJour}}</p>
                                <p>Aux parents de </p>
                                <p> <strong>{{$donne[0]['NOM']}} {{$donne[0]['PRENOM']}} </strong></p>
                                <p>{{$donne[0]['CODECLAS']}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <p>Chers parents,</p>
                                {{-- Calcul de la somme des restes à payer --}}
                                @foreach ($donne as $donneecheance)
                                    @php
                                        $sommeReste += $donneecheance['reste_a_payer']; // Accumule le reste à payer
                                    @endphp
                                @endforeach
                            <p class="texte"> Sauf erreur ou omission de notre part, nous vous rappelons qu'au titre des frais
                                de scolarité <strong>{{$donne[0]['annescolaire']}}</strong> de votre enfant, ci-dessus mentionné, vous restez devoir dans nos livres,
                                  par rapport aux échéances de paiement de paiement, la somme de <strong>{{ $sommeReste }}</strong>. Conformement aux  recapitulatif de paiement 
                                  ci-dessous.</p><br><br>
                            {{-- Tableau des élèves --}}
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date échéance</th>
                                        <th>Montant à payer</th>
                                        <th>Montant payé</th>
                                        <th>Reste à payer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($donne as $index => $donneecheance)
                                            
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{$donneecheance['date_echeance']}}</td>
                                                <td>{{$donneecheance['montant_a_payer']}}</td>
                                                <td>{{$donneecheance['montant_payer']}}</td>
                                                <td>{{$donneecheance['reste_a_payer']}}</td>
                                            </tr>
                                            @php
                                                $sommeReste += $donneecheance['reste_a_payer']; // Accumule le reste à payer
                                            @endphp
        

                                        @endforeach

                                </tbody>
                            </table>
                        </div><br><br>
                        <p>Vous êtes invité à régler ce solde au plus tard le <strong>{{$dateDuJour}}</strong></p>
                        <p>Veuillez agréer, chers parents, l'expression de nos sentiments distingués.</p>
                        <p>Nous vous remercions par avance pour votre compréhension.</p>
                        <div style="align-items: flex-end">

                            <p class="direction">La direction</p>
                        </div>

                        {{-- <div class="page-break"></div> --}}
                        </div>
                        <div class="page-break"></div>
                    @endforeach

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

            const titre = "liste de relance";

            // Récupérer le contenu à imprimer (le tableau dans la div avec id "contenuRelance")
            let contenuImpressions = document.getElementById('contenuRelance').innerHTML;

            console.log(contenuImpressions);

            // Créer une nouvelle fenêtre pour l'impression
            let printWindow = window.open('', '', 'height=700,width=1600');

            printWindow.document.write(`
                <html>
                <head>
                    <title>liste de relance</title>
                    <style>
                        @page { size: landscape; }
                        @media print {
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

                            .texte{
                                font-size: 20px !important ;
                                text-align: justify;
                            }

                            .col-2 {
                                display: inline-block;
                                vertical-align: top;
                                margin-left: 40rem;
                            }
                            .direction {
                                margin-left: 50rem;
                                font-weight: bold;
                            }

                            .page-break {
                                page-break-before: always;
                            }


                        }
                    </style>
                </head>
                <body>
                    <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">${titre}</h1>
                    ${contenuImpressions}
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
            a.download = `situation_financiere.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }


    </script>





@endsection
