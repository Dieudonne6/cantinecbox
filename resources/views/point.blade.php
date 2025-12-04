@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-lg border-0 p-4">

        <style>
            .btn-arrow {
                position: absolute;
                top: 10px;
                left: 10px;
                background-color: transparent !important;
                border: none !important;
                text-transform: uppercase !important;
                font-weight: 600 !important;
                cursor: pointer !important;
                font-size: 18px !important;
                color: #b51818 !important;
                transition: all 0.3s ease;
            }

            .btn-arrow:hover {
                color: #7a00ff !important;
                transform: translateX(-3px);
            }

            .point {
                border: 2px solid #222;
                padding: 6px 12px;
                border-radius: 8px;
                display: inline-block;
                font-weight: 600;
                background-color: #f8f9fa;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .card {
                background-color: #ffffff;
                border-radius: 15px;
            }

            h4 {
                font-weight: 700;
                color: #333;
                margin-bottom: 15px;
            }

            p {
                margin-bottom: 8px;
                color: #444;
            }

            hr {
                border: none;
                border-top: 2px dashed #ccc;
                margin: 25px 0;
            }

            .section-container {
                padding: 20px;
                border-radius: 10px;
                background-color: #fdfdfd;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
                flex: 1;
            }

            .text-right {
                text-align: right !important;
            }

            /* === Tableau amélioré === */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                font-size: 15px;
                border-spacing: 0;
            }

            table th, table td {
                padding: 8px 12px;
                text-align: left;
            }

            table th {
                background-color: #f0f0f0;
                font-weight: 700;
                border-bottom: 2px solid #333;
            }

            table tr {
                border-bottom: 1px solid #ccc;
            }

            table td, table th {
                border-left: none;
                border-right: none;
            }

            table tr:last-child {
                border-bottom: 2px solid #333;
            }

            .last_point {
                font-size: 50px !important;
            }

            /* === Nouvelle mise en page sans col-md === */
            .print-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 20px;
                flex-wrap: wrap;
            }

            .bloc-souche {
                width: 45%;
            }

            .bloc-separation {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 2%;
            }

            .bloc-original {
                width: 50%;
            }

            /* Responsive */
            @media screen and (max-width: 992px) {
                .bloc-souche, .bloc-original {
                    width: 100%;
                }

                .bloc-separation {
                    display: none;
                }
            }
        </style>

        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
            <i class="fas fa-arrow-left"></i> Retour
        </button>

        <br><br>
        <div class="text-end">
            <button onclick="imprimerPage()" type="button" class="btn btn-primary">
                Imprimer
            </button>
        </div>

        <div class="container" id="point">
            <div class="print-row">

                {{-- Premier bloc --}}
                <div class="bloc-souche section-container">
                    <div>{!! $entete !!}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="point">HISTORIQUE DES PAIEMENTS </p>
                        <p>{{ date('d/m/Y') }}</p>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <p>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                        <p>Classe : {{ $eleve->CODECLAS }}</p>
                    </div>

                    <table>
                        <tr>
                            <th>Date</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                        </tr>

                        @forelse ($paiements as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->DATEOP)->format('d/m/Y') }}</td>
                                <td>
                                    @switch($p->AUTREF)
                                        @case(1) Scolarité @break
                                        @case(2) Arriéré @break
                                        @case(3) {{ $params->LIBELF1 ?? 'LIBELF1' }} @break
                                        @case(4) {{ $params->LIBELF2 ?? 'LIBELF2' }} @break
                                        @case(5) {{ $params->LIBELF3 ?? 'LIBELF3' }} @break
                                        @case(6) {{ $params->LIBELF4 ?? 'LIBELF4' }} @break
                                        @default Autre
                                    @endswitch
                                </td>
                                <td>{{ number_format($p->MONTANT, 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Aucun paiement trouvé</td></tr>
                        @endforelse
                    </table>

                    <br>
                    
                    <div>
                        <p><strong>Total à payer :</strong> <span style="float: right;">{{ number_format($totalAPayer, 0, ',', ' ') }}</span></p>
                        <p>Montant Total Payé<span style="float: right;">{{ number_format($totalPaye, 0, ',', ' ') }} F</span></p>
                        <p>Reste à payer<span style="float: right;">0</span></p>                
                        <p>Arrièrés restants<span style="float: right;">{{ number_format($arriereRestant, 0, ',', ' ') }}</span></p>
                        <p>Scolarité restants<span style="float: right;">{{ number_format($scolariteRestant, 0, ',', ' ') }}</span></p>
                        <p>{{ $params->LIBELF1 }} restants<span style="float: right;">{{ number_format($libelF1Restant, 0, ',', ' ') }}</span></p>
                        <p>{{ $params->LIBELF2 }} restants<span style="float: right;">{{ number_format($libelF2Restant, 0, ',', ' ') }}</span></p>
                        <p>{{ $params->LIBELF3 }} restants<span style="float: right;">{{ number_format($apeRestant, 0, ',', ' ') }}</span></p>
                        <p>{{ $params->LIBELF4 }} restants<span style="float: right;">{{ number_format($libelF4Restant, 0, ',', ' ') }}</span></p>
                        <p style="margin-left: 3rem;"><strong>Total restant :</strong> <span style="float: right;">{{number_format($resteAPayer, 0, ',', ' ')}}</span></p>
                    </div>
                    <br>
                    <p style="font-weight: 500; float: right; padding-bottom:5rem;">Signature, cachet</p>
                </div>

                {{-- Trait de séparation --}}
                <!-- <div class="bloc-separation">
                    <hr style="height: 200px; width: 2px; background-color: black; border: none;">
                </div> -->

                {{-- Deuxième bloc --}}
                <!-- <div class="bloc-original section-container">
                    <div>{!! $entete !!}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="point">POINT PAYEMENT (Original)</p>
                        <p>{{ date('d/m/Y') }}</p>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <p>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                        <p>Classe : {{ $eleve->CODECLAS }}</p>
                    </div>

                    {{-- Même contenu du tableau et du résumé ici --}}
                    <table>
                        <tr>
                            <th>Date</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                        </tr>

                        @forelse ($paiements as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->DATEOP)->format('d/m/Y') }}</td>
                                <td>
                                    @switch($p->AUTREF)
                                        @case(1) Scolarité @break
                                        @case(2) Arriéré @break
                                        @case(3) {{ $params->LIBELF1 ?? 'LIBELF1' }} @break
                                        @case(4) {{ $params->LIBELF2 ?? 'LIBELF2' }} @break
                                        @case(5) {{ $params->LIBELF3 ?? 'LIBELF3' }} @break
                                        @case(6) {{ $params->LIBELF4 ?? 'LIBELF4' }} @break
                                        @default Autre
                                    @endswitch
                                </td>
                                <td>{{ number_format($p->MONTANT, 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Aucun paiement trouvé</td></tr>
                        @endforelse
                    </table>
                    <br>
                    {{-- <div class="container mt-1">
                        <div style="
                            border: 1px solid #333;                         
                            padding: 2px 2px;
                            background-color: #fdfdfd;
                        ">

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Arriéré restant :</strong> <span style="float: right;">{{$arriereRestant}}</span></p>
                                    <p><strong>{{$params->LIBELF1}} :</strong> <span style="float: right;">{{$libelF1Restant}}</span></p>
                                    <p><strong>{{$params->LIBELF4}} restant :</strong> <span style="float: right;">{{$libelF4Restant}}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Écolage restant :</strong> <span style="float: right;">{{$scolariteRestant}}</span></p>
                                    <p><strong>{{$params->LIBELF2}} restant:</strong> <span style="float: right;">{{$libelF2Restant}}</span></p>
                                    <p><strong>{{$params->LIBELF3}} restant:</strong> <span style="float: right;">{{$apeRestant}}</span></p>
                                </div>
                            </div>
                           
                        </div>
                    </div>--}}
                    
                    <div>
                        <p><strong>Total à payer :</strong> <span style="float: right;">{{ $totalAPayer }}</span></p>
                        <p>Paie actuel<span style="float: right;">{{ number_format($totalPaye, 0, ',', ' ') }} F</span></p>
                        <p>Reste à payer<span style="float: right;">0</span></p>                
                        <p>Arrièrés restants<span style="float: right;">{{ $arriereRestant }}</span></p>
                        <p>{{ $params->LIBELF1 }}<span style="float: right;">{{ $libelF1Restant }}</span></p>
                        <p>{{ $params->LIBELF2 }}<span style="float: right;">{{ $libelF2Restant }}</span></p>
                        <p>{{ $params->LIBELF3 }}<span style="float: right;">{{ $apeRestant }}</span></p>
                        <p>{{ $params->LIBELF4 }}<span style="float: right;">{{ $libelF4Restant }}</span></p>
                        <p style="margin-left: 3rem;"><strong>Total restant :</strong> <span style="float: right;">{{$resteAPayer}}</span></p>
                    </div>
                    <br>
                    <p style="font-weight: 500;">Signature, cachet</p>  
                    <br>
                </div> -->
            </div>
        </div>
    </div>
</div>

<script>
function imprimerPage() {
    // On récupère le contenu à imprimer
    const contenu = document.getElementById('point').innerHTML;

    // On récupère tous les styles Bootstrap + internes
    const styles = [
        ...document.querySelectorAll('link[rel="stylesheet"], style')
    ].map(node => node.outerHTML).join('\n');

    // On ouvre une nouvelle fenêtre d'impression
    const printWindow = window.open('', '', 'height=900,width=800');

    printWindow.document.write(`
        <html>
            <head>
                <title>Impression du point de paiement</title>
                ${styles}
                <style>
                    body {
                        background: white !important;
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        margin: 15px;
                        font-family: Arial, sans-serif !important;
                    }

                    /* On garde le flex côte à côte */
                    .print-row {
                        display: flex !important;
                        justify-content: space-between !important;
                        align-items: flex-start !important;
                        gap: 20px !important;
                        flex-wrap: nowrap !important;
                    }

                    .bloc-souche, .bloc-original {
                        width: 48% !important;
                    }

                    .bloc-separation {
                        display: flex !important;
                        justify-content: center !important;
                        align-items: center !important;
                        width: 2% !important;
                    }

                    /* On cache les boutons */
                    .btn, .btn-arrow {
                        display: none !important;
                    }

                    .card, .section-container {
                        box-shadow: none !important;
                        border: 1px solid #bbb !important;
                    }

                    @page {
                        size: A4;
                        margin: 10mm 15mm;
                    }
                </style>
            </head>
            <body>${contenu}</body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    // Attendre le chargement complet avant d’imprimer
    printWindow.onload = () => {
        printWindow.print();
        printWindow.close();
    };
}
</script>

@endsection
