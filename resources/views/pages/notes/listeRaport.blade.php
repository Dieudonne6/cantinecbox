@extends('layouts.master')
@section('content')


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="">
            <div>
                <style>
                    .screen-only {
                            display: none !important;
                    }

                    

                    .btn-arrow {
                        background-color: transparent !important;
                        border: 1px solid transparent !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        color: #b51818 !important;
                    }

                    .btn-arrow:hover {
                        color: #b700ff !important;
                    }

                    .table th, .table td {
                        padding: 4px;
                        font-size: 0.85rem;
                    }  
                </style>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3 ">

                    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <button onclick="prinTable()" class="btn btn-dark" style="margin-left: 25rem;">Imprimer</button>
                    <button class="btn btn-secondary " type="button" onclick="exportToExcel()" >Exporter vers Excel</button>
                    
                </div>

                <div id="tablecontent" class="mt-4 ">
                    <div style="text-align: center; flex: 1;">
                        <h3 style="margin-bottom: 0;">RAPPORT DE FIN D'ANNEE</h3>
                        <div>{{ $anneeScolaire }}</div>
                    </div>
                    @foreach ($rapportsParClasse as $codeClasse => $rapports)
                        <div class="classe-section">
                            <br>
                            <h4 class="text-uppercase mb-3" style="text-align: center">Classe : {{ $codeClasse }}</h4>
                            <div  style="display: flex; align-items: center; justify-content: space-between;">
                                <div class="screen-only no-export" style="border: 1px solid #333; padding: 10px; background-color: rgb(185,185,185); border-radius: 8px;">
                                    @foreach ($params2 as $param)
                                        <strong>{{ $param->NOMETAB }}</strong><br>
                                        {{ $param->ADRESSE }}<br>
                                    @endforeach
                                </div>
                               
                                <div class="screen-only no-export">
                                    <img src="data:image/png;base64,{{ base64_encode($params2[0]->logoimage ?? '') }}" alt="Logo" width="60">
                                </div>
                            </div>

                            <div class="col-md-3 screen-only">
                                <div class="border p-1 mb-2 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                    
                                        <p><strong>EFFECTIF :</strong> {{ $rapports->count() }} DONT {{ $statsParClasse[$codeClasse]['effectifFilles'] }} FILLES</p>
                                        <p><strong>PASSAGE :</strong> {{$statsParClasse[$codeClasse]['passantsTotal']}} DONT {{ $statsParClasse[$codeClasse]['passantesFilles'] }} FILLES</p>
                                        <p><strong>REDOUBLEMENT :</strong> {{ $statsParClasse[$codeClasse]['redoublesTotal'] }} DONT {{ $statsParClasse[$codeClasse]['redoublesFilles'] }} FILLES</p>
                                        <p><strong>EXCLUSION :</strong> {{ $statsParClasse[$codeClasse]['exclusTotal'] }} DONT {{ $statsParClasse[$codeClasse]['excluesFilles'] }} FILLES</p>
                                        <p><strong>ABANDON :</strong> {{ $statsParClasse[$codeClasse]['abandonsTotal'] }} DONT {{ $statsParClasse[$codeClasse]['abandonsFilles'] }} FILLES</p>
                                    </div>
                                </div>
                            </div>

                            <div id="printableArea" class="table-responsive" style="max-height: 100vh; overflow-y: auto;">
                                @php
                                    $rapports = collect($rapports);
                                    $groupes = ['P' => 'A/ PASSAGE', 'R' => 'B/ REDOUBLEMENT', 'X' => 'C/ EXCLUSION', 'Z' => 'D/ ABANDON'];
                                @endphp


                                @foreach ($groupes as $code => $titre)
                                    @php
                                        $groupe = $rapports->where('STATUTF', $code);
                                    @endphp

                                    @if ($groupe->count())
                                        <h4 style="mar<<<<<<< HEAD
                                            <p>SUITE AUX DECISIONS DU CONSEIL DE                                         @php
                                            $codepromo = $classe->where('CODECLAS', $codeClasse)->pluck('CODEPROMO');
                                            $codeP = $codepromo[0];
                                            $LibelpromoSup = $classeSup->where('codeClas', $codeP)->pluck('libelle_classe_sup');
                                            $LibelpromoSupp = $LibelpromoSup[0];
                                            // echo($LibelpromoSupp);   
                                        @endphp
                                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT PROPOSES AU PASSAGE : <span id="libSup" style="font-weight: bold;">  {{ $LibelpromoSupp }} </span> </p>
>
                                        @elseif ($code === 'R')
                                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT REDOUBLENT LA CLASSE DE : <span style="font-weight: bold;">{{ $codeClasse ?? '' }}</span> </p>
                                        @elseif ($code === 'X')
                                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT PROPOSES A L'EXCLUSION DE LA  CLASSE DE : <span style="font-weight: bold;">{{ $codeClasse ?? '' }}</span> </p>
                                        @else
                                            <p>SUITE AUX DECISIONS DU CONSEIL DE FIN D'ANNEE LES ELEVES DONT LES NOMS SUIVENT SONT ABANDON DE LA CLASSE DE : <span style="font-weight: bold;">{{ $codeClasse ?? '' }}</span> </p>
                                        @endif

                                    

                                        <table id="rapportTable" class="table table-bordered table-light table-sm text-center mb-0">
                                            <thead class="table-secondary sticky-top">
                                                <tr>
                                                    <th scope="col">Statut</th>
                                                    <th scope="col">Mat</th>
                                                    <th scope="col">Rang</th>
                                                    <th scope="col">Nom </th>
                                                    <th scope="col">Prénom</th>
                                                    <th scope="col">Redou</th>
                                                    <th scope="col">Moy1</th>
                                                    <th scope="col">Moy2</th>
                                                    <th scope="col">Moy An</th>
                                                    <th scope="col">Observation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupe->sortBy('RANG') as $rapport)
                                                    <tr>
                                                        <td>{{ $rapport->STATUTF }}</td>
                                                        <td class="mat">{{ $rapport->MATRICULEX }}</td>
                                                        <td>{{ $rapport->RANG }}</td>
                                                        <td class="text-start">{{ $rapport->NOM }} </td>
                                                        <td>{{ $rapport->PRENOM }}</td>
                                                        <td>{{ $rapport->STATUT == 0 ? 'Nouveau' : 'Redouble' }}</td>
                                                        <td>{{ number_format($rapport->MOY1, 2) == 21 || number_format($rapport->MOY1, 2) == -1 ? '**' : number_format($rapport->MOY1, 2) }}</td>
                                                        <td>{{ number_format($rapport->MOY2, 2) == 21 || number_format($rapport->MOY2, 2) == -1 ? '**' : number_format($rapport->MOY2, 2) }}</td>
                                                        <td>{{ number_format($rapport->MOYAN, 2) == 21 || number_format($rapport->MOYAN, 2) == -1 ? '**' : number_format($rapport->MOYAN, 2) }}</td>
                                                        <td>{{ $rapport->OBSERVATION }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                @endif
                            @endforeach
                            </div>
                            <br><br><br>
                        </div>
                    @endforeach
                    <div class="droite"  >
                    <p style="text-align: end">Fait à  {{ $param->VILLE }} le,  {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p> 
                    <p style="text-align: end; margin-right: 6rem;">{{ $param->TITRE}},</p> <br>
                    <p style="text-align: end; margin-right: 3rem;">{{ $param->NOMDIRECT}}</p>

                  </div>
                </div>               
            </div>
        </div>

        <script>
             function prinTable() {            
          
                    var tablecontent = document.getElementById('tablecontent');

                    // const content = document.getElementById('rapportData');
                    // if (!content) {
                    //     alert('Rapport non généré. Veuillez d\'abord cliquer sur "Créer rapport".');
                    //     return;
                    // }

                    const win = window.open('', '_blank');
                    win.document.write(`
                        <html>
                        <head>
                            <title>Impression du rapport</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { border: 1px solid #000; padding: 5px; text-align: center; }
                                th { background-color: #f2f2f2; }
                                h4 { margin-top: 30px; margin-bottom: 10px; }
                                p { margin: 2px 0; }

                                @media print {                              
                                    

                                    .table-responsive {
                                        max-height: none !important;
                                        overflow: visible !important;
                                    }
                                    
                                    
                                    .classe-section:last-of-type {
                                            page-break-after: auto !important;
                                        }
                                }  
                                     
                            </style>
                        </head>
                        <body>
                        
                        ${tablecontent.innerHTML}</body>
                        </html>
                    `);
                    win.document.close();
                    win.focus();

                    win.onload = () => {
                        win.print();
                        win.close();
                    };
            }


              function exportToExcel() {
        

                const contentElement = document.getElementById('tablecontent');
              
                if (!contentElement) {
                    alert('Aucun rapport à exporter. Veuillez d\'abord créer les rapports.');
                    return;
                }

               
                // Cloner le contenu pour ne pas modifier l'original
                const clone = contentElement.cloneNode(true);

                // Supprimer les éléments avec la classe .no-print ou .screen-only
                const unwantedElements = clone.querySelectorAll('.no-export');
                unwantedElements.forEach(el => el.remove());

                // style Excel plus propre
                const style = `
                    <style>
                        table {
                            border-collapse: collapse;
                            width: 100%;
                        }
                        th, td {
                            border: 1px solid black;
                            padding: 5px;
                            text-align: center;
                            font-size: 20px;
                            line-height: 1.5rem;
                        }
                        th {
                            font-weight: bold;
                        }
                        td {
                            text-align: center;
                        }
                        td.mat {
                            mso-number-format:"0";
                        }
                    </style>
                `;

                // Construire le HTML complet pour Excel
                const html = `
                    <html xmlns:o="urn:schemas-microsoft-com:office:office"
                        xmlns:x="urn:schemas-microsoft-com:office:excel"
                        xmlns="http://www.w3.org/TR/REC-html40">
                    <head>
                        <meta charset="UTF-8">
                        ${style}
                    </head>
                    <body>
                       
                        ${clone.innerHTML}
                    </body>
                    </html>
                `;

                const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `liste_generale_rapport.xls`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }

        </script>
    </div>



        
@endsection
