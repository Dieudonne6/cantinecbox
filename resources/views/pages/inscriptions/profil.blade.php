@extends('layouts.master')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-lg-12 mx-auto">
      <div class="card">
        <div class="card-body">
          @if (Session::has('status'))
          <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
          </div>
          @endif
          <form  action="{{ url('modifieprofil/'.$eleve->MATRICULE) }}" method="POST">
            @csrf
            @method('PUT')            
            <div class="row"> 
              <div class="col-lg-4">
                <input type="hidden" name="duree" value="{{ $elev->classe->DUREE }}">
                <h5>Changement de profil</h5>
                <div class="mb-2">
                  <input type="" class="form-control" id="nom" placeholder="" value="{{ $eleve->NOM }}" readonly>
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="prenom" placeholder="" value="{{ $eleve->PRENOM}}" readonly>
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="classe" placeholder="" name="classe" value="{{ $eleve->CODECLAS}}" readonly>
                </div>
                <div class="mb-2">
                  <select class="js-example-basic-multiple w-100" id="exampleSelectGender" name="type" readonly>
                    <option value="1" {{ $eleve->STATUTG == 1 ? 'selected' : '' }}>Nouveau</option>
                    <option value="2" {{ $eleve->STATUTG  == 2 ? 'selected' : '' }}>Ancien</option>
                    <option value="3" {{ $eleve->STATUTG  == 3 ? 'selected' : '' }}>Transferer</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-5">
                <h5 style="margin-left:12.5rem;">Données classes</h5>
                <div class="d-flex mb-2">
                  <label class="w-100">Scolarité</label>
                  <input type="" id="classesco" class="form-control" value="{{ $elev->STATUTG == 1 ? $elev->classe->APAYER : $elev->classe->APAYER2 }}" placeholder="" readonly>
                </div>
                
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF1 ?? '' }}</label>
                  <input type="text" id="fraisclasse1" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS1 ?? 0) : ($elev->classe->FRAIS1_A ?? 0) }}" readonly>
                </div>
               
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF2 ?? '' }}</label>
                  <input type="text" id="fraisclasse2" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS2 ?? 0) : ($elev->classe->FRAIS2_A ?? 0) }}" readonly>
                </div>
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF3 ?? '' }}</label>
                  <input type="text" id="fraisclasse3" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS3 ?? 0) : ($elev->classe->FRAIS3_A ?? 0) }}" readonly>
                </div>
                
                <div class="d-flex mb-2">
                  <label class="w-100">{{ $libel->LIBELF4 ?? '' }}</label>
                  <input type="text" id="fraisclasse4" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS4 ?? 0) : ($elev->classe->FRAIS4_A ?? 0) }}" readonly>
                </div>
              </div>
                <div class="col-lg-3">
                  <h5>Donnée eleve</h5>
                  <div class="mb-2">
                    <input id="apayer" type="" name="sco" class="form-control" value="{{ $eleve->APAYER }}" readonly>
                  </div>
                  <div class="mb-2">
                    <input id="frais1" type="" name="frais1" class="form-control"  placeholder="" value="{{ $eleve->FRAIS1}}" readonly>
                  </div>
                  <div class="mb-2">
                    <input id="frais2" type="" name="frais2" class="form-control"  placeholder="" value="{{ $eleve->FRAIS2}}" readonly>
                  </div>
                  <div class="mb-2">
                    <input id="frais3" type="" name="frais3" class="form-control"  placeholder="" value="{{ $eleve->FRAIS3}}" readonly>
                  </div>
                  <div class="mb-2">
                    <input id="frais4" type="" name="frais4" class="form-control"  placeholder="" value="{{ $eleve->FRAIS4}}" readonly>
                  </div>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <h6>Arriere (initial a payer)</h6>
                </div>
                <div class="col-lg-4">
                  <input id="arriereinitial" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE_INITIAL}}" readonly>
                </div>
                <div class="col-lg-4">
                  <input id="arriere" type="" name="arriere" class="form-control"  placeholder="" value="{{$eleve->ARRIERE}}" readonly>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-lg-8">
                  <div class="dropup-center dropup">
                    <select id="class-select" name="reduction" class="js-example-basic-multiple mb-3">
                      <option value="">Sélectionnez une réduction</option>
                      @foreach ($reduction as $reductions)
                        <option value="{{$reductions->CodeReduction}}" data-type="{{$reductions->typereduction}}" data-sco="{{$reductions->Reduction_scolarite}}" data-arrie="{{$reductions->Reduction_arriere}}"
                          data-frais1="{{$reductions->Reduction_frais1}}" data-frais2="{{$reductions->Reduction_frais2}}" data-frais3="{{$reductions->Reduction_frais3}}" data-frais4="{{$reductions->Reduction_frais4}}" data-fixesco="{{$reductions->Reduction_fixe_sco}}" data-fixefrais1="{{$reductions->Reduction_fixe_frais1}}" data-fixefrais2="{{$reductions->Reduction_fixe_frais2}}" data-fixefrais3="{{$reductions->Reduction_fixe_frais3}}" data-fixefrais4="{{$reductions->Reduction_fixe_frais4}}" data-fixearriere="{{$reductions->Reduction_fixe_arriere}}"
                          @if ($reductions->CodeReduction == $eleve->CodeReduction) selected @endif>
                          {{$reductions->LibelleReduction}}
                        </option>
                      @endforeach
                    </select>
                  {{-- <button type="button" class="mt-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Creer un nouveau profil de reduction
                  </button> --}}
                  </div>
                  
                  <!-- Button trigger modal -->
                  
                  
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