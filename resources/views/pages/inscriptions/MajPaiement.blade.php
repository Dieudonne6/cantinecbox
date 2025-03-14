@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="col-md-12">
            <div class="card">
              <div>
                <style>
                    .btn-arrow {
                        position: absolute;
                        top: 0px;
                        /* Ajustez la position verticale */
                        left: 0px;
                        /* Positionnez à gauche */
                        background-color: transparent !important;
                        border: 1px !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        /* Taille de l'icône */
                        color: #b51818 !important;
                        /* Couleur de l'icône */
                    }
            
                    .btn-arrow:hover {
                        color: #b700ff !important;
                        /* Couleur au survol */
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br>
                <br>                                     
            </div>
                <div class="card-body">
                  <h4 class="card-title">
                    Mis à jour des paiements de {{ $eleve->NOM }} {{ $eleve->PRENOM }}
                </h4>                    {{-- <p class="card-description">
                    A simple suggestion engine
                  </p> --}}
                    <div class="row">
                        <div class="col-6">
                            <h5 style="text-align: center; color: rgb(188, 64, 64)">Scolarité</h5>
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($scolarite as $item)
                                        <tr>
                                          <td>{{ $item->DATEOP }}</td>
                                          <td>{{ $item->MONTANT }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm">Modifier</button>
                                                <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                            </td>
                                            {{-- <td rowspan="6">150000</td> --}}
                                        </tr>
                                        @endforeach
                                        <tr>
                                          <th>Total</th>
                                          <th>{{ $totalScolarite }}</th>
                                          <th></th>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            {{-- <div style="text-align: center">
                                <button type="button" class="btn btn-primary btn-sm">Ajou Scolarite</button>
                                <button type="button" class="btn btn-danger btn-sm" >Sup Scolarite</button>
                            </div> --}}

                        </div>


                        {{-- deuxieme tableau --}}

                        <div class="col-6">
                            <h5 style="text-align: center; color: rgb(188, 64, 64)">Arriérés</h5>
                            <div class="table-responsive pt-3">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($arrières as $item)
                                        <tr>
                                          <td>{{ $item->DATEOP }}</td>
                                          <td>{{ $item->MONTANT }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm">Modifier</button>
                                                <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                            </td>
                                            {{-- <td rowspan="6">150000</td> --}}
                                        </tr>
                                        @endforeach
                                        <tr>
                                          <th>Total</th>
                                          <th>{{ $totalArrieres }}</th>
                                          <th></th>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            {{-- <div style="text-align: center">
                                <button type="button" class="btn btn-primary btn-sm">Ajou Arriere</button>
                                <button type="button" class="btn btn-danger btn-sm" >Sup Arriere</button>
                            </div> --}}
                        </div>
                    </div>
                </div><br>

                <div class="row">
                             {{-- troisieme tableau --}}

                             <div class="col-8">

                              <h5 style="text-align: center; color: rgb(188, 64, 64)">Récapitulatif par reçu</h5>
                              <div class="table-responsive pt-3">
  
                                  <table class="table table-bordered">
                                      <thead>
                                          <tr>
                                              <th>no recu</th>
                                              <th>Date</th>
                                              <th>Montant</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>

                                          <tr>
                                              <td>004</td>
                                              <td>21/23/2025</td>
                                              <td>150000</td>
                                              <td>
                                                  <button type="button" class="btn btn-primary btn-sm">Modifier</button>
                                                  <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                              </td>
                                              {{-- <td rowspan="6">150000</td> --}}
                                          </tr>

                                          <tr>
                                              <td>005</td>
                                              <td>21/23/2025</td>
                                              <td>150000</td>
                                              <td>
                                                  <button type="button" class="btn btn-primary btn-sm">Modifier</button>
                                              <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                              </td>
                                          </tr>
                                          <tr>
                                              <th colspan="2">
                                                  Total
                                              </th>
                                              <th>3300</th>
                                          </tr>


                                      </tbody>
                                  </table>                                </div>
                                <br>
                                {{-- <div style="text-align: center">
                                    <button type="button" class="btn btn-primary btn-sm">Ajou Recap</button>
                                    <button type="button" class="btn btn-danger btn-sm" >Sup Recap</button>
                                </div> --}}

                            </div>

                            {{-- derniere partie --}}

                                <div class="col-3 mt-4 ml-4">

                                    <div>
                                        <button type="button" class="btn btn-inverse-secondary btn-fw btn-sm" style="margin-top: 10px">En attente / eleve</button>
                                        <button type="button" class="btn btn-inverse-secondary btn-fw btn-sm" style="margin-top: 10px">En attente / eleve</button>
                                        <button type="button" class="btn btn-inverse-secondary btn-fw btn-sm" style="margin-top: 10px">Recapitulatif paiement</button>
                                    </div>

                                </div>

                </div>
                   

                    </div><br><br>

                                {{-- 
                  <div class="row">
                    <div class="col-12">

                      <div>

                        <table class="table">
                          <thead>
                            <tr>
                              <th>Numero</th>
                              <th>Date</th>
                              <th>Montant</th>
                              <th>Montant Paie</th>
                              <th>SIGNATURE</th>
                            </tr>
                          </thead>
                          <tbody>
  
                            <tr>
                              <td>001</td>
                              <td>21/23/2025</td>
                              <td>150000</td>
                              <td>Cheque</td>
                              <td>COMPTABILITE</td>
                            </tr>
         
                            <tr>
                              <td>002</td>
                              <td>21/23/2025</td>
                              <td>150000</td>
                              <td>Cheque</td>
                              <td>COMPTABILITE</td>
                            </tr>
       
                          </tbody>
                        </table>
                      </div>

                      
                    </div>
                  </div><br><br>

                  <div class="row">
                    <div class="col-10">
                      <div class="form-group row">
                        <div class="col">
                          <label>Date Operation</label>
                          <div id="the-basics">
                            <input class="form-control" type="date" >
                          </div>
                        </div>
                        <div class="col">
                          <label>Montant paye</label>
                          <div id="bloodhound">
                            <input class="form-control" type="text" >
                          </div>
                        </div>
                        <div class="col">
                          <label>Arriere</label>
                          <div id="bloodhound">
                            <input class="form-control" type="text" >
                          </div>
                        </div>
                        <div class="col">
                          <label>Scolarite</label>
                          <div id="bloodhound">
                            <input class="form-control" type="text" >
                          </div>
                        </div>
                      </div>
                    </div>

                  </div><br>
                  <div class="row">
                    <div class="col-5">
                      <div class="form-group row">
                        <div class="col">
                          <label>Mode de paiement</label>
                          <div >
                            <select class="form-select form-select-md mb-3" aria-label=".form-select-lg example">
                              <option selected>Mode</option>
                              <option value="1">Espece</option>
                              <option value="2">Cheque</option>
                            </select>                         
                          </div>
                        </div>
                
                        <div class="col">
                          {{-- <label style="visibility: hidden">Mode de paiement</label> --}}
                                {{-- <label style="visibility: hidden">Mode de paiement</label>
                          <div >
                            <button class="btn btn-primary" type="submit">Valider</button>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div><br> --}}

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.0/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script> --}}
                {{-- <script>
  $(function(){
    $('form').submit(function(e) {
      e.preventDefault()
      var $form = $(this)
      $.post($form.attr('action'), $form.serialize())
      .done(function(data) {
        $('#html').html(data)
        $('#formulaire').modal('hide')
      })
      .fail(function() {
        alert('ça ne marche pas...')
      })
    })
    $('.modal').on('shown.bs.modal', function(){
      $('input:first').focus()
    })
  })
</script> --}}
            @endsection
