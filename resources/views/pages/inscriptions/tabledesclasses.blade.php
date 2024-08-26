@extends('layouts.master')
@section('content')
    <style>
        /* Styles spécifiques pour l'impression */

        /* Masquer les colonnes non imprimées */
        @media print {
            .print-hide {
                display: none !important;
            }

            /* Supprimer les bordures pour les colonnes non imprimées */
            .print-hide,
            .print-hide+td {
                border: none !important;
            }
        }
    </style>



    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if (Session::has('status'))
                        <div id="statusAlert" class="alert alert-success btn-primary">
                            {{ Session::get('status') }}
                        </div>
                    @endif
                    <h4 class="card-title">Mise a jour des classes</h4>
                    {{-- <div class="row"> --}}
                    <a type="button" class="btn btn-primary" href="{{ url('/enrclasse') }}">Nouveau</a>
                    <a type="button" class="btn btn-primary" href="{{ url('/groupe') }}">Groupe</a>
                    <button onclick="imprimerPage()" class="btn btn-secondary">Imprimer</button><br>

                    {{-- </div> --}}

                    {{-- <h5 style="text-align: center; color: rgb(188, 64, 64)">Scolarite</h5> --}}
                    <div class="table-responsive pt-3" id="contenu">

                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Gpe Peda</th>
                                    <th>Libelle</th>
                                    <th>Type classe</th>
                                    <th>Promotion</th>
                                    <th>Cycle</th>
                                    <th>Serie</th>
                                    <th>Enseignement</th>
                                    <th>Effectifs</th>
                                    <th class="print-hide">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($classes as $classe)
                                    <tr>
                                        <td>{{ $classe->CODECLAS }}</td>
                                        <td>{{ $classe->LIBELCLAS }}</td>
                                        <td>{{ $classe->typeclasse_LibelleType }}</td>
                                        <td>{{ $classe->CODEPROMO }}</td>
                                        <td>{{ $classe->CYCLE }}</td>
                                        {{-- <td>{{ ($classe->serie)->LIBELSERIE }}</td> --}}
                                        <td>{{ $classe->serie_libelle }}</td>
                                        <td>{{ $classe->typeenseigne_type }}</td>
                                        <td>{{ $classe->EFFECTIF }}</td>
                                        <td class="print-hide">
                                            <div class="d-flex align-items-center">
                                                <!-- Button trigger modal -->
                                                <button class="btn btn-primary p-2 btn-sm dropdown" type="button"
                                                    id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="typcn typcn-th-list btn-icon-append"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3"
                                                    style="">
                                                    <li><a class="dropdown-item"
                                                            href="/modifierclasse/{{ $classe->CODECLAS }}">Modifier</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalDelete{{ $classe->CODECLAS }}">Supprimer</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal bouton supprimer -->
                                    <div class="modal fade" id="exampleModalDelete{{ $classe->CODECLAS }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de
                                                        suppression</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer cette classe ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ url('/supprimerclass') }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="CODECLAS"
                                                            value="{{ $classe->CODECLAS }}">
                                                        <input type="submit" class="btn btn-danger" value="Confirmer">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <br>

                </div>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="modifgroupe" tabindex="-1" aria-labelledby="modalnouveauLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalnouveauLabel">Groupe</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="exampleSelectGender">Choisir le groupe</label>
                            <select class="form-control" id="exampleSelectGender">
                                <option>Ens. General</option>
                                <option>Ens. Maternel</option>
                                <option>Ens. Primaire</option>
                                <option>Ens. Professionnel</option>
                                <option>Ens. Technique</option>
                                <option>Standard</option>
                            </select>
                        </div>



                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button> --}}
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function imprimerPage() {
        var table = document.getElementById('myTable');
        var contenu = document.getElementById('contenu').innerHTML;

        if (!contenu) {
            alert('Le contenu à imprimer est vide.');
            return;
        }

        // Cloner le tableau pour manipulation
        var tableClone = table.cloneNode(true);

        // Masquer les colonnes non imprimées
        var columnsToHide = tableClone.querySelectorAll('.print-hide');

        columnsToHide.forEach(function(cell) {
            var colIndex = cell.cellIndex;

            // Masquer toutes les cellules dans cette colonne
            tableClone.querySelectorAll('td:nth-child(' + (colIndex + 1) + '), th:nth-child(' + (colIndex + 1) +
                ')').forEach(function(cell) {
                cell.style.display = 'none';
            });
        });

        // Préparer le contenu HTML pour l'impression
        var simplifiedContent = '<div>' + tableClone.outerHTML + '</div>';

        var page = window.open('', 'blank_');
        if (!page) {
            alert('Impossible d\'ouvrir une nouvelle fenêtre.');
            return;
        }

        page.document.write('<html><head><title>Liste des classes</title>');
        page.document.write(
            '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >'
            );
        page.document.write(
            '<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 4px; } tbody tr:nth-child(even) { background-color: #f1f3f5; } tbody tr:nth-child(odd) { background-color: #ffffff; } </style>'
            );
        page.document.write('</head><body>');
        page.document.write(simplifiedContent);
        page.document.write('</body></html>');
        page.document.close();


        setTimeout(function() {
            page.print();
            page.close();
        }, 500);
    }
</script>
