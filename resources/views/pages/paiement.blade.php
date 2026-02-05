@extends('layouts.master')
@section('content')

<style>
    /* Responsive styles */
    @media (max-width: 1200px) {
        .col-lg-12 {
            padding: 0.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .col-lg-12 {
            padding: 0.25rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .col-md-8 {
            width: 100%;
            margin: 0.5rem 0;
        }
        
        .card-title {
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .row {
            margin: 0;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem;
        }
        
        .card-title {
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 0.75rem;
        }
        
        .form-check {
            margin-bottom: 0.5rem;
        }
        
        .typeaheads {
            font-size: 0.9rem;
            padding: 0.4rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>

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