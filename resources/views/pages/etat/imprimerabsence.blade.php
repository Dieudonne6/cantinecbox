<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression des Fautes</title>
    <style>
        /* Styles spécifiques pour l'impression */
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<h2>Liste des Fautes</h2>
<table>
    <thead>
        <tr>
            <th>Nom</th>
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
                <td>
                    {{ $absence->eleve->NOM ?? '' }}
                    {{ $absence->eleve->PRENOM ?? '' }}
                </td>
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


<!-- Script pour déclencher l'impression automatiquement -->
<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
