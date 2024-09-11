@extends('layouts.master')
@section('content')
<style>
    .table-container {
        overflow-x: auto;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        min-width: 1200px; /* Ajustez cette valeur selon vos besoins */
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
    }
    .reduction-header {
        text-align: center;
        font-weight: bold;
    }
    /* Styles individuels pour chaque colonne */
    .col-small { width: 80px; }
    .col-medium { width: 120px; }
    .col-large { width: 200px; }
    .col-xlarge { width: 250px; }

    /* Styles pour l'impression */
    @media print {
        .sidebar, .navbar, .footer, .noprint {
            display: none !important; /* Masquer la barre de titre et autres éléments */
        }
        body {
            overflow: hidden; /* Masquer les barres de défilement */
            zoom: 0.7; /* Réduire le zoom du tableau */
        }
        .table-container {
            overflow: visible; /* Assurer que le tableau s'affiche correctement */
        }
        @page {
            size: A4 landscape; /* Orientation paysage */
            margin: 20mm; /* Ajustez les marges si nécessaire */
        }
    }
</style>

<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3>Etat des réductions accordées</h3>
                <div class="container-fluid">
                    <div id="contenu">
                        @foreach ($classes as $classe)
                            @php
                                $elevesAvecReduction = $eleves->where('CODECLAS', $classe->CODECLAS)
                                                              ->where('CodeReduction', '!=', 0);
                            @endphp
                            @if ($elevesAvecReduction->isNotEmpty())
                                <h5>{{ $classe->CODECLAS }}</h5>
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th scope="col" rowspan="2" class="col-small">N°</th>
                                                <th scope="col" rowspan="2" class="col-large">Nom et prénoms</th>
                                                <th scope="col" rowspan="2" class="col-medium">Montant initial</th>
                                                <th scope="col" colspan="6" class="reduction-header">REDUCTIONS ACCORDEES</th>
                                                <th scope="col" rowspan="2" class="col-medium">Total réductions</th>
                                                <th scope="col" rowspan="2" class="col-medium">Net à payer</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="col-small">Scolarité</th>
                                                <th scope="col" class="col-small">Arriérés</th>
                                                <th scope="col" class="col-small">Frais 1</th>
                                                <th scope="col" class="col-small">Frais 2</th>
                                                <th scope="col" class="col-small">Frais 3</th>
                                                <th scope="col" class="col-small">Frais 4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($elevesAvecReduction as $index => $eleve)
                                                @php
                                                    // Récupération de la réduction de l'élève
                                                    $reduction = $reductions->firstWhere('CodeReduction', $eleve->CodeReduction);

                                                    // Récupération des valeurs de réduction depuis la réduction
                                                    $pourcentagescolarite = $reduction ? $reduction->Reduction_scolarite : 0;
                                                    $pourcentagearriere = $reduction ? $reduction->Reduction_arriere : 0;
                                                    $pourcentagefrais1 = $reduction ? $reduction->Reduction_frais1 : 0;
                                                    $pourcentagefrais2 = $reduction ? $reduction->Reduction_frais2 : 0;
                                                    $pourcentagefrais3 = $reduction ? $reduction->Reduction_frais3 : 0;
                                                    $pourcentagefrais4 = $reduction ? $reduction->Reduction_frais4 : 0;

                                                    // Calcul des réductions individuelles
                                                    $reductionScolarite = ($eleve->APAYER * $pourcentagescolarite) / 100;
                                                    $reductionArriere = ($eleve->ARRIERE * $pourcentagearriere) / 100;
                                                    $reductionFrais1 = ($eleve->FRAIS1 * $pourcentagefrais1) / 100;
                                                    $reductionFrais2 = ($eleve->FRAIS2 * $pourcentagefrais2) / 100;
                                                    $reductionFrais3 = ($eleve->FRAIS3 * $pourcentagefrais3) / 100;
                                                    $reductionFrais4 = ($eleve->FRAIS4 * $pourcentagefrais4) / 100;

                                                    // Total de la réduction
                                                    $totalReduction = $reductionScolarite + $reductionArriere + $reductionFrais1 + $reductionFrais2 + $reductionFrais3 + $reductionFrais4;

                                                    // Net à payer
                                                    $netAPayer = $eleve->APAYER - $totalReduction;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</td>
                                                    <td>{{ number_format($eleve->APAYER, 2) }}</td>
                                                    <td>{{ number_format($reductionScolarite, 2) }}</td>
                                                    <td>{{ number_format($reductionArriere, 2) }}</td>
                                                    <td>{{ number_format($reductionFrais1, 2) }}</td>
                                                    <td>{{ number_format($reductionFrais2, 2) }}</td>
                                                    <td>{{ number_format($reductionFrais3, 2) }}</td>
                                                    <td>{{ number_format($reductionFrais4, 2) }}</td>
                                                    <td>{{ number_format($totalReduction, 2) }}</td>
                                                    <td>{{ number_format($netAPayer, 2) }}</td>
                                                </tr>
                                            @endforeach

                                        
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                            <style>
                                #print-button {
                                    position: relative;
                                    z-index: 20; /* Assurer que le bouton soit au-dessus du footer */
                                    margin-bottom: 20px; /* Ajouter un espace en bas pour éviter le chevauchement */
                                }
                            </style>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-primary" id="print-button" onclick="imprimerliste()">
                                        Imprimer
                                    </button>
                                </div>
                    </div>
                </div>
            </div>
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
</script>
@endsection