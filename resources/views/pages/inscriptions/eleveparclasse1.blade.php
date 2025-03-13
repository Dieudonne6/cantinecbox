@extends('layouts.master')
@section('content')
    <style>
        @media print {

            body * { visibility: hidden !important; }
                .print-table, .print-table * { visibility: visible !important; }
                .print-table { position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; }

           
            /* Masquer le premier tableau lors de l'impression */
            .main-table {
                display: none !important;
            }
            /* .main-table {
                display: none !important;
            } */

            /* Afficher le deuxième tableau uniquement lors de l'impression */


            .d-print-none {
                display: none !important;
            }

            /* .d-print-block {
                display: block !important;
            } */
        }

        /* Par défaut, le deuxième tableau est masqué */
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
                    <h4 class="card-title">Liste des élèves par classe</h4>
                    <div class="row mb-3 entete">
                        <div class="col-3">
                            <label for="" style="margin-top: 2px">Classe</label>
                            <select class="js-example-basic-multiple w-100" multiple="multiple" name="classeCode[]">
                                <option value="">Sélectionnez une classe</option>
                                @foreach ($classe as $classe)
                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mt-4">
                            <button class="btn-sm btn-primary" id="submitBtn1">Appliquer la sélection</button>
                        </div>

                        <div class="col-3">
                            <div class="form-group row">
                                <label for="titreEtat">Titre</label>
                                <div>
                                    <input type="text" class="form-control" id="titreEtat"
                                        style="width:15rem; height: 34px" />
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-4">
                            {{-- <button class="btn-sm btn-primary" id="printBtn">Imprimer</button> --}}
                            <button type="button" class="btn-sm btn-primary" onclick="imprimerPage()">Imprimer</button>
                        </div>
                    </div>

                    <div class="table table-bordered main-table">
                        <table id="myTable">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom et Prénom</th>
                                    <th>Date de Naissance</th>
                                    <th>Lieu de Naissance</th>
                                    <th>Sexe</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filterEleve as $filterEleves)
                                    <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}"
                                        data-prenom="{{ $filterEleves->PRENOM }}"
                                        data-codeclas="{{ $filterEleves->CODECLAS }}">
                                        <td>
                                            {{ $filterEleves->MATRICULEX }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->NOM }} {{ $filterEleves->PRENOM }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->LIEUNAIS }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->SEXE == 1 ? 'Masculin' : 'Feminin' }}
                                        </td>
                                        <td>
                                            <!-- Statut -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tableau imprimé uniquement -->
                    <div id="contenu">
                    <div class="table table-bordered print-table d-none">
                        @foreach ($elevesGroupes as $classeCode => $eleves)
                            <h2>Classe : {{ $classeCode }}</h2>

                            <div class="row effred">
                                <!-- Tableau des Effectifs -->
                                <div class="col1">
                                    @foreach ($statistiquesClasses as $stat)
                                        @if ($stat->CODECLAS == $classeCode)
                                            <div class="table table-bordered d-none d-print-block">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th></th> <!-- Ligne vide pour l'en-tête -->
                                                            <th>Filles</th>
                                                            <th>Garçons</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Effectif Total -->
                                                        <tr>
                                                            <td>Effectif</td>
                                                            <td>{{ $stat->total_filles }}</td>
                                                            <td>{{ $stat->total_garcons }}</td>
                                                            <td>{{ $stat->total }}</td>
                                                        </tr>
                                                        <!-- Redoublants -->
                                                        <tr>
                                                            <td>Redoublants</td>
                                                            <td>{{ $stat->total_filles_redoublants }}</td>
                                                            <td>{{ $stat->total_garcons_redoublants }}</td>
                                                            <td>{{ $stat->total_redoublants }}</td>
                                                        </tr>
                                                        <!-- Nouveaux -->
                                                        <tr>
                                                            <td>Nouveaux</td>
                                                            <td>{{ $stat->total_filles_nouveaux }}</td>
                                                            <td>{{ $stat->total_garcons_nouveaux }}</td>
                                                            <td>{{ $stat->total_nouveaux }}</td>
                                                        </tr>
                                                        <!-- Transférés -->
                                                        <tr>
                                                            <td>Transférés</td>
                                                            <td>{{ $stat->total_filles_transferes }}</td>
                                                            <td>{{ $stat->total_garcons_transferes }}</td>
                                                            <td>{{ $stat->total_transferes }}</td>
                                                        </tr>
                                                        <!-- Anciens -->
                                                        <tr>
                                                            <td>Anciens</td>
                                                            <td>{{ $stat->total_filles_anciens }}</td>
                                                            <td>{{ $stat->total_garcons_anciens }}</td>
                                                            <td>{{ $stat->total_anciens }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>


                                <!-- Tableau des Réductions/Profils -->
                                <div class="col2">
                                    <div class="table table-bordered d-none d-print-block">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Réduction</th>
                                                    <th>Nombre d'Élèves</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($reductionsParClasse as $reduction)
                                                    @if ($reduction->CODECLAS == $classeCode)
                                                        <tr>
                                                            <td>{{ $reduction->libelleReduction }}</td>
                                                            <td>{{ $reduction->total }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Tableau des élèves de la classe -->
                            {{-- <h4>Liste des élèves de la classe {{ $classeCode }}</h4> --}}
                            <table id="tableClasse_{{ $classeCode }}">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Matricule</th>
                                        <th>Nom et Prénom</th>
                                        <th>Date de Naissance</th>
                                        <th>Lieu de Naissance</th>
                                        <th>Sexe</th>
                                        <th>NV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $index => $eleve)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $eleve->MATRICULEX }}</td>
                                            <td>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                                            <td>{{ \Carbon\Carbon::parse($eleve->DATENAIS)->format('d/m/Y') }}</td>
                                            <td>{{ $eleve->LIEUNAIS }}</td>
                                            <td>{{ $eleve->SEXE == 1 ? 'Masculin' : 'Féminin' }}</td>
                                            <td>
                                                <input type="checkbox" {{ $eleve->statutG == 1 ? 'checked' : '' }}
                                                    disabled >
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                                <!-- Ajouter une autre rupture de page avant la classe suivante -->
                                <div class="page-break"></div>
                        @endforeach
                    </div>
                    </div>

                </div>
            </div>
    </div>

        {{-- <script>
            document.getElementById('printBtn').addEventListener('click', function() {
                const titre = document.getElementById('titreEtat').value;

                // Insérer le titre dans un élément caché pour l'impression
                const titreImpression = document.createElement('div');
                titreImpression.id = 'printTitre';
                titreImpression.innerHTML = `<h2>${titre}</h2>`;
                document.body.appendChild(titreImpression);

                // Rendre les tableaux par classe visibles pour l'impression
                const printTables = document.querySelectorAll('.print-table');
                printTables.forEach(table => table.classList.remove('d-none'));

                // Lancer l'impression
                window.print();

                // Masquer à nouveau les tableaux après impression
                printTables.forEach(table => table.classList.add('d-none'));

                // Supprimer l'élément du titre après impression
                document.body.removeChild(titreImpression);
            });
        </script> --}}



        <script>
function imprimerPage() {
    let originalTitle = document.title;
    document.title = 'Liste des élèves';
    
    let titreEtat = document.getElementById('titreEtat').value;
    
    // Utiliser un titre par défaut si le champ est vide
    if (!titreEtat) {
        titreEtat = 'Liste des élèves';
    }

    // Récupérer le contenu à imprimer (le tableau dans la div avec id "contenu")
    let contenuImpression = document.getElementById('contenu').innerHTML;

    // Créer une nouvelle fenêtre pour l'impression
    let printWindow = window.open('', '', 'height=700,width=1600');

    printWindow.document.write(`
        <html>
        <head>
            <title>${titreEtat}</title>
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
            <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">${titreEtat}</h1>
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



