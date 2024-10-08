<script src="{{ asset('davidshimjs-qrcodejs-04f46c6/qrcode.js') }}"></script>

<style>
    body {
        font-family: Arial, sans-serif;
    }

    .invoice {
        width: 100%;
        /* margin: 20px auto; */
        /* border: 1px solid #ccc; */
        background-color: #fffff;
        padding: 0;
        /* height: 100% */
    }

    .entete {
        /* margin:20px  auto; */
        border: 1px solid #ccc;
        font-size: 15px;
        background: #cccccc34;
    }

    .logo {
        margin-left: 20px;
        margin-top: 20px;
        width: 300px;
        height: 300px;
    }

    .logoimg {
        width: 25%;
        margin-top: -1rem;
    }

    .info {
        margin-left: 26rem;
        margin-top: -20rem;
    }

    .bas {
        margin-top: 20px;
        border: 1px solid #ccc;
        font-size: 15px;
        background: #cccccc34;
    }

    .titre {
        margin: 20px auto;
        /* border: 1px solid #ccc; */
        font-size: 15px;
        /* background: #ccc; */
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



    /* .info{
                            float: left;
                        } */

    .entreprise {
        margin-left: 40px;
        border: 1px solid black;
        background: #aeadad35;
        width: 14rem;
        text-align: center;
        margin-top: 2rem;

    }


    .client {
        margin-top: -8.4rem;
        margin-left: 28rem;
        border: 1px solid black;
        width: 14rem;
        text-align: center;

    }

    .infomecef {
        border: 1px solid black;
        width: 80%;
        margin: 40px auto;
        padding: 30px;
    }

    .qcode {
        margin-left: 70px;
        padding: 0px 20px 20px 5px;
        margin-top: -1rem;
    }

    .mecef {
        margin-top: -7.8rem;
        margin-left: 15rem;
        font-size: 10px;
        padding: 2px 2px;
    }

    .textmontant {
        margin-left: 40px;
        margin-top: 20px;
    }

    .textremerciement {
        margin-left: 18px;
        /* text-align: center; */
        margin-top: 6px;

    }

    .logo1 {
        margin-top: 10px;
        margin-left: 18px;
    }

    .info1 {
        margin-top: -2.5rem;
        margin-left: 28.5rem;
    }


    .prix {
        /* background: rgb(27, 27, 27); */
        font-weight: bold;
        color: black;
        font-size: 16px;
        text-align: center;
        padding: 6px 6px
            /* background-size: 50px */
            /* height: 50px;
                            width: 50px; */
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
        color: black;
    }

    .table2 {
        margin-top: 20px;
    }

    .telecharger {
        width: 8rem;
        height: 2.4rem;
        text-align: center;
        margin-top: 20px;
        margin-left: 10px;
    }
</style>

<body onload="window.print();">
    {{-- <a class="telecharger btn btn-success" href="{{ route('merci') }}">Telecharger</a> --}}
    <div class="invoice">
        <div class="entete">
            <div class="logo">
                @if($logoUrl)
                <img src="data:image/jpeg;base64,{{ base64_encode($logoUrl) }}" alt="Logo" class="logoimg">
                @else
                    <p>Aucun logo disponible.</p>
                @endif                
                {{-- <img src="" alt=""> --}}
            </div>

            <div class="info">
                <p>Facture de Paiement </p>
                <p>Reference:<strong>{{ $reffacture }}</strong></p>
            </div>
        </div>

        <div class="titre">

            <div class="entreprise">
                <p><i class="title">Ecole</i></p>
                <p>IFU:<strong>0202380068074</strong></p>
                <p>Ecole:<strong> complexe scolaire "le petit poucet" </strong></p>
            </div>



            <div class="client">
                <p><i class="title">Eleve</i></p>
                {{-- <p>IFU:<strong> {{ $facturedetaille['ifu'] }}</strong></p> --}}
                <p>Nom eleve : <strong>{{ $nomcompleteleve }}</strong></p>
                <p>Classe eleve : <strong>{{ $classeeleve }}</strong></p>
            </div>

        </div>





        {{-- <h2>code mecef {{ $factureconfirm['codeMECeFDGI'] }} </h2>
        <h2>Facture</h2>
        <ul>
            <li><strong>IFU:</strong> {{ $facturedetaille['ifu'] }}</li>
            <li><strong>Type:</strong> {{ $facturedetaille['type'] }}</li>
        </ul> --}}

        <div class="tableau">
            <table id="customers">
                <thead>
                    <tr>
                        <th scope="col">Designation</th>
                        {{-- <th scope="col">Prix unitaire</th> --}}
                        {{-- <th scope="col">Quantite</th> --}}
                        <th scope="col">Montant HT</th>
                        <th scope="col">tva</th>
                        <th scope="col">Mois</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $totalHT = 0;
                        $totalTTC = 0;
                    @endphp

                    @foreach ($facturedetaille['items'] as $item)
                    @php
                    // Définition du taux de TVA initial
                    $tauxTVA = 0;
                
                    // Vérification de la valeur de taxGroup
                    if ($item['taxGroup'] == 'B') {
                        // Si taxGroup est 'B', appliquer le taux de TVA 18%
                        $tauxTVA = 0.18;
                    } elseif ($item['taxGroup'] == 'A') {
                        // Si taxGroup est 'A', appliquer le taux de TVA 1%
                        $tauxTVA = 0.01;
                    }
                
                    // Calcul du montant de TVA
                    $tva = $item['price'] * $tauxTVA;
                
                    // Calcul du montant TTC pour chaque article en ajoutant la TVA
                    $totalTTCItem = $item['price'] + $tva;
                
                    // Ajout du montant TTC de l'article au total TTC
                    $totalTTC += $totalTTCItem;
                
                    // Ajout du montant HT de l'article au total HT
                    $totalHT += $item['price'];
                @endphp
                        <tr>
                            <td>
                                {{ $item['name'] }}
                            </td>


                            <td>
                                {{ $item['price'] }}
                            </td>

                            <td>
                                {{ $tva }}
                            </td>

                            <td>
                                {{ $toutmoiscontrat }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="table2">
            <table id="customers">
                <thead>
                    <tr>
                        <th scope="col">Groupe tax</th>
                        <th scope="col">Montant total HT</th>
                        <th scope="col">Net a Payer</th>

                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>
                            B-TAX
                        </td>

                        <td>
                            {{ $totalHT }}
                        </td>

                        <td>
                            {{ $totalTTC }}
                        </td>


                    </tr>
                </tbody>
            </table>

        </div>
        <p class="textmontant">Arretée, la presente facture a la somme de <span
                class="prix">{{ $totalTTC }}</span> FCFA .</p>

        {{-- <h2>Operator: {{ $facturedetaille['operator']['name'] }}</h2> --}}

        <div class="infomecef">

            <div class="qcode">
                <div >

                <img src="data:image/jpeg;base64,{{ base64_encode($qrcodecontent) }}" alt="qrcode">


                    {{-- <img src="{{ asset('qrcode/codeqr.png') }}" alt="Code QR" /> --}}
                </div>
            </div>

            <div class="mecef">
                <p><strong>Code MECeF/DGI:</strong> {{ $factureconfirm['codeMECeFDGI'] }}</p>
                <p><strong>MECeF NIM:</strong> {{ $factureconfirm['nim'] }}</p>
                <p><strong>MECeF Compteur:</strong> {{ $factureconfirm['counters'] }}</p>
                <p><strong>MECeF Heure:</strong> {{ $factureconfirm['dateTime'] }}</p>
            </div>
        </div>

        <div class="bas">
            <div class="logo1">
                <p><strong> complexe scolaire petit poucet </strong> </p>
                {{-- <p><strong> {{ $nometab }} </strong> </p> --}}
                {{-- <img src="" alt=""> --}}
            </div>

            <div class="info1">
                <p>Fait a cotonou le , <strong>{{ $factureconfirm['dateTime'] }} </strong></p>
                {{-- <p>Fait a {{ $villeetab }} le , <strong>{{ $factureconfirm['dateTime'] }} </strong></p> --}}
                {{-- <p>Reference 909090909090   </p> --}}
            </div>
            <p class="textremerciement"><i>Merci d'avoir choisi le complexe scolaire petiti poucet. </i> </p>
            {{-- <p class="textremerciement"><i>Merci d'avoir choisi le {{ $nometab }}. </i> </p> --}}

        </div>
    </div>

    {{-- <a class="telecharger btn btn-success" href="{{ url('telechargerfacture') }}">Imprimer</a> --}}
    {{-- <a class="telecharger btn btn-success" onclick="printContent()" href="{{ url('/facturenormalise') }}">Imprimer</a> --}}
    {{-- <button onclick="window.print()">Imprimer</button> --}}
    {{-- <button onclick="printContent()">Imprimer</button> --}}


    {{-- <h2> Merci pour votre confiance votre facture a ete generer et vous serras envoyer lors dee la livraison de votre commande</h2> --}}
    {{-- {{ dd($factureconfirm) }} --}}
    {{-- 
    <h2>qr code {{ $factureconfirm['qrCode'] }} </h2>
    <h2>code mecef {{ $factureconfirm['codeMECeFDGI'] }} </h2>

    <h2>IFU: {{ $facturedetaille['ifu'] }}</h2>
    <h2>Type: {{ $facturedetaille['type'] }}</h2>
    
    <h3>Items:</h3>
    <ul>
        @foreach ($facturedetaille['items'] as $item)
            <li>
                <strong>Name:</strong> {{ $item['name'] }},
                <strong>Price:</strong> {{ $item['price'] }},
                <strong>Quantity:</strong> {{ $item['quantity'] }},
                <strong>Tax Group:</strong> {{ $item['taxGroup'] }}
            </li>
        @endforeach
    </ul>
    
    <h2>Operator: {{ $facturedetaille['operator']['name'] }}</h2> --}}

    {{-- 
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="{{asset('assets/css/bootstrap.min.js')}}"></script> --}}
    {{-- <script>
        // Obtenez la chaîne du QR code depuis le serveur
        var qrCodeStrings = "{{ $qrCodeString }}";

        // Générez le QR code et affichez-le dans le conteneur avec l'ID "qrcode"
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrCodeStrings,
            width: 100,
            height: 100
        });
    </script> --}}
