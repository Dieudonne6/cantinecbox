@extends('layouts.master')
@section('content')

<div class="container">
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
      <h4 class="card-title">Liste générale des élèves</h4>
      <div class="form-group row">
        <div class="col-3">
          <select class="js-example-basic-multiple w-100" multiple="multiple" name="CODECLAS[]"  name="CODECLAS[]" data-placeholder="Toutes les classes">         
            <option value="">Toutes les classes</option>
            @foreach ($allClasse as $classes)
              <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-3">
          <button  class="btn btn-primary" id="submitBtn">Appliquer la sélection</button>
        </div>

      </div>
      <div class="row grid-margin stretch-card">
        <div class="col-lg-10 mx-auto card">
          <div class="table-responsive mb-4">
            <table id="myTable" class="table">
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
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



    @endsection