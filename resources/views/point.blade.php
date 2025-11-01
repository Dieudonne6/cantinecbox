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

            /* Supprimer les traits verticaux */
            table td, table th {
                border-left: none;
                border-right: none;
            }

            table tr:last-child {
                border-bottom: 2px solid #333;
            }

            .last_point{
                font-size: 50px !important;
            }
        </style>

        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
            <i class="fas fa-arrow-left"></i> Retour
        </button>

        <br><br>

        <div class="container">
            <div class="row justify-content-between align-items-start">

                {{-- Premier bloc --}}
                <div class="col-md-5 section-container">
                    <div>{!!$entete!!}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="point">POINT PAYEMENT (Souche)</p>
                        <p>{{ date('d/m/Y') }}</p>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <p>{{ $eleve->NOM }} {{ $eleve->PRENOM }} </p>
                        <p>Classe : {{$eleve->CODECLAS}}</p>
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
                                        @case(1)
                                            Scolarité
                                            @break
                                        @case(2)
                                            Arriéré
                                            @break
                                        @case(3)
                                            {{ $params->LIBELF1 ?? 'LIBELF1' }}
                                            @break
                                        @case(4)
                                            {{ $params->LIBELF2 ?? 'LIBELF2' }}
                                            @break
                                        @case(5)
                                            {{ $params->LIBELF3 ?? 'LIBELF3' }}
                                            @break
                                        @case(6)
                                            {{ $params->LIBELF4 ?? 'LIBELF4' }}
                                            @break
                                        @default
                                            Autre
                                    @endswitch
                                </td>
                                <td>{{ number_format($p->MONTANT, 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun paiement trouvé</td>
                            </tr>
                        @endforelse
                    </table>
                    <br>
                    <div>
                        <p><strong>Total à payer :</strong> <span style="float: right;">{{$totalAPayer}}</span></p>
                        <p classe="last_point">Paie actuel<span style="float: right;">{{ number_format($totalPaye, 0, ',', ' ') }} F</span></p>
                        <p classe="last_point">Reste à payer<span style="float: right;">0</span></p>                
                        <p classe="last_point">Arrièrés restants<span style="float: right;">{{$arriereRestant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF1}}<span style="float: right;">{{$libelF1Restant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF2}}<span style="float: right;">{{$libelF2Restant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF3}}<span style="float: right;">{{$apeRestant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF4}}<span style="float: right;">{{$libelF4Restant}}</span></p>
                        <p style="margin-left: 3rem;"><strong>Total restant :</strong> <span style="float: right;">0</span></p>
                    </div>
                   <br> <p style="font-weight: 500;">Signature, cachet</p>
                </div>

                {{-- Trait de séparation --}}
                <div class="col-md-1 d-flex justify-content-center align-items-center">
                    <hr style="height: 200px; width: 2px; background-color: black; border: none;">
                </div>

                {{-- Deuxième bloc --}}
                <div class="col-md-6 section-container">
                    <div>{!!$entete!!}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="point">POINT PAYEMENT (Original)</p>
                        <p> {{ date('d/m/Y') }}</p>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <p>{{ $eleve->NOM }} {{ $eleve->PRENOM }} </p>
                        <p>Classe : {{$eleve->CODECLAS}}</p>
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
                                        @case(1)
                                            Scolarité
                                            @break
                                        @case(2)
                                            Arriéré
                                            @break
                                        @case(3)
                                            {{ $params->LIBELF1 ?? 'LIBELF1' }}
                                            @break
                                        @case(4)
                                            {{ $params->LIBELF2 ?? 'LIBELF2' }}
                                            @break
                                        @case(5)
                                            {{ $params->LIBELF3 ?? 'LIBELF3' }}
                                            @break
                                        @case(6)
                                            {{ $params->LIBELF4 ?? 'LIBELF4' }}
                                            @break
                                        @default
                                            Autre
                                    @endswitch
                                </td>                               
                                <td>{{ number_format($p->MONTANT, 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun paiement trouvé</td>
                            </tr>
                        @endforelse
                    </table>

                    <div class="container mt-1">
                        <div style="
                            border: 1px solid #333;                         
                            padding: 2px 2px;
                            background-color: #fdfdfd;
                        ">

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Arriéré restant :</strong> <span style="float: right;">{{$arriereRestant}}</span></p>
                                    <p><strong>{{$params->LIBELF1}} :</strong> <span style="float: right;">{{$libelF1Restant}}</span></p>
                                    <p><strong>{{$params->LIBELF4}} :</strong> <span style="float: right;">{{$libelF4Restant}}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Écolage restant :</strong> <span style="float: right;">{{$scolariteRestant}}</span></p>
                                    <p><strong>{{$params->LIBELF2}} :</strong> <span style="float: right;">{{$libelF2Restant}}</span></p>
                                    <p><strong>{{$params->LIBELF3}} :</strong> <span style="float: right;">{{$apeRestant}}</span></p>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <br>
                    <div >
                        <p ><strong>Total à payer :</strong> <span style="float: right;"> {{$totalAPayer}}</span></p>
                        <p classe="last_point" >Total payé<span style="float: right;">{{ number_format($totalPaye, 0, ',', ' ') }} F</span></p>
                        <p classe="last_point">Arrièrés restants<span style="float: right;">{{$arriereRestant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF1}}<span style="float: right;">{{$libelF1Restant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF2}}<span style="float: right;">{{$libelF2Restant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF3}}<span style="float: right;">{{$apeRestant}}</span></p>
                        <p classe="last_point">{{$params->LIBELF4}}<span style="float: right;">{{$libelF4Restant}}</span></p>
                        <p style="margin-left: 3rem;"><strong>Total restant :</strong> <span style="float: right;">0</span></p>
                    </div>
                   <br> <p style="font-weight: 500; text-align: end;">Signature, cachet</p>

                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
