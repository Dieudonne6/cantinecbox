
@extends('layouts.master')
@section('content')
<style>
    .logoimg {
        width: 35%;
        margin-top: 20px;
    }
</style>
<body>
    {{-- <a class="telecharger btn btn-primary" href="{{ url('/duplicatainscription/' . $elevyo) }}" target="_blank">Imprimer</a> --}}

    <div class="container-fluid d-flex  align-items-center justify-content-center" >
        <div id="contenu">

        </div>
            <div class="col-6 mx-auto contenu"  style="background-color:white;">
              <div class="d-flex justify-content-between mb-5">
                <div>
                  {{-- @if($logoUrl)
                  <img src="{{ asset('storage/logo/' . $logoUrl) }}" alt="Logo" class="logoimg">
                  @else
                      <p>Aucun logo disponible.</p>
                  @endif --}}
                </div>
                <div>
                  <h5 style="padding-top:20px;">
                    COMPLEXE SCOLAIRE: 
                  </h5> 
                  <h5>"le petit poucet"</h5>
                </div>
              </div>

               
                <h5 style="text-align: center">
                    Reçu d'inscription cantine
                </h5><br>

                    <div class="row mx-auto">
                        <div class="col-5">
                            <p style="font-weight:bold;">Date de paiement :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $dateContrat }}</p><br>
                        </div>
                    </div>
                    <div class="row mx-auto">
                      <div class="col-5">
                          <p style="font-weight:bold;">Eleve :</p><br>
                      </div>
                      <div class="col-5" style="margin-left: 5rem">
                          <p>{{ $elevyo }}</p><br>
                      </div>
                  </div>
                    <div class="row mx-auto">
                        <div class="col-5">
                            <p style="font-weight:bold;">Montant paiement :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $amount }}</p><br>
                        </div>
                    </div>
                  
                  
                    <div class="row mx-auto">
                        <div class="col-5">
                            <p style="font-weight:bold;">Classe :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $classe }}</p><br>
                        </div>
                    </div>
                    <div class="row mx-auto">
                        <div class="col-5">
                            <p style="font-weight:bold;">Signature Caissier</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p style="font-weight:bold;">Signature Parent</p><br>
                        </div>
                    </div>
                    <div class="row mx-auto">
                        <div class="col-5">
                            <p style="font-weight:bold;"></p><br><br>
                        </div>
                        <div class="col-5">
                            <p style="font-weight:bold;"></p><br><br>
                        </div>
                    </div>



            </div>
    </div>
</body>

@endsection

