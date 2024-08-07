@extends('layouts.master')
@section('content')

<div class="main-panel-10">
  <div class="content-wrapper">
    @if(Session::has('status'))
    <div id="statusAlert" class="alert alert-succes btn-primary">
      {{ Session::get('status')}}
    </div>
    @endif
    <div class="row">          
      <div class="col-12">
        <div class="card mb-6">
          <div class="card-body">
            <h4 class="card-title">Table des types de classes</h4>
            <div class="row gy-3">
              <div class="demo-inline-spacing">
                {{-- <a  class="btn btn-primary" href=" {{url('/nouveautypesclasses')}}">Nouveau</a> --}}
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Nouveau
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Saisie d'un type de classe</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="{{url('/savetypeclasse')}}" method="POST">
                          @csrf
                          <div class="form-group">
                            <div class="form-group">
                              <div class="col mb-4">
                                <div>
                                  <label><strong>Code groupe</strong></label>
                                  <input type="text" name="TYPECLASSE" placeholder="6" class="form-control">
                                </div>
                              </div>
                              <div class="col">
                                <div>
                                  <label><strong>Libellé groupe</strong> (Donner le libellé du groupe à créer. Ex : Examen Blanc)</label>
                                  <input type="text" name="LibelleType" placeholder="Examen Blanc" class="form-control">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                          </div>
                        </form>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <style>
                  table {
                    float: right;
                    width: 10%;
                    border-collapse: collapse;
                    margin: 5px auto;
                  }
                  th, td {
                    /* border: 1px solid #aaa1a1; */
                    padding: 4px;
                    text-align: center;
                  }
                  th {
                    /* background-color: #f2f2f2; */
                  }
                  td.bouton {
                    /* background-color: #ffcccb; */
                  }
                </style>
              </div>
            </div>
          </div>
        </div>
      </div>       
    </div>
    
    {{--  --}}
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="table-responsive" style="height: 300px; overflow: auto;">
            <table class="table table-striped" style="min-width: 600px; font-size: 10px;">
              <thead>
                <tr>
                  <th class="">Code type</th>
                  <th>Libellé</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($allclasse as $allclass)
                <tr>
                  <td>{{$allclass->TYPECLASSE}}</td>
                  <td>{{$allclass->LibelleType}}</td>
                  <td>
                    <div class="">
                      <button type="button" class="btn btn-primary p-2 btn-sm"
                      data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $allclass->idtype; ?>">
                      Modifier
                    </button>
                    <div class="modal fade" id="exampleModal<?php echo $allclass->idtype; ?>" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel2">Modifier un type de classe</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form id="editTypeClasseForm" action="{{ url('/modifiertypesclasses') }}" method="POST">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="idtype" id="edit-id" value="<?php echo $allclass->idtype; ?>">
                              <div class="form-group">
                                <div class="col mb-3">
                                  <label><strong>Code groupe</strong></label>
                                  <input type="text" name="TYPECLASSE" value="<?php echo $allclass->TYPECLASSE; ?>" id="edit-typeclasse" class="form-control">
                                </div>
                                <div class="col">
                                  <label><strong>Libellé groupe</strong> (Donner le libellé du groupe à créer. Ex : Examen Blanc)</label>
                                  <input type="text" name="LibelleType" value="<?php echo $allclass->LibelleType; ?>" id="edit-libelle" class="form-control">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal -->
                    <button type="button" class="btn btn-danger p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModaldelete<?php echo $allclass->idtype; ?>">Supprimer</button> 
                    <div class="modal fade" id="exampleModaldelete<?php echo $allclass->idtype; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer ce contrat ?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <form action="{{ url('/supprimertype')}}" method="post">
                              @csrf
                              @method('DELETE')
                              <input type="hidden" name="idtype" value="<?php echo $allclass->idtype; ?>">
                              <input type="submit" class="btn btn-danger" value="Confirmer">
                            </form>  
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel2">Modifier un type de classe</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="editTypeClasseForm" action="{{ url('/modifiertypesclasses') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="idtype" id="edit-id">
                    <div class="form-group">
                      <div class="col mb-3">
                        <label><strong>Code groupe</strong></label>
                        <input type="text" name="TYPECLASSE" id="edit-typeclasse" class="form-control">
                      </div>
                      <div class="col">
                        <label><strong>Libellé groupe</strong> (Donner le libellé du groupe à créer. Ex : Examen Blanc)</label>
                        <input type="text" name="LibelleType" id="edit-libelle" class="form-control">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Enregistrer</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
              @endforeach
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection