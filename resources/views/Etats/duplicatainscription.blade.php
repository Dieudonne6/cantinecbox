
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
    padding: 1rem;

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
      padding: 0.5rem;
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
              <h4>Facture d'inscription cantine </h4>
          </div>
      </div>

      <div class="titre" style="margin-bottom: 6rem;">

          <div class="entreprise">
              <p><i class="title">Ecole</i></p>
              <p>IFU:<strong>0202380068074</strong></p>
              <p>Ecole:<strong> complexe scolaire "le petit poucet" </strong></p>
          </div>



          <div class="client">
              <p><i class="title">Eleve</i></p>
              {{-- <p>IFU:<strong> {{ $facturedetaille['ifu'] }}</strong></p> --}}
              <p>Nom eleve : <strong>{{ $elevyo }}</strong></p>
              <p>Classe eleve : <strong>{{ $classe }}</strong></p>
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
                          <td>
                              Inscription Cantine 
                          </td>


                          <td>
                              {{ $amount }}
                          </td>

                          <td>
                              {{ $dateContrat }}
                          </td>

                         

                      </tr>
              </tbody>
          </table>

      </div>

  

      <div class="bas">
          <div class="logo1">
              <p><strong> complexe scolaire petit poucet </strong> </p>
              {{-- <p><strong> {{ $nometab }} </strong> </p> --}}
              {{-- <img src="" alt=""> --}}
          </div>

          <div class="info1">
              <p>Fait a cotonou le , <strong>{{ $dateContrat}} </strong></p>
              {{-- <p>Fait a {{ $villeetab }} le , <strong>{{ $factureconfirm['dateTime'] }} </strong></p> --}}
              {{-- <p>Reference 909090909090   </p> --}}
          </div>
          <p class="textremerciement"><i>Merci d'avoir choisi le complexe scolaire petiti poucet. </i> </p>
          {{-- <p class="textremerciement"><i>Merci d'avoir choisi le {{ $nometab }}. </i> </p> --}}

      </div>
  </div>

</body>
