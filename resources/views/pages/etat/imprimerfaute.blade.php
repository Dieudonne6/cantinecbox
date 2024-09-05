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
            <th>Faute</th>
            <th>Sanction</th>
            <th>Heure</th>
            <th>Col</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fautes as $faute)
            <tr>
                <td>
                    {{ $faute->eleve->NOM ?? '' }}
                    {{ $faute->eleve->PRENOM ?? '' }}
                </td>
                <td>{{ $faute->DATEOP }}</td>
                <td>{{ $faute->FAUTE }}</td>
                <td>{{ $faute->SANCTION }}</td>
                <td>{{ $faute->NBHEURE }}</td>
                <td>{{ $faute->COLLECTIVE }}</td>
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
