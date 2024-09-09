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
                                    <h2>{{ $type->type }}</h2>
                                    <br>
                                    @if ($classesByType->isNotEmpty())
                                        <table class="table table-striped" style="margin: 0 auto; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th scope="col">N°</th>
                                                    <th scope="col">Classes</th>
                                                    <th scope="col">Libellé Classe</th>
                                                    <th scope="col">Effectif</th>
                                                    <th scope="col">Promotion</th>
                                                    <th scope="col">Scolarité</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classesByType as $classe)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $classe->CODECLAS }}</td>
                                                    <td>{{ $classe->LIBELCLAS }}</td>
                                                    <td>{{ $classe->EFFECTIF }}</td>
                                                    <td>{{ $classe->CODEPROMO }}</td>
                                                    <td>{{ $classe->APAYER }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <p><strong>Total {{ $type->type }}: {{ $totalEffectif }}</strong></p>
                                        <br>
                                    @else
                                        <p>Aucune classe trouvée pour ce type d'enseignement.</p>
                                    @endif
                                @endif
                            @endforeach
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-primary" onclick="imprimerliste()">
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
    @media print {
        .sidebar, .navbar, .footer, .noprint {
            display: none !important; /* Masquer la barre de titre et autres éléments */
        }

        /* Centrer le contenu horizontalement et occuper tout l'espace */
        body {
            width: 100% !important;
            margin: 0 !important; /* Enlever les marges */
        }

        table {
            margin: 0 auto !important; /* Centrer le tableau */
            width: 100% !important; /* Utiliser toute la largeur disponible */
            border-collapse: collapse; /* Fusionner les bordures */
        }
        
        button {
            display: none !important;
        }

        th, td {
            font-size: 10pt; /* Ajuster la taille de la police pour impression */
            padding: 5px;
            word-wrap: break-word; /* Permet aux mots longs d'être coupés */
            text-align: left; /* Aligner le texte à gauche pour meilleure lisibilité */
            border: 1px solid black; /* Bordures pour impression */
        }

        /* Ajuster les marges de la page pour centrer verticalement */
        @page {
            size: auto; /* Laisser le navigateur gérer la taille */
            margin: 10mm 15mm; /* Marges équilibrées pour centrage */
        }

        /* Ajustement pour A4 */
        @media print and (size: A4) {
            table {
                font-size: 8pt; /* Ajuster la taille de la police pour A4 */
            }
            th, td {
                padding: 4px; /* Ajuster le padding pour A4 */
            }
        }

        /* Ajustement pour A3 */
        @media print and (size: A3) {
            table {
                font-size: 10pt; /* Ajuster la taille de la police pour A3 */
            }
            th, td {
                padding: 5px; /* Ajuster le padding pour A3 */
            }
        }
    }
    .card-body {
        padding: 2rem;
    }

    /* Assurer que le footer soit en bas de la page */
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 10; /* Assurer que le footer soit au-dessus des autres éléments */
    }

    /* Assurer que le body soit en premier plan */
    body {
        position: relative;
        z-index: 1; /* Assurer que le body soit en dessous du footer */
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    table {
        width: 100%; /* Utiliser toute la largeur disponible */
        border-collapse: collapse;   
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    /* Le conteneur principal doit occuper tout l'espace disponible */
    .main-content {
        flex: 1;
        padding-bottom: 50px; /* Ajouter de l'espace en bas pour le footer */
        overflow: auto; /* Permettre le défilement si le contenu dépasse la hauteur disponible */
    }

</style>