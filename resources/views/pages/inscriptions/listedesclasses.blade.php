@extends('layouts.master')
@section('content')
    <script>
        function imprimerliste() {
            var content = document.querySelector('.main-panel').innerHTML; // Sélectionne le contenu à imprimer
            var originalContent = document.body.innerHTML; // Sauvegarde le contenu complet de la page

            document.body.innerHTML = content; // Remplace tout le contenu par la section sélectionnée
            window.print(); // Lance l'impression

            document.body.innerHTML = originalContent; // Restaure le contenu original après impression
        }
    </script>
<body>
    <div class="main-content">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2>Liste des classes</h2>
                    <div class="container-fluid d-flex align-items-center justify-content-center">
                        <div id="contenu">
                            @foreach ($types as $type)
                                @php
                                    $classesByType = $classes->where('TYPEENSEIG', $type->idenseign);
                                    $totalEffectif = $classesByType->sum('EFFECTIF');
                                @endphp
                                @if ($type->id === 0 || $classesByType->isNotEmpty())
                                    <h3>{{ $type->type }}</h3>
                                    <br>
                                    @if ($classesByType->isNotEmpty())
                                        <table style="margin: 0 auto; width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">N°</th>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">Classes</th>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">Libellé Classe</th>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">Effectif</th>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">Promotion</th>
                                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">Scolarité</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classesByType as $classe)
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $loop->iteration }}</td>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $classe->CODECLAS }}</td>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $classe->LIBELCLAS }}</td>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $classe->EFFECTIF }}</td>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $classe->CODEPROMO }}</td>
                                                    <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $classe->APAYER }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                        <h5>Total effectif {{ $type->type }}: {{ $totalEffectif }}</h5>
                                        <br>
                                    @else
                                        <p>Aucune classe trouvée pour ce type d'enseignement.</p>
                                    @endif
                                @endif
                            @endforeach
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 20px; margin-bottom: 40px;"> <!-- Ajout d'un margin-bottom pour espacer le bouton du footer -->
                                <button type="button" class="btn btn-primary btn-lg" onclick="imprimerliste()"> <!-- Ajout de la classe btn-lg pour agrandir le bouton -->
                                    Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

<style>
    h2, h3 {
        text-align: left; /* Alignement du titre à gauche */
        font-weight: bold; /* Mettre en gras les titres */
    }

    table {
        width: 100%;
        min-width: 12px;
        border-collapse: collapse;
        table-layout: fixed;
    }
    th, td {
        border: 2px solid black;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
    }
    .footer {
        position: relative; /* Position relative pour éviter tout chevauchement */
        bottom: 0;
        width: 100%;
        z-index: 10; /* Assurer que le footer soit visible par-dessus les autres éléments */
    }
    @media print {
        .sidebar, .navbar, .footer, .noprint {
            display: none !important; /* Masquer les éléments non désirés à l'impression */
        }

        /* Centrer le contenu horizontalement et occuper tout l'espace */
        body {
            width: 100% !important;
            margin: 0 !important; /* Enlever les marges */
            overflow: hidden; /* Masquer la barre de défilement */

        }

        table {
            margin: 0 auto !important; /* Centrer le tableau */
            width: 100% !important; /* Utiliser toute la largeur disponible */
            border-collapse: collapse; /* Fusionner les bordures */
        }
        
        button {
            display: none !important;
        }

        /* Ajuster les marges de la page pour centrer verticalement */
        @page {
            size: auto; /* Laisser le navigateur gérer la taille */
            margin: 10mm 15mm; /* Marges équilibrées pour centrage */
        }
    }

    /* Ajustement pour l'apparence sur les petits écrans */
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 12px; /* Réduire la taille de la police pour les petits écrans */
            padding: 4px; /* Réduire le padding pour les petits écrans */
        }
    }
</style>
