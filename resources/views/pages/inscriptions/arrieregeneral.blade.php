@extends('layouts.master')
@section('content')

<style>
            .btn-arrow {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: transparent !important;
            border: 1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            color: #b51818 !important;
        }

        .btn-arrow:hover {
            color: #b700ff !important;
        }
</style>

@push('styles')
    <style>


        /* Style d'impression */
        @media print {
            /* Masquer tout sauf le tableau */
            body > *:not(#arrieresTable) {
                display: none !important;
            }
            
            /* Afficher le tableau en pleine largeur */
            #arrieresTable {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                font-size: 10pt !important;
            }
            
            /* Assurer que le tableau tient sur la largeur */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            
            /* Style des cellules pour l'impression */
            th, td {
                padding: 4px !important;
                border: 1px solid #ddd !important;
            }
            
            /* Forcer les couleurs d'arrière-plan à l'impression */
            .table-dark {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .text-end {
                text-align: right !important;
            }
            
            /* Style pour les totaux */
            .table-light {
                background-color: #f1f1f1 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            /* Style pour les montants en rouge */
            .text-danger {
                color: #dc3545 !important;
            }
            
            tfoot {
                display: table-footer-group;
            }
        }
    </style>
@endpush

    {{-- Suppression de l'export Excel : aucun script XLSX n'est chargé ici --}}

    <div class="col-md-12">

        <button class="btn btn-arrow " onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>

        <div class="mb-3 no-print mt-5">
            <button id="printButton" class="btn btn-primary me-2" onclick="printSection('printableArea')">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>

        <div id="printableArea" class="print-container">

            <div class="print-header text-center mb-4">
                <h2 class="fw-bold">{{ $titre ?? 'État des arriérés – Élèves inscrits' }}</h2>
                <hr>
            </div>

            <div class="table-responsive" id="arrieresTable">
                <table  class="table table-bordered table-striped table-sm">
                    <thead class="fw-bold">
                        <tr>
                            <th class="fw-bold">N°</th>
                            <th class="fw-bold">Matricule</th>
                            <th class="fw-bold">Nom et Prénoms</th>
                            <th class="fw-bold">Classe</th>
                            <th class="fw-bold">Montant dû</th>
                            <th class="fw-bold">Montant payé</th>
                            <th class="fw-bold">Reste à payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $compteurTotal = 1; @endphp
                        @forelse($resultats as $classe => $eleves)
                            <tr style="background-color: #f3f3f3;">
                                <td colspan="7" class="fw-bold">
                                    Classe : {{ $classe }}
                                </td>
                            </tr>
                            @foreach($eleves as $matricule => $resultat)
                                <tr>
                                    <td>{{ $compteurTotal++ }}</td>
                                    <td>{{ $matricule }}</td>
                                    <td>{{ $resultat['NOM'] }} {{ $resultat['PRENOM'] }}</td>
                                    <td>{{ $resultat['CLASSE'] }}</td>
                                    <td class="text-end">{{ number_format($resultat['ARRIERE'], 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end">{{ number_format($resultat['PAYE'], 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($resultat['RESTE'], 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                // Calculer les totaux par classe
                                $totalClasseDues = collect($eleves)->sum('ARRIERE');
                                $totalClassePayes = collect($eleves)->sum('PAYE');
                                $totalClasseRestes = collect($eleves)->sum('RESTE');
                            @endphp
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold">Total {{ $classe }} :</td>
                                <td class="text-end fw-bold">{{ number_format($totalClasseDues, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format($totalClassePayes, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold text-danger">{{ number_format($totalClasseRestes, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun élève trouvé avec des arriérés</td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if(count($resultats) > 0)
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">TOTAUX</th>
                                <th class="fw-bold text-end">{{ number_format($totalDues, 0, ',', ' ') }} FCFA</th>
                                <th class="fw-bold text-end">{{ number_format($totalPayes, 0, ',', ' ') }} FCFA</th>
                                <th class="fw-bold text-end">{{ number_format($totalRestes, 0, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
                <br><br><br>
            </div>
            
        </div>
    
    </div>

    <script>
        function printSection(sectionId) {
            var printContent = document.getElementById(sectionId).innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>

@endsection
