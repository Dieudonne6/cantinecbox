@extends('layouts.master')
@section('content')

<style>
        @media print {
            .sidebar, .footer, .navbar, .noprint  {
                display: none !important; 
            }
            body {
                width: 100% !important;
                margin-left: -13rem !important;
                margin-top: -5rem !important;
               /*  position: relative;
                top: 55px; /* Ajustez la valeur pour déplacer le cadre vers le bas 
                left: 130px;*/
            }
        }
        /* Espacement entre le tableau et le haut de la page */

        /* Amélioration du style du tableau */
        table {
            width: 100% !important;
            margin: 0 auto;
             border-collapse:collapse;
             table-layout:fixed;
        }

        
        th, td {
            padding: 5px;
            font-size: 10pt;
            word-wrap:break-word;
            text-align: left;
            border: 1px solid black;
        }

            @page {
                size: auto;
                margin: 0mm 0mm;
            }

        /* Bouton d'impression */
/*         button {
            display: block;
            margin: 50px auto;
            padding: 10px 20px;
            background-color:blueviolet;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
 */
        /* Styles pour l'impression */

        @media print and (size: A3) {
            table {
                font-size: 10pt;
            }
            th,td {
                padding: 30px;
            }
        }
 /*        body {
            
            position: relative;
            top: px; 
            left: 30px;
        } */
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
                            <th>Date</th> <!-- Largeur définie -->
                            <th>Matière</th> <!-- Largeur définie -->
                            <th>Motif</th> <!-- Largeur définie -->
                            <th>Heure</th> <!-- Largeur définie -->
                            <th>Absent</th> <!-- Largeur définie -->
                            <th>Retard</th> <!-- Largeur définie -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr>
                                <td>{{ $absence->DATEOP }}</td>
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