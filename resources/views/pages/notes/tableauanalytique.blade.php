@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
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
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>   
            <br>
            <br>                                   
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Moyenne de référence</label>
                        <input type="number" class="form-control" value="10.00" step="0.01">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Période</label>
                        <select class="form-control">
                            <option>Sélectionner une période</option>
                            <option value="1">1ère Période</option>
                            <option value="2">2ème Période</option>
                            <option value="3">3ème Période</option>
                            <option value="4">Annuel</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Type d'états</label>
                        <select class="form-control">
                            <option>Sélectionner un état</option>
                            <option>Tableau analytique des résultats</option>
                            <option>Tableau synoptique des résultats</option>
                            <option>Tableau synoptique des éffectifs</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" style="max-width: 300px; margin: 0 auto;">
                    <thead>
                        <tr>
                            <th style="width: 100px; text-align: center;">Intervalle</th>
                            <th style="width: 150px; text-align: center;">Min</th>
                            <th style="width: 150px; text-align: center;">Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I1</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="0.00"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="06.50"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I2</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="06.50"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="07.50"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I3</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="07.50"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="10.00"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I4</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="10.00" step="0.01"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="20.00" step="0.01"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I5</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="12.00" step="0.01"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="14.00" step="0.01"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I6</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="14.00" step="0.01"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="16.00" step="0.01"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">I7</td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="16.00" step="0.01"></td>
                            <td style="text-align: center;"><input type="number" class="form-control mx-auto" value="20.00" step="0.01"></td>
                        </tr>
                        <!-- Ajoutez d'autres intervalles selon vos besoins -->
                    </tbody>
                </table>
            </div>
            <div class="text-right mt-4">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-calculator"></i> Calculer
                </button>
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>            
            <div class="table-responsive mt-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 25px;">GPE</th>
                            <th style="text-align: center;">MFOG</th>
                            <th style="text-align: center;">MFOF</th>
                            <th style="text-align: center;">MFAG</th>
                            <th style="text-align: center;">MFAF</th>
                            <th style="text-align: center;">I1G</th>
                            <th style="text-align: center;">I1F</th>
                            <th style="text-align: center;">I1T</th>
                            <th style="text-align: center;">I2G</th>
                            <th style="text-align: center;">I2F</th>
                            <th style="text-align: center;">I2T</th>
                            <th style="text-align: center;">I3G</th>
                            <th style="text-align: center;">I3F</th>
                            <th style="text-align: center;">I3T</th>
                            <th style="text-align: center;">I4G</th>
                            <th style="text-align: center;">I4F</th>
                            <th style="text-align: center;">I4T</th>
                            <th style="text-align: center;">I5G</th>
                            <th style="text-align: center;">I5F</th>
                            <th style="text-align: center;">I5T</th>
                            <th style="text-align: center;">I6G</th>
                            <th style="text-align: center;">I6F</th>
                            <th style="text-align: center;">I6T</th>
                            <th style="text-align: center;">I7G</th>
                            <th style="text-align: center;">I7F</th>
                            <th style="text-align: center;">I7T</th>
                            <th style="text-align: center;">I8G</th>
                            <th style="text-align: center;">I8F</th>
                            <th style="text-align: center;">I8T</th>
                            <th style="text-align: center;">I9G</th>
                            <th style="text-align: center;">I9F</th>
                            <th style="text-align: center;">I9T</th>
                            <!-- Ajoutez les autres colonnes selon vos besoins -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<style>
    .form-control {
        border: 1px solid #ddd;
    }
    .table input.form-control {
        width: 100%;
        max-width: 120px;
        text-align: center;
        margin: 0 auto;
    }
    .btn {
        margin-left: 10px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        margin: 0 auto;
    }
    .table td, .table th {
        vertical-align: middle !important;
    }
    
    /* Styles spécifiques pour le tableau des statistiques */
    .table input.form-control {
        padding: 2px;
        font-size: 0.9em;
    }
    
    /* Style pour le grand tableau */
    .table-responsive {
        overflow-x: auto;
        margin-bottom: 1rem;
    }
    
    .table-responsive table {
        min-width: 100%;
        white-space: nowrap;
    }
</style>
@endsection