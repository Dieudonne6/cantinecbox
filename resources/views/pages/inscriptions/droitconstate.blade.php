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
      <h4 class="card-title">Etats des droits constatés</h4>
      <form action="{{url('/filteretat')}}" method="POST">
        {{csrf_field()}}
        <div class="row">
          <div class="col-3">
            <select class="form-control w-100" name="annee">
              <option value="">Classe</option>
              <option value="CI">CI</option>
              <option value="CP">CP</option>
              <option value="Tous">Tous</option>
                  {{-- @foreach ($classe as $classes)
                  <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                @endforeach --}}
            </select>
          </div>
          
          <div class="col-3">
            <select class="form-control w-100" name="classe">
              <option value="">Format</option>
              <option value="5 tranche">5 tranche</option>
              <option value="10 tranche">10 tranche</option>
                {{-- @foreach ($classe as $classes)
                  <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                @endforeach --}}
            </select>
          </div>

          <div class="col-3">
            <select class="form-control w-100" name="classe">
              <option value="">Type</option>
              <option value="Scolarite">Scolarite</option>
              <option value="Arriere">Arriere</option>
                {{-- @foreach ($classe as $classes)
                  <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                @endforeach --}}
            </select>
          </div>
          <div class="col-3">
            <button type="submit" class="btn btn-primary w-100">
            Imprimer
            </button>
          </div>

        </div>
      </form>
 
      <div class="table-responsive pt-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>
                No
              </th>
              <th>Elève</th>
              <th>A PAYER</th>
              <th>VERS1</th>
              <th>VERS2</th>
              <th>VERS3</th>
              <th>VERS4</th>
              <th>VERS5</th>
              <th>VERS6</th>
              <th>VERS7</th>
              <th>VERS8</th>
              <th>VERS9</th>
              <th>VERS10</th>
              <th>TOTAL PAYE</th>
              <th>RESTE A PAYER</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection