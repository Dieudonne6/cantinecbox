@extends('layouts.master')

@section('content')

<div class="card">

    <div class="row ">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="page-title"><h4>Transfert</h4></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Classes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td>00000844</td>
                                                <td>ABOGOURIN</td>
                                                <td>Mardiath</td>
                                                <td>NON</td>
                                                <td>
                                                    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary p-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Transfert</button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de transfert</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Source</label>
                                        <select class="form-select">
                                            <option>CE1</option>
                                            <option>Italy</option>
                                            <option>Russia</option>
                                            <option>Britain</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                      <label>Destination</label>
                                      <select class="form-select">
                                          <option>CM2</option>
                                          <option>Italy</option>
                                          <option>Russia</option>
                                          <option>Britain</option>
                                      </select>
                                  </div>
                                </div>
                                <div class="form-group row">      
                            <div class="col">
                                <label>Nom</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" name="nom" id="nom" value="">
                                </div>
                            </div>
                            <div class="col">
                                <label>Prénom</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" name="prenom" id="prenom" value="">
                                </div>
                            </div>
                                </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                              Changer statut en Ancien
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                              Ne pas changer de statut
                                            </label>
                                          </div>
                                    </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Confirmer le transfert</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler le transfert</button>
        </div>
      </div>
    </div>
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
        </div>
    </div>

</div>

@endsection