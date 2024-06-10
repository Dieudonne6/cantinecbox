@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Etats des droits constatés</h4>
      <form action="{{url('/filteretat')}}" method="POST">
        {{csrf_field()}}
        <div class="row">
          <div class="col-3">
            <select class="form-control w-100" name="annee">
              <option value="">Sélectionnez une année</option>
              @foreach ($annee as $annees)
                <option value="{{$annees}}">{{$annees}}</option>
              @endforeach
            </select>
          </div>
          
          <div class="col-3">
            <select class="form-control w-100" name="classe">
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
          <div class="col-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Imprimer la relance
            </button>
          </div>
        </div>
      </form>
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{url('/relance')}}" method="POST">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Date Buttoir</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="date" class="form-control" name="daterelance">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Imprimer</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
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