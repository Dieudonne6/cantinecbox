@extends('layouts.master')
@section('content')
@php
    use Carbon\Carbon;
    @endphp
@endphp
<style>
    /* Masquer la sidebar et la navbar pendant l'impression */
    @media print {
        .sidebar, .navbar, .footer, .noprint {
            display: none !important;
        }

        /* Centrer le contenu horizontalement et occuper tout l'espace */
        body {
            width: 100% !important;
            margin-left: -7rem  !important; /* Centrer la page */
            margin-top: -5rem  !important;
        }

        /* Ajuster le tableau pour qu'il prenne toute la largeur et soit lisible */
        table {
            width: 100% !important; /* Tableau pleine largeur */
            table-layout: fixed; /* Fixer la mise en page pour éviter les débordements */
            border-collapse: collapse; /* Fusionner les bordures */
            margin: 0 auto; /* Centrer le tableau */
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

    .sidebar, .navbar, .footer, .noprint {
            display: none !important;
        }

        /* Centrer le contenu horizontalement et occuper tout l'espace */
        body {
            width: 100% !important;
            margin-left: -7rem  !important;  /*Centrer la page */
            margin-top: -5rem  !important;
        }

        .page-body-wrapper{
            background: white !important;
        }
</style>


<body>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                
                <div>
                <h4 style="text-align: center; font-weight:bold">Liste des fautes de {{ $eleve->NOM }} {{ $eleve->PRENOM }}</h4>
                </div><br>
                <div class="table-responsive pt-3">
          {{--   <div class="table-container"> --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Matière</th>
                            <th>Motif</th>
                            <th>Heure</th>
                            <th>Absent</th>
                            <th>Retard</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr>
                                <td>{{Carbon::parse($faute->DATEOP)->format('d/m/Y') }}</td>
                                <td>{{ $absence->CODEMAT }}</td>
                                <td>{{ $absence->MOTIF }}</td>
                                <td>{{ $absence->HEURES }}</td>
                                <td>{{ $absence->ABSENT }}</td>
                                <td>{{ $absence->RETARD }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
                </div>
     </div>
    </div>
</body>



@endsection

<script>
    // Fonction pour imprimer la page actuelle
    function imprimePage() {
        window.print();
    }

    // Appeler imprimePage() dès que la page est chargée
    window.onload = function() {
        setTimeout(function() {
            imprimePage();
        }, 500);
    };
</script>