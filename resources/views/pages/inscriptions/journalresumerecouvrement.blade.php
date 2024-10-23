@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-center align-items-center">
    <div class="card col-md-10">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Journal résumé des recouvrements</h4>
                <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
            </div>
            <h5 style="text-align: center;">Période du {{ $dateDebut }} au {{ $dateFin }}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Composantes</th>
                        <th>Montant Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $groupedScolarite = $scolarite->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->DATEOP)->format('d/m/Y');
                        });
                    @endphp
        
                    @foreach($groupedScolarite as $date => $entries)
                        <tr>
                            <td rowspan="{{ $entries->count() }}">{{ $date }}</td>
                            @foreach($entries as $index => $entry)
                                @if($index > 0)
                                    <tr>
                                @endif
                                <td>
                                    @switch($entry->AUTREF)
                                        @case(1)
                                            Scolarité
                                            @break
                                        @case(2)
                                            Arrièré
                                            @break
                                        @case(3)
                                            {{ $params->first()->LIBELF1 }}
                                            @break
                                        @case(4)
                                            {{ $params->first()->LIBELF2 }}
                                            @break
                                        @case(5)
                                            {{ $params->first()->LIBELF3 }}
                                            @break
                                        @case(6)
                                            {{ $params->first()->LIBELF4 }}
                                            @break
                                        @default
                                            Inconnu
                                    @endswitch
                                </td>
                                <td>{{ $entry->Montant }}</td>
                                </tr>
                            @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function imprimerliste() {
            var content = document.querySelector('.main-panel').innerHTML; // Sélectionne le contenu à imprimer
            var originalContent = document.body.innerHTML; // Sauvegarde le contenu complet de la page
    
            document.body.innerHTML = content; // Remplace tout le contenu par la section sélectionnée
            window.print(); // Lance l'impression
    
            document.body.innerHTML = originalContent; // Restaure le contenu original après impression
    }
</script>


<style>
    th, td {
        border: 1px solid #000 !important;
    }
    @media print {
        .sidebar, .navbar, .footer, .noprint, button{
            display: none !important; /* Masquer les éléments non désirés à l'impression */
        }
        .card-title {
            text-align: center;
        }
        th, td {
            border: 1px solid #000 !important;
        }
     }
</style>
@endsection
