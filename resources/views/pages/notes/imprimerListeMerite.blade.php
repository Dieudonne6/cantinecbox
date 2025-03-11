@extends('layouts.master')

@section('content')
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
    <div class="d-print-none">
        <button onclick="imprimerTableau()" class="btn btn-primary float-end">Imprimer</button>
    </div>

    <!-- Tableau visible à l'écran (d-print-none le cache à l'impression) -->
    <div class="d-print-none">
        @php
            $currentClass = '';
        @endphp
        @php
        if ($typean == 1) {
            $texte = 'Semestre';
        } else {
            $texte = 'Trimestre';
        }
        @endphp

        
        @foreach ($eleves as $eleve)
            @if($currentClass != $eleve->CODECLAS)
                @if($currentClass != '')
                    </tbody>
                    </table>
                @php
                    $colonneMoyen = (($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3)) ? 'MAN' : 'MOYENNE';
                @endphp

                <div style="background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; margin-top: 20px;">
                    <div class="row text-center">
                        <div class="col-3">
                            <small>Effectif: {{ $eleves->where('CODECLAS', $currentClass)->count() }}</small>
                        </div>
                        <div class="col-3">
                            <small>Nombre de moyennes: {{ $eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() }}</small>
                        </div>
                        <div class="col-3">
                            <small>% Moyennes: {{ number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) }}%</small>
                        </div>
                        <div class="col-3">
                            <small>% Non moyennes: {{ number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '<', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) }}%</small>
                        </div>
                    </div>
                </div>
                <br/>
                <br/>                    
                @endif

                <div class="print-table-container">
                    <h4 class="text-center mb-3">Liste par Ordre de Mérite - 
                        @if ($periode == 1) 
                            {{ $periode }}er
                        @else
                            {{ $periode }}ème 
                        @endif
                        @switch($typean)
                            @case('1')
                                 {{ $texte }}
                            @break

                            @case('2')
                                 {{ $texte }}
                            @break

                            @default
                                 {{ $texte }}
                        @endswitch
                    </h4>
                    <h5 class="text-center"
                        style="font-weight: 400;">
                        Année scolaire : {{ $annescolaire }}
                    </h5>
                    <div style="background-color: transparent; border: 1px solid black; border-radius: 10px;">
                    <h5 class="text-center">
                        Classe: {{ $eleve->CODECLAS }}
                    </h5>
                    </div>
                </div>                    
                    {{-- <div class="text-right">
                        <small>{{ $periode }}ème {{ $texte }}</small>
                    </div> --}}
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Stats</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Moyenne</th>
                                @if(($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3))
                                    <th>Moyenne Annuelle</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                                @php
                                    $currentClass = $eleve->CODECLAS;
                                @endphp
                            @endif
                            <tr>
                                <td>{{ $eleve->RANGA }}</td>
                                <td></td>
                                <td>{{ $eleve->NOM }}</td>
                                <td>{{ $eleve->PRENOM }}</td>
                                <td>{{ number_format($eleve->MOYENNE, 2) }}</td>
                                @if(($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3))
                                    <td>{{ number_format($eleve->MAN, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @php
                        $colonneMoyen = (($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3)) ? 'MAN' : 'MOYENNE';
                    @endphp

                    <div style="background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; margin-top: 20px;">
                        <div class="row text-center">
                            <div class="col-3">
                                <small>Effectif: {{ $eleves->where('CODECLAS', $currentClass)->count() }}</small>
                            </div>
                            <div class="col-3">
                                <small>Nombre de moyennes: {{ $eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() }}</small>
                            </div>
                            <div class="col-3">
                                <small>% Moyennes: 
                                    {{ $eleves->where('CODECLAS', $currentClass)->count() > 0 
                                        ? number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) 
                                        : 0 }}%
                                </small>
                            </div>
                            <div class="col-3">
                                <small>% Non moyennes: 
                                    {{ $eleves->where('CODECLAS', $currentClass)->count() > 0 
                                        ? number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '<', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) 
                                        : 0 }}%
                                </small>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>
    </div>

    <!-- Tableau optimisé pour l'impression (d-none le cache à l'écran, d-print-block l'affiche à l'impression) -->
    <div class="d-none d-print-block">
        @php
            $currentClass = '';
        @endphp
        @php
        if ($typean == 1) {
            $texte = 'Semestre';
        } else {
            $texte = 'Trimestre';
        }
        @endphp        
        
        @foreach ($eleves as $eleve)
            @if($currentClass != $eleve->CODECLAS)
                @if($currentClass != '')
                    </tbody>
                    </table>
                @php
                    $colonneMoyen = (($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3)) ? 'MAN' : 'MOYENNE';
                @endphp

                <div style="background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; margin-top: 20px;">
                    <div class="row text-center">
                        <div class="col-3">
                            <small>Effectif: {{ $eleves->where('CODECLAS', $currentClass)->count() }}</small>
                        </div>
                        <div class="col-3">
                            <small>Nombre de moyennes: {{ $eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() }}</small>
                        </div>
                        <div class="col-3">
                            <small>% Moyennes: {{ number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) }}%</small>
                        </div>
                        <div class="col-3">
                            <small>% Non moyennes: {{ number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '<', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) }}%</small>
                        </div>
                    </div>
                </div>

                @endif
                
                <div class="print-table-container">
                    <h4 class="text-center mb-3">Liste par Ordre de Mérite - 
                        @if ($periode == 1) 
                            {{ $periode }}er
                        @else
                            {{ $periode }}ème 
                        @endif
                        @switch($typean)
                            @case('1')
                                 {{ $texte }}
                            @break

                            @case('2')
                                 {{ $texte }}
                            @break

                            @default
                                 {{ $texte }}
                        @endswitch
                    </h4>
                    <h5 class="text-center"
                        style="font-weight: 400;">
                        Année scolaire : {{ $annescolaire }}
                    </h5>
                    <div class="text-center"
                        style="background-color: transparent; border: 1px solid black; border-radius: 10px;">
                    <h5>
                        Classe: {{ $eleve->CODECLAS }}
                    </h5>
                    </div>
                </div>
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Stats</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Moyenne</th>
                                @if(($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3))
                                    <th>Moyenne Annuelle</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                @php
                    $currentClass = $eleve->CODECLAS;
                @endphp
            @endif
            <tr>
                <td>{{ $eleve->RANGA }}</td>
                <td></td>
                <td>{{ $eleve->NOM }}</td>
                <td>{{ $eleve->PRENOM }}</td>
                <td>{{ number_format($eleve->MOYENNE, 2) }}</td>
                @if(($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3))
                    <td>{{ number_format($eleve->MAN, 2) }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
        </table>
        @php
            $colonneMoyen = (($typean == 1 && $periode == 2) || ($typean == 2 && $periode == 3)) ? 'MAN' : 'MOYENNE';
        @endphp
        
        <div style="background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; margin-top: 20px;">
            <div class="row text-center">
                <div class="col-3">
                    <small>Effectif: {{ $eleves->where('CODECLAS', $currentClass)->count() }}</small>
                </div>
                <div class="col-3">
                    <small>Nombre de moyennes: {{ $eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() }}</small>
                </div>
                <div class="col-3">
                    <small>% Moyennes: 
                        {{ $eleves->where('CODECLAS', $currentClass)->count() > 0 
                            ? number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '>=', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) 
                            : 0 }}%
                    </small>
                </div>
                <div class="col-3">
                    <small>% Non moyennes: 
                        {{ $eleves->where('CODECLAS', $currentClass)->count() > 0 
                            ? number_format(($eleves->where('CODECLAS', $currentClass)->where($colonneMoyen, '<', 10)->count() / $eleves->where('CODECLAS', $currentClass)->count()) * 100, 2) 
                            : 0 }}%
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
</div>

<style>
    @media print {
        body {
            background: white;
            font-size: 12pt;
        }

        .print-table-container {
            page-break-before: always; /* Nouvelle page pour chaque classe */
            page-break-inside: avoid;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Supprimer les marges inutiles pour l'impression */
        @page {
            size: auto;
            margin: 10mm;
        }
    }

</style>

<script>
    function imprimerTableau() {
        let printWindow = window.open('', '_blank', 'height=600,width=800');

        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f8f9fa; font-weight: bold; }
            .print-table-container { page-break-before: always; page-break-inside: avoid; margin-bottom: 30px; }
            @page { size: auto; margin: 10mm; }
        `);
        printWindow.document.write('</style></head><body>');

        // Copier uniquement les tableaux d'impression
        let content = document.querySelector('.d-print-block').innerHTML;
        printWindow.document.write(content);

        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();

        // Attendre que le contenu soit chargé avant d'imprimer
        printWindow.onload = function () {
            printWindow.print();
            printWindow.close();
        };
    }
</script>

@endsection
