@extends('layouts.master')

@section('content')


<div class="main-panel-10">
    <div class="content-wrapper">
        
      {{--  --}}
      <div class="row">          
        <div class="col-12">
          <div class="card mb-6">
            <div class="card-body">
              <h4 class="card-title">Table réduction</h4>
              <div class="row gy-3">
                <div class="demo-inline-spacing">
                  {{-- <a  class="btn btn-primary" href=" {{url('/nouveautypesclasses')}}">Nouveau</a> --}}
                  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Nouveau
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabe" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content calcul-modal">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Fiche de reduction</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body profile-content">
          <div class="row">
            <div class="" id="calcul-one">
              <form class="forms-sample">
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Numero reduction</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Libelle reduction</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur scolarite</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00000000000">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur arriere</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputUsername2" class="col-sm-12 col-form-label mb-0">Mode d'application de la reduction sur les echeancier</label>
                  <div class="col-sm-12 mb-2">
                    <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                      <option>Agir sur les dernier tranches</option>
                      <option>Agir sur les dernier tranches</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                <button class="btn btn-secondary">Fermer</button>
              </form>
            </div>
            <div class="col-lg-4 d-none" id="percentage">
              <h6>Calculateur de pourcentage de reduction</h6>
              <div class="row">
                <div class="col-lg-6">
                  <p>Avant redcution</p>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div>
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                </div>
                <div class="col-lg-6">
                  <p>Apres reduction</p>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div class="mb-2">
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                  <div>
                    <input type="text"  class="form-control" placeholder="133000">
                  </div>
                </div>
                <div class="my-4 col-lg-12">
                  <button type="button" class="btn btn-secondary" id="closecalculate">Fermer le calculateur</button>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="calculs">Afficher calculateur de pourcentage</button>              
            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">


            </div>
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
                    <th class="">Numéro</th>
                    <th>Libellé réduction</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>0</td>
                    <td>Plein Tarif</td>
                    <td>
                        <div class="">
                            <!-- Button trigger modal -->
                            {{-- <a  class="btn btn-primary p-2 btn-sm" href="{{url('/modifiertypesclasses')}}">Modif</a> --}}
                            <button type="button" class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Modifier
                              </button>
                              <!-- Modal -->
                              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content calcul-modal">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier fiche de reduction</h1>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body profile-content">
                                      <div class="row">
                                        <div class="" id="calcul-one">
                                          <form class="forms-sample">
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Numero reduction</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Libelle reduction</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur scolarite</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00000000000">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur arriere</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Reduction accordee sur</label>
                                              <div class="col-sm-4">
                                                <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0,00%">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label for="exampleInputUsername2" class="col-sm-12 col-form-label mb-0">Mode d'application de la reduction sur les echeancier</label>
                                              <div class="col-sm-12 mb-2">
                                                <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                                  <option>Agir sur les dernier tranches</option>
                                                  <option>Agir sur les dernier tranches</option>
                                                </select>
                                              </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                            <button class="btn btn-light">Fermer</button>
                                          </form>
                                        </div>
                                        <div class="col-lg-4 d-none" id="percentage">
                                          <h6>Calculateur de pourcentage de reduction</h6>
                                          <div class="row">
                                            <div class="col-lg-6">
                                              <p>Avant redcution</p>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div>
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                            </div>
                                            <div class="col-lg-6">
                                              <p>Apres reduction</p>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div class="mb-2">
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                              <div>
                                                <input type="text"  class="form-control" placeholder="133000">
                                              </div>
                                            </div>
                                            <div class="my-4 col-lg-12">
                                              <button type="button" class="btn btn-secondary" id="closecalculate">Fermer le calculateur</button>
              
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" id="calculs">Afficher calculateur de pourcentage</button>              
                                        <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">


                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <button class="btn btn-danger p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Supprimer
                                {{-- <i class="typcn typcn-trash btn-icon-append"></i>   --}}
                              </button>
                              <!-- Button trigger modal -->
                              <button class="btn btn-primary p-2 btn-sm dropdown" data-bs-toggle="modal" data-bs-target="#exampleModal3">Détails</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal3" tabindex="-3" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel3">Détails de la Réductions</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="main-panel-10">
          <div class="content-wrapper">
              <div class="card">
                  <div class="card-body">
                      <div class="row">
                          <div class="form-group">
                              <div class="form-group row">
                                  <div class="col">
                                      <label>Réduction accordée sur scolarité</label>
                                      <div id="bloodhound">
                                          <input class="form-control" type="text" placeholder="0,0333"  name="nom_pere" id="nom_pere" value="">
                                      </div>
                                  </div>
                                  <div class="col">
                                      <label>Réduction accordée sur </label>
                                      <div id="bloodhound">
                                          <input class="form-control" type="text" placeholder="0,00" name="nom_mere" id="nom_mere" value="">
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="form-group row">
                                <div class="col">
                                  <label>Réduction accordée sur arrièrée</label>
                                  <div id="bloodhound">
                                    <input class="form-control" type="text" placeholder="0,00" name="numero_de_telephone" id="numero_de_telephone" value="">
                                </div>
                              </div>
                              <div class="col">
                                  <label>Réduction accordée sur</label>
                                  <div id="bloodhound">
                                    <input class="form-control" type="text" placeholder="0,00" name="adresse_email" id="adresse_email" value="">
                                </div>
                              </div>
                              </div>
                          </div>
                          
                          <div class="form-group">
                            <div class="form-group row">
                              <div class="col">
                                <label>Réduction accordée sur arrièrée</label>
                                <div id="bloodhound">
                                  <input class="form-control" type="text" placeholder="0,00" name="numero_de_telephone" id="numero_de_telephone" value="">
                              </div>
                            </div>
                            <div class="col">
                                <label>Réduction accordée sur</label>
                                <div id="bloodhound">
                                  <input class="form-control" type="text" placeholder="0,00" name="adresse_email" id="adresse_email" value="">
                              </div>
                            </div>
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Enregistrer</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
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
@endsection