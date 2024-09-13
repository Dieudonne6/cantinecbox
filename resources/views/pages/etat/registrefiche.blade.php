{{-- <h1>Registre_suivant_la_fiche</h1> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registre_suivant_la_fiche</title>

    <style>
        @media print {
            /* Évite de couper les informations d'un élève entre deux pages */
            .student-info {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }

            /* Forcer un saut de page après chaque groupe de 4 élèves */
            .page-break {
                page-break-after: always;
            }
        }

        /* Pour l'affichage sur l'écran également */
        .student-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body onload="window.print()">


    @foreach ($infoelevenom->chunk(4) as $group)
        @foreach ($group as $infoelevenom)
        <h4 style="text-align: center">REGISTRE DES ELEVES</h4>
        <div class="student-info">
            <div style="display: flex">
                <div style="width: 80%;">
                    <p ><span style="font-weight: bold">Matricule:</span> <span style="margin-left:2rem">{{ $infoelevenom->MATRICULE }}</span></p>
                    <p ><span style="font-weight: bold">Nom:</span> <span style="margin-left:4rem">{{ $infoelevenom->NOM }}</span></p>
                    <p ><span style="font-weight: bold">Prenoms:</span> <span style="margin-left:2rem">{{ $infoelevenom->PRENOM }}</span></p>
                    {{-- \Carbon\Carbon::parse($filterEleves->DATENAIS)->format('d/m/Y') --}}
                    <p ><span style="font-weight: bold">Ne le</span> <span style="margin-left:4rem">{{ \Carbon\Carbon::parse($infoelevenom->DATENAIS)->format('d/m/Y') }}</span> <span style="font-weight: bold; margin-left:10rem">à</span> {{ $infoelevenom->LIEUNAIS }}</p>
                    <p ><span style="font-weight: bold">CLASSE:</span> <span style="margin-left:2rem">{{ $infoelevenom->CODECLAS }}</span>  <span style="font-weight: bold; margin-left:10rem">Date inscription:</span> <span style="margin-left:1.5rem">{{  \Carbon\Carbon::parse($infoelevenom->DATEINS)->format('d/m/Y') }}</span></p>
                </div>
                <div style="width: 20%;">
                    <h5>{{ $annescolaire }}</h5>
                    <img src="data:image/jpeg;base64,{{ base64_encode($infoelevenom->PHOTO) }}" alt="photo"  style="width: 15%;">
                    {{-- <img src="" alt="photo"> --}}
                </div>
            </div>
            <hr>
        </div>
        {{-- <hr> --}}
        @endforeach

        <!-- Ajouter un saut de page après chaque groupe de 4 élèves -->
        <div class="page-break"></div>
    @endforeach

</body>
</html>
