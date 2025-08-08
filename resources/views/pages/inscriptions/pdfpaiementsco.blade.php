@extends('layouts.master')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .btn-arrow {
            position: absolute;
            top: 0px;
            /* Ajustez la position verticale */
            left: 0px;
            /* Positionnez à gauche */
            background-color: transparent !important;
            border: 1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            /* Taille de l'icône */
            color: #b51818 !important;
            /* Couleur de l'icône */
        }

        .btn-arrow:hover {
            color: #b700ff !important;
            /* Couleur au survol */
        }

        body {
            font-family: Arial, sans-serif;
        }

        @media print {
            .ko {
                background-color: blue;
            }

        }

        p {

            font-size: 15px;
        }

        /* .bas{
                bottom: 4rem;
            } */
        /*.footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 10;
            }*/



        #mecef p {
            font-size: 12px;
        }


        .page-break {
            display: none;
            /* Masquer les éléments de saut de page lors de l'impression */
        }


        .facture-container1 {
            display: flex;
            justify-content: space-between;
            /* Optionnel : espace entre les blocs */
            gap: 50px;
            /* Optionnel : espace entre les blocs */
            border-radius: 5px;
            margin-left: 40px;
            margin-right: 30px;
            margin-top: -1.8rem;
        }

        .facture-container2 {
            display: flex;
            justify-content: space-between;
            /* Optionnel : espace entre les blocs */
            gap: 50px;
            /* Optionnel : espace entre les blocs */
            margin-left: 40px;
            margin-right: 30px;
        }

        .facture-container5 {
            display: flex;
            justify-content: space-between;
            /* Optionnel : espace entre les blocs */
            gap: 50px;
            /* Optionnel : espace entre les blocs */
            margin-left: 0px;
            margin-right: 40px;
        }

        .info {
            flex: 1;
            /* Les deux blocs auront la même largeur */
            padding: 10px;
            /* border: 1px solid #ddd; */
            border-radius: 5px;
            margin-top: 2rem;
        }

        .table4 {
            width: 600px;
            /* Largeur fixe du conteneur */
            overflow: auto;
            /* Ajouter un défilement si nécessaire */
        }

        #customers4 {
            width: 100%;
            /* Largeur du tableau prend la largeur du conteneur */
            border-collapse: collapse;
            /* Fusionner les bordures */
        }

        #customers4 th,
        #customers4 td {
            border: 1px solid #ddd;
            /* Bordure des cellules */
            padding: 8px;
            /* Espacement intérieur des cellules */
            text-align: left;
            /* Alignement du texte */
        }

        #customers4 th {
            background-color: #f2f2f2;
            /* Couleur de fond de l'en-tête */
        }

        #customers4 td p {
            margin: 0;
            /* Supprimer les marges des paragraphes */
        }


        .invoice {
            width: 60%;
            height: 62.9rem;
            background-color: #ffff;
            padding: 0;
            page-break-before: always;
            margin: 10px auto;

        }

        .entete {
            border: 1px solid #ccc;
            font-size: 15px;
            background: #cccccc34;
        }

        /* .logo {
                margin-left: 20px;
                margin-top: 20px;
                width: 300px;
                height: 300px;
            } */

        .logoimg {
            width: 40%;
            /* margin-top: 0.5rem; */
        }

        /* .info {
                margin-left: 26rem;
                margin-top: -20rem;
            } */

        .bas {
            margin-top: 20px;
            border: 1px solid #ccc;
            font-size: 15px;
            background: #cccccc34;
        }

        .titre {
            margin: 20px auto;
            font-size: 15px;
        }

        h2 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .title {
            font-size: 17px;
            font-weight: bold;
            text-align: center;
        }

        .entreprise {
            margin-left: 40px;
            border: 1px solid black;
            background: #aeadad35;
            width: 14rem;
            height: 9rem;
            text-align: center;
            margin-top: 2rem;
        }

        .client {
            margin-top: -9.4rem;
            margin-left: 28rem;
            border: 1px solid black;
            width: 14rem;
            height: 9rem;
            text-align: center;
        }

        .infomecef {
            border: 1px solid black;
            width: 80%;
            margin: 2px auto;
            padding: 30px;
        }

        .qcode {
            /* margin-left: 70px; */
            padding: 0px 20px 20px 5px;
            margin-top: -1.3rem;
        }

        .mecef {
            margin-top: -8.5rem;
            margin-left: 8rem;
            font-size: 10px;
            padding: 2px 2px;
        }

        .textmontant {
            margin-left: 40px;
            margin-top: 20px;
        }

        .textremerciement {
            margin-left: 18px;
            /* margin-top: 6px; */
        }

        .logo1 {
            margin-left: 18px;
        }

        .info1 {
            margin-top: -1.5rem;
            margin-left: 28.5rem;
        }

        .prix {
            font-weight: bold;
            color: black;
            font-size: 16px;
            text-align: center;
            padding: 6px 6px;
        }

        @media print {

            /* .ko {
                    background-color: red !important;
                } */
            @page {
                size: portrait;
            }

            /* #customers3 th {
                    color: #a5d5e9;
                } */
        }

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 90%;
            margin-left: 40px;
            margin-top: 2rem;
        }

        #customers2 {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 150%;
            margin-left: 40px;

            margin-top: 2rem;
        }

        #customers8 {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 20%;
            margin-right: 0px;

            margin-top: 2rem;
        }

        #customers3 {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 90%;
            margin-left: 40px;
            margin-top: 2rem;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 200px;
            /* Taille maximale des colonnes */
            word-wrap: break-word;
            /* Permet de couper les mots et de passer à la ligne */
            word-break: break-all;
            /* Casse les mots plus longs que la taille de la colonne */
        }

        #customers2 td,
        #customers2 th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 200px;
            /* Taille maximale des colonnes */
            word-wrap: break-word;
            /* Permet de couper les mots et de passer à la ligne */
            word-break: break-all;
            /* Casse les mots plus longs que la taille de la colonne */
        }

        #customers3 td,
        #customers3 th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 200px;
            /* Taille maximale des colonnes */
            word-wrap: break-word;
            /* Permet de couper les mots et de passer à la ligne */
            word-break: break-all;
            /* Casse les mots plus longs que la taille de la colonne */
        }


        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers2 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers3 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers2 tr:hover {
            background-color: #ddd;
        }

        #customers3 tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #a5d5e9;
            color: black;
        }

        #customers2 th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #a5d5e9;
            color: black;
        }

        #customers3 th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #a5d5e9;
            color: black;
        }

        .table2 {
            margin-top: -0.5rem;

        }

        .table3 {
            margin-top: -0.5rem;
        }

        .telecharger {
            width: 8rem;
            height: 2.4rem;
            text-align: center;
            margin-top: 40px;
            margin-left: 10px;
        }
    </style>

    {{-- <body> --}}
    <div class="col-lg-12 grid-margin stretch-card">
        <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <div class="mt-5">
            <button class="btn btn-success" onclick="printInvoice()">Imprimer</button>
        </div>
        <br><br> <!-- Espacement entre les boutons -->
        {{-- <a class="telecharger btn btn-success" href="{{ url('/facturenormalise/' . $nomcompleteleve) }}"
            target="_blank">Imprimer</a> --}}
        <div class="invoice">

            <section>


                <div class="facture-container1">
                    <div class="info">
                        <div class="logo">
                            <img src="data:image/jpeg;base64,{{ base64_encode($logoUrl) }}" alt="Logo" class="logoimg">
                        </div>

                    <div>
                         {!! $entete !!}
                    </div>
                    </div>
                    <div class="info">
                        <h4><strong>FACTURE DE PAIEMENT</strong></h4>
                        <p><strong>Facture # {{ $reffacture }} </strong></p>
                        <p>Date : {{ $dateTime }}
                        </p>
                        <p>Vendeur : CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)</p>
                        {{-- <p>Réference fact. originale :</p> --}}
                    </div>
                </div>
            </section>

            <section>
                <div class="facture-container2">
                    <div class="table4 entre">
                        <table id="customers4">
                            <thead>
                                <tr>
                                    <th>Informations de l'établissement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{-- <p>Nom : {{ $NOMETAB }}</p> --}}
                                        {{-- <p>Nom : CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)</p> --}}
                                        <p>IFU : {{ $ifuEcoleFacture }}</p>
                                        {{-- <p>RCCM :</p>
                                <p>Adresse :</p>
                                <p>Contact :</p> --}}
                                        <p>VMCF : {{ $nim }}</p>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table4 cli">
                        <table id="customers4">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Eleve</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>Nom : {{ $nomcompleteleve }}</p>
                                        <p>Classe : {{ $classeeleve }}</p>
                                        {{-- <p>IFU : 0202380068074</p> --}}
                                        {{-- <p>Adresse :</p> --}}
                                        {{-- <p>Contact :</p> --}}

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <div class="tables-wrapper">
            <div class="tableZ">
                <table id="customers">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Montant T.T.C</th>
                            
                            {{-- <th>Montant T.T.C</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemFacture as $item)
                            <tr>
                                <td>{{ $item['name'] }} (E) </td>
                                <td style="text-align: end">{{ number_format($item['price'], 0, ',', ',') }}</td>
                                {{-- <td>{{ $item['price'] }}</td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="yes">
            <div class="table2 specifique">
                <table id="customers">
                    <thead>
                        <tr>
                            <th scope="col">Total</th>
                            <th scope="col">REGIME TPS [E]</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ number_format($montanttotal, 0, ',', ',') }}</td>
                            <td>{{  number_format($montanttotal, 0, ',', ',') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- <div class="facture-container5">
            <div class="table2">
                <table id="customers2">
                    <thead>
                        <tr>
                            <th>Groupe</th>
                            <th>Total</th>
                            <th>Imposable</th>
                            <th>Impôt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A - EXHONERER</td>
                            <td>{{ $facture->montant_total }}</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="customers8" class="table2">
                <hr class="line">
                <h3> Total : {{ $facture->montant_total }}</h3>
                <hr  class="line2">
            </div>
            </div> --}}

            <div class="table3">
                <table id="customers3">
                    <thead>
                        <tr>
                            <th>Type de paiement</th>
                            <th>Payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if ($mode_paiement == 1)
                                    ESPECES
                                @elseif($mode_paiement == 2)
                                    CHEQUES
                                @else
                                    AUTRE
                                @endif
                                
                                {{-- {{ $mode_paiement }} --}}
                            </td>
                            <td>{{ number_format($montanttotal, 0, ',', ',') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


            @php
            use NumberToWords\NumberToWords;

            // Crée le convertisseur
            $numberToWords = new NumberToWords();
            $transformer = $numberToWords->getNumberTransformer('fr');
            $abs = abs($montanttotal);
            $words = ucfirst(trim($transformer->toWords($abs)));

            // Si négatif, on préfixe "moins"
            if ($montanttotal < 0) {
                $words = 'Moins ' . $words;
            }
        @endphp

            <p class="textmontant">Arrêtée, la présente facture à la somme de <span class="prix">
                    {{ $words }} ({{ number_format($montanttotal, 0, ',', ',') }})</span> FCFA.</p>
            <br>
            {{-- <div class="table2"> --}}
                <div class="infomecef">
                    <div class="qcode">
                        <img src="data:image/jpeg;base64,{{ base64_encode($qrcodecontent) }}" alt="QR Code">
                    </div>
                    <div id="mecef" class="mecef">
                        <p><strong>Code MECeF/DGI:</strong> {{ $factureconfirm['codeMECeFDGI'] }}</p>
                        <p><strong>MECeF NIM:</strong> {{ $factureconfirm['nim'] }}</p>
                        <p><strong>MECeF Compteur:</strong> {{ $factureconfirm['counters'] }}</p>
                        <p><strong>MECeF Heure:</strong> {{ $factureconfirm['dateTime'] }}</p>
                    </div>
                </div>
            </div>
            {{-- <div class="bas">
                <div class="logo1">
                    <p><strong>CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)</strong></p>

                </div>
                <div class="info1">
                    <p>Fait à Cotonou le, <strong>{{ $factureconfirm['dateTime'] }}</strong></p>
                </div>
                <p class="textremerciement"><i>Merci d'avoir choisi CRYSTAL SERVICE INFO (TONY ABAMAN FIRMIN)</i></p>
            </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
    {{-- </body> --}}
    {{-- <script>
        function printInvoice() {
            // Vérifiez si une section spécifique doit être imprimée
            const invoice = document.querySelector('.invoice');
            const printWindow = window.open('', '', 'width=800,height=600');

            // Injectez le contenu à imprimer dans la nouvelle fenêtre
            printWindow.document.write('<html><head><title>Facture</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                    .btn-arrow { position: absolute; top: 0px; left: 0px; background-color: transparent !important; border: 1px !important; text-transform: uppercase !important; font-weight: bold !important; cursor: pointer !important; font-size: 17px !important; color: #b51818 !important; }
                    .btn-arrow:hover { color: #b700ff !important; }
                    body { font-family: Arial, sans-serif; }
                    @media print { .ko { background-color: blue; } }
                    p { font-size: 15px; margin-bottom: 20px; }
                    #mecef p { font-size: 12px; }
                    .page-break { display: none; }
                    .facture-container1, .facture-container2, .facture-container5 { display: flex; justify-content: space-between; gap: 50px; border-radius: 5px; margin-left: 40px; margin-right: 30px; margin-top: -1.8rem; }
                    .facture-container5 { margin-left: 0px; margin-right: 40px; }
                    .info { flex: 1; padding: 10px; border-radius: 5px; margin-top: 2rem; }
                    .table4 { width: 600px; overflow: auto; }
                    #customers4 { width: 100%; border-collapse: collapse; }
                    #customers4 th, #customers4 td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    #customers4 th { background-color: #f2f2f2; }
                    #customers4 td p { margin: 0; }
                    .invoice { width: 60%; height: 62.9rem; background-color: #ffff; padding: 0; page-break-before: always; margin: 10px auto; }
                    .entete, .bas { border: 1px solid #ccc; font-size: 15px; background: #cccccc34; }
                    .logoimg { width: 40%; }
                    .titre { margin: 20px auto; font-size: 15px; }
                    h2 { color: #333; }
                    ul { list-style-type: none; padding: 0; }
                    .title { font-size: 17px; font-weight: bold; text-align: center; }
                    .entreprise, .client { margin-left: 40px; border: 1px solid black; background: #aeadad35; width: 14rem; height: 9rem; text-align: center; margin-top: 2rem; }
                    .client { margin-top: -9.4rem; margin-left: 28rem; }
                    .infomecef { border: 1px solid black; width: 80%; margin: 2px auto; padding: 30px; }
                    .qcode { padding: 0px 20px 20px 5px; margin-top: -1.3rem; }
                    .mecef { margin-top: -8.5rem; margin-left: 8rem; font-size: 10px; padding: 2px 2px; }
                    .textmontant { margin-left: 40px; margin-top: 20px; }
                    .textremerciement, .logo1 { margin-left: 18px; }
                    .info1 { margin-top: -1.5rem; margin-left: 28.5rem; }
                    .prix { font-weight: bold; color: black; font-size: 16px; text-align: center; padding: 6px 6px; }
                    @media print { @page { size: portrait; } }
                    #customers, #customers2, #customers3, #customers8 { font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; margin-left: 40px; margin-top: 2rem; }
                    #customers { width: 90%; }
                    #customers2 { width: 150%; }
                    #customers8 { width: 20%; margin-right: 0px; }
                    #customers3 { width: 90%; }
                    #customers td, #customers th, #customers2 td, #customers2 th, #customers3 td, #customers3 th { border: 1px solid #ddd; padding: 8px; text-align: left; max-width: 200px; word-wrap: break-word; word-break: break-all; }
                    #customers tr:nth-child(even), #customers2 tr:nth-child(even), #customers3 tr:nth-child(even) { background-color: #f2f2f2; }
                    #customers tr:hover, #customers2 tr:hover, #customers3 tr:hover { background-color: #ddd; }
                    #customers th, #customers2 th, #customers3 th { padding-top: 12px; padding-bottom: 12px; background-color: #a5d5e9; color: black; }
                    .table2, .table3 { margin-top: -0.5rem; }
                    .telecharger { width: 8rem; height: 2.4rem; text-align: center; margin-top: 40px; margin-left: 10px; }
    
                /* Ajoutez d'autres styles ici si nécessaire */
            `);
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(invoice.innerHTML); // Copie le contenu de la facture
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Imprimez et fermez la fenêtre
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script> --}}

    {{-- <script>
    function printInvoice() {
        const invoice = document.querySelector('.invoice');
        const printWindow = window.open('', '', 'width=800,height=600');

        printWindow.document.write('<html><head><title>Facture</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            @media print {
                @page {
                    size: A5 portrait;
                }
                body {
                    margin: 0;
                    padding: 0;
                }
                .invoice {
                    page-break-inside: avoid;
                    break-inside: avoid;
                    zoom: 0.4;
                    width: 100%;
                    height: auto;
                    overflow: hidden;
                    box-sizing: border-box;
                }
                body, p, td, th {
                    font-size: 12px !important;
                }
            }

            body { font-family: Arial, sans-serif; }

            .btn-arrow { position: absolute; top: 0px; left: 0px; background-color: transparent !important; border: 1px !important; text-transform: uppercase !important; font-weight: bold !important; cursor: pointer !important; font-size: 17px !important; color: #b51818 !important; }
            .btn-arrow:hover { color: #b700ff !important; }
            p { font-size: 14px; margin-bottom: 10px; }
            #mecef p { font-size: 1px; }
            .facture-container1, .facture-container2, .facture-container5 { display: flex; justify-content: space-between; gap: 30px; border-radius: 5px; margin: 10px 20px; }
            .info { flex: 1; padding: 10px; border-radius: 5px; margin-top: 1rem; }
            .table4 { width: 100%; overflow: auto; }
            #customers4 { width: 100%; border-collapse: collapse; }
            #customers4 th, #customers4 td { border: 1px solid #ddd; padding: 6px; text-align: left; }
            #customers4 th { background-color: #f2f2f2; }
            #customers4 td p { margin: 0; }
            .invoice { background-color: #fff; padding: 10px; margin: 10px auto; width: 100%; }
            .entete, .bas { border: 1px solid #ccc; font-size: 13px; background: #cccccc34; }
            .titre { margin: 10px auto; font-size: 14px; }
            h2 { color: #333; }
            ul { list-style-type: none; padding: 0; }
            .title { font-size: 15px; font-weight: bold; text-align: center; }
            .entreprise, .client { margin-left: 20px; border: 1px solid black; background: #aeadad35; width: 10rem; height: 7rem; text-align: center; margin-top: 1rem; }
            .client { margin-top: -7.4rem; margin-left: 20rem; }
            .infomecef { border: 1px solid black; width: 90%; margin: 2px auto; padding: 10px; }
            .qcode { padding: 0px 10px 10px 5px; margin-top: -1rem; }
            .mecef { margin-top: -7rem; margin-left: 4rem; font-size: 10px; padding: 2px; }
            .textmontant { margin-left: 20px; margin-top: 10px; }
            .textremerciement, .logo1 { margin-left: 10px; }
            .info1 { margin-top: -1rem; margin-left: 20rem; }
            .prix { font-weight: bold; color: black; font-size: 14px; text-align: center; padding: 4px; }
            #customers, #customers2, #customers3, #customers8 {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                margin-left: 10px;
                margin-top: 1rem;
                width: 90%;
            }
            #customers td, #customers th,
            #customers2 td, #customers2 th,
            #customers3 td, #customers3 th {
                border: 1px solid #ddd;
                padding: 6px;
                text-align: left;
                word-break: break-word;
            }
            #customers tr:nth-child(even),
            #customers2 tr:nth-child(even),
            #customers3 tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            #customers th, #customers2 th, #customers3 th {
                background-color: #a5d5e9;
                color: black;
                font-size: 12px;
            }
        `);
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(invoice.innerHTML); // Injecte le HTML
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script> --}}

{{-- <script>
    function printInvoice() {
        const invoice = document.querySelector('.invoice');
        const printWindow = window.open('', '', 'width=800,height=600');

        printWindow.document.write('<html><head><title>Facture</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            @media print {
                @page {
                    size: A5 paysage;
                    margin: 5mm;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-size: 10px;
                }
                .invoice {
                    zoom: 0.6;
                    width: 100%;
                    height: auto;
                    margin: 0 auto;
                    padding: 0;
                    overflow: hidden;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                *, *::before, *::after {
                    box-sizing: border-box;
                }
            }

            body { font-family: Arial, sans-serif; font-size: 10px; }

            .btn-arrow { display: none; }

            p, td, th, li, h1, h2, h3, h4 {
                font-size: 10px !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            #customers, #customers2, #customers3, #customers8 { font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; margin-left: 40px; margin-top: 2rem; }

            #customers td, #customers th, #customers2 td, #customers2 th, #customers3 td, #customers3 th { border: 1px solid #ddd; padding: 8px; text-align: left; max-width: 200px; word-wrap: break-word; word-break: break-all; }
            #customers tr:nth-child(even), #customers2 tr:nth-child(even), #customers3 tr:nth-child(even) { background-color: #f2f2f2; }
            #customers tr:hover, #customers2 tr:hover, #customers3 tr:hover { background-color: #ddd; }
            #customers th, #customers2 th, #customers3 th { padding-top: 12px; padding-bottom: 12px; background-color: #a5d5e9; color: black; }

            #customers4 th, #customers4 td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            #customers4 th { background-color: #f2f2f2; }

            .entete, .bas { border: 1px solid #ccc; font-size: 15px; background: #cccccc34; }

            .facture-container1, .facture-container2, .facture-container5 {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                margin: 0 0;
            }

            .info {
                padding: 5px;
            }

            .logo img{
                width: 90px;
                height: 60px;
            }

            .qcode img{
                width: 60px;
                height: 60px;
            }

            .invoice {
                background-color: #fff;
                margin: 0 auto;
                padding: 5px;
            }

            .entete, .bas {
                font-size: 10px;
                padding: 4px;
            }



            .cli, .entre {
                width: 43% !important;
                background: #aeadad35; 
            }

            .info {
                width: 43% !important;
            }

            .infomecef {
                width: 90%;
                margin: 5px auto;
                padding: 5px;
            }

            .mecef { margin-top: -3.8rem; margin-left: 4rem; font-size: 10px; padding: 2px; }

            .prix {
                font-weight: bold;
                font-size: 11px;
                text-align: center;
            }

            #customers, #customers2, #customers3, #customers4 {
                width: 100%;
                border-collapse: collapse;
                margin: 5px 0;
            }

            table, th, td {
                border: 1px solid #ccc;
                padding: 4px;
                font-size: 10px;
            }

            .table2 {
                width: 50% !important;
            }

            .table3 {
                width: 45% !important;
                margin-left: 18rem;
            }
            
            .specifique {
                width: 45% !important;
                margin-top: -4rem;
                margin-left: 18rem;
            }

            .bas {
                width: 45% !important;
                margin-top: -5rem;
                margin-left: 18rem;
            }

            th {
                background-color: #e0e0e0;
            }
        `);
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(invoice.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script> --}}

<script>
    function printInvoice() {
        const invoice = document.querySelector('.invoice');
        const printWindow = window.open('', '', 'width=800,height=600');

        printWindow.document.write('<html><head><title>Facture</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            @media print {
                @page {
                    size: A5 paysage;
                    margin: 5mm;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-size: 10px;
                }
                .invoice {
                    zoom: 0.6;
                    width: 100%;
                    height: auto;
                    margin: 0 auto;
                    padding: 0;
                    overflow: hidden;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                *, *::before, *::after {
                    box-sizing: border-box;
                }
            }

            body { font-family: Arial, sans-serif; font-size: 10px; }

            .btn-arrow { display: none; }

            p, td, th, li, h1, h2, h3, h4 {
                font-size: 10px !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            #customers, #customers2, #customers3, #customers8 { font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; margin-left: 40px; margin-top: 2rem; }

            #customers td, #customers th, #customers2 td, #customers2 th, #customers3 td, #customers3 th { border: 1px solid #ddd; padding: 8px; text-align: left; max-width: 200px; word-wrap: break-word; word-break: break-all; }
            #customers tr:nth-child(even), #customers2 tr:nth-child(even), #customers3 tr:nth-child(even) { background-color: #f2f2f2; }
            #customers tr:hover, #customers2 tr:hover, #customers3 tr:hover { background-color: #ddd; }
            #customers th, #customers2 th, #customers3 th { padding-top: 12px; padding-bottom: 12px; background-color: #a5d5e9; color: black; }

            #customers4 th, #customers4 td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            #customers4 th { background-color: #f2f2f2; }
            
            .entete, .bas { border: 1px solid #ccc; font-size: 15px; background: #cccccc34; }

            .facture-container1, .facture-container2, .facture-container5 {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                margin: 0 0;
            }

            .info {
                padding: 5px;
            }

            .logo img{
                width: 90px;
                height: 60px;
            }

            .qcode img{
                width: 60px;
                height: 60px;
            }

            .invoice {
                background-color: #fff;
                margin: 0 auto;
                padding: 5px;
            }

            .entete, .bas {
                font-size: 10px;
                padding: 4px;
            }



            .cli, .entre {
                width: 43% !important;
                background: #aeadad35; 
            }

            .info {
                width: 43% !important;
            }

            .infomecef {
                width: 100%;
                margin: 0px auto;
                margin-left: 135px;
                padding: 5px;
            }

            .mecef { margin-top: -3.4rem; margin-left: 4rem; font-size: 10px; padding: 2px; }

            .prix {
                font-weight: bold;
                font-size: 11px;
                text-align: center;
            }

            #customers, #customers2, #customers3, #customers4 {
                width: 100%;
                border-collapse: collapse;
                margin: 5px 0;
            }

            table, th, td {
                border: 1px solid #ccc;
                padding: 4px;
                font-size: 10px;
            }


            .tables-wrapper {
            display: flex;
            align-items: flex-start;    
            justify-content: space-between;
            gap: 10px;                   
            }

            .table2 {
            flex: 2;                    
            max-width: 100%;
            box-sizing: border-box;
            }
            .tableZ{
                width : 50%;
            }
                    

            .yes {
            flex: 1; 
                max-width: 50% !important;
            }

            

        

                    

                    .bas {
                        width: 45% !important;
                        margin-top: -5rem;
                        margin-left: 18rem;
                    }

                    th {
                        background-color: #e0e0e0;
                    }
                `);
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(invoice.innerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();

                printWindow.focus();
                printWindow.print();
                printWindow.close();
    }
</script>

@endsection



