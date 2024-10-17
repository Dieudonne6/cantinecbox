@extends('layouts.master')
@section('content')
<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3>État des droits constatés par classe</h3>
                <br></br>
                <div class="form-group row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <form id="form-selection1">
                                    <label for="choixPlage">Choix de la plage</label>
                                    <div class="radio-inline">
                                        <input type="radio" id="option1" name="choixPlage" value="Scolarité">
                                        <label for="option1">Scolarité</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option2" name="choixPlage" value="Arrièrés">
                                        <label for="option2">Arrièrés</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option3" name="choixPlage" value="{{$params->LIBELF1}}">
                                        <label for="option3">{{$params->LIBELF1}}</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option4" name="choixPlage" value="{{$params->LIBELF2}}">
                                        <label for="option4">{{$params->LIBELF2}}</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option5" name="choixPlage" value="{{$params->LIBELF3}}">
                                        <label for="option5">{{$params->LIBELF3}}</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option6" name="choixPlage" value="{{$params->LIBELF4}}">
                                        <label for="option6">{{$params->LIBELF4}}</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option7" name="choixPlage" value="Scolarité + Arrièrés + {{ $params->LIBELF1 }} + {{ $params->LIBELF2 }} + {{ $params->LIBELF3 }} + {{ $params->LIBELF4 }}">
                                        <label for="option7">Tout</label>
                                    </div>
                                </form>     
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <label for="class-select">Sélectionnez une classe</label>
                        <form id="form-selection" method="GET" action="{{ route('etatdesdroits') }}" onsubmit="return false;">
                            <select id="class-select" class="js-example-basic-multiple w-100" name="selectedClasses[]" data-placeholder="Sélectionnez une classe" required>
                                @foreach ($classe as $classes)
                                <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                                @endforeach
                            </select>
                        </form>
                        <br>
                        <button type="button" class="btn btn-primary" id="apply-selection">Appliquer la sélection</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary justify-content-md-end" id="print-btn">Imprimer</button>
                <div id="result-table" style="display:none;">
                    <!-- Le tableau généré sera inséré ici -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
    .sub-header {
        background-color: #e0e0e0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('class-select');
    const applyButton = document.getElementById('apply-selection');
    const resultTable = document.getElementById('result-table');
    const printButton = document.getElementById('print-btn');

    applyButton.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page

        const selectedClasses = Array.from(selectElement.selectedOptions).map(option => option.value);
        const selectedPlage = document.querySelector('input[name="choixPlage"]:checked')?.value;

        if (!selectedClasses.length || !selectedPlage) {
            displayMessage('Veuillez sélectionner une classe et une plage.');
            return;
        }

        // Effectuer une requête AJAX pour récupérer les données des élèves
        fetch(`{{ route('etatdesdroits') }}?${new URLSearchParams({ 'selectedClasses[]': selectedClasses, choixPlage: selectedPlage })}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indiquer que c'est une requête AJAX
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Erreur lors de la récupération des données.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data || !data.eleves || data.eleves.length === 0) {
                displayMessage('Aucune donnée trouvée pour les classes sélectionnées.');
                resultTable.innerHTML = ''; // Réinitialiser le tableau
                resultTable.style.display = 'none'; // Masquer le tableau
                return;
            }

            resultTable.innerHTML = ''; // Réinitialiser le tableau

            // Créer un tableau pour chaque classe sélectionnée
            selectedClasses.forEach(classe => {
                const elevesClasse = data.eleves.filter(eleve => eleve.CODECLAS === classe);
                if (elevesClasse.length === 0) return;

                const table = document.createElement('div');
                table.innerHTML = `
                <br>
                    <h4>Classe : ${classe}</h4>
                    <h5>Objet de la recette : ${selectedPlage}</h5>
                    <table>
                        <tr>
                            <th rowspan="2">N°</th>
                            <th rowspan="2">NOM & PRENOMS</th>
                            <th rowspan="2">A PAYER</th>
                            <th colspan="6">MONTANT PAYE - VERSEMENTS</th>
                            <th rowspan="2">TOTAL PAYE</th>
                            <th rowspan="2">RESTE A PAYER</th>
                            <th rowspan="2">OBSE</th>
                        </tr>
                        <tr class="sub-header">
                            <th>VERS1</th>
                            <th>VERS2</th>
                            <th>VERS3</th>
                            <th>VERS4</th>
                            <th>VERS5</th>
                            <th>VERS6</th>
                        </tr>
                        ${elevesClasse.map((eleve, index) => {
                            const montants = eleve.montants || [];
                            const totalPaye = montants.reduce((acc, curr) => acc + (curr.MONTANT || 0), 0);
                            const resteAPayer = eleve.APAYER - totalPaye; // Calculer la différence entre APAYER et totalPaye
                            return `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${eleve.NOM} ${eleve.PRENOM}</td>
                                <td>${eleve.APAYER}</td>
                                ${[...Array(6)].map((_, i) => {
                                    // Récupérer le montant correspondant à chaque versement
                                    const montant = eleve.montants && eleve.montants[i] ? eleve.montants[i].MONTANT : 0;
                                    return `<td>${montant}</td>`;
                                }).join('')}
                                <td>${totalPaye}</td>
                                <td>${resteAPayer}</td> <!-- Utiliser la nouvelle variable resteAPayer -->
                                <td></td>
                            </tr>`;
                        }).join('')}
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>${elevesClasse.reduce((acc, eleve) => acc + eleve.APAYER, 0)}</strong></td>
                            <td colspan="6"></td>
                            <td><strong>${elevesClasse.reduce((acc, eleve) => {
                                const montants = eleve.montants || [];
                                return acc + montants.reduce((sum, curr) => sum + (curr.MONTANT || 0), 0);
                            }, 0)}</strong></td>
                            <td><strong>${elevesClasse.reduce((acc, eleve) => {
                                const montants = eleve.montants || [];
                                const totalPaye = montants.reduce((sum, curr) => sum + (curr.MONTANT || 0), 0);
                                return acc + (eleve.APAYER - totalPaye);
                            }, 0)}</strong></td>
                            <td></td>
                        </tr>
                    </table>
                    <br><br>
                `;
                resultTable.appendChild(table);
            });
            resultTable.style.display = 'block';
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
            displayMessage('Une erreur s\'est produite lors de la récupération des données : ' + error.message);
        });
    });

    // Fonction d'impression
    printButton.addEventListener('click', function() {
        const printContent = resultTable.innerHTML; // Récupérer le contenu du tableau
        const printWindow = window.open('', '', 'height=600,width=800'); // Ouvrir une nouvelle fenêtre
        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: center; } th { background-color: #f2f2f2; }</style>'); // Styles pour l'impression
        printWindow.document.write('<h2 style="text-align: center;">État des droits constatés par classe</h2>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent); // Insérer le contenu du tableau
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // Fermer le document
        printWindow.print(); // Lancer l'impression
    });

    // Fonction pour afficher les messages
    function displayMessage(message) {
        const messageContainer = document.createElement('div');
        messageContainer.className = 'alert alert-warning';
        messageContainer.textContent = message;
        document.body.prepend(messageContainer);

        setTimeout(() => {
            messageContainer.remove();
        }, 5000);
    }
});
</script>
        
@endsection