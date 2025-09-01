
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
    <div class="card-body">
      <h4 class="card-title">Liste générale des élèves</h4>
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
          <select class="js-example-basic-multiple w-100" multiple="multiple" name="CODECLAS[]" data-placeholder="Sélectionnez une classe">         
            <option value=""></option>
            @foreach ($classe as $classes)
            <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-3">
          <button  class="btn btn-primary" id="submitBtn">Appliquer la sélection</button>
        </div>
        <div class="col-2">
          <input type="text" id="titreEtat" class="form-control p-2" name="" value="" placeholder="Titre de l'état">
        </div>
        
        <div class="col-2">
          <button onclick="imprimerPage()" type="button" class="btn btn-primary">
            Imprimer 
          </button>
        </div>
        <div class="col-2 text-center">
            <button class="btn-sm btn-primary" type="button" onclick="exportToExcel()">Exporter</button>
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
                <th>OBSERVATION</th>
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
                <td></td>
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
    
    <script>
  function imprimerPage() {
    let originalTitle = document.title;
    document.title = `Liste des élèves`;

    // Désactiver le DataTable et réinitialiser l'affichage
    let table = $('#myTable').DataTable();
    let currentPage = table.page();  
    table.destroy();

    setTimeout(function() {
      let titreEtat = document.getElementById('titreEtat').value;

// Utiliser un titre par défaut si le champ est vide
        if (!titreEtat) {
            titreEtat = "Liste des élèves";
        }
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


function exportToExcel() {
    // Sélectionner la table
    let table = document.querySelector("#myTab"); 
    if (!table) {
        alert("Aucune donnée à exporter !");
        return;
    }

    // Récupérer toutes les classes affichées dans le tableau
    let classes = Array.from(table.querySelectorAll("tbody tr"))
        .map(row => row.querySelector("td:nth-child(5)")?.textContent.trim()) // Colonne "CLASSE"
        .filter(classe => classe && classe !== "");

    // Si plusieurs classes, on met "toutes_classes"
    let classeNom = "classes";
    if (classes.length === 1) {
        classeNom = classes[0];
    } else if (classes.length > 1) {
        // Vérifie si tous les élèves ont la même classe
        const uniqueClasses = [...new Set(classes)];
        if (uniqueClasses.length === 1) {
            classeNom = uniqueClasses[0];
        }
    }

    // Créer le fichier Excel avec XLSX
    let workbook = XLSX.utils.book_new();
    let worksheet = XLSX.utils.table_to_sheet(table);

    // Ajouter la feuille au fichier
    XLSX.utils.book_append_sheet(workbook, worksheet, "Liste des élèves");

    // Générer le nom du fichier dynamique
    let fileName = `liste_des_eleves_${classeNom}.xlsx`;

    // Sauvegarder le fichier
    XLSX.writeFile(workbook, fileName);
}



    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    @endsection
    