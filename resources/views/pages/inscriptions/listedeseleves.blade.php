@extends('layouts.master')
@section('content')

<div class="container">
  <div class="form-group row">
    <div class="col-3">
      <select class="js-example-basic-multiple w-100">
        <option value="">Sélectionnez une classe</option>
      </select>
    </div>
    <div class="col-4">
      <input type="text" class="form-control p-2" name="" value="" placeholder="Entrez titre de l'etat">
    </div>
    <div class="col-2">
      <a class="btn btn-primary" href="">Creer une liste</a>
    </div>
    <div class="col-2">
      <a class="btn btn-primary" href="">Imprimer liste</a>
    </div>
  </div>
  <div class="row grid-margin stretch-card">
    <div class="col-lg-10 mx-auto card">
      <div class="table-responsive mb-4">
        <table class="table">
          <thead>
            <tr>
              <th>Matricule</th>
              <th>Nom </th>
              <th>Prenom</th>
              <th>Classe</th>
              <th>Date de naissance</th>
              <th>Lieu de naissance</th>
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
  </div>
</div>
    @endsection