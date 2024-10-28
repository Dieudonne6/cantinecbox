@extends('layouts.master')
@section('content')
    <div class="main-panel-10">

        <div class="container">

            <div class="row">
                <div class="col-5 md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <div class="row">
                                        <div class="col">
                                            
                                            <select class="js-example-basic-multiple w-100 select2-hidden-accessible"
                                                id="tableSelect1" onchange="displayTable('tableSelect1')" tabindex="-1"
                                                aria-hidden="true"> 
                                                <option value="">Choisir un groupe</option>
                                               @foreach($gclasses as $gclasse)
                                                    <option value="{{ $gclasse->LibelleGroupe }}">{{ $gclasse->LibelleGroupe }}</option>
                                                @endforeach
                                            </select>
                                            <select class="js-example-basic-multiple w-100 select2-hidden-accessible" id="periodSelect" onchange="updateCheckbox()">
                                                <option value="">Période</option>
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
                                        <div class="col">
                                            <input type="number" id="champ1" name="champ1" style="width: 30%; padding: 8px; box-sizing: border-box;" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-5 md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div class="col">
                                    <select class="js-example-basic-multiple w-100 select2-hidden-accessible"
                                        id="tableSelect1" onchange="displayTable('tableSelect1')" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="">Classe</option>
                                        @foreach($classes as $classe)
                                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                                @endforeach
                                    </select>
                                    <select class="js-example-basic-multiple w-100 select2-hidden-accessible"
                                        id="tableSelect2" onchange="displayTable('tableSelect2')" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="">Matières</option>
                                        @foreach($matieres as $matiere)
                                                    <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
                                                @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number" id="champ2" name="champ2"
                                        style="width: 30%; padding: 8px; box-sizing: border-box;" placeholder=""  oninput="updateInterroVisibility()" default-value=10>
                                </div>
                            </div>
                            <div class="checkbox-container">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="option1" name="option1">
                                    Option 1
                                </label>
                                <button class="btn btn-modifier"></button>
                                <button class="btn btn-enregistrer"></button>
                                <label class="checkbox-label">
                                    <input type="checkbox" id="option2" name="option2">
                                    Option 2
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-2 md-3 grid-margin stretch-card">
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
                </div>
            </div>


            <div class="col-12 md-3 grid-margin">

                <div class="stretch-card">
                    <div class="row">
                        <div class="col-6 md-3">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <div class="checkbox-container">
                                                <label class="checkbox-label interro-checkbox" data-interro="5">
                                                    <input type="checkbox" id="optionINT5" name="optionGroup1[]"
                                                        value="INT5">
                                                    INT5
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="6">
                                                    <input type="checkbox" id="optionINT6" name="optionGroup1[]"
                                                        value="INT6">
                                                    INT6
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="7">
                                                    <input type="checkbox" id="optionINT7" name="optionGroup1[]"
                                                        value="INT7">
                                                    INT7
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="8">
                                                    <input type="checkbox" id="optionINT8" name="optionGroup1[]"
                                                        value="INT8">
                                                    INT8
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="9">
                                                    <input type="checkbox" id="optionINT9" name="optionGroup1[]"
                                                        value="INT9">
                                                    INT9
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="10">
                                                    <input type="checkbox" id="optionINT10" name="optionGroup1[]"
                                                        value="INT10">
                                                    INT10
                                                </label>
                                            </div>

                                            <div class="checkbox-container">
                                                <label class="checkbox-label interro-checkbox" data-interro="1">
                                                    <input type="checkbox" id="optionINT1" name="optionGroup2[]"
                                                        value="INT1">
                                                    INT1
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="2">
                                                    <input type="checkbox" id="optionINT2" name="optionGroup2[]"
                                                        value="INT2">
                                                    INT2
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="3">
                                                    <input type="checkbox" id="optionINT3" name="optionGroup2[]"
                                                        value="INT3">
                                                    INT3
                                                </label>
                                                <label class="checkbox-label interro-checkbox" data-interro="4">
                                                    <input type="checkbox" id="optionINT4" name="optionGroup2[]"
                                                        value="INT4">
                                                    INT4
                                                </label>
                                                <label class="checkbox-label">
                                                    <input type="checkbox" id="optionDEV1" name="optionGroup2[]"
                                                        value="DEV1">
                                                    DEV1
                                                </label>
                                                <label class="checkbox-label">
                                                    <input type="checkbox" id="optionDEV2" name="optionGroup2[]"
                                                        value="DEV2">
                                                    DEV2
                                                </label>
                                                <label class="checkbox-label">
                                                    <input type="checkbox" id="optionDEV3" name="optionGroup2[]"
                                                        value="DEV3">
                                                    DEV3
                                                </label>
                                                <label class="checkbox-label">
                                                    <input type="checkbox" id="optionTESTCOMPO" name="optionGroup2[]"
                                                        value="TEST(COMPO)">
                                                    TEST(COMPO)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <div class="table-responsive mb-4">
                                                <table id="myTab" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Ordre</th>
                                                            <th>Matricule</th>
                                                            <th>MATRICULE</th>
                                                            <th>Nom et Prenoms</th>
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
                                                        <!-- Les lignes seront ajoutées dynamiquement ici -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-2 md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-primary btn-rounded btn-icon">
                                                        <i class="typcn typcn-home-outline"></i>
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
                                                        <i class="typcn typcn-heart-outline text-danger"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-rounded btn-icon">
                                                        <i class="typcn typcn-notes text-dark"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-rounded btn-icon">
                                                        <i class="typcn typcn-star text-primary"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-rounded btn-icon">
                                                        <i class="typcn typcn-wi-fi text-info"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mb-2 d-flex justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-rounded btn-icon">
                                                        <i class="typcn typcn-chart-line text-success"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div class="table-responsive mb-4">
                                            <table id="myTab" class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td>COMM</td>
                                                        <td>Ma</td>
                                                        <td>1/0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
