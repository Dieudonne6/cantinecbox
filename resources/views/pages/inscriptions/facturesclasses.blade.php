@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="card">
 
    </div>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .table-container {
                width: 100%;
                overflow-x: auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
            th, td {
                padding: 8px 12px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #f4f4f4;
                position: sticky;
                top: 0;
                z-index: 1;
            }
            @media (max-width: 600px) {
                table, thead, tbody, th, td, tr {
                    display: block;
                }
                th {
                    top: auto;
                    background-color: transparent;
                }
                tr {
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 16px;
                }
                td {
                    text-align: right;
                    position: relative;
                    padding-left: 50%;
                }
                td::before {
                    content: attr(data-label);
                    position: absolute;
                    left: 0;
                    width: 50%;
                    padding-left: 12px;
                    font-weight: bold;
                    text-align: left;
                    background-color: #f4f4f4;
                    border-right: 1px solid #ddd;
                }
            }
        </style>
    
    <div class="table-container">
        <table id="myTable">
            <thead>
                <tr>
                    <th>Groupe Pédagogique</th>
                    <th>Libellé</th>
                    <th>Enseignement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Groupe Pédagogique">Groupe 1</td>
                    <td data-label="Libellé">Mathématiques</td>
                    <td data-label="Enseignement">M. Dupont</td>
                    <td data-label="Actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Facture
                        </button>
                    </td>

                </tr>
                <tr>
                    <td data-label="Groupe Pédagogique">Groupe 2</td>
                    <td data-label="Libellé">Sciences</td>
                    <td data-label="Enseignement">Mme. Martin</td>
                    <td data-label="Actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Facture
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
</div>

@endsection
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%;">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enrégistrement des classes</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                  <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Scolarité</button>
                  <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Echéancier de paiement</button>
                  <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false" disabled>Disabled</button>
                  <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                  <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
                </div>
                <div class="tab-content" id="v-pills-tabContent">
                  <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">

                    <div class="form-group row mt-1">
                        <label for="lieu" class="col-sm-4 col-form-label">Nom de la classe</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="lieu" placeholder=" text ">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="lieu" placeholder=" text ">
                        </div>
                    </div>
        
                    <div class="row">
                            <div class="form-group row mt-1">
                                <label class="col-sm-3 col-form-label" for="lieu"><button>Défaut</button></label>
                                <div class="col-sm-7">
                                    <table>
                                        <tr>
                                            <th>Nouveaux</th>
                                            <th>Anciens</th>
                                        </tr>
                                        <tr>
                                            <td>Ligne 1</td>
                                            <td>Cellule 2</td>
                                        </tr>
                                        <tr>
                                            <td>Cellule 1</td>
                                            <td>Ligne 2</td>
                                        </tr>
                                        <tr>
                                            <td>Cellule 1</td>
                                            <td>Ligne 3</td>
                                        </tr>
                                        <tr>
                                            <td>Cellule 1</td>
                                            <td>Ligne 4</td>
                                        </tr>
                                        <tr>
                                            <td>Cellule 1</td>
                                            <td>Ligne 5</td>
                                        </tr>
                                        <tr>
                                            <td>Cellule 1</td>
                                            <td>Ligne 6</td>
                                        </tr>
                                    </table>
                                </div>
                            
                        </div>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                    <div class="row">
                      <div class="card">
                        <div class="card-body">
                          <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                              L'échéancier prend en compte les frais de scolarité seulement
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flexCheckChecked" checked>
                            <label class="form-check-label" for="flexCheckChecked">
                              L'échéancier prend en compte tous les frais [195000] et [190000]
                            </label>
                          </div>
                        </div>
                      </div>
                  
                      <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <div class="form-group row mt-1">
                              <label for="nbEcheance" class="col-sm-4 col-form-label">Nb. échéance</label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control" id="nbEcheance" placeholder=" ">
                              </div>
                            </div>
                            <div class="form-group row mt-1">
                              <label for="dateDebut" class="col-sm-6 col-form-label">Date de début de paiement</label>
                              <div class="col-sm-5">
                                <input type="date" class="form-control" id="dateDebut" placeholder="jj/mm/aa">
                              </div>
                            </div>
                            <div class="form-group row mt-1">
                              <label for="periodicite" class="col-sm-3 col-form-label">Périodicité</label>
                              <div class="col-sm-2">
                                <input type="text" class="form-control" id="periodicite" placeholder=" ">
                              </div>
                              <label class="col-sm-3 col-form-label">Mois</label>
                              <label class="col-sm-4 col-form-label">>= 7 pour exprimer jours</label>
                            </div>
                            <div class="table-responsive" style="height: 100px; overflow: auto;">
                                <table class="table table-dark">
                                  <thead style="position: sticky; top: 0; z-index: 1; background: #10bebe;">
                                    <tr>
                                      <th>Tranche</th>
                                      <th>% nouv</th>
                                      <th>% anc</th>
                                      <th>Montant</th>
                                      <th>Montant2</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="table-active">
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tbody>
                                  <tfoot style="position: sticky; bottom: 0; z-index: 1; background: #10bebe;">
                                    <tr>
                                      <td colspan="1" class="table-active"></td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                          </div>
                        </div>
                      </div>
                  
                      <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h5 style="text-align: center";>Nouveaux élèves</h5>
                            <div class="table-responsive" style="height: 100px; overflow: auto;">
                                <table class="table table-dark">
                                  <thead style="position: sticky; top: 0; z-index: 1; background: #343a40;">
                                    <tr>
                                      <th>Tranche</th>
                                      <th>% nouv</th>
                                      <th>% anc</th>
                                      <th>Montant</th>
                                      <th>Montant2</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="table-active">
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tbody>
                                  <tfoot style="position: sticky; bottom: 0; z-index: 1; background: #343a40;">
                                    <tr>
                                      <td colspan="1" class="table-active"></td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                          </div>
                          <div class="card-body">
                            <h5 style="text-align: center;">Anciens élèves</h5>
                            <div class="table-responsive" style="height: 100px; overflow: auto;">
                                <table class="table table-dark">
                                  <thead style="position: sticky; top: 0; z-index: 1; background: #343a40;">
                                    <tr>
                                      <th>Tranche</th>
                                      <th>% nouv</th>
                                      <th>% anc</th>
                                      <th>Montant</th>
                                      <th>Montant2</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="table-active">
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                    <tr>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>130</td>
                                      <td>02024</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tbody>
                                  <tfoot style="position: sticky; bottom: 0; z-index: 1; background: #343a40;">
                                    <tr>
                                      <td colspan="1" class="table-active"></td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                      <td>190 000</td>
                                    </tr>
                                  </tfoot>
                                </table>
                            </div>
                            <button type="button" style="" class="btn btn-secondary mt-2">Créer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Valider & Fermer</button>
        </div>
      </div>
    </div>
  </div>