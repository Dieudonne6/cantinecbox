@extends('layouts.master')
@section('content')

<div class="container">
  <div class="form-group row">
    <div class="col-3">
      <select class="js-example-basic-multiple w-100">
        <option value="">Sélectionnez une classe</option>
        <option value="CE2">CE2</option>
        <option value="tout">Tout</option>
      </select>
    </div>
    <div class="col-4">
      <select class="js-example-basic-multiple w-100">
        <option value="">La decision porte sur </option>
        <option value="Echeancier">Echeancier</option>
        <option value="Arrièré">Arrièré</option>
        <option value="Autres frais">Autres frais</option>
      </select>
    </div>
    <div class="col-4">
      <select class="js-example-basic-multiple w-100">
        <option value="">Tous les eleves</option>
        <option value="Echeancier">Eleves non à jour</option>
        <option value="Arrièré">Eleves à jour</option>
      </select>
    </div>
  </div>
  <div class="row grid-margin stretch-card">
    <div class="col-lg-10">
      <div class="table-responsive mb-4">
        <table class="table">
          <thead>
            <tr>
              <th>Nom et prenom</th>
              <th>Classe </th>
              <th>Reste echeance</th>
              <th>Reste arriere</th>
              <th>Reste autre</th>
              <th>Decision</th>
              <th>Sexe</th>
              <th>Statut</th>
              <th>Apte</th>
              <th>Moy1</th>
              <th>Moy2</th>
              <th>Moy3</th>
              <th>Moyan</th>
            </tr>
          </thead>
          <tbody id="">
            <tr>
              <td>900</td>
              <td>JODy </td>
              <td>Macu</td>
              <td>CE2</td>
              <td>03/09/24</td>
              <td>Cotonou</td>
              <td>M</td>
              <td></td>
              <td>Apte</td>
              <td>####</td>
              <td>####</td>
              <td>####</td>
              <td>-200</td>
            </tr>
            <tr>
              <td>900</td>
              <td>JODy </td>
              <td>Macu</td>
              <td>CE2</td>
              <td>03/09/24</td>
              <td>Cotonou</td>
              <td>M</td>
              <td></td>
              <td>Apte</td>
              <td>####</td>
              <td>####</td>
              <td>####</td>
              <td>-200</td>
            </tr>
          </tbody>
        </table>
      </div>
      
    </div>
    <div class="col-lg-2">
      <a class="btn btn-primary mb-2" href="">Creer</a>
      <a class="btn btn-primary mb-2" href="">Decisions</a>
      <a class="btn btn-primary mb-2" href="">Imprimer</a>
      <a class="btn btn-danger mb-2" href="">Arreter</a>
      <a class="btn btn-primary mb-2" href="">Relance</a>
      <p>Date de relance</p>
      <input type="date" class="form-control"> 
    </div>
  </div>
</div>
    @endsection