<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificats de Scolarité</title>
    <style>
        /* Styles spécifiques à l'impression */
        @media print {
            .no-print {
                display: none;
            }

            .page-break {
                page-break-before: always;
            }

            .watermark::before {
                content: '';
                position: absolute;
                top: 50%;
                left: -25%;
                width: 150%;
                height: 40px;
                /* Largeur de la ligne */
                background-color: red;
                transform: rotate(45deg);
                /* Inverser la direction de la ligne */
                z-index: 0;
                opacity: 0.8;
                /* Transparence de la ligne */
                pointer-events: none;
                /* Assure que le filigramme n'interfère pas avec le contenu */
                user-select: none;
            }
        }

        /* Styles généraux */
        .certificate {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            position: relative;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .content {
            margin-top: 30px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
            /* Assure que le contenu est au-dessus de la ligne */
        }

        .text-end {
            text-align: right;
            margin-top: 50px;
        }

        .text-center {
            text-align: center;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            color: #fff;
            background-color: #007bff;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-sm {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Bouton d'impression (ne sera pas affiché sur la page imprimée) -->
        <div class="text-center no-print">
            <button onclick="window.print()" class="btn btn-primary btn-sm">Imprimer</button>
        </div>

        @foreach ($eleves as $eleve)
            <div class="certificate">
                <div class="watermark"></div> <!-- Filigramme en forme de ligne rouge oblique inversée -->
                <p class="text-center">{{ $nomecole->NOMETAB }}</p>

                <h1>Certificat de Scolarité</h1>
                <h3>N° {{ $eleve->MATRICULE }}/{{ date('Y') }}-{{ date('Y') + 1 }}</h3>

                <div class="content">
                    <p>Le Directeur de <strong>{{ $nomecole->NOMETAB }}</strong> certifie que l'élève
                        {{ $eleve->NOM }}
                        {{ $eleve->PRENOM }}, né(e) le {{ \Carbon\Carbon::parse($eleve->DATENAIS)->format('d/m/Y') }} à
                        {{ $eleve->LIEUNAIS }}, fils de {{ $eleve->NOMPERE }} et de
                        {{ $eleve->NOMMERE }}, est inscrit(e) dans notre établissement
                        sous le numéro <strong>{{ $eleve->MATRICULE }}</strong> depuis le <strong>{{ $eleve->DATEINS }}</strong> et y
                        poursuit actuellement les études en classe de <strong>{{ $eleve->CODECLAS }}</strong>.</p>
                    <p>Il/elle mérite les appréciations suivantes :</p>
                    <p class="text-end"> - Assiduité :
                        ................................................................................</p>
                    <p class="text-end"> - Conduite :
                        ................................................................................</p>
                    <p class="text-end"> - Travail :
                        ................................................................................</p>
                    <p class="text-center">Observations particulières :</p>
                    <p class="text-center">
                        {{ $observation ? $observation : '...............................................................................................................................................' }}
                    </p>

                    <div class="text-end">
                        <p><strong>{{ $nomecole->VILLE }}</strong>, le <strong>{{ date('d/m/Y') }}</strong></p>
                        <p><strong>Le Directeur,</strong></p>
                        <p>[Signature]</p>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>
</body>

</html>
