@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
        <br>
        <br>
        <br>
        @foreach ($resultats as $index => $resultat)
        <div class="bulletin" style="{{ $index < count($resultats) - 1 ? 'page-break-after: always;' : '' }}">
          <br>
          <br>
          <br>
          <br>
          <br>
          @if (isset($option['entete']) && $option['entete'])
            <h6>{{ $entete }}</h6>
          @endif
          <div id="carre" style="width: 125px; height: 125px; background-color: transparent; border: 1px solid black;"></div>
          <br>
          <br>
          <br>
          <div class="watermark">Scodelux</div>
          <div class="d-flex">
            <div id="donneeleve" style="width: 600px; height: 80px; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                <h5 style="margin-top: 5px;">NOM : {{ $resultat['nom'] }}</h5>
                <h5 >PRENOM : {{ $resultat['prenom'] }}</h5>
                <div class="d-flex">
                  <h5>Redoublant (e) : 
                      <input type="checkbox" name="redoublant" id="redoublant_oui" disabled {{ $resultat['redoublant'] == 1 ? 'checked' : '' }}>
                      <label for="redoublant_oui">Oui</label>
                      <input type="checkbox" name="redoublant" id="redoublant_non" disabled {{ $resultat['redoublant'] == 2 ? 'checked' : '' }}>
                      <label for="redoublant_non">Non</label>
                  </h5>
                  @if (isset($option['matricule']) && $option['matricule'])
                    <h5 style="margin-left: 40px;">Matricule : {{ $resultat['matricule'] }}</h5>
                  @endif
                </div>
            </div>
            <div  style="width: 300px; height: 80px; background-color: transparent; border: 1px solid black; border-radius: 10px;">
              <h5 style="margin-top: 5px;">Année scolaire : {{ $resultat['anneScolaire'] }}</h5>
              <br>
              <h5 id="periode" class="text-center">{{$resultat['periode']}} </h5>
            </div>
            <div id="classe" style="width: 300px; height: 80px; background-color: transparent; border: 1px solid black; border-radius: 10px;">
              <h5 style="margin-top: 5px;">Classe : {{ $resultat['classe'] }}</h5>
              <br>
              <h5>Effectif : {{ $resultat['effectif'] }}</h5>
            </div>
          </div>
          <table style="width: 1187px;" id="tableau">
            <thead>
              <tr>
                <th class="text-center" style="font-weight: normal; width: 150px;">Disciplines</th>
                <th class="text-center" style="font-weight: normal; width: 50px;">Cf</th>
                <th class="text-center" style="font-weight: normal; width: 50px;">Moy.Int</th>
                <th class="text-center" style="font-weight: normal; width: 50px;">Dev.1</th>
                <th class="text-center" style="font-weight: normal; width: 50px;">Dev.2</th>
                @if (!isset($option['masquer_devoir3']))
                <th class="text-center" style="font-weight: normal; width: 50px;">Dev.3</th>
                @endif
                @if(!isset($option['note_test']) || !$option['note_test'])
                  <th class="text-center" style="font-weight: normal; width: 50px;">Moy.20</th>
                  <th class="text-center" style="font-weight: normal; width: 50px;">Moy.coef</th>
                @endif
                @if (isset($option['note_test']) && $option['note_test'])
                <th class="text-center" style="font-weight: normal; width: 50px;">Moy. part</th>
                <th class="text-center" style="font-weight: normal; width: 50px;">Compo</th>
                  <th class="text-center" style="font-weight: normal; width: 50px;">Moy.20</th>
                  <th class="text-center" style="font-weight: normal; width: 50px;">Moy.coef</th>
                  @else
                  <th class="text-center" style="font-weight: normal; width: 50px;">Faible moy.</th>
                  <th class="text-center" style="font-weight: normal; width: 50px;">Forte moy.</th>
                  @endif
                  @if(isset($option['rang_matiere']) && $option['rang_matiere'])
                  <th class="text-center" style="font-weight: normal; width: 50px;">Rang par matière</th>
                  @endif
                <th class="text-center" style="font-weight: normal; width: 200px;">Appréciations des professeurs</th>
              </tr>
            </thead>
            <tbody>
              @php $i = 0; $note_conduite = null; $total_coefficients = 0; $total_moyenne_coeffs = 0; @endphp
              @foreach ($resultat['matieres'] as $matiere)
              @php
              $i++;
              $moyenne_part = $matiere['moyenne_sur_20'];
              if ($matiere['test'] != null && isset($option['note_test']) && $option['note_test']) {
                $moyenne_sur_20 = ($moyenne_part + $matiere['test']) / 2;
              } else {
                $moyenne_sur_20 = $matiere['moyenne_sur_20'];
              }
              if ($matiere['coefficient'] != -1) {
                $moyenne_coeff = $moyenne_sur_20 * $matiere['coefficient'];
                $total_coefficients += $matiere['coefficient'];
              } else if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'integral') {
                $moyenne_coeff = $matiere['surplus'];
              } else if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'Aucun') {
                $moyenne_coeff = $matiere['moyenne_coeff'];
              } else if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'intervalle') {
                $moyenne_coeff = $matiere['moyenne_intervalle'];
              }
              if ($matiere['code_matiere'] == $request->input('conduite')) {
                $note_conduite = $matiere['moyenne_sur_20'];
              }
              $total_moyenne_coeffs += $moyenne_coeff;
              if (!isset($option['note_conduite']) && $matiere['code_matiere'] == $request->input('conduite')) {
                $total_moyenne_coeffs = $total_moyenne_coeffs - $note_conduite;
                $total_coefficients = $total_coefficients - $matiere['coefficient'];
              }
            @endphp
            @if (!isset($option['note_conduite']) && $matiere['code_matiere'] == $request->input('conduite'))
              @continue;
            @endif
                <tr>
                <td>{{ $matiere['nom_matiere'] }}</td>
                <td>{{ $matiere['coefficient'] }}</td>
                <td>{{ $matiere['moyenne_interro'] ?? '**.**' }}</td>
                <td>{{ $matiere['devoir1'] ?? '**.**' }}</td>
                <td>{{ $matiere['devoir2'] ?? '**.**' }}</td>
                @if (!isset($option['masquer_devoir3']))
                  <td>{{ $matiere['devoir3'] ?? '**.**' }}</td>
                @endif
                @if(!isset($option['note_test']) || !$option['note_test'])
                  <td>{{ number_format($moyenne_sur_20, 2) ?? '**.**' }}</td>
                  @if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'integral')
                    <td>+ {{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                  @else
                    <td>{{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                  @endif
                @endif
                @if (isset($option['note_test']) && $option['note_test'])
                <td>{{ number_format($moyenne_part, 2) ?? '**.**' }}</td>
                <td>{{ $matiere['test'] ?? '**.**' }}</td>
                <td>{{ number_format($moyenne_sur_20, 2) ?? '**.**' }}</td>
                <td>{{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                @else
                <td>{{ number_format($matiere['plusFaibleMoyenne'], 2) ?? '**.**' }}</td>
                <td>{{ number_format($matiere['plusForteMoyenne'], 2) ?? '**.**' }}</td>
                @endif
                @if(isset($option['rang_matiere']) && $option['rang_matiere'])
                  <td>{{ $matiere['rang'] }}</td>
                @endif
                @if (isset($option['appreciation_prof']))
                  <td>{{ $matiere['mentionProf'] }}</td>
                @else
                  <td></td>
                @endif
              </tr>
              @endforeach
              <tr>
                <td>Total</td>
                <td>{{ $total_coefficients }}</td>
                <td colspan="4"></td> <!-- Ajustez le colspan selon le nombre de colonnes à fusionner -->
                <td>{{ number_format($total_moyenne_coeffs, 2) }}</td>
                <td colspan="5"></td> <!-- Ajustez le colspan selon le nombre de colonnes restantes -->
              </tr>
            </tbody>
          </table>
          <div id="ligne" style="width: 1187px; height: 1px; background-color: rgb(0, 0, 0);"></div>
          <div class="d-flex">
            <div style="width: 280px; height: 85px; background-color: transparent; border: 1px solid black; border-radius: 10px;">
              <h4 class="text-center" style="margin-top: 20px;">Bilan Trimestriel</h4>
            </div>
            <div>
              <h5 style="margin-left: 10px;">Moyenne Trimestrielle : {{$total_moyenne_coeffs != 0 ? number_format($total_moyenne_coeffs / $total_coefficients, 2) : '**.**'}}</h5>
              <table id="tableau_bilan" style="width: 500px; margin-left: 60px;">
                <thead>
                  <tr>
                    <th style="font-weight: normal;">Plus forte Moy.</th>
                    <th style="font-weight: normal;">Plus faible Moy.</th>
                    <th style="font-weight: normal;">Moy. de la classe</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center" style=""><strong>12</strong></td>
                    <td class="text-center"><strong>15</strong></td>
                    <td class="text-center"><strong>16</strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div  style="width: 350px; height: 85px; background-color: transparent; border: 1px solid black; border-radius: 10px; margin-left: 10px;">
              <h8><strong>Bilan des matières littéraires :</strong></h8>
              <br>
              <h8><strong>Bilan des matières scientifiques :</strong></h8>
              <br>
              <h8><strong>Bilan des matières fondamentales :</strong></h8>
            </div>
          </div>
          <div id="ligne" style="width: 1187px; height: 1px; background-color: rgb(0, 0, 0);"></div>
          <div class="d-flex">
            <div  style="width: 230px; height: auto; background-color: transparent; border: 1px solid black; border-radius: 10px;">
              <h6 style="margin-top: 5px;" class="text-center">Mention du conseil des Prof.</h6>
              <div class="d-flex">
                <h8>Félicitations.........................................................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="felicitation" id="felicitation" disabled>
              </div>
              <div class="d-flex">
                <h8>Encouragements.........................................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="encouragement" id="encouragement" disabled>
              </div>
              <div class="d-flex">
                <h8>Tableau d'honneur....................................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="tableau_dhonneur" id="tableau_dhonneur" disabled>
              </div>
              <div class="d-flex">
                <h8>Avertissement/Travail...........................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="avertissement_travail" id="avertissement_travail" disabled>
              </div>
              <div class="d-flex">
                <h8>Avertissement/Discipline...................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="avertissement_discipline" id="avertissement_discipline" disabled>
              </div>
              <div class="d-flex">
                <h8>Blâme/Travail...................................................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="blame_work" id="blame_work" disabled>
              </div>
              <div class="d-flex">
                <h8>Blâme/Discipline..........................................</h8>
                <input style="margin-left: 5px;" type="checkbox" name="blame_discipline" id="blame_discipline" disabled>
              </div>
            </div> 
            <div id="appreciation" style="width: 560px; height: 180px; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column;">
              <div style="flex: 1; display: flex;justify-content: center;">
                <h6 style="margin-top: 5px;" class="text-center"><u>Appréciation du chef d'établissement</u></h6>
              </div>
              <hr style="border: 1px solid black; margin: 0;">
              <div style="flex: 1;justify-content: center;">
                <h6 class="text-center"><u>Appréciations du professeur principal</u></h6>
                <h7>Conduite : <span style="border-bottom: 1px dotted #000; width: 131px; display: inline-block;">{{ $note_conduite }}</span></h7>
                <h7>Travail........................................................................................................</h7>
                <h7>............................................................................................................................................................................................................</h7>
              </div>
            </div>
            <div id="signature" style="width: 410px; height: 180px; background-color: transparent; border: 1px solid black; border-radius: 10px;">
              <h5 id="signature_chef" style="margin-top: 5px;" class="text-center"><u>Signature et cachet du chef d'établissement</u></h5>
              <h7>CCC</h7>
            </div>
          </div>
          <br>
          <div class="d-flex">
            <div class="flex-grow-1">
              <p><u>Code web: </u></p>
            </div>
            <div class="flex-grow-1 justify-content-end" style="margin-left: 600px;">
              <p>Edité le</p>
            </div>
          </div>
        </div>
        <br>
        <div>
          <div id="message" style="border: none; width: 100%; height: auto; text-align: justify;">
            {!! html_entity_decode($request->input('msgEnBasBulletin')) !!}
          </div>
        </div>
        @endforeach
    </div>
</div>
<style>
  th, td {
    border: 1px solid black;
  }
  .footer {
    display: none
  }
  h8 {
    font-size: 12px;
  }
  .watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg); /* Rotation pour diagonale */
    font-size: 100px;
    color: rgb(0, 0, 0);
    opacity: 1;
    z-index: 1000;
    font-family: 'Cursive', sans-serif;
    user-select: none;
    pointer-events: none;
  }
  #tableau td {
    text-align: center;
  }
  @media print {
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg); /* Rotation pour diagonale */
      font-size: 100px;
      color: rgb(0, 0, 0);
      opacity: 1;
      z-index: 1000; 
      font-family: 'Cursive', sans-serif;
      user-select: none;
      pointer-events: none;
        }
    .sidebar, .navbar, .footer, .noprint, button {
      display: none !important;
      overflow: hidden !important;
    }
    #tableau {
      width: 968px !important;
    }
    #ligne {
      width: 965px !important;
    }
    #carre {
      display: block !important;
    }
    .card-body {
      margin-top: 50px !important;
    }
    #tableau_bilan {
      width: 400px !important;
    }
    #appreciation {
      width: 560px !important;
    }
    #signature {
      width: auto !important;
    }
    #signature_chef {
      font-size: 16px !important;
    }
    #donneeleve {
      width: 500px;
    }
    #periode {
      margin-top: -20px !important;
    }

  }
</style>
<script>
  function imprimerliste() {
          var content = document.querySelector('.main-panel').innerHTML;
          var originalContent = document.body.innerHTML;
  
          document.body.innerHTML = content;
          window.print();
  
          document.body.innerHTML = originalContent;
      }
  </script>
@endsection