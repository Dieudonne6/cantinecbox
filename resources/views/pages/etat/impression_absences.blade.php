<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fautes de l'élève {{ $eleve->NOM }}</title>
    <style>
        /* Styles pour l'impression */
        @media print {
            .noprint {
                display: none;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="col">
        <!-- Bouton pour imprimer la page -->
        <button onclick="window.print()" class="noprint">Imprimer</button>
    </div>

    <h1>Liste des fautes de {{ $eleve->NOM }} {{ $eleve->PRENOM }}</h1>
    <table>
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
</body>

</html>
