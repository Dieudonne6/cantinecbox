@extends('layouts.master')
@section('content')
<div class="container">
  <div class="col-12">
    @if(Session::has('status'))
      <div id="statusAlert" class="alert alert-succes btn-primary">
      {{ Session::get('status')}}
      </div>
    @endif
    @if($errors->any())
    <div id="statusAlert" class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(Session::has('error'))
    <div id="statusAlert" class="alert alert-danger">
      {{ Session::get('error')}}
    </div>
    @endif
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Enregistrement des classes</h4>
        <form  action="{{url('enregistrerclasse')}}" method="POST">
          {{csrf_field()}}
          <div class="form-group row mb-0">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="nomclasse" id="nomclasse"
              placeholder="Nom classe" value="{{ old('nomclasse') }}">
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="{{ old('libclasse') }}" name="libclasse" id="libclasse" placeholder="Libelle">
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Type Classe</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100"  name="typclasse">
                <option value="">Sélectionnez une classe</option>
                @foreach ($typecla as $typecla)
                  <option value="{{$typecla->TYPECLASSE}}" {{ old('typclasse') == $typecla->TYPECLASSE ? 'selected' : '' }}>{{$typecla->LibelleType}}</option>
                @endforeach
                
              </select>
            </div>
          </div>          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Enseignement</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100"  name="typeensei">
                <option value="">Sélectionnez un type d'enseignement</option>
                @foreach ($typeenseigne as $typeenseig)
                  <option value="{{$typeenseig->idenseign}}" {{ old('typeensei') == $typeenseig->idenseign ? 'selected' : '' }}>{{$typeenseig->type}}</option>
                @endforeach
              </select>
            </div>
          </div>         
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Promotion</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100"  name="typepromo">
                <option value="{{ old('typepromo') }}">Sélectionnez une promotion</option>
                @foreach ($promo as $promo)
                  <option value="{{$promo->CODEPROMO}}"  {{ old('typepromo') == $promo->CODEPROMO ? 'selected' : '' }}>{{$promo->LIBELPROMO}}</option>
                @endforeach
                
              </select>
            </div>
          </div>         
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">No d'ordre</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2" value="{{ old('numero') }}" placeholder="No d'ordre" name="numero">
            </div>
          </div>          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cycle</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100"  id="exampleSelectGender" name="cycle">
                <option value="0" {{ old('cycle') == '0' ? 'selected' : '' }}>0</option>
                <option value="1" {{ old('cycle') == '1' ? 'selected' : '' }}>1</option>
                <option value="2" {{ old('cycle') == '2' ? 'selected' : '' }}>2</option>
                <option value="3" {{ old('cycle') == '3' ? 'selected' : '' }}>3</option>
              </select>
            </div> 
          </div>         
          <div class="form-group row">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Serie</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typeserie">
                <option value="">Sélectionnez une série</option>
                @foreach ($serie as $item)
                  <option value="{{ $item->SERIE }}" {{ old('typeserie') == $item->SERIE ? 'selected' : '' }}>
                    {{ $item->LIBELSERIE }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>         
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cours Jour/Soir</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typecours" id="typecours" class="form-control">
                <option value="">Sélectionnez le type de cours</option>
                <option value="jour" {{ old('typecours') == 'jour' ? 'selected' : '' }}>Jour</option>
                <option value="soir" {{ old('typecours') == 'soir' ? 'selected' : '' }}>Soir</option>
             </select>
            </div>
          </div>
          <div class="form-group row mb-5">
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-danger">Annuler</button>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#donnefinanciere">Donne financières (Factures)</button>
            </div>
          </div>
        </form>
        <br>
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