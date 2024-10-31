@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Statistiques par Matière</h3>
                    <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
                </div>
                <h5 class="text-center">Trimestre {{ $trimestres->first()->timestreencours }}</h5>
                @foreach ($matieres as $matiere)
                    @php
                        $hasData = false;
                        foreach ($classes as $classe) {
                            $stat = $stats[$classe->CODECLAS][$matiere->CODEMAT] ?? null;
                            if ($stat && $stat['notesStats']->nombre_eleves > 0) {
                                $hasData = true;
                                break;
                            }
                        }
                    @endphp

                    @if ($hasData) <!-- Vérifier si des données existent avant d'afficher quoi que ce soit pour cette matière -->
                        <h5>Matière : {{ $matiere->LIBELMAT }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Classe</th>
                                    <th>Effectif</th>
                                    <th>+ Forte moyenne</th>
                                    <th>+ Faible moyenne</th>
                                    <th>M < 10</th>
                                    <th>M >= 10</th>
                                    <th>% M >= 10</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $classe)
                                    @php
                                        $stat = $stats[$classe->CODECLAS][$matiere->CODEMAT] ?? null;
                                    @endphp
                                    @if ($stat && $stat['notesStats']->nombre_eleves > 0)
                                        <tr>
                                            <td>{{ $classe->CODECLAS }}</td>
                                            <td>{{ $stat['notesStats']->nombre_eleves }}</td>
                                            <td>{{ $stat['notesStats']->moyenne_max }}</td>
                                            <td>{{ $stat['notesStats']->moyenne_min }}</td>
                                            <td>{{ $stat['notesStats']->nombre_moyenne_inf_10 }}</td>
                                            <td>{{ $stat['notesStats']->nombre_moyenne_sup_10 }}</td>
                                            <td>{{ number_format($stat['pourcentage_moyenne_sup_10'], 2) }}%</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .footer {
        position:fixed !important; /* Changer de relative à absolute pour le placer en bas de la carte */
        bottom: 0 !important; /* Assurer que le footer soit en bas */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
    th, td {
        border: 1px solid black !important;
    }
    @media print {
        .sidebar, .navbar, .footer, .noprint, button {
            display: none !important; /* Masquer la barre de titre et autres éléments */
        }
        h3 {
            text-align: center !important;
        }
    }
</style>
<script>
function imprimerliste() {
        var content = document.querySelector('.main-panel').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = content;
        window.print();

        document.body.innerHTML = originalContent;
    }
</script>
@endsection
