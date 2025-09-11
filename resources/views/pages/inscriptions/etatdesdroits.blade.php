@extends('layouts.master')
@section('content')
<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div>
                <style>
                    .btn-arrow {
                        position: absolute;
                        top: 0px;
                        left: 0px;
                        background-color: transparent !important;
                        border: 1px !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        color: #b51818 !important;
                    }
            
                    .btn-arrow:hover {
                        color: #b700ff !important;
                    }

                    /* Scrollable table wrapper */
                    #result-table-container {
                        max-height: 600px;
                        overflow: auto;
                        border: 1px solid #ddd;
                        padding: 5px;
                    }
                    .table-wrapper {
                        overflow-x: auto;
                        margin-bottom: 20px;
                    }
                    .table-scrollable {
                        min-width: 1000px; /* Ajuster si besoin */
                        border-collapse: collapse;
                        width: 100%;
                    }
                    .table-scrollable thead th {
                        position: sticky;
                        top: 0;
                        background: #f2f2f2;
                        z-index: 2;
                    }
                    .table-scrollable th, .table-scrollable td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: center;
                    }
                    .sub-header {
                        background-color: #e0e0e0;
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>                                     
            </div>
            <div class="card-body">
                <h3>État des droits constatés par classe</h3>
                <br>
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
                                        <input type="radio" id="option7" name="choixPlage" value="Tout">
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
                <button type="button" class="btn btn-primary justify-content-md-end" id="excel-btn">Exporter vers Excel</button>
                
                <div id="result-table-container">
                    <div id="result-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('class-select');
    const applyButton = document.getElementById('apply-selection');
    const resultTable = document.getElementById('result-table');
    const printButton = document.getElementById('print-btn');
    const excelButton = document.getElementById('excel-btn');

    let lastGeneratedHTML = "";

    applyButton.addEventListener('click', function(event) {
        event.preventDefault(); 

        const selectedClasses = Array.from(selectElement.selectedOptions).map(option => option.value);
        const selectedPlage = document.querySelector('input[name="choixPlage"]:checked')?.value;

        if (!selectedClasses.length || !selectedPlage) {
            displayMessage('Veuillez sélectionner une classe et une plage.');
            return;
        }

        fetch(`{{ route('etatdesdroits') }}?${new URLSearchParams({ 'selectedClasses[]': selectedClasses, choixPlage: selectedPlage })}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (!data || !data.eleves || data.eleves.length === 0) {
                displayMessage('Aucune donnée trouvée pour les classes sélectionnées.');
                resultTable.innerHTML = '';
                resultTable.style.display = 'none';
                return;
            }

            resultTable.innerHTML = '';
            lastGeneratedHTML = "";

            selectedClasses.forEach(classe => {
                const elevesClasse = data.eleves.filter(eleve => eleve.CODECLAS === classe);
                if (elevesClasse.length === 0) return;

                const maxVersements = Math.max(...elevesClasse.map(eleve => (eleve.montants?.length || 0)));

                const tableHTML = `
                <div class="table-wrapper">
                    <table class="table-scrollable">
                        <tr>
                            <th rowspan="2">N°</th>
                            <th rowspan="2">NOM & PRENOMS</th>
                            <th rowspan="2">A PAYER</th>
                            <th colspan="${maxVersements}">MONTANT PAYE - VERSEMENTS</th>
                            <th rowspan="2">TOTAL PAYE</th>
                            <th rowspan="2">RESTE A PAYER</th>
                            <th rowspan="2">OBSE</th>
                        </tr>
                        <tr class="sub-header">
                            ${Array.from({ length: maxVersements }, (_, i) => `<th>VERS${i+1}</th>`).join('')}
                        </tr>
                        ${elevesClasse.map((eleve, index) => {
                            const montants = eleve.montants || [];
                            const totalPaye = montants.reduce((acc, curr) => acc + (curr.MONTANT || 0), 0);
                            const resteAPayer = eleve.APAYER - totalPaye;
                            return `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${eleve.NOM} ${eleve.PRENOM}</td>
                                <td>${eleve.APAYER}</td>
                                ${Array.from({ length: maxVersements }, (_, i) => montants[i] ? `<td>${montants[i].MONTANT}</td>` : `<td>0</td>`).join('')}
                                <td>${totalPaye}</td>
                                <td>${resteAPayer}</td>
                                <td></td>
                            </tr>`;
                        }).join('')}
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>${elevesClasse.reduce((acc, eleve) => acc + eleve.APAYER, 0)}</strong></td>
                            ${Array.from({ length: maxVersements }, () => `<td></td>`).join('')}
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
                </div>
                `;

                lastGeneratedHTML += tableHTML;
                const table = document.createElement('div');
                table.innerHTML = tableHTML;
                resultTable.appendChild(table);
            });

            resultTable.style.display = 'block';
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
            displayMessage('Une erreur s\'est produite : ' + error.message);
        });
    });

    printButton.addEventListener('click', function() {
        if (!lastGeneratedHTML) {
            displayMessage("Veuillez générer un tableau avant d'imprimer.");
            return;
        }
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>.table-scrollable {border-collapse: collapse; width: 100%;} .table-scrollable th, .table-scrollable td {border: 1px solid #ddd; padding: 8px; text-align: center;} .sub-header {background-color: #e0e0e0;}</style>');
        printWindow.document.write('<h2 style="text-align: center;">État des droits constatés par classe</h2>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(lastGeneratedHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });

    excelButton.addEventListener('click', function() {
        if (!lastGeneratedHTML) {
            displayMessage("Veuillez générer un tableau avant d'exporter.");
            return;
        }
        const selectedPlage = document.querySelector('input[name="choixPlage"]:checked')?.value;
        const selectedClasse = selectElement.value || "Toutes_Classes";
        const blob = new Blob(["\uFEFF" + lastGeneratedHTML], { type: "application/vnd.ms-excel;charset=utf-8" });
        const fileName = `${selectedPlage}_${selectedClasse}.xls`;
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    function displayMessage(message) {
        const messageContainer = document.createElement('div');
        messageContainer.className = 'alert alert-warning';
        messageContainer.textContent = message;
        document.body.prepend(messageContainer);
        setTimeout(() => { messageContainer.remove(); }, 5000);
    }
});
</script>
@endsection
