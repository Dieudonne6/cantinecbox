@extends('layouts.master')

@section('content')
    
<div>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col">
                        <div class="card-title">
                            <h4> Configurer les comptes</h4>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Voir la liste des comptes</button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Selection d'un compte</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th>Taux</th>
                          <th>Comptes</th>
                          <th>Libellé</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="text-info"> 28.76% <i class=""></i></td>
                          <td>Jacob</td>
                          <td>Photoshop</td>
                          <td>
                            <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                            </button>
                          </td>
                        </tr>
                        <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-info"> 28.76% <i class=""></i></td>
                            <td>Jacob</td>
                            <td>Photoshop</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                        <tr>
                            <td class="text-info"> 70.43% <i class=""></i></td>
                            <td>Franck</td>
                            <td>Plein Tarif</td>
                            <td>
                              <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sélectionner
                                  {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                            </td>
                          </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>

        </div>
      </div>
    </div>
  </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group">
                        <div class="form-group row">
                            <div class="col">
                                <label>Frais scolarité</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" placeholder=""  name="" id="" value="">
                                </div>
                            </div>
                            <div class="col">
                                <label>Libellé</label>
                                <select class="form-select">
                                    <option>AG │ ACTIVITES GENERALES</option>
                                    <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                    <option>MM │ MATERNELLE CADJEHOUN</option>
                                    <option>PC │ PRIMAIRE CADJEHOUN</option>
                                    <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label>Arrièrés</label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="numero_de_telephone" id="numero_de_telephone" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="form-select">
                                <option>AG │ ACTIVITES GENERALES</option>
                                <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                <option>MM │ MATERNELLE CADJEHOUN</option>
                                <option>PC │ PRIMAIRE CADJEHOUN</option>
                                <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="numero_de_telephone" id="numero_de_telephone" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="form-select">
                                <option>AG │ ACTIVITES GENERALES</option>
                                <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                <option>MM │ MATERNELLE CADJEHOUN</option>
                                <option>PC │ PRIMAIRE CADJEHOUN</option>
                                <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="numero_de_telephone" id="numero_de_telephone" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="form-select">
                                <option>AG │ ACTIVITES GENERALES</option>
                                <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                <option>MM │ MATERNELLE CADJEHOUN</option>
                                <option>PC │ PRIMAIRE CADJEHOUN</option>
                                <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="numero_de_telephone" id="numero_de_telephone" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="form-select">
                                <option>AG │ ACTIVITES GENERALES</option>
                                <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                <option>MM │ MATERNELLE CADJEHOUN</option>
                                <option>PC │ PRIMAIRE CADJEHOUN</option>
                                <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="numero_de_telephone" id="numero_de_telephone" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="form-select">
                                <option>AG │ ACTIVITES GENERALES</option>
                                <option>MF │ MATERNELLE CAMP-GUEZO</option>
                                <option>MM │ MATERNELLE CADJEHOUN</option>
                                <option>PC │ PRIMAIRE CADJEHOUN</option>
                                <option>PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-danger">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    @endsection