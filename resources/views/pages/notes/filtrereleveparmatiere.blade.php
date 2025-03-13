@extends('layouts.master')  
@section('content')
<style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  thead {
    background-color: #f2f2f2;
    text-transform: uppercase;
  }
 
  th, td {
    padding: 6px;
    border: 1px solid #ddd;
    text-align: center;
  }
</style>
<div class="container">
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
      <h4 class="card-title">Editions des releves des notes</h4>
      <div class="form-group row">
        <div class="col-3">
          <button class="btn btn-primary" onclick="printNote()">Imprimer</button>
        </div>
      </div>
      <div class="row grid-margin stretch-card">
        <div class="col-lg-12 mx-auto " id="printNot">
          <div class="d-none titles">
            <h3 style="text-transform: uppercase; margin-bottom: 1rem; text-align: center">Releves de notes</h3>
            <p>Classe : {{$classe}} </p>
            <p>Matiere : {{$matiere}} </p>
          </div>

          <div class="table-responsive mb-4">
            <table>
              <thead>
                <tr>
                  <th rowspan="2">N</th>
                  <th rowspan="2">Matricule</th>
                  <th rowspan="2">Nom</th>
                  <th rowspan="2">Prenom</th>                  
                  <th colspan="4">Interrogations</th> 
                  <th colspan="3">Devoirs</th> 
                  <th rowspan="2">TEST</th>
                </tr>
                <tr>
                  <th>Int1</th>
                  <th>Int2</th>
                  <th>Int3</th>
                  <th>MI</th>
                  <th>Dev1</th>
                  <th>Dev2</th>
                  <th>Dev3</th>
                </tr>
              </thead>
              <tbody>
                @php
                $count = 1;
                @endphp
                @foreach ($notes as $note)
                <tr>
                  <td>{{ $count }}</td>
                  <td>{{ $note->MATRICULE ?? '****'  }}</td>
                  <td>{{ $note->nom ?? '****' }}</td>
                  <td>{{ $note->prenom ?? '****' }}</td>
                  <td>{{ $note->INT1 ?? '****' }}</td>
                  <td>{{ $note->INT2 ?? '****' }}</td>
                  <td>{{ $note->INT3 ?? '****' }}</td>
                  <td>{{ $note->MI ?? '****' }}</td>
                  <td>{{ $note->DEV1 ?? '****'  }}</td>
                  <td>{{ $note->DEV2 ?? '****'  }}</td>
                  <td>{{ $note->DEV3 ?? '****'  }}</td>
                  <td>{{ $note->TEST ?? '****' }}</td>
                </tr>
                @php
                  $count++; 
                @endphp
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
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
      text-transform: uppercase;
    }
          th, td {
              padding: 6px;
              border: 1px solid #ddd;
              text-align: center;
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
    injectTableStyles(); // Injecter les styles pour l'impression
    var originalContent = document.body.innerHTML; // Contenu original de la page
    var printContent = document.getElementById('printNot').innerHTML; // Contenu spécifique à imprimer
    document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer
    
    setTimeout(function() {
      window.print(); // Ouvrir la boîte de dialogue d'impression
      document.body.innerHTML = originalContent; // Restaurer le contenu original
    }, 1000);
  }
  
</script>
@endsection

