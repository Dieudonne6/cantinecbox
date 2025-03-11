@extends('layouts.master')
@section('content')

<div class="main-panel-10">
  <div class="content-wrapper">
    
    <div class="row">          
      <div class="col-12">
        <div class="card mb-6">
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
            <h4 class="card-title">Situation financière globale</h4>
            <div class="row gy-3">
              <div class="demo-inline-spacing">
                <button type="button" class="btn btn-primary" id="printBtn" onclick="imprimerPage();">Imprimer</button>    
              </div>
            </div>
          </div>
        </div>
      </div>       
    </div>
    
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-responsive" id="contenu">
                  <table id="myTable" class="table custom-table print-table">
                    <thead>
                      <tr>
                        <th>MATRICULE</th>
                        <th>NOM ET PRENOMS</th>
                        <th>CLASSE</th>
                        <th>APAYER</th>
                        <th>PAYE</th>
                        <th>RESTE</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($resultats as $resultat)
                      <tr>
                        <td>{{ $resultat['MATRICULE'] }}</td>
                        <td>{{ $resultat['NOM'] }} {{ $resultat['PRENOM'] }}</td>
                        <td>{{ $resultat['CLASSE'] }}</td>
                        <td>{{ $resultat['RESTE'] + $resultat['PAYE'] }}</td>
                        <td>{{ $resultat['PAYE'] }}</td>
                        <td>{{ $resultat['RESTE'] }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td>{{ $totalAPayer }}</td>
                        <td>{{ $totalPaye }}</td>
                        <td>{{ $totalReste }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ajout des styles -->
  <style>
    @media print {
      /* Masquer les éléments non désirés à l'impression */
      .navbar, .sidebar, .footer, button {
        display: none !important;
      }

      /* Table styling for print */
      table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
      }

      table th, table td {
        font-size: 10px;
        padding: 5px;
        text-align: left;
        border: 1px solid black;
      }

      tbody tr:nth-child(even) {
        background-color: #f1f3f5;
      }

      tbody tr:nth-child(odd) {
        background-color: #fff;
      }
    }
  </style>

  <!-- Inclure jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <!-- Script pour imprimer -->
  <script>
    function imprimerPage() {
      // Sauvegarder le titre original de la page
      let originalTitle = document.title;
      document.title = 'Situation financière globale';

      // Obtenir le tableau DataTable actif et les données triées
      let table = $('#myTable').DataTable();
      let sortedData = table.rows({order: 'applied'}).data().toArray(); // Récupérer les données triées

      // Générer le contenu HTML trié à partir des données récupérées
      let tableBody = '';
      sortedData.forEach(function(row) {
        tableBody += `<tr>
          <td>${row[0]}</td>
          <td>${row[1]}</td>
          <td>${row[2]}</td>
          <td>${row[3]}</td>
          <td>${row[4]}</td>
          <td>${row[5]}</td>
        </tr>`;
      });

      // Créer un élément invisible pour l'impression avec les données triées
      let printDiv = document.createElement('div');
      printDiv.innerHTML = `
        <h1 style="font-size: 20px; text-align: center;">Situation financière globale</h1>
        <table id="printTable" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
          <thead>
            <tr>
              <th>MATRICULE</th>
              <th>NOM ET PRENOMS</th>
              <th>CLASSE</th>
              <th>APAYER</th>
              <th>PAYE</th>
              <th>RESTE</th>
            </tr>
          </thead>
          <tbody>
            ${tableBody}
          </tbody>
        </table>
      `;

      // Appliquer des styles spécifiques pour l'impression
      let style = document.createElement('style');
      style.innerHTML = `
        @page { size: landscape; }
        @media print {
          body * { visibility: hidden; }
          #printDiv, #printDiv * { visibility: visible; }
          #printDiv { position: absolute; top: 0; left: 0; width: 100%; }
          table th, table td {
            font-size: 10px;
            padding: 5px;
            text-align: left;
            border: 1px solid black;
          }
        }
      `;
      
      // Ajouter les styles et le contenu à imprimer
      document.head.appendChild(style);
      document.body.appendChild(printDiv);
      printDiv.setAttribute("id", "printDiv");

      // Lancer l'impression
      window.print();
      
      // Nettoyer après l'impression
      window.location.reload();
      document.body.removeChild(printDiv);
      document.head.removeChild(style);
    }
  </script>
@endsection