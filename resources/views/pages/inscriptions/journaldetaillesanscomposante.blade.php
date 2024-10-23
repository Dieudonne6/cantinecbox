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
  th, td, tr {
    padding: 6px;
    border: 1px solid #ddd;
    text-align: center;
  }
</style>
<div class="row">
  <div class="col-lg-3">
    <button id="" class="btn btn-primary" onclick="imprimerArriereConstat()"> Imprimer</button>
    
  </div>
  
</div>
  <div  id="ArriereConstat" class="card">
    <div class="card-body">
      <h4 class="text-center py-3">Journal détaillé des recouvrements(sans précision des composantes)</h4>
      
      <!-- Tableau pour afficher les recouvrements -->
      <table>
        <thead>
          <tr>
            <th>Num recu</th>
            <th>Nom </th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Montant</th>
            <th>Signature</th>
          </tr>
        </thead>
        <tbody>
          @php
            $currentDate = null;
            $dailyTotal = 0;
          @endphp

          @foreach($recouvrements as $recouvrement)
            @if($currentDate !== $recouvrement->DATEOP)
              @if($currentDate !== null)
                <!-- Afficher le sous-total pour la date précédente -->
                <tr>
                  <td colspan="4" class="text-right"><strong>Total perçu du : {{ \Carbon\Carbon::parse($recouvrement->DATEOP)->format('d/m/Y') }}</strong></td>
                  <td><strong>{{ number_format($dailyTotal,  0, ',', ' ') }} </strong></td>

                </tr>
              @endif

              <!-- Réinitialiser le sous-total pour la nouvelle date -->
              @php
                $currentDate = $recouvrement->DATEOP;
                $dailyTotal = 0;
              @endphp

              <!-- Afficher la nouvelle date -->
              <tr>
                <td colspan="1"><strong>Date : {{ \Carbon\Carbon::parse($recouvrement->DATEOP)->format('d/m/Y') }}</strong></td>
                <td colspan="4"><strong>Ens {{ $enseign->type }}</strong></td>
              </tr>
            @endif

            <!-- Afficher les détails de l'élève -->
            <tr>
              <td>{{ $recouvrement->NUMRECU }}</td>

              <td>{{ $recouvrement->NOM }}</td>
              <td>{{ $recouvrement->PRENOM }}</td>
              <td>{{ $recouvrement->CODECLAS }}</td>
              <td>{{ number_format($recouvrement->total,  0, ',', ' ') }} </td>

            <td>{{ $recouvrement->SIGNATURE }}</td>

            </tr>

            @php
              $dailyTotal += $recouvrement->total;
            @endphp
          @endforeach

          <!-- Afficher le dernier sous-total -->
          @if($currentDate !== null)
            <tr>
              <td colspan="4" class="text-right"><strong>Total perçu du : {{ \Carbon\Carbon::parse($recouvrement->DATEOP)->format('d/m/Y') }}</strong></td>
              <td><strong>{{ number_format($dailyTotal,  0, ',', ' ') }} </strong></td>
            </tr>
          @endif
        </tbody>
      </table>
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

