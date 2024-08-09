@extends('layouts.master')
@section('content')
<div class="container">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

      @if(Session::has('status'))
      <div id="statusAlert" class="alert alert-success btn-primary">
        {{ Session::get('status')}}
      </div>
    @endif
        <h4 class="card-title">Mise a jour des classes</h4>
        {{-- <div class="row"> --}}
          <a type="button" class="btn btn-primary" href="{{url('/enrclasse')}}">Nouveau</a>
          <a type="button" class="btn btn-primary" href="{{url('/groupe')}}">Groupe</a>
          <button type="button" class="btn btn-secondary">Imprimer</button><br>
          
          {{-- </div> --}}
          
          {{-- <h5 style="text-align: center; color: rgb(188, 64, 64)">Scolarite</h5> --}}
          <div class="table-responsive pt-3">
            
            <table id="myTable" class="table table-bordered">
              <thead>
                <tr>
                  <th>Gpe Peda</th>
                  <th>Libelle</th>
                  <th>Type classe</th>
                  <th>Promotion</th>
                  <th>Cycle</th>
                  <th>Serie</th>
                  <th>Enseignement</th>
                  <th>Effectifs</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($classes as $classe)
                
                <tr>
                  <td>{{ $classe->CODECLAS }}</td>
                  <td>{{ $classe->LIBELCLAS }}</td>
                  <td>{{ $classe->typeclasse_LibelleType }}</td>
                  <td>{{ $classe->CODEPROMO }}</td>
                  <td>{{ $classe->CYCLE }}</td>
                  {{-- <td>{{ ($classe->serie)->LIBELSERIE }}</td> --}}
                  <td>{{ $classe->serie_libelle }}</td>
                  <td>{{ $classe->typeenseigne_type }}</td>
                  <td>{{ $classe->EFFECTIF }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <!-- Button trigger modal -->
                      <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="typcn typcn-th-list btn-icon-append"></i>  
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="">
                        <li><a class="dropdown-item" href="/modifierclasse/{{$classe->CODECLAS}}">Modifier</a></li>
                        <li>
                          <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#exampleModalDelete{{ $classe->CODECLAS }}" >Supprimer</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>

        <!-- Modal bouton supprimer -->
        <div class="modal fade" id="exampleModalDelete{{ $classe->CODECLAS }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette classe ?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ url('/supprimerclass')}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="CODECLAS" value="{{ $classe->CODECLAS }}">
                  <input type="submit" class="btn btn-danger" value="Confirmer">
                </form>  
              </div>
            </div>
          </div>
        </div>
                @endforeach
                
                </tr> 
              </tbody>
            </table>
          </div>
          <br>
          
        </div>
      </div>
    </div>
    
    
    
    <!-- Modal -->
    <div class="modal fade" id="modifgroupe" tabindex="-1" aria-labelledby="modalnouveauLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalnouveauLabel">Groupe</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <div class="form-group row">
              <label for="exampleSelectGender">Choisir le groupe</label>
              <select class="form-control" id="exampleSelectGender">
                <option>Ens. General</option>
                <option>Ens. Maternel</option>
                <option>Ens. Primaire</option>
                <option>Ens. Professionnel</option>
                <option>Ens. Technique</option>
                <option>Standard</option>
              </select>
            </div>
            
            
            
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button> --}}
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
            <button type="button" class="btn btn-primary">Enregistrer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection