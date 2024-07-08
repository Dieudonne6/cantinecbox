@extends('layouts.master')
@section('content')

<style>
    .cadre-photo {
        border: 2px solid #ccc; /* Bordure de 2 pixels de large, solide, couleur gris clair */
        padding: 10px; /* Espacement intérieur de 10 pixels */
        max-width: 100%; /* Pour s'assurer que l'image ne dépasse pas de son conteneur */
        display: block; /* Assure que l'image est un bloc, afin que les marges puissent être appliquées correctement */
        margin: 0 auto; /* Centre l'image horizontalement */
    }
    .conteneur {
        text-align: center; /* Centre le contenu horizontalement */
        margin-top: 20px; /* Marge supérieure pour l'espacement */
    }
    table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                    text-align: left;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #ddd;
                }
</style>

<div class="card row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card-body">
            <div class="chartjs-size-monitor">
                <div class="chartjs-size-monitor-expand"><div class=""></div></div>
                <div class="chartjs-size-monitor-shrink"><div class=""></div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénoms</th>
                        <th>DemClasse</th>
                        <th>Sexe</th>
                        <th>Date de Naissance</th>
                        <th>Lieu de Naissance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12345</td>
                        <td>Doe</td>
                        <td>John</td>
                        <td>3ème</td>
                        <td>Masculin</td>
                        <td>01/01/2000</td>
                        <td>Paris</td>
                        <td>
                            <button type="button" class="btn btn-primary p-2 btn-sm btn-icon-text mr-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="typcn typcn-eye btn-icon-append">Voir plus</i>
                            </button>
                        </td>
                    </tr>
                    <!-- Ajouter d'autres lignes ici -->
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size: 14px;">
                        <button class="nav-link active" id="nav-Infor-tab" data-bs-toggle="tab" data-bs-target="#nav-Infor" type="button" role="tab" aria-controls="nav-Infor" aria-selected="true">Informations générales</button>
                        <button class="nav-link" id="nav-Detail-tab" data-bs-toggle="tab" data-bs-target="#nav-Detail" type="button" role="tab" aria-controls="nav-Detail" aria-selected="false">Détail des notes</button>
                        <button class="nav-link" id="nav-Deta-tab" data-bs-toggle="tab" data-bs-target="#nav-Deta" type="button" role="tab" aria-controls="nav-Deta" aria-selected="false">Informations académiques</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Informations générales -->
                    <div class="tab-pane fade show active" id="nav-Infor" role="tabpanel" aria-labelledby="nav-Infor-tab" tabindex="0">
                        <form class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3;">
                            <div class="form-group row mt-1">
                                <div class="conteneur">
                                    <div class="cadre-photo">
                                        <img src="chemin_vers_ton_image.jpg">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="lieu" class="col-sm-4 col-form-label">Lieu</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="lieu" placeholder="Lieu">
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label for="dateN" class="col-sm-4 col-form-label">Date d'inscription</label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control mt-2" id="dateN" placeholder="jj/mm/dd">
                                </div>
                            </div>
                            <div class="form-group row mt-1">
                                <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="sexe">
                                        <option>Masculin</option>
                                        <option>Féminin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-1 align-items-center">
                                <label for="apte" class="col-sm-2 col-form-label">Apte</label>
                                <div class="col-sm-2">
                                    <select class="form-control" id="apte">
                                        <option>Oui</option>
                                        <option>Non</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Détail des notes -->
                    <div class="tab-pane fade" id="nav-Detail" role="tabpanel" aria-labelledby="nav-Detail-tab" tabindex="0">
                        <form class="accordion-body col-md-12 mx-auto" style="background-color: #f0eff3;">
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Année</th>
                                            <th>Classe</th>
                                            <th>Moy.annuelle</th>
                                            <th>Rang</th>
                                            <th>Statut</th>
                                            <th>Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-label="Année">2024</td>
                                            <td data-label="Classe">3ème</td>
                                            <td data-label="Moy.annuelle">14.5</td>
                                            <td data-label="Rang">1</td>
                                            <td data-label="Statut">Admis</td>
                                            <td data-label="Observation">Excellent</td>
                                        </tr>
                                        <!-- Ajouter d'autres lignes ici -->
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <!-- Informations académiques -->
                    <div class="tab-pane fade" id="nav-Deta" role="tabpanel" aria-labelledby="nav-Deta-tab" tabindex="0">
                        <div class="accordion-body col-md-12 mx-auto bg-light-gray">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Année</th>
                                        <th>Moy. Trim1</th>
                                        <th>Rg1</th>
                                        <th>Moy. Trim2</th>
                                        <th>Rg2</th>
                                        <th>Moy. Trim3</th>
                                        <th>Rg3</th>
                                        <th>Moy. an</th>
                                        <th>Rang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2024</td>
                                        <td>15.5</td>
                                        <td>1</td>
                                        <td>14.8</td>
                                        <td>2</td>
                                        <td>16.2</td>
                                        <td>1</td>
                                        <td>15.5</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>2023</td>
                                        <td>13.4</td>
                                        <td>3</td>
                                        <td>12.9</td>
                                        <td>4</td>
                                        <td>14.0</td>
                                        <td>3</td>
                                        <td>13.4</td>
                                        <td>3</td>
                                    </tr>
                                    <!-- Ajouter d'autres lignes ici -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
