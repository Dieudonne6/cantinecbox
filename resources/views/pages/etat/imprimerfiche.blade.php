
@extends('layouts.master')
@section('content')

<body>
    <button onclick="imprimerPage()" class="btn btn-primary" style="width: 10rem">Imprimer</button>
    <div class="container-fluid d-flex  align-items-center justify-content-center" >
        <div id="contenu">

        </div>
            <div class="col-6 contenu"  style="background-color:white;">
                <h5 style="text-align: center; padding-top:20px;">
                    COMPLEXE SCOLAIRE: ""
                </h5>
                <h5 style="text-align: center">
                    RECU DE PAIEMENT
                </h5><br>

                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Date de paiement :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $date_paiementcontrat }}</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Montant paiement :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $solde }}</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Frais paye pour :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $mois }}</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Eleve :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $nomcompeleve }}</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Classe :</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p>{{ $classeeleve }}</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <p style="font-weight:bold;">Signature Caissier</p><br>
                        </div>
                        <div class="col-5" style="margin-left: 5rem">
                            <p style="font-weight:bold;">Signature Parent</p><br>
                        </div>
                    </div>
                    <div class="row">
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

<script>
    function imprimerPage() {
        var page = window.open();
        page.document.write('<html><head><title>fiche_{{$nomcompeleve}}</title>');
        page.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >');
        page.document.write('</head><body>');
            page.document.write(document.querySelector('.contenu').innerHTML);
            
        page.document.write('</body></html>');
        page.document.close();
        page.print();
    }
    
  </script>