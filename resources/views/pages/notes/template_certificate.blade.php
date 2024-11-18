<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation de Mérite</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f7;
            color: #333;
        }

        .outer-frame {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            border: 10px solid #003366;
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .inner-frame {
            padding: 30px;
            border: 2px solid #003366;
            border-radius: 10px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h1 {
            font-size: 36px;
            color: #003366;
            margin: 0;
        }

        .content {
            margin: 50px 0;
            text-align: justify;
            line-height: 1.8;
        }

        .content h2 {
            font-size: 22px;
            margin-bottom: 30px;
            text-decoration: underline;
            color: #003366;
        }

        .content p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .signature {
            margin-top: 60px;
            text-align: center;
            font-style: italic;
        }

        .signature div {
            margin-top: 30px;
            font-size: 18px;
            color: #333;
        }

        .footer p {
            font-size: 12px;
            color: #777;
            margin-top: 60px;
        }
    </style>
    <script>
        // Lance l'impression et ferme la page après
        window.onload = function () {
            window.print();
        };

        // Ferme la page après l'impression ou si elle est annulée
        window.onafterprint = function () {
            window.close();
        };
    </script>
</head>

<body>
    <div class="outer-frame">
        <div class="inner-frame">
            <div class="header">
                <h1>Attestation de Mérite</h1>
            </div>
            <div class="content">
                <h2 style='text-align: center;'>${name}</h2>
                <p>
                    Nous certifions par la présente que l'élève <strong>${name}</strong>,
                    inscrit en <strong>${studentClass}</strong>, a fait preuve d'une diligence exemplaire
                    au cours de l'année scolaire <strong>${year}</strong> et a obtenu des résultats exceptionnels
                    dans ses études. Pour ses efforts, il/elle est reconnu(e) pour son mérite.
                </p>
                <p>
                    Cette attestation lui est délivrée en reconnaissance de son assiduité et de son travail acharné
                    tout au long de l'année. Nous lui adressons nos plus sincères félicitations pour ses
                    accomplissements.
                </p>
            </div>
            <div class="signature">
                <div>
                    <p>Fait à ${location}, le ${date}</p>
                    <p>Signature et cachet</p>
                </div>
            </div>
            <div class="footer">
                <p>© [Nom de l'Établissement] - Tous droits réservés</p>
            </div>
        </div>
    </div>
</body>

</html>
