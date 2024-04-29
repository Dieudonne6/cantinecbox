@extends('layouts.master')
@section('content')



<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      <div class="form-group row">
        <div class="col-3">
        <select class="js-example-basic-single w-100" onchange="window.location.href=this.value">
          <option value="">Sélectionnez une classe</option>
            @foreach ($classe as $classes)
             <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach
        </select>
      </div>
      <div class="col-2">
        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
          Nouveau
        </button>
      </div>
      {{-- <div class="col-2"> 
        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
          Suspendre
        </button>
      </div>
      <div class="col-2"> 
        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#examplePaiement">
          Paiement
        </button>
      </div> --}}
      {{-- <div class="col-3">
        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleInscrire">
          Inscriptions mensuelles
        </button>
      </div> --}}

      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>
                Classes
              </th>
              <th>
                Elève
              </th>
              <th>
                Action
              </th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            @foreach ($filterEleve as $filterEleves)
                <tr class="eleve" data-id="{{ $filterEleves->id }}" data-nom="{{ $filterEleves->NOM }}" data-prenom="{{ $filterEleves->PRENOM }}" data-codeclas="{{ $filterEleves->CODECLAS }}">
                    <td>
                        {{$filterEleves->CODECLAS}}
                    </td>
                    <td>
                        {{$filterEleves->NOM}} {{$filterEleves->PRENOM}}
                    </td>
                    <td>

                      <a href='/paiementcontrat/{{$filterEleves->CODECLAS}}/{{$filterEleves->MATRICULE}}' class='btn btn-primary w-50'>Paiement</a>
                      <a href='/admin/deletecashier/{{$filterEleves->MATRICULE}}' class='btn btn-danger w-50'>Suspendre</a>

                      {{-- <button type="button" class="btn btn-primary w-50" data-bs-toggle="modal" data-bs-target="#examplePaiement">
                        Paiement
                      </button>
                      <button type="button" class="btn btn-danger w-50" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Suspendre
                      </button> --}}
                  </td>
                </tr>
            @endforeach
        </tbody>
        </table>
      </div>

    </div>
{{-- 
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title fs-3" id="exampleModalLabel">Nouveau contrat</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="card">
                <div class="card-body">
                  <p class="card-description" style="color: black">
                    Eleve
                  </p>
                  <div class="form-group">
                    <label>Single select box using select 2</label>
                    <select class="js-example-basic-single w-100">
                      <option value="AL">Alabama</option>
                      <option value="WY">Wyoming</option>
                      <option value="AM">America</option>
                      <option value="CA">Canada</option>
                      <option value="RU">Russia</option>
                    </select>
                  </div>
                  <div class="form-group w-100">
                    <select class="js-example-basic-multiple w-100" multiple="multiple">
                      @foreach ($eleve as $eleves)
                        <option value="{{$eleves->NOM}} {{$eleves->PRENOM}}">{{$eleves->NOM}} {{$eleves->PRENOM}}</option>
                      @endforeach
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <p class="card-description">
                      Info de l'inscriptions
                    </p>
                    <div class="form-group row">
                      <div class="col">
                        <label>Date</label>
                        <div id="the-basics">
                          <input class="typeahead w-100" type="date">
                        </div>
                      </div>
                      <div class="col">
                        <label>Montant</label>
                        <div id="bloodhound">
                          <input class="typeahead" type="text" placeholder="0">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-primary">Enregister</button>
            </div>
          </div>
        </div>
      </div> --}}


      {{-- @foreach ($cashiers as $cashier)
          
      <div class="modal fade" id="exampleModalUpdate{{$cashier->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content bg-dark text-white">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Cashier Account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{url('/admin/updatecashier')}}" method="POST">
                @csrf
                  <div class="form-group">
                    <input type="hidden" name="id" value="{{$cashier->id}}">
                    <input class="form-control w-75 mx-auto" type="email" name="email" required value="{{$cashier->email}}">
                  </div>
                  <div class="form-group">
                    <input class="form-control w-75 mx-auto" type="password" name="password" required value="{{$cashier->password}}" minlength="4">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="saveAccount" class="btn btn-primary">Update Account</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach --}}






    @foreach ($filterEleve as  $filterEleves)

      <div class="modal fade" id="examplePaiement{{$filterEleves->MATRICULE}}" tabindex="-1" aria-labelledby="examplePaiementLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title fs-3" id="exampleModalLabel">Paiement</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="{{url('/paiementcontrat')}}" method="POST">
                @csrf
                <div class="col-md-12 mx-auto grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Infos paiement</h4>
                      <div class="form-group row">
                        <div class="col">
                          <label>Date</label>
                          <div id="the-basics">
                            <input class="typeaheads" type="date" id="date" name="date" value="{{ date('Y-m-d') }}">

                          </div>
                        </div>
                        <div class="col">
                          <label>Montant Mensuel</label>
                          <div id="bloodhound">
                            <input class="typeaheads" type="text" placeholder="60090">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mx-auto grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Mois a payer</h4>
                      <form>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                         
                             
                           
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input">
                                 Juillet
                                </label>
                              </div>
 
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mx-auto grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Coût total</h4>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Montant Total</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" placeholder="0">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Enregister</button>
            </div>

          </form>

          </div>
        </div>
      </div>

      @endforeach





      {{-- <div class="modal fade" id="exampleInscrire" tabindex="-1" aria-labelledby="exampleInscrireLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title fs-3" id="exampleModalLabel">Inscription Mensuelle</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Mois a payer</h4>
                  <form>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                             Janvier
                            </label>
                          </div>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" checked>
                             Février
                            </label>
                          </div>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                             Mars
                            </label>
                          </div>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" checked>
                              Avril
                            </label>
                          </div>
                         
                       
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                             Juillet
                            </label>
                          </div>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                             Août
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-primary">Enregister</button>
            </div>
          </div>
        </div>
      </div> --}}
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@endsection
