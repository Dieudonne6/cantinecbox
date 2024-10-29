@extends('layouts.master')
@section('content')
    <div class="main-panel-10">

        <div class="container">

            <div class="row">
                <div class="col-6 md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                <div class="row w-100">
                                    <!-- Select pour le groupe -->
                                    <div class="col-md-8 mb-3">
                                        <select class="form-select select2 w-100" id="tableSelect1"
                                            onchange="displayTable('tableSelect1')" aria-label="Choisir un groupe">
                                            <option value="" selected>Choisir un groupe</option>
                                            @foreach ($gclasses as $gclasse)
                                                <option value="{{ $gclasse->LibelleGroupe }}">{{ $gclasse->LibelleGroupe }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Select pour la période -->
                                    <div class="col-md-8 mb-3">
                                        <select class="form-select select2 w-100 mt-2" id="periodSelect"
                                            onchange="updateCheckbox()" aria-label="Choisir une période">
                                            <option value="" selected>Période</option>
                                            <option value="1">1ère Période</option>
                                            <option value="2">2ème Période</option>
                                            <option value="3">3ème Période</option>
                                            <option value="4">4ème Période</option>
                                            <option value="5">5ème Période</option>
                                            <option value="6">6ème Période</option>
                                            <option value="7">7ème Période</option>
                                            <option value="8">8ème Période</option>
                                            <option value="9">9ème Période</option>
                                        </select>
                                    </div>

                                    <!-- Champ de nombre -->
                                    <div class="col-md-4">
                                        <input type="number" id="champ1" name="champ1" class="form-control"
                                            placeholder="Valeur" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                <div class="col">
                                    <div class="col-md-8 mb-3">
                                        <!-- Select pour les classes -->
                                        <select class="form-select select2 w-100" id="tableSelect1"
                                            onchange="displayTable('tableSelect1')">
                                            <option value="">Classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <!-- Select pour les matières -->
                                        <select class="form-select select2 w-100 mt-2" id="tableSelect2"
                                            onchange="updateCodeMat()">
                                            <option value="">Matières</option>
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Champ de nombre -->
                                <div class="col-md-4 mb-3">
                                    <input type="number" id="champ2" name="champ2" class="form-control"
                                        placeholder="Valeur" readonly>
                                </div>
                            </div>


                            {{-- <div class="checkbox-container">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="option2" name="option2" onclick="toggleINTCheckboxes()">
                                    Afficher 10 Colonnes pour INT
                                </label>
                            </div> --}}
                        </div>
                    </div>
                </div>


                {{-- <div class="col-2 md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div class="col" style="text-align: center">
                                    <button class="btn btn-duplicate" onclick="handleDuplicate()">Doublon</button>
                                    <button class="btn btn-help" onclick="handleHelp()">Aide</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>


            <div class="col-12 md-3 grid-margin">

                <div class="row">
                    <div class="col-9">
                        <div class="row">
                            <!-- Card pour afficher les cases à cocher -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div class="checkbox-container">
                                            <div id="intCheckboxes">
                                                <!-- Cases à cocher pour contrôler l'affichage des colonnes -->
                                                @for ($i = 5; $i <= 10; $i++)
                                                    <label class="checkbox-label interro-checkbox"
                                                        data-interro="{{ $i }}">
                                                        <input type="checkbox" id="optionINT{{ $i }}"
                                                            name="optionGroup1[]" value="INT{{ $i }}"
                                                            onchange="toggleColumn({{ $i }})">
                                                        INT{{ $i }}
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card pour afficher le tableau -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div class="table-responsive mb-4">
                                            <table id="myTab" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>MATRICULE</th>
                                                        <th>Nom et Prénoms</th>
                                                        <th class="interro-column" data-interro="1">Int1</th>
                                                        <th class="interro-column" data-interro="2">Int2</th>
                                                        <th class="interro-column" data-interro="3">Int3</th>
                                                        <th class="interro-column" data-interro="4">Int4</th>
                                                        <th class="interro-column" data-interro="5">Int5</th>
                                                        <th class="interro-column" data-interro="6">Int6</th>
                                                        <th class="interro-column" data-interro="7">Int7</th>
                                                        <th class="interro-column" data-interro="8">Int8</th>
                                                        <th class="interro-column" data-interro="9">Int9</th>
                                                        <th class="interro-column" data-interro="10">Int10</th>
                                                        <th>M.int</th>
                                                        <th>Dev1</th>
                                                        <th>Dev2</th>
                                                        <th>Dev3</th>
                                                        <th>Moy</th>
                                                        <th>Test</th>
                                                        <th>Ms</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($notes as $note)
                                                        <tr>
                                                            <td>{{ $note->eleve->MATRICULE ?? '' }}</td>
                                                            <td>{{ $note->eleve->NOM ?? '' }}<br>{{ $note->eleve->PRENOM ?? '' }}
                                                            </td>
                                                            @for ($i = 1; $i <= 10; $i++)
                                                                <td class="interro-column"
                                                                    data-interro="{{ $i }}">
                                                                    <input type="text" name="INT{{ $i }}"
                                                                        value="{{ $note['INT' . $i] ?? '' }}"
                                                                        class="form-control form-control-sm">
                                                                </td>
                                                            @endfor
                                                            <td><input type="text" name="MI"
                                                                    value="{{ $note->MI ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="DEV1"
                                                                    value="{{ $note->DEV1 ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="DEV2"
                                                                    value="{{ $note->DEV2 ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="DEV3"
                                                                    value="{{ $note->DEV3 ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="MS"
                                                                    value="{{ $note->MS ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="TEST"
                                                                    value="{{ $note->TEST ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                            <td><input type="text" name="MS1"
                                                                    value="{{ $note->MS1 ?? '' }}"
                                                                    class="form-control form-control-sm"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleColumn(interroNumber) {
                            // Récupère l'état de la case à cocher
                            const checkbox = document.getElementById("optionINT" + interroNumber);
                            const isChecked = checkbox.checked;

                            // Récupère toutes les cellules de la colonne correspondante
                            const columns = document.querySelectorAll(`.interro-column[data-interro="${interroNumber}"]`);

                            // Affiche ou masque la colonne selon l'état de la case à cocher
                            columns.forEach(column => {
                                column.style.display = isChecked ? '' : 'none';
                            });
                        }

                        // Initialisation : Masque les colonnes Int5 à Int10 au chargement de la page
                        document.addEventListener("DOMContentLoaded", () => {
                            for (let i = 5; i <= 10; i++) {
                                toggleColumn(i);
                            }
                        });
                    </script>

                    <style>
                        /* Améliore l'affichage des champs de saisie */
                        .table thead th,
                        .table tbody td {
                            text-align: center;
                            vertical-align: middle;
                        }

                        .form-control-sm {
                            width: 100%;
                            padding: 5px;
                            text-align: center;
                            border: 1px solid #ddd;
                        }

                        /* Ajustement des marges dans les cellules */
                        td {
                            padding: 4px;
                        }
                    </style>

                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-primary btn-rounded btn-icon">
                                                    <i class="typcn typcn-home-outline">Enrégistrer</i>
                                                </button>
                                            </div>
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-dark btn-rounded btn-icon">
                                                    <i class="typcn typcn-wi-fi"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-danger btn-rounded btn-icon">
                                                    <i class="typcn typcn-mail"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-info btn-rounded btn-icon">
                                                    <i class="typcn typcn-star"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-success btn-rounded btn-icon">
                                                    <i class="typcn typcn-location-outline"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-rounded btn-icon">
                                                    <i class="typcn typcn-star text-primary"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br><br><br>

    <script>
        function updateCodeMat() {
            // Récupère la valeur sélectionnée dans le select de matières
            var selectedMatiere = document.getElementById("tableSelect2").value;
            // Met à jour le champ d'input avec le CODEMAT sélectionné
            document.getElementById("champ2").value = selectedMatiere;
        }

        function updateInterroVisibility() {
            const value = parseInt(document.getElementById('champ2').value);
            document.querySelectorAll('.interro-checkbox').forEach(checkbox => {
                const interroNumber = parseInt(checkbox.dataset.interro);
                checkbox.style.display = interroNumber <= value ? 'inline-block' : 'none';
            });

            document.querySelectorAll('.interro-column').forEach(column => {
                const interroNumber = parseInt(column.dataset.interro);
                column.style.display = interroNumber <= value ? '' : 'none';
            });
        }

        function updateCheckbox() {
            const periodSelect = document.getElementById('periodSelect');
            const champ1 = document.getElementById('champ1');

            // Met à jour la valeur de champ1 avec la valeur sélectionnée dans periodSelect
            if (periodSelect.value) {
                champ1.value = periodSelect.value;
            } else {
                champ1.value = '';
            }
        }
    </script>
@endsection
