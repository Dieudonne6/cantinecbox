@extends('layouts.master')
@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            width: 55%;
            margin: 20px auto;
            border: 1px solid #ccc;
            background-color: #ffffff;
            padding: 0;
            height: 100%;
        }

        .entete {
            padding: 1rem;
            border: 1px solid #ccc;
            font-size: 15px;
            background: #cccccc34;
        }

        .logo {
            margin-left: 22px;
            width: 30%;
        }

        .logoimg {
            width: 40%;
        }

        .info {
            padding: 0.5rem;
            margin-left: 22rem;
            margin-top: -3rem;
        }

        .bas {
            margin-top: 20px;
            margin-bottom: 20px;
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
            width: 11rem;
            text-align: center;
            margin-top: 2rem;
        }

        .client {
            margin-top: -7rem;
            margin-left: 25rem;
            border: 1px solid black;
            width: 11rem;
            text-align: center;
        }

        .info1 {
            margin-top: 0.5rem;
            margin-left: 28.5rem;
        }

        .tableau {
            margin-top: 3.5rem;
            margin-bottom: 2.5rem;
            /* margin-left: 28.5rem; */
        }

        .textremerciement {
            margin-top: -2.5rem;
            margin-left: 0.5rem;
        }

        .prix {
            background: rgb(27, 27, 27);
            color: white;
            font-size: 16px;
            text-align: center;
            padding: 6px;
        }

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 90%;
            margin-left: 40px;
            margin-top: 1.5rem;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .telecharger {
            width: 8rem;
            height: 2.4rem;
            text-align: center;
            margin-top: 20px;
            margin-left: 10px;
            display: block;
        }

        @media print {
            .telecharger {
                display: none;
            }
        }
    </style>

    <body>
        <a class="telecharger btn btn-primary" href="{{ url('/duplicatainscription2/' . $factureIns->id_contrat) }}"
            target="_blank">Imprimer</a>
        <div class="invoice">
            <div class="entete">
                <div class="logo">
                    @if (isset($logo))
                        <img src="data:image/jpeg;base64,{{ base64_encode($logo) }}" alt="Logo" class="logoimg">
                    @else
                        <p>Aucun logo disponible.</p>
                    @endif
                </div>

                <div class="info">
                    <p>Facture d'inscription cantine</p>
                </div>
            </div>

            <div class="titre">
                <div class="entreprise">
                    <p><i class="title">Ecole</i></p>
                    <p>IFU:<strong>{{ $ifuEcole }}</strong></p>
                    <p>Ecole:<strong>{{ $nomecole }}</strong></p>
                </div>

                <div class="client">
                    <p><i class="title">Eleve</i></p>
                    <p>Nom : <strong>{{ $nomcompeleve }}</strong></p>
                    <p>Classe : <strong>{{ $classeeleve }}</strong></p>
                </div>
            </div>

            <div class="tableau">
                <table id="customers">
                    <thead>
                        <tr>
                            <th scope="col">Designation</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Date de paiement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td >
                                Inscription Cantine
                            </td>
                            <td>{{ $factureIns->cout_contrat }}</td>
                            <td>
                                {{
                                    \Carbon\Carbon::hasFormat($factureIns->datecreation_contrat, 'Y-m-d H:i:s') 
                                    ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $factureIns->datecreation_contrat)->format('d/m/Y') 
                                    : (\Carbon\Carbon::hasFormat($factureIns->datecreation_contrat, 'd/m/Y H:i:s') 
                                    ? \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $factureIns->datecreation_contrat)->format('d/m/Y') 
                                    : 'Format de date non supporté')
                                  }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bas">
                <div class="info1">
                    <p>Fait à Cotonou le <strong>
                        {{
                            \Carbon\Carbon::hasFormat($factureIns->datecreation_contrat, 'Y-m-d H:i:s') 
                            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $factureIns->datecreation_contrat)->format('d/m/Y') 
                            : (\Carbon\Carbon::hasFormat($factureIns->datecreation_contrat, 'd/m/Y H:i:s') 
                            ? \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $factureIns->datecreation_contrat)->format('d/m/Y') 
                            : 'Format de date non supporté')
                          }}
                          </strong></p>
                </div>
                <p class="textremerciement"><i>Merci d'avoir choisi le {{ $nomecole }}</i></p>
            </div>
        </div>
        <br>
        <br>
    </body>
@endsection
