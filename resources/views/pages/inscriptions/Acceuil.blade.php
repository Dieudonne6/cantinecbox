@extends('layouts.master')

@section('content')
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

            @if (Session::has('status'))
                <div id="statusAlert" class="alert alert-success btn-primary">
                    {{ Session::get('status') }}
                </div>
            @endif
            {{--  --}}


            <div class="card-body">
                <h4 class="card-title mt-2">Accueil</h4>
                <div class="row gy-6">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <a class="btn btn-primary btn-sm" href="{{ url('/inscrireeleve') }}">
                                <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau
                            </a>
                            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="typcn typcn-printer btn-icon-prepend"></i> Filtrer pour imprimer
                            </button>
                        </div>
                        {{-- <form action="{{ route('Acceuil') }}" method="GET">
                            <div class="col-12">
                                <select name="classe" class="js-example-basic-multiple w-100"
                                    onchange="this.form.submit()">
                                    @foreach ($allClass as $classes)
                                        <option value="{{ $classes->CODECLAS }}"
                                            {{ request()->input('classe', '6E1') == $classes->CODECLAS ? 'selected' : '' }}>
                                            {{ $classes->CODECLAS }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form> --}}

                        <div>
                            <table id="tableau-effectifs" class="table">
                                <tbody>
                                    <tr>
                                        <td class="bouton">Eff.Total</td>
                                        <td id="total">942</td>
                                        <td class="bouton">Filles</td>
                                        <td id="filles">60</td>
                                        <td class="bouton">Garçons</td>
                                        <td id="garcons">742</td>
                                    </tr>
                                    <tr>
                                        <td class="bouton">Eff.Red</td>
                                        <td id="total-red">10</td>
                                        <td class="bouton">Red.Filles</td>
                                        <td id="filles-red">2</td>
                                        <td class="bouton">Red.Garçons</td>
                                        <td id="garcons-red">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
                        id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Filtrage</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="mb-3">
                                <select id="filterClasse" class="w-100 mb-3 js-example-basic-multiple">
                                    <option value="">Selectionnez la classe</option>
                                    <option value="Toute la classe">Toute la classe</option>
                                    @foreach ($allClass as $allClasse)
                                        <option value="{{ $allClasse->CODECLAS }}">{{ $allClasse->CODECLAS }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterSexe" class="js-example-basic-multiple w-100 mb-3">
                                    <option value="">Selectionnez le sexe</option>
                                    <option value="1">Masculin</option>
                                    <option value="2">Feminin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <select id="filterSerie" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez la série</option>
                                    @foreach ($serie as $series)
                                        <option value="{{ $series->SERIE }}">{{ $series->LIBELSERIE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterCategory" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez la catégorie</option>
                                    <option value="2">Ancien</option>
                                    <option value="1">Nouveau</option>
                                    {{-- <option value="3">Transférés</option> --}}
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterStatut" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez le statut</option>
                                    <option value="1">Redoublant</option>
                                    <option value="0">Non Redoublant</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterPromo" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez la promotion</option>
                                    @foreach ($promotion as $promo)
                                        <option value="{{ $promo->CODEPROMO }}">{{ $promo->LIBELPROMO }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterTypeClas" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez le type de classe </option>
                                    @foreach ($typeclah as $typecla)
                                        <option value="{{ $typecla->TYPECLASSE }}">{{ $typecla->LibelleType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterEnseign" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez le type d'enseignement</option>
                                    @foreach ($typeenseigne as $typeenseign)
                                        <option value="{{ $typeenseign->idensign }}">{{ $typeenseign->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select id="filterCycle" class="js-example-basic-multiple w-100">
                                    <option value="">Selectionnez le cycle</option>
                                    <option value="0">Aucun</option>
                                    <option value="1">1ere cycle</option>
                                    <option value="2">2eme cycle</option>
                                    <option value="3">3eme cycle</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button onclick="imprimerPage()" type="button" class="btn btn-primary">
                                    Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your recalculating script -->
                {{--  --}}
                <div id="contenu">
                    <div class="table-responsive mb-4">
                        <table id="myTab" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="ml-6">Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Classe</th>
                                    <th>Sexe</th>
                                    <th class="d-none"></th>
                                    <th class="d-none"></th>
                                    <th class="d-none"></th>
                                    <th class="d-none"></th>
                                    <th class="d-none"></th>
                                    <th class="d-none">Promo</th>
                                    <th class="d-none">Cycle</th>
                                    <th>Red.</th>
                                    <th>Date nai</th>
                                    <th>Lieunais</th>
                                    <th class="hide-printe">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($eleves as $eleve)
                                    <tr>
                                        <td>{{ $eleve->MATRICULEX }}</td>
                                        <td>{{ $eleve->NOM }} <br>{{ $eleve->PRENOM }}</td>
                                        <td data-classe="{{ $eleve->CODECLAS }}">{{ $eleve->CODECLAS }}</td>
                                        <td data-sexe="{{ $eleve->SEXE }}">
                                            @if ($eleve->SEXE == 1)
                                                M
                                            @elseif($eleve->SEXE == 2)
                                                F
                                            @else
                                                Non spécifié
                                            @endif
                                        </td>
                                        {{-- <td class="" data-promi="{{ $eleve->classe->promo->CODEPROMO }}">{{ $eleve->classe->promo->LIBELPROMO }}</td> --}}
                                        <td class="d-none" data-cycle="{{ $eleve->classe->CYCLE }}">
                                            {{ $eleve->classe->CYCLE }}</td>
                                        <td class="d-none" data-promo="{{ $eleve->classe->promo->CODEPROMO }}"></td>
                                        <td class="d-none" data-category="{{ $eleve->STATUTG }}"></td>
                                        <td class="d-none" data-statut="{{ $eleve->STATUT }}"></td>
                                        <td class="d-none" data-serie="{{ $eleve->SERIE }}"></td>
                                        <td class="d-none" data-typeenseign="{{ $eleve->TYPEENSEIGN }}"></td>
                                        <td class="d-none" data-typeclasse="{{ $eleve->TYPECLASSE }}"></td>

                                        <td class="checkboxes-select" style="width: 24px;">
                                            <input type="checkbox" class="form-check-input-center"
                                                {{ $eleve->STATUT ? 'checked' : '' }}>
                                        </td>
                                        @php
                                            $dateNaissance = $eleve->DATENAIS;
                                            $dateFormatted = $dateNaissance
                                                ? \Carbon\Carbon::parse($dateNaissance)->format('d/m/Y')
                                                : '';
                                        @endphp

                                        <td>{{ $dateFormatted }}</td>
                                        <td>{{ $eleve->LIEUNAIS }}</td>
                                        <td class="hide-printe">
                                            <div class="d-flex align-items-center">
                                                <a href="/pagedetail/{{ $eleve->MATRICULE }}"
                                                    class= "btn btn-primary p-2 btn-sm mr-2">Voir plus</a>
                                                <button class="btn btn-primary p-2 btn-sm dropdown" type="button"
                                                    id="dropdownMenuSizeButton3" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="typcn typcn-th-list btn-icon-append"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                                                    <li>
                                                        <button class="dropdown-item delete-eleve"
                                                            data-matricule="{{ $eleve->MATRICULE }}"
                                                            data-nom="{{ $eleve->NOM }}"
                                                            data-prenom="{{ $eleve->PRENOM }}" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal">
                                                            Supprimer
                                                        </button>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="/modifiereleve/{{ $eleve->MATRICULE }}">Modifier</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ url('/paiementeleve/' . $eleve->MATRICULE) }}">Paiement</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ url('/majpaiementeleve/' . $eleve->MATRICULE) }}">Maj
                                                            Paie</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="/profil/{{ $eleve->MATRICULE }}">Profil</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="/echeancier/{{ $eleve->MATRICULE }}">Echéance</a>
                                                    </li>
                                                    {{--  <li><a class="dropdown-item" href="#">Cursus</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="noResultsMessage" class="alert alert-success btn-primary my-2" style="display: none;">
                            Aucun résultat trouvé.
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('select[name="classe"]').on('change', function() {
                                var selectedClass = $(this).val();

                                $.ajax({
                                    url: '{{ route('Acceuil') }}',
                                    type: 'GET',
                                    data: {
                                        classe: selectedClass
                                    },
                                    success: function(response) {
                                        // Supposons que votre réponse contienne le tableau HTML généré
                                        $('#tableContainer').html(response);
                                    },
                                    error: function() {
                                        alert('Une erreur est survenue lors de la récupération des élèves.');
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer l'élève ?
                    <p id="eleveName"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Scripts JavaScript à placer à la fin du body pour optimiser le chargement -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal de suppression
            document.querySelectorAll('.delete-eleve').forEach(button => {
                button.addEventListener('click', function() {
                    const matricule = this.getAttribute('data-matricule');
                    const nom = this.getAttribute('data-nom');
                    const prenom = this.getAttribute('data-prenom');

                    document.getElementById('eleveName').textContent = `${nom} ${prenom}`;
                    document.getElementById('deleteForm').action = `/eleves/${matricule}`;
                });
            });
        });

        function imprimerPage() {
            let originalTitle = document.title;
            document.title = `Liste des eleves`;

            setTimeout(function() {
                let selectedClasse = $('#filterClasse option:selected').text().trim();
                let selectedSexe = $('#filterSexe option:selected').text().trim();
                let selectedStatut = $('#filterStatut option:selected').text().trim();
                let selectedSerie = $('#filterSerie option:selected').text().trim();
                let selectedEnseign = $('#filterEnseign option:selected').text().trim();
                let selectedTypeClas = $('#filterTypeClas option:selected').text().trim();
                let selectedCategory = $('#filterCategory option:selected').text().trim();
                let selectedCycle = $('#filterCycle option:selected').text().trim();
                let selectedPromo = $('#filterPromo option:selected').text().trim();

                // Créer une chaîne de critères sélectionnés
                let criteria = '<div>';

                // Afficher les critères uniquement si une option différente de la valeur par défaut est sélectionnée
                if (selectedClasse && selectedClasse !== "Selectionnez la classe") {
                    criteria += `<p><strong>Classe :</strong> ${selectedClasse}</p>`;
                }
                if (selectedSexe && selectedSexe !== "Selectionnez le sexe") {
                    criteria += `<p><strong>Sexe :</strong> ${selectedSexe}</p>`;
                }
                if (selectedStatut && selectedStatut !== "Selectionnez le statut") {
                    criteria += `<p><strong>Statut :</strong> ${selectedStatut}</p>`;
                }
                if (selectedSerie && selectedSerie !== "Selectionnez la série") {
                    criteria += `<p><strong>Série :</strong> ${selectedSerie}</p>`;
                }
                if (selectedEnseign && selectedEnseign !== "Selectionnez le type d'enseignement") {
                    criteria += `<p><strong>Type d'enseignement :</strong> ${selectedEnseign}</p>`;
                }
                if (selectedTypeClas && selectedTypeClas !== "Selectionnez le type de classe") {
                    criteria += `<p><strong>Type de classe :</strong> ${selectedTypeClas}</p>`;
                }
                if (selectedCategory && selectedCategory !== "Selectionnez la catégorie") {
                    criteria += `<p><strong>Catégorie :</strong> ${selectedCategory}</p>`;
                }
                if (selectedCycle && selectedCycle !== "Selectionnez le cycle") {
                    criteria += `<p><strong>Cycle :</strong> ${selectedCycle}</p>`;
                }
                if (selectedPromo && selectedPromo !== "Selectionnez la promotion") {
                    criteria += `<p><strong>Promotion :</strong> ${selectedPromo}</p>`;
                }
                criteria += '</div>';

                // Créer un élément invisible pour l'impression
                let printDiv = document.createElement('div');
                printDiv.innerHTML =
                    '<h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">Liste des eleves selon criteres </h1>' +
                    criteria +
                    document.getElementById('contenu').innerHTML;

                // Appliquer des styles pour l'impression
                let style = document.createElement('style');
                style.innerHTML = `@page { size: landscape; }
            @media print {
                body * { visibility: hidden; }
                #printDiv, #printDiv * { visibility: visible; }
                #printDiv { position: absolute; top: 0; left: 0; width: 100%; }
                .dt-end, .dt-start, .hide-printe, .offcanvas { display: none !important; }
                table th {
                    font-weight: bold !important;
                    font-size: 12px !important;
                }
                table th, table td {
                    font-size: 10px;
                    margin: 0 !important;
                    padding: 0 !important;
                    border-collapse: collapse !important;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    page-break-inside: auto;
                }
                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
                tfoot {
                    display: table-row-group;
                    page-break-inside: avoid;
                }
                tbody tr:nth-child(even) {
                    background-color: #f1f3f5;
                }
                tbody tr:nth-child(odd) {
                    background-color: #ffffff;
                }
            }
        `;
                document.head.appendChild(style);
                document.body.appendChild(printDiv);
                printDiv.setAttribute("id", "printDiv");

                window.print();
                window.location.reload();
            });
        }
        $(document).ready(function() {
            var table = $('#myTab').DataTable({
                columnDefs: [{
                        targets: [4, 5, 6, 7, 8, 9, 10, 11],
                        visible: false,
                        searchable: false
                    } // Cache ces colonnes
                ],
                "language": {
                    "sProcessing": "Traitement en cours...",
                    "sSearch": "Rechercher&nbsp;:",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                    "sInfoEmpty": "Affichage de 0 à 0 sur 0 entrées",
                    "sInfoFiltered": "(filtré à partir de _MAX_ entrées au total)",
                    "sInfoPostFix": "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "oPaginate": {
                        "sPrevious": "Précédent",
                        "sNext": "Suivant"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    }
                },
                paging: true,
                ordering: true
            });

            function filterTableByClass() {
                var selectedClasse = $('#filterClasse').val();
                var selectedSexe = $('#filterSexe').val();
                var selectedStatut = $('#filterStatut').val();
                var selectedSerie = $('#filterSerie').val();
                var selectedEnseign = $('#filterEnseign').val();
                var selectedTypeClas = $('#filterTypeClas').val();
                var selectedCategory = $('#filterCategory').val();
                var selectedPromo = $('#filterPromo').val();
                var selectedCycle = $('#filterCycle').val();

                var hasVisibleRows = false;
                let table = $('#myTab').DataTable();
                let currentPage = table.page();
                table.destroy();
                setTimeout(function() {
                    $('table tbody tr').each(function() {
                        var row = $(this);
                        var rowClasse = row.find('td[data-classe]').data('classe');
                        var rowSexe = row.find('td[data-sexe]').data('sexe');
                        var rowStatut = row.find('td[data-statut]').data('statut');
                        var rowSerie = row.find('td[data-serie]').data('serie');
                        var rowCategory = row.find('td[data-category]').data('category');
                        var rowEnseign = row.find('td[data-typeenseign]').data('typeenseign');
                        var rowTypeclas = row.find('td[data-typeclasse]').data('typeclasse');
                        var rowPromo = row.find('td[data-promo]').data('promo');
                        var rowCycle = row.find('td[data-cycle]').data('cycle');

                        var showRow = true;

                        if (selectedClasse !== "" && selectedClasse !== "Toute la classe" &&
                            rowClasse !== selectedClasse) {
                            showRow = false;
                        }

                        if (selectedSexe !== "" && rowSexe != selectedSexe) {
                            showRow = false;
                        }

                        if (selectedStatut !== "" && rowStatut != selectedStatut) {
                            showRow = false;
                        }

                        if (selectedSerie !== "" && rowSerie != selectedSerie) {
                            showRow = false;
                        }

                        if (selectedCategory !== "" && rowCategory != selectedCategory) {
                            showRow = false;
                        }

                        if (selectedTypeClas !== "" && rowTypeclas != selectedTypeClas) {
                            showRow = false;
                        }

                        if (selectedEnseign !== "" && rowEnseign != selectedEnseign) {
                            showRow = false;
                        }

                        if (selectedPromo !== "" && rowPromo != selectedPromo) {
                            showRow = false;
                        }
                        if (selectedCycle !== "" && rowCycle != selectedCycle) {
                            showRow = false;
                        }

                        if (showRow) {
                            row.show();
                            hasVisibleRows = true;
                        } else {
                            row.hide();
                        }
                        if (!hasVisibleRows) {
                            $('#noResultsMessage').show();
                        } else {
                            $('#noResultsMessage').hide();
                        }
                    });
                }, 50);
            }

            $('#filterClasse, #filterSexe, #filterStatut, #filterSerie, #filterTypeClas, #filterEnseign, #filterCategory, #filterPromo, #filterCycle')
                .on('change', filterTableByClass);
        });
    </script>
@endsection
