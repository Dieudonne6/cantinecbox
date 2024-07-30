@extends('layouts.master')
@section('content')

<div class="main-panel-10">
  <div class="content-wrapper">
    
    {{--  --}}
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
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-secondary">Imprimer</button>  
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
                <tr>
                  <td>0</td>
                  <td>SYSTEME</td>
                  <td>
                    <div class="">
                      <!-- Button trigger modal -->
                      {{-- <a  class="btn btn-primary p-2 btn-sm" href="{{url('/modifiertypesclasses')}}">Modif</a> --}}
                      <button type="button" class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                        Modifier
                      </button>
                      
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal2" tabindex="-2" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel2">Modifier d'un type de classe</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="form-group row">
                                  <div class="col">
                                    <div>
                                      <label><strong>Code groupe</strong></label>
                                      <input type="text" placeholder="6" class="form-control">
                                    </div>
                                  </div>
                                  <div class="col">
                                    <div>
                                      <label><strong>Libellé groupe</strong> (Donner le libellé du groupe à créer. Ex : Examen Blanc)</label>
                                      <input type="text" placeholder="Examen Blanc" class="form-control">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-primary">Enregistrer</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <button class="btn btn-danger p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Supprimer
                        {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  @endsection