<?php use Carbon\Carbon; ?>

@extends('layouts.master')
@section('content')
<style>
  /* Styles spécifiques pour l'impression sur papier A4 */
  @media print and (size: A4) {
      table {
          font-size: 5pt; /* Ajuste la taille de la police pour A4 */
      }
      th, td {
          padding: 3px; /* Ajuste le padding pour A4 */
      }
  }

  /* Styles spécifiques pour l'impression sur papier A3 */
  @media print and (size: A3) {
      table {
          font-size: 6pt; /* Ajuste la taille de la police pour A3 */
      }
      th, td {
          padding: 4px; /* Ajuste le padding pour A3 */
      }
  }
</style>

<body>
  
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Etats des droits constatés</h4>
        {{-- <form action="{{url('/filteretat')}}" method="POST">
          {{csrf_field()}}
          <div class="form-group row">
            <div class="col-lg-3">
              <select class="form-control w-100" name="annee">
                <option value="">Sélectionnez une année</option>
                @foreach ($anne as $annees)
                <option value="{{$annees}}">{{$annees}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-3">
              <select class="form-control w-100" name="classe">
                <option value="">Sélectionnez une classe</option>
                @foreach ($class as $classes)
                <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-2">
              <button type="submit" class="btn btn-primary">Afficher</button>
            </div>
            <div class="col-lg-2 offset-2">
              <button onclick="imprimePage()" class="btn btn-primary">Imprimer</button>
            </div>
          </div>
        </form> --}}
        
        <div id="contenu">
          <div>
            <h4 class="card-title" style="text-align: center; font-weight:bold;">Etats des droits constatés ANNEE-ACADEMIQUE: {{ $annee }} - {{ $anneesuivant }} | CLASSE: {{ $classe }}</h4>
          </div><br>
          <div class="table-responsive pt-3">
            {{-- <table id="myTable" class="table table-striped table-sm" > --}}
            <table id="myTable" class="table table-bordered" >
              <thead>
                <tr>
                  <th>N</th>
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
                @foreach ($eleves as $index => $eleve)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="width: 10px">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                    <td>{{ $eleve->CODECLAS }}</td>
                    
                    @php
                    $totalMois = [];
                    @endphp
                    @foreach ($moisContrat as $mois)
                    @if ($mois->nom_moiscontrat != 'Juillet' && $mois->nom_moiscontrat != 'Aout')
                    @php
                    $totalMois[$mois->id_moiscontrat] = 0;
                    $montantTotalMois = 0;
                    $paiementTrouve = false;
                    @endphp
                    <td>
                        @foreach ($eleve->contrats as $contrat)
                        @foreach ($contrat->paiements as $paiement)
                        @if ($paiement->mois_paiementcontrat == $mois->id_moiscontrat)
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
                        @endif
                        @php
                        $totalMois[$mois->id_moiscontrat] += $montantTotalMois;
                        @endphp
                        @else
                        @php
                        $totalMois[$mois->id_moiscontrat] += $montantTotalMois;
                        @endphp
                        @endif
                        
                    </td>
                    @endif
                    @endforeach
                    <td>{{ array_sum($totalMois) }}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
@endsection


<script>

  function imprimePage() {
      // Désactiver la pagination
      let table = $('#myTable').DataTable();
      let currentPage = table.page();  // Sauvegarder la page actuelle
      table.page.len(-1).draw();  // Afficher toutes les lignes
  
      // Attendre un court instant pour être sûr que le tableau est complètement rendu
      setTimeout(function() {
          // Masque les colonnes avec la classe hide-on-print
          var tableElement = document.getElementById('myTable');
          tableElement.classList.remove('dataTable');
  
          // var columns = tableElement.querySelectorAll('.hide-on-print');
          // columns.forEach(function(column) {
          //     column.style.display = 'none';
          // });
  
          var page = window.open('', '_blank');
          page.document.write('<html><head><title>EDC_annee_{{ $annee }}_{{ $anneesuivant }}_classe_{{ $classe }}</title>');
          page.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >');
          page.document.write('<style>@media print { .dt-end { display: none !important; } }</style>');
          page.document.write('<style>@media print { .dt-start { display: none !important; } }</style>');
          page.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 4px; } tbody tr:nth-child(even) { background-color: #f1f3f5; } tbody tr:nth-child(odd) { background-color: #ffffff; } </style>');
          page.document.write('</head><body>');
          page.document.write('<div>' + document.getElementById('contenu').innerHTML + '</div>');
          page.document.write('</body></html>');
          page.document.close();
          page.onload = function() {
              page.print();
              page.close();
  
              // Restaurer la pagination après l'impression
              table.page.len(10).draw();  // Remettre la taille de la page comme avant (par exemple : 10)
              table.page(currentPage).draw(false);  // Retour à la page actuelle
  
              // Restaure les colonnes après l'impression
              columns.forEach(function(column) {
                  column.style.display = '';
              });
          };
      }, 200);
  }
  
  
  </script>
