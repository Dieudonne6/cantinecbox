@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4">Verrouillage des Notes</h2>

            <!-- Form for locking grades -->
            <form method="POST" action="{{ route('verrouillage') }}">
                @csrf

                <!-- Section des choix d'action -->
                <div class="mb-4">
                    <h4>Que voulez-vous faire?</h4>
                    <div class="form-group border p-4 rounded" style="border-color: #ccc;">
                        @php
                            $options = [
                                'notes_saisies_a_ce_jour' => 'Vérrouiller toutes les notes saisies à ce jour',
                                'classe_en_cours' => 'Vérrouiller les notes saisies de la classe en cours',
                                'matiere_en_cours' => 'Vérrouiller les notes saisies pour la matière en cours',
                                'classe_et_matiere_en_cours' => 'Vérrouiller des colonnes pour la classe et la matière en cours',
                                'trimestre_en_cours' => 'Vérrouiller le trimestre en cours',
                            ];
                        @endphp

                        @foreach ($options as $value => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="type" id="{{ $value }}"
                                       value="{{ $value }}" onclick="handleRadioClick(this)"
                                       @if ($loop->first) checked @endif>
                                <label class="form-check-label" for="{{ $value }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Confirmation de verrouillage -->
                <div class="mb-4">
                    <h4>Confirmation</h4>
                    <div class="form-check">
                        <input type="checkbox" id="confirmLock" name="confirmLock">
                        <label for="confirmLock">Cocher si seul le superviseur pourra déverrouiller</label>
                    </div>
                </div>

                <!-- Choix de la classe et matière -->
                <div class="row mb-4">
                    <!-- Choix de la classe -->
                    <div class="col-md-6 mb-3">
                        <label for="classe">Choisir une classe</label>
                        <select id="classe" name="classe" class="form-select" onchange="loadSubjects(this.value)">
                            <option value="">Sélectionner une classe</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Choix de la matière -->
                    <div class="col-md-6 mb-3">
                        <label for="niveau">Choisir une matière</label>
                        <select id="niveau" name="niveau" class="form-select">
                            <option value="">Sélectionner une matière</option>
                        </select>
                    </div>
                </div>

                <!-- Section des options supplémentaires -->
                <div class="mb-4">
                    <div class="row">
                        @foreach (range(1, 10) as $i)
                            <div style="display: flex; align-items: center; width: calc(100% / 7 - 10px); margin-bottom: 10px;">
                                <input type="checkbox" id="checkbox{{ $i }}" name="checkbox{{ $i }}"
                                       style="margin-right: 8px; cursor: pointer;">
                                <label for="checkbox{{ $i }}"
                                       style="font-weight: 500; color: #333; cursor: pointer; my-5;">
                                    INT{{ $i }}
                                </label>
                            </div>
                        @endforeach
                        @foreach (range(1, 3) as $i)
                            <div style="display: flex; align-items: center; width: calc(100% / 7 - 10px); margin-bottom: 10px;">
                                <input type="checkbox" id="checkboxDEV{{ $i }}"
                                       name="checkboxDEV{{ $i }}" style="margin-right: 8px; cursor: pointer;">
                                <label for="checkboxDEV{{ $i }}"
                                       style="font-weight: 500; color: #333; cursor: pointer; my-5;">
                                    DEV{{ $i }}
                                </label>
                            </div>
                        @endforeach
                        <div style="display: flex; align-items: center; width: calc(100% / 7 - 10px); margin-bottom: 10px;">
                            <input type="checkbox" id="checkbox14" name="checkbox14"
                                   style="margin-right: 8px; cursor: pointer;">
                            <label for="checkbox14" style="font-weight: 500; color: #333; cursor: pointer; my-5;">
                                TEST(COMPO)
                            </label>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSelectAll()" id="selectAllBtn"
                            class="btn btn-outline-secondary mt-3">Sélectionner Tout</button>
                </div>

                <!-- Bouton de soumission -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Lancer verrouillage</button>
                </div>
            </form>
        </div>
    </div>
    <br><br><br>

    <script>
        function handleRadioClick(radio) {
            // Handle radio button click
            console.log(radio.value);
        }

        function toggleSelectAll() {
            // Toggle select all checkboxes functionality
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
            selectAllBtn.textContent = allChecked ? 'Sélectionner Tout' : 'Désélectionner Tout';
        }

        function loadSubjects(classCode) {
            const subjectSelect = document.getElementById('niveau');

            // Clear the current subjects
            subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';

            // Make an AJAX request to get subjects based on the selected class
            fetch(`/api/subjects/${classCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length) {
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Aucune matière disponible';
                        subjectSelect.appendChild(option);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
@endsection

