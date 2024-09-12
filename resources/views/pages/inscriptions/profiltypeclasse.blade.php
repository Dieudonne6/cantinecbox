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
            zoom: 0.5; /* Réduire le zoom du tableau */
        }
        .table-container {
            overflow: visible; /* Assurer que le tableau s'affiche correctement */
        }
        @page {
            size: A4 landscape; /* Orientation paysage */
            margin: 3mm; /* Ajustez les marges si nécessaire */
        }
    }
</style>
<body onload="window.print()">
<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center">Liste des élèves par profils</h1>
                <h4>Groupe: {{ $typeclasse->where('TYPECLASSE', $typeClasse)->first()->LibelleType }}</h4>
                
                @foreach ($reductions as $reduction)
                    @if ($elevesParReduction->has($reduction->CodeReduction))
                        <h5>Profil de réduction: {{ $reduction->LibelleReduction }}</h5>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="col-small">N°</th>
                                        <th class="col-xlarge">Nom</th>
                                        <th class="col-xlarge">Prénoms</th>
                                        <th class="col-xlarge">Code Groupe</th> 
                                        <th class="col-xlarge">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($elevesParReduction->get($reduction->CodeReduction) as $eleve)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $eleve->NOM }}</td>
                                            <td>{{ $eleve->PRENOM }}</td>
                                            <td>{{ $eleve->CODECLAS }}</td>
                                            <td>
                                                @if ($eleve->STATUTG == 1)
                                                    Nouveau
                                                @elseif ($eleve->STATUTG == 2)
                                                    Ancien
                                                @elseif ($eleve->STATUTG == 3)
                                                    Transféré
                                                @else
                                                    Inconnu
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
@endsection
