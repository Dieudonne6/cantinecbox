@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card" id="original">
    <div class="card">
        <div>
            <style>
                .btn-arrow {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    background-color: transparent !important;
                    border: 1px !important;
                    text-transform: uppercase !important;
                    font-weight: bold !important;
                    cursor: pointer !important;
                    font-size: 17px !important;
                    color: #b51818 !important;
                }
                .btn-arrow:hover {
                    color: #b700ff !important;
                }
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <br><br>
        </div>

        <div class="card-body">
            <!-- Formulaire pour le filtrage et le calcul -->
            <form action="{{ route('tableauanalytique') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Moyenne de référence</label>
                            <input type="number" name="moyenne_ref" class="form-control" value="10.00" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Période</label>
                            <select name="periode" class="form-control">
                                <option value="">Sélectionner une période</option>
                                <option value="1">1ère Période</option>
                                <option value="2">2ème Période</option>
                                <option value="3">3ème Période</option>
                                <option value="4">Annuel</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type d'états</label>
                            <select name="typeEtat" class="form-control">
                                <option value="">Sélectionner un état</option>
                                <option value="tableau_analytique">Tableau analytique des résultats</option>
                                <option value="tableau_synoptique">Tableau synoptique des résultats</option>
                                <option value="effectifs">Tableau synoptique des effectifs</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tableau des intervalles -->
                <div class="table-responsive">
                    <table class="table table-bordered" style="max-width: 300px; margin: 0 auto;">
                        <thead>
                            <tr>
                                <th style="width: 100px; text-align: center;">Intervalle</th>
                                <th style="width: 150px; text-align: center;">Min</th>
                                <th style="width: 150px; text-align: center;">Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(range(1, 7) as $i)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">I{{ $i }}</td>
                                <td style="text-align: center;">
                                    <input type="number" name="intervales[I{{ $i }}][min]" class="form-control mx-auto" 
                                        value="{{ $i == 1 ? '0.00' : ($i == 2 ? '06.50' : ($i == 3 ? '07.50' : ($i == 4 ? '10.00' : ($i == 5 ? '12.00' : ($i == 6 ? '14.00' : '16.00'))))) }}">
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" name="intervales[I{{ $i }}][max]" class="form-control mx-auto" 
                                        value="{{ $i == 1 ? '06.50' : ($i == 2 ? '07.50' : ($i == 3 ? '10.00' : ($i == 4 ? '12.00' : ($i == 5 ? '14.00' : ($i == 6 ? '16.00' : '20.00'))))) }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator"></i> Calculer
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="printNote()">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </form>

            <!-- TABLEAU QUI S'AFFICHE À L'ÉCRAN (ACTUEL) -->
            @if(isset($resultats))
            <div class="table-responsive mt-5">
                <table class="table table-bordered table-screen">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 25px;">GPE</th>
                            <th style="text-align: center;">MFOG</th>
                            <th style="text-align: center;">MFOF</th>
                            <th style="text-align: center;">MFAG</th>
                            <th style="text-align: center;">MFAF</th>
                            @foreach(range(1,7) as $i)
                                <th style="text-align: center;">I{{ $i }}G</th>
                                <th style="text-align: center;">I{{ $i }}F</th>
                                <th style="text-align: center;">I{{ $i }}T</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultats as $groupeKey => $stats)
                        <tr>
                            <td>{{ $groupeKey }}</td> 
                            <td>{{ number_format($stats['max_moyenne_garcons'], 2) }}</td>
                            <td>{{ number_format($stats['max_moyenne_filles'], 2) }}</td>
                            <td>{{ number_format($stats['min_moyenne_garcons'], 2) }}</td>
                            <td>{{ number_format($stats['min_moyenne_filles'], 2) }}</td>
                            @foreach($stats['intervales'] as $intervalle => $data)
                                <td>{{ $data['garcons'] }}</td>
                                <td>{{ $data['filles'] }}</td>
                                <td>{{ $data['total'] }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- TABLEAU SPÉCIAL "CACHÉ" POUR L'IMPRESSION -->
            @if(isset($resultats))
            <div class="table-print" id="mainTable" style="display: none;">
                <!-- En-tête ou titre -->
                <h4 style="text-align:center; font-weight: bold;">TABLEAU ANALYTIQUE SPÉCIAL POUR IMPRESSION</h4>

                <table class="table table-bordered table-rapport">
                    <!-- EXEMPLE D'EN-TÊTE AVEC 2 LIGNES -->
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center;">GPE</th>
                            <!-- FORTE MOY -->
                            <th colspan="2" style="text-align: center;">FORTE MOY</th>
                            <!-- FAIBLE MOY -->
                            <th colspan="2" style="text-align: center; " class="bordleft">FAIBLE MOY</th>
                            <!-- Intervalles : 7 intervalles => 7 "blocs" => chacun colspan=3 -->
                            @php
                                // Pour afficher le libellé "0 <= M <= 6.5" etc.
                                // On suppose que vous avez toujours 7 intervalles dans $stats['intervales'],
                                // sinon adaptez la logique
                                $listeIntervales = [];
                                // On prend la première clé du $resultats pour extraire les intervalles
                                $anyKey = array_key_first($resultats);
                                if($anyKey !== null){
                                    foreach($resultats[$anyKey]['intervales'] as $intervalName => $val){
                                        // On construit un label du style "0 <= M <= 6.5"
                                        $min = 0; $max = 0;
                                        // Si vous avez besoin du min/max exact, il faut les récupérer
                                        // depuis la requête ou un autre endroit. 
                                        // Pour la démo, on mettra un placeholder "Intervalle X"
                                        // 
                                        // $label = number_format($val['min'],2).' <= M <= '.number_format($val['max'],2);
                                        // => si vous stockez min/max dans $val
                                        // 
                                        // Pour l'exemple, on fait juste "I1", "I2" ...
                                        $listeIntervales[] = $intervalName; 
                                    }
                                }
                            @endphp
                            @foreach($listeIntervales as $intervalName)
                                <th colspan="3" style="text-align: center;">
                                    {{ $intervalName }} <!-- ou "0 <= M <= 6.5" -->
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <!-- Sous-colonnes FORTE MOY -->
                            <th style="text-align: center;">G</th>
                            <th style="text-align: center;">F</th>
                            <!-- Sous-colonnes FAIBLE MOY -->
                            <th style="text-align: center;">G</th>
                            <th style="text-align: center;">F</th>
                            <!-- Sous-colonnes Intervalles (G, F, T) -->
                            @foreach($listeIntervales as $intervalName)
                                <th style="text-align: center;">G</th>
                                <th style="text-align: center;">F</th>
                                <th style="text-align: center;">T</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($resultats as $groupeKey => $stats)
                        <tr>
                            <!-- GPE -->
                            <td>{{ $groupeKey }}</td>
                            <!-- FORTE MOY (max_moyenne_garcons, max_moyenne_filles) -->
                            <td>{{ number_format($stats['max_moyenne_garcons'], 2) }}</td>
                            <td>{{ number_format($stats['max_moyenne_filles'], 2) }}</td>
                            <!-- FAIBLE MOY (min_moyenne_garcons, min_moyenne_filles) -->
                            <td>{{ number_format($stats['min_moyenne_garcons'], 2) }}</td>
                            <td class="bordleft">{{ number_format($stats['min_moyenne_filles'], 2) }}</td>
                            <!-- Intervalles -->
                            @foreach($stats['intervales'] as $intervalName => $data)
                                <td>{{ $data['garcons'] }}</td>
                                <td>{{ $data['filles'] }}</td>
                                <td>{{ $data['total'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            <!-- FIN DU TABLEAU IMPRIMÉ -->

        </div>
    </div>
</div>
    



<script>
      function injectTableStyles() {
    var style = document.createElement('style');
    style.innerHTML = `

    @media print {
    /* Masquer les éléments non imprimables, etc. */
    body * {
        visibility: hidden;
    }
    .table-print, .table-print * {
        visibility: visible;
    }
    .table-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
      @page { size: landscape; }
          table {
              width: 100%;
              border-collapse: collapse;
          }
              thead {
                            border: 1px solid #000;

      background-color: #f2f2f2;
      text-transform: uppercase;
    }

        .table td:nth-child(n+2), .table th:nth-child(n+2) {
          margin: 0 !important;
          padding: 5px 0 5px 5px !important;
          width: 100px !important;
          word-wrap: break-word !important;
          white-space: normal !important;
                    }
          th, td {
              padding: 0 !important;
              margin: 0 !important;
              border: 1px solid #000;
              text-align: center;
              font-size: 10px !important;
          }
              .titles {
              display: block  !important;
              }
          .classe-row {
              background-color: #f9f9f9;
              font-weight: bold;
          }`;
    document.head.appendChild(style);
  }


  function printNote() {
    injectTableStyles(); // Injecter les styles pour l'impressionoriginal
    var originalContent = document.body.innerHTML; // Contenu original de la page
    var printContent = document.getElementById('mainTable').innerHTML; // Contenu spécifique à imprimer
    document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer
    
    setTimeout(function() {
      window.print(); // Ouvrir la boîte de dialogue d'impression
      document.body.innerHTML = originalContent; // Restaurer le contenu original
    }, 1000);
  }
</script>
@endsection
