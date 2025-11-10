


@extends('layouts.master')

@section('content')
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
        color: #000;
        background: #fff;
    }
    .invoice-part {
        border: 1px solid #000;
        width: 49%;
        position: relative;
        padding: 10px 15px;
        min-height: 500px;
    }

    .watermark {
        position: absolute;
        font-size: 70px;
        color: rgba(0, 0, 0, 0.26);
        font-weight: bold;
        transform: rotate(-30deg);
        top: 40%;
        left: 20%;
        pointer-events: none;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
    }

    .header .school-info {
        flex: 1;
        padding-left: 10px;
    }

    .header .school-info h4 {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }

    .amount-box {
        border: 1px solid #000;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        width: 150px;
        padding: 5px 0;
    }

    .amount-box small {
        display: block;
        font-size: 12px;
        font-weight: normal;
    }

    .eleve-box{
        border: 1px solid #000000a0;
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        width: 100%;
        padding: 3px 0;
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    .student-info {
        margin-top: 10px;
        font-size: 15px;
    }

    .student-info strong {
        font-weight: bold;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .details-table td {
        padding: 4px 8px;
        font-size: 15px;
    }

    .footer {
        text-align: center;
        font-size: 14px;
        margin-top: 30px;
    }

    .sign {
        margin-top: 40px;
        text-align: center;
        font-weight: bold;
    }

    .ajour-box {
        border: 1px solid #000;
        text-align: center;
        padding: 5px;
        font-weight: bold;
        margin-top: 10px;
    }

    .right-details {
        text-align: center;
        font-size: 14px;
        line-height: 1.4;
    }
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        .btn-print {
            display: none;
        }
    }

    .custom-container {
    display: flex;
    gap: 20px; /* espace entre les sections */
    }

    .left-section {
        flex: 2; /* équivalent à col-md-8 */
    }

    .right-section {
        flex: 1; /* équivalent à col-md-4 */
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
    }

    .details-table td {
        border: 1px solid #000;
        padding: 5px;
    }

    .ajour-box {
        background-color: #d6d3d3;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .right-details {
        background-color: #f1f1f1;
        padding: 10px;
    }

    .logoimg {
        width: 20% !important;
        height: 30% !important;
    }
</style>


<div class="container mt-4">

            <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
            @php
                use NumberToWords\NumberToWords;
                $numberToWords = new NumberToWords();
                $transformer = $numberToWords->getNumberTransformer('fr');
                $abs = abs($montanttotal);
                $words = ucfirst(trim($transformer->toWords($abs)));
                // if ($words < 0) {
                    $words = $words;
                // }
            @endphp
    <div class="text-end mb-3">
        <button class="btn btn-success" onclick="printInvoice()">🖨️ Imprimer</button>
    </div>

    <div id="reçue" class="container">
        <div class="row">
            {{-- ===== Partie SOUCHE ===== --}}
            <div class="invoice-part col-md-4">
                <div class="watermark">DUPLICATA</div>

                <div class="header">
                    <div>
                        <p>{{$classeeleve}}</p>
                    </div>
                    {{-- <div class="school-info">
                        {!!$entete!!}
                    </div> --}}
                    <div class="amount-box">
                            {{$montanttotal}} F
                        <small> 
                            @if ($mode_paiement == 1)
                                ESPECES
                            @elseif($mode_paiement == 2)
                                CHEQUES
                            @else
                                AUTRE
                            @endif
                        </small>
                    </div>
                </div>

                <div class="title mt-2">
                    QUITTANCE N° <span style="font-size: 14px;">{{$reffacture }}</span>
                </div>

                <div class="student-info">
                    <strong>{{$nomcompleteleve}}</strong>
                </div>

                <div class="details-table">
                    <br>
                    <table width="100%">
                        <tr>
                            <td>Montant :</td>
                            {{-- <td><strong>({{ ucfirst($fmt->format($total)) }} francs CFA)</strong></td> --}}
                            <td><strong>({{ $words }} francs CFA)</strong></td>
                        </tr>
                        <tr>
                            <td>Scolarité</td>                            
                            <td>{{$scolaritéPayéAujourdhui}}</td>
                        </tr>
                        <tr>
                            <td>Arrièré</td>                          
                            <td>{{$arrierréPayéAujourdhui}}</td> 
                        </tr>
                        <tr>
                            <td>{{$LIBELF1}}</td>                          
                            <td>{{$frais1PayéAujourdhui}}</td> 
                        </tr>
                        <tr>
                            <td>{{$LIBELF2}}</td>                        
                            <td>{{$frais2PayéAujourdhui}}</td> 
                        </tr>
                        <tr>
                            <td>{{$LIBELF3}}</td>                     
                            <td>{{$frais3PayéAujourdhui}}</td>  
                        </tr>
                        <tr>
                            <td>{{$LIBELF4}}</td>                           
                            <td>{{$frais4PayéAujourdhui}}</td> 
                        </tr>
                    </table>
                </div>

                <div style="text-align: left; font-size: 14px; margin-top: 8rem;">            
                    <p>{{$ville}}, le {{ \Carbon\Carbon::parse($datePaiement)->format('d/m/Y') }}</p>
                    <p><strong>{{ $titreComptable }}</strong></p> <br><br><br>
                    <p><u>{{ $nomComptable }}</u></p>                 
                </div>
                <div style="text-align: end">
                     <small><strong>{{ $editeur }}</strong> </small>
                </div>

                
            </div>

            {{-- ===== Partie ORIGINALE ===== --}}
            <div class="invoice-part col-md-8">
                <div class="watermark">DUPLICATA</div>

                <div class="header">
                   
                    @if($logoUrl)
                        <img src="data:image/jpeg;base64,{{ base64_encode($logoUrl) }}" alt="Logo" class="logoimg">
                    @endif
                    
                    <div class="school-info" style="margin-right: 5rem;">
                        {!!$entete!!}
                    </div>
                    <div class="amount-box">
                        {{$montanttotal}}F
                        <small> 
                            @if ($mode_paiement == 1)
                                ESPECES
                            @elseif($mode_paiement == 2)
                                CHEQUES
                            @else
                                AUTRE
                            @endif
                        </small>
                    </div>
                </div>

                <div class="title mt-2">
                    QUITTANCE N° <span style="font-size: 14px;">{{$reffacture }}</span>
                </div>

                <div class="eleve-box">
                    <br>
                    <p><strong>{{$nomcompleteleve}}</strong> - {{$classeeleve}}</p>
                    <p><strong>({{ $words }} francs CFA)</strong></p>
                </div>

                <div class="custom-container">
                    <div class="left-section">
                        <table class="details-table">
                            <tr>                               
                                <td></td>
                                <td><strong>Ancien Solde</strong></td>
                                <td><strong>Montant payé</strong></td>                          
                            </tr>
                            <tr>
                                <td>Scolarité</td>
                                <td>{{$totalRestantScolarité}}</td>
                                <td>{{$scolaritéPayéAujourdhui}}</td>
                            </tr>
                            <tr>
                                <td>Arrièré</td>
                                <td>{{$totalRestantArrierre}}</td>
                                <td>{{$arrierréPayéAujourdhui}}</td> 
                            </tr>
                            <tr>
                                <td>{{$LIBELF1}}</td>
                                <td>{{$totalRestantFrais1}}</td>
                                <td>{{$frais1PayéAujourdhui}}</td> 
                            </tr>
                            <tr>
                                <td>{{$LIBELF2}}</td>
                                <td>{{$totalRestantFrais2}}</td>
                                <td>{{$frais2PayéAujourdhui}}</td> 
                            </tr>
                            <tr>
                                <td>{{$LIBELF3}}</td>
                                <td>{{$totalRestantFrais3}}</td>
                                <td>{{$frais3PayéAujourdhui}}</td>  
                            </tr>
                            <tr>
                                <td>{{$LIBELF4}}</td>
                                <td>{{$totalRestantFrais4}}</td>
                                <td>{{$frais4PayéAujourdhui}}</td> 
                            </tr>                            
                        </table>
                    </div>
                    <div class="right-section">
                        <div class="ajour-box">
                            @if ($resteEcheance == 0)
                                A JOUR  
                            @else
                                NON A JOUR
                            @endif
                        </div>
                        <div class="right-details">
                            Reste à payer <br><strong>[ {{$totalGlobalRestantAPayer}} FCFA]</strong><br>
                            Reste % échéancier <br><strong>[ {{$resteEcheance}} FCFA]</strong>
                        </div>
                    </div>
                </div>


                <div style="text-align: left; font-size: 14px; margin-top: 3rem;">            
                    <p>{{$ville}}, le {{ \Carbon\Carbon::parse($datePaiement)->format('d/m/Y') }}</p>
                    <p><strong> {{ $titreComptable }} </strong></p> <br><br><br>
                    <p><u>{{ $nomComptable }}</u></p>
                </div>
                <div style="text-align: end">                
                    {{-- <p><u>Signature</u></p> <br><br><br>   --}}
                    <small>Edité par <strong>{{ $editeur }}</strong></small>
                </div>
            </div>
        </div></br>

        <p>Edition Logiciel SCHOOLBOX 01 Tél +229 01 97 79 17 17 &nbsp;&nbsp;&nbsp; Edition Logiciel SCHOOLBOX 01 Tél +229 01 97 79 17 17 e-mail: contact@cbox.bj &nbsp;&nbsp; C BOX SARL (BENIN) </p>
    </div>
    <br><br><br><br><br><br> 
</div>

<script>
    function printInvoice() {
        const recu = document.getElementById('reçue');
        if (!recu) {
            alert('Aucune zone "reçue" trouvée à imprimer.');
            return;
        }

        // Ouvre la fenêtre d'impression
        const printWindow = window.open('', '', 'width=1000,height=800');

        // Clone du contenu HTML à imprimer
        const contentHtml = recu.outerHTML;

        // Liens Bootstrap + styles de la page
        const links = `
            <link rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
                crossorigin="anonymous">
        ` + Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
            .map(l => `<link rel="stylesheet" href="${l.href}">`)
            .join('\n');

        const styles = Array.from(document.querySelectorAll('style'))
            .map(s => s.outerHTML)
            .join('\n');

        // Styles d'impression renforcés
        const extraHead = `
            ${links}
            <style>
                @page { size: A4 portrait; margin: 10mm; }
                body {
                    font-family: Arial, sans-serif;
                    color: #000;
                    background: #fff;
                    margin: 0;
                    padding: 10px;
                }
                .btn, .btn-print, .no-print { display: none !important; }

                /* ✅ Force l'affichage des colonnes même sans Bootstrap */
                .row {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    justify-content: space-between;
                    width: 100%;
                }
                .col-md-8, .col-md-4 {
                    display: inline-block !important;
                    vertical-align: top;
                    box-sizing: border-box;
                    padding: 5px;
                }
                .col-md-8 { width: 66.66% !important; }
                .col-md-4 { width: 33.33% !important; }

                /* Sécurité : jamais "overflow:hidden" sur la partie droite */
                .invoice-part { overflow: visible !important; }

                /* Visibilité renforcée de la partie droite */
                .ajour-box, .right-details {
                    color: #000 !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
            </style>
            ${styles}
        `;

        printWindow.document.open();
        printWindow.document.write(`<!doctype html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Reçu de Paiement</title>
                ${extraHead}
            </head>
            <body>
                ${contentHtml}
                <script>
                    (function(){
                        const imgs = document.getElementsByTagName('img');
                        for (let i = 0; i < imgs.length; i++) {
                            const img = imgs[i];
                            try {
                                const u = new URL(img.getAttribute('src'), window.opener ? window.opener.location.href : location.href);
                                img.src = u.href;
                            } catch(e) { }
                        }
                    })();
                    window.onload = function() {
                        setTimeout(function() {
                            window.focus();
                            window.print();
                            window.close();
                        }, 400);
                    };
                <\/script>
            </body>
            </html>`);
        printWindow.document.close();
    }
</script>

@endsection
