@extends('layouts.master')
@section('content')
<div class="container">
  <div class="table-responsive pt-3">
      <table class="table table-striped project-orders-table">
          <thead>
              <tr>
                  <th>Matricule</th>
                  <th>Nom</th>
                  <th>Prénoms</th>
                  <th>Classe</th>
                  <th>Date de Naissance</th>
                  <th>Lieu de Naissance</th>
                  <th>Sexe</th>
                  <th>Statut</th>
                  <th>Apte</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>001</td>
                  <td>Dupont</td>
                  <td>Jean</td>
                  <td>CE2</td>
                  <td>01/01/1990</td>
                  <td>Paris</td>
                  <td>M</td>
                  <td>Actif</td>
                  <td>Apte</td>
              </tr>
              <tr>
                  <td>002</td>
                  <td>Durand</td>
                  <td>Marie</td>
                  <td>CM2</td>
                  <td>15/05/1985</td>
                  <td>Lyon</td>
                  <td>F</td>
                  <td>Inactif</td>
                  <td>Apte</td>
              </tr>
              <!-- Ajouter d'autres lignes ici -->
          </tbody>
      </table>
  </div>
</div>
@endsection