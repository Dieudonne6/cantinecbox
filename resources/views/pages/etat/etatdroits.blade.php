@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Etats des droits constatés</h4>
      <form action="{{url('/filteretat')}}" method="POST">
        {{csrf_field()}}
      <div class="form-group row">
          <div class="col-3">
            <select class="js-example-basic-single w-100" name="annee">
              <option value="">Sélectionnez une année</option>
              @foreach ($annee as $annees)
                <option value="{{$annees}}">{{$annees}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-3">
            <select class="js-example-basic-single w-100" name="classe">
              <option value="">Sélectionnez une classe</option>
                @foreach ($classe as $classes)
                  <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                @endforeach
            </select>
          </div>
          <div class="col-3">
            <button type="submit" class="btn btn-primary w-100">
            Afficher
            </button>
          </div>
        </div>
      </form>
    
      <div class="table-responsive pt-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>
                N
              </th>
              <th>Elève</th>
              <th>Classe</th>
              <th>Inscription</th>
              <th>Janvier</th>
              <th>Fevrier</th>
              <th>Mars</th>
              <th>Avril</th>
              <th>Mai</th>
              <th>Juin</th>
              <th>Septembre</th>
              <th>Octobre</th>
              <th>Novembre</th>
              <th>Décembre</th>
              <th>Total</th>
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