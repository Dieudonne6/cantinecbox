@extends('layouts.master')
@section('content')
{{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
<style>
  .table-container {
    width: 100%;
    overflow-x: auto;
    margin: 20px auto;
}
</style>
<div class="col-lg-12 grid-margin stretch-card">
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
    <h4 class="card-title">Liste selectives des élèves</h4>
    @if(Session::has('status'))
    <div id="statusAlert" class="alert alert-succes btn-primary">
      {{ Session::get('status')}}
    </div>
    @endif
    @if(Session::has('erreur'))
    <div class="alert alert-danger btn-primary">
      {{ Session::get('erreur')}}
    </div>
    @endif
    <div class="form-group row">
      <div class="col-3">
        <select class="js-example-basic-multiple w-100" multiple="multiple" name="CODECLAS[]" data-placeholder="Toutes les classes">         
          <option value="">Toutes les classes</option>
          @foreach ($eleve as $eleves)
            <option value="{{$eleves->CODECLAS}}">{{$eleves->CODECLAS}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-3">
        <select class="js-example-basic-multiple w-100" name="sexe" id="sexeSelect">     
          <option value="">Selectionner le Sexe</option>    
          <option value="2">Fille</option>
          <option value="1">Garcon</option>
        </select>
      </div>
      
      <div class="col-3">
        <div class="d-flex mb-2">
          <labe>Age compris</labe>
          <input type="text" class="mx-2 text-right" placeholder="{{ $minAgeFromDB }}"  id="minAge" name="" style="width: 50px !important;"> et 
          <input type="text"  placeholder="{{ $maxAgeFromDB }}" class="text-right ml-2" id="maxAge" style="width: 50px !important;">
        </div>
        {{-- <div class="d-flex">
          age
          <input type="text"  placeholder="8000" name="" style="width: 70px !important;" readonly> et 
          <input type="text"  placeholder="2000" style="width: 70px !important;" readonly>
        </div> --}}
      </div>
      <div class="col-1">
        <button class="btn btn-primary" id="submitBtnselective">Filtrer</button>
      </div>
      <div class="col-2">
        <button onclick="imprimerPage()" type="button" class="btn btn-primary">
          Imprimer Liste
        </button>
      </div>
    </div>
    <div class="table-responsive mb-4">
      <table id="myTable" class="table table-bordered">
        <thead>
          <tr>
            <th>
              N
            </th>
            <th>
              NOM
            </th>
            <th>PRENOM</th>
            <th>SEXE</th>
            <th>CLASSE</th>
            <th>DATE DE NAISSANCE</th>
          </tr>
        </thead>
        <tbody id="eleve-details">
          @foreach ($filterEleve as $index => $filterEleves)
            <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}" data-prenom="{{ $filterEleves->PRENOM }}" data-codeclas="{{ $filterEleves->CODECLAS }}">
              <td>{{ $index + 1 }}</td>
              <td>
                {{$filterEleves->NOM}}
              </td>
              <td>
                {{$filterEleves->PRENOM}}
              </td>
              <td>
                @if ($filterEleves->SEXE == 1)
                Masculin
                @elseif($filterEleves->SEXE == 2)
                Féminin
                @else
                Non spécifié
                @endif
              </td>
              <td>
                {{$filterEleves->CODECLAS}}
              </td>
              <td>  
                  {{ \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') }}

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div id="contenu" class="d-none">
      <div class="table-responsive mb-4">
        <table id="myTab" class="table table-bordered">
          <thead>
            <tr>
              <th>
                N
              </th>
              <th>
                NOM
              </th>
              <th>PRENOM</th>
              <th>SEXE</th>
              <th>CLASSE</th>
              <th colspan="2">DATE ET LIEU NAISSANCE</th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            @foreach ($filterEleve as $index => $filterEleves)
            <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}" data-prenom="{{ $filterEleves->PRENOM }}" data-codeclas="{{ $filterEleves->CODECLAS }}">
              <td>{{ $index + 1 }}</td>
              <td>
                {{$filterEleves->NOM}}
              </td>
              <td>
                {{$filterEleves->PRENOM}}
              </td>
              <td>
                @if ($filterEleves->SEXE == 1)
                M
                @elseif($filterEleves->SEXE == 2)
                F
                @else
                Non spécifié
                @endif
              </td>
              <td>
                {{$filterEleves->CODECLAS}}
              </td>
              <td>  
                {{ \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') }}


              </td>
              <td>   {{$filterEleves->LIEUNAIS}}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-4">
        <!-- Calculer l'effectif total et le nombre de filles -->
        @php
        $effectifTotal = count($filterEleve); // Total des élèves
        $femininCount = $filterEleve->where('SEXE', 2)->count(); // Nombre de filles
        $dateAujourdhui = \Carbon\Carbon::now()->format('d/m/Y'); // Date du jour
        @endphp

        <div>
            Effectif total : {{ $effectifTotal }} dont {{ $femininCount }} fille(s) 
            ....................................................................................... à Cotonou, le {{ $dateAujourdhui }}
        </div>
    </div>
    </div>
  </div>
</div>    
    <script>
      function imprimerPage() {
        let originalTitle = document.title;
        document.title = `Liste des élèves`;

        // Désactiver le DataTable et réinitialiser l'affichage
        let table = $('#myTable').DataTable();
        let currentPage = table.page();  
        table.destroy();

        setTimeout(function() {

        // Utiliser un titre par défaut si le champ est vide
          titreEtat = "Liste des élèves";

              // Créer un élément invisible pour l'impression
              let printDiv = document.createElement('div');
              printDiv.innerHTML = `
                  <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">
                          ${titreEtat}
                  </h1>
                  ${document.getElementById('contenu').innerHTML}
              `;
              
              // Appliquer des styles pour l'impression
              let style = document.createElement('style');
              style.innerHTML = `
                  @page { size: landscape; }
                  @media print {
                      body * { visibility: hidden; }
                      #printDiv, #printDiv * { visibility: visible; }
                      #printDiv { position: absolute; top: 0; left: 0; width: 100%; }

                      /* Styles pour masquer les éléments non désirés à l'impression */
                      .dt-end, .dt-start, .hide-printe, .offcanvas { display: none !important; }

                      /* Afficher les éléments cachés avec la classe d-none */
                      .d-none { display: block !important; }

                      /* Styles pour le tableau */
                      table th {
                          font-weight: bold !important;
                          font-size: 12px !important;
                      }
                      table th, table td {
                          font-size: 10px;
                          margin: 0 !important;
                          padding: 0 !important;
                          border-collapse: collapse !important;
                      }
                      table {
                          width: 100%;
                          border-collapse: collapse;
                          page-break-inside: auto;
                          border: 1px solid #000;
                      }
                      tr {
                          page-break-inside: avoid;
                          page-break-after: auto;
                      }
                      tfoot {
                          display: table-row-group;
                          page-break-inside: avoid;
                      }
                      tbody tr:nth-child(even) {
                          background-color: #f1f3f5;
                      }
                      tbody tr:nth-child(odd) {
                          background-color: #000;
                      }
                  }
              `;
              
              // Ajouter les styles et le contenu à imprimer au document
              document.head.appendChild(style);
              document.body.appendChild(printDiv);
              printDiv.setAttribute("id", "printDiv");
              
              // Lancer l'impression
              window.print();
              window.location.reload();

              // Nettoyer après l'impression
              document.body.removeChild(printDiv);
              document.head.removeChild(style);

          }, 100);
      }
    </script>
@endsection
   
    