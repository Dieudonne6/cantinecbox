@extends('layouts.master')
@section('content')


    <style>
        .radio-inline {
            display: inline-block;
            margin-right: 15px;
        }
    </style>

    <div class="container card mt-3">
        <div class="row">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <select class="form-select mb-2" aria-label="Small select example">
                        <option selected>Fautes et sanctions</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-select mb-2" aria-label="Small select example">
                        <option selected>Sélectionner un groupe</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plage</h5>
                            <form>
                                <div class="radio-inline">
                                    <input type="radio" id="option1" name="choixPlage" value="option1">
                                    <label for="option1">Matinée</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option2" name="choixPlage" value="option2">
                                    <label for="option2">Soirée</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option3" name="choixPlage" value="option3">
                                    <label for="option3">Journée</label>
                                </div>
                            </form>
                            <div class="form-group row mt-1">
                                <label for="heureDebut" class="col-sm-4 col-form-label">Plage Horaire</label>
                                <div class="col-sm-3">
                                    <input type="time" class="form-control" id="heureDebut">
                                </div>
                                <label for="heureFin" class="col-sm-1 col-form-label">à</label>
                                <div class="col-sm-3">
                                    <input type="time" class="form-control" id="heureFin">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="radio-inline">
                                    <input type="radio" id="option4" name="choixPeriode" value="option1">
                                    <label for="option4">Définir une période</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option5" name="choixPeriode" value="option2">
                                    <label for="option5">Trimestre/Semestre</label>
                                </div>
                            </form>
                            <div class="form-group row mt-1">
                                <label for="periodeDebut" class="col-sm-3 col-form-label">Période du</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="periodeDebut">
                                </div>
                                <label for="periodeFin" class="col-sm-2 col-form-label">au</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="periodeFin">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="trimestre" class="col-sm-4 col-form-label">Trimestre</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="trimestre">
                                        <option>1er Trimestre</option>
                                        <option>2ème Trimestre</option>
                                        <option>3ème Trimestre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <a>Traitement impression</a>
                            <form>
                                <div class="radio-inline">
                                    <input type="radio" id="option6" name="choixImpression" value="global">
                                    <label for="option6">Global</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option7" name="choixImpression" value="singulier">
                                    <label for="option7">Singulier</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="card grid-margin stretch-card">
            
            <div class="card-body">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                
                        .table-container {
                            width: 100%;
                            overflow-x: auto;
                        }
                
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                
                        th {
                            background-color: #f2f2f2;
                        }
                
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                
                        tr:hover {
                            background-color: #ddd;
                        }
                
                        @media screen and (max-width: 600px) {
                            table, thead, tbody, th, td, tr {
                                display: block;
                            }
                
                            thead tr {
                                display: none;
                            }
                
                            tr {
                                margin-bottom: 15px;
                            }
                
                            td {
                                text-align: right;
                                padding-left: 50%;
                                position: relative;
                            }
                
                            td:before {
                                content: attr(data-label);
                                position: absolute;
                                left: 0;
                                width: 50%;
                                padding-left: 15px;
                                font-weight: bold;
                                text-align: left;
                            }
                        }
                    </style>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Classe</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Classe">1A</td>
                                    <td data-label="Nom">Dupont</td>
                                    <td data-label="Prénom">Jean</td>
                                </tr>
                                <tr>
                                    <td data-label="Classe">2B</td>
                                    <td data-label="Nom">Martin</td>
                                    <td data-label="Prénom">Marie</td>
                                </tr>
                                <tr>
                                    <td data-label="Classe">3C</td>
                                    <td data-label="Nom">Durand</td>
                                    <td data-label="Prénom">Paul</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>               
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="row">
                        <div class="card-body col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="">Table des fautes/Sanction</button>
                                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Nouvelle faute/Sanction</button>
                                    <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false" disabled>Modifier sanction...</button>
                                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Supprimer sanction</button>
                                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Sanction collective...</button>
                                    <button class="nav-link" id="v-pills-setting-tab" data-bs-toggle="pill" data-bs-target="#v-pills-setting" type="button" role="tab" aria-controls="v-pills-setting" aria-selected="false">Imprimer</button>
                                </div>
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                                        <!-- Table des fautes/Sanction -->
                                        <style>
                                            /* Basic styling for responsiveness */
                                            table {
                                                width: 100%;
                                                border-collapse: collapse;
                                                margin-bottom: 1em;
                                            }
                                            th, td {
                                                border: 1px solid #ddd;
                                                padding: 8px;
                                                text-align: left;
                                            }
                                            th {
                                                background-color: #f2f2f2;
                                            }
                                            @media only screen and (max-width: 600px) {
                                                /* Responsive adjustments for small screens */
                                                table {
                                                    border: 0;
                                                }
                                                table thead {
                                                    display: none;
                                                }
                                                table tbody {
                                                    display: block;
                                                    width: auto;
                                                    position: relative;
                                                    overflow-x: auto;
                                                    -webkit-overflow-scrolling: touch;
                                                    white-space: nowrap;
                                                }
                                                table tbody tr {
                                                    display: inline-block;
                                                    vertical-align: top;
                                                }
                                                table tbody tr td {
                                                    display: block;
                                                    text-align: left;
                                                }
                                            }
                                        </style>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Libellé faute</th>
                                                    <th>Sanction prévue</th>
                                                    <th>En heure</th>
                                                    <th>En point</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Exemple de faute</td>
                                                    <td>Blâme</td>
                                                    <td>2</td>
                                                    <td>10</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary">Modifier</button>
                                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                                    </td>
                                                </tr>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Nouveau
                                        </button>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">...</div>
                                    <div class="tab-pane fade" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">...</div>
                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">...</div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">...</div>
                                    <div class="tab-pane fade" id="v-pills-setting" role="tabpanel" aria-labelledby="v-pills-setting-tab" tabindex="0">...</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body col-md-8">
                            <!-- Second Table Section -->
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                }
            
                                .table-container {
                                    overflow-x: auto;
                                    margin: 20px;
                                }
            
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
            
                                th, td {
                                    padding: 12px;
                                    text-align: left;
                                    border: 1px solid #ddd;
                                }
            
                                th {
                                    background-color: #f2f2f2;
                                }
            
                                tr:nth-child(even) {
                                    background-color: #f9f9f9;
                                }
            
                                /* Responsive styling */
                                @media screen and (max-width: 600px) {
                                    table, thead, tbody, th, td, tr {
                                        display: block;
                                    }
            
                                    thead tr {
                                        position: absolute;
                                        top: -9999px;
                                        left: -9999px;
                                    }
            
                                    tr {
                                        border: 1px solid #ccc;
                                        margin-bottom: 5px;
                                    }
            
                                    td {
                                        border: none;
                                        border-bottom: 1px solid #eee;
                                        position: relative;
                                        padding-left: 50%;
                                    }
            
                                    td:before {
                                        position: absolute;
                                        top: 50%;
                                        left: 6px;
                                        transform: translateY(-50%);
                                        white-space: nowrap;
                                        font-weight: bold;
                                    }
            
                                    td:nth-of-type(1):before { content: "Date"; }
                                    td:nth-of-type(2):before { content: "Faute"; }
                                    td:nth-of-type(3):before { content: "Sanction"; }
                                    td:nth-of-type(4):before { content: "Heure"; }
                                    td:nth-of-type(5):before { content: "Col"; }
                                }
                            </style>
            
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Faute</th>
                                            <th>Sanction</th>
                                            <th>Heure</th>
                                            <th>Col</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2024-06-01</td>
                                            <td>Exemple de faute 1</td>
                                            <td>Exemple de sanction 1</td>
                                            <td>10:00</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>2024-06-02</td>
                                            <td>Exemple de faute 2</td>
                                            <td>Exemple de sanction 2</td>
                                            <td>11:00</td>
                                            <td>2</td>
                                        </tr>
                                        <!-- Ajoutez d'autres lignes selon vos besoins -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Nouveau type de faute</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                <form class="forms-sample">
                  <div class="form-group row">
                    <label for="exampleInputUsername2" class="col-sm-4 col-form-label" _msttexthash="564538" _msthash="108">Faute</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Faute" _mstplaceholder="113997" _msthash="109">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputEmail2" class="col-sm-4 col-form-label" _msttexthash="564538" _msthash="110">Sanction Indicative</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Sanction" _mstplaceholder="58058" _msthash="111">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputMobile" class="col-sm-4 col-form-label" _msttexthash="74867" _msthash="112">Sanction en heure</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="exampleInputMobile" placeholder="0,00" _mstplaceholder="205387" _msthash="113">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputPassword2" class="col-sm-4 col-form-label" _msttexthash="157794" _msthash="114">Sanction en point</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="exampleInputPassword2" placeholder="0,00" _mstplaceholder="117572" _msthash="115">
                    </div>
                  </div>

                  <div class="form-check form-check-flat form-check-primary">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input"><font _mstmutation="1" _msttexthash="383331" _msthash="118"> Cocher si c'est une absence </font><i class="input-helper"></i></label>
                  </div>
                  <button type="submit" class="btn btn-primary mr-2" _msttexthash="98280" _msthash="119">Envoyer</button>
                  <button class="btn btn-light" _msttexthash="95901" _msthash="120">Annuler</button>
                </form>
              </div>
        </div>
      </div>
    </div>
  </div>


@endsection