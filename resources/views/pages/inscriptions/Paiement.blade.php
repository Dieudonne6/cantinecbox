@extends('layouts.master')
@section('content')


<div class="container">
       <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Paiement</h4>
                  {{-- <p class="card-description">
                    A simple suggestion engine
                  </p> --}}
                  <div class="row">
                    <div class="col-6">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input" checked>
                          Envoyer un SMS accuse de reception aux parents au numero
                        </label>
                      </div>
                    </div>
           
                    <div class="col-2">
                      <div >
                        <input class="form-control" type="text" value="229" style="text-align: center; color:black;" readonly>
                      </div>
                    </div>
                    <div class="col-3">
                      <div >
                        <input class="form-control" type="text" placeholder="numero">
                      </div>
                    </div>
                  </div><br><br>

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
                          <label style="visibility: hidden">Mode de paiement</label>
                          <div >
                            <button class="btn btn-primary" type="submit">Valider</button>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div><br>

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