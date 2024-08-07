@extends('layouts.master')
@section('content')
<div class="container">
  <div class="col-12">
    @if(Session::has('status'))
    <div id="statusAlert" class="alert alert-succes btn-primary">
      {{ Session::get('status')}}
    </div>
    @endif
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Modifier les classes</h4>
        <form action="{{url('modifieclasse/'.$typecla->CODECLAS)}}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group row">

            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="{{ $typecla->CODECLAS }}" name="nomclasse" id="nomclasse"
              placeholder="Nom classe" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="libclasse"  value="{{$typecla->LIBELCLAS}}"  id="libclasse" placeholder="Libelle">
            </div>
          </div>
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Type Classe</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typclasse">
                <option value="">Sélectionnez une classe</option>
                @foreach ($typeclah as $typeclat)
                  <option value="{{$typeclat->TYPECLASSE}}">{{$typeclat->LibelleType}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Enseignement</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typeensei">
                <option value="">Sélectionnez un type d'enseignement</option>
                @foreach ($typeenseigne as $typeenseig)
                  <option value="{{$typeenseig->idenseign}}">{{$typeenseig->type}}</option>
                @endforeach
              </select>
            </div>
          </div> 
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Promotion</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typepromo">
                <option value="">Sélectionnez une promotion</option>

                @foreach ($promo as $promo)
                  <option value="{{$promo->CODEPROMO}}">{{$promo->LIBELPROMO}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">No d'ordre</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2" value="{{ $typecla->Niveau }}"   placeholder="No d'ordre" name="numero">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cycle</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" id="exampleSelectGender" name="cycle">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div> 
          </div>
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Serie</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typeserie">
                <option value="">Sélectionnez une série</option>
                @foreach ($serie as $serie)
                  <option value="{{$serie->SERIE}}">{{$serie->LIBELSERIE}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cours Jour/Soir</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" id="exampleSelectGender" name="typecours">
                <option value="">Jour</option>
                <option value="Soir">Soir</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Modifier</button>
          <button type="button" class="btn btn-danger">Annuler</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#donnefinanciere">Donne financieres(Factures)</button>
      </form>
      </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="donnefinanciere" tabindex="-1" aria-labelledby="donnefinanciereLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="donnefinanciereLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
          <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2"
              placeholder="Nom classe">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2"
              placeholder="Nom classe">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle">
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button> --}}
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection