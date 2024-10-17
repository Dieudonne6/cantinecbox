@extends('layouts.master')

@section('content')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Bordereau des paiements/Recettes</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <div class="col">
                                <label>Type</label>
                                <div class="form-group border p-3 rounded" style="border-color: #ccc;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="optionsPaiement"
                                            id="bordereauPaiements" value="Bordereaux des paiements"
                                            onclick="handleRadioClick(this)" checked>
                                        <label class="form-check-label" for="bordereauPaiements">Bordereaux des
                                            paiements</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="optionsPaiement"
                                            id="releveMensuel" value="Relevé mensuel des ordres de recettes"
                                            onclick="handleRadioClick(this)">
                                        <label class="form-check-label" for="releveMensuel">Relevé mensuel des ordres de
                                            recettes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="optionsPaiement"
                                            id="bordereauCaisse" value="Bordereau de caisse"
                                            onclick="handleRadioClick(this)">
                                        <label class="form-check-label" for="bordereauCaisse">Bordereau de caisse</label>
                                    </div>
                                </div>
                                <label>Date d'arrêt</label>
                                <div id="the-basics">
                                    <input class="form-control" type="date" id="date" name="date" value="">
                                </div>
                            </div>
                            
                            <div class="col">
                                <label for="chapitre-select">Chapitre</label>
                                <select id="chapitre-select" class="js-example-basic-multiple w-100">
                                    <option value="">TOUTES LES CHAPITRES</option>
                                    @foreach ($chapitres as $chapitre)
                                        <option value="{{ url('/etatdelacaisse/' . $chapitre->CHAPITRE) }}">
                                            {{ $chapitre->LIBELLECHA }}</option>
                                    @endforeach
                                </select>
                                <div class="col border p-4 rounded mt-3" style="border-color: #ccc;">
                                    <label>Périodicité</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-5 ms-2">
                                            <input class="form-check-input" type="radio" name="periodicite" id="mensuel"
                                                value="Mensuel" onclick="handlePeriodiciteClick(this)" disabled>
                                            <label class="form-check-label" for="mensuel">Mensuel</label>
                                        </div>
                                        <div class="form-check ms-5">
                                            <input class="form-check-input" type="radio" name="periodicite" id="annuel"
                                                value="Annuel" onclick="handlePeriodiciteClick(this)" disabled>
                                            <label class="form-check-label" for="annuel">Périodique</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="additionalField" style="display: block;">
                                    <div class="form-group row">
                                        <div class="col" id="monthField" style="display: block;">
                                            <label id="additionalLabel">Sélectionnez le mois :</label>
                                            <input class="form-control" type="month" id="month" name="month"
                                                style="display: block;">
                                        </div>
                                        <div class="col" id="periodField" style="display: none;">
                                            <label id="periodLabel">Sélectionnez la période :</label>
                                            <div class="d-flex align-items-center">
                                                <input class="form-control me-2" type="date" id="period1"
                                                    name="period1" style="display: none;">
                                                <label class="me-2"> au </label>
                                                <input class="form-control" type="date" id="period2" name="period2"
                                                    style="display: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button onclick="imprimerPage()" type="button" class="btn btn-primary">Imprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>

    <script>
        // Fonction pour gérer le clic sur les boutons radio de type de paiement
        function handleRadioClick(selectedRadio) {
            const mensalRadio = document.getElementById('mensuel');
            const annualRadio = document.getElementById('annuel');
            const additionalField = document.getElementById('additionalField');
            const monthField = document.getElementById('monthField'); // Champ pour sélectionner le mois
            const periodField = document.getElementById('periodField');
            const monthInput = document.getElementById('month');
            const period1Input = document.getElementById('period1');
            const period2Input = document.getElementById('period2');

            // Réinitialiser tous les champs
            mensalRadio.checked = false;
            annualRadio.checked = false;
            mensalRadio.disabled = false;
            annualRadio.disabled = false;
            additionalField.style.display = 'none'; // Cacher le champ par défaut
            monthField.style.display = 'block';
            periodField.style.display = 'none';
            monthInput.style.display = 'none';
            period1Input.style.display = 'none';
            period2Input.style.display = 'none';

            if (selectedRadio.id === 'bordereauPaiements' || selectedRadio.id === 'releveMensuel') {
                mensalRadio.checked = true;
                mensalRadio.disabled = false;
                annualRadio.disabled = true;
                additionalField.style.display = 'block';
                monthField.style.display = 'block';
                monthInput.style.display = 'block';
                document.getElementById('additionalLabel').textContent = 'Sélectionnez le mois :';
            } else if (selectedRadio.id === 'bordereauCaisse') {
                annualRadio.checked = true;
                mensalRadio.disabled = true;
                additionalField.style.display = 'block';
                monthField.style.display = 'none';
                periodField.style.display = 'block';
                period1Input.style.display = 'block';
                period2Input.style.display = 'block';
                document.getElementById('periodLabel').textContent = 'Sélectionnez la période :';
            }
        }

        // Fonction pour obtenir le nom du mois à partir d'un nombre
        function getMonthName(month) {
            const monthNames = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];
            return monthNames[month - 1]; // Les mois sont indexés à partir de 0
        }

        // Fonction pour imprimer le journal
        function imprimerPage() {
            const releveMensuelRadio = document.getElementById('releveMensuel');
            const dateArrêt = document.getElementById('date').value;
            const moisSelectionne = document.getElementById('month').value;
            const [year, month] = moisSelectionne.split('-'); // Récupérer l'année et le mois

            // Formater la date d'arrêt au format j/m/a
            const [yearArrêt, monthArrêt, dayArrêt] = dateArrêt.split('-');
            const formattedDateArrêt = `${dayArrêt}/${monthArrêt}/${yearArrêt}`;

            // Obtenir le nom du mois sélectionné
            const monthName = getMonthName(parseInt(month)); // Convertir le mois en entier

            if (releveMensuelRadio.checked) {
                // Créer une fenêtre pour l'impression
                var printWindow = window.open('', '', 'height=600,width=800');

                // Récupérer le contenu du tableau
                var content = '';

                @foreach ($journals as $journal)
                    content += `<tr>
                                    <td>{{ $journal->DATEOP }}</td>
                                    <td>{{ $journal->NUMJ }}</td>
                                    <td>{{ $journal->CHAPITRE }}</td>
                                    <td>{{ $journal->NCOMPTE }}</td>
                                    <td>{{ $journal->LIBELOP }}</td>
                                    <td>{{ $journal->DEBIT }}</td>
                                    <td>{{ $journal->OBSER }}</td>
                                </tr>`;
                @endforeach

                // Construire le HTML à imprimer avec style
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Impression de Relevé mensuel des ordres de recettes</title>
                            <style>
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
                                h2{
                                    text-align: center;
                                }
                                th, td {
                                    border: 1px solid black;
                                    padding: 8px;
                                    text-align: left;
                                }
                                th {
                                    background-color: #D3D3D3; /* Entête gris */
                                }
                                @media print {
                                    body {
                                        font-family: Arial, sans-serif;
                                    }
                                    table {
                                        margin-top: 20px;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <h2>Relevé mensuel des ordres de recettes</h2>
                            <p>Mois de ${monthName} </p>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>N de l'Ordre</th>
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
                            <p>Total des recettes du mois: </p>
                            <p>Date d'arrêt: ${formattedDateArrêt}</p>
                        </body>
                    </html>
                `);

                printWindow.document.close(); // Fermer le document pour pouvoir l'imprimer
                printWindow.print(); // Ouvrir la boîte de dialogue d'impression
            } else {
                alert("Veuillez sélectionner 'Relevé mensuel des ordres de recettes' pour imprimer.");
            }
        }

        // Fonction pour gérer le clic sur les boutons radio de périodicité
        function handlePeriodiciteClick(selectedPeriodicite) {
            const monthInput = document.getElementById('month');
            const period1Input = document.getElementById('period1');
            const period2Input = document.getElementById('period2');

            if (selectedPeriodicite.id === 'mensuel') {
                monthInput.style.display = 'block';
                period1Input.style.display = 'none';
                period2Input.style.display = 'none';
            } else if (selectedPeriodicite.id === 'annuel') {
                monthInput.style.display = 'none';
                period1Input.style.display = 'block';
                period2Input.style.display = 'block';
            }
        }

        // Déclencher l'événement au chargement de la page pour afficher le champ par défaut
        window.onload = function() {
            // Récupérer la date actuelle
            const today = new Date().toISOString().split('T')[0]; // Format 'YYYY-MM-DD'

            // Définir la date actuelle dans le champ de date principal
            document.getElementById('date').value = today;

            // Définir la date actuelle dans les champs de période si affichés
            if (document.getElementById('period1')) {
                document.getElementById('period1').value = today;
            }
            if (document.getElementById('period2')) {
                document.getElementById('period2').value = today;
            }

            // Appeler la fonction par défaut pour initialiser l'affichage
            handleRadioClick(document.getElementById('bordereauPaiements'));
        };
    </script>
@endsection
