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
        <h4 class="card-title">Modifier les classes</h4>
        <form action="{{url('modifieclasse/'.$typecla->CODECLAS)}}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group row mb-0">

            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="{{ $typecla->CODECLAS }}" name="nomclasse" id="nomclasse"
              placeholder="Nom classe" readonly>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="libclasse"  value="{{$typecla->LIBELCLAS}}"  id="libclasse" placeholder="Libelle">
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Type Classe</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typclasse">
                <option value="">Sélectionnez une classe</option>
                @foreach ($typeclah as $typeclat)
                    <option value="{{ $typeclat->TYPECLASSE }}"
                        @if ($typecla->TYPECLASSE == $typeclat->TYPECLASSE) selected @endif>
                        {{ $typeclat->LibelleType }}
                    </option>
                @endforeach
            </select>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Enseignement</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typeensei">
                <option value="">Sélectionnez un type d'enseignement</option>
                @foreach ($typeenseigne as $typeenseig)
                  <option value="{{$typeenseig->idenseign}}"
                    @if ($typecla->TYPEENSEIG == $typeenseig->idenseign) selected @endif>
                    {{$typeenseig->type}}
                  </option>
                @endforeach
              </select>
            </div>
          </div> 
          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Promotion</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typepromo">
                <option value="">Sélectionnez une promotion</option>
                @foreach ($promo as $promo)
                  <option value="{{$promo->CODEPROMO}}"
                    @if ($typecla->CODEPROMO == $promo->CODEPROMO) selected @endif>
                    {{$promo->LIBELPROMO}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">No d'ordre</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="exampleInputUsername2" minlength="1" maxlength="3" value="{{ $typecla->Niveau }}"   placeholder="No d'ordre" name="numero">
            </div>
          </div>
          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cycle</label>
            <div class="col-sm-9">
                <select  class="js-example-basic-multiple w-100" id="exampleSelectGender" name="cycle">
                  <option value="0" {{ $typecla->CYCLE == 0 ? 'selected' : '' }}>Aucun</option>
                  <option value="1" {{ $typecla->CYCLE == 1 ? 'selected' : '' }}>1er Cycle</option>
                  <option value="2" {{ $typecla->CYCLE == 2 ? 'selected' : '' }}>2eme Cycle</option>
                  <option value="3" {{ $typecla->CYCLE == 3 ? 'selected' : '' }}>3eme Cycle</option>
              </select>
            </div> 
          </div>
          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Serie</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" name="typeserie">
                <option value="">Sélectionnez une série</option>
                @foreach ($serie as $serie)
                  <option value="{{$serie->SERIE}}"
                    @if ($typecla->SERIE == $serie->SERIE) selected @endif>
                    {{$serie->LIBELSERIE}}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-group row mb-0">
            <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cours Jour/Soir</label>
            <div class="col-sm-9">
              <select class="form-control js-example-basic-multiple w-100" id="exampleSelectGender" name="typecours">
                <option value="">Jour</option>
                <option value="Soir">Soir</option>
              </select>
            </div>
          </div>
         
          <div class="form-group row mb-5">
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-danger">Annuler</button>
            </div>
            {{-- <div class="col-auto">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#donnefinanciere">Donne financières (Factures)</button>
            </div> --}}
          </div>
          <br>
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