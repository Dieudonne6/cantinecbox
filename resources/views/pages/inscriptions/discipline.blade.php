@extends('layouts.master')
@section('content')

<link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
<div class="container card mt-3">
    <div class="row">
        <div class="card-body">
            <h4 class="card-title">Dicipline</h4>
            <div class="row mb-3">
                <div class="col">
                    <select class="form-select">
                        <option selected>Fautes et sanctions</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-select">
                        <option selected>Sélectionner un groupe</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-6">
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
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <p>Traitement impression</p>
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
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-container">
                                <table class="table table-striped">
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
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-container">
                                <table class="table table-striped">
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Table des fautes/Sanction</button>
                                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Nouvelle faute/Sanction</button>
                                    <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false">Modifier sanction...</button>
                                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Autres sanctions</button>
                                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Paramètres</button>
                                </div>
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <table class="table table-striped">
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
                                                    <td>Non-respect du code</td>
                                                    <td>Avertissement</td>
                                                    <td>2h</td>
                                                    <td>5</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="bi bi-pencil-fill"></i> Modifier
                                                        </button>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash-fill"></i> Supprimer
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Retard</td>
                                                    <td>Retenue</td>
                                                    <td>1h</td>
                                                    <td>2</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="bi bi-pencil-fill"></i> Modifier
                                                        </button>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash-fill"></i> Supprimer
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Ajoutez d'autres lignes selon vos besoins -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto">
                                                            <label for="nouvelleFaute" class="form-label">Nouvelle faute</label>
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="nouvelleFaute">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto">
                                                            <label for="sanctionPrev" class="form-label">Sanction prévue</label>
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="sanctionPrev">
                                                        </div>                                            
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto">
                                                            <label for="heure" class="form-label">En heure</label>
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" class="form-control" id="heure">
                                                        </div>                                            
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto">
                                                            <label for="point" class="form-label">En point</label>
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" class="form-control" id="point">
                                                        </div>                                            
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <button type="submit" class="btn btn-success">Ajouter</button>
                                        </form>
                                        
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
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
