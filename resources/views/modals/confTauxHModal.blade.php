<!-- Modal Configuration Taux Horaires -->
<div class="modal fade" id="confTauxHModal" tabindex="-1" aria-labelledby="confTauxHModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confTauxHModalLabel">Configuration des Taux Horaires</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <style>
                    .label-width {
                        min-width: 150px;
                    }
                    .input-width {
                        max-width: 120px;
                    }
                </style>

                <div class="tab-content col-md-12 mx-auto" id="nav-tabContent-modal">
                    <div class="tab-pane fade show active" id="nav-tauxH-modal" role="tabpanel" aria-labelledby="nav-tauxH-tab-modal">
                        <nav>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="nav nav-tabs" id="nav-tab1-modal" role="tablist">
                                    <button class="nav-link active" id="nav-Hnormale-tab-modal" data-bs-toggle="tab" data-bs-target="#nav-Hnormale-modal" type="button" role="tab" aria-controls="nav-Hnormale-modal" aria-selected="true">Configuration heures normales</button>
                                    <button class="nav-link" id="nav-Hsup-tab-modal" data-bs-toggle="tab" data-bs-target="#nav-Hsup-modal" type="button" role="tab" aria-controls="nav-Hsup-modal" aria-selected="false">Configuration heures sup.</button>
                                </div>
                                <!-- Sélecteur de profil + bouton -->
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <label class="form-label mb-0 me-2">Choisir un profil</label>
                                        <select class="form-select d-inline-block w-auto" id="profilSelect">
                                            <option value="1">lqt</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-secondary ms-2" id="creerProfilBtn">Créer profil</button>
                                </div>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent1-modal">
                            <!-- Onglet Heures Normales -->
                            <div class="tab-pane fade show active" id="nav-Hnormale-modal" role="tabpanel" aria-labelledby="nav-Hnormale-tab-modal">
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
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead class="table-light sticky-top">
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach(['3EM1','3EM2','3EM3','3EM4','3EM5','3EM6','3EM7','4EM1','4EM2','4EM3','4EM4','4EM5'] as $c)
                                                                                    <tr>
                                                                                        <td>{{ $c }}</td>
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
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead class="table-light sticky-top">
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach(['1EREA1','1EREA2','1EREB','1EREC','1ERED','2NDEA2-1','2NDEA2-2','2NDEB','2NDEC','2NDED','TLEA1'] as $c)
                                                                                    <tr>
                                                                                        <td>{{ $c }}</td>
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
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead>
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @for($i=1; $i<=12; $i++)
                                                                                    <tr>
                                                                                        <td colspan="2" class="text-center text-muted">Aucune donnée</td>
                                                                                    </tr>
                                                                                    @endfor
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                    
                                                            <!-- Cycle II Soir -->
                                                            <div class="col-md-6">
                                                                <div class="card mb-3">
                                                                    <div class="card-header bg-danger text-white">Cycle II SOIR</div>
                                                                    <div class="card-body p-0">
                                                                        <div class="table-responsive" style="max-height: calc(1.75rem * 12 + 40px); overflow-y: auto;">
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead>
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @for($i=1; $i<=12; $i++)
                                                                                    <tr>
                                                                                        <td colspan="2" class="text-center text-muted">Aucune donnée</td>
                                                                                    </tr>
                                                                                    @endfor
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
                                                                    <input type="number" class="form-control col-md-6" value="10000">
                                                                </div>
                    
                                                                <!-- Saisie des taux -->
                                                                <div class="border p-3 rounded">
                                                                    <h6>Saisie des taux horaires</h6>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" name="creation_normales_modal" id="creation_normales_classe_modal" checked>
                                                                        <label class="form-check-label" for="creation_normales_classe_modal">Créer par classe</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" name="creation_normales_modal" id="creation_normales_promotion_modal">
                                                                        <label class="form-check-label" for="creation_normales_promotion_modal">Créer par promotion</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" name="creation_normales_modal" id="creation_normales_cycle_modal">
                                                                        <label class="form-check-label" for="creation_normales_cycle_modal">Créer par cycle</label>
                                                                    </div>
                                                                    <div class="form-check mb-0">
                                                                        <input type="radio" class="form-check-input" name="creation_normales_modal" id="creation_normales_unique_modal">
                                                                        <label class="form-check-label" for="creation_normales_unique_modal">Créer unique</label>
                                                                    </div>
                                                                </div>
                    
                                                                <!-- Boutons de modification -->
                                                                <div class="mb-3 d-flex flex-column gap-2">
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I JOUR</button>
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II JOUR</button>
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I SOIR</button>
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II SOIR</button>
                                                                </div>
                    
                                                                <!-- Champs des taux -->
                                                                <div>
                                                                    <h6>Cours du jour</h6>
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>
                    
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>
                    
                                                                    <h6>Cours du soir</h6>
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>
                    
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>
                                                                </div>
                    
                                                                <!-- Taux unique -->
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6 d-flex align-items-center gap-2">
                                                                        <label class="mb-0" style="width: 250px;">Taux unique (JOUR)</label>
                                                                        <input type="number" class="form-control" value="0" style="max-width: 100px;">
                                                                    </div>
                                                                    <div class="col-md-6 d-flex align-items-center gap-2">
                                                                        <label class="mb-0" style="width: 250px;">Taux unique (SOIR)</label>
                                                                        <input type="number" class="form-control" value="0" style="max-width: 100px;">
                                                                    </div>
                                                                </div>
                    
                                                                <!-- Tableau promotion -->
                                                                <table class="table table-sm table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Promotion</th>
                                                                            <th>Taux Jour</th>
                                                                            <th>Taux Soir</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Undefined</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Première</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Terminale</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Seconde</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Heures Supplémentaires -->
                            <div class="tab-pane fade" id="nav-Hsup-modal" role="tabpanel" aria-labelledby="nav-Hsup-tab-modal">
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
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead class="table-light sticky-top">
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach(['3EM1','3EM2','3EM3','3EM4','3EM5','3EM6','3EM7','4EM1','4EM2','4EM3','4EM4','4EM5'] as $c)
                                                                                    <tr>
                                                                                        <td>{{ $c }}</td>
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
                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                <thead class="table-light sticky-top">
                                                                                    <tr><th>Classe</th><th>Taux</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach(['1EREA1','1EREA2','1EREB','1EREC','1ERED','2NDEA2-1','2NDEA2-2','2NDEB','2NDEC','2NDED','TLEA1'] as $c)
                                                                                    <tr>
                                                                                        <td>{{ $c }}</td>
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
                                                                        <input type="radio" class="form-check-input" name="creation_hsup_modal" id="creation_hsup_classe_modal" checked>
                                                                        <label class="form-check-label" for="creation_hsup_classe_modal">Créer par classe</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" name="creation_hsup_modal" id="creation_hsup_promotion_modal">
                                                                        <label class="form-check-label" for="creation_hsup_promotion_modal">Créer par promotion</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" name="creation_hsup_modal" id="creation_hsup_cycle_modal">
                                                                        <label class="form-check-label" for="creation_hsup_cycle_modal">Créer par cycle</label>
                                                                    </div>
                                                                    <div class="form-check mb-0">
                                                                        <input type="radio" class="form-check-input" name="creation_hsup_modal" id="creation_hsup_unique_modal">
                                                                        <label class="form-check-label" for="creation_hsup_unique_modal">Créer unique</label>
                                                                    </div>
                                                                </div>
                    
                                                                <!-- Boutons de modification -->
                                                                <div class="mb-3 d-flex flex-column gap-2">
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle I</button>
                                                                    <button class="btn btn-outline-secondary btn-sm mb-1 w-100">Modifier taux horaire classe sélectionné Cycle II</button>
                                                                </div>
                    
                                                                <!-- Champs des taux -->
                                                                <div>
                                                                    <h6>Taux h. sup. par cycle</h6>
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 1</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>
                                                                    <div class="col-md-6 d-flex align-items-center mb-2">
                                                                        <label class="me-3 mb-0 text-nowrap label-width">Taux horaire cycle 2</label>
                                                                        <input type="number" class="form-control input-width col-md-6" value="0">
                                                                    </div>                          
                                                                </div>
                    
                                                                <!-- Taux unique -->
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6 d-flex align-items-center gap-2">
                                                                        <label class="mb-0" style="width: 250px;">Taux h. sup. unique</label>
                                                                        <input type="number" class="form-control" value="0" style="max-width: 100px;">
                                                                    </div>
                                                                </div>
                    
                                                                <!-- Tableau promotion -->
                                                                <table class="table table-sm table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Promotion</th>
                                                                            <th>Taux Jour</th>
                                                                            <th>Taux Soir</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Undefined</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Première</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Terminale</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Seconde</td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                            <td><input type="number" class="form-control form-control-sm" value="0"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="lancerMiseAJourModal">Lancer la mise à jour</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton de mise à jour dans la modale
    document.getElementById('lancerMiseAJourModal')?.addEventListener('click', function() {
        // Ici vous pouvez ajouter la logique de sauvegarde
        alert('Mise à jour lancée depuis la modale !');
        // Optionnel : fermer la modale après la mise à jour
        // bootstrap.Modal.getInstance(document.getElementById('confTauxHModal')).hide();
    });

    // Gestion du bouton créer profil
    document.getElementById('creerProfilBtn')?.addEventListener('click', function() {
        alert('Fonctionnalité de création de profil à implémenter');
    });
});
</script>
