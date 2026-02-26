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
            <div class="card-body">

                <h4 class="card-title">Gestion des Coefficients</h4>
                <div class="row mb-3">
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn-sm btn-primary" form="coefficient-form">Sauvegarder</button>
                        <button id="btnCopierValeur" type="button" class="btn-sm btn-success">Copier valeur</button>
                        <button id="btnCollerValeur" type="button" class="btn-sm btn-secondary">Coller valeur</button>
                        <button id="btnMarquerFondamentale" type="button" class="btn-sm btn-danger">Marquer
                            Fondamentale</button>
                    </div>
                </div>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasRightLabel">Liste des Matieres</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        @foreach ($listeMatieres as $matiere)
                            <p><strong>{{ $matiere->CODEMAT }}</strong> - {{ $matiere->LIBELMAT }}</p>
                        @endforeach
                    </div>
                </div>

                {{-- @csrf --}}

                <div class="row mb-3">
                    @if (Session::has('status'))
                        <div id="statusAlert" class="alert alert-succes btn-primary">
                            {{ Session::get('status') }}
                        </div>
                    @endif
                    <form id="coefficient-form" action="{{ route('enregistrerCoefficient') }}" method="POST">
                        @csrf
                        {{-- @method('PUT') --}}
                        <div class="table-responsive table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="sticky-header"{{-- style="position: sticky; left: 0; background: white; z-index: 2;" --}}>Classe / Matière</th>
                                        @foreach ($listeMatieres as $matiere)
                                            <th class="sticky-header">{{ $matiere->LIBELMAT }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listeClasses as $classe)
                                        <tr>
                                            <td style="position: sticky; left: 0; background: white; z-index: 1;">
                                                {{ $classe->CODECLAS }}</td>
                                            @foreach ($listeMatieres as $matiere)
                                                @php
                                                    $key = $classe->CODECLAS . '-' . $matiere->CODEMAT;
                                                    $coefficient = $coefficients->get($key);
                                                    $value = $coefficient ? $coefficient->COEF : '';
                                                    $isFondamentale =
                                                        $coefficient && $coefficient->FONDAMENTALE
                                                            ? 'fondamentale'
                                                            : '';
                                                @endphp
                                                <td>
                                                    <input type="number"
                                                        name="coefficients[{{ $classe->CODECLAS }}][{{ $matiere->CODEMAT }}]"
                                                        class="form-control form-control-sm coefficient-input {{ $isFondamentale }}"
                                                        min="0" style="width: 90px; text-align: center;"
                                                        step="1" value="{{ $value }}">
                                                    <input type="hidden"
                                                        name="fondamentale[{{ $classe->CODECLAS }}][{{ $matiere->CODEMAT }}]"
                                                        value="{{ $coefficient && $coefficient->FONDAMENTALE ? 1 : 0 }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <br>

    <style>
        .fondamentale {
            color: red !important;
            /* Change la couleur du texte en rouge */
        }

        .fondamentale .coefficient-input {
            color: red !important;
            font-weight: bold !important;
            background-color: #ffe6e6 !important;
            /* Fond très léger rouge */
        }

        .selected {
            border: 2px solid blue;
            /* Optionnel : ajouter un contour à la cellule sélectionnée */
        }

        /* Contrainte de hauteur et scroll interne */
        .table-container {
            max-height: 500px;
            /* ajustez selon votre besoin */
            overflow-y: auto;
            /* scroll uniquement sur le conteneur */
            position: relative;
            /* permet aux sticky d’être relatifs à ce conteneur */
        }

        /* En-têtes toujours visibles */
        .sticky-header {
            position: sticky;
            top: 0;
            background: white;
            /* ou la couleur de votre card */
            z-index: 10;
            /* au-dessus des cellules du corps */
        }

        /* Première colonne déjà en sticky */
        .first-col {
            position: sticky;
            left: 0;
            background: white;
            z-index: 11;
            /* un cran au-dessus pour éviter le recouvrement */
        }

        /* Éventuel style pour distinguer */
        .sticky-header,
        .first-col {
            /* padding, border… vous pouvez affiner ici */
        }
    </style>

    <script>
        console.log('=== SCRIPT DIRECTEMENT CHARGÉ ===');

        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM CHARGÉ ===');

            let selectedInput = null;
            let copiedValues = [];
            let copiedClasses = [];

            const tableContainer = document.querySelector('.table-container');
            console.log('Table container trouvé:', !!tableContainer);

            // Test du bouton
            const boutonFondamentale = document.getElementById('btnMarquerFondamentale');
            console.log('Bouton fondamentale trouvé:', !!boutonFondamentale);

            if (!boutonFondamentale) {
                console.error('Bouton "btnMarquerFondamentale" NON TROUVÉ !');
                return;
            }

            // Initialize 'fondamentale' class based on hidden input's value
            document.querySelectorAll('input[type="hidden"]').forEach(function(hiddenInput) {
                if (hiddenInput.value === '1') {
                    hiddenInput.closest('td').classList.add('fondamentale');
                }
            });

            // Cell selection logic
            tableContainer.addEventListener('click', function(event) {
                const input = event.target.closest('input.coefficient-input');
                if (input) {
                    if (selectedInput) {
                        selectedInput.closest('td').classList.remove('selected');
                    }
                    selectedInput = input;
                    selectedInput.closest('td').classList.add('selected');
                }
            });

            // Mark as fundamental - Version simplifiée et corrigée
            boutonFondamentale.addEventListener('click', function() {
                console.log('Bouton fondamentale cliqué, selectedInput:', selectedInput);

                if (!selectedInput) {
                    alert('Veuillez sélectionner une cellule avant de marquer fondamentale.');
                    return;
                }

                const cell = selectedInput.closest('td');
                console.log('Cellule trouvée:', cell);

                if (!cell) {
                    alert('Cellule non trouvée.');
                    return;
                }

                // Chercher tous les inputs dans la cellule
                const allInputs = cell.querySelectorAll('input');
                const hiddenInput = cell.querySelector('input[type="hidden"]');
                const numberInput = cell.querySelector('input[type="number"]');

                console.log('Inputs dans la cellule:', {
                    'total inputs': allInputs.length,
                    'hidden input': !!hiddenInput,
                    'number input': !!numberInput,
                    'hidden input value': hiddenInput ? hiddenInput.value : 'NON TROUVÉ'
                });

                if (!hiddenInput) {
                    alert('Champ hidden non trouvé dans cette cellule.');
                    return;
                }

                // Basculement de l'état fondamental
                const isCurrentlyFondamentale = cell.classList.contains('fondamentale');
                console.log('État actuel:', isCurrentlyFondamentale);

                if (isCurrentlyFondamentale) {
                    // Retirer l'état fondamental
                    cell.classList.remove('fondamentale');
                    hiddenInput.value = '0';
                    if (numberInput) {
                        numberInput.classList.remove('fondamentale');
                    }
                    console.log('Retrait de l\'état fondamental');
                } else {
                    // Ajouter l'état fondamental
                    cell.classList.add('fondamentale');
                    hiddenInput.value = '1';
                    if (numberInput) {
                        numberInput.classList.add('fondamentale');
                    }
                    console.log('Ajout de l\'état fondamental');
                }

                console.log('État final:', {
                    'classe fondamentale': cell.classList.contains('fondamentale'),
                    'valeur cachée': hiddenInput.value,
                    'classes cellule': cell.className
                });
            });

            // Copy logic
            document.getElementById('btnCopierValeur').addEventListener('click', function() {
                if (selectedInput) {
                    const rowInputs = Array.from(selectedInput.closest('tr').querySelectorAll(
                        'input.coefficient-input'));
                    const startIndex = rowInputs.indexOf(selectedInput);
                    copiedValues = rowInputs.slice(startIndex).map(function(input) {
                        return input.value;
                    });
                    copiedClasses = rowInputs.slice(startIndex).map(function(input) {
                        return input.closest('td').classList.contains('fondamentale');
                    });
                } else {
                    alert('Veuillez sélectionner une cellule avant de copier les valeurs.');
                }
            });

            // Paste logic
            document.getElementById('btnCollerValeur').addEventListener('click', function() {
                if (selectedInput && copiedValues.length > 0) {
                    const rowInputs = Array.from(selectedInput.closest('tr').querySelectorAll(
                        'input.coefficient-input'));
                    const startIndex = rowInputs.indexOf(selectedInput);

                    copiedValues.forEach(function(value, index) {
                        const targetIndex = startIndex + index;
                        if (targetIndex < rowInputs.length) {
                            const targetInput = rowInputs[targetIndex];
                            const targetCell = targetInput.closest('td');
                            const hiddenInput = targetCell.querySelector('input[type="hidden"]');

                            targetInput.value = value;
                            if (copiedClasses[index]) {
                                targetCell.classList.add('fondamentale');
                                hiddenInput.value = 1;
                            } else {
                                targetCell.classList.remove('fondamentale');
                                hiddenInput.value = 0;
                            }
                        }
                    });
                } else {
                    alert('Veuillez sélectionner une cellule et copier des valeurs avant de coller.');
                }
            });
        });
    </script>
    
@endsection
