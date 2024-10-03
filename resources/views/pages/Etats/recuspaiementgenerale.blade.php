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
        @if (Session::has('success'))
            <div id="recu" class="mt-4">
                <div class="row">
                    <div class="recu-container"
                        style="display: flex; justify-content: space-between; gap: 20px; border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                        @php
                            $libelles = ['LIBELF1', 'LIBELF2', 'LIBELF3'];
                        @endphp

                        @foreach (['Souche', 'Original'] as $type)
                            <div class="recu-section"
                                style="flex: 1; border: 1px solid #333; padding: 20px; background-color: #fff;">
                                <h5
                                    style="text-align: center; font-size: 18px; margin-bottom: 20px; font-weight: bold;">
                                    Reçu de Paiement ({{ $type }})
                                </h5>
                                <div style="margin-bottom: 15px;">
                                    <p style="margin: 0; font-size: 14px;"><strong>Élève:</strong>
                                        {{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                                    <p style="margin: 0; font-size: 14px;"><strong>Classe:</strong>
                                        {{ $eleve->CODECLAS }}</p>
                                    <p style="margin: 0; font-size: 14px;"><strong>Date:</strong>
                                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                    <strong>Numéro de reçu :</strong> {{ Session::get('numeroRecu') }}
                                </div>
                                <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                <div style="margin-bottom: 15px;">
                                    <p style="margin: 0; font-size: 14px;"><strong>Montant payé:</strong>
                                        {{ Session::get('montantPaye') }} F CFA</p>
                                    <p style="margin: 0; font-size: 14px;"><strong>Mode de paiement:</strong>
                                        {{ Session::get('mode_paiement') }}</p>
                                    <p style="margin: 0; font-size: 14px;"><strong>Arriéré:</strong>
                                        {{ Session::get('arriéré') }} F CFA</p>
                                    <p style="margin: 0; font-size: 14px;"><strong>Scolarité:</strong>
                                        {{ Session::get('scolarite') }} F CFA</p>
                                </div>
                                <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                @foreach ($libelles as $index => $libelleKey)
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>{{ $libelle->$libelleKey }}:</strong>
                                        {{ Session::get('montant') }} F CFA
                                    </p>
                                @endforeach
                                <div style="margin-bottom: 15px;>
                                <p style="margin: 0;
                                    font-size: 14px;"><strong>Reliquat restant :</strong>
                                    {{ Session::get('reliquat') }} F CFA</p>
                                </div>
                                <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                <div class="recu-footer" style="text-align: center; margin-top: 20px;">
                                    <p style="font-size: 14px;"><strong>CCC, le
                                            {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong>
                                    </p>
                                    <p style="font-size: 14px;"><strong>Le Comptable Gestion</strong>
                                    </p>
                                    {{ Session::get('signature') }}
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="bordered"
                        style="border-top: 1px dashed #333; margin-top: 20px; padding-top: 10px; text-align: center;">
                        <p style="font-size: 14px; color: #666;">Merci d'avoir effectué votre paiement.</p>
                    </div>

                    <!-- Bouton pour imprimer le reçu -->
                    <button onclick="imprimerRecu()" class="btn btn-success mt-4">Imprimer le Reçu</button>
                </div>
            </div>
        @endif
    </div>

    <script>
        function imprimerRecu() {
            var contenu = document.getElementById('recu').innerHTML;
            var fenetre = window.open('', '_blank', 'width=800,height=600');
            fenetre.document.open();
            fenetre.document.write(`
            <html>
            <head>
                <title>Reçu de Paiement</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }
                    .recu-container {
                        display: flex;
                        justify-content: space-between;
                        border: 1px solid #000;
                        padding: 20px;
                        background-color: #f9f9f9;
                    }
                    .recu-section {
                        width: 48%;
                        border: 1px solid black;
                        padding: 15px;
                        box-sizing: border-box;
                        background-color: #fff;
                    }
                    h5 {
                        text-align: center;
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 20px;
                    }
                    .recu-footer {
                        text-align: center;
                        margin-top: 20px;
                    }
                    .bordered {
                        border-top: 1px dashed black;
                        margin-top: 20px;
                        padding-top: 10px;
                        text-align: center;
                        color: #666;
                    }
                    p {
                        font-size: 14px;
                        margin: 5px 0;
                    }
                </style>
            </head>
            <body>${contenu}</body>
            </html>
        `);
            fenetre.document.close();
            fenetre.onload = function() {
                fenetre.focus();
                fenetre.print();
                fenetre.onafterprint = function() {
                    fenetre.close();
                };
            };
        }

        @if (Session::has('success'))
            window.onload = function() {
                imprimerRecu();
            };
        @endif
    </script>
</body>

</html>
