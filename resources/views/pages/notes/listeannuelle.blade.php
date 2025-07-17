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
                    'abandon' => 'ABANDON',
                ];
                $annee = (date('Y') - 1) . '-' . date('Y');
            @endphp
            <!-- BOUTONS DE COMMANDE -->
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">

                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>

                <button onclick="imprimerliste()" class="btn btn-dark">Imprimer</button>

            </div>


            @foreach ($rapportsParClasse as $codeClasse => $eleves)
                @php
                    $garcons = $eleves->where('SEXE', 1)->count();
                    $filles = $eleves->where('SEXE', 2)->count();
                    $total = $eleves->count();
                @endphp

                <div class="bulletin mb-4">

                    <!-- Titre -->
                    <div class="text-center fw-bold mb-2" style="font-size: 1.3rem; text-transform: uppercase;">
                        LISTE NOMINATIVE DES ELEVES PROPOSÉS AU {{ $statutLabel[$statut] ?? 'STATUT INCONNU' }}
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
                                    <th>Nom et prenoms</th>
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
                                        <td class="azerty text-start">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                                        <td>{{ $eleve->MATRICULE }}</td>
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
                                            @if($eleve->STATUTF == "P") PASSE
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
        @endif
        </div>

                        </div>
                    </div>

                    <SCRipt>
                        function imprimerliste() {
                var content = document.querySelector('.main-panel').innerHTML;
                var originalContent = document.body.innerHTML;

                document.body.innerHTML = content;
                window.print();

                document.body.innerHTML = originalContent;
            }
                    </SCRipt>
                    @endsection
