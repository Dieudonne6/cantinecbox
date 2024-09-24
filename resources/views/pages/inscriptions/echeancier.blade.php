@extends('layouts.master')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Mise a jour echeancier eleve</h4>
          @if (Session::has('status'))
          <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-4" style="margin-top:3rem">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample">
                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputUsername1" class="mr-2">Matricule</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" value="{{ $eleve->MATRICULEX }}" readonly>
                    </div>
                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputEmail1" class="mr-2">Nom</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $eleve->NOM }}" readonly>
                    </div>
                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputPassword1" class="mr-2">Prenom</label>
                      <input type="text" class="form-control" id="exampleInputPassword1" value="{{ $eleve->PRENOM }}" readonly>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
            <div class="d-none">
              <div class="d-flex mb-2">
                <label class="w-100">Scolarité</label>
                <input type="" id="classesco" class="form-control" value="{{ $elev->STATUTG == 1 ? $elev->classe->APAYER : $elev->classe->APAYER2 }}" placeholder="" readonly>
              </div>
              <div class="col-lg-4">
                <input id="arriereinitial" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE_INITIAL}}" readonly>
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
          

            <div class="col-8">
              <div class="card">
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Scolarite</label>
                      <div id="the-basics">
                        <input class="form-control" id="apayer" type="text"  value="{{ $eleve->APAYER }}" readonly>
                      </div>
                    </div>
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Arriere</label>
                      <div id="bloodhound">
                        <input class="form-control" id="arriere"  type="text" value="{{ $eleve->ARRIERE }}" readonly>
                      </div>
                    </div>
                  </div>
          
                  <div class="form-group row">
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Frais 1</label>
                      <div id="the-basics">
                        <input class="form-control" type="text" id="frais1"  value="{{ $eleve->FRAIS1 }}" readonly>
                      </div>
                    </div>
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Frais 2</label>
                      <div id="bloodhound">
                        <input class="form-control" id="frais2" type="text"  value="{{ $eleve->FRAIS2}}" readonly>
                      </div>
                    </div>
                  </div>
          
                  <div class="form-group row">
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Frais 3</label>
                      <div id="the-basics">
                        <input class="form-control" id="frais3" type="text"  value="{{ $eleve->FRAIS3 }}" readonly>
                      </div>
                    </div>
                    <div class="col d-flex align-items-center">
                      <label class="mr-2">Frais 4</label>
                      <div id="bloodhound">
                        <input class="form-control" id="frais4" type="text"  value="{{ $eleve->FRAIS4 }}" readonly>
                      </div>
                    </div>
                  </div>
          
                  <div class="form-group row">
                    <label class="col-12">Modifier le profil de reduction</label>
                    <div class="col">
                      <select id="class-select" name="reduction" class="js-example-basic-multiple mb-3">
                        <option value="">Sélectionnez une réduction</option>
                        @foreach ($reduction as $reductions)
                          <option value="{{$reductions->CodeReduction}}" data-type="{{$reductions->typereduction}}" data-sco="{{$reductions->Reduction_scolarite}}" data-arrie="{{$reductions->Reduction_arriere}}"
                            data-frais1="{{$reductions->Reduction_frais1}}" data-frais2="{{$reductions->Reduction_frais2}}" data-frais3="{{$reductions->Reduction_frais3}}" data-frais4="{{$reductions->Reduction_frais4}}" data-fixesco="{{$reductions->Reduction_fixe_sco}}" data-fixefrais1="{{$reductions->Reduction_fixe_frais1}}" data-fixefrais2="{{$reductions->Reduction_fixe_frais2}}" data-fixefrais3="{{$reductions->Reduction_fixe_frais3}}" data-fixefrais4="{{$reductions->Reduction_fixe_frais4}}" 
                            data-fixearriere="{{$reductions->Reduction_fixe_arriere}}"  @if ($reductions->CodeReduction == $eleve->CodeReduction) selected @endif>
                            {{$reductions->LibelleReduction}}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col">
                      <button type="submit" class="btn btn-primary mr-2">Valider</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </br>

          <div class="row">

            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Debut echeance</label>
                        <div class="col-sm-3">
                          <input type="date" class="form-control"  style="width: 10rem"/>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Periodicite</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" style="width: 3.9rem !important" value="30" />
                          </div>
                        <label class="col-sm-3 col-form-label">Jours</label>
                      </div>
                    </div>

                    <div class="col-3">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Duree</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" style="width: 3.9rem !important" value="30" />
                          </div>
                        <label class="col-sm-5 col-form-label">echeance</label>
                      </div>
                    </div>
                    {{-- <div class="col-2">
                      <div >
                        <input class="form-control" type="text" value="229" style="text-align: center; color:black;" readonly>
                      </div>
                    </div>
                    <div class="col-3">
                      <div >
                        <input class="form-control" type="text" placeholder="numero">
                      </div>
                    </div> --}}
                  </div>
                </div>
              </div>
            </div>

          </div><br>

          <div class="row">

            <div class="col-8">
              <div class="card">
                <div class="card-body">
  
                    {{-- <h5 style="text-align: center; color: rgb(188, 64, 64)">Scolarite</h5> --}}
                    <div class="table-responsive pt-3">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date paie</th>
                                    <th>Scolarite</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>001</td>
                                    <td>21/23/2025</td>
                                    <td>150000</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-primary btn-sm">Modifier</button> --}}
                                        <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                    </td>
                                    {{-- <td rowspan="6">150000</td> --}}
                                </tr>

                                <tr>
                                    <td>002</td>
                                    <td>21/23/2025</td>
                                    <td>150000</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-primary btn-sm">Modifier</button> --}}
                                        <button type="button" class="btn btn-danger btn-sm" >Supprimer</button>
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="3">
                                        Total
                                    </th>
                                    <th >
                                        300000
                                    </th>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <br>
  
                </div>
              </div>
            </div>

            <div class="col-4" style="margin-top:2.5rem">
              <div class="row">
                <div class="col-5">
                  <button type="button" class="btn btn-primary" >Ajouter</button>
                  <button type="button" class="btn btn-secondary" style="margin-top: 10px;">Fermer</button>
                </div>
                <div class="col-5">
                  <button type="button" class="btn btn-primary" style=" width:8rem ;">Imprimer</button>
                  <button type="button" class="btn btn-primary" style="margin-top: 10px; width:8rem;">Regenerer</button>
                </div>
              </div>

            </div>
 
          </div>



        
        </div>
      </div>
    </div>
  </div>


@endsection

{{-- 

<div class="table-responsive pt-3">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>
          #
        </th>
        <th>
          First name
        </th>
        <th>
          Progress
        </th>
        <th>
          Amount
        </th>
        <th>
          Deadline
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          1
        </td>
        <td>
          Herman Beck
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 77.99
        </td>
        <td>
          May 15, 2015
        </td>
      </tr>
      <tr>
        <td>
          2
        </td>
        <td>
          Messsy Adam
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $245.30
        </td>
        <td>
          July 1, 2015
        </td>
      </tr>
      <tr>
        <td>
          3
        </td>
        <td>
          John Richards
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $138.00
        </td>
        <td>
          Apr 12, 2015
        </td>
      </tr>
      <tr>
        <td>
          4
        </td>
        <td>
          Peter Meggik
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 77.99
        </td>
        <td>
          May 15, 2015
        </td>
      </tr>
      <tr>
        <td>
          5
        </td>
        <td>
          Edward
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 160.25
        </td>
        <td>
          May 03, 2015
        </td>
      </tr>
      <tr>
        <td>
          6
        </td>
        <td>
          John Doe
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 123.21
        </td>
        <td>
          April 05, 2015
        </td>
      </tr>
      <tr>
        <td>
          7
        </td>
        <td>
          Henry Tom
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 150.00
        </td>
        <td>
          June 16, 2015
        </td>
      </tr>
    </tbody>
  </table>
</div> --}}