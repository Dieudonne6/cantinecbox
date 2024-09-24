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
        <!-- Section Original -->
        <div class="original-receipt" style="border: 1px solid #000; padding: 20px; margin-bottom: 20px;">
            <h5>Original</h5>
            <div>
                <p><strong>Nom de l'élève:</strong> {{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                <p><strong>Montant payé:</strong> {{ $montantPaye }} F CFA</p>
                <p><strong>Mode de paiement:</strong> {{ $modePaiement }}</p>
                <p><strong>Signature:</strong> ______________________</p>
            </div>
        </div>

        <!-- Section Souche -->
        <div class="souche-receipt" style="border: 1px dashed #000; padding: 20px;">
            <h5>Souche</h5>
            <div>
                <p><strong>Nom de l'élève:</strong> {{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                <p><strong>Montant payé:</strong> {{ $montantPaye }} F CFA</p>
                <p><strong>Mode de paiement:</strong> {{ $modePaiement }}</p>
                <p><strong>Signature:</strong> ______________________</p>
            </div>
        </div>
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
