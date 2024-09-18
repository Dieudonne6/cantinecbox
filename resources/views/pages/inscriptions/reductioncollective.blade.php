@extends('layouts.master')
@section('content')

<div class="container card mt-3">
    <div class="card-body">
        <h4 class="card-title">Réductions de groupe</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="table-container mt-2">
                    <div id="classe-eleves-container">
                        @foreach ($classes as $classe)
                            @php
                                $elevesClasse = $eleves->where('CODECLAS', $classe->CODECLAS);
                            @endphp
                            @if ($elevesClasse->isNotEmpty())
                                <div class="classe-group" data-classe="{{ $classe->CODECLAS }}" id="classe-{{ $classe->CODECLAS }}">
                                    <h5>{{ $classe->CODECLAS }}</h5>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" onclick="toggleAll(this, '{{ $classe->CODECLAS }}')"></th>
                                                <th>Nom & Prénoms</th>
                                                <th>Sexe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($elevesClasse as $eleve)
                                                <tr data-code-reduction="{{ $eleve->CodeReduction }}">
                                                    <td><input type="checkbox" class="individual-checkbox" data-sexe="{{ $eleve->SEXE }}" data-classe="{{ $classe->CODECLAS }}"></td>
                                                    <td>{{ $eleve->NOM }} <br>{{ $eleve->PRENOM }} <span class="hidden">{{ $eleve->MATRICULE }}</span></td>
                                                    <td>{{ $eleve->SEXE == 1 ? 'Masculin' : ($eleve->SEXE == 2 ? 'Féminin' : 'Non spécifié') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-body">
                    @if($classes->isEmpty())
                    <p>Aucune classe trouvée.</p>
                    @else
                    <select id="class-select" name="classes" class="form-select mb-3" aria-label="Large select example" onchange="moveClassToTop()">
                        <option value="">Sélectionnez une classe</option>
                        @foreach ($classes as $classe)
                         <option value="{{$classe->CODECLAS}}">{{$classe->CODECLAS}}</option>
                        @endforeach
                    </select>
                    @endif

                    <form>
                        <p>Public cible</p>
                        <input type="radio" id="option1" name="choix" value="2" onclick="toggleGenderSelection(2)">
                        <label for="option1">Sélectionner les filles</label><br>
                        
                        <input type="radio" id="option2" name="choix" value="1" onclick="toggleGenderSelection(1)">
                        <label for="option2">Sélectionner les garçons</label><br>
                        
                        <input type="radio" id="option3" name="choix" value="0" checked onclick="toggleGenderSelection(0)">
                        <label for="option3">Sélectionner sans distinction</label><br>
                    </form>

                    <select id="reduction-select" name="reduction" class="form-select mb-3" aria-label="Large select example">
                        <option value="">Profil de réduction</option>
                        @foreach($reductions as $reduction)
                            <option value="{{ $reduction->CodeReduction }}">{{ $reduction->LibelleReduction }}</option>
                        @endforeach
                    </select>
                    
                    <a id="reduction-details-link" href="#" onclick="openReductionModal()" style="display:none;">Détails sur le profil de réduction</a>
                    
                    <button type="button" class="btn btn-primary" onclick="applyReductions()">Appliquer les réductions</button>
                    <button type="button" class="btn btn-secondary" onclick="clearSelection()">Annuler</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les messages d'erreur -->
<div id="error-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeErrorModal()">&times;</span>
        <p id="error-message"></p>
        <button class="btn btn-secondary" onclick="closeErrorModal()">Fermer</button>
    </div>
</div>

<script>
    function toggleAll(source, classe) {
        const checkboxes = document.querySelectorAll(`.classe-group[data-classe="${classe}"] .individual-checkbox`);
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked; // Coche ou décoche tous les élèves selon l'état de la case principale
        });
    }

    function toggleGenderSelection(sexe) {
        const selectedClass = document.getElementById('class-select').value;
        const checkboxes = selectedClass 
            ? document.querySelectorAll(`.classe-group[data-classe="${selectedClass}"] .individual-checkbox`)
            : document.querySelectorAll('.individual-checkbox');

        checkboxes.forEach(checkbox => {
            const gender = parseInt(checkbox.getAttribute('data-sexe'));
            if (sexe === 0) {
                checkbox.checked = false; // Aucune sélection
            } else {
                checkbox.checked = (gender === sexe); // Coche les cases selon le sexe sélectionné
            }
        });
    }

    function clearGenderSelection() {
        const checkboxes = document.querySelectorAll('.individual-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false; // Décocher toutes les cases
        });
    }

    function moveClassToTop() {
        const selectedClass = document.getElementById('class-select').value;
        const classGroup = document.getElementById(`classe-${selectedClass}`);
        if (classGroup) {
            const container = document.getElementById('classe-eleves-container');
            container.prepend(classGroup); // Déplace le groupe de classe sélectionné en haut
        }
    }

    function clearSelection() {
        clearGenderSelection(); // Décocher toutes les cases
        document.getElementById('class-select').selectedIndex = 0; // Réinitialiser la sélection de classe
        const radioButtons = document.querySelectorAll('input[name="choix"]');
        radioButtons.forEach(radio => {
            radio.checked = false; // Décocher tous les boutons radio
        });
        document.getElementById('option3').checked = true; // Sélectionner l'option "Sélectionner sans distinction"
    }

    function applyReductions() {
        const selectedClass = document.getElementById('class-select').value;
        const selectedReduction = document.getElementById('reduction-select').value;
        const selectedEleves = [];
        let hasInvalidReduction = false;

        const checkboxes = document.querySelectorAll('.individual-checkbox:checked');
        checkboxes.forEach(checkbox => {
            const eleveRow = checkbox.closest('tr');
            const eleveCodeReduction = eleveRow.getAttribute('data-code-reduction'); // Assurez-vous que le code de réduction est accessible
            if (eleveCodeReduction == 0) {
                selectedEleves.push({
                    nom: eleveRow.children[1].textContent.trim(),
                    sexe: checkbox.getAttribute('data-sexe')
                });
            } else {
                hasInvalidReduction = true;
            }
        });

        // Vérification si un profil de réduction est sélectionné
        if (!selectedReduction) {
            showErrorModal("Veuillez sélectionner un profil de réduction.");
            return;
        }

        // Vérification si au moins un élève est sélectionné
        if (checkboxes.length === 0) {
            showErrorModal("Veuillez sélectionner au moins un élève.");
            return;
        }

        // Vérification si au moins un élève avec un code de réduction valide est sélectionné
        if (selectedEleves.length === 0) {
            showErrorModal("Veuillez sélectionner un élève n'ayant pas de réduction.");
            return;
        }

        // Vérification si un élève sélectionné a déjà un profil de réduction
        if (hasInvalidReduction) {
            showErrorModal("Un ou plusieurs élèves sélectionnés ont déjà un profil de réduction.");
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/appliquereduc'; // Remplacez par la route de votre application

        const csrfToken = '{{ csrf_token() }}'; // Ajouter CSRF token pour les applications Laravel
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const classInput = document.createElement('input');
        classInput.type = 'hidden';
        classInput.name = 'class';
        classInput.value = selectedClass;
        form.appendChild(classInput);

        const reductionInput = document.createElement('input');
        reductionInput.type = 'hidden';
        reductionInput.name = 'reduction';
        reductionInput.value = selectedReduction;
        form.appendChild(reductionInput);

        selectedEleves.forEach((eleve, index) => {
            const eleveInput = document.createElement('input');
            eleveInput.type = 'hidden';
            eleveInput.name = `eleves[${index}]`;
            eleveInput.value = eleve.nom;
            form.appendChild(eleveInput);

            const sexeInput = document.createElement('input');
            sexeInput.type = 'hidden';
            sexeInput.name = `eleves_sexe[${index}]`;
            sexeInput.value = eleve.sexe;
            form.appendChild(sexeInput);
        });

        document.body.appendChild(form);
        form.submit();
    }

    function showErrorModal(message) {
        document.getElementById('error-message').textContent = message;
        document.getElementById('error-modal').style.display = 'block';
    }

    function closeErrorModal() {
        document.getElementById('error-modal').style.display = 'none';
    }
</script>

@endsection

<style>
    body {
    font-family: Arial, sans-serif;
}

.table-container {
    max-width: 100%;
    max-height: 500px; 
    overflow-x: auto;
    overflow-y: auto; 
    border: 1px solid #ddd; 
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    position: sticky;
    top: 0;
    background-color: #f2f2f2;
    z-index: 1; 
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
.hidden {
    visibility: hidden;
    display: none;
}


tr:hover {
    background-color: #f1f1f1;
}

tbody tr {
    background-color: #ffffff;
}

#reduction-modal {
    position: fixed;
    z-index: 9999;
    left: 600;
    top: 200;
    width: 500px; 
    height: 350px; 
    overflow: auto;
    background-color: rgb(255, 255, 255); 
}

.modal-content {
    background-color: #fefefe;
    padding: 20px;
    border: 1px solid #888;
    width: 300px; 
    height: 250px; 
    position: relative;
}

.close {
    color: #aaa;
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#error-modal {
    position: fixed;
    z-index: 9999;
    left: 50%;
    top: 20%;
    transform: translate(-50%, -50%);
    width: 500px;
    height: 200px;
    background-color: #fff;
    border: 1px solid #888;
    box-shadow: 0 5px 5px rgba(0,0,0,0.3);
    padding: 20px;
    display: none;
}

#error-modal .modal-content {
    position: relative;
    padding: 20px;
}

#error-modal .close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}
</style>