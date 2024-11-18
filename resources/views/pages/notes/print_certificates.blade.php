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
        @foreach ($eleves as $eleve)
        <div class="outer-frame">
            <!-- Header -->
            <div class="header">
                <h1>Attestation de Mérite</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <h2 style='text-align: center;'>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</h2>
                <p>
                    Nous certifions par la présente que l'élève <strong>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</strong>, 
                    inscrit(e) en classe de <strong>{{ $eleve->CODECLAS }}</strong>, a fait preuve d'une diligence exemplaire 
                    au cours de l'année scolaire <strong>{{ $eleve->anneeacademique }}</strong>, et a obtenu des résultats exceptionnels 
                    dans ses études. En reconnaissance de ses efforts, il/elle est honoré(e) pour son mérite.
                </p>
                <p>
                    Cette attestation lui est délivrée en témoignage de son assiduité et de son travail acharné 
                    durant la <strong>{{ $trimestre }}<sup>e</sup></strong> période de l'année académique. Nous lui adressons nos plus sincères félicitations pour ses accomplissements.
                </p>
            </div>            

            <!-- Signature -->
            <div class="signature">
                <div>
                    <p>Fait à [{{ $params->VILLE }}], le {{ now()->format('d/m/Y') }}</p>
                    <p>{{ $signChoice }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>© [{{ $params->NOMETAB }}] - Tous droits réservés</p>
            </div>
        </div>
        <div style="page-break-after: always;"></div> <!-- Ajout d'un saut de page -->
        @endforeach
    </body>
</html>
