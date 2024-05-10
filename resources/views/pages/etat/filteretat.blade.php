<?php use Carbon\Carbon; ?>
@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Etats des droits constatés</h4>
      <form action="{{url('/filteretat')}}" method="POST">
        {{csrf_field()}}
      <div class="form-group row">
        <div class="col-lg-3">
          <select class="js-example-basic-single w-100"  name="annee">
            <option value="">Sélectionnez une année</option>
            @foreach ($anne as $annees)
            <option value="{{$annees}}">{{$annees}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-3">
          <select class="js-example-basic-single w-100"  name="classe">
            <option value="">Sélectionnez une classe</option>
            
            @foreach ($class as $classes)
            <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-2">
          <button type="submit" class="btn btn-primary">
            Afficher
          </button>
        </div>
    
        <div class="col-lg-2 offset-2">
          <button type="button" class="btn btn-primary">
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
                N
              </th>
              <th>Elève</th>
              <th>Classe</th>
              @foreach ($moisContrat as $mois)
              @if ($mois->nom_moiscontrat != 'Juillet' && $mois->nom_moiscontrat != 'Aout')
              <th>{{ $mois->nom_moiscontrat }}</th>
              @endif
              @endforeach         
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach ($eleves as $index => $eleve)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                <td>{{ $eleve->CODECLAS }}</td>
                
                @php
                $totalMois = [];
                @endphp
                @foreach ($moisContrat as $mois)
                @if ($mois->nom_moiscontrat != 'Juillet' && $mois->nom_moiscontrat != 'Aout')
                @php
                $totalMois[$mois->id_moiscontrat] = 0;
                @endphp
                <td>
                  @php
                  $montantTotalMois = 0;
                  $paiementTrouve = false;
                  @endphp
                  @foreach ($eleve->contrats as $contrat)
                  @foreach ($contrat->paiements as $paiement)
                  @if ($paiement->mois_paiementcontrat == $mois->id_moiscontrat)
                  <!-- Afficher la date et le montant si le paiement correspond au mois -->
                  {{ \Carbon\Carbon::parse($paiement->date_paiementcontrat)->format('d/m/Y') }}</br></br>
                  {{ $paiement->montant_paiementcontrat }}
                  @php
                  $montantTotalMois += $paiement->montant_paiementcontrat;
                  $paiementTrouve = true;
                  @endphp
                  @endif
                  @endforeach
                  @endforeach
                  @if (!$paiementTrouve)
                  @if ($mois->id_moiscontrat != 13)
                  Pas inscrit
                  @else
                  0
                  @php
                  $totalMois[$mois->id_moiscontrat] += $montantTotalMois;
                  @endphp
                  @endif
                  @else
                  @php
                  $totalMois[$mois->id_moiscontrat] += $montantTotalMois;
                  @endphp
                  @endif
                  
                </td>
                
                {{-- <td>
                  @foreach ($eleve->contrats as $contrat)
                  @foreach ($contrat->paiements as $paiement)
                  @if ($paiement->mois_paiementcontrat == $mois->id_moiscontrat)
                  {{ \Carbon\Carbon::parse($paiement->date_paiementcontrat)->format('d/m/Y') }}</br></br>
                  {{ $paiement->montant_paiementcontrat }}
                  @php
                  $totalMois[$mois->id_moiscontrat] += $paiement->montant_paiementcontrat;
                  @endphp
                  @endif
                  @endforeach
                  @endforeach
                </td> --}}
                @endif
                @endforeach
                <td>
                  {{ array_sum($totalMois) }}
                </td>
              </tr>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection