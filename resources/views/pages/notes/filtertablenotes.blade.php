@extends('layouts.master')

@section('content')
    <style>
        #mainTable th,
        #mainTable td {
            text-align: center;
            padding: 8px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        #mainTable th {
            background-color: #f2f2f2;
        }

        .interval-section td {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .table thead tr:first-child {
            border: 2px solid #000 !important;
        }

        /* Grise les deux dernières colonnes du tbody */
        #mainTable tbody td:nth-last-child(-n+2) {
            background-color: #e0e0e0;
        }
    </style>

    <div class="container">
        <div class="card shadow-sm p-4">
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
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="col-md-2 mb-3">
                    <select class="js-example-basic-multiple w-100" id="tableSelect4" onchange="redirectWithSelection()"
                        name="classe">
                        @foreach ($classe as $classeOption)
                            <option value="{{ $classeOption->CODECLAS }}"
                                {{ request()->query('classe') == $classeOption->CODECLAS ? 'selected' : '' }}>
                                {{ $classeOption->CODECLAS }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <select class="js-example-basic-multiple w-100" id="tableSelect5" onchange="redirectWithSelection()">
                        @for ($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}" {{ request()->query('periode') == $i ? 'selected' : '' }}>
                                {{ $i }}ème Période
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <select class="js-example-basic-multiple w-100" id="tableSelect6" onchange="redirectWithSelection()">
                        @foreach (['DEV1', 'DEV2', 'DEV3', 'TEST', 'MS1'] as $note)
                            <option value="{{ $note }}" {{ request()->query('note') == $note ? 'selected' : '' }}>
                                {{ $note }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <form action="{{ url('/calculermoyenne') }}" method="POST" class="text-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="classe" id="classeHidden" value="">
                        <button type="submit" class="btn btn-primary">Calculer moyennes</button>
                    </form>
                </div>
                <div class="col-md-2 mb-3">
                    <button onclick="printNote()" class="btn btn-primary">Imprimer</button>
                </div>
            </div>
            <div class="table-responsive mb-4" id="mainTable">
                <div class="titles mb-4 d-none">
                    <h4 class="mb-2 text-center">Tableau Récapitulatifs des notes - {{ $annescolaire }} </h4>
                    <div class="text-right">
                        <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-center">
                        <strong>
                            Classe: {{ $selectedClass }} / {{ $typean == 2 ? 'Trimestre' : 'Semestre' }}:
                            {{ $selectedPeriod }}
                            @if ($selectedEvaluation == 'MS1')
                                (Moyenne {{ $typean == 2 ? 'trimestre' : 'semestre' }})
                            @elseif($selectedEvaluation == 'DEV1')
                                (Note de Devoirs 1)
                            @elseif($selectedEvaluation == 'DEV2')
                                (Note de Devoirs 2)
                            @elseif($selectedEvaluation == 'DEV3')
                                (Note de Devoirs 3)
                            @elseif($selectedEvaluation == 'TEST')
                                (Note de TEST)
                            @endif
                        </strong>
                    </p>
                </div>

                <table class="table">
                    <p>
                        <strong>
                            Classe: {{ $selectedClass }} / {{ $typean == 2 ? 'Trimestre' : 'Semestre' }}:
                            {{ $selectedPeriod }}
                            @if ($selectedEvaluation == 'MS1')
                                (Moyenne {{ $typean == 2 ? 'trimestre' : 'semestre' }})
                            @elseif($selectedEvaluation == 'DEV1')
                                (Note de Devoirs 1)
                            @elseif($selectedEvaluation == 'DEV2')
                                (Note de Devoirs 2)
                            @elseif($selectedEvaluation == 'DEV3')
                                (Note de Devoirs 3)
                            @elseif($selectedEvaluation == 'TEST')
                                (Note de TEST)
                            @endif
                        </strong>
                    </p>
                    <thead>
                        <tr class="me">
                            <th>Matricule</th>
                            <th>Nom et Prénoms</th>
                            @foreach ($matieres as $matiere)
                                @if ($matiere->COEF != 0)
                                    <th>{{ $matiere->NOMCOURT }} ({{ $matiere->COEF }})</th>
                                @endif
                            @endforeach
                            @if ($selectedEvaluation == 'MS1')
                                <th>M.SEM</th>
                                @if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3))
                                    <th>M.AN</th>
                                @endif
                                <th>RANG</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Affichage des notes par élève -->
                        @foreach ($eleves as $eleve)
                            <tr>
                                <td>{{ $eleve->MATRICULEX }}</td>
                                <td style="text-align: left;">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                                @foreach ($matieres as $matiere)
                                    @if ($matiere->COEF != 0)
                                        @php
                                            $noteKey = $eleve->MATRICULE . '-' . $matiere->CODEMAT;
                                            $notesRecord = $notes[$noteKey] ?? null;
                                            $hasValidDevoir = false;
                                            if ($notesRecord) {
                                                // Vérification des notes de devoir (DEV1, DEV2, DEV3)
                                                if (
                                                    (isset($notesRecord->DEV1) &&
                                                        $notesRecord->DEV1 != -1 &&
                                                        $notesRecord->DEV1 != 21) ||
                                                    (isset($notesRecord->DEV2) &&
                                                        $notesRecord->DEV2 != -1 &&
                                                        $notesRecord->DEV2 != 21) ||
                                                    (isset($notesRecord->DEV3) &&
                                                        $notesRecord->DEV3 != -1 &&
                                                        $notesRecord->DEV3 != 21)
                                                ) {
                                                    $hasValidDevoir = true;
                                                }
                                            }
                                            $noteValue =
                                                $notesRecord &&
                                                $hasValidDevoir &&
                                                isset($notesRecord->$selectedEvaluation) &&
                                                $notesRecord->$selectedEvaluation != -1 &&
                                                $notesRecord->$selectedEvaluation != 21
                                                    ? $notesRecord->$selectedEvaluation
                                                    : '**.**';
                                        @endphp
                                        <td>{{ $noteValue }}</td>
                                    @endif
                                @endforeach
                                @if ($selectedEvaluation == 'MS1')
                                    <td>{{ $eleve->MSEM != -1 && $eleve->MSEM != 21 ? $eleve->MSEM : '**' }}</td>
                                    @if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3))
                                        <td>{{ $eleve->MAN != -1 && $eleve->MAN != 21 ? $eleve->MAN : '**' }}</td>
                                    @endif
                                    <td>{{ $eleve->RANG != -1 && $eleve->RANG != 0 ? $eleve->RANG : '**' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>

                    @php
                        // Si MS1 est sélectionné, on ajoute les colonnes M.SEM, éventuellement M.AN et RANG.
                        // Sinon, on ne rajoute aucune colonne supplémentaire.
                        $extraColumns =
                            $selectedEvaluation == 'MS1'
                                ? (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3)
                                    ? 2
                                    : 1)
                                : 0;
                    @endphp

                    <tfoot class="me">
                        <!-- Deux lignes vides -->
                        <tr>
                            <td colspan="{{ count($matieres->where('COEF', '!=', 0)) + $extraColumns }}"
                                style="border: none;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ count($matieres->where('COEF', '!=', 0)) + $extraColumns }}"
                                style="border: none;">&nbsp;</td>
                        </tr>

                        <!-- Ligne pour Plus faibles moyennes -->
                        <tr class="me interval-section">
                            <td class="me" colspan="2" style="font-weight: 550">Plus faibles moyennes</td>
                            @foreach ($matieres as $matiere)
                                @if ($matiere->COEF != 0)
                                    <td>{{ $moyennes[$matiere->CODEMAT]['min'] ?? 0 }}</td>
                                @endif
                            @endforeach
                            @if ($selectedEvaluation == 'MS1')
                                <!-- Afficher M.SEM -->
                                <td>{{ $moyennesMSEM['min'] }}</td>
                                <!-- Afficher M.AN si applicable -->
                                @if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3))
                                    <td>{{ $moyennesMAN['min'] }}</td>
                                @endif
                            @else
                                @for ($i = 0; $i < $extraColumns; $i++)
                                    <td></td>
                                @endfor
                            @endif
                        </tr>

                        <!-- Ligne pour Plus fortes moyennes -->
                        <tr class="mb-3">
                            <td colspan="2" style="font-weight: 550">Plus fortes moyennes</td>
                            @foreach ($matieres as $matiere)
                                @if ($matiere->COEF != 0)
                                    <td>{{ $moyennes[$matiere->CODEMAT]['max'] ?? 0 }}</td>
                                @endif
                            @endforeach
                            @if ($selectedEvaluation == 'MS1')
                                <!-- Afficher M.SEM -->
                                <td>{{ $moyennesMSEM['max'] }}</td>
                                <!-- Afficher M.AN si applicable -->
                                @if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3))
                                    <td>{{ $moyennesMAN['max'] }}</td>
                                @endif
                            @else
                                @for ($i = 0; $i < $extraColumns; $i++)
                                    <td></td>
                                @endfor
                            @endif
                        </tr>

                        <!-- Lignes pour les intervalles -->
                        @foreach ($intervals as $index => $interval)
                            <tr>
                                <td colspan="2" style="font-weight: 550">Nb moyennes de {{ $interval['min'] }} à
                                    {{ $interval['max'] }}</td>
                                @foreach ($matieres as $matiere)
                                    @if ($matiere->COEF != 0)
                                        <td>{{ $moyenneCounts[$matiere->CODEMAT][$index] ?? 0 }}</td>
                                    @endif
                                @endforeach
                                @if ($selectedEvaluation == 'MS1')
                                    <!-- Afficher les intervalles pour M.SEM -->
                                    <td>{{ $moyenneCountsMSEM[$index] ?? 0 }}</td>
                                    <!-- Afficher les intervalles pour M.AN si applicable -->
                                    @if (($typean == 1 && $selectedPeriod == 2) || ($typean == 2 && $selectedPeriod == 3))
                                        <td>{{ $moyenneCountsMAN[$index] ?? 0 }}</td>
                                    @endif
                                @else
                                    @for ($i = 0; $i < $extraColumns; $i++)
                                        <td></td>
                                    @endfor
                                @endif
                            </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script>
        function redirectWithSelection() {
            const classe = document.getElementById("tableSelect4").value; // Récupère la classe sélectionnée
            const periode = document.getElementById("tableSelect5").value; // Récupère la matière sélectionnée
            const note = document.getElementById("tableSelect6").value; // Récupère la matière sélectionnée

            let url = '/filtertablenotes'; // URL de redirection
            let params = [];

            if (classe) params.push(`classe=${classe}`); // Ajoute le paramètre de classe si sélectionné
            if (periode) params.push(`periode=${periode}`); // Ajoute le paramètre de matière si sélectionné
            if (note) params.push(`note=${note}`);
            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            window.location.href = url;
        }

        // Ajoute les écouteurs d'événements pour les deux sélecteurs
        document.getElementById("tableSelect4").addEventListener("change", redirectWithSelection);
        document.getElementById("tableSelect5").addEventListener("change", redirectWithSelection);
        document.getElementById("tableSelect6").addEventListener("change", redirectWithSelection);

        function injectTableStyles() {
            var style = document.createElement('style');
            style.innerHTML = `
                @page { size: landscape; }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                thead {
                    background-color: #f2f2f2;
                    border: 1px solid #000;
                    text-transform: uppercase;
                }
                .table td:nth-child(n+2), .table th:nth-child(n+2) {
                    margin: 0 !important;
                    padding: 5px 0 5px 5px !important;
                    width: 100px !important;
                    word-wrap: break-word !important;
                    white-space: normal !important;
                }
                tbody td:nth-last-child(-n+2) {
                    background-color: #e0e0e0 !important;;
                }

                th, td {
                    padding: 0 !important;
                    margin: 0 !important;
                    border: 1px solid #000;
                    text-align: center;
                    font-size: 10px !important;
                }
                .titles {
                    display: block  !important;
                }
                .classe-row {
                    background-color: #f9f9f9;
                    font-weight: bold;
                }
                .table tbody tr:last-child td {
                    border-bottom: 2px solid #000 !important;
                }
                .table thead tr:first-child {
                    border: 2px solid #000 !important;
                }
                tr.interval-section td {
                    border: 1px solid #000 !important;
                }
                .me {
                    border-collapse: collapse;
                }
            `;
            document.head.appendChild(style);
        }

        function printNote() {
            injectTableStyles(); // Injecter les styles pour l'impression
            var originalContent = document.body.innerHTML; // Contenu original de la page
            var printContent = document.getElementById('mainTable').innerHTML; // Contenu spécifique à imprimer
            document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer

            setTimeout(function() {
                window.print(); // Ouvrir la boîte de dialogue d'impression
                document.body.innerHTML = originalContent; // Restaurer le contenu original
            }, 1000);
        }

        document.getElementById('calculMoyenneForm').addEventListener('submit', function(e) {
            var classeSelect = document.getElementById("tableSelect4");
            var selectedClasse = classeSelect.value;
            document.getElementById("classeHidden").value = selectedClasse;
        });
    </script>
@endsection
