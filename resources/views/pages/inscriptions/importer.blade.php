@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <div class="page-title"><h4>Importation des données</h4></div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <h6>Configuration du fichier Excel</h6>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    Dans le fichier excel les nom et prénoms sont dans des colonnes différentes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    Dans le fichier excel les nom et prénoms sont dans la même colonne
                  </label>
                </div>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Cliquer ici pour ouvrir un modèle de fichier Excel</button>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Cliquer ici pour localiser Excel...</button>
              </div>
              <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">Ecraser le fichier des élèves existants</label>
                  </div>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Afficher la conversion du fichier</button>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Cliquer ici pour afficher les erreurs</button>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Importer</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table custom-table">
                  <thead>
                    <tr>
                      <th>Matricule</th>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th>Sexe</th>
                      <th>Statut</th>
                      <th>Classes</th>
                      <th>Date</th>
                      <th>Lieu</th>
                      <th>Nevers</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>00000844</td>
                      <td>ABOGOURIN</td>
                      <td>Mardiath</td>
                      <td>Féminin</td>
                      <td>Ancien</td>
                      <td>CM2</td>
                      <td>02/08/2010</td>
                      <td>Cotonou</td>
                      <td></td>
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

@endsection