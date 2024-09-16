@extends('layouts.master')
@section('content')

<div class="container card mt-3">
    <div class="card-body">
        <h4 class="card-title">Réductions de groupe</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="table-container mt-2">
                    <div id="classe-eleves-container">
                        <!-- Les classes et élèves sont insérés ici par Javascript -->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-body">
                    @if($classes->isEmpty())
                    <p>Aucune classe trouvée.</p>
                    @else
                    <select id="class-select" name="classes" class="form-select mb-3" aria-label="Large select example" onchange="sortClasses()">
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

                    <select id="reduction-select" name="reduction" class="form-select mb-3" aria-label="Large select example" onchange="showReductionDetailsLink()">
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

<!-- Modal pour les détails de réduction -->
<div id="reduction-modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeReductionModal()">&times;</span>
        <h4>Détails sur le profil de réduction</h4>
        <p id="reduction-details"></p>
        <button class="btn btn-secondary" onclick="closeReductionModal()">Fermer</button>
    </div>
</div>

@endsection

<script>
    const elevesByClasse = @json($eleves->groupBy('CODECLAS'));
    const reductions = @json($reductions);

    function displayEleves() {
        const container = document.getElementById('classe-eleves-container');
        container.innerHTML = ''; // Clear current content

        const sortedClasses = Object.keys(elevesByClasse).sort();

        sortedClasses.forEach(classe => {
            const eleves = elevesByClasse[classe];
            const classeElement = document.createElement('div');
            classeElement.classList.add('classe-group');
            classeElement.setAttribute('data-classe', classe);

            // Classe name
            const classeTitle = document.createElement('h5');
            classeTitle.textContent = classe;
            classeElement.appendChild(classeTitle);

            // Table for the students
            const table = document.createElement('table');
            const thead = document.createElement('thead');
            const trHead = document.createElement('tr');

            trHead.innerHTML = `
                <th><input type="checkbox" onclick="toggleAll(this, '${classe}')"></th>
                <th>Nom & Prénoms</th>
                <th>Sexe</th>
            `;
            thead.appendChild(trHead);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            eleves.forEach(eleve => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td><input type="checkbox" class="individual-checkbox" data-sexe="${eleve.SEXE}"></td>
                <td>${eleve.NOM} <br>${eleve.PRENOM} <span class="hidden">${eleve.MATRICULE}</span></td>
                <td>${eleve.SEXE == 1 ? 'Masculin' : (eleve.SEXE == 2 ? 'Féminin' : 'Non spécifié')}</td>
            `;
                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            classeElement.appendChild(table);
            container.appendChild(classeElement);
        });
    }

    function toggleGenderSelection(sexe) {
        const selectedClass = document.getElementById('class-select').value;
        const checkboxes = selectedClass 
            ? document.querySelectorAll(`.classe-group[data-classe="${selectedClass}"] .individual-checkbox`)
            : document.querySelectorAll('.individual-checkbox');

        checkboxes.forEach(checkbox => {
            const gender = parseInt(checkbox.getAttribute('data-sexe'));
            checkbox.checked = (sexe === 0) || (gender === sexe); // coche si le sexe correspond ou si c'est "sans distinction"
        });
    }

    function toggleAll(source, classe = null) {
        const sexeSelected = parseInt(document.querySelector('input[name="choix"]:checked').value); // Récupère l'option choisie
        const checkboxes = classe 
            ? document.querySelectorAll(`.classe-group[data-classe="${classe}"] .individual-checkbox`)
            : document.querySelectorAll('.individual-checkbox');

        checkboxes.forEach(checkbox => {
            const gender = parseInt(checkbox.getAttribute('data-sexe'));
            checkbox.checked = (sexeSelected === 0 || gender === sexeSelected) && source.checked;
        });
    }

    function sortClasses() {
        const selectedClass = document.getElementById('class-select').value;
        const container = document.getElementById('classe-eleves-container');
        const classes = Array.from(container.getElementsByClassName('classe-group'));

        classes.sort((a, b) => {
            if (a.getAttribute('data-classe') === selectedClass) return -1;
            if (b.getAttribute('data-classe') === selectedClass) return 1;
            return a.getAttribute('data-classe').localeCompare(b.getAttribute('data-classe'));
        });

        // Re-append sorted classes
        classes.forEach(classe => container.appendChild(classe));
    }

    function showReductionDetailsLink() {
        const selectedValue = document.getElementById('reduction-select').value;
        const link = document.getElementById('reduction-details-link');
        
        if (selectedValue) {
            link.style.display = 'inline';
        } else {
            link.style.display = 'none';
            closeReductionModal(); // Ferme le modal si aucun profil n'est sélectionné
        }
    }

    function openReductionModal() {
        const selectedValue = document.getElementById('reduction-select').value;

        if (selectedValue) {
            const reduction = reductions.find(r => r.CodeReduction == selectedValue);

            if (reduction) {
                document.getElementById('reduction-details').innerHTML = `
                    <p> Réduction sur Scolarité: ${reduction.Reduction_scolarite}</p>
                    <p> Réduction sur Arriérés: ${reduction.Reduction_arriere}</p>
                    <p> Réduction sur Frais 1: ${reduction.Reduction_frais1}</p>
                    <p> Réduction sur Frais 2: ${reduction.Reduction_frais2}</p>
                    <p> Réduction sur Frais 3: ${reduction.Reduction_frais3}</p>
                `;
            }
            
            document.getElementById('reduction-modal').style.display = 'block';
        }
    }

    function closeReductionModal() {
        document.getElementById('reduction-modal').style.display = 'none';
    }

    window.onload = function() {
        displayEleves(); // Affiche les élèves au chargement de la page
    }
    function applyReductions() {
    const selectedClass = document.getElementById('class-select').value;
    const selectedReduction = document.getElementById('reduction-select').value;
    const selectedEleves = [];

    const checkboxes = document.querySelectorAll('.individual-checkbox:checked');
    checkboxes.forEach(checkbox => {
        selectedEleves.push({
            nom: checkbox.closest('tr').children[1].textContent.trim(),
            sexe: checkbox.getAttribute('data-sexe')
        });
    });

    if (!selectedReduction || selectedEleves.length === 0) {
        alert("Veuillez sélectionner un profil de réduction et au moins un élève.");
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

</script>

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
</style>
