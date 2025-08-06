@extends('layouts.master')
@section('content')
<div class="container">
    <h4 class="card-title">Annuler une facture d'inscription</h4>
        @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div>
        @endif
    <div class="table-responsive mb-4">
        <table id="myTable" class="table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Référence</th>
              <th>Montant</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($factures as $facture)

            <tr>
                <td>{{$facture->nom}}</td>
                <td>{{$facture->codemecef}}</td>
                <td>{{$facture->montant_total}}</td>
                <td>{{$facture->datepaiementcontrat}}</td>
                <td>
                    <a class="btn btn-primary" href="{{ url('avoirfactureinscri/'.$facture->codemecef) }}" >Annuler</a>
                    {{-- <button class="btn btn-primary">Annuler</button> --}}
                </td>
            </tr>

            @endforeach

        </tbody>
    </table>
  </div>
</div>

@endsection