@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <!-- Bouton de retour -->
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
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <br><br>
        </div>
        <div class="card-body">
            <h5 class="mb-2">Importation des élèves</h5>

            <!-- Zone d'upload et boutons sur une même ligne -->
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <input type="file" class="form-control me-2" id="fileInput" name="excelFile" accept=".xlsx, .xls, .csv" />
                    <button class="btn btn-primary me-2" onclick="previewExcel()">Prévisualiser</button>
                    <button class="btn btn-primary" onclick="importExcel()">Importer</button>
                </div>
            </div>

            <!-- Section d'affichage du contenu du fichier Excel -->
            <div class="mt-4" id="previewSection">
                <table class="table table-bordered table-striped" id="excelTable"></table>
            </div>

            <!-- Section pour afficher d'éventuelles erreurs -->
            <div id="errorSection" class="mt-4"></div>
        </div>
    </div>
</div>

<!-- Importer SheetJS via CDN (ou via npm et votre build) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // Fonction pour prévisualiser le contenu du fichier Excel en utilisant SheetJS
    function previewExcel() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        alert("Veuillez sélectionner un fichier Excel.");
        return;
    }

    const reader = new FileReader();
    reader.readAsBinaryString(file);
 
    reader.onload = function(e) {
        const data = e.target.result;
        const workbook = XLSX.read(data, { type: "binary" });
        const firstSheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[firstSheetName];
        
        // Convertir la feuille en JSON, incluant les en-têtes
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        
        // Extraire la première ligne (les en-têtes) pour identifier les colonnes
        const headers = jsonData[0];

        // Parcourir les données et reformater les dates
        const formattedData = jsonData.map((row, rowIndex) => {
            return row.map((cell, colIndex) => {
                // Si la cellule est un nombre (valeur Excel pour une date) et que ce n'est pas la colonne "Redoublant"
                if (typeof cell === 'number' && headers[colIndex] !== "Redoublant") {
                    return formatDateFromExcel(cell);
                }
                // Sinon, retourner la cellule telle quelle
                return cell;
            });
        });

        // Convertir les données formatées en HTML
        const html = jsonToHtmlTable(formattedData);
        document.getElementById('excelTable').innerHTML = html;
    };
}

// Fonction pour reformater la date à partir d'une valeur Excel (nombre)
function formatDateFromExcel(excelDate) {
    const epoch = new Date(1900, 0, 1); // 1er janvier 1900 (date de départ Excel)
    const date = new Date(epoch.getTime() + (excelDate - 2) * 86400000); // Correction du bug Excel en soustrayant 2
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

// Fonction pour convertir un tableau JSON en HTML
function jsonToHtmlTable(data) {
    let html = '<table class="table table-bordered table-striped">';
    data.forEach((row, rowIndex) => {
        html += '<tr>';
        row.forEach(cell => {
            html += `<td>${cell}</td>`;
        });
        html += '</tr>';
    });
    html += '</table>';
    return html;
}




    // Fonction pour importer le fichier via AJAX vers Laravel
    function importExcel() {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];

        if (!file) {
            alert("Veuillez sélectionner un fichier Excel.");
            return;
        }

        const formData = new FormData();
        formData.append('excelFile', file);

        fetch("{{ route('eleves.import') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
            } else {
                document.getElementById('errorSection').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error("Erreur :", error);
            console.info("");
            document.getElementById('errorSection').innerHTML = `<div class="alert alert-danger">Erreur lors de l'importation.</div>`;
        });
    }
</script>
@endsection

