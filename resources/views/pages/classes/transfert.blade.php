@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Transfert d'élèves entre classes</h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('classes.transferer-eleves') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="classe_source" class="form-label">Classe source <span id="source-class-count" class="badge bg-secondary ms-2" style="display: none;"></span></label>
                                <select class="form-select @error('classe_source') is-invalid @enderror" id="classe_source" name="classe_source" required>
                                    <option value="">Sélectionnez une classe source</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->CODECLAS }}" {{ old('classe_source') == $classe->CODECLAS ? 'selected' : '' }}>
                                            {{ $classe->LIBELCLAS }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="classe_destination" class="form-label">Classe de destination</label>
                                <select class="form-select @error('classe_destination') is-invalid @enderror" id="classe_destination" name="classe_destination" required>
                                    <option value="">Sélectionnez une classe de destination</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->CODECLAS }}" {{ old('classe_destination') == $classe->CODECLAS ? 'selected' : '' }}>
                                            {{ $classe->LIBELCLAS }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Élèves à transférer</label>
                                                        <div id="eleves-container" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                <p class="text-muted mb-0">Veuillez d'abord sélectionner une classe source</p>
                            </div>

                            <div id="selection-actions" class="mt-2 mb-3" style="display: none;">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="select-all">Tout sélectionner</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all">Tout désélectionner</button>
                            </div>

                            <div class="selected-students mb-3" id="selected-students-container" style="display: none;">
                                <h6><span id="selected-count">0</span> élève(s) sélectionné(s) :</h6>
                                <div id="selected-students-list" class="list-group mb-3" style="max-height: 150px; overflow-y: auto;">
                                    <!-- Les élèves sélectionnés apparaîtront ici -->
                                </div>
                            </div>

                            @error('eleves')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="heriter_infos" name="heriter_infos" value="1" checked>
                            <label class="form-check-label" for="heriter_infos">
                                Hériter des informations de la classe source (matières, enseignants, emploi du temps, etc.)
                            </label>
                            <div class="form-text">
                                Si coché, la classe de destination héritera des matières, enseignants et autres informations de la classe source.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-exchange-alt me-1"></i> Transférer les élèves sélectionnés
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        console.log('Le document est prêt. Initialisation du script de transfert.');

        const elevesParClasse = @json($elevesParClasse);
        console.log('Données des élèves par classe:', elevesParClasse);

        let selectedStudents = {}; // { MATRICULE: { nom: '...', prenom: '...' } }

        function updateSelectedStudentsDisplay() {
            // ... (le contenu de cette fonction reste le même)
        }

        function updateElevesList(codeClasse) {
            console.log(`Mise à jour de la liste pour la classe : ${codeClasse}`);
            const eleves = elevesParClasse[codeClasse] || [];
            console.log(`Trouvé ${eleves.length} élèves pour cette classe.`);
            
            // Mettre à jour le compteur d'élèves de la classe source
            const countBadge = $('#source-class-count');
            countBadge.text(eleves.length + ' élèves').show();

            const container = $('#eleves-container');
            const actions = $('#selection-actions');

            if (eleves.length === 0) {
                container.html('<p class="text-muted mb-0">Aucun élève dans cette classe.</p>');
                actions.hide();
                return;
            }

            let html = '<ul class="list-group">';
            eleves.forEach(eleve => {
                const isChecked = selectedStudents[eleve.MATRICULE] ? 'checked' : '';
                html += `
                    <li class="list-group-item">
                        <div class="form-check">
                            <input class="form-check-input eleve-checkbox" type="checkbox" name="eleves[]" value="${eleve.MATRICULE}" id="eleve-${eleve.MATRICULE}" data-nom="${eleve.NOM}" data-prenom="${eleve.PRENOM}" ${isChecked}>
                            <label class="form-check-label" for="eleve-${eleve.MATRICULE}">${eleve.NOM} ${eleve.PRENOM}</label>
                        </div>
                    </li>`;
            });
            html += '</ul>';
            container.html(html);
            actions.show();
            console.log('Affichage des élèves terminé.');
        }

        $('#classe_source').change(function () {
            console.log('Événement change détecté sur #classe_source.');
            const codeClasse = $(this).val();
            if (codeClasse) {
                updateElevesList(codeClasse);
            } else {
                $('#eleves-container').html('<p class="text-muted mb-0">Veuillez sélectionner une classe source</p>');
                $('#selection-actions').hide();
                $('#source-class-count').hide();
            }
        });

        // Le reste du script (gestion de la sélection, validation, etc.) reste identique...
        // Clic sur une checkbox d'élève
        $(document).on('change', '.eleve-checkbox', function () {
            const matricule = $(this).val();
            if (this.checked) {
                selectedStudents[matricule] = {
                    nom: $(this).data('nom'),
                    prenom: $(this).data('prenom')
                };
            } else {
                delete selectedStudents[matricule];
            }
            updateSelectedStudentsDisplay();
        });

        // Bouton 'Tout sélectionner'
        $('#select-all').click(function() {
            $('.eleve-checkbox').each(function() {
                if (!this.checked) {
                    $(this).prop('checked', true).trigger('change');
                }
            });
        });

        // Bouton 'Tout désélectionner'
        $('#deselect-all').click(function() {
            $('.eleve-checkbox').each(function() {
                if (this.checked) {
                    $(this).prop('checked', false).trigger('change');
                }
            });
        });

        // Validation du formulaire avant soumission
        $('form').submit(function(e) {
            const source = $('#classe_source').val();
            const destination = $('#classe_destination').val();
            const studentIds = Object.keys(selectedStudents);

            if (source && source === destination) {
                e.preventDefault();
                alert('La classe source et la classe de destination ne peuvent pas être identiques.');
                return;
            }

            if (studentIds.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un élève à transférer.');
                return;
            }
        });

        // Initialisation au chargement
        console.log('Déclenchement initial si une classe est déjà sélectionnée...');
        if ($('#classe_source').val()) {
            $('#classe_source').trigger('change');
        }
        updateSelectedStudentsDisplay();
        console.log('Script de transfert initialisé avec succès.');
    });
</script>
@endpush
@endsection
