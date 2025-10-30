@extends('layouts.master')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .btn-arrow {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: transparent !important;
            border: 1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            color: #b51818 !important;
        }

        .btn-arrow:hover {
            color: #b700ff !important;
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

        .page-break {
            display: none;
        }

        .facture-container1 {
            display: flex;
            justify-content: space-between;
            gap: 50px;
            border-radius: 5px;
            margin-left: 40px;
            margin-right: 30px;
            margin-top: -1.8rem;
        }

        .facture-container2 {
            display: flex;
            justify-content: space-between;
            gap: 50px;
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
            padding: 10px;
            border-radius: 5px;
            margin-top: 2rem;
        }

        .table4 {
            width: 600px;
            overflow: auto;
        }

        #customers4 {
            width: 100%;
            border-collapse: collapse;
        }

        #customers4 th,
        #customers4 td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #customers4 th {
            background-color: #f2f2f2;
        }

        #customers4 td p {
            margin: 0;
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

        .logoimg {
            width: 40%;
        }

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
            @page {
                size: portrait;
            }
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
            word-wrap: break-word;
            word-break: break-all;
        }

        #customers2 td,
        #customers2 th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 200px;
            word-wrap: break-word;
            word-break: break-all;
        }

        #customers3 td,
        #customers3 th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 200px;
            word-wrap: break-word;
            word-break: break-all;
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
    </style>

    <div class="col-lg-12 grid-margin stretch-card">
        <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <div class="mt-5">
            <button class="btn btn-success" onclick="printInvoice()">Imprimer</button>
        </div>
        <br><br>
        
        <div class="invoice">
            <section>
                <div class="facture-container1">
                    <div class="info">
                        <div class="logo">
                            @if($logoUrl)
                                <img src="data:image/jpeg;base64,{{ base64_encode($logoUrl) }}" alt="Logo" class="logoimg">
                            @endif
                        </div>
                        <div>
                            {!! $entete !!}
                        </div>
                    </div>
                    <div class="info">
                        <h4><strong>FACTURE DE PAIEMENT</strong></h4>
                        <p><strong>Facture # {{ $reffacture }} </strong></p>
                        <p>Date : {{ $dateTime }}</p>
                        <p>Vendeur : {{ $NOMETAB }}</p>
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
                                        <p>Nom : {{ $NOMETAB }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table4 cli">
                        <table id="customers4">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Élève</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>Nom : {{ $nomcompleteleve }}</p>
                                        <p>Classe : {{ $classeeleve }}</p>
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
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemFacture as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td style="text-align: end">{{ number_format($item['price'], 0, ',', ',') }}</td>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ number_format($montanttotal, 0, ',', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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
                                        {{-- {{ $libelleMode }} --}}
                                        @if ($mode_paiement == 1)
                                            ESPECES
                                        @elseif($mode_paiement == 2)
                                            CHEQUES
                                        @else
                                            AUTRE
                                        @endif
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
                $numberToWords = new NumberToWords();
                $transformer = $numberToWords->getNumberTransformer('fr');
                $abs = abs($montanttotal);
                $words = ucfirst(trim($transformer->toWords($abs)));
                if ($montanttotal < 0) {
                    $words = 'Moins ' . $words;
                }
            @endphp

            <p class="textmontant">Arrêtée, la présente facture à la somme de <span class="prix">
                {{ $words }} ({{ number_format($montanttotal, 0, ',', ',') }})</span> FCFA.</p>
            <br>
        </div>
    </div>

    <script>
        function printInvoice() {
            const invoice = document.querySelector('.invoice');
            const printWindow = window.open('', '', 'width=800,height=600');

            printWindow.document.write('<html><head><title>Facture Non Normalisée</title>');
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

                #customers, #customers2, #customers3 { 
                    font-family: Arial, Helvetica, sans-serif; 
                    border-collapse: collapse; 
                    margin-left: 40px; 
                    margin-top: 2rem; 
                }

                #customers td, #customers th, #customers2 td, #customers2 th, #customers3 td, #customers3 th { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left; 
                    max-width: 200px; 
                    word-wrap: break-word; 
                    word-break: break-all; 
                }
                
                #customers tr:nth-child(even), #customers2 tr:nth-child(even), #customers3 tr:nth-child(even) { 
                    background-color: #f2f2f2; 
                }
                
                #customers th, #customers2 th, #customers3 th { 
                    padding-top: 12px; 
                    padding-bottom: 12px; 
                    background-color: #a5d5e9; 
                    color: black; 
                }

                #customers4 th, #customers4 td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left; 
                }
                
                #customers4 th { 
                    background-color: #f2f2f2; 
                }

                .facture-container1, .facture-container2 {
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

                .invoice {
                    background-color: #fff;
                    margin: 0 auto;
                    padding: 5px;
                }

                .cli, .entre {
                    width: 43% !important;
                    background: #aeadad35; 
                }

                .info {
                    width: 43% !important;
                }

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