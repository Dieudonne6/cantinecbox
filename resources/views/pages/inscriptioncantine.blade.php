@extends('layouts.master')
@section('content')
<div class="main-panel-16">        
  <div class="container">
    
    <div class="row">
      <div class="col-md-8 mx-auto grid-margin stretch-card">

      </div>
      <div class="col-md-8 mx-auto grid-margin stretch-card">
        
        <div class="card">
          <div class="card-body">

            <h4 class="card-title">Nouveau contrat</h4>
            {{-- affichage des erreur et les message de succes --}}
            @if (Session::has('status'))
            <div class="alert alert-success">
                {{ Session::get('status') }}
            </div>
            @endif
            @if (Session::has('errors'))
              <div class="alert alert-danger">
                  {{ Session::get('errors') }}
              </div>
            @endif
            <form method="POST" action="{{url('creercontrat')}}">
              {{csrf_field()}}
              @if(Session::has('id_usercontrat'))
              <?php $id_usercontrat = Session::get('id_usercontrat'); ?>
              <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
              @endif
              <div class="form-group w-100 mb-6">
                @csrf
                <label for="classSelect">Sélectionner la classe</label>
                <select class="js-example-basic-multiple w-100" id="classSelect" name="classes">
                  {{-- <option value="">Sélectionner la classe</option> --}}
                  @foreach ($class as $eleves)
                  <option value="{{$eleves->CODECLAS}}">{{$eleves->CODECLAS}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group w-100">
                <label for="eleveSelect">Sélectionner un élève</label>
                <select id="eleveSelect" class="js-example-basic-multiple w-100" name="matricules">
                  {{-- <option value="">Sélectionner un élève</option> --}}
                </select>
              </div>
              <div class="form-group">
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
                      <input class="typeaheads mater" type="text" readonly name="montant"  id="montant" value="">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="">
                <input type="reset" class="btn btn-secondary" data-bs-dismiss="modal" value="Annuler">
                <button type="submit" class="btn btn-primary">Enregister</button>
              </div>
            </form>
          </div>
          
        </div>
      </div>
  

      @endsection