@extends('layouts.master')
@section('content')

<style>
    /* Responsive styles */
    @media (max-width: 1200px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 0.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .card-title {
            font-size: 1rem;
        }
        
        .container {
            padding: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 0.25rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .card-title {
            font-size: 0.9rem;
        }
        
        .container {
            padding: 0.25rem;
        }
        
        .form-control {
            font-size: 0.9rem;
            padding: 0.4rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .input-group {
            flex-direction: column;
        }
        
        .input-group-append {
            margin-top: 0.5rem;
        }
        
        .btn-sm {
            width: 100%;
        }
    }
</style>

<div class="col-md-5 container d-flex justify-content-center align-items-center">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Nouveau contrat</h4>
        {{-- <p class="card-description">
          Basic bootstrap input groups
        </p> --}}
        {{-- <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">@</span>
            </div>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-primary text-white">$</span>
            </div>
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
            <div class="input-group-append">
              <span class="input-group-text">.00</span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <div class="input-group-prepend">
              <span class="input-group-text">0.00</span>
            </div>
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
          </div>
        </div> --}}
        <p class="card-description" style="color: black">
          Eleve
        </p>
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="choisir l'eleve" aria-label="Recipient's username">
            <div class="input-group-append">
              <button class="btn btn-sm btn-primary" type="button">Choisir</button>
            </div>
          </div>
        </div>

            <div class="form-group">
              <p class="card-description">
                Info de l'inscriptions
              </p>
              <div class="form-group row">
                <div class="col">
                  <label>Date</label>
                 
                  <div id="the-basics">
                    <input class="typeaheads" type="date" id="date" name="date"
                        value="{{ date('Y-m-d') }}">
                  </div>
                </div>
                <div class="col">
                  <label>Montant</label>
                  <div id="bloodhound">
                    <input class="typeahead" type="text" placeholder="montant">
                  </div>
                </div>
              </div>
            </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary mr-2">Enregister</button>
            {{-- <button class="btn btn-light ml-5">Cancel</button> --}}
        </div>

      </div>
    </div>
  </div>

@endsection