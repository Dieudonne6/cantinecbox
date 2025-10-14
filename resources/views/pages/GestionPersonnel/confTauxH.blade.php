@extends('layouts.master')

@section('content')

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

          .label-width {
              min-width: 150px;
          }
          .input-width {
              max-width: 120px;
          }
      </style>
      <div class="d-flex justify-content-between align-items-center mb-3">
          <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
              <i class="fas fa-arrow-left"></i> Retour
          </button>
         {{--  <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
              <i class="fas fa-external-link-alt"></i> Ouvrir en modale
          </button>--}}
      </div>
      <br>
      <br>                                   
    </div>

    <div class="card-body">

        <div class="tab-content col-md-12 mx-auto " id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-tauxH" role="tabpanel" aria-labelledby="nav-tauxH-tab">
                <nav>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                            <button class="nav-link active" id="nav-Hnormale-tab" data-bs-toggle="tab" data-bs-target="#nav-Hnormale" type="button" role="tab" aria-controls="nav-Hnormale" aria-selected="true">Configuration heures normales</button>
                            <button class="nav-link" id="nav-Hsup-tab" data-bs-toggle="tab" data-bs-target="#nav-Hsup" type="button" role="tab" aria-controls="nav-Hsup" aria-selected="false">Configuration heures sup.</button>
                        </div>
                        <!-- Sélecteur de profil + bouton -->
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <label class="form-label mb-0 me-2">Choisir un profil</label>
                                <select name="profil" id="profil" class="form-select d-inline-block w-auto" required>
                                    <option value="">--  --</option>
                                    @foreach($profils as $profil)
                                        <option value="{{ $profil->Numeroprofil }}"
                                            data-salaire="{{ $profil->SalaireBase }}"
                                            {{ old('profil', $agentData->PROFIL ?? '') == $profil->Numeroprofil ? 'selected' : '' }}>
                                            {{ $profil->NomProfil }}
                                        </option>
                                    @endforeach
                                </select>                                 
                            </div>
                            <!-- <button class="btn btn-secondary ms-2">Créer profil</button> -->
                        </div>
                    </div>
                    <br>
                </nav>

                <div class="tab-content" id="nav-tabContent1">

                    <div class="tab-pane fade show active" id="nav-Hnormale" role="tabpanel" aria-labelledby="nav-Hnormale-tab">
                        <form action="{{ route('profils.updateNormales') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="Numeroprofil" id="profil_id_normales">
                            <div class="row">
                                <div class="col-md-12 mx-auto grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                        
                                            <h4 class="card-title">Configuration heures normales</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <!-- Cycle I Jour -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-warning text-dark">Cycle I JOUR</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 12 + 40px); overflow-y: auto;">
                                                                        <!-- 1.75rem ~ hauteur d'une ligne table-sm, 40px pour le header -->
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead class="table-light sticky-top">
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle1 as $classe)
                                                                                <tr>
                                                                                    <td>{{ $classe->CODECLAS }}</td>
                                                                                    <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                
                                                        <!-- Cycle II Jour -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-warning text-dark">Cycle II JOUR</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 12 + 40px); overflow-y: auto;">
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead class="table-light sticky-top">
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle2 as $classe)
                                                                                <tr>
                                                                                    <td>{{ $classe->CODECLAS }}</td>
                                                                                    <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                
                                                    <div class="row">
                                                        <!-- Cycle I Soir -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-danger text-white">Cycle I SOIR</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 12 + 40px); overflow-y: auto;">
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead>
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle1 as $classes)
                                                                                    <tr>
                                                                                        <td>{{ $classes->CODECLAS }}</td>
                                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                
                                                        <!-- Cycle II Soir -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-danger text-white table-classe">Cycle II SOIR</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 12 + 40px); overflow-y: auto;">
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead>
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle2 as $classes)
                                                                                    <tr>
                                                                                        <td>{{ $classes->CODECLAS }}</td>
                                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                   
                                                <!-- Partie paramètres -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-body">
                                                            <!-- Salaire de base -->                 
                                                            <div class="col-md-6 d-flex align-items-center mb-3">
                                                                <label class="form-label me-3 mb-0 text-nowrap">Salaire de base</label>
                                                                <input type="number" id="salaire_base" name="salaire_base" class="col-md-6"
                                                                    value="{{ old('salaire_base', $agentData->SALBASE ?? '') }}">
                                                            </div>
                                
                                                            <!-- Saisie des taux -->
                                                            <div class="border p-3 rounded">
                                                                <h6>Saisie des taux horaires</h6>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_classe" >
                                                                    <label class="form-check-label" for="creation_normales_classe">Créer par classe</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_promotion">
                                                                    <label class="form-check-label" for="creation_normales_promotion">Créer par promotion</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_cycle">
                                                                    <label class="form-check-label" for="creation_normales_cycle">Créer par cycle</label>
                                                                </div>
                                                                <div class="form-check mb-0">
                                                                    <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_unique">
                                                                    <label class="form-check-label" for="creation_normales_unique">Créer unique</label>
                                                                </div>
                                                            </div>
                                
                                                            <!-- Boutons de modification -->
                                                            <!-- <div class="mb-3 d-flex flex-column gap-2">
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I JOUR</button>
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II JOUR</button>
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I SOIR</button>
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II SOIR</button>
                                                            </div> -->
                                
                                                            <!-- Champs des taux -->
                                                            <div class="block-cycle">
                                
                                                                <h6>Cours du jour</h6>
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                    <input type="number" name="TauxCycle1jour" class="form-control input-width col-md-6" value="0">
                                                                </div>
                                
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                    <input type="number" name="TauxCycle2jour" class="form-control input-width col-md-6" value="0">
                                                                </div>
                                
                                                                <h6>Cours du soir</h6>
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                    <input type="number" name="TauxCycle1Soir" class="form-control input-width col-md-6" value="0">
                                                                </div>
                                
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                    <input type="number" name="TauxCycle2Soir" class="form-control input-width col-md-6" value="0">
                                                                </div>
                                
                                                            </div>
                                
                                                            <!-- Taux unique -->
                                                            <div class="row mb-3 block-unique">
                                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                                    <label class="mb-0" style="width: 250px;">T unique (JOUR)</label>
                                                                    <input type="number" name="TauxUniqueJour" class="form-control" value="0" style="max-width: 100px;">
                                                                </div>
                                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                                    <label class="mb-0" style="width: 250px;">T unique (SOIR)</label>
                                                                    <input type="number" name="TauxUniqueSoir" class="form-control" value="0" style="max-width: 100px;">
                                                                </div>
                                                            </div>
                                                            <!-- Tableau promotion -->
                                                            <table class="table table-sm table-bordered block-promotion">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Promotion</th>
                                                                        <th>Taux Jour</th>
                                                                        <th>Taux Soir</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($codepromo as $codepromos)
                                                                    <tr>
                                                                        <td>{{ $codepromos }}</td>
                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4">Lancer la mise à jour</button>
                                        <br><br>
                                    </div>
                                
                                </div>
                                
                            </div>
                           
                        </form>                        
                    </div>

                    <div class="tab-pane fade" id="nav-Hsup" role="tabpanel" aria-labelledby="nav-Hsup-tab">
                        <form action="{{ route('profils.updateSup') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="Numeroprofil" id="profil_id_sup">
                            <div class="row">
                                <div class="col-md-12 mx-auto grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                        
                                            <h4 class="card-title">Configuration heures supplémentaires</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <!-- Cycle I HSup -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-warning text-dark">Cycle I Heures Supp.</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 24 + 40px); overflow-y: auto;">
                                                                        <!-- 1.75rem ~ hauteur d'une ligne table-sm, 40px pour le header -->
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead class="table-light sticky-top">
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle1 as $classecycle1)
                                                                                    <tr>
                                                                                        <td>{{ $classecycle1->CODECLAS }}</td>
                                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                
                                                        <!-- Cycle II HSup -->
                                                        <div class="col-md-6">
                                                            <div class="card mb-3">
                                                                <div class="card-header bg-warning text-dark">Cycle II Heures Supp.</div>
                                                                <div class="card-body p-0">
                                                                    <div class="table-responsive" style="max-height: calc(1.75rem * 24 + 40px); overflow-y: auto;">
                                                                        <table class="table table-sm table-bordered mb-0 table-classe">
                                                                            <thead class="table-light sticky-top">
                                                                                <tr><th>Classe</th><th>Taux</th></tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($classecycle2 as $classecycle2)
                                                                                    <tr>
                                                                                        <td>{{ $classecycle2->CODECLAS }}</td>
                                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody> 
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Partie paramètres -->
                                                <div class="col-md-6">
                                                    <div class="card mt-3">
                                                        <div class="card-body">
                                                            <!-- Saisie des taux -->
                                                            <div class="border p-3 rounded">
                                                                <h6>Saisie des taux horaires</h6>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_sup" id="creation_normales_classe" >
                                                                    <label class="form-check-label" for="creation_normales_classe">Créer par classe</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_sup" id="creation_normales_promotion">
                                                                    <label class="form-check-label" for="creation_normales_promotion">Créer par promotion</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" name="creation_sup" id="creation_normales_cycle">
                                                                    <label class="form-check-label" for="creation_normales_cycle">Créer par cycle</label>
                                                                </div>
                                                                <div class="form-check mb-0">
                                                                    <input type="radio" class="form-check-input" name="creation_sup" id="creation_normales_unique">
                                                                    <label class="form-check-label" for="creation_normales_unique">Créer unique</label>
                                                                </div>
                                                            </div>
                                
                                                            <!-- Boutons de modification -->
                                                            <!-- <div class="mb-3 d-flex flex-column gap-2">
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I</button>
                                                                <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II</button>
                                                            </div> -->
                                
                                                            <!-- Champs des taux -->
                                                            <div class="block-cycle">

                                                                <h6>Taux h. sup. par cycle</h6>
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                    <input type="number" name="TauxHeureSupC1" class="form-control input-width col-md-6" value="0">
                                                                </div>
                                                                <div class="col-md-6 d-flex align-items-center mb-2">
                                                                    <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                    <input type="number" name="TauxHeureSupC2" class="form-control input-width col-md-6" value="0">
                                                                </div>                          
                                                            </div>
                                
                                                            <!-- Taux unique -->
                                                            <div class="row mb-3 block-unique">
                                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                                    <label class="mb-0" style="width: 250px;">Taux h. sup. unique</label>
                                                                    <input type="number" name="TauxHeureSupUnique" class="form-control" value="0" style="max-width: 100px;">
                                                                </div>
                                                            </div>
                                
                                                            <!-- Tableau promotion -->
                                                            <table class="table table-sm table-bordered block-promotion">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Promotion</th>
                                                                        <th>Taux Jour</th>
                                                                        <th>Taux Soir</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($codepromo as $promo)
                                                                    <tr>
                                                                        <td>{{ $promo }}</td>
                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                   
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4">Lancer la mise à jour</button>
                                        <br><br>
                                    </div>                           
                                </div>
                            </div>                        
                        </form>                    
                                                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- script pour le changement automatique des champs de saisie du taux horaires -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        function toggleBlocks(modal, radioName) {
            let selected = modal.querySelector(`input[name='${radioName}']:checked`)?.id;

            // Cibler les blocs
            let cycleBlock   = modal.querySelector(".block-cycle");
            let uniqueBlock  = modal.querySelector(".block-unique");
            let promoBlock   = modal.querySelector(".block-promotion");
            let classTables  = modal.querySelectorAll(".table-classe input");

            // Tout désactiver au départ
            if (cycleBlock)  cycleBlock.querySelectorAll("input").forEach(inp => inp.disabled = true);
            if (uniqueBlock) uniqueBlock.querySelectorAll("input").forEach(inp => inp.disabled = true);
            if (promoBlock)  promoBlock.querySelectorAll("input").forEach(inp => inp.disabled = true);
            classTables.forEach(inp => inp.disabled = true);

            // Activer suivant le choix
            if (selected === "creation_normales_classe" || selected === "creation_sup_classe") {
                classTables.forEach(inp => inp.disabled = false);
            } 
            else if (selected === "creation_normales_cycle" || selected === "creation_sup_cycle") {
                if (cycleBlock) cycleBlock.querySelectorAll("input").forEach(inp => inp.disabled = false);
            } 
            else if (selected === "creation_normales_unique" || selected === "creation_sup_unique") {
                if (uniqueBlock) uniqueBlock.querySelectorAll("input").forEach(inp => inp.disabled = false);
            } 
            else if (selected === "creation_normales_promotion" || selected === "creation_sup_promotion") {
                if (promoBlock) promoBlock.querySelectorAll("input").forEach(inp => inp.disabled = false);
            }
        }

        // Gestion Heures normales
        document.querySelectorAll("#nav-Hnormale .card-body").forEach(function(modal){
            toggleBlocks(modal, "creation_normales");
            modal.querySelectorAll("input[name='creation_normales']").forEach(function(radio){
                radio.addEventListener("change", function(){
                    toggleBlocks(modal, "creation_normales");
                });
            });
        });  

        // Gestion Heures sup
        document.querySelectorAll("#nav-Hsup .card-body").forEach(function(modal){
            toggleBlocks(modal, "creation_sup");
            modal.querySelectorAll("input[name='creation_sup']").forEach(function(radio){
                radio.addEventListener("change", function(){
                    toggleBlocks(modal, "creation_sup");
                });
            });
        });

    });
</script>

<!-- Affichage autmatique du salaire -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilSelect = document.getElementById('profil');
        const salaireInput = document.getElementById('salaire_base');

        profilSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const salaire = selectedOption.getAttribute('data-salaire') || '';
            salaireInput.value = salaire; // Met à jour le champ salaire
        });
    });
</script>
<!-- Pour l'enrégistrement des taux horaires -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectProfil = document.getElementById('profil');
        const hiddenNormales = document.getElementById('profil_id_normales');
        const hiddenSup = document.getElementById('profil_id_sup');

        selectProfil.addEventListener('change', function() {
            hiddenNormales.value = this.value;
            hiddenSup.value = this.value;
        });

        // Initialisation au chargement
        hiddenNormales.value = selectProfil.value;
        hiddenSup.value = selectProfil.value;
    });
</script>

@endsection
