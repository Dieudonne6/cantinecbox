@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Paiement</h4>
      <div class="col-md-8 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Infos paiement</h4>
            <p class="card-description">
            Veuillez remplir les champs
            </p>
            <div class="form-group row">
              <div class="col">
                <label>Date</label>
                <div id="the-basics">
                  <input class="typeaheads" type="date" placeholder="States of USA">
                </div>
              </div>
              <div class="col">
                <label>Montant Mensuel</label>
                <div id="bloodhound">
                  <input class="typeaheads" type="text" placeholder="60090">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Mois a payer</h4>
            <form>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                       Janvier
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                       Février
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                       Mars
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Avril
                      </label>
                    </div>
                   
                 
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                       Juillet
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                       Août
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-8 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Coût total</h4>
            <div class="form-group">
              <div class="col">
                <label>Montant Total</label>
                <div id="bloodhound">
                  <input class="typeahead" type="text" placeholder="0">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
            <button class="btn btn-light">Annuler</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection