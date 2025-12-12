@extends('layouts.master')

@section('content')
<div class="container-fluid p-0" style="max-width: 1000px;">
    <div class="card border-0 shadow-sm" style="background-color: #96C8F1; font-family: Arial, sans-serif;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Enregistrement de l'emploi du temps</h4>
        </div>

        <div class="card-body p-3" style="background-color: #96C8F1;">
            <div class="row g-3">
                <!-- Colonne gauche : Sélectionner un groupe + Table des classes -->
                <div class="col-md-5">
                    <!-- Sélectionner un groupe -->
                    <div class="d-flex align-items-center mb-2">
                        <label for="groupe" class="form-label me-2" style="width: 160px;">Sélectionner un groupe</label>
                        <select id="groupe" class="form-select form-select-sm" style="width: 180px;">
                            <option value="">Choisir...</option>
                                @foreach ($classesg as $classeg)
                                    <option value="{{ $classeg->LibelleGroupe }}">
                                        {{ $classeg->LibelleGroupe }}</option>
                                @endforeach
                        </select>
                    </div>


                    <!-- Table des classes -->
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: bold;">Classes disponibles</label>
                        <div class="table-container" style="height: 300px; overflow-y: auto; border: 2px solid #000; background-color: #E0F7FA;">
                            <table class="table table-striped table-sm mb-0" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 80%; padding: 5px; font-weight: bold; text-align: center; 
                                                   background-color: #B3E5FC; 
                                                   position: sticky; top: 0; z-index: 10;">
                                            CODECLAS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="classesTableBody">
                                    <tr>
                                        <td style="padding: 5px; text-align: center; color: #666;">Sélectionnez un groupe pour voir les classes</td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>
                    </div>                    
                </div>

                <!-- Colonne droite : Jour, Matière, Plage horaire, Salle, Professeur -->
                <div class="col-md-7" id="rightColumn" style="opacity: 0.5; pointer-events: none;">
                    <!-- Sélectionner un jour -->
                    <div class="d-flex align-items-center mb-3">
                        <label for="jour" class="form-label me-2" style="width: 160px;">Sélectionner un jour</label>
                        <select id="jour" class="form-select form-select-sm" style="width: 180px;">
                            <option selected>Lundi</option>
                            <option>Mardi</option>
                            <option>Mercredi</option>
                            <option>Jeudi</option>
                            <option>Vendredi</option>
                            <option>Samedi</option>
                        </select>
                    </div>

                    <!-- Sélectionner la matière -->
                    <div class="d-flex align-items-center mb-3">
                        <label for="matiere" class="form-label me-2" style="width: 160px;">Sélectionner la matière</label>
                        <select id="matiere" name="matiere" class="form-select form-select-sm" style="width: 180px;">
                            <option value="">Choisir...</option>
                            @foreach ($matieres as $matiereOption)
                                <option value="{{ $matiereOption->CODEMAT }}"
                                    {{ (old('matiere', $matiere ?? '') == $matiereOption->CODEMAT) ? 'selected' : '' }}>
                                    {{ $matiereOption->LIBELMAT }}
                                </option>
                            @endforeach
                        </select>
                    </div>                    

                    <!-- Plage horaire -->
                    <div class="d-flex align-items-center mb-3">
                        <label class="form-label me-2" style="width: 160px;">Plage horaire de</label>
                        <select id="heureDebut" class="form-select form-select-sm me-2" style="width: 100px;">
                            <option selected>07:00</option>
                            <option>08:00</option>
                            <option>09:00</option>
                            <option>10:00</option>
                            <option>11:00</option>
                            <option>13:00</option>
                            <option>14:00</option>
                            <option>15:00</option>
                            <option>16:00</option>
                            <option>17:00</option>
                            <option>18:00</option>
                        </select>
                        <span style="margin: 0 5px;">à</span>
                        <select id="heureFin" class="form-select form-select-sm" style="width: 100px;">
                            <option selected>08:00</option>
                            <option>09:00</option>
                            <option>10:00</option>
                            <option>11:00</option>
                            <option>12:00</option>
                            <option>13:00</option>
                            <option>14:00</option>
                            <option>16:00</option>
                            <option>17:00</option>
                            <option>18:00</option>
                            <option>19:00</option>
                        </select>
                    </div>

                    <!-- Choisir la salle -->
                    <div class="d-flex align-items-center mb-3">
                        <label for="salle" class="form-label me-2" style="width: 160px;">Choisir la salle</label>
                        <select id="salle" name="salle" class="form-select form-select-sm" style="width: 180px;">
                            <option selected disabled>Choisir...</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->CODESALLE }}">{{ $salle->CODESALLE }}</option>
                            @endforeach
                        </select>
                    </div>                    

                    <!-- Choisir un professeur -->
                    <div class="d-flex align-items-center mb-3">
                        <label for="professeur" class="form-label me-2" style="width: 160px;">Choisir un professeur</label>
                        <select id="professeur" class="form-select form-select-sm" style="width: 180px;">
                            <option selected>Choisir...</option>
                            @foreach($agents as $agent)
                            <option value="{{ $agent->MATRICULE }}">
                                {{ $agent->NOM }} {{ $agent->PRENOM }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                    <!-- Boutons Enregistrer et Voir l'emploi du temps -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" id="btnEnregistrer" class="btn btn-success btn-sm me-2" disabled style="width: 140px; font-weight: bold; background-color: #B2EBF2; color: #000; border: 1px solid #000;">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                        <button type="button" id="btnVoirEmploi" class="btn btn-info btn-sm" disabled style="width: 180px; font-weight: bold; background-color: #FFE082; color: #000; border: 1px solid #000;">
                            <i class="fas fa-calendar-alt me-1"></i> Voir l'emploi du temps
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JS pour la sélection en cascade et gestion des formulaires -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupeSelect = document.getElementById('groupe');
    const classesTableBody = document.getElementById('classesTableBody');
    const rightColumn = document.getElementById('rightColumn');
    const btnEnregistrer = document.getElementById('btnEnregistrer');
    const btnVoirEmploi = document.getElementById('btnVoirEmploi');
    
    // Variables pour stocker l'état
    let selectedGroupe = '';
    let selectedClasse = '';
    let availableClasses = [];

    // Fonction pour activer/désactiver la colonne droite
    function toggleRightColumn(enable) {
        if (enable) {
            rightColumn.style.opacity = '1';
            rightColumn.style.pointerEvents = 'auto';
            btnEnregistrer.disabled = false;
            btnVoirEmploi.disabled = false;
        } else {
            rightColumn.style.opacity = '0.5';
            rightColumn.style.pointerEvents = 'none';
            btnEnregistrer.disabled = true;
            btnVoirEmploi.disabled = true;
        }
    }

    // Fonction pour mettre à jour le tableau des classes
    function updateClassesTable(classes) {
        classesTableBody.innerHTML = '';
        
        if (classes.length === 0) {
            classesTableBody.innerHTML = '<tr><td style="padding: 5px; text-align: center; color: #666;">Aucune classe trouvée pour ce groupe</td></tr>';
            return;
        }

        classes.forEach(function(classe) {
            const row = document.createElement('tr');
            row.innerHTML = `<td style="padding: 5px; cursor: pointer;" data-classe="${classe.CODECLAS}">${classe.CODECLAS}</td>`;
            
            // Ajouter un événement de clic pour sélectionner la classe
            row.addEventListener('click', function() {
                // Retirer la sélection précédente
                document.querySelectorAll('#classesTableBody tr').forEach(tr => {
                    tr.style.backgroundColor = '';
                    tr.style.fontWeight = '';
                });
                
                // Appliquer la nouvelle sélection
                row.style.backgroundColor = '#007bff';
                row.style.color = 'white';
                row.style.fontWeight = 'bold';
                
                // Mettre à jour la classe sélectionnée
                selectedClasse = classe.CODECLAS;
                
                // Activer la colonne droite
                toggleRightColumn(true);
            });
            
            classesTableBody.appendChild(row);
        });
    }

    // Fonction pour charger les classes par groupe
    function loadClassesByGroupe(libelleGroupe) {
        if (!libelleGroupe) {
            classesTableBody.innerHTML = '<tr><td style="padding: 5px; text-align: center; color: #666;">Sélectionnez un groupe pour voir les classes</td></tr>';
            toggleRightColumn(false);
            return;
        }

        // Afficher un message de chargement
        classesTableBody.innerHTML = '<tr><td style="padding: 5px; text-align: center; color: #666;">Chargement des classes...</td></tr>';
        
        // Faire la requête AJAX
        fetch(`{{ route('get.classes.by.groupe') }}?groupe=${encodeURIComponent(libelleGroupe)}`)
            .then(response => response.json())
            .then(data => {
                availableClasses = data;
                
                // Mettre à jour le tableau
                updateClassesTable(data);
                
                // Réinitialiser la sélection
                selectedClasse = '';
                toggleRightColumn(false);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des classes:', error);
                classesTableBody.innerHTML = '<tr><td style="padding: 5px; text-align: center; color: #ff0000;">Erreur lors du chargement des classes</td></tr>';
                toggleRightColumn(false);
            });
    }

    // Événement de changement de groupe
    groupeSelect.addEventListener('change', function() {
        selectedGroupe = this.value;
        loadClassesByGroupe(selectedGroupe);
    });

    // Fonction pour enregistrer le cours
    function enregistrerCours() {
        // Récupérer toutes les valeurs du formulaire
        const jour = document.getElementById('jour').value;
        const matiere = document.getElementById('matiere').value;
        const heureDebut = document.getElementById('heureDebut').value;
        const heureFin = document.getElementById('heureFin').value;
        const salle = document.getElementById('salle').value;
        const professeur = document.getElementById('professeur').value;

        // Validation côté client
        if (!selectedClasse) {
            alert('Veuillez sélectionner une classe.');
            return;
        }

        if (!matiere) {
            alert('Veuillez sélectionner une matière.');
            return;
        }

        if (!salle) {
            alert('Veuillez sélectionner une salle.');
            return;
        }

        if (!professeur || professeur === 'Choisir...') {
            alert('Veuillez sélectionner un professeur.');
            return;
        }

        // Désactiver le bouton pendant l'enregistrement
        btnEnregistrer.disabled = true;
        btnEnregistrer.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enregistrement...';

        // Préparer les données pour FormData (plus compatible avec Laravel)
        const formData = new FormData();
        formData.append('classe', selectedClasse);
        formData.append('jour', jour);
        formData.append('matiere', matiere);
        formData.append('heureDebut', heureDebut);
        formData.append('heureFin', heureFin);
        formData.append('salle', salle);
        formData.append('professeur', professeur);
        formData.append('_token', '{{ csrf_token() }}');

        // Envoyer la requête AJAX
        fetch('{{ route("store.cours") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cours enregistré avec succès !');
                
                // Réinitialiser le formulaire
                document.getElementById('matiere').value = '';
                document.getElementById('salle').value = '';
                document.getElementById('professeur').value = 'Choisir...';
                
                // Optionnel : recharger les données ou mettre à jour l'affichage
                console.log('Cours créé:', data.data);
            } else {
                alert('Erreur: ' + data.message);
                if (data.errors) {
                    console.error('Erreurs de validation:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'enregistrement:', error);
            alert('Erreur lors de l\'enregistrement du cours. Veuillez réessayer.');
        })
        .finally(() => {
            // Réactiver le bouton
            btnEnregistrer.disabled = false;
            btnEnregistrer.innerHTML = '<i class="fas fa-save me-1"></i> Enregistrer';
        });
    }

    // Événement de clic sur le bouton Enregistrer
    btnEnregistrer.addEventListener('click', enregistrerCours);

    // Événement de clic sur le bouton Voir l'emploi du temps
    btnVoirEmploi.addEventListener('click', function() {
        if (selectedClasse) {
            // Rediriger vers la page d'emploi du temps général avec la classe sélectionnée
            window.location.href = '{{ route("emploidutempsgeneral") }}?classe=' + encodeURIComponent(selectedClasse);
        } else {
            alert('Veuillez sélectionner une classe.');
        }
    });

    // Initialiser l'état
    toggleRightColumn(false);
});
</script>

@endsection