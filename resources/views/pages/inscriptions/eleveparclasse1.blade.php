@extends('layouts.master')
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }
            .print-table, .print-table * {
                visibility: visible !important;
            }
            .print-table {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
            }
            .main-table {
                display: none !important;
            }
            .d-print-none {
                display: none !important;
            }
        }
    </style>
    <div class="main-panel-10">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
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
                                    <input type="text" class="form-control" id="titreEtat" style="width:15rem; height: 34px" />
                                </div>
                            </div>
                        </div>
                        <div class="col-3 mt-4">
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
                                    <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}" data-prenom="{{ $filterEleves->PRENOM }}" data-codeclas="{{ $filterEleves->CODECLAS }}">
                                        <td>{{ $filterEleves->MATRICULEX }}</td>
                                        <td>{{ $filterEleves->NOM }} {{ $filterEleves->PRENOM }}</td>
                                        <td>{{ \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') }}</td>
                                        <td>{{ $filterEleves->LIEUNAIS }}</td>
                                        <td>{{ $filterEleves->SEXE == 1 ? 'Masculin' : 'Feminin' }}</td>
                                        <td><!-- Statut --></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="contenu">
                        <div class="table table-bordered print-table d-none">
                            @foreach ($elevesGroupes as $classeCode => $eleves)
                                <h4>Classe : {{ $classeCode }}</h4>
                                <div class="row">
                                    <div class="col1">
                                        @foreach ($statistiquesClasses as $stat)
                                            @if ($stat->CODECLAS == $classeCode)
                                                <div class="table table-bordered d-none d-print-block">
                                                    <table style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Filles</th>
                                                                <th>Garçons</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Effectif</td>
                                                                <td>{{ $stat->total_filles }}</td>
                                                                <td>{{ $stat->total_garcons }}</td>
                                                                <td>{{ $stat->total }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Redoublants</td>
                                                                <td>{{ $stat->total_filles_redoublants }}</td>
                                                                <td>{{ $stat->total_garcons_redoublants }}</td>
                                                                <td>{{ $stat->total_redoublants }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nouveaux</td>
                                                                <td>{{ $stat->total_filles_nouveaux }}</td>
                                                                <td>{{ $stat->total_garcons_nouveaux }}</td>
                                                                <td>{{ $stat->total_nouveaux }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Transférés</td>
                                                                <td>{{ $stat->total_filles_transferes }}</td>
                                                                <td>{{ $stat->total_garcons_transferes }}</td>
                                                                <td>{{ $stat->total_transferes }}</td>
                                                            </tr>
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
                                                <td><input type="checkbox" {{ $eleve->statutG == 1 ? 'checked' : '' }} disabled></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function imprimerPage() {
    let originalTitle = document.title;
    document.title = `Liste des élèves`;
    let table = $('#myTable').DataTable();
    let currentPage = table.page(); // Enregistrer la page actuelle
    table.destroy(); // Désactiver DataTables avant impression

    setTimeout(function() {
        let titreEtat = document.getElementById('titreEtat').value;
        if (!titreEtat) {
            titreEtat = `Liste des élèves`;
        }

        let printDiv = document.createElement('div');
        printDiv.innerHTML = `
            <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">
                ${titreEtat}
            </h1>
            ${document.getElementById('contenu').innerHTML}
        `;

        let style = document.createElement('style');
        style.innerHTML = `
            @page { size: landscape; }
            @media print {
                /* .main-table {
                    display: none !important;
                } */
                .print-table {
                    display: block !important;
                  /*   position: relative !important;
                    width: 100% !important; */
                }
                /* table {
                    overflow: visible;
                } */
                #contenu {
                    display: block;
                    left: 0 !important;
                    top: 0 !important;
                }
            }
        `;

        document.head.appendChild(style);
  /*       document.body.appendChild(printDiv); */
        printDiv.setAttribute("id", "printDiv");
        window.print();
        
        // Rétablir DataTables après impression
      /*   table = $('#myTable').DataTable();
        table.page(currentPage).draw(false); */ // Restaurer la page actuelle

        // Nettoyage
        document.body.removeChild(printDiv);
        document.head.removeChild(style);
    }, 100);
}

        </script>
    @endsection
