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
          <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
              <i class="fas fa-external-link-alt"></i> Ouvrir en modale
          </button>
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
                                <select class="form-select d-inline-block w-auto">
                                    <option value="1">lqt</option>
                                </select>
                            </div>
                            <button class="btn btn-secondary ms-2">Créer profil</button>
                        </div>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent1">

                    <div class="tab-pane fade show active" id="nav-Hnormale" role="tabpanel" aria-labelledby="nav-Hnormale-tab">
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
                                                                <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_classe" checked>
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
                            
                                                        <!-- Bouton final -->
                                                        <button class="btn btn-primary m-t-4">Lancer la mise à jour</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-Hsup" role="tabpanel" aria-labelledby="nav-Hsup-tab">
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
                                                                <input type="radio" class="form-check-input" name="creation_normales" id="creation_normales_classe" checked>
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
                            
                                                        <!-- Bouton final -->
                                                        <button class="btn btn-primary m-t-4">Lancer la mise à jour</button>
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

</div>

@endsection

<!-- Inclusion de la modale -->
@include('modals.confTauxHModal')

