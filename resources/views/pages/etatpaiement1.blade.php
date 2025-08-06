@extends('layouts.master')
@section('content')
<style>
  table.dataTable th,
  table.dataTable td {
    margin: 0 !important;
    padding: 10px 0 10px 10px !important;
    border-collapse: collapse !important;
    font-size: 14px;
  }
  @media screen {
    tfoot {
      display: none;
    }
  }
  @media print {
    .table-bordered th, .table-bordered td {
      border: 1px solid #000 !important;
    }
  } 
  @media print {
    tfoot {
      display: table-row-group;
    }
    
    table {
      page-break-inside: auto;
    }
    
    tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }
    
    tfoot {
      page-break-inside: avoid;
    }
  }
  .btn-arrow {
      position: absolute;
      top: 0px; /* Ajustez la position verticale */
      left: 0px; /* Positionnez à gauche */
      background-color: transparent !important;
      border:1px !important;
      text-transform: uppercase !important;
      font-weight: bold !important;
      cursor: pointer!important;
      font-size: 17px!important; /* Taille de l'icône */
      color: #b51818!important; /* Couleur de l'icône */
  }
  
  .btn-arrow:hover {
      color: #b700ff !important; /* Couleur au survol */
  }

</style>


{{-- <div class="container "> --}}
  
  <div class="col-lg-12 grid-margin stretch-card">
    {{-- <form action="{{ url('/traitementetatpaiement') }}" method="POST">
      @csrf
      <div class="form-group row">
        <div class="col">
          <label for="debut">Du</label>
          <input name="debut" id="debut" type="date" value="{{ old('date', now()->subMonths(3)->format('Y-m-d')) }}" class="typeaheads">
        </div>
        <div class="col">
          <label for="fin">Au</label>
          <input name="fin" id="fin" type="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="typeaheads">
        </div>
        
        <div class="col">
          <!-- Bouton de soumission de formulaire -->
          <label for="debut" style="visibility: hidden">supprimer paiement</label>
          <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
        
        <div class="col">
          <label for="debut" style="visibility: hidden">Du</label>
          <button onclick="imprimerPage()" type="button" class="btn btn-primary w-100">
            Imprimer Etat
          </button>
        </div>
        
      </div>
    </form> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="card">
      <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <h5 style="text-align: center"> Liste des paiements de la periode du {{$dateFormateedebut}} au {{$dateFormateefin}}</h5>
        <div class="col mb-2">
          <label for="debut" style="visibility: hidden">Du</label>
          <button onclick="imprimerPage()" type="button" class="btn btn-primary" style="display: block">
            Imprimer Etat
          </button>
        </div>
        
        {{-- @if (Session::has('status'))
        <div id="statusAlert" class="alert alert-succes btn-primary">
          {{ Session::get('status')}}
        </div>
        @endif --}}
        
        @if (Session::has('statuspaiement'))
        <div class="alert alert-success">
          {{ Session::get('statuspaiement') }}
        </div>
        @endif
        {{-- @elseif (Session::has('statuspaiement'))
        @if (Session::has('statuspaiement'))
        <div id="statusAlert" class="alert alert-succes btn-primary">
          {{ Session::get('statuspaiement')}}
          {{ Session::get('statuspaiement')}}
        </div>
        @endif
        @else
        
        <div id="statusAlert" class="alert alert-succes btn-primary">
          {{ Session::get('statuspaiement')}}
          <p>lolllllllllllllllllllllll</p>
        </div>
        @endif --}}
        <div id="contenu">
          <div class="table-responsive">
            <table id="myTable" class="table table-bordered">
              <thead>
                <tr>
                  <th>N</th>
                  <th>Classe</th>
                  <th>Eleve</th>
                  <th>Montant</th>
                  <th>Mois payé(s)</th>
                  <th>Date</th>
                  <th>Reference</th>
                  <th>Caissier</th>
                </tr>
              </thead>
              <tbody>
                @php $totalMontant = 0; @endphp
                @foreach ($paiementsAvecEleves as $index => $resultatsIndividuel)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $resultatsIndividuel['classe_eleve'] }}</td>
                  <td>{{ $resultatsIndividuel['nomcomplet_eleve'] }}</td>
                  <td class="montant">{{ $resultatsIndividuel['montant'] }}</td>
                  <td>{{ $resultatsIndividuel['mois'] }}</td>

                  <td>
                    {{
                      \Carbon\Carbon::hasFormat($resultatsIndividuel['date_paiement'], 'Y-m-d H:i:s') 
                      ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $resultatsIndividuel['date_paiement'])->format('d/m/Y') 
                      : (\Carbon\Carbon::hasFormat($resultatsIndividuel['date_paiement'], 'd/m/Y H:i:s') 
                      ? \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $resultatsIndividuel['date_paiement'])->format('d/m/Y') 
                      : 'Format de date non supporté')
                    }}
                  </td>
                  <td>{{ $resultatsIndividuel['reference'] }}</td>
                  <td>{{ $resultatsIndividuel['user'] }}</td>

                </tr>
                @php $totalMontant += $resultatsIndividuel['montant']; @endphp

                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th id="totalMontant" colspan="4">{{ number_format($totalMontant, 0, '', ' ') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        

      </div>
    </div>
  </div>
  {{-- @endif --}}
  {{-- </div> --}}
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer cette ligne ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <form action="{{ url('supprimerpaiement/' . $resultatsIndividuel['id_paiementcontrat']) }}"
          method="post">
          @csrf
          @method('DELETE')
          <input type="submit" class="btn btn-danger" value="Supprimer">
        </form>
        
      </div>
    </div>
  </div>
</div>

@endsection


<script>
  function imprimerPage() {
      let table = $('#myTable').DataTable();
      let currentPage = table.page();  
      table.destroy(); // Sauvegarder la page actuelle
      let originalTitle = document.title;
      document.title = `Liste des paiements de la periode du {{$dateFormateedebut}} au {{$dateFormateefin}}`;
  
      setTimeout(function() {
          // Créer un élément invisible pour l'impression
          let printDiv = document.createElement('div');
          printDiv.innerHTML = '<h1 style="font-size: 20px; text-align: center;">Liste des paiements de la periode du {{$dateFormateedebut}} au {{$dateFormateefin}} </h1>' +
          document.getElementById('contenu').innerHTML;
          
          // Appliquer des styles pour l'impression
          let style = document.createElement('style');
          style.innerHTML = `@page { size: landscape; }
              @media print {
                  body * { visibility: hidden; }
                  #printDiv, #printDiv * { visibility: visible; }
                  #printDiv { position: absolute; top: 0; left: 0; width: 100%; }
                  .dt-end { display: none !important; }
                  .dt-start { display: none !important; }
                  .table td:nth-child(n+2) {
                      margin: 0 !important;
                      padding: 10px 0 5px 5px !important;
                      width: 500px !important;
                      word-wrap: break-word !important;
                      white-space: normal !important;
                  }
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
                      background-color: #ffffff;
                  }
              }
          `;
          document.head.appendChild(style);
          document.body.appendChild(printDiv);
          printDiv.setAttribute("id", "printDiv");
          
          window.print();
          
          $('#myTable').DataTable({
              "pageLength": 10,  // Remettre la pagination à 10 par défaut (ou la valeur souhaitée)
          });
          
          // Nettoyer après l'impression
          document.body.removeChild(printDiv);
          document.head.removeChild(style);
      }, 200);
  }
  </script>
  
{{-- <script>
  
  function imprimerPage() {
  let table = $('#myTable').DataTable();
  let currentPage = table.page();
  table.page.len(-1).draw();
  setTimeout(function() {
  var tableElement = document.getElementById('myTable');
  tableElement.classList.remove('dataTable');
  
  var columns = tableElement.querySelectorAll('.hide-on-print');
  columns.forEach(function(column) {
  column.style.display = 'none';
  });
  
  var page = window.open('', '_blank');
  page.document.write('<html><head><title>Paiement du {{$dateFormateedebut}} au {{$dateFormateefin}}</title>');
    page.document.write('<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />');
    page.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >');
    page.document.write('<style>@media print { .dt-end { display: none !important; } }</style>');
    page.document.write('<style>@media print { .dt-start { display: none !important; } }</style>');
    page.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } .cell-classe { background-color: #f8f9fa; } .cell-eleve { background-color: #e9ecef; } .cell-montant { background-color: #dee2e6; } .cell-mois { background-color: #ced4da; } .cell-date { background-color: #adb5bd; } .cell-reference { background-color: #6c757d; } .cell-action { background-color: #343a40; color: #fff; } tbody tr:nth-child(even) { background-color: #f1f3f5; } tbody tr:nth-child(odd) { background-color: #ffffff; } </style>');
    page.document.write('</head><body>');
      page.document.write('<div>' + document.getElementById('contenu').innerHTML + '</div>');
      page.document.write('</body></html>');
      page.document.close();
      page.onload = function() {
      page.print();
      page.close();
      table.page.len(10).draw();  
      table.page(currentPage).draw(false);            
      columns.forEach(function(column) {
      column.style.display = '';
      });
      location.reload(); 
      };
      }, 200);
      }
    </script>
    --}}
    
    <!-- Assurez-vous d'inclure jQuery avant d'utiliser les méthodes AJAX -->
    
    {{-- <script>
      document.addEventListener("DOMContentLoaded", function() {
      var form = document.querySelector('#formulaire');
      
      form.addEventListener('submit', function(event) {
      event.preventDefault(); // Empêche le formulaire de se soumettre normalement
      
      var debut = document.querySelector('#debut').value;
      var fin = document.querySelector('#fin').value;
      
      // Fonction pour formater les dates au format yyyy-mm-dd
      function formatDate(dateString) {
      var parts = dateString.split("-");
      return parts[0] + '-' + parts[1] + '-' + parts[2];
      }
      
      // Convertir les dates au format yyyy-mm-dd
      var debutFormatted = formatDate(debut);
      var finFormatted = formatDate(fin);
      
      console.log(finFormatted);
      
      // Envoie des données via AJAX
      $.ajax({
      type: 'POST',
      url: '{{ route('traitementetatpaiement') }}',
      data: {
      debut: debutFormatted,
      fin: finFormatted,
      _token: '{{ csrf_token() }}' // CSRF token Laravel
      },
      success: function(data) {
      // Traitement des données de réponse
      console.log(data);
      
      var tbody = document.getElementById('eleve-details');
      tbody.innerHTML = ''; // Effacer le contenu actuel du tableau
      
      // Itérer sur chaque objet dans le tableau
      data.forEach(function(item) {
      var tr = document.createElement('tr');
      tr.innerHTML = `
      <td>${item.date_paiementcontrat}</td>
      <td>${item.reference_paiementcontrat}</td>
      <td>${item.montant_paiementcontrat}</td>
      <td>${item.mois_paiementcontrat}</td>
      <!-- Insérer les autres colonnes ici -->
      `;
      tbody.appendChild(tr);
      });
      },
      error: function(xhr, status, error) {
      // Gestion des erreurs
      console.error(error);
      }
      });
      });
      
      
      });
    </script> --}}
    