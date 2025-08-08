@extends('layouts.master')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreur :</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-9">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6 d-flex align-items-center">
                                <label for="tableSelect1" class="mb-0" style="min-width: 90px;">Classe :</label>
                                <select class="form-control js-example-basic-multiple" name="classe_code" id="tableSelect1"
                                    onchange="displayTable()">
                                    @foreach ($classes as $classe)
                                        <option name="classe_code" value="{{ $classe->CODECLAS }}"
                                            {{ isset($selectedClasseCode) && $classe->CODECLAS === $selectedClasseCode ? 'selected' : '' }}>
                                            {{ $classe->CODECLAS }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <label for="tableSelect2" class="me-2 mb-0" style="min-width: 170px;">
                                    Classes déjà créées :
                                </label>
                                @php
                                    $classeSel = request()->get('classe_selectionne');
                                @endphp

                                {{-- <select class="form-control js-example-basic-multiple" id="tableSelect2" onchange="displayTable2()">
                                    <option value="" selected disabled> -- Classes -- </option>
                                    @foreach ($classesAvecRapport as $classeCR)
                                        <option name="classe_selectionne" value="{{ $classeCR->CODECLAS }}" >
                                            {{ $classeCR->CODECLAS }}  
                                        </option>
                                    @endforeach
                                </select> --}}

                                <select class="form-control js-example-basic-multiple" id="tableSelect2"
                                    onchange="displayTable2()">
                                    <option value="" disabled {{ is_null($classeSel) ? 'selected' : '' }}>-- Classes
                                        --</option>

                                    @foreach ($classesAvecRapport as $classeCR)
                                        <option value="{{ $classeCR->CODECLAS }}"
                                            {{ $classeCR->CODECLAS === $classeSel ? 'selected' : '' }}>
                                            {{ $classeCR->CODECLAS }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="p-3 rounded">
                            <div class="row align-items-center g-2"
                                style="background-color: #f5f5dc; border: 1px solid #ccc;">
                                <div class="col-md-2 d-flex align-items-center">
                                    <label for="seuil" class="me-1 mb-0" style="min-width: 40px;">Seuil:</label>
                                    <input type="number" id="seuil" class="form-control form-control-sm"
                                        value="{{ $config->seuil_Passage ?? 0 }}" readonly
                                        style="width: fit-content; min-width: 40px; padding: 0 4px; margin-left: -0.3rem;">
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label for="classeSup" class="me-1 mb-0" style="min-width: 100px;">Classe sup :</label>
                                    <input type="text" id="classeSup" class="form-control form-control-sm" readonly
                                        style="margin-left: -0.1rem;">
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <label for="minCycle1" class="me-1 mb-0" style="min-width: 100px;"
                                        style="margin-left: -0.3rem;">Min.Cycle 1:</label>
                                    <input type="number" id="minCycle1" class="form-control form-control-sm"
                                        value="{{ $config->Min_Cycle1 ?? 0 }}" readonly
                                        style="width: fit-content; min-width: 40px; padding: 0 4px; margin-left: -1rem;">
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <label for="minCycle2" class="me-1 mb-0" style="min-width: 100px;">Min.Cycle 2:</label>
                                    <input type="number" id="minCycle2" class="form-control form-control-sm"
                                        value="{{ $config->Min_Cycle2 ?? 0 }}" readonly
                                        style="width: fit-content; min-width: 40px; padding: 0 4px; margin-left: -1rem;">
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <div class="ad-flex justify-content-between mb-2">
                                    <button type="button" class="a btn btn-link text-primary btn-outline-primary fw-bold"
                                        data-bs-toggle="modal" data-bs-target="#configClassesModal">
                                        1. Configurer les classes supérieures
                                    </button>

                                    <button type="button" class="a btn btn-link text-danger btn-outline-danger fw-bold"
                                        data-bs-toggle="modal" data-bs-target="#modalConfiguration"
                                        style="margin-left: 5rem;">
                                        2. Configurer décisions
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-1 mb-2 rounded">
                            <div class="mb-3 text-center fw-bold">Statistiques élèves</div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 80px;">Effectif :</label>
                                <input type="number" class="form-control form-control-sm me-1"
                                    value="{{ $effectifTotal }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1"
                                    value="{{ $effectifFilles }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 80px;">Passage :</label>
                                <input type="number" class="form-control form-control-sm me-1"
                                    value="{{ $passantsTotal }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1"
                                    value="{{ $passantesFilles }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span>filles</span>
                            </div>


                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 90px;">Redouble :</label>
                                <input type="number" class="form-control form-control-sm me-1"
                                    value="{{ $redoublesTotal }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1"
                                    value="{{ $redoublesFilles }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 90px;">Exclusion :</label>
                                <input type="number" class="form-control form-control-sm me-1"
                                    value="{{ $exlusesTotal }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1"
                                    value="{{ $exlusFilles }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <label class="fw-bold me-2" style="min-width: 90px;">Abandon :</label>
                                <input type="number" class="form-control form-control-sm me-1"
                                    value="{{ $abandonsTotal }}" readonly
                                    style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm" value="{{ $abandonsFilles }}"
                                    readonly style="width: fit-content; min-width: 40px; padding: 0 4px;">
                                <span>filles</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-n5">
                    <div class="card shadow-sm p-1 rounded" style="background-color: #f8f9fa;">
                        <div class=" gap-5" style="display: flex; flex-wrap: nowrap; justify-content:center;">

                            <form method="POST" action="{{ route('rapportannuel') }}">
                                @csrf
                                <input type="hidden" name="classe_code" id="selectedClasseCode"
                                    value="{{ old('selectedClasseCode') }}">
                                <button class="btn btn-outline-primary btn-sm fw-bold" type="submit">Créer
                                    rapport</button>
                            </form>

                            <button class="btn btn-outline-secondary btn-sm fw-bold " type="button" onclick="printTable()" >Imprimer rapport</button>
                            <button class="btn btn-outline-secondary btn-sm fw-bold " type="button" onclick="exportToExcel()" >Exporter vers Excel</button>
                            <button id="btnToggle" class="btn btn-outline-primary btn-sm fw-bold ">Afficher/ignorer</button>
                            
                            
                            <form method="POST" action="{{ route('classe.delete') }}">
                                @csrf
                                <input type="hidden" name="classe_selectionne" id="classeCR"
                                    value="{{ old('classe_selectionne') }}">
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </form>

                            <form action="{{ route('rapport.liste') }}" method="GET">
                                @csrf
                                <button  class="btn btn-outline-secondary btn-sm fw-bold">Tous les rapports</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-left: 0.5rem">
                <div class="mt-4">
                    <div id="printableArea" class="table-responsive" style="max-height: 100vh; overflow-y: auto;">

                        <table id="rapportTable"class="table table-bordered table-light table-sm text-center mb-0">
                            <thead class="table-secondary sticky-top">
                                <tr>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Mat</th>
                                    <th scope="col">Rang</th>
                                    <th scope="col">Nom et prénoms</th>
                                    <th scope="col">Redou</th>
                                    <th scope="col">Moy1</th>
                                    <th scope="col">Moy2</th>
                                    <th scope="col">Moy An</th>
                                    <th scope="col">Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rapports as $rapport)
                                    <tr>
                                        <td>{{ $rapport->STATUTF }}</td>
                                         <td>{{ $rapport->MATRICULEX }}</td>
                                        <td>{{ $rapport->RANG }}</td>
                                        <td class="text-start">{{ $rapport->NOM }} {{ $rapport->PRENOM }}</td>
                                        <td>{{ $rapport->STATUT == 0 ? 'Nouveau' : 'Redouble' }}</td>
                                        <td>{{ number_format($rapport->MOY1, 2) == 21 || number_format($rapport->MOY1, 2) == -1 ? '**' : number_format($rapport->MOY1, 2) }}
                                        </td>
                                        <td>{{ number_format($rapport->MOY2, 2) == 21 || number_format($rapport->MOY2, 2) == -1 ? '**' : number_format($rapport->MOY2, 2) }}
                                        </td>
                                        <td>{{ number_format($rapport->MOYAN, 2) == 21 || number_format($rapport->MOYAN, 2) == -1 ? '**' : number_format($rapport->MOYAN, 2) }}
                                        </td>
                                        <td>{{ $rapport->OBSERVATION }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-muted">Aucune donnée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <br>
                    <br>
                    <br>
                </div>
                <br><br><br><br>
                <!-- Modale de la configuration des décisions -->
                <div class="modal fade" id="modalConfiguration" tabindex="-1" aria-labelledby="modalConfigLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title" id="modalConfigLabel">Configuration des décisions de fin d’année
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Paramètres -->
                                <form method="POST" action="{{ route('decision.config.store') }}">
                                    @csrf
                                    <div class="row mb-4" style="margin-left: 10rem">
                                        <div class="col-md-3 fw-bold ">
                                            <label>Seuil passage :</label>
                                            <input type="number" id="seuil_Passage" name="seuil_Passage"
                                                class="form-control form-control-sm"
                                                value="{{ old('seuil_Passage', $config->seuil_Passage ?? 10) }}"
                                                step="0.01" style="background-color: whitesmoke">
                                        </div>
                                        <div class="col-md-3 fw-bold">
                                            <label>Minimum Cycle 1 :</label>
                                            <input type="number" id="min_Cycle1" name="min_Cycle1"
                                                class="form-control form-control-sm"
                                                value="{{ old('min_Cycle1', $config->Min_Cycle1 ?? 6.5) }}"
                                                step="0.01" style="background-color: whitesmoke">
                                        </div>
                                        <div class="col-md-3 fw-bold">
                                            <label>Minimum Cycle 2 :</label>
                                            <input type="number" id="min_Cycle2" name="min_Cycle2"
                                                class="form-control form-control-sm"
                                                value="{{ old('min_Cycle2', $config->Min_Cycle2 ?? 7.5) }}"
                                                step="0.01" style="background-color: whitesmoke">
                                        </div>
                                    </div>
                                    <div class="row mb-4" style="margin-left: 10rem">
                                        <div class="col-md-3 mt-2 fw-bold">
                                            <label>Tableau d'honneur :</label>
                                            <input type="number" name="Seuil_tableau_Honneur"
                                                class="form-control form-control-sm"
                                                value="{{ old('Seuil_tableau_Honneur', $config->Seuil_tableau_Honneur ?? 12) }}"
                                                step="0.01" style="background-color: whitesmoke">
                                        </div>
                                        <div class="col-md-3 mt-2 fw-bold">
                                            <label>Encouragements :</label>
                                            <input type="number" name="Seuil_Encouragements"
                                                class="form-control form-control-sm"
                                                value="{{ old('Seuil_Encouragements', $config->Seuil_Encouragements ?? 14) }}"
                                                step="0.01" style="background-color: whitesmoke">
                                        </div>
                                        <div class="col-md-3 fw-bold">
                                            <label>Félicitations :</label>
                                            <input type="number" name="Seuil_Félicitations"
                                                class="form-control form-control-sm"
                                                value="{{ old('Seuil_Félicitations', $config->Seuil_Felicitations ?? 16) }}"
                                                step="0.01" style="background-color: whitesmoke">

                                        </div>
                                    </div>

                                    <!-- TABLEAU -->
                                    <table id="printableContent"
                                        class="table table-bordered table-sm text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Promotion</th>
                                                <th>Statut</th>
                                                <th>Moyenne</th>
                                                <th>Passe</th>
                                                <th>Redouble</th>
                                                <th>Exclu</th>
                                                <th style="display: none">Cycle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $rowIndex = 0;
                                                $lastCodePromo = null;
                                                $seenPromoCodes = []; // tableau pour mémoriser les CODEPROMO déjà traités
                                                $statuts = [1, 0, 0, 1];
                                            @endphp
                                            @foreach ($promo as $promos)
                                                @php
                                                    $codePromo = $promos->CODEPROMO;

                                                    // Sauter si CODEPROMO est déjà vu
                                                    if (in_array($codePromo, $seenPromoCodes)) {
                                                        continue;
                                                    }

                                                    // Sauter si CODEPROMO est une valeur purement numérique courte (1, 00, 11...)
                                                    if (is_numeric($codePromo) && strlen($codePromo) <= 2) {
                                                        continue;
                                                    }

                                                    //  On ajoute le code à la liste des déjà vus
                                                    $seenPromoCodes[] = $codePromo;

                                                    //Récupération des valeurs de mincycle1 et minCycle2 pour préremplir le tableau

                                                    $seuilPassage = old('seuil_Passage', $config->seuil_Passage ?? 10);
                                                    $minCycle1 = old('min_Cycle1', $config->Min_Cycle1 ?? 6.5);
                                                    $minCycle2 = old('min_Cycle2', $config->Min_Cycle2 ?? 7.5);
                                                @endphp

                                                @for ($i = 0; $i < 4; $i++)
                                                    @php
                                                        $cycle = $promos->CYCLE;
                                                        $minCycle = $cycle == 1 ? $minCycle1 : $minCycle2;

                                                        switch ($i) {
                                                            case 0:
                                                                $moyenne = ">= <span class='seuil-passage-placeholder'>$seuilPassage</span>";
                                                                break;
                                                            case 1:
                                                                $moyenne = "de [0 à <span class='min-cycle-placeholder' data-cycle='$cycle'>$minCycle</span>[";
                                                                break;
                                                            case 2:
                                                                $moyenne = "de [<span class='min-cycle-placeholder' data-cycle='$cycle'>$minCycle</span> à <span class='seuil-passage-placeholder'>$seuilPassage</span>[";
                                                                break;
                                                            case 3:
                                                                $moyenne = "de [0 à <span class='seuil-passage-placeholder'>$seuilPassage</span>[";
                                                                break;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $codePromo }}
                                                            <input type="hidden" name="promotion_{{ $rowIndex }}"
                                                                value="{{ $codePromo }}">
                                                        </td>
                                                        @php
                                                            $valStatut = $statuts[$i];

                                                            if ($i === 0) {
                                                                $labelStatut = 'Nouveau & Redoublant';
                                                            } else {
                                                                $labelStatut =
                                                                    $valStatut == 1 ? 'Redoublant' : 'Nouveau';
                                                            }
                                                        @endphp

                                                        <td>
                                                            {{ $labelStatut }}
                                                            <input type="hidden" name="statut_{{ $rowIndex }}"
                                                                value="{{ $valStatut }}">
                                                        </td>

                                                        <td>{!! $moyenne !!}</td>
                                                        <td>
                                                            <input type="radio" name="decision_{{ $rowIndex }}"
                                                                value="P" {{ $i === 0 ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="decision_{{ $rowIndex }}"
                                                                value="R" {{ $i === 2 ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="decision_{{ $rowIndex }}"
                                                                value="X"
                                                                {{ in_array($i, [1, 3]) ? 'checked' : '' }}>
                                                        </td>
                                                        <td style="display: none">{{ $cycle }}
                                                            <input type="hidden" name="cycle_{{ $rowIndex }}"
                                                                value="{{ $cycle }}">
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $rowIndex++;
                                                    @endphp
                                                @endfor
                                            @endforeach
                                        </tbody>
                                        <input type="hidden" name="row_count" value="{{ $rowIndex }}">
                                    </table>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 mt-n5">
                        <div class="card shadow-sm p-3 rounded" style="background-color: #f8f9fa; margin-left: 1rem">
                            <div class="gap-2" style="display: flex; flex-wrap: nowrap; margin-left: 2.5rem">

                                <form action="{{ route('rapport.passage') }}" method="GET" class="me-1">
                                    <button type="submit" class="btn btn-outline-primary btn-sm fw-bold">Liste générale
                                        passage</button>
                                </form>

                                <form action="{{ route('rapport.redoublement') }}" method="POST" class="me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning btn-sm fw-bold">Liste générale
                                        redoublement</button>
                                </form>

                                <form action="{{ route('rapport.exclusion') }}" method="POST" class="me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">Liste générale
                                        exclusion</button>
                                </form>

                                <form action="{{ route('rapport.abandon') }}" method="POST" class="me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Liste générale
                                        abandon</button>
                                </form>
                            </div>
                            <br><br> <br>
                        </div>
                    </div>
                </div>
            </div>


            {{-- debut div cacher pour le bouton imprimer rapport --}}
            <div id="rapportData" style="display: none;">
                <div  style="display: flex; align-items: center; justify-content: space-between;">
                    <div class="screen-only" style="border: 1px solid #333; padding: 10px; background-color: rgb(185,185,185); border-radius: 8px;">
                        @foreach ($params2 as $param)
                            <strong>{{ $param->NOMETAB }}</strong><br>
                            {{ $param->ADRESSE }}<br>
                        @endforeach
                    </div>
                    <div style="text-align: center; flex: 1;">
                        <h3 style="margin-bottom: 0;">RAPPORT DE FIN D'ANNEE</h3>
                        <div>{{ $anneeScolaire }}</div>
                    </div>
                    <div class="screen-only">
                        <img src="data:image/png;base64,{{ base64_encode($params2[0]->logoimage ?? '') }}" alt="Logo" width="60">
                    </div>
                </div>

                <p style="margin-top: 10px;"><strong>DEPARTEMENT / VILLE :</strong> LITTORAL / COTONOU</p>
                <p><strong>CLASSE :</strong> {{ $selectedClasseCode ?? '' }} </p>
                <p><strong>EFFECTIF :</strong> {{ $effectifTotal }} DONT {{ $effectifFilles }} FILLES</p>
                <p><strong>PASSAGE :</strong> {{ $passantsTotal }} DONT {{ $passantesFilles }} FILLES</p>
                <p><strong>REDOUBLEMENT :</strong> {{ $redoublesTotal }} DONT {{ $redoublesFilles }} FILLES</p>
                <p><strong>EXCLUSION :</strong> {{ $exlusesTotal }} DONT {{ $exlusFilles }} FILLES</p>
                <p><strong>ABANDON :</strong> {{ $abandonsTotal }} DONT {{ $abandonsFilles }} FILLES</p>

                @php
                    $rapports = collect($rapports);
                    $groupes = [
                        'P' => 'A/ PASSAGE',
                        'R' => 'B/ REDOUBLEMENT',
                        'X' => 'C/ EXCLUSION',
                        'Z' => 'D/ ABANDON',
                    ];
                @endphp


                @foreach ($groupes as $code => $titre)
                    @php
                        $groupe = $rapports->where('STATUTF', $code);
                    @endphp

                    @if ($groupe->count())
                        <h4 style="margin-top: 20px;">{{ $titre }}</h4>
                        @if ($code === 'P')
                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT PROPOSES
                                AU PASSAGE EN CLASSE DE : <span id="libSup" style="font-weight: bold;"></span> </p>
                        @elseif ($code === 'R')
                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT REDOUBLENT LA
                                CLASSE DE : {{ $selectedClasseCode ?? '' }} </p>
                        @elseif ($code === 'X')
                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT PROPOSES
                                A L'EXCLUSION DE LA CLASSE DE : {{ $selectedClasseCode ?? '' }} </p>
                        @else
                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT ABANDON
                                DE LA CLASSE DE : {{ $selectedClasseCode ?? '' }} </p>
                        @endif

                        <table border="1" cellspacing="0" cellpadding="4"
                            style="width: 100%; font-size: 12px; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th>Ordre Mérite</th>
                                    <th>Mat</th>
                                    <th>Nom et Prénoms</th>
                                    <th>Sexe</th>
                                    <th>Red</th>
                                    <th>Moy1</th>
                                    <th>Moy2</th>
                                    <th>Ann</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupe->sortBy('RANG') as $rapport)
                                    <tr>
                                        <td>{{ $rapport->RANG }}</td>
                                        <td class="mat">{{ $rapport->MATRICULEX }}</td>
                                        <td>{{ $rapport->NOM }} {{ $rapport->PRENOM }}</td>
                                        <td>{{ $rapport->SEXE == 1 ? 'M' : 'F' }}</td>
                                        <td>{{ $rapport->STATUT == 0 ? 'N' : 'R' }}</td>
                                        <td>{{ in_array(number_format($rapport->MOY1, 2), [21.0, -1.0]) ? '**' : number_format($rapport->MOY1, 2) }}
                                        </td>
                                        <td>{{ in_array(number_format($rapport->MOY2, 2), [21.0, -1.0]) ? '**' : number_format($rapport->MOY2, 2) }}
                                        </td>
                                        <td>{{ in_array(number_format($rapport->MOYAN, 2), [21.0, -1.0]) ? '**' : number_format($rapport->MOYAN, 2) }}
                                        </td>
                                        <td>{{ $rapport->OBSERVATION }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
                <br>
                <div class="droite">
                    <p style="text-align: end">Fait à {{ $param->VILLE }} le,
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                    <p style="text-align: end; margin-right: 6rem;">{{ $param->TITRE }},</p> <br>
                    <p style="text-align: end; margin-right: 3rem;">{{ $param->NOMDIRECT }}</p>

                </div>
            </div>
            {{-- fin div cacher pour le bouton imprimer rapport --}}



            {{-- Modal de configuration de classe supérieur --}}
            <div class="modal fade" id="configClassesModal" tabindex="-1" aria-labelledby="configClassesModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('config.promotions.update') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="configClassesModalLabel">Configuration Classes Supérieur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive" style="max-height:60vh; overflow-y:auto;">
                                    <table class="table table-sm table-bordered text-center mb-0">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Code</th>
                                                <th>Libellé promotion</th>
                                                <th>Libellé classe supérieure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($promotions as $index => $promo)
                                                @php
                                                    // on cherche la configuration existante
                                                    $cfg = $configs->get($promo->CODEPROMO);
                                                @endphp
                                                <tr>
                                                    {{-- Code (readonly) --}}
                                                    <td style="vertical-align: middle;">
                                                        <input type="text" name="codeClas[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $promo->CODEPROMO }}" readonly>
                                                    </td>

                                                    {{-- Libellé promotion --}}
                                                    <td>
                                                        <input type="text" name="libelle_promotion[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $promo->LIBELPROMO }}" readonly>
                                                    </td>

                                                    {{-- Libellé classe supérieure --}}
                                                    <td>
                                                        <input type="text" name="libelle_classe_sup[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ old("libelle_classe_sup.$index", optional($cfg)->libelle_classe_sup) }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- script pour la configuration des décisions -->

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const seuilInput = document.getElementById('seuil_Passage');
                const cycle1Input = document.getElementById('min_Cycle1');
                const cycle2Input = document.getElementById('min_Cycle2');

                const seuilSpans = document.querySelectorAll('.seuil-passage-placeholder');
                const cycleSpans = document.querySelectorAll('.min-cycle-placeholder');

                function updateSeuil() {
                    let seuil = seuilInput.value.trim() || '0';
                    seuilSpans.forEach(span => span.textContent = seuil);
                }

                function updateCycleMin() {
                    let min1 = cycle1Input.value.trim() || '0';
                    let min2 = cycle2Input.value.trim() || '0';

                    cycleSpans.forEach(span => {
                        let cycle = span.getAttribute('data-cycle');
                        span.textContent = (cycle === '1') ? min1 : min2;
                    });
                }

                seuilInput.addEventListener('input', updateSeuil);
                cycle1Input.addEventListener('input', updateCycleMin);
                cycle2Input.addEventListener('input', updateCycleMin);

            });
        </script>

        <script>
            function printDiv(divId) {
                // Sauvegarde du contenu complet
                const originalContents = document.body.innerHTML;
                // Contenu à imprimer
                const printContents = document.getElementById(divId).innerHTML;
                // Remplace tout le body par la zone à imprimer
                document.body.innerHTML = printContents;
                // Lance la boîte d'impression
                window.print();
                // Restaure le contenu original de la page
                document.body.innerHTML = originalContents;
                // Recharge le script et le CSS
                window.location.reload();
            }
        </script>

        <!-- script pour imprimer rapport par classe -->
        <script>
            function printTable() {

                const select = document.getElementById('classeSup');
                const code = select.value; // ex: "6EM1", "TleA2-1"
          
                document.getElementById('libSup').textContent  = code;

                    const content = document.getElementById('rapportData');
                    if (!content) {
                        alert('Rapport non généré. Veuillez d\'abord cliquer sur "Créer rapport".');
                        return;
                    }

                    const win = window.open('', '_blank');
                    win.document.write(`
                        <html>
                        <head>
                            <title>Impression du rapport</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { border: 1px solid #000; padding: 5px; text-align: center; }
                                th { background-color: #f2f2f2; }
                                h4 { margin-top: 30px; margin-bottom: 10px; }
                                p { margin: 2px 0; }
                            </style>
                        </head>
                        <body>${content.innerHTML}</body>
                        </html>
                    `);
                    win.document.close();
                    win.focus();

                    win.onload = () => {
                        win.print();
                        win.close();
                    };
            }       

            const classes = @json($selectedClasseCode);

            function exportToExcel() {
        

                const contentElement = document.getElementById('rapportData');
              
                if (!contentElement) {
                    alert('Aucun rapport à exporter. Veuillez d\'abord créer les rapports.');
                    return;
                }

               
                // Cloner le contenu pour ne pas modifier l'original
                const clone = contentElement.cloneNode(true);

                // Supprimer les éléments avec la classe .no-print ou .screen-only
                const unwantedElements = clone.querySelectorAll('.screen-only');
                unwantedElements.forEach(el => el.remove());

                // style Excel plus propre
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
                            font-size: 20px;
                            line-height: 1.5rem;
                        }
                        th {
                            font-weight: bold;
                        }
                        td {
                            text-align: center;
                        }
                        td.mat {
                            mso-number-format:"0";
                        }
                    </style>
                `;

                // Construire le HTML complet pour Excel
                const html = `
                    <html xmlns:o="urn:schemas-microsoft-com:office:office"
                        xmlns:x="urn:schemas-microsoft-com:office:excel"
                        xmlns="http://www.w3.org/TR/REC-html40">
                    <head>
                        <meta charset="UTF-8">
                        ${style}
                    </head>
                    <body>
                       
                        ${clone.innerHTML}
                    </body>
                    </html>
                `;

                const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `rapport_${classes}.xls`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        </script>

    </div>

    {{-- SCRIPT pour synchroniser le select et le hidden --}}

    <script>
        window.setTimeout(function() {
            const alertNode = document.querySelector('.alert');
            if (alertNode) {
                // utilise l’API JS de Bootstrap 5
                const bsAlert = new bootstrap.Alert(alertNode);
                bsAlert.close();
            }
        }, 5000);

        // Crée un objet JS où chaque clé est le code de classe
        // et la valeur est le libellé de la classe supérieure
        const supMap = @json(
            $configs->mapWithKeys(function ($cfg) {
                return [$cfg->codeClas => $cfg->libelle_classe_sup];
            }));

        function displayTable() {
            const select = document.getElementById('tableSelect1');
            const code = select.value; // ex: "6EM1", "TleA2-1"
            const firstChr = code.charAt(0); // "6" ou "T", etc.
            document.getElementById('selectedClasseCode').value = code;
            let libSup = ''; // valeur par défaut

            // Parcours des clés de la map
            for (const supKey of Object.keys(supMap)) {
                if (supKey.charAt(0) === firstChr) {
                    libSup = supMap[supKey];
                    break; // on sort dès qu’on a trouvé le 1er match
                }
            }

            // Injecte dans l’input
            document.getElementById('classeSup').value = libSup;
        }

        function displayTable2() {
            const select = document.getElementById('tableSelect2');
            const classe = select.value;
            document.getElementById('classeCR').value = classe;

            const selectedClass = document.getElementById('tableSelect2').value;
            if (!selectedClass) return;

            // Redirige vers l'URL avec le code classe sélectionné en paramètre
            const url = new URL(window.location.href);
            url.searchParams.set('classe_selectionne', selectedClass);
            // selectedClass.value = document.getElementById('tableSelect2').value;
            window.location.href = url.toString();

        }


        document.getElementById('btnToggle')
            .addEventListener('click', function() {
                const tbl = document.getElementById('rapportTable');
                tbl.style.display = (tbl.style.display === 'none') ? '' : 'none';
            });

        // 3. Liaison de l’événement
        document
            .getElementById('tableSelect1')
            .addEventListener('change', displayTable);

        // 4. Optionnel : pré-remplir au chargement de la page/modal
        document.addEventListener('DOMContentLoaded', displayTable);

        // Appel au chargement de la page
        //   window.addEventListener('DOMContentLoaded', displayTable2);
    </script>

    <script>
        // met à jour le champ caché
        function updateHiddenField() {
            const select = document.getElementById('tableSelect2');
            document.getElementById('classeCR').value = select.value;
        }

        // redirige vers la même page avec le paramètre
        function onClasseChange() {
            updateHiddenField();

            const selectedClass = document.getElementById('tableSelect2').value;
            if (!selectedClass) return;

            const url = new URL(window.location.href);
            url.searchParams.set('classe_selectionne', selectedClass);
            window.location.href = url.toString();
        }

        // liaison sur l'événement change
        document.getElementById('tableSelect2')
            .addEventListener('change', onClasseChange);

        // à l'ouverture de la page, on initialise juste le hidden (pas de redirection)
        document.addEventListener('DOMContentLoaded', updateHiddenField);
    </script>


    <style>
        .a:hover {
            color: #fff !important;
            text-decoration: none;
        }

        .a {
            text-decoration: none;
        }

        /* Assure que le conteneur est positionné pour le sticky */
        #configClassesModal .table-responsive {
            position: relative;
        }


        /* Rend chaque <th> collant en haut du conteneur */
        #configClassesModal .table-responsive thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            /* pour qu'ils restent au-dessus des lignes */
            background-color: #f8f9fa;
            /* même couleur que .table-secondary */
        }

        thead tr {
            position: sticky;
            top: 0;
            z-index: 5;
            /* pour qu'ils restent au-dessus des lignes */
            background-color: #f8f9fa;
            /* même couleur que .table-secondary */
        }
    </style>

    <style>
        @media print {

            /* Imprime les couleurs de fond et textes comme à l’écran */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Masque tout sauf la zone à imprimer */
            body * {
                visibility: hidden;
            }

            #printableArea,
            #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            /* Corps de page sans marges externes (optionnel, selon besoin) */
            @page {
                margin: 1cm;
            }

            /* Tableau plein largeur, bordures nettes */
            #printableArea table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-bottom: 0.5em;
            }

            #printableArea th,
            #printableArea td {
                border: 1px solid #333 !important;
                padding: 0.6em !important;
                font-size: 11pt !important;
            }

            /* En‑tête contrasté */
            #printableArea thead th {
                background-color: #2c3e50 !important;
                color: #ecf0f1 !important;
                text-transform: uppercase;
                font-size: 12pt !important;
            }

            /* Lignes zébrées pour le print */
            #printableArea tbody tr:nth-of-type(odd) {
                background-color: #f2f2f2 !important;
            }

            /* Hover désactivé (inutile à l’impression) */
            #printableArea tbody tr:hover {
                background: none !important;
            }

            /* Empêche les coupures de lignes à l’intérieur d’une ligne du tableau */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection
