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
            <h5 class="mb-2">Exportation des élèves</h5>

            <!-- Zone d'upload et boutons sur une même ligne -->
            <div class="col-auto" style="margin-left: 15rem;">
                <div class="d-flex align-items-center" style="space-between: 10rem">     
                    <button class="btn btn-primary" onclick="exportToExcel()">Exporter vers Excel</button>
                </div>
            </div>

            <!-- Section d'affichage du contenu du fichier Excel -->
            <div class="mt-4" id="previewSection" style="overflow-x: auto;">
                <table class="table table-bordered table-striped" id="excelTable">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                             <th>Sexe</th>
                            <th>Statut</th>                          
                            <th>Classe</th>
                            <th>Date de naissance</th>
                            <th>Lieu de naissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eleves as $classe => $groupe)
                           
                            @foreach ($groupe as $eleve)
                                <tr>
                                    <td class="mat">{{ $eleve->MATRICULEX }}</td>
                                    <td>{{ $eleve->NOM }}</td>
                                    <td>{{ $eleve->PRENOM }}</td>
                                    <td>{{ $eleve->SEXE == 1 ? 'M' : 'F' }}</td>
                                    <td>{{ $eleve->STATUT == 0 ? 'N' : 'R' }}</td> 
                                    <td>{{ $eleve->CODECLAS}}</td>
                                    <td>{{ $eleve->DATENAIS }}</td>
                                    <td>{{ $eleve->LIEUNAIS }}</td>   
                                </tr>
                            @endforeach

                            <!-- 3 lignes vides après chaque groupe -->
                            <tr><td colspan="7">&nbsp;</td></tr>
                            <tr><td colspan="7">&nbsp;</td></tr>
                            <tr><td colspan="7">&nbsp;</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Section pour afficher d'éventuelles erreurs -->
            <div id="errorSection" class="mt-4"></div>
        </div>
    </div>
</div>

<script>
    function exportToExcel() {
        const table = document.getElementById("excelTable");

        if (!table) {
            alert("Aucun tableau à exporter !");
            return;
        }

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
                    font-size: 14px;
                    line-height: 1.5rem;
                }
                td.mat {
                    mso-number-format:"0"; /* format nombre sans décimale */
                }
            </style>
        `;

        const html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                ${style}
            </head>
            <body>
                ${table.outerHTML}
            </body>
            </html>
        `;

        const blob = new Blob([html], {
            type: 'application/vnd.ms-excel'
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "ficheDesEleves.xls";

        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

</script>


@endsection

