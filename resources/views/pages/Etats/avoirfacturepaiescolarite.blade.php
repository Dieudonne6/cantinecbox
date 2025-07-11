
@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  .btn-arrow {
      position: absolute;
      top: 0px; /* Ajustez la position verticale */
      left: 0px; /* Positionnez à gauche */
      background-color: transparent !important;
      border:1px !important;
      text-transform: uppercase !important;
      font-weight: bold !important;
      cursor: pointer!important;
      font-size: 17px!important; /* Taille de l'icône */
      color: #b51818!important; /* Couleur de l'icône */
  }
  
  .btn-arrow:hover {
      color: #b700ff !important; /* Couleur au survol */
  }
  </style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button></br>

        @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div></br>
        @endif

        @if(Session::has('erreur'))
            <div id="statusAlert" class="alert alert-danger btn-primary">
            {{ Session::get('erreur')}}
            </div></br>
        @endif
                <h4 class="card-title" style="text-align: center">Annulation de la facture de paiement de <strong>{{ $factureOriginale->nom }}</strong></h4>

                <form action="{{url('avoirfacturescolarite/'.$codemecef)}}" method="POST">
                    @csrf
                    {{-- @if(Session::has('id_usercontrat'))
                        <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
                    @endif --}}
                    <div class="col-md-8 mx-auto grid-margin stretch-card">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Infos de la facture de vente</h4>
                                {{-- <p class="card-description">
                                    Veuillez remplir les champs
                                </p> --}}
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Date de paiement</label>
                                        <div id="the-basics">
                                            <input class="typeaheads" type="datetime-local" id="date" name="date"
                                                value="{{ $factureOriginale->dateHeure }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Montant</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="montantTotal" name="montantTotal" type="text" value="{{ $factureOriginale->montant_total }}" readonly>
                                            {{-- <input class="typeaheads" id="fraismensuelle" name="montantcontratReel" type="hidden" value="{{ $fraismensuelle }}" > --}}
                                        </div>
                                    </div>
                                    {{-- <div class="col">
                                        <label>Reduction</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="reduction" name="reduction" type="number" >
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mx-auto grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Entrer le codemef de la facture originale</h4>
                                <div class="form-group">
                                    <div class="col">
                                        {{-- <p>Nombre de cases cochées : <span id="checked-count">0</span></p> --}}
                                        <label for="codemecefEntrer">Codemecef Facture Originale</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="codemecefEntrer" name="inputCodemecef" type="text" >
                                            {{-- <p style="visibility: hidden"><span id="checked-count">0</span></p> --}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto grid-margin stretch-card mt-5 mb-5">

                        <input type="submit" class="btn btn-primary mr-2" value="Confirmer">
                        <input type="reset" class="btn btn-light" value="Annuler">
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

