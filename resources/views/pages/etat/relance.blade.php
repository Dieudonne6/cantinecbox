@extends('layouts.master')
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <title>Résultats de la Relance</title>
        <style>
            .page {
                page-break-after: always;
            }

            .relance {
                height: 50vh;
                box-sizing: border-box;
                padding: 20px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 20px;
            }

            .logo {
                width: 100px;
                /* Ajustez cette valeur selon la taille de votre logo */
            }

            .info {
                text-align: right;
                width: 70%;
            }

            .content {
                text-align: left;
                margin-top: 2rem;
                /* Ajustez cette valeur si nécessaire */
            }

            .piedlettre {
                text-align: right;
                margin-top: 5rem;
                /* Ajustez cette valeur si nécessaire */
            }


            @media print {
                body * {
                    visibility: hidden;
                }

                .printable,
                .printable * {
                    visibility: visible;
                }

                .printable {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .relance {
                    height: 50vh;
                    box-sizing: border-box;
                    padding: 20px;
                }
            }
        </style>
    </head>

    <body>
        <div class="printable">
            @if (count($results) > 0)
                @foreach (array_chunk($results, 2) as $resultPair)
                    <div class="page">
                        @foreach ($resultPair as $result)
                            <div class="relance">
                                <div class="header">
                                    <div class="logo">
                                        {{-- <img src="path/to/logo.png" alt="Logo" class="logo"> --}}
                                   
                                        @if($logoUrl)
                                        <img src="data:image/jpeg;base64,{{ base64_encode($logoUrl) }}" alt="Logo" class="logo">
                                        @else
                                            <p>Aucun logo disponible.</p>
                                        @endif
                                    </div>
                                    <div class="info">
                                        <p>Cotonou le {{ date('d/m/Y') }}</p><br>
                                        <p>Aux parents de</p>
                                        <p style="font-weight: bold">{{ $result['nometclasse']['nom_complet'] }}</p>
                                        <p style="font-weight: bold">{{ $result['nometclasse']['classe'] }}</p>
                                    </div>
                                </div><br><br>
                                <div class="content">
                                    <p>Chers parents,</p><br><br>
                                    <p>Sauf erreur ou omission de notre part, nous rappelons qu'au titre des frais de
                                        cantine <strong> 2022_2023 </strong> de votre enfant, vous restez devoir dans nos livres, la somme de {{ $result['total_du'] }}
                                        {{-- cantine <strong> {{ $databaseName }} </strong> de votre enfant, vous restez devoir dans nos livres, la somme de {{ $result['total_du'] }} --}}
                                        pour le compte de <strong> {{ implode(', ', $result['mois_impayes']) }}</strong>.</p><br>
                                    <p>Vous êtes invités à régler ce solde au plus tard le <strong>{{ $result['datebuttoire'] }}</strong></p>
                                    <p>Vous remerciant par avance pour votre compréhension.</p>
                                </div>

                                <div class="piedlettre">
                                    <h5>La Direction.</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p>Aucun élève avec des contrats impayés pour les mois précédents la date sélectionnée.</p>
            @endif
        </div>

        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </body>

    </html>
@endsection
