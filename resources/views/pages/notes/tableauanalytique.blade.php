@extends('layouts.master')
@section('content')
    @php
        $nom = request('periode') == 1 ? 'TRIMESTRE' : 'SEMESTRE';
        $infoparamcontrat = \App\Models\Paramcontrat::first();
        $anneencours = $infoparamcontrat->anneencours_paramcontrat;
        $annesuivante = $anneencours + 1;
        $anneeScolaire = $anneencours . '-' . $annesuivante;
    @endphp
    <div class="col-lg-12 grid-margin stretch-card" id="original">
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

                    /* Masquer les en-têtes des intervalles à l'écran */
                    .table-screen thead tr:first-child th {
                        display: none;
                    }

                    .print-only {
                        display: none;
                    }

                    @media print {
                        @page {
                            size: landscape;
                            margin: 1cm;
                        }

                        body {
                            margin: 0;
                            padding: 15px;
                            font-size: 11px;
                        }

                        .header {
                            margin-bottom: 15px;
                            padding-bottom: 10px;
                        }

                        .school-info {
                            font-size: 14px;
                            margin-right: 15px;
                            min-width: 350px;
                            background-color: rgb(185, 185, 185) !important;
                            border: 1px solid #333 !important;
                            border-radius: 8px !important;
                        }

                        .title h4 {
                            font-size: 15px;
                            margin: 0;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }

                        /* Style unifié pour tous les tableaux */
                        table,
                        .table,
                        .table-synoptique {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 0;
                            padding: 0;
                        }

                        table th,
                        table td,
                        .table th,
                        .table td,
                        .table-synoptique th,
                        .table-synoptique td {
                            border: 1px solid #000;
                            padding: 5px;
                            text-align: center;
                            font-size: 11px;
                            line-height: 1.2;
                        }

                        /* Ligne distinctive après FAIBLE MOY */
                        .table-screen th:nth-child(5),
                        .table-screen td:nth-child(5) {
                            border-right: 2px solid #000 !important;
                        }

                        /* Masquer les cellules vides pour tous les tableaux */
                        table td:empty,
                        table td:blank,
                        .table td:empty,
                        .table td:blank,
                        .table-synoptique td:empty,
                        .table-synoptique td:blank {
                            display: none !important;
                        }

                        /* Encadrer toutes les cellules non vides pour tous les tableaux */
                        table td:not(:empty),
                        table td:not(:blank),
                        .table td:not(:empty),
                        .table td:not(:blank),
                        .table-synoptique td:not(:empty),
                        .table-synoptique td:not(:blank) {
                            border: 1px solid #000 !important;
                            display: table-cell !important;
                        }

                        /* Style unifié pour les en-têtes */
                        table th,
                        .table th,
                        .table-synoptique th {
                            font-weight: bold;
                            font-size: 12px;
                            border: 1px solid #000 !important;
                        }

                        /* Style unifié pour les lignes de bilan */
                        .ligne-bilan,
                        tr.table-success,
                        tr.table-info,
                        tr.table-primary {
                            background-color: rgb(170, 170, 170) !important;
                            font-weight: bold;
                            font-size: 12px;
                        }

                        /* Style unifié pour les cellules de total */
                        .total-cell {
                            font-weight: bold;
                        }

                        /* Conserver la couleur uniquement pour les lignes de bilan */
                        .ligne-bilan .total-cell,
                        tr.table-success .total-cell,
                        tr.table-info .total-cell,
                        tr.table-primary .total-cell {
                            background-color: rgb(170, 170, 170) !important;
                            font-weight: bold;
                        }

                        /* Style unifié pour les colonnes de groupe */
                        .col-gpe {
                            text-align: left !important;
                            padding-left: 8px;
                            min-width: 90px;
                            font-weight: bold;
                        }

                        /* Style unifié pour les en-têtes principaux */
                        thead tr:first-child th {
                            font-size: 13px;
                            padding: 6px 4px;
                            border: 2px solid #000 !important;
                        }

                        /* Amélioration de l'espacement des lignes */
                        tbody tr {
                            page-break-inside: avoid;
                        }

                        /* Style unifié pour les lignes de total */
                        tr.table-primary td,
                        tr.table-success td,
                        tr.table-info td {
                            font-size: 12px;
                            font-weight: bold;
                            border: 2px solid #000 !important;
                        }

                        /* Masquer les éléments non imprimables */
                        .no-print,
                        .screen-only {
                            display: none !important;
                        }
                    }
                </style>

                <button type="button" class="btn btn-arrow no-print" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br>

                <br>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-body">
                <!-- Formulaire pour le filtrage et le calcul -->
                <form action="{{ route('tableauanalytique') }}" method="POST" id="etatForm" class="no-print">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Moyenne de référence</label>
                                <input type="number" name="moyenne_ref" class="form-control"
                                    value="{{ old('moyenne_ref', '10.00') }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Période</label>
                                <select name="periode" class="form-control" required>
                                    <option value="">Sélectionner une période</option>
                                    @php
                                        $typeAn = $params2->first()->TYPEAN ?? null;
                                    @endphp
                                    @if ($typeAn == 1)
                                        {{-- SEMESTRES --}}
                                        <option value="1"
                                            {{ old('periode', request('periode')) == '1' ? 'selected' : '' }}>1er Semestre
                                        </option>
                                        <option value="2"
                                            {{ old('periode', request('periode')) == '2' ? 'selected' : '' }}>2ème Semestre
                                        </option>
                                        <option value="4"
                                            {{ old('periode', request('periode')) == '4' ? 'selected' : '' }}>Annuel
                                        </option>
                                    @elseif ($typeAn == 2)
                                        {{-- TRIMESTRES --}}
                                        <option value="1"
                                            {{ old('periode', request('periode')) == '1' ? 'selected' : '' }}>1er Trimestre
                                        </option>
                                        <option value="2"
                                            {{ old('periode', request('periode')) == '2' ? 'selected' : '' }}>2ème Trimestre
                                        </option>
                                        <option value="3"
                                            {{ old('periode', request('periode')) == '3' ? 'selected' : '' }}>3ème Trimestre
                                        </option>
                                        <option value="4"
                                            {{ old('periode', request('periode')) == '4' ? 'selected' : '' }}>Annuel
                                        </option>
                                    @else
                                        {{-- Cas par défaut --}}
                                        <option value="1"
                                            {{ old('periode', request('periode')) == '1' ? 'selected' : '' }}>Période 1
                                        </option>
                                        <option value="2"
                                            {{ old('periode', request('periode')) == '2' ? 'selected' : '' }}>Période 2
                                        </option>
                                        <option value="3"
                                            {{ old('periode', request('periode')) == '3' ? 'selected' : '' }}>Période 3
                                        </option>
                                        <option value="4"
                                            {{ old('periode', request('periode')) == '4' ? 'selected' : '' }}>Annuel
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type d'états</label>
                                <select name="typeEtat" class="form-control" id="typeEtat" required>
                                    <option value="">Sélectionner un état</option>
                                    <option value="tableau_analytique"
                                        {{ old('typeEtat', request('typeEtat')) == 'tableau_analytique' ? 'selected' : '' }}>
                                        Tableau synoptique des résultats</option>
                                    <option value="tableau_synoptique"
                                        {{ old('typeEtat', request('typeEtat')) == 'tableau_synoptique' ? 'selected' : '' }}>
                                        Tableau synoptique des effectifs</option>
                                    {{-- <option value="effectifs"
                                        {{ old('typeEtat', request('typeEtat')) == 'effectifs' ? 'selected' : '' }}>Tableau
                                        synoptique des effectifs</option> --}}
                                    <option value="statistique"
                                        {{ old('typeEtat', request('typeEtat')) == 'statistique' ? 'selected' : '' }}>
                                        Statistique des résultats</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des intervalles -->
                    <div class="table-responsive">

                        <table style="max-width: 500px; margin: 0 auto;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Intervalle</th>
                                    <th style="text-align: center;">Min</th>
                                    <th style="text-align: center;">Max</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Détermination du nombre d'intervalles à afficher (old input ou 5 par défaut) --}}
                                @php
                                    $oldIntervals = old('intervales', request('intervales', []));
                                    $initialCount = count($oldIntervals) ? min(count($oldIntervals), 10) : 5;
                                @endphp
                                @foreach (range(1, $initialCount) as $i)
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">I{{ $i }}</td>
                                        <td style="text-align: center;">
                                            <input type="number" name="intervales[I{{ $i }}][min]"
                                                class="form-control mx-auto interval-input"
                                                data-interval="{{ $i }}" data-type="min" step="0.01"
                                                min="0" max="20"
                                               value="{{ old("intervales.I{$i}.min", request("intervales.I{$i}.min") ?? ($i == 1 ? '0.00' : ($i == 2 ? '08.50' : ($i == 3 ? '10.00' : ($i == 4 ? '12.00' : ($i == 5 ? '16.00' : '' ))))) ) }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="intervales[I{{ $i }}][max]"
                                                class="form-control mx-auto interval-input"
                                                data-interval="{{ $i }}" data-type="max" step="0.01"
                                                min="0" max="20"                                              
                                                value="{{ old("intervales.I{$i}.max", request("intervales.I{$i}.max") ?? ($i == 1 ? '08.50' : ($i == 2 ? '10.00' : ($i == 3 ? '12.00' : ($i == 4 ? '16.00' : ($i == 5 ? '20.00' : '' )))))) }}">
                                        </td>
                                        <td style="display: none;">
                                            <a href="jojoo">jjojo</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Template caché pour intervalles dynamiques -->
                                <tr id="interval-template" style="display: none;">
                                    <td class="interval-label" style="text-align: center; vertical-align: middle;"></td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control mx-auto interval-input" data-type="min"
                                            step="0.01" min="0" max="20">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control mx-auto interval-input" data-type="max"
                                            step="0.01" min="0" max="20">
                                    </td>

                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-sm btn-danger remove-interval-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="text-center mt-3">
                        <button type="button" id="add-interval-btn" class="btn btn-success">Ajouter un intervalle</button>
                    </div>

                    {{-- <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const intervalInputs = document.querySelectorAll('.interval-input');

                            // Fonction pour vérifier si un intervalle est valide (non nul)
                            function isIntervalValid(interval) {
                                const minInput = document.querySelector(`input[data-interval="${interval}"][data-type="min"]`);
                                const maxInput = document.querySelector(`input[data-interval="${interval}"][data-type="max"]`);

                                const minValue = parseFloat(minInput.value) || 0;
                                const maxValue = parseFloat(maxInput.value) || 0;

                                return minValue > 0 || maxValue > 0;
                            }

                            // Fonction pour initialiser les valeurs à 0
                            function initializeValues() {
                                intervalInputs.forEach(input => {
                                    if (!input.value) {
                                        input.value = '0.00';
                                    }
                                });
                            }

                            // Fonction pour transférer la valeur max vers le min suivant
                            function transferMaxToNextMin(interval) {
                                if (interval < 7) {
                                    const currentMaxInput = document.querySelector(
                                        `input[data-interval="${interval}"][data-type="max"]`);
                                    const nextMinInput = document.querySelector(
                                        `input[data-interval="${interval + 1}"][data-type="min"]`);

                                    if (currentMaxInput && nextMinInput) {
                                        const maxValue = parseFloat(currentMaxInput.value) || 0;
                                        if (maxValue > 0) {
                                            nextMinInput.value = maxValue.toFixed(2);
                                        }
                                    }
                                }
                            }

                            // Fonction pour mettre à jour les valeurs null
                            function updateNullValues() {
                                const intervals = Array.from({
                                    length: 7
                                }, (_, i) => i + 1);

                                intervals.forEach(interval => {
                                    const minInput = document.querySelector(
                                        `input[data-interval="${interval}"][data-type="min"]`);
                                    const maxInput = document.querySelector(
                                        `input[data-interval="${interval}"][data-type="max"]`);

                                    const minValue = parseFloat(minInput.value) || 0;
                                    const maxValue = parseFloat(maxInput.value) || 0;

                                    // Si les deux valeurs sont 0 ou vides, mettre 0.00
                                    if ((minValue === 0 && maxValue === 0) || (!minValue && !maxValue)) {
                                        minInput.value = '0.00';
                                        maxInput.value = '0.00';
                                    }
                                });
                            }

                            // Gestionnaire d'événements pour les inputs
                            intervalInputs.forEach(input => {
                                input.addEventListener('input', function() {
                                    const currentInterval = parseInt(this.dataset.interval);
                                    const currentType = this.dataset.type;
                                    const currentValue = parseFloat(this.value) || 0;

                                    // Récupérer les valeurs min et max de l'intervalle actuel
                                    const minInput = document.querySelector(
                                        `input[data-interval="${currentInterval}"][data-type="min"]`);
                                    const maxInput = document.querySelector(
                                        `input[data-interval="${currentInterval}"][data-type="max"]`);

                                    const minValue = parseFloat(minInput.value) || 0;
                                    const maxValue = parseFloat(maxInput.value) || 0;

                                    // Validation minimale pour éviter les erreurs
                                    if (currentValue < 0) {
                                        this.value = '0.00';
                                    } else if (currentValue > 20) {
                                        this.value = '20.00';
                                    }

                                    // Transférer automatiquement la valeur max vers le min suivant uniquement si la valeur max est non nulle
                                    if (currentType === 'max' && currentValue > 0) {
                                        transferMaxToNextMin(currentInterval);
                                    }

                                    // Mettre à jour la visibilité des colonnes d'intervalle dans les tableaux
                                    updateIntervalColumnsVisibility();
                                });

                                // Ajouter un événement focus pour initialiser à 0 si vide
                                input.addEventListener('focus', function() {
                                    if (!this.value) {
                                        this.value = '0.00';
                                    }
                                });

                                // Ajouter un événement blur pour mettre à 0 si la valeur est vide
                                input.addEventListener('blur', function() {
                                    if (!this.value || this.value === '0.00') {
                                        this.value = '0.00';
                                    }
                                    // Mettre à jour la visibilité des colonnes après la perte du focus
                                    updateIntervalColumnsVisibility();
                                });
                            });

                            // Fonction pour mettre à jour la visibilité des colonnes d'intervalle
                            function updateIntervalColumnsVisibility() {
                                const intervals = Array.from({
                                    length: 7
                                }, (_, i) => i + 1);

                                intervals.forEach(interval => {
                                    const isValid = isIntervalValid(interval);
                                    const columns = document.querySelectorAll(
                                        `th:contains('I${interval}'), td:nth-child(${interval * 3 + 1}), td:nth-child(${interval * 3 + 2}), td:nth-child(${interval * 3 + 3})`
                                    );

                                    columns.forEach(column => {
                                        if (isValid) {
                                            column.style.display = '';
                                        } else {
                                            column.style.display = 'none';
                                        }
                                    });
                                });
                            }

                            // Initialiser les valeurs au chargement
                            initializeValues();
                            updateNullValues();
                            updateIntervalColumnsVisibility();
                        });
                    </script> --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const oldIntervals = @json($oldIntervals);
                            let currentIntervalCount = {{ $initialCount }};
                            const maxIntervals = 9;
                            const tableBody = document.querySelector('table tbody');
                            const intervalTemplate = document.getElementById('interval-template');
                            const addIntervalBtn = document.getElementById('add-interval-btn');

                            function clampValue(input) {
                                let val = parseFloat(input.value);
                                if (isNaN(val)) return;
                                if (val < 0) val = 0;
                                if (val > 20) val = 20;
                                input.value = val;
                            }

                            function formatValue(input) {
                                let val = parseFloat(input.value) || 0;
                                input.value = val.toFixed(2);
                            }

                            function attachEvents(input) {
                                input.addEventListener('input', function() {
                                    // Clamp only, don't format to avoid blocking multi-digit entry
                                    clampValue(this);
                                });
                                input.addEventListener('blur', function() {
                                    // On blur, format to two decimals and transfer max->next min
                                    formatValue(this);
                                    if (this.dataset.type === 'max') {
                                        const interval = parseInt(this.dataset.interval);
                                        const nextMin = document.querySelector(
                                            `input[data-interval="${interval+1}"][data-type="min"]`);
                                        if (nextMin && parseFloat(this.value) > 0) {
                                            nextMin.value = this.value;
                                        }
                                    }
                                });
                                input.addEventListener('focus', function() {
                                    // Remove formatting if user focuses
                                    this.value = parseFloat(this.value) || '';
                                });
                            }

                              function bindRemove(btn) {
                                    btn.addEventListener('click', () => {
                                    btn.closest('tr').remove();
                                    currentIntervalCount--;
                                    if (currentIntervalCount < maxIntervals) {
                                        addIntervalBtn.disabled = false;
                                    }
                                    });
                                }

 // Initialise les inputs existants
  document.querySelectorAll('.interval-input').forEach(attachEvents);

  addIntervalBtn.addEventListener('click', () => {
    if (currentIntervalCount >= maxIntervals) return;
    currentIntervalCount++;

    // 1) Clone du template (inclut déjà la colonne "Supprimer")
    const newRow = intervalTemplate.cloneNode(true);
    newRow.id = '';
    newRow.style.display = '';

    // 2) Mise à jour du label et des inputs
    newRow.querySelector('.interval-label').textContent = `I${currentIntervalCount}`;
    ['min','max'].forEach(type => {
      const inp = newRow.querySelector(`input[data-type="${type}"]`);
      inp.dataset.interval = currentIntervalCount;
      inp.name            = `intervales[I${currentIntervalCount}][${type}]`;
      inp.value           = oldIntervals[`I${currentIntervalCount}`]?.[type] ?? '0.00';
      attachEvents(inp);
    });

    // 3) Binder le bouton Supprimer **uniquement** sur ce newRow
    bindRemove(newRow.querySelector('.remove-interval-btn'));

    // 4) L'ajoute au tableau
    tableBody.appendChild(newRow);

    // 5) Désactive "Ajouter" si on atteint la limite
    if (currentIntervalCount === maxIntervals) {
      addIntervalBtn.disabled = true;
    }
  });
});
</script>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calculator"></i> Calculer
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="printTable()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Exporter vers Excel
                        </button>

                    </div>

                </form>
                <br>
                <br>
                <br>

                <!-- Affichage conditionnel des tableaux -->
                @if (isset($resultats) && isset($typeEtat))
                    @php
                        $nom = request('periode') == 1 ? 'TRIMESTRE' : 'SEMESTRE';
                    @endphp
                    @php
                                $typeEtat = request('typeEtat'); // Ajout pour éviter l'erreur variable indéfinie
                                $periode = request('periode');
                                $titre = '';
                                $typeAn = $params2->first()->TYPEAN ?? null;
                                if ($typeAn == 1) {
                                    if ($periode == 1) {
                                        $titre = 'FIN DU 1er SEMESTRE';
                                    } elseif ($periode == 2) {
                                        $titre = 'FIN DU 2ème SEMESTRE';
                                    } elseif ($periode == 4) {
                                        $titre = 'FIN D\'ANNÉE';
                                    }
                                } elseif ($typeAn == 2) {
                                    if ($periode == 1) {
                                        $titre = 'FIN DU 1er TRIMESTRE';
                                    } elseif ($periode == 2) {
                                        $titre = 'FIN DU 2ème TRIMESTRE';
                                    } elseif ($periode == 3) {
                                        $titre = 'FIN DU 3ème TRIMESTRE';
                                    } elseif ($periode == 4) {
                                        $titre = 'FIN D\'ANNÉE';
                                    }
                                }
                            @endphp
                    <div id="printableContent">
                        @if ($typeEtat == 'tableau_analytique')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4 no-print" id="titre">TABLEAU ANALYTIQUE DES RÉSULTATS  {{ ' - ' . $titre . ' ' . $anneeScolaire }}</h4>
                                <table class="table table-bordered table-screen">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" class="text-center" style="width:25px">GPE</th>
                                            <th colspan="2" class="text-center">FORTE MOY</th>
                                            <th colspan="2" class="text-center">FAIBLE MOY</th>

                                            {{-- Entêtes dynamiques intervalles --}}
                                            @foreach ($intervales as $key => $val)
                                                @php
                                                    $min = floatval($val['min']);
                                                    $max = floatval($val['max']);
                                                    $format = fn($n) => rtrim(
                                                        rtrim(number_format($n, 4, '.', ''), '0'),
                                                        '.',
                                                    );
                                                @endphp
                                                <th colspan="3" class="text-center">
                                                    {{ $format($min) }} &le; M < {{ $format($max) }} </th>
                                            @endforeach
                                        </tr>
                                        <tr class="print-only">
                                            <th class="text-center">G</th>
                                            <th class="text-center">F</th>
                                            <th class="text-center">G</th>
                                            <th class="text-center">F</th>
                                            @foreach ($intervales as $key => $val)
                                                <th class="text-center">G</th>
                                                <th class="text-center">F</th>
                                                <th class="text-center">T</th>
                                            @endforeach
                                        </tr>
                                        <tr class="screen-only">
                                            <th class="text-center">GPE</th>
                                            <th class="text-center">MFOG</th>
                                            <th class="text-center">MFOF</th>
                                            <th class="text-center">MFAG</th>
                                            <th class="text-center">MFAF</th>
                                            @foreach ($intervales as $key => $val)
                                                <th class="text-center">I{{ $loop->index + 1 }}G</th>
                                                <th class="text-center">I{{ $loop->index + 1 }}F</th>
                                                <th class="text-center">I{{ $loop->index + 1 }}T</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ordreDesGroupes = [
                                                '6è',
                                                '5è',
                                                '4è',
                                                '3è',
                                                'CYCLE I',
                                                '2ndA1',
                                                '2ndA2',
                                                '2ndB',
                                                '2ndC',
                                                '2ndD',
                                                'SECONDES',
                                                '1èreA1',
                                                '1èreA2',
                                                '1èreB',
                                                '1èreC',
                                                '1èreD',
                                                'PREMIÈRE',
                                                'TleA1',
                                                'TleA2',
                                                'TleB',
                                                'TleC',
                                                'TleD',
                                                'TERMINALE',
                                                'CYCLE II',
                                                'ETABLISSEMENT',
                                            ];
                                        @endphp

                                        @foreach ($ordreDesGroupes as $groupe)
                                            @if (isset($resultats[$groupe]))
                                                <tr @if (in_array($groupe, ['CYCLE I', 'SECONDES', 'PREMIÈRE', 'TERMINALE', 'CYCLE II', 'ETABLISSEMENT'])) class="ligne-bilan" @endif>
                                                    <td class="font-weight-bold">{{ $groupe }}</td>
                                                    {{-- Moyennes fortes/faibles --}}
                                                    <td class="text-center">
                                                        {{ $resultats[$groupe]['max_moyenne_garcons']
                                                            ? number_format($resultats[$groupe]['max_moyenne_garcons'], 2)
                                                            : '***' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $resultats[$groupe]['max_moyenne_filles']
                                                            ? number_format($resultats[$groupe]['max_moyenne_filles'], 2)
                                                            : '***' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $resultats[$groupe]['min_moyenne_garcons']
                                                            ? number_format($resultats[$groupe]['min_moyenne_garcons'], 2)
                                                            : '***' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $resultats[$groupe]['min_moyenne_filles']
                                                            ? number_format($resultats[$groupe]['min_moyenne_filles'], 2)
                                                            : '***' }}
                                                    </td>

                                                    {{-- Valeurs par intervalle --}}
                                                    @foreach ($intervales as $key => $val)
                                                        @php $data = $resultats[$groupe]['intervales'][$key] ?? ['garcons'=>0,'filles'=>0,'total'=>0]; @endphp
                                                        <td class="text-center">{{ $data['garcons'] }}</td>
                                                        <td class="text-center">{{ $data['filles'] }}</td>
                                                        <td class="text-center font-weight-bold">{{ $data['total'] }}</td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($typeEtat == 'tableau_synoptique')
                            @if (request('periode') == 4)
                                <div class="table-responsive mt-5">
                                    <h4 class="text-center mb-4 no-print" id="titre">TABLEAU ANALYTIQUE DES Effectifs  {{ ' - ' . $titre . ' ' . $anneeScolaire }}</h4>
                                    <table class="table table-bordered table-synoptique">
                                        <thead class="thead-dark">
                                            <tr class="print-only">
                                                <th colspan="2"></th>
                                                <th colspan="3">EFFECTIF TOTAL DES ELEVES<br>INSCRITS</th>

                                                <th colspan="3">
                                                        EFFECTIF DES PROMUS
                                                </th>
                                                <th colspan="3">
                                                        EFFECTIF DES REDOUBLANT
                                                </th>
                                                <th colspan="3">
                                                        EFFECTIF DES ABANDONS
                                                </th>
                                                <th colspan="3">
                                                        EFFECTIF DES EXCLUS
                                                </th>
                                            </tr>
                                            <tr class="print-only">
                                                <th>ANNEE</th>
                                                <th>NB GROUPE PEDAGOGIQUE</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                            </tr>
                                            <tr class="screen-only">
                                                <th style="width: 100px;">GPE</th>
                                                <th>NBGPE</th>
                                                <th>I1G</th>
                                                <th>I1F</th>
                                                <th>I1T</th>
                                                <th>I2G</th>
                                                <th>I2F</th>
                                                <th>I2T</th>
                                                <th>I3G</th>
                                                <th>I3F</th>
                                                <th>I3T</th>
                                                <th>I4G</th>
                                                <th>I4F</th>
                                                <th>I4T</th>
                                                <th>I5G</th>
                                                <th>I5F</th>
                                                <th>I5T</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $ordreDesGroupes = [
                                                    '6è',
                                                    '5è',
                                                    '4è',
                                                    '3è',
                                                    'CYCLE I',
                                                    '2ndA1',
                                                    '2ndA2',
                                                    '2ndB',
                                                    '2ndC',
                                                    '2ndD',
                                                    'SECONDES',
                                                    '1èreA1',
                                                    '1èreA2',
                                                    '1èreB',
                                                    '1èreC',
                                                    '1èreD',
                                                    'PREMIÈRE',
                                                    'TleA1',
                                                    'TleA2',
                                                    'TleB',
                                                    'TleC',
                                                    'TleD',
                                                    'TERMINALE',
                                                    'CYCLE II',
                                                    'ETABLISSEMENT',
                                                ];
                                            @endphp
                                            @foreach ($ordreDesGroupes as $codePromo)
                                                @if (isset($resultats[$codePromo]))
                                                    <tr @if (in_array($codePromo, ['CYCLE I', 'SECONDES', 'PREMIÈRE', 'TERMINALE', 'CYCLE II', 'ETABLISSEMENT'])) class="ligne-bilan" @endif>
                                                        <td class="font-weight-bold col-gpe">{{ $codePromo }}</td>
                                                        <td>{{ $resultats[$codePromo]['nbClasses'] }}</td>
                                                        @foreach (range(1, 5) as $i)
                                                            <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['garcons'] ?? 0 }}
                                                            </td>
                                                            <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['filles'] ?? 0 }}
                                                            </td>
                                                            <td class="total-cell">
                                                                {{ $resultats[$codePromo]['intervales']['I' . $i]['total'] ?? 0 }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @else
                                <div class="table-responsive mt-5">
                                    <h4 class="text-center mb-4 no-print" id="titre">TABLEAU ANALYTIQUE DES Effectifs  {{ ' - ' . $titre . ' ' . $anneeScolaire }}</h4>
                                    <table class="table table-bordered table-synoptique">
                                        <thead class="thead-dark">
                                            <tr class="print-only">
                                                <th colspan="2" rowspan="2"></th>
                                                <th colspan="3" rowspan="2">EFFECTIF TOTAL DES ELEVES<br>INSCRITS EN
                                                    DEBUT D'ANNEE</th>
                                                <th colspan="6">
                                                    @if (request('periode') == 2)
                                                        EFFECTIF TOTAL DES ELEVES A LA FIN DU 2nd SEMESTRE 
                                                    @elseif (request('periode') == 1)
                                                    EFFECTIF TOTAL DES ELEVES A LA FIN DU 1er SEMESTRE
                                                    @endif
                                                    
                                                </th>

                                                <th colspan="3" rowspan="2">
                                                    @if (request('periode') == 2)
                                                        EFFECTIF TOTAL DES ELEVES<br>AYANT ABANDONNE
                                                        AU COURS DE L'ANNEE
                                                    @elseif (request('periode') == 1)
                                                        EFFECTIF TOTAL DES ELEVES<br>AYANT ABANDONNE
                                                        AU COURS DU 1er SEMESTRE
                                                    @endif
                                                </th>
                                            </tr>
                                            <tr class="print-only">
                                                <th colspan="3">ELEVES NON REDOUBLANTS</th>
                                                <th colspan="3">ELEVES REDOUBLANTS</th>
                                            </tr>
                                            <tr class="print-only">
                                                <th>ANNEE</th>
                                                <th>NB GROUPE PEDAGOGIQUE</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                                <th>G</th>
                                                <th>F</th>
                                                <th>T</th>
                                            </tr>
                                            <tr class="screen-only">
                                                <th style="width: 100px;">GPE</th>
                                                <th>NBGPE</th>
                                                <th>I1G</th>
                                                <th>I1F</th>
                                                <th>I1T</th>
                                                <th>I2G</th>
                                                <th>I2F</th>
                                                <th>I2T</th>
                                                <th>I3G</th>
                                                <th>I3F</th>
                                                <th>I3T</th>
                                                <th>I4G</th>
                                                <th>I4F</th>
                                                <th>I4T</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $ordreDesGroupes = [
                                                    '6è',
                                                    '5è',
                                                    '4è',
                                                    '3è',
                                                    'CYCLE I',
                                                    '2ndA1',
                                                    '2ndA2',
                                                    '2ndB',
                                                    '2ndC',
                                                    '2ndD',
                                                    'SECONDES',
                                                    '1èreA1',
                                                    '1èreA2',
                                                    '1èreB',
                                                    '1èreC',
                                                    '1èreD',
                                                    'PREMIÈRE',
                                                    'TleA1',
                                                    'TleA2',
                                                    'TleB',
                                                    'TleC',
                                                    'TleD',
                                                    'TERMINALE',
                                                    'CYCLE II',
                                                    'ETABLISSEMENT',
                                                ];
                                            @endphp
                                            @foreach ($ordreDesGroupes as $codePromo)
                                                @if (isset($resultats[$codePromo]))
                                                    <tr @if (in_array($codePromo, ['CYCLE I', 'SECONDES', 'PREMIÈRE', 'TERMINALE', 'CYCLE II', 'ETABLISSEMENT'])) class="ligne-bilan" @endif>
                                                        <td class="font-weight-bold col-gpe">{{ $codePromo }}</td>
                                                        <td>{{ $resultats[$codePromo]['nbClasses'] }}</td>
                                                        @foreach (range(1, 4) as $i)
                                                            <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['garcons'] ?? 0 }}
                                                            </td>
                                                            <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['filles'] ?? 0 }}
                                                            </td>
                                                            <td class="total-cell">
                                                                {{ $resultats[$codePromo]['intervales']['I' . $i]['total'] ?? 0 }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @elseif($typeEtat == 'statistique')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4 no-print" id="titre">Statistique des Résultats  {{ ' - ' . $titre . ' ' . $anneeScolaire }}</h4>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">Classe</th>
                                            <th rowspan="2" class="text-center align-middle">Effectif</th>
                                            <th colspan="{{ count($intervales) }}" class="text-center">Répartition des
                                                moyennes</th>
                                            <th colspan="2" class="text-center">Meilleur élève</th>
                                            <th colspan="2" class="text-center">Plus faible élève</th>
                                            <th rowspan="2" class="text-center align-middle">Abandons</th>
                                            <th rowspan="2" class="text-center align-middle">% de Moy.</th>
                                        </tr>
                                        <tr>
                                            @php
                                                // Fonction de formatage des nombres corrigée
                                                $formatNumber = function ($num) {
                                                    if (!is_numeric($num)) {
                                                        return $num;
                                                    }

                                                    $floatVal = floatval($num);
                                                    $intVal = intval($floatVal);

                                                    // Vérifie si la valeur est entière
                                                    if ($floatVal == $intVal) {
                                                        return $intVal;
                                                    }

                                                    // Formatage pour les décimaux (supprime les zéros non significatifs)
                                                    $formatted = number_format($floatVal, 4, '.', '');
                                                    $formatted = rtrim($formatted, '0');
                                                    return rtrim($formatted, '.');
                                                };
                                            @endphp

                                            @foreach ($intervales as $intervalle => $valeurs)
                                                @php
                                                    $minFormatted = $formatNumber($valeurs['min']);
                                                    $maxFormatted = $formatNumber($valeurs['max']);
                                                @endphp
                                                <th class="text-center">
                                                    [{{ $minFormatted }} - {{ $maxFormatted }}[
                                                </th>
                                            @endforeach

                                            <th class="text-center">Nom</th>
                                            <th class="text-center">Moyenne</th>
                                            <th class="text-center">Nom</th>
                                            <th class="text-center">Moyenne</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // 1. Initialisation des regroupements par CODEPROMO
                                            $groupedClasses = [];

                                            // 2. Initialisation des bilans globaux pour les deux cycles
                                            $bilansCycles = [
                                                'CYCLE I' => [
                                                    'effectif_total' => 0,
                                                    'intervales' => [], // [intervalle => total]
                                                    'abandons' => 0,
                                                    'taux_reussite' => 0,
                                                    'meilleur_eleve' => null,
                                                    'plus_faible_eleve' => null,
                                                    'somme_taux' => 0,
                                                    'nbre_promos' => 0,
                                                ],
                                                'CYCLE II' => [
                                                    'effectif_total' => 0,
                                                    'intervales' => [],
                                                    'abandons' => 0,
                                                    'taux_reussite' => 0,
                                                    'meilleur_eleve' => null,
                                                    'plus_faible_eleve' => null,
                                                    'somme_taux' => 0,
                                                    'nbre_promos' => 0,
                                                ],
                                            ];

                                            // 3. Initialisation du bilan global ÉTABLISSEMENT
                                            $bilanEtablissement = [
                                                'effectif_total' => 0,
                                                'intervales' => [],
                                                'abandons' => 0,
                                                'taux_reussite' => 0,
                                                'meilleur_eleve' => null,
                                                'plus_faible_eleve' => null,
                                            ];

                                            // 4. Parcours des résultats de chaque classe
                                            foreach ($resultats as $classeNom => $stats) {
                                                // On considère que $stats contient :
                                                //  - 'effectif_total' (int)
                                                //  - 'intervales' (assoc [intervalle => nombre])
                                                //  - 'abandons' (int)
                                                //  - 'meilleur_eleve' ([ 'nom'=>…, 'moyenne'=>… ]) ou null
                                                //  - 'plus_faible_eleve' ([ 'nom'=>…, 'moyenne'=>… ]) ou null

                                                // 4.1. Déterminer le préfixe CODEPROMO dans $classeNom
                                                $codepromo = substr($classeNom, 0, 3);

                                                if (!isset($groupedClasses[$codepromo])) {
                                                    $groupedClasses[$codepromo] = [
                                                        'classes' => [],
                                                        'bilan' => [
                                                            'effectif_total' => 0,
                                                            'intervales' => [],
                                                            'abandons' => 0,
                                                            'taux_reussite' => 0,
                                                            'meilleur_eleve' => null,
                                                            'plus_faible_eleve' => null,
                                                        ],
                                                    ];
                                                }

                                                // 4.2. Stocker les stats de la classe dans groupedClasses
                                                $groupedClasses[$codepromo]['classes'][$classeNom] = $stats;

                                                // 4.3. Mettre à jour le bilan du CODEPROMO
                                                $groupedClasses[$codepromo]['bilan']['effectif_total'] +=
                                                    $stats['effectif_total'];
                                                $groupedClasses[$codepromo]['bilan']['abandons'] += $stats['abandons'];
                                                $groupedClasses[$codepromo]['bilan']['taux_reussite'] +=
                                                    $stats['taux_reussite'];

                                                // Intervalles
                                                foreach ($stats['intervales'] as $intervalleCle => $nbEleves) {
                                                    if (
                                                        !isset(
                                                            $groupedClasses[$codepromo]['bilan']['intervales'][
                                                                $intervalleCle
                                                            ],
                                                        )
                                                    ) {
                                                        $groupedClasses[$codepromo]['bilan']['intervales'][
                                                            $intervalleCle
                                                        ] = 0;
                                                    }
                                                    $groupedClasses[$codepromo]['bilan']['intervales'][
                                                        $intervalleCle
                                                    ] += $nbEleves;
                                                }

                                                // 4.4. Meilleur élève pour ce CODEPROMO
                                                if (!empty($stats['meilleur_eleve'])) {
                                                    $meilleurActuel =
                                                        $groupedClasses[$codepromo]['bilan']['meilleur_eleve'];
                                                    if (
                                                        !$meilleurActuel ||
                                                        $stats['meilleur_eleve']['moyenne'] > $meilleurActuel['moyenne']
                                                    ) {
                                                        $groupedClasses[$codepromo]['bilan']['meilleur_eleve'] =
                                                            $stats['meilleur_eleve'];
                                                    }
                                                }

                                                // 4.5. Plus faible élève pour ce CODEPROMO
                                                if (!empty($stats['plus_faible_eleve'])) {
                                                    $faibleActuel =
                                                        $groupedClasses[$codepromo]['bilan']['plus_faible_eleve'];
                                                    if (
                                                        !$faibleActuel ||
                                                        $stats['plus_faible_eleve']['moyenne'] <
                                                            $faibleActuel['moyenne']
                                                    ) {
                                                        $groupedClasses[$codepromo]['bilan']['plus_faible_eleve'] =
                                                            $stats['plus_faible_eleve'];
                                                    }
                                                }

                                                // 5. Mise à jour du bilan global des cycles
                                                if (in_array($codepromo, ['6EM', '5EM', '4EM', '3EM'], true)) {
                                                    $cycle = 'CYCLE I';
                                                } elseif (in_array($codepromo, ['2ND', '1RE', 'TLE'], true)) {
                                                    $cycle = 'CYCLE II';
                                                } else {
                                                    $cycle = null;
                                                }

                                                if ($cycle) {
                                                    // Effectif total et abandons
                                                    $bilansCycles[$cycle]['effectif_total'] += $stats['effectif_total'];
                                                    $bilansCycles[$cycle]['abandons'] += $stats['abandons'];

                                                    // Intervalles
                                                    foreach ($stats['intervales'] as $intervalleCle => $nbEleves) {
                                                        if (
                                                            !isset($bilansCycles[$cycle]['intervales'][$intervalleCle])
                                                        ) {
                                                            $bilansCycles[$cycle]['intervales'][$intervalleCle] = 0;
                                                        }
                                                        $bilansCycles[$cycle]['intervales'][
                                                            $intervalleCle
                                                        ] += $nbEleves;
                                                    }

                                                    // Meilleur élève du cycle
                                                    if (!empty($stats['meilleur_eleve'])) {
                                                        $meilleurCycle = $bilansCycles[$cycle]['meilleur_eleve'];
                                                        if (
                                                            !$meilleurCycle ||
                                                            $stats['meilleur_eleve']['moyenne'] >
                                                                $meilleurCycle['moyenne']
                                                        ) {
                                                            $bilansCycles[$cycle]['meilleur_eleve'] =
                                                                $stats['meilleur_eleve'];
                                                        }
                                                    }

                                                    // Plus faible élève du cycle
                                                    if (!empty($stats['plus_faible_eleve'])) {
                                                        $faibleCycle = $bilansCycles[$cycle]['plus_faible_eleve'];
                                                        if (
                                                            !$faibleCycle ||
                                                            $stats['plus_faible_eleve']['moyenne'] <
                                                                $faibleCycle['moyenne']
                                                        ) {
                                                            $bilansCycles[$cycle]['plus_faible_eleve'] =
                                                                $stats['plus_faible_eleve'];
                                                        }
                                                    }
                                                }

                                                // 6. Mise à jour du bilan global ÉTABLISSEMENT
                                                $bilanEtablissement['effectif_total'] += $stats['effectif_total'];
                                                $bilanEtablissement['abandons'] += $stats['abandons'];

                                                foreach ($stats['intervales'] as $intervalleCle => $nbEleves) {
                                                    if (!isset($bilanEtablissement['intervales'][$intervalleCle])) {
                                                        $bilanEtablissement['intervales'][$intervalleCle] = 0;
                                                    }
                                                    $bilanEtablissement['intervales'][$intervalleCle] += $nbEleves;
                                                }

                                                // Meilleur élève de l'établissement
                                        if (!empty($stats['meilleur_eleve'])) {
                                            $meilleurEtab = $bilanEtablissement['meilleur_eleve'];
                                            if (
                                                !$meilleurEtab ||
                                                $stats['meilleur_eleve']['moyenne'] > $meilleurEtab['moyenne']
                                            ) {
                                                $bilanEtablissement['meilleur_eleve'] =
                                                    $stats['meilleur_eleve'];
                                            }
                                        }

                                        // Plus faible élève de l'établissement
                                                if (!empty($stats['plus_faible_eleve'])) {
                                                    $faibleEtab = $bilanEtablissement['plus_faible_eleve'];
                                                    if (
                                                        !$faibleEtab ||
                                                        $stats['plus_faible_eleve']['moyenne'] < $faibleEtab['moyenne']
                                                    ) {
                                                        $bilanEtablissement['plus_faible_eleve'] =
                                                            $stats['plus_faible_eleve'];
                                                    }
                                                }
                                            }

                                            // 7. Calcul des taux de réussite pour chaque cycle

                                            // 7. 1 Définir les intervalles considérés comme réussite
                                            $intervalesReussite = [];
                                            foreach ($intervales as $nom => $val) {
                                                if (
                                                    isset($val['min']) &&
                                                    is_numeric($val['min']) &&
                                                    $val['min'] >= $moyenne_ref
                                                ) {
                                                    $intervalesReussite[] = $nom;
                                                }
                                            }

                                            // 8. Recalcul correct des taux de réussite par CYCLE
                                            foreach (['CYCLE I', 'CYCLE II'] as $cycle) {
                                                $effectifSansAbandon =
                                                    $bilansCycles[$cycle]['effectif_total'] -
                                                    $bilansCycles[$cycle]['abandons'];
                                                $totalReussiteCycle = 0;

                                                foreach ($intervalesReussite as $intervale) {
                                                    $totalReussiteCycle +=
                                                        $bilansCycles[$cycle]['intervales'][$intervale] ?? 0;
                                                }

                                                $bilansCycles[$cycle]['taux_reussite'] =
                                                    $effectifSansAbandon > 0
                                                        ? ($totalReussiteCycle / $effectifSansAbandon) * 100
                                                        : 0;
                                            }

                                            // 9. Taux global de l'établissement (cycle I + cycle II)
                                        $totalReussiteEtab = 0;
                                        $effectifSansAbandonEtab =
                                            $bilanEtablissement['effectif_total'] - $bilanEtablissement['abandons'];

                                        foreach ($intervalesReussite as $intervale) {
                                            $totalReussiteEtab +=
                                                $bilanEtablissement['intervales'][$intervale] ?? 0;
                                        }

                                        $bilanEtablissement['taux_reussite'] =
                                                        $effectifSansAbandonEtab > 0
                                                            ? ($totalReussiteEtab / $effectifSansAbandonEtab) * 100
                                                            : 0;
                                                @endphp

                                        {{-- 9. Affichage des tableaux par CODEPROMO --}}
                                        @foreach ($groupedClasses as $codepromo => $dataPromo)
                                            @php
                                                $sommeTauxClasse = 0;
                                                $nbClasses = count($dataPromo['classes']);

                                                $intervalesAvec10 = [];
                                                foreach ($intervales as $nomIntervale => $valeurs) {
                                                    if (
                                                        isset($valeurs['min']) &&
                                                        is_numeric($valeurs['min']) &&
                                                        $valeurs['min'] >= $moyenne_ref
                                                    ) {
                                                        $intervalesAvec10[] = $nomIntervale;
                                                    }
                                                }

                                                // Parcours des classes pour calculer le taux de réussite de chaque classe
                                                foreach ($dataPromo['classes'] as $classeNom => $statsClasse) {
                                                    $totalReussiteClasse = 0;
                                                    $denominateur =
                                                        $statsClasse['effectif_total'] - $statsClasse['abandons'];

                                                    // Somme des effectifs des intervalles admissibles à la réussite
                                                    foreach ($intervalesAvec10 as $intervale) {
                                                        $totalReussiteClasse +=
                                                            $statsClasse['intervales'][$intervale] ?? 0;
                                                    }

                                                    //calcul du taux pour la classe en question
                                                    $tauxClasse =
                                                        $denominateur > 0
                                                            ? ($totalReussiteClasse / $denominateur) * 100
                                                            : 0;
                                                    $sommeTauxClasse += $tauxClasse;
                                                }

                                                // Moyenne des taux de réussite des classes = taux de réussite de la promotion
                                                $dataPromo['bilan']['taux_reussite'] =
                                                    $nbClasses > 0 ? $sommeTauxClasse / $nbClasses : 0;
                                            @endphp


                                            {{-- 9.1. Boucle sur chacune des classes de ce CODEPROMO --}}
                                            @foreach ($dataPromo['classes'] as $classeNom => $statsClasse)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $classeNom }}</td>
                                                    <td class="text-center">{{ $statsClasse['effectif_total'] }}</td>

                                                    @php
                                                        $totalReussiteClasse = 0;
                                                        $denominateur =
                                                            $statsClasse['effectif_total'] - $statsClasse['abandons'];

                                                        // On récupère les noms des intervalles à partir de la vue (ceux dont min >= moyenne_ref)
                                                        $intervalesAvec10 = [];
                                                        foreach ($intervales as $nomIntervale => $valeurs) {
                                                            if (
                                                                isset($valeurs['min']) &&
                                                                $valeurs['min'] >= $moyenne_ref
                                                            ) {
                                                                $intervalesAvec10[] = $nomIntervale;
                                                            }
                                                        }

                                                        foreach ($intervalesAvec10 as $intervale) {
                                                            $totalReussiteClasse +=
                                                                $statsClasse['intervales'][$intervale] ?? 0;
                                                        }

                                                        $tauxClasse =
                                                            $denominateur > 0
                                                                ? ($totalReussiteClasse / $denominateur) * 100
                                                                : 0;
                                                    @endphp

                                                    {{-- 9.1.1. Affichage des effectifs par intervalle --}}
                                                    @foreach ($intervales as $intervalleCle => $valeurs)
                                                        <td class="text-center">
                                                            {{ $statsClasse['intervales'][$intervalleCle] ?? 0 }}
                                                        </td>
                                                    @endforeach

                                                    {{-- 9.1.2. Meilleur élève --}}
                                                    <td>{{ $statsClasse['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ !empty($statsClasse['meilleur_eleve']) ? number_format($statsClasse['meilleur_eleve']['moyenne'], 2) : '-' }}
                                                    </td>

                                                    {{-- 9.1.3. Plus faible élève --}}
                                                    <td>{{ $statsClasse['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ !empty($statsClasse['plus_faible_eleve'])
                                                            ? number_format($statsClasse['plus_faible_eleve']['moyenne'], 2)
                                                            : '-' }}
                                                    </td>

                                                    {{-- 9.1.4. Abandons et taux de réussite – classe --}}
                                                    <td class="text-center">{{ $statsClasse['abandons'] }}</td>
                                                    <td class="text-center">{{ number_format($tauxClasse, 2) }}%</td>
                                                </tr>
                                            @endforeach

                                            {{-- 9.2. Ligne de bilan « total » pour ce CODEPROMO --}}
                                            <tr class="table-primary">
                                                <td class="font-weight-bold">{{ $codepromo }}</td>
                                                <td class="text-center">{{ $dataPromo['bilan']['effectif_total'] }}</td>

                                                @foreach ($intervales as $intervalleCle => $valeurs)
                                                    <td class="text-center">
                                                        {{ $dataPromo['bilan']['intervales'][$intervalleCle] ?? 0 }}
                                                    </td>
                                                @endforeach

                                                <td>{{ $dataPromo['bilan']['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ !empty($dataPromo['bilan']['meilleur_eleve'])
                                                        ? number_format($dataPromo['bilan']['meilleur_eleve']['moyenne'], 2)
                                                        : '-' }}
                                                </td>

                                                <td>{{ $dataPromo['bilan']['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ !empty($dataPromo['bilan']['plus_faible_eleve'])
                                                        ? number_format($dataPromo['bilan']['plus_faible_eleve']['moyenne'], 2)
                                                        : '-' }}
                                                </td>

                                                <td class="text-center">{{ $dataPromo['bilan']['abandons'] }}</td>
                                                <td class="text-center">
                                                    {{ number_format($dataPromo['bilan']['taux_reussite'], 2) }}%
                                                </td>
                                            </tr>

                                            {{-- 9.3. Afficher le bilan CYCLE I juste après le bilan du CODEPROMO "3EM" --}}
                                            @if ($codepromo === '3EM')
                                                <tr class="table-info">
                                                    <td class="font-weight-bold">CYCLE I</td>
                                                    <td class="text-center">
                                                        {{ $bilansCycles['CYCLE I']['effectif_total'] }}</td>

                                                    @foreach ($intervales as $intervalleCle => $valeurs)
                                                        <td class="text-center">
                                                            {{ $bilansCycles['CYCLE I']['intervales'][$intervalleCle] ?? 0 }}
                                                        </td>
                                                    @endforeach

                                                    <td>{{ $bilansCycles['CYCLE I']['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ !empty($bilansCycles['CYCLE I']['meilleur_eleve'])
                                                            ? number_format($bilansCycles['CYCLE I']['meilleur_eleve']['moyenne'], 2)
                                                            : '-' }}
                                                    </td>

                                                    <td>{{ $bilansCycles['CYCLE I']['plus_faible_eleve']['nom'] ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ !empty($bilansCycles['CYCLE I']['plus_faible_eleve'])
                                                            ? number_format($bilansCycles['CYCLE I']['plus_faible_eleve']['moyenne'], 2)
                                                            : '-' }}
                                                    </td>

                                                    <td class="text-center">{{ $bilansCycles['CYCLE I']['abandons'] }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($bilansCycles['CYCLE I']['taux_reussite'], 2) }}%
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        {{-- 10. Affichage du bilan CYCLE II (après tous les CODEPROMO) --}}
                                        <tr class="table-info">
                                            <td class="font-weight-bold">CYCLE II</td>
                                            <td class="text-center">{{ $bilansCycles['CYCLE II']['effectif_total'] }}</td>

                                            @foreach ($intervales as $intervalleCle => $valeurs)
                                                <td class="text-center">
                                                    {{ $bilansCycles['CYCLE II']['intervales'][$intervalleCle] ?? 0 }}
                                                </td>
                                            @endforeach

                                            <td>{{ $bilansCycles['CYCLE II']['meilleur_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ !empty($bilansCycles['CYCLE II']['meilleur_eleve'])
                                                    ? number_format($bilansCycles['CYCLE II']['meilleur_eleve']['moyenne'], 2)
                                                    : '-' }}
                                            </td>

                                            <td>{{ $bilansCycles['CYCLE II']['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ !empty($bilansCycles['CYCLE II']['plus_faible_eleve'])
                                                    ? number_format($bilansCycles['CYCLE II']['plus_faible_eleve']['moyenne'], 2)
                                                    : '-' }}
                                            </td>

                                            <td class="text-center">{{ $bilansCycles['CYCLE II']['abandons'] }}</td>
                                            <td class="text-center">
                                                {{ number_format($bilansCycles['CYCLE II']['taux_reussite'], 2) }}%
                                            </td>
                                        </tr>

                                        {{-- 11. Ligne de bilan global ÉTABLISSEMENT --}}
                                        <tr class="table-success">
                                            <td class="font-weight-bold">ÉTABLISSEMENT</td>
                                            <td class="text-center">{{ $bilanEtablissement['effectif_total'] }}</td>

                                            @foreach ($intervales as $intervalleCle => $valeurs)
                                                <td class="text-center">
                                                    {{ $bilanEtablissement['intervales'][$intervalleCle] ?? 0 }}
                                                </td>
                                            @endforeach

                                            <td>{{ $bilanEtablissement['meilleur_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ !empty($bilanEtablissement['meilleur_eleve'])
                                                    ? number_format($bilanEtablissement['meilleur_eleve']['moyenne'], 2)
                                                    : '-' }}
                                            </td>

                                            <td>{{ $bilanEtablissement['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ !empty($bilanEtablissement['plus_faible_eleve'])
                                                    ? number_format($bilanEtablissement['plus_faible_eleve']['moyenne'], 2)
                                                    : '-' }}
                                            </td>

                                            <td class="text-center">{{ $bilanEtablissement['abandons'] }}</td>
                                            <td class="text-center">
                                                {{ number_format($bilanEtablissement['taux_reussite'], 2) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function printTable() {
            var typeEtat = document.getElementById('typeEtat').value;
            var periode = document.querySelector('select[name="periode"]').value;

            if (!typeEtat) {
                alert('Veuillez sélectionner un type de tableau à imprimer.');
                return;
            }

            if (!periode) {
                alert('Veuillez sélectionner une période.');
                return;
            }

            var printableContent = document.getElementById('printableContent');
            if (!printableContent) {
                alert('Aucun tableau à imprimer. Veuillez d\'abord calculer les résultats.');
                return;
            }

            // Créer une nouvelle fenêtre pour l'impression
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <style>
                        body {
                            margin: 0;
                            padding: 20px;
                            font-family: Arial, sans-serif;
                        }
                        .header {
                            display: flex;
                            align-items: center;
                            margin-bottom: 20px;
                            border-bottom: 0px solid #000;
                            padding-bottom: 10px;
                            position: relative;
                        }
                        .school-info {
                             
                            display: inline-block;
                            font-size: 1.1rem;
                            border: 1px solid #333;
                            border-top-left-radius: 0.75rem;
                            border-bottom-left-radius: 0.75rem;
                            border-top-right-radius: 0.75rem;       /* pas d'arrondi à droite */
                            border-bottom-right-radius: 0.75rem;
                            margin-right: 0.5rem;
                            background-color: rgb(185,185,185);        /* ou ce qui convient */
                             vertical-align: middle; 
                        }
                        .title {
                            flex: 1;
                            text-align: center;
                        }
                        .title h4 {
                            margin: 0;
                            font-size: 18px;
                            font-weight: bold;
                        }
                        .date {
                            position: absolute;
                            right: 0;
                            top: 0;
                            font-size: 12px;
                        }
                        @media print {
                            @page {
                                size: landscape;
                                margin: 1cm;
                            }
                            body {
                                margin: 0;
                                padding: 15px;
                                font-size: 11px;
                            }
                            .header {
                                margin-bottom: 15px;
                                padding-bottom: 10px;
                            }
                            .school-info {
                                font-size: 14px;
                                margin-right: 15px;
                                min-width: 350px;
                                background-color: rgb(185,185,185) !important;
                                border: 1px solid #333 !important;
                                border-radius: 8px !important;
                            }
                            .title h4 {
                                font-size: 15px;
                                margin: 0;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                            }
                            /* Style unifié pour tous les tableaux */
                            table, .table, .table-synoptique {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 0;
                                padding: 0;
                            }
                            table th, table td,
                            .table th, .table td,
                            .table-synoptique th, .table-synoptique td {
                                border: 1px solid #000;
                                padding: 5px;
                                text-align: center;
                                font-size: 11px;
                                line-height: 1.2;
                            }
                            /* Ligne distinctive après FAIBLE MOY */
                            .table-screen th:nth-child(5),
                            .table-screen td:nth-child(5) {
                                border-right: 2px solid #000 !important;
                            }
                            /* Masquer les cellules vides pour tous les tableaux */
                            table td:empty, table td:blank,
                            .table td:empty, .table td:blank,
                            .table-synoptique td:empty, .table-synoptique td:blank {
                                display: none !important;
                            }
                            /* Encadrer toutes les cellules non vides pour tous les tableaux */
                            table td:not(:empty), table td:not(:blank),
                            .table td:not(:empty), .table td:not(:blank),
                            .table-synoptique td:not(:empty), .table-synoptique td:not(:blank) {
                                border: 1px solid #000 !important;
                                display: table-cell !important;
                            }
                            /* Style unifié pour les en-têtes */
                            table th, .table th, .table-synoptique th {
                                font-weight: bold;
                                font-size: 12px;
                                border: 1px solid #000 !important;
                            }
                            /* Style unifié pour les lignes de bilan */
                            .ligne-bilan, tr.table-success, tr.table-info, tr.table-primary {
                                background-color: rgb(170,170,170) !important;
                                font-weight: bold;
                                font-size: 12px;
                            }
                            /* Style unifié pour les cellules de total */
                            .total-cell {
                                font-weight: bold;
                            }
                            /* Conserver la couleur uniquement pour les lignes de bilan */
                            .ligne-bilan .total-cell,
                            tr.table-success .total-cell,
                            tr.table-info .total-cell,
                            tr.table-primary .total-cell {
                                background-color: rgb(170,170,170) !important;
                                font-weight: bold;
                            }
                            /* Style unifié pour les colonnes de groupe */
                            .col-gpe {
                                text-align: left !important;
                                padding-left: 8px;
                                min-width: 90px;
                                font-weight: bold;
                            }
                            /* Style unifié pour les en-têtes principaux */
                            thead tr:first-child th {
                                font-size: 13px;
                                padding: 6px 4px;
                                border: 2px solid #000 !important;
                            }
                            /* Amélioration de l'espacement des lignes */
                            tbody tr {
                                page-break-inside: avoid;
                            }
                            /* Style unifié pour les lignes de total */
                            tr.table-primary td,
                            tr.table-success td,
                            tr.table-info td {
                                font-size: 12px;
                                font-weight: bold;
                                border: 2px solid #000 !important;
                            }
                            /* Masquer les éléments non imprimables */
                            .no-print, .screen-only {
                                display: none !important;
                            }
                    </style>
                </head>
                    <body>
                        <div class="header">
                            <div class="school-info" style="background-color: rgb(170,170,170) !important; border: 1px solid #ddd; border-radius: 4px; padding: 10px; margin-right: 20px;">
                                @foreach ($params2 as $param)
                                    <div style="border-bottom: 0px solid #ddd; padding-bottom: 8px;">
                                        DDESTFP {{ strtoupper(mb_substr($param->DEPARTEMEN, 0, 3)) }}<br>
                                        {{ $param->NOMETAB }}<br>
                                        {!! preg_replace('/\bTél/', '<br>Tél', $param->ADRESSE, 1) !!}
                                    </div>
                                @endforeach
                            </div>
                            @php
                                $typeEtat = request('typeEtat'); // Ajout pour éviter l'erreur variable indéfinie
                                $periode = request('periode');
                                $titre = '';
                                $typeAn = $params2->first()->TYPEAN ?? null;
                                if ($typeAn == 1) {
                                    if ($periode == 1) {
                                        $titre = 'FIN DU 1er SEMESTRE';
                                    } elseif ($periode == 2) {
                                        $titre = 'FIN DU 2ème SEMESTRE';
                                    } elseif ($periode == 4) {
                                        $titre = 'FIN D\'ANNÉE';
                                    }
                                } elseif ($typeAn == 2) {
                                    if ($periode == 1) {
                                        $titre = 'FIN DU 1er TRIMESTRE';
                                    } elseif ($periode == 2) {
                                        $titre = 'FIN DU 2ème TRIMESTRE';
                                    } elseif ($periode == 3) {
                                        $titre = 'FIN DU 3ème TRIMESTRE';
                                    } elseif ($periode == 4) {
                                        $titre = 'FIN D\'ANNÉE';
                                    }
                                }
                            @endphp

                            <div class="title">
                                <h4>
                                    {{ $typeEtat === 'tableau_analytique'
                                        ? 'TABLEAU ANALYTIQUE DES RÉSULTATS'
                                        : ($typeEtat === 'tableau_synoptique'
                                            ? 'TABLEAU ANALYTIQUE DES EFFECTIFS'
                                            : ($typeEtat === 'effectifs'
                                                ? 'TABLEAU SYNOPTIQUE DES EFFECTIFS'
                                                : 'STATISTIQUE DES RÉSULTATS')) }}
                                    {{ ' - ' . $titre . ' ' . $anneeScolaire }}
                                </h4>
                            </div>
                            <div class="date">
                                <small>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                            </div>
                        </div>
                        ${printableContent.innerHTML}
                    </body>
                    </html>
                `);

            printWindow.document.close();
            printWindow.focus();

            // Attendre que le contenu soit chargé avant d'imprimer
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
     
     
        const typeEtat = @json($typeEtat);
        let periode = @json($periode);
        if (periode == 1) {
            periode = 'FIN DU 1er SEMESTRE';
            } else if (periode == 2) {
                periode = 'FIN DU 2ème SEMESTRE';
            } else  {
                periode = 'FIN D\'ANNÉE';
        } 
            
                        
        function exportToExcel() {        
                const contentElement = document.getElementById('printableContent');
                const contentElements = document.getElementById('titre');
                if (!contentElement) {
                    alert('Aucun tableau à exporter. Veuillez d\'abord calculer les résultats.');
                    return;
                }

                const clones = contentElements.cloneNode(true);
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
                a.download = `${typeEtat}_${periode}.xls`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
        }



        // Mettre à jour l'action du formulaire en fonction du type d'état sélectionné
        document.getElementById('typeEtat').addEventListener('change', function() {
            var form = document.getElementById('etatForm');
            var selectedValue = this.value;
            var routes = {
                'tableau_analytique': "{{ route('tableauanalytique') }}",
                'tableau_synoptique': "{{ route('tableausynoptique') }}",
                'effectifs': "{{ route('effectifs') }}",
                'statistique': "{{ route('statistiques') }}"
            };
            if (routes[selectedValue]) {
                form.action = routes[selectedValue];
            }
        });
        // force le changement d’action selon la valeur courante du select
        document.getElementById('typeEtat').dispatchEvent(new Event('change'));
    </script>
@endsection
