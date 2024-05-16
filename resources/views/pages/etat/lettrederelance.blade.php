@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Lettre de relance</h4>
      
      <div class="table-responsive pt-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Classe</th>
              <th>Elève</th>
              <th>Relances</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($relance as $relances)
              <tr>
                <td>{{ $relances->CODECLAS }}</td>
                <td>{{ $relances->NOM }} {{ $relances->PRENOM }}</td>
                <td></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection