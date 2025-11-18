@extends('layouts.master')
@section('content')
<style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  thead {
    background-color: #f2f2f2;
  }
  th, td {
    padding: 6px;
    border: 1px solid #ddd;
    text-align: center;
  }
</style>
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
    <div class="row">
      <div class="col-lg-3">
        <button id="" class="btn btn-primary" onclick="imprimerArriereConstat()"> Imprimer</button>
        
      </div>
      
    </div>
    
    <div id="ArriereConstat" style="width: 100%"> 
      <h4 class="card-title text-center my-3">{{ $titre ?? 'État des arriérés constatés' }}</h4>
      
      @if(isset($datedebut) && isset($datefin))
      <p class="text-center">Période: {{ \Carbon\Carbon::parse($datedebut)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($datefin)->format('d/m/Y') }}</p>
      @endif
      @if(isset($groupedResultats) && $groupedResultats->count() > 0)
      
      @php
      $totalGeneral = 0;
      @endphp
      
      <table style="width: 100%">
        <thead>
          <tr>
            <th>Date</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe précédente</th>
            <th>Montant</th>
            <th>Percepteur</th>
          </tr>
        </thead>
        <tbody>
          @foreach($groupedResultats as $date => $resultats)
          @php
          $sousTotal = 0; // Initialisation du sous-total pour cette date
          @endphp
          
          @foreach($resultats as $resultat)
          <tr>
            <td> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
            <td>{{ $resultat->NOM }}</td>
            <td>{{ $resultat->PRENOM }}</td>
            <td>{{ $resultat->classe_precedente }}</td> <!-- Classe précédente -->
            <td>{{ number_format($resultat->MONTANT, 0, ',', ' ') }} </td>
            <td>{{ $resultat->SIGNATURE }}</td>
          </tr>
          @php
          // Calcul du sous-total pour cette date
          $sousTotal += $resultat->MONTANT;
          @endphp
          @endforeach
          
          <!-- Affichage du sous-total pour cette date -->
          <tr>
            <td colspan="5" class="text-right"><strong>Sous-total</strong></td>
            <td><strong>{{ number_format($sousTotal, 0, ',', ' ') }} </strong></td>
          </tr>
          
          @php
          // Ajouter le sous-total au total général
          $totalGeneral += $sousTotal;
          @endphp
          @endforeach
        </tbody>
        <tfoot>
          <!-- Affichage du total général -->
          <tr>
            <td colspan="5" class="text-right"><strong>Total général :</strong></td>
            <td><strong>{{ number_format($totalGeneral, 0, ',', ' ') }}</strong></td>
          </tr>
        </tfoot>
      </table>
      
      @else
      <p>Aucune donnée trouvée pour les dates sélectionnées.</p>
      @endif
    </div>
  </div>
</div>
<script>
  function injectTableStyles() {
    var style = document.createElement('style');
    style.innerHTML = `
      @page { size: landscape; }
          table {
              width: 100%;
              border-collapse: collapse;
          }
          thead {
              background-color: #f2f2f2;
          }
          th, td {
              padding: 6px;
              border: 1px solid #ddd;
              text-align: center;
          }
          .classe-row {
              background-color: #f9f9f9;
              font-weight: bold;
          }`;
    document.head.appendChild(style);
  }
  function imprimerArriereConstat() {
    injectTableStyles(); // Injecter les styles pour l'impression
    var originalContent = document.body.innerHTML; // Contenu original de la page
    var printContent = document.getElementById('ArriereConstat').innerHTML; // Contenu spécifique à imprimer
    document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer
    
    setTimeout(function() {
      window.print(); // Ouvrir la boîte de dialogue d'impression
      document.body.innerHTML = originalContent; // Restaurer le contenu original
    }, 1000);
  }
</script>
@endsection
