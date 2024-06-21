@extends('layouts.master')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card">
        <div class="card-body">
          <form class="accordion-body">
            <div class="row"> 
              <div class="col-lg-4">
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Nom">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Prenom">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Classe">
                </div>
                <div class="mb-2">
                  <select class="form-control">
                    <option>Type d'eleve</option>
                    <option>Ancien</option>
                    <option>Nouveau</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <h5>Donnée classe</h5>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                
              </div>
              <div class="col-lg-4">
                <h5>Donnée eleve</h5>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4">
                <h6>Arriere (initial a payer)</h6>
              </div>
              <div class="col-lg-4">
                <input type="" class="form-control"  placeholder="900">
              </div>
              <div class="col-lg-4">
                <input type="" class="form-control"  placeholder="900">
              </div>
            </div>
            <div class="row my-3">
              <div class="col-lg-8">
                <div class="dropup-center dropup">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Choisir un profil de reduction
                  </button>
                  <ul class="dropdown-menu">
                    <ul class="list-group list-group-horizontal">
                      <li class="list-group-item">An item</li>
                      <li class="list-group-item">A second item</li>
                      <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal">
                      <li class="list-group-item">An item</li>
                      <li class="list-group-item">A second item</li>
                      <li class="list-group-item">A third item</li>
                    </ul>
                  </ul>
                </div>
                
                <!-- Button trigger modal -->
                <button type="button" class="mt-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Creer un nouveau profil de reduction
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                  <select class="form-select">
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
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Aide</button>

                          <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                            <div class="offcanvas-header">
                              <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Offcanvas with body scrolling</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                              <p>Try scrolling the rest of the page to see this option in action.</p>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 justify-content-end d-flex">
                <div class="mr-2">
                  <button type="submit" class="btn btn-primary mb-2">Sauvegarde</button>
                </div>
                <div>
                  <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolli" aria-controls="offcanvasScrolli">Aide</button>

                  <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolli" aria-labelledby="offcanvasScrolliLabel">
                    <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="offcanvasScrolliLabel">Offcanvas with body scrolling</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <p>Try scrolli the rest of the page to see this option in action.</p>
                    </div>
                  </div>
                </div>
                
              </div>
              
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</div>

@endsection