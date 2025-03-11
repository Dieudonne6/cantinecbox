@extends('layouts.master')

@section('content')
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
    <div class="col" style="display: none;">
        <h2>{{ $type }}</h2>
        {{-- @if ($month)
            <p>Mois: {{ date('F Y', strtotime($month)) }}</p>
        @endif
        <p>Date d'arrêt: {{ date('d/m/Y', strtotime($dateArret)) }}</p>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° de l'Ordre</th>
                    <th>Chapitre</th>
                    <th>Compte</th>
                    <th>Objet de Recette</th>
                    <th>Montant</th>
                    <th>Observation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($journals as $journal)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($journal->DATEOP)) }}</td> <!-- Format date -->
                        <td>{{ $journal->NUMJ }}</td>
                        <td>{{ $journal->CHAPITRE }}</td>
                        <td>{{ $journal->NCOMPTE }}</td>
                        <td>{{ $journal->LIBELOP }}</td>
                        <td>{{ number_format($journal->DEBIT, 2, ',', ' ') }}</td> <!-- Format montant -->
                        <td>{{ $journal->OBSER }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Total des recettes: {{ number_format($journals->sum('DEBIT'), 2, ',', ' ') }} €</p> <!-- Format total --> --}}

        <script>
            // Fonction pour imprimer le journal automatiquement et fermer la fenêtre
            function imprimerPageAutomatiquement() {
                const releveMensuelRadio = document.getElementById('releveMensuel');
                const dateArret = document.getElementById('date')?.value;
                const moisSelectionne = document.getElementById('month')?.value;

                const [year, month] = moisSelectionne ? moisSelectionne.split('-') : ['', '']; // Récupérer l'année et le mois
                const [yearArret, monthArret, dayArret] = dateArret ? dateArret.split('-') : ['', '', ''];
                const formattedDateArret = dateArret ? `${dayArret}/${monthArret}/${yearArret}` : '';

                const monthName = getMonthName(parseInt(month)); // Convertir le mois en entier

                // Créer une fenêtre pour l'impression
                const printWindow = window.open('', '', 'height=600,width=800');

                let content = '';

                @foreach ($journals as $journal)
                    content += `<tr>
                            <td>{{ date('d/m/Y', strtotime($journal->DATEOP)) }}</td>
                            <td>{{ $journal->NUMJ }}</td>
                            <td>{{ $journal->CHAPITRE }}</td>
                            <td>{{ $journal->NCOMPTE }}</td>
                            <td>{{ $journal->LIBELOP }}</td>
                            <td>{{ number_format($journal->DEBIT, 2, ',', ' ') }}</td>
                            <td>{{ $journal->OBSER }}</td>
                        </tr>`;
                @endforeach

                // Construire le HTML à imprimer
                printWindow.document.write(`<html>
    <head>
        <title>Impression de Relevé mensuel des ordres de recettes</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                line-height: 1.6;
            }
            h2 {
                text-align: center;
                font-size: 20px;
                margin-bottom: 20px;
            }
            p {
                margin: 10px 0;
                font-size: 14px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid black;
                padding: 10px;
                text-align: left;
                font-size: 13px;
            }
            th {
                background-color: #D3D3D3; /* Entête gris */
            }
            .summary {
                margin-top: 20px;
                font-weight: bold;
                font-size: 14px;
                text-align: center; /* Centrer le texte */
            }
            .encadre {
                display: inline-block;
                padding: 5px 10px;
                border: 2px solid black; /* Encadrer les montants */
                margin-top: 10px;
                font-size: 16px; /* Taille du texte des montants */
            }
            .signature {
                margin-top: 40px;
                display: flex;
                justify-content: space-between;
            }
            .signature p {
                width: 45%;
                text-align: center;
            }
            .flex-space-between {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                }
                table {
                    margin-top: 20px;
                }
                .signature {
                    page-break-inside: avoid;
                }
            }
        </style>
    </head>
    <body>
        <h2>Relevé mensuel des ordres de recettes</h2>
        
        <div class="flex-space-between">
            @if ($month)
                <p>Mois de {{ date('F Y', strtotime($month)) }}</p>
            @endif
            <p>{{ date('Y') }}/{{ date('Y', strtotime('+1 year')) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° de l'Ordre</th>
                    <th>Chap.</th>
                    <th>Compte</th>
                    <th>Objet de Recette</th>
                    <th>Montant</th>
                    <th>Observation</th>
                </tr>
            </thead>
            <tbody>
                ${content}
            </tbody>
        </table>

        <p class="summary">Total des recettes du mois: <span class="encadre">{{ number_format($journals->sum('DEBIT'), 2, ',', ' ') }} Fcfa</span></p>
        <p class="summary">Total des recettes au {{ date('d/m/Y', strtotime($dateArret)) }} : <span class="encadre">{{ number_format($totalRecettes, 2, ',', ' ') }} Fcfa</span></p>
        <p class="summary">Arrêté le présent relevé des recettes à la somme de <span class="encadre">{{ number_format($journals->sum('DEBIT'), 2, ',', ' ') }} Fcfa</span></p>
        
        <div class="signature">
            <p>Vue et approuvé <br> Le chef d'établissement</p>
            <p>À CCC, le <br> Le gestionnaire comptable</p>
        </div>
    </body>
</html>
                `);

                // Lancer l'impression
                printWindow.print(); // Ouvrir la boîte de dialogue d'impression

                printWindow.document.close(); // Fermer le document

                // Fermer la fenêtre après l'impression et revenir à la page "etatdelacaisse"
                printWindow.onafterprint = function() {
                    window.history.back(); // Revenir à la page précédente si la fenêtre d'impression se ferme
                };

                // Fermer la fenêtre immédiatement après son ouverture
                printWindow.onbeforeunload = function() {
                    window.history.back(); // Revenir à la page précédente si la fenêtre d'impression se ferme
                };

            }

            // Lancer l'impression dès le chargement de la page
            window.onload = imprimerPageAutomatiquement;

            // Fonction pour obtenir le nom du mois
            function getMonthName(month) {
                const months = [
                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                    'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];
                return months[month - 1] || '';
            }
        </script>
    </div>
@endsection
