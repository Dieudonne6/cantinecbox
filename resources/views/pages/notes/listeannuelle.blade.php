@extends('layouts.master')
@section('content')


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="">
            <div>
                <style>
                  @media print {
                    .d-print-none { display: none !important; }
                     @page {
                                size: landscape;
                       
                            }
                    .bulletin { font-family: Arial, sans-serif; font-size: 20px !important;}
                    .azerty{
                       font-size: 1rem !important; 
                    }
                    table { font-size: 1.5rem !important;}
                     .sidebar,
                    .navbar,
                    .footer,
                    .noprint,
                    button {
                        display: none !important;
                        overflow: hidden !important;
                    }

                    .card-body {
                        overflow: hidden;
                        font-size: 20px !important;
                    }
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
                @if(isset($rapportsParClasse) && count($rapportsParClasse))

                    @php
                        $statutLabel = [
                            'P' => 'PASSAGE',
                            'R' => 'REDOUBLEMENT',
                            'X' => 'EXCLUSION',
                            'Z' => 'ABANDON',
                        ];
                        $annee = (date('Y') - 1) . '-' . date('Y');
                    @endphp
                    <!-- BOUTONS DE COMMANDE -->
                    <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">

                        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button onclick="imprimerliste()" class="btn btn-dark" style="margin-left: 25rem;">Imprimer</button>
                        <button class="btn btn-secondary " type="button" onclick="exportToExcel()" >Exporter vers Excel</button>
                        
                    </div>

                    <div id="listedefinitive">
                        @foreach ($rapportsParClasse as $codeClasse => $eleves)
                            @php
                                $garcons = $eleves->where('SEXE', 1)->count();
                                $filles = $eleves->where('SEXE', 2)->count();
                                $total = $eleves->count();
                            @endphp
                            <br><br>
                            <div class="bulletin mb-4">

                                <!-- Titre -->
                                <div class="text-center mb-2" style="font-size: 1.3rem; text-transform: uppercase; font-weight: bold;">
                                    LISTE NOMINATIVE DES ELEVES PROPOSÉS AU {{ $statutLabel[$statut] ?? 'STATUT INCONNU' }}
                                    <input type="hidden" name="statut" value="{{ $statutLabel[$statut] ?? 'STATUT INCONNU' }}">
                                </div>

                                <!-- En-tête classe + stats -->
                                <div class="d-flex justify-content-between mb-2" style="font-weight: bold;">
                                    <div style="margin-left: 10rem;">CLASSE : {{ $codeClasse }} / {{ $annee }}</div>
                                    <div style="margin-right: 10rem;">
                                        G = {{ str_pad($garcons, 2, ' ', STR_PAD_LEFT) }} &nbsp; &nbsp;
                                        F = {{ str_pad($filles, 2, ' ', STR_PAD_LEFT) }} &nbsp; &nbsp;
                                        T = {{ str_pad($total, 2, ' ', STR_PAD_LEFT) }}
                                    </div>
                                </div>

                                <!-- Tableau -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40px;">N°</th>
                                                <th>Nom </th>
                                                <th>Prenoms</th>
                                                <th>N° Mle</th>
                                                <th>Sexe</th>
                                                <th>Stat</th>                                          
                                                <th>Moy1</th>
                                                <th>Moy2</th>
                                                <th>Moy An</th>                                 
                                                <th>Observ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleves as $index => $eleve)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class="azerty text-start">{{ $eleve->NOM }} </td>
                                                    <td>{{ $eleve->PRENOM }}</td>
                                                    <td class="mat">{{ $eleve->MATRICULEX }}</td>
                                                    <td>
                                                        @if($eleve->SEXE == 1) M
                                                        @elseif($eleve->SEXE == 2) F
                                                        @else ?
                                                        @endif
                                                    </td>  
                                                    <td>
                                                        @if($eleve->STATUT == 0) P
                                                        @else R                                           
                                                        @endif
                                                    </td>                                                                        
                                                    <td>{{ number_format($eleve->MOY1, 2) == 21 || number_format($eleve->MOY1, 2) == -1 ? '**' : number_format($eleve->MOY1, 2)  }}</td>
                                                    <td>{{ number_format($eleve->MOY2, 2) == 21 || number_format($eleve->MOY2, 2) == -1 ? '**' : number_format($eleve->MOY2, 2)  }}</td>
                                                    <td><strong>{{ number_format($eleve->MOYAN, 2) == 21 || number_format($eleve->MOYAN, 2) == -1 ? '**' : number_format($eleve->MOYAN, 2)  }}</strong></td>
                                                    <td class="text-start">
                                                        @if($eleve->STATUTF == 'P') PASSE
                                                        @elseif($eleve->STATUTF == 'R') Redouble
                                                        @elseif($eleve->STATUTF == 'X') Exclu
                                                        @else Abandon
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr class="my-4 d-print-none">

                            </div>
                           
                        @endforeach
                         <br><br><br>
                    </div>
                @endif
            </div>

     
        </div>
    </div>

    <script>
        function imprimerliste() {
            var content = document.querySelector('.main-panel').innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = content;
            window.print();

            document.body.innerHTML = originalContent;
        }

        
        let statutfinal = @json($statutLabel[$statut]);
        
        if(statutfinal === "PASSAGE"){
            statutfinal = 'Liste_definive_Passage';
        }else if (statutfinal === "REDOUBLEMENT") {
            statutfinal = 'Liste_definive_Redoublement';
        }else if (statutfinal === "EXCLUSION"){
                statutfinal = 'Liste_definive_Exclusion';
        } else {
                statutfinal = 'Liste_definive_Abandon';
        }


        function exportToExcel() {
    
            const contentElement = document.getElementById('listedefinitive');
            
            if (!contentElement) {
                alert('Aucun liste à exporter. Veuillez d\'abord créer les rapports.');
                return;
            }

            // Cloner le contenu pour ne pas modifier l'original
            const clone = contentElement.cloneNode(true);

            // Supprimer les éléments avec la classe .no-print ou .screen-only
            const unwantedElements = clone.querySelectorAll('.d-print-none');
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
            a.download = `${statutfinal}.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
@endsection
