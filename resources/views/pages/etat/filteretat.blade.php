<?php use Carbon\Carbon; ?>

@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  /* Suppression du padding et margin pour le tableau */
  table.dataTable {
    margin: 0 !important;
    padding: 0;
    border-collapse: collapse !important;
  }
  table.dataTable th {
    padding: 15px !important;
    font-size: 13px !important;
  }
  table.dataTable th,
  table.dataTable td {
    margin: 0 !important;
    padding: 10px 0 10px 10px;
    border-collapse: collapse !important;
    font-size: 13px;
  }
  .table td:nth-child(2) {
    line-height: 1.2;
  }
  #myTable td:nth-child(n+2) {
    width: 100px;
    word-wrap: break-word;
    white-space: normal;
  }
  /* Masquer la ligne des totaux par défaut */
  .totals-row {
    display: none;
  }
  
  /* Afficher la ligne des totaux uniquement lors de l'impression */
  @media print {
    .totals-row {
      display: table-row;
    }
    .classe-start {
      display: none;
    }
    .table-bordered th, .table-bordered td {
      border: 1px solid #000 !important;
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

<body>
  <script>
    var test = format(50000000);
  </script>
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <h4 class="card-title" style="text-align: center">Etats des droits constatés</h4>
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
            
          </div>
        </form>  --}}
        <div class="col-lg-2">
          <button onclick="imprimePage()" class="btn btn-primary">Imprimer</button>
        </div>
        <div id="contenu">
          
          <div class="table-responsive pt-3">
            {{-- <table id="myTable" class="table table-striped table-sm" > --}}
              <table id="myTable" class="table table-bordered">
                <thead>
                  <tr>
                    <th>N</th>
                    <th>Élève</th>
                    <th class="classe-start">Classe</th>
                    @php
                    // Mapping des mois par ordre personnalisé
                    $moisMapping = [
                    13 => 'Inscription', // Inscription correspond à id_moiscontrat 13
                    9 => 'Septembre',
                    10 => 'Octobre',
                    11 => 'Novembre',
                    12 => 'Décembre',
                    1 => 'Janvier',
                    2 => 'Février',
                    3 => 'Mars',
                    4 => 'Avril',
                    5 => 'Mai',
                    6 => 'Juin'
                    ];
                    @endphp
                    
                    @foreach ($moisMapping as $id_mois => $nom_mois)
                    <th>{{ $nom_mois }}</th>
                    @endforeach
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  // Initialiser les totaux des colonnes
                  $totalsParMois = array_fill_keys(array_keys($moisMapping), 0);
                  @endphp
                  
                  
                  @foreach ($eleves as $index => $eleve)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="space-nom">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                    <td class="classe-start">{{ $eleve->CODECLAS }}</td>
                    @php
                    $totalMoisEleve = 0; // Initialiser le total pour cet élève
                    @endphp
                    @foreach ($moisMapping as $id_mois => $nom_mois)
                    <td>
                      @php
                      $montantTotalMois = 0;
                      $paiementTrouve = false;
                      @endphp
                      
                      @foreach ($eleve->contrats as $contrat)
                      @foreach ($contrat->paiements as $paiement)
                      @if ($paiement->mois_paiementcontrat == $id_mois)
                      {{ \Carbon\Carbon::parse($paiement->date_paiementcontrat)->format('d/m/Y') }}<br><br>
                      {{ $paiement->montant_paiementcontrat }}
                      @php
                      $montantTotalMois += $paiement->montant_paiementcontrat;
                      $paiementTrouve = true;
                      @endphp
                      @endif
                      @endforeach
                      @endforeach
                      
                      @if (!$paiementTrouve)
                      @if ($id_mois != 13)
                      Pas inscrit
                      @else
                      0
                      @endif
                      @endif
                      
                      @php
                      $totalsParMois[$id_mois] += $montantTotalMois; // Ajouter au total de la colonne
                      $totalMoisEleve += $montantTotalMois; // Ajouter au total de l'élève
                      @endphp
                    </td>
                    @endforeach
                    <td>{{ formate($totalMoisEleve) }}</td>
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
  <?php 
  function formate($nbre)
{
    $str_nbre = "";
    $str_nbre_entiere = "";
    $str_nbre_decimal = "";

    $entiere = explode(".", (string)$nbre)[0];  // Partie entière du nombre
    $decimal = explode(".", (string)$nbre)[1] ?? null;  // Partie décimale du nombre, si elle existe

    if ($entiere !== null) {
        // Découpage de la fin vers le début, par longueur de 3
        for ($cpt = strlen($entiere) - 3; $cpt >= 0; $cpt -= 3) {
            $str_nbre_entiere = substr($entiere, $cpt, 3) . " " . $str_nbre_entiere;
        }

        // S'il y a un reste, on traite
        if (strlen($entiere) % 3 !== 0) {
            $str_nbre_entiere = substr($entiere, 0, strlen($entiere) % 3) . " " . $str_nbre_entiere;
        }

        // Suppression du dernier espace
        $str_nbre_entiere = trim($str_nbre_entiere);
    }

    // ******traitement de la partie décimale******
    if ($decimal !== null) {
        // Découpage de la fin vers le début, par longueur de 3
        for ($cpt = strlen($decimal) - 3; $cpt >= 0; $cpt -= 3) {
            $str_nbre_decimal = substr($decimal, $cpt, 3) . " " . $str_nbre_decimal;
        }

        // S'il y a un reste, on traite
        if (strlen($decimal) % 3 !== 0) {
            $str_nbre_decimal = substr($decimal, 0, strlen($decimal) % 3) . " " . $str_nbre_decimal;
        }

        // Suppression du dernier espace
        $str_nbre_decimal = trim($str_nbre_decimal);
    }

    if ($decimal !== null) {
        $str_nbre .= $str_nbre_entiere . ',' . $str_nbre_decimal;
    } else {
        $str_nbre .= $str_nbre_entiere;
    }

    // Retour du résultat
    return $str_nbre;
}
?>

  <script>
    //Separateur de milliers 
  function format(nbre)
    {
        var str_nbre = "";
        var str_nbre_entiere = "";
        var str_nbre_decimal = "";

        var entiere = (nbre + "").split(".")[0];
        var decimal = (nbre + "").split(".")[1];
        
        if (entiere != undefined) {

             //    Découpage de le fin vers le début, par longueur de 3
        for ( let  cpt = entiere.length - 3; cpt >= 0; cpt = cpt - 3 )
        {
            str_nbre_entiere = entiere.substr(cpt, 3) + " " + str_nbre_entiere;
        }
    
        //    S'il y a un reste on traite
        if ( (entiere.toString().length % 3) != 0 )
        str_nbre_entiere = entiere.substr(0, entiere.toString().length % 3) + " " + str_nbre_entiere;
       
        //    Suppression du dernier .
        str_nbre_entiere = str_nbre_entiere.substr(0, str_nbre_entiere.length - 1);
            
        }


        //******traitement de la partie decimal*****

        if (decimal != undefined) {

            //    Découpage de le fin vers le début, par longueur de 3
       for ( let  cpt = decimal.length - 3; cpt >= 0; cpt = cpt - 3 )
       {
        str_nbre_decimal = decimal.substr(cpt, 3) + " " + str_nbre_decimal;
       }
   
       //    S'il y a un reste on traite
       if ( (decimal.toString().length % 3) != 0 )
       str_nbre_decimal = decimal.substr(0, decimal.toString().length % 3) + " " + str_nbre_decimal;
      
       //    Suppression du dernier .
       str_nbre_decimal = str_nbre_decimal.substr(0, str_nbre_decimal.length - 1);
           
       }
       
       if (decimal != undefined) { 

        str_nbre += str_nbre_entiere+','+str_nbre_decimal;
           
       }

       else {
        str_nbre += str_nbre_entiere;
       }
       
        //    Retour du résultat
        return (str_nbre);
    }
    function imprimePage() {
        let table = $('#myTable').DataTable();
        let currentPage = table.page();  
        table.destroy(); // Sauvegarder la page actuelle
        let originalTitle = document.title;
        
        document.title = `Etat des droits constatés - Année académique {{ $annee }}-{{ $anneesuivant }} - Classe {{ $classe }}`;
        
        setTimeout(function() {
            // Créer un élément invisible pour l'impression
            let printDiv = document.createElement('div');
            printDiv.innerHTML = '<h1 style="font-size: 20px; text-transform: uppercase; text-align: center;">Etat des droits constatés - année académique {{ $annee }}-{{ $anneesuivant }} | classe {{ $classe }}</h1>' +
            document.getElementById('contenu').innerHTML;
            
            // Calculer les totaux pour chaque mois et total global
            let totalsParMois = [];
            @foreach ($moisMapping as $id_mois => $nom_mois)
                totalsParMois[{{ $id_mois }}] = @json($totalsParMois[$id_mois] ?? 0);
            @endforeach
            let totalGlobal = totalsParMois[13] + @json(array_sum($totalsParMois) - ($totalsParMois[13] ?? 0));

            // Ajouter les totaux au tableau pour l'impression
            let tableFoot = document.createElement('tfoot');
            let totalRow = '<tr>';
            totalRow += '<th colspan="2">Total</th>';
            
            @foreach ($moisMapping as $id_mois => $nom_mois)
                totalRow += '<th>' + format(totalsParMois[{{ $id_mois }}]) + '</th>';
            @endforeach
            
            totalRow += '<th>' + format(totalGlobal) + '</th>';
            totalRow += '</tr>';
            
            tableFoot.innerHTML = totalRow;
            printDiv.querySelector('#myTable').appendChild(tableFoot);
            
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
                        padding: 5px 0 5px 5px !important;
                        width: 500px !important;
                        word-wrap: break-word !important;
                        white-space: normal !important;
                    }
                    table th {
                        font-weight: bold !important;
                    }
                    table th, table td {
                        font-size: 9px !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        border-collapse: collapse !important;
                    
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    th:nth-child(even) {
                        background-color: #f1f3f5;
                    }
                    th:nth-child(odd) {
                        background-color: red;
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

    
    {{-- 
    <script>
      function imprimePage() {
      let table = $('#myTable').DataTable();
      let currentPage = table.page();  
      table.page.len(-1).draw();  
      setTimeout(function() {
      let printWindow = window.open('', '', 'height=800,width=600');
      printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>@page { size: landscape; }</style>');
        
        printWindow.document.write('<style>@media print { .dt-end { display: none !important; } }</style>');
        printWindow.document.write('<style>@media print { td { font-size: 13px; } }</style>');
        printWindow.document.write('<style>@media print { .dt-start { display: none !important; } .table td:nth-child(n+2) {width: 500px !important; word-wrap: break-word !important; white-space: normal !important;} table {margin: 0 !important; padding: 0 !important; border-collapse: collapse !important;} table th, table td {margin: 0 !important; padding: 10px 0 5px 5px !important; border-collapse: collapse !important;} }</style>');
        printWindow.document.write('<style>table th, table td {margin: 0 !important; padding: 10px 0 5px 5px !important; border-collapse: collapse !important;} table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd;} tbody tr:nth-child(even) { background-color: #f1f3f5; } tbody tr:nth-child(odd) { background-color: #ffffff; } </style>');
        
        printWindow.document.write('</head><body>');
          printWindow.document.write('<h1 style="font-size: 20px; text-transform: uppercase; text-align: center;">Etat des droits constates - annee academique {{ $annee }} - {{ $anneesuivant }} | classe ({{ $classe }})</h1>');
          
          printWindow.document.write('<div>' + document.getElementById('contenu').innerHTML + '</div>');
          printWindow.document.write('</body></html>');
          printWindow.document.close();
          
          printWindow.onload = function() {
          printWindow.print();
          printWindow.close();
          
          table.page.len(10).draw();  
          table.page(currentPage).draw(false); 
          };
          }, 200);
          }
          
        </script> --}}
        