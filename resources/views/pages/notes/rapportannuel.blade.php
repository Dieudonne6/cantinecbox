@extends('layouts.master')
@section('content')

@if(session('success'))
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
                            <label for="tableSelect1" class="me-2 mb-0" style="min-width: 150px;">Classe :</label>
                            <select class="form-control js-example-basic-multiple" id="tableSelect1" onchange="displayTable()">
                                <option value="">Choisis une classe</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <label for="tableSelect2" class="me-2 mb-0" style="min-width: 170px;">Classes déjà créées :</label>
                            <select class="form-control js-example-basic-multiple" id="tableSelect2" readonly>
                                <option value=""> Classes créées</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-3 rounded" style="background-color: #f5f5dc; border: 1px solid #ccc;">
                        <div class="row align-items-center g-2">
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="seuil" class="me-2 mb-0" style="min-width: 100px;">Seuil : </label>
                                <input type="number" id="seuil" class="form-control form-control-sm" value="{{ $config->seuil_Passage ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="classeSup" class="me-2 mb-0" style="min-width: 100px;">Classe sup. :</label>
                                <input type="text" id="classeSup" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="minCycle1" class="me-2 mb-0" style="min-width: 100px;">Min. Cycle 1 :</label>                               
                                <input type="number" id="minCycle1" class="form-control form-control-sm" value="{{ $config->min_Cycle1 ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="minCycle2" class="me-2 mb-0" style="min-width: 100px;">Min. Cycle 2 :</label>                               
                                <input type="number" id="minCycle2" class="form-control form-control-sm" value="{{ $config->min_Cycle2 ?? 0 }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border p-1 mb-2 rounded" >
                        <div class="mb-3 text-center fw-bold">Statistiques élèves</div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 100px;">Effectif :</label>
                                <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 100px;">Passage :</label>
                                <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 100px;">Redoubl. :</label>
                                <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <label class="fw-bold me-2" style="min-width: 100px;">Exclusion :</label>
                                <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                                <span>filles</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <label class="fw-bold me-2" style="min-width: 100px;">Abandon :</label>
                                <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                                <span class="mx-1">dont</span>
                                <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                                <span>filles</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="margin-left: 0.5rem" >
            
                <div class="col-md-8" style="margin-top: -4rem">
                    <div class="d-flex justify-content-between mb-2">
                        <button class="btn btn-link text-primary fw-bold">1. Configurer les classes supérieures</button>
                        <button class="btn btn-link text-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalConfiguration">
                            2. Configurer décisions
                        </button>

                    </div>
                    <!-- Tableau principal -->
                    <table class="table table-bordered table-light table-sm text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Rang</th>
                                <th>Nom et prénoms</th>
                                <th>Redou</th>
                                <th>Moy1</th>
                                <th>Moy2</th>
                                <th>Moy An</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-muted">Aucune donnée</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3 d-grid gap-2" style="margin-top: 1rem; margin-left: 4rem">
                    <button class="btn btn-outline-primary btn-sm">Créer rapport</button>
                    <button class="btn btn-outline-primary btn-sm">Imprimer rapport</button>
                    <button class="btn btn-outline-secondary btn-sm">Afficher/ignorer</button>
                    <button class="btn btn-success btn-sm">Enregistrer</button>
                    <br> <br><br>
                </div>
            </div> 
    
            <div class="row">       
                <div class="col-md-12 mt-n5">
                    <div class="card shadow-sm p-3 rounded" style="background-color: #f8f9fa;">
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button class="btn btn-outline-primary btn-sm fw-bold">Liste générale passage</button>
                            <button class="btn btn-outline-warning btn-sm fw-bold">Liste générale redoublement</button>
                            <button class="btn btn-outline-danger btn-sm fw-bold" >Liste générale exclusion</button>
                            <button class="btn btn-outline-secondary btn-sm fw-bold" >Liste générale abandon</button>
                            <button class="btn btn-outline-success btn-sm fw-bold">Exporter liste</button>
                        </div>
                        <br><br> <br>
                    </div>
                </div>
            </div>  
        </div>

        <!-- Modale de la configuration des décisions -->
        <div class="modal fade" id="modalConfiguration" tabindex="-1" aria-labelledby="modalConfigLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="modalConfigLabel">Configuration des décisions de fin d’année</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Paramètres -->
                        <form method="POST" action="{{ route('decision.config.store') }}">
                            @csrf  
                            <div class="row mb-4" style="margin-left: 10rem">
                                <div class="col-md-3 fw-bold ">
                                    <label>Seuil passage :</label>
                                    <input type="number" id="seuil_Passage" name="seuil_Passage" class="form-control form-control-sm" value="{{ old('seuil_Passage', $config->seuil_Passage ?? 0) }}" step="0.01" style="background-color: whitesmoke">
                                </div>
                                <div class="col-md-3 fw-bold">
                                    <label>Minimum Cycle 1 :</label>
                                    <input type="number" id="min_Cycle1" name="min_Cycle1" class="form-control form-control-sm" value="{{ old('min_Cycle1', $config->Min_Cycle1 ?? 0) }}" step="0.01"  style="background-color: whitesmoke" >
                                </div>
                                <div class="col-md-3 fw-bold">
                                    <label>Minimum Cycle 2 :</label>
                                    <input type="number" id="min_Cycle2" name="min_Cycle2" class="form-control form-control-sm" value="{{ old('min_Cycle2', $config->Min_Cycle2 ?? 0) }}" step="0.01" style="background-color: whitesmoke">
                                </div>
                            </div>
                            <div class="row mb-4" style="margin-left: 10rem">
                                <div class="col-md-3 fw-bold">
                                    <label>Félicitations :</label>
                                    <input type="number" name="Seuil_Félicitations" class="form-control form-control-sm" value="{{ old('Seuil_Félicitations', $config->Seuil_Felicitations ?? 0) }}" step="0.01" style="background-color: whitesmoke">
                                
                                </div>
                                <div class="col-md-3 mt-2 fw-bold">
                                    <label>Encouragements :</label>
                                    <input type="number" name="Seuil_Encouragements" class="form-control form-control-sm" value="{{ old('Seuil_Encouragements', $config->Seuil_Encouragements ?? 0) }}" step="0.01" style="background-color: whitesmoke">
                                </div>
                                <div class="col-md-3 mt-2 fw-bold">
                                    <label>Tableau d'honneur :</label>
                                    <input type="number" name="Seuil_tableau_Honneur" class="form-control form-control-sm" value="{{ old('Seuil_tableau_Honneur', $config->Seuil_tableau_Honneur ?? 0) }}" step="0.01" style="background-color: whitesmoke">
                                </div>
                            </div>                         

                                <!-- TABLEAU -->
                            <table class="table table-bordered table-sm text-center align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Promotion</th>
                                        <th>Statut</th>
                                        <th>Moyenne</th>
                                        <th>Passe</th>
                                        <th>Redouble</th>
                                        <th>Exclu</th>
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
                                            if (in_array($codePromo, $seenPromoCodes)) continue;

                                            // Sauter si CODEPROMO est une valeur purement numérique courte (1, 00, 11...)
                                            if (is_numeric($codePromo) && strlen($codePromo) <= 2) continue;

                                            //  On ajoute le code à la liste des déjà vus
                                            $seenPromoCodes[] = $codePromo;

                                            //Récupération des valeurs de mincycle1 et minCycle2 pour préremplir le tableau 

                                            $seuilPassage = old('seuil_Passage', $config->seuil_Passage ?? 0);
                                            $minCycle1 = old('min_Cycle1', $config->Min_Cycle1 ?? 0);
                                            $minCycle2 = old('min_Cycle2', $config->Min_Cycle2 ?? 0);                             
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
                                                <input type="hidden" name="promotion_{{ $rowIndex }}" value="{{ $codePromo }}">
                                            </td>
                                           @php
                                                $valStatut = $statuts[$i];

                                                if ($i === 0) {
                                                    $labelStatut = 'Nouveau & Redoublant';
                                                } else {
                                                    $labelStatut = $valStatut == 1 ? 'Redoublant' : 'Nouveau';
                                                }
                                            @endphp

                                            <td>
                                                {{ $labelStatut }}
                                                <input type="hidden" name="statut_{{ $rowIndex }}" value="{{ $valStatut }}">
                                            </td>

                                            <td>{!! $moyenne !!}</td>
                                            <td><input type="radio" name="decision_{{ $rowIndex }}" value="P" {{ $i === 0 ? 'checked' : '' }}></td>
                                            <td><input type="radio" name="decision_{{ $rowIndex }}" value="R" {{ in_array($i, [1, 2]) ? 'checked' : '' }}></td>
                                            <td><input type="radio" name="decision_{{ $rowIndex }}" value="X" {{ $i === 3 ? 'checked' : '' }}></td>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                </div>
                    
            </div>
        </div>
    </div>

    <!-- script pour la configuration des décisions -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
</div>

@endsection

