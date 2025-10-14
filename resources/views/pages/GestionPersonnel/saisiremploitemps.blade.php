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
                            <option selected>Choisir...</option>
                                @foreach ($classesg as $classeg)
                                    <option value="{{ $classeg->LibelleGroupe }}" 
                                        {{ $classeg->LibelleGroupe == 'Ens. General' ? 'selected' : '' }}>
                                        {{ $classeg->LibelleGroupe }}</option>
                                @endforeach
                        </select>
                    </div>

                    <!-- Table des classes -->
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: bold;">Table des classes</label>
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
                                    @foreach($classes as $classe)
                                    <tr>
                                        <td style="padding: 5px;">{{ $classe->CODECLAS }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>                                
                            </table>
                        </div>
                    </div>                    
                </div>

                <!-- Colonne droite : Jour, Matière, Plage horaire, Salle, Professeur -->
                <div class="col-md-7">
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

                    <!-- Bouton Enregistrer -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-success btn-sm" style="width: 120px; font-weight: bold; background-color: #B2EBF2; color: #000; border: 1px solid #000;">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JS pour ajouter/supprimer des lignes -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter une nouvelle ligne
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-row') || e.target.closest('.add-row')) {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td style="padding: 5px;"><input type="text" class="form-control form-control-sm" style="border: none; background: transparent;"></td>
                <td style="padding: 5px; text-align: center;">
                    <button type="button" class="btn btn-sm btn-success add-row" title="Ajouter une ligne">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Supprimer la ligne">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            document.getElementById('classesTableBody').appendChild(newRow);
        }
        
        // Supprimer une ligne
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('#classesTableBody tr').length > 1) {
                row.remove();
            } else {
                alert('Vous ne pouvez pas supprimer la dernière ligne.');
            }
        }
    });
});
</script>

@endsection