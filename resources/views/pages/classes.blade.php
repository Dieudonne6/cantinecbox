@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      @if(Session::has('status'))
        <div id="statusAlert" class="alert alert-succes btn-primary">
          {{ Session::get('status')}}
        </div>
      @endif
            @if(Session::has('erreur'))
        <div id="statusAlert" class="alert alert-danger btn-primary">
          {{ Session::get('erreur')}}
        </div>
      @endif
      <div class="form-group row">
        <div class="col-3">
          <select class="js-example-basic-single w-100" onchange="window.location.href=this.value">
            <option value="">Sélectionnez une classe</option>
            @foreach ($classe as $classes)
              <option value="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-3">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nouveau Contrat
          </button>
        </div>

        {{-- <div class="col-3">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleInscrire">
            Inscriptions mensuelles
          </button>
        </div> --}}

      </div>
      <div class="table-responsive mb-4">
        <table id="myTable">
          <thead>
            <tr>
              <th>
                Date
              </th>
             
              <th>Elève </th>

              <th>
                Action
              </th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            @foreach ($eleve as $index => $eleves)
                <tr class="eleve" data-id="{{ $eleves->id }}" data-nom="{{ $eleves->NOM }}" data-prenom="{{ $eleves->PRENOM }}" data-codeclas="{{ $eleves->CODECLAS }}">
                    <td>
                        {{$eleves->CODECLAS}}
                    </td>
                    <td>
                        {{$eleves->NOM}} {{$eleves->PRENOM}}
                    </td>
                    <td>
                      <a href='/paiementcontrat/{{$eleves->CODECLAS}}/{{$eleves->MATRICULE}}' class='btn btn-primary w-40'>Paiement</a>                      
                      <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="post">
                        @csrf
                        @method('DELETE')
                        {{-- <a href='/admin/deletecashier/{{$eleves->MATRICULE}}' class='btn btn-danger w-50'>Suspendre</a> --}}
                        <input type="submit" class="btn btn-danger w-40" value="Suspendre" style="margin-left: 9.5rem; margin-top: -3.8rem">
                    </form>  
                    </td>
                </tr>
            @endforeach

        </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-3" id="exampleModalLabel">Nouveau contrat</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{url('creercontrat')}}">
      <div class="modal-body">
        <div class="card">
            {{csrf_field()}}
            @if(Session::has('id_usercontrat'))
              <?php $id_usercontrat = Session::get('id_usercontrat'); ?>
              <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
            @endif
          <div class="card-body">
            <div class="form-group">
              <label> Sélectionnez la section </label>
              <select class="form-select form-control w-100" id="selectClasses" name="classes">
                <option value="Maternelle">Maternelle</option>
                <option value="Primaire">Primaire</option>
              </select>
            </div>
           
              <div class="form-group w-100">
                <label> Sélectionnez l'élève </label>
                @csrf
                <select class="js-example-basic-multiple w-100" multiple="multiple" name="matricules[]">
                  @foreach ($elev as $eleves)
                    <option value="{{$eleves->MATRICULE}}">{{$eleves->NOM}} {{$eleves->PRENOM}}</option>
                  @endforeach
                </select>
              </div>

            <div class="form-group">
              <div class="form-group row">
                <div class="col">
                  <label>Date</label>
                  <div id="the-basics">
                    <input class="typeaheads" type="date" id="date" name="date"
                        value="{{ date('Y-m-d') }}">
                  </div>
                </div>
                <div class="col">
                  <label>Montant</label>
                  <div id="bloodhound">
                    <input class="typeaheads mater" type="text" readonly name="montant" value="{{$fraiscontrats->fraisinscription_mat}}">
                    <input class="typeaheads d-none prima" type="text" readonly name="montant" value="{{$fraiscontrats->fraisinscription_paramcontrat}}">
                  </div>
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
<div class="modal fade" id="examplePaiement" tabindex="-1" aria-labelledby="examplePaiementLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-3" id="exampleModalLabel">Paiement</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="col-md-12 mx-auto grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Infos paiement</h4>
                <div class="form-group row">
                  <div class="col">
                    <label>Date</label>
                    <div id="the-basics">
                      <input class="typeaheads" type="date" id="date" name="date"
                          value="{{ date('Y-m-d') }}">
                    </div>
                  </div>
                  <div class="col">
                    <label>Montant Mensuel</label>
                    <div id="bloodhound">
                      <input class="typeahead" type="text" placeholder="60090">
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
        <button type="button" class="btn btn-primary">Enregister</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleInscrire" tabindex="-1" aria-labelledby="exampleInscrireLabel" aria-hidden="true">
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
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}


