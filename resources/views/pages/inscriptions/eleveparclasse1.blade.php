@extends('layouts.master')
@section('content')
    <style>
        @media print {

            body * { visibility: hidden !important; }
                .print-table, .print-table * { visibility: visible !important; }
                .print-table { position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; }

           
            /* Masquer le premier tableau lors de l'impression */
            .main-table {
                display: none !important;
            }
            /* .main-table {
                display: none !important;
            } */

            /* Afficher le deuxième tableau uniquement lors de l'impression */


            .d-print-none {
                display: none !important;
            }

            /* .d-print-block {
                display: block !important;
            } */
        }

        /* Par défaut, le deuxième tableau est masqué */
    </style>
    <div class="main-panel-10">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Liste des élèves par classe</h4>
                    <div class="row mb-3 entete">
                        <div class="col-3">
                            <label for="" style="margin-top: 2px">Classe</label>
                            <select class="js-example-basic-multiple w-100" multiple="multiple" name="classeCode[]">
                                <option value="">Sélectionnez une classe</option>
                                @foreach ($classe as $classe)
                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mt-4">
                            <button class="btn-sm btn-primary" id="submitBtn1">Appliquer la sélection</button>
                        </div>

                        <div class="col-3">
                            <div class="form-group row">
                                <label for="titreEtat">Titre</label>
                                <div>
                                    <input type="text" class="form-control" id="titreEtat"
                                        style="width:15rem; height: 34px" />
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-4">
                            {{-- <button class="btn-sm btn-primary" id="printBtn">Imprimer</button> --}}
                            <button type="button" class="btn-sm btn-primary" onclick="imprimerPage()">Imprimer</button>
                        </div>
                    </div>

                    <div class="table table-bordered main-table">
                        <table id="myTable">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom et Prénom</th>
                                    <th>Date de Naissance</th>
                                    <th>Lieu de Naissance</th>
                                    <th>Sexe</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filterEleve as $filterEleves)
                                    <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}"
                                        data-prenom="{{ $filterEleves->PRENOM }}"
                                        data-codeclas="{{ $filterEleves->CODECLAS }}">
                                        <td>
                                            {{ $filterEleves->MATRICULEX }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->NOM }} {{ $filterEleves->PRENOM }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->LIEUNAIS }}
                                        </td>
                                        <td>
                                            {{ $filterEleves->SEXE == 1 ? 'Masculin' : 'Feminin' }}
                                        </td>
                                        <td>
                                            <!-- Statut -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tableau imprimé uniquement -->
                    <div id="contenu">
                    <div class="table table-bordered print-table d-none">
                        @foreach ($elevesGroupes as $classeCode => $eleves)
                            <h4>Classe : {{ $classeCode }}</h4>

                            <div class="row">
                                <!-- Tableau des Effectifs -->
                                <div class="col1">
                                    @foreach ($statistiquesClasses as $stat)
                                        @if ($stat->CODECLAS == $classeCode)
                                            <div class="table table-bordered d-none d-print-block">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th></th> <!-- Ligne vide pour l'en-tête -->
                                                            <th>Filles</th>
                                                            <th>Garçons</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Effectif Total -->
                                                        <tr>
                                                            <td>Effectif</td>
                                                            <td>{{ $stat->total_filles }}</td>
                                                            <td>{{ $stat->total_garcons }}</td>
                                                            <td>{{ $stat->total }}</td>
                                                        </tr>
                                                        <!-- Redoublants -->
                                                        <tr>
                                                            <td>Redoublants</td>
                                                            <td>{{ $stat->total_filles_redoublants }}</td>
                                                            <td>{{ $stat->total_garcons_redoublants }}</td>
                                                            <td>{{ $stat->total_redoublants }}</td>
                                                        </tr>
                                                        <!-- Nouveaux -->
                                                        <tr>
                                                            <td>Nouveaux</td>
                                                            <td>{{ $stat->total_filles_nouveaux }}</td>
                                                            <td>{{ $stat->total_garcons_nouveaux }}</td>
                                                            <td>{{ $stat->total_nouveaux }}</td>
                                                        </tr>
                                                        <!-- Transférés -->
                                                        <tr>
                                                            <td>Transférés</td>
                                                            <td>{{ $stat->total_filles_transferes }}</td>
                                                            <td>{{ $stat->total_garcons_transferes }}</td>
                                                            <td>{{ $stat->total_transferes }}</td>
                                                        </tr>
                                                        <!-- Anciens -->
                                                        <tr>
                                                            <td>Anciens</td>
                                                            <td>{{ $stat->total_filles_anciens }}</td>
                                                            <td>{{ $stat->total_garcons_anciens }}</td>
                                                            <td>{{ $stat->total_anciens }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>


                                <!-- Tableau des Réductions/Profils -->
                                <div class="col2">
                                    <div class="table table-bordered d-none d-print-block">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Réduction</th>
                                                    <th>Nombre d'Élèves</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($reductionsParClasse as $reduction)
                                                    @if ($reduction->CODECLAS == $classeCode)
                                                        <tr>
                                                            <td>{{ $reduction->libelleReduction }}</td>
                                                            <td>{{ $reduction->total }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Tableau des élèves de la classe -->
                            {{-- <h4>Liste des élèves de la classe {{ $classeCode }}</h4> --}}
                            <table id="tableClasse_{{ $classeCode }}">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Matricule</th>
                                        <th>Nom et Prénom</th>
                                        <th>Date de Naissance</th>
                                        <th>Lieu de Naissance</th>
                                        <th>Sexe</th>
                                        <th>NV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $index => $eleve)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $eleve->MATRICULEX }}</td>
                                            <td>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                                            <td>{{ \Carbon\Carbon::parse($eleve->DATENAIS)->format('d/m/Y') }}</td>
                                            <td>{{ $eleve->LIEUNAIS }}</td>
                                            <td>{{ $eleve->SEXE == 1 ? 'Masculin' : 'Féminin' }}</td>
                                            <td>
                                                <input type="checkbox" {{ $eleve->statutG == 1 ? 'checked' : '' }}
                                                    disabled>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- <script>
            document.getElementById('printBtn').addEventListener('click', function() {
                const titre = document.getElementById('titreEtat').value;

                // Insérer le titre dans un élément caché pour l'impression
                const titreImpression = document.createElement('div');
                titreImpression.id = 'printTitre';
                titreImpression.innerHTML = `<h2>${titre}</h2>`;
                document.body.appendChild(titreImpression);

                // Rendre les tableaux par classe visibles pour l'impression
                const printTables = document.querySelectorAll('.print-table');
                printTables.forEach(table => table.classList.remove('d-none'));

                // Lancer l'impression
                window.print();

                // Masquer à nouveau les tableaux après impression
                printTables.forEach(table => table.classList.add('d-none'));

                // Supprimer l'élément du titre après impression
                document.body.removeChild(titreImpression);
            });
        </script> --}}



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
              titreEtat =`Liste des élèves`;
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
                        //   .print-table, .print-table * { visibility: visible; }
                          .print-table { position: absolute; top: 0; left: 0; width: 100%; }
                          .col1 {width: 40%; }
                          .col2 {width: 40%;}
                          .row {display: flex;}
          
                          /* Styles pour masquer les éléments non désirés à l'impression */
                        //   .dt-end, .dt-start, .hide-printe, .offcanvas { display: none !important; }
          
                          /* Afficher les éléments cachés avec la classe d-none */
                          .print-table { display: block !important; }
          
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




    {{-- 
    

         public function eleveparclassespecifique($classeCode)
    {
        $CODECLASArray = explode(',', $classeCode);
    
        $eleves = Eleve::orderBy('NOM', 'asc')->get();
        $classesAExclure = ['NON', 'DELETE'];
    
        $classes = Classes::whereNotIn('CODECLAS', $classesAExclure)->get();
        $fraiscontrat = Paramcontrat::first(); 
    
        $contratValideMatricules = Contrat::where('statut_contrat', 1)->pluck('eleve_contrat');
    
        // Filtrer les élèves en fonction des classes sélectionnées
        $filterEleves = Eleve::whereIn('MATRICULE', $contratValideMatricules)
            ->whereIn('CODECLAS', $CODECLASArray)
            // ->select('MATRICULE', 'NOM', 'PRENOM', 'CODECLAS')
            ->orderBy('NOM', 'asc')
            // ->groupBy('CODECLAS')
            ->get();

            // Requête pour récupérer les élèves avec l'effectif total, le nombre de filles et le nombre de garçons par classe
            $statistiquesClasses = Eleve::whereIn('MATRICULE', $contratValideMatricules)
            ->whereIn('CODECLAS', $CODECLASArray)
            ->select(
                'CODECLAS',
                // Effectif total
                DB::raw('COUNT(*) as total'),
                // Nombre de garçons
                DB::raw('SUM(CASE WHEN SEXE = 1 THEN 1 ELSE 0 END) as total_garcons'),
                // Nombre de filles
                DB::raw('SUM(CASE WHEN SEXE = 2 THEN 1 ELSE 0 END) as total_filles'),
                // Nombre de redoublants
                DB::raw('SUM(CASE WHEN STATUT = 1 THEN 1 ELSE 0 END) as total_redoublants'),
                // Nombre de redoublants garçons
                DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_redoublants'),
                // Nombre de redoublants filles
                DB::raw('SUM(CASE WHEN STATUT = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_redoublants'),
                // Nouveaux ou transférés (statutG = 1 pour nouveaux, statutG = 3 pour transférés)
                DB::raw('SUM(CASE WHEN statutG = 1 THEN 1 ELSE 0 END) as total_nouveaux'),
                DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_nouveaux'),
                DB::raw('SUM(CASE WHEN statutG = 1 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_nouveaux'),
                DB::raw('SUM(CASE WHEN statutG = 3 THEN 1 ELSE 0 END) as total_transferes'),
                DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_transferes'),
                DB::raw('SUM(CASE WHEN statutG = 3 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_transferes'),
                // Anciens (statutG = 2)
                DB::raw('SUM(CASE WHEN statutG = 2 THEN 1 ELSE 0 END) as total_anciens'),
                DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 1 THEN 1 ELSE 0 END) as total_garcons_anciens'),
                DB::raw('SUM(CASE WHEN statutG = 2 AND SEXE = 2 THEN 1 ELSE 0 END) as total_filles_anciens')
            )
            ->groupBy('CODECLAS')
            ->get();


            // requette pour recuperer le nombre d'eleve par codereduction
            $reductionsParClasse = DB::table('eleve')
            ->join('reduction', 'eleve.CodeReduction', '=', 'reduction.CodeReduction') // Liaison avec la table des réductions
            ->whereIn('eleve.MATRICULE', $contratValideMatricules)
            ->whereIn('eleve.CODECLAS', $CODECLASArray)
            ->select(
                'eleve.CODECLAS', 
                'reduction.libelleReduction', // Nom de la réduction
                DB::raw('COUNT(*) as total') // Nombre d'élèves ayant cette réduction
            )
            ->groupBy('eleve.CODECLAS', 'reduction.libelleReduction')
            ->get();

            // requette pour grouper les eleve par classe
            $elevesGroupes = $filterEleves->groupBy('CODECLAS');

    
            // dd($filterEleves);
        Session::put('fraiscontrats', $fraiscontrat);
        Session::put('fill', $filterEleves);
    
        return view('pages.inscriptions.eleveparclasse1')
            ->with("filterEleve", $filterEleves)
            ->with('classe', $classes)
            ->with('eleve', $eleves)
            ->with('elevesGroupes', $elevesGroupes)
            ->with('statistiquesClasses', $statistiquesClasses)
            ->with('reductionsParClasse', $reductionsParClasse)
            ->with('fraiscontrats', $fraiscontrat);
    } 
    
    
    
    
    --}}