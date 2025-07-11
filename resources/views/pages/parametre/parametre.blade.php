@extends('layouts.master')
@section('content')

<div class="card">
  <div>
    <style>
        .btn-arrow {
            position: absolute;
            top: 0px;
            /* Ajustez la position verticale */
            left: 0px;
            /* Positionnez à gauche */
            background-color: transparent !important;
            border: 1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            /* Taille de l'icône */
            color: #b51818 !important;
            /* Couleur de l'icône */
        }

        .btn-arrow:hover {
            color: #b700ff !important;
            /* Couleur au survol */
        }
    </style>
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
        <i class="fas fa-arrow-left"></i> Retour
    </button>   
    <br>
    <br>                                   
</div>
    <div class="card-body">

        <div class="tab-content col-md-10 mx-auto " id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-cantine" role="tabpanel" aria-labelledby="nav-cantine-tab">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                      <button class="nav-link active" id="nav-identification-tab" data-bs-toggle="tab" data-bs-target="#nav-identification" type="button" role="tab" aria-controls="nav-identification" aria-selected="true">Identification</button>
                      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-frais&année" type="button" role="tab" aria-controls="nav-frais&année" aria-selected="false">Frais & Année</button>
                      <button class="nav-link" id="nav-connexionBD-tab" data-bs-toggle="tab" data-bs-target="#nav-connexionBD" type="button" role="tab" aria-controls="nav-connexionBD" aria-selected="false">ConnexionBD</button>
                      <button class="nav-link" id="nav-facture-tab" data-bs-toggle="tab" data-bs-target="#nav-facture" type="button" role="tab" aria-controls="nav-facture" aria-selected="false">Facture</button>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent1">
                    <div class="tab-pane fade show active" id="nav-identification" role="tabpanel" aria-labelledby="nav-identification-tab">
                        <div class="row">
                            <div class="col-md-10 mx-auto grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body">
                                  @if(Session::has('status'))
                                  <div id="statusAlert" class="alert alert-succes btn-primary">
                                  {{ Session::get('status')}}
                                  </div>
                                  @endif
                                  <h4 class="card-title">Enregistrement d'un utilisateur</h4>
                                
                                  <form action="{{ url('/enregistreruser') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                      <label for="exampleInputUsername1">Nom d'utilisateur</label>
                                      <input type="text" class="form-control" name="login" id="exampleInputUsername1" placeholder="Nom d'utilisateur">
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputEmail1">Nom</label>
                                      <input type="text" class="form-control" name="nom" id="exampleInputEmail1" placeholder="Nom">
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputPassword1">Prenom</label>
                                      <input type="text" class="form-control" name="prenom" id="exampleInputPassword1" placeholder="Prenom">
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputConfirmPassword1">Mot de passe</label>
                                      <input type="password" class="form-control" name="password" id="exampleInputConfirmPassword1" placeholder="Mot de passe">
                                    </div>
                                  
                                    <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                    <button class="btn btn-light">Annuler</button>
                                  </form>
                                </div>
                              </div>
                              
                            </div>
                          </div>
                    </div>

                    <div class="tab-pane fade" id="nav-frais&année" role="tabpanel" aria-labelledby="nav-frais&année-tab">
                        <div class="content-wrapper">
                            @if(Session::has('status'))
                              <div id="statusAlert" class="alert alert-succes btn-primary">
                              {{ Session::get('status')}}
                              </div>
                            @endif
                            @if($param)
                              <form action="{{url('modifierfrais/'.$param->id_paramcontrat)}}" method="POST">
                                {{csrf_field()}}
                                @method('PUT')
                                <input type="hidden" value="{{$param->id_paramcontrat}}" name="id_paramcontrat">
                                <div class="row">
                                  <div class="col-md-12 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Année académique </h4>
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-12">
                                            <input type="text" name="anneencours_paramcontrat" class="form-control" id="exampleInputUserannée"  value="{{$param->anneencours_paramcontrat}}" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                        
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais d'inscriptions Primaire</h4>      
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="fraisinscription_paramcontrat" value="{{$param->fraisinscription_paramcontrat}}" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais d'inscriptions Maternel</h4>      
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="fraisinscription_mat" value="{{$param->fraisinscription_mat}}" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais mensuel Maternel</h4>      
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="fraisinscription2_paramcontrat" value="{{$param->fraisinscription2_paramcontrat}}"  class="form-control" id="exampleInputUserannée"  _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais mensuel Primaire</h4>      
                                          <div class="form-group row">
                                            <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                            <div class="col-sm-9">
                                              <input type="text" name="coutmensuel_paramcontrat" class="form-control" value="{{$param->coutmensuel_paramcontrat}}"  id="exampleInputUserannée" _mstplaceholder="117572" _msthash="115">
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                  <button type="submit" class="btn btn-primary w-100" _msttexthash="98280" _msthash="118">Modifier</button>
                                </div>
                              </form>
                            @else
                              <form action="{{url('nouveaufrais')}}" method="POST">
                                {{csrf_field()}}
                                <div class="row">
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Année académique</h4>
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="anneencours_paramcontrat" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                        
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais d'inscriptions</h4>      
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="fraisinscription_paramcontrat" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                        
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais mensuel Maternel</h4>      
                                        <div class="form-group row">
                                          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                          <div class="col-sm-9">
                                            <input type="text" name="fraisinscription2_paramcontrat" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                      <div class="card-body">
                                        <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais mensuel Primaire</h4>      
                                          <div class="form-group row">
                                            <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                                            <div class="col-sm-9">
                                              <input type="text" name="coutmensuel_paramcontrat" class="form-control" id="exampleInputUserannée" placeholder="2024" _mstplaceholder="117572" _msthash="115">
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                  <button type="submit" class="btn btn-primary w-100" _msttexthash="98280" _msthash="118">Enregistrer</button>
                                </div>
                              </form>
                            @endif
                          </div>
                    </div>

                    <div class="tab-pane fade" id="nav-connexionBD" role="tabpanel" aria-labelledby="nav-connexionBD-tab">
                        <div class="col-md grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body">  
                                @if(Session::has('status'))
                                <div  id="statusAlert" class="alert alert-succes btn-primary mb-4">
                                {{ Session::get('status')}}
                                </div>
                                @endif
                                <h4 class="mb-5">Connexion à la base de donnée</h4>
                                <form action="{{url('connexion')}}" method="POST">
                                  {{csrf_field()}}
                                  <div class="form-group row">
                                    <label for="exampleInputServeur" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="108">Serveur</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="exampleInputServeur" name="nameserveur" placeholder="localhost" _mstplaceholder="113997" _msthash="109">
                                    </div>
                                  </div>
                          
                                  <div class="form-group row">
                                    <label for="exampleInputDatabase" class="col-sm-3 col-form-label" _msttexthash="564538"  _msthash="110">Base de donnée</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="exampleInputDatabase" name="namebase" placeholder="Nom de la base de donnée" _mstplaceholder="58058" _msthash="111">
                                    </div>
                                  </div>
                          
                                  <div class="form-group row">
                                    <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116">Utilisateur</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="exampleInputUsername" name="user"  placeholder="Utilisateur" _mstplaceholder="117572" _msthash="115">
                                    </div>
                                  </div>
                          
                                  <div class="form-group row">
                                    <label for="exampleInputPassword" class="col-sm-3 col-form-label" _msttexthash="157794" _msthash="114">Mot de passe</label>
                                    <div class="col-sm-9">
                                      <input type="password" class="form-control" id="exampleInputPassword" name="password" placeholder="Mot de passe" _mstplaceholder="117572" _msthash="117">
                                    </div>
                                  </div>
                                  <button type="submit" class="btn btn-primary mr-2" _msttexthash="98280" _msthash="118">Tester</button>
                                </form>
                              </div>
                            </div>
                          </div>
                    </div>

                    <div class="tab-pane fade" id="nav-facture" role="tabpanel" aria-labelledby="nav-facture-tab">
                        <div class="row">
                            <div class="col-md-6 mx-auto grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body">
                                  <div class="form-group d-flex">
                                    <div class="form-check pr-4">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" checked>
                                        EMECEF
                                      </label>
                                    </div>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1">
                                        MCF
                                      </label>
                                    </div>
                                  </div>
                                  @if(Session::has('status'))
                                    <div id="statusAlert" class="alert alert-succes btn-primary">
                                      {{ Session::get('status')}}
                                    </div>
                                  @endif
                                  <form action="{{url('paramsemecef')}}" method="POST"  enctype="multipart/form-data" id="div1" class="d-block">
                                    {{csrf_field()}}
                                    <h4 class="card-title">Informations Emecef</h4>
                      
                                    <div class="form-group">
                                      <label for="exampleInputUsername1">IFU</label>
                                      <input type="text" class="form-control" name="ifu" id="exampleInputUsername1" placeholder="IFU">
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputEmail1">Token</label>
                                      <input type="text" class="form-control" name="token" id="exampleInputEmail1" placeholder="Token">
                                    </div>
                                    <div class="form-group row">
                                      <label for="exampleInputUsername1">Groupe de taxe</label>
                                      <input type="text" class="form-control" name="taxe"  id="exampleInputUsername1" placeholder="Groupe de taxe">
                                    </div>
                                    <div class="form-group row">
                                      <label for="exampleInputUsername1">Type de facture</label>
                                      <input type="text" class="form-control" name="type" id="exampleInputUsername1" placeholder="Type de facture">
                                    </div>
                                    <div class="form-group row">
                                      <label for="exampleInputUsername1">LOGO</label>
                                      <input type="file" class="form-control" name="logo" id="exampleInputUsername1" placeholder="Logo">
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Envoyer</button>
                                    <input type="reset" class="btn btn-light" value="Annuler">
                                  </form>
                                  <form class="forms-sample d-none" id="div2">
                                    <h4 class="card-title">Informations Mcef</h4>
                                    <div class="form-group">
                                      <label for="exampleInputUsername1">IFU</label>
                                      <input type="text" class="form-control" id="exampleInputUsername1" placeholder="IFU">
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                    <button class="btn btn-light">Annuler</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                           
                            {{-- <div class="col-md-6 grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body">
                                 
                                </div>
                              </div>
                            </div> --}}
                          </div>
                    </div>

                  </div>
            </div>

            <div class="tab-pane fade" id="nav-inscriptions" role="tabpanel" aria-labelledby="nav-inscriptions-tab">
              
                <nav>
                    <div class="nav nav-tabs" id="nav-inscriptions" role="tablist">
                      <button class="nav-link active" id="nav-transfert-tab" data-bs-toggle="tab" data-bs-target="#nav-transfert" type="button" role="tab" aria-controls="nav-transfert" aria-selected="true">Transfert</button>
                      <button class="nav-link" id="nav-importer-tab" data-bs-toggle="tab" data-bs-target="#nav-importer" type="button" role="tab" aria-controls="nav-importer" aria-selected="false">Importer</button>
                      <button class="nav-link" id="nav-exporter-tab" data-bs-toggle="tab" data-bs-target="#nav-exporter" type="button" role="tab" aria-controls="nav-exporter" aria-selected="false">Exporter</button>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-transfert" role="tabpanel" aria-labelledby="nav-transfert-tab">
                      <div class="card">

                        <div class="row ">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Transfert</h4>
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
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label>Source</label>
                                                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                                                <option>CE1</option>
                                                                <option>Italy</option>
                                                                <option>Russia</option>
                                                                <option>Britain</option>
                                                            </select>
                                                        </div>
                                                        <div class="col">
                                                          <label>Destination</label>
                                                          <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
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
                    </div>
  
                    <div class="tab-pane fade" id="nav-importer" role="tabpanel" aria-labelledby="nav-importer-tab">
                      <div class="container">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="col-12 grid-margin">
                              <div class="card">
                                <div class="card-body">
                                  <h4 class="card-title">Importation de données</h4>
                                  <div class="form-group">
                                    <h6>Configuration du fichier Excel</h6>
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                      <label class="form-check-label" for="flexRadioDefault1">
                                        Dans le fichier excel les nom et prénoms sont dans des colonnes différentes
                                      </label>
                                    </div>
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                      <label class="form-check-label" for="flexRadioDefault2">
                                        Dans le fichier excel les nom et prénoms sont dans la même colonne
                                      </label>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <button type="button" class="btn btn-primary">Cliquer ici pour ouvrir un modèle de fichier Excel</button>
                                  </div>
                                  <div class="form-group">
                                    <button type="button" class="btn btn-primary">Cliquer ici pour localiser Excel...</button>
                                  </div>
                                  <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">Ecraser le fichier des élèves existants</label>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <button type="button" class="btn btn-primary">Afficher la conversion du fichier</button>
                                  </div>
                                  <div class="form-group">
                                    <button type="button" class="btn btn-primary">Cliquer ici pour afficher les erreurs</button>
                                  </div>
                                  <div class="form-group">
                                    <button type="button" class="btn btn-primary">Importer</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="col-12 grid-margin">
                              <div class="card">
                                <div class="card-body">
                                  <div class="table-responsive">
                                    <table class="table custom-table">
                                      <thead>
                                        <tr>
                                          <th>Matricule</th>
                                          <th>Nom</th>
                                          <th>Prénom</th>
                                          <th>Sexe</th>
                                          <th>Statut</th>
                                          <th>Classes</th>
                                          <th>Date</th>
                                          <th>Lieu</th>
                                          <th>Nevers</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>00000844</td>
                                          <td>ABOGOURIN</td>
                                          <td>Mardiath</td>
                                          <td>Féminin</td>
                                          <td>Ancien</td>
                                          <td>CM2</td>
                                          <td>02/08/2010</td>
                                          <td>Cotonou</td>
                                          <td></td>
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
  
                    <div class="tab-pane fade" id="nav-exporter" role="tabpanel" aria-labelledby="nav-exporter-tab">
                      <div class="container">
                        <div class="row">
                          <div class="col-lg-10 card">
                            <div class="card-body">
                              <h4 class="card-title">Transfert de données</h4>
                            <div class="row p-3">
                              <div class="col-lg-3">
                                <h6>Diriger vers</h6>
                                <select  class="form-select">
                                  <option> Fichier texte </option>
                                  <option> Excel </option>
                                  <option>  XML </option>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <h6>Repertoire de destination</h6>
                                <input type="file" class="form-control" placeholder="\Tempo12">
                              </div>
                              <div class="col-lg-3">
                                <h6>Nom du fichier</h6>
                                <input type="text" class="form-control" placeholder="eleve">
                              </div>
                              <div class="col-lg-2 mt-4">
                                <input type="submit" class="btn btn-primary" value="Demarer">
                              </div>
                            </div>
                            <div class="table-responsive">
                              <table id="" class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>N ORDRE</th>
                                    <th>Matricule</th> 
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Classe</th>
                                    <th>Sexe</th>
                                    <th> Statut</th>
                                    <th>Datenais</th>
                                    <th>Lieunais</th>
                                    <th>Nevers</th>
                                    <th>Moy1</th>
                                    <th>Moy2</th>
                                    <th>Moy3</th>
                                    <th>Moyan</th>
                                    <th>Rang1</th>
                                    <th>Rang2</th>
                                    <th>Rang3</th>
                                    <th>Rangan</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>4</td>
                                    <td>00004</td>
                                    <td>KOUNOU</td>
                                    <td>Orane</td>
                                    <td>CE2B</td>
                                    <td>Feminin</td>
                                    <td><input type="checkbox" class=""></td>
                                    <td>04/09/2021</td>
                                    <td>cotonou</td>
                                    <td>###</td>
                                    <td>4</td>
                                    <td>6</td>
                                    <td>8</td>
                                    <td>10</td>
                                    <td>19</td>
                                    <td>89</td>
                                    <td>10</td>
                                    <td>19</td>
                                  </tr>    
                                  <tr>
                                    <td>4</td>
                                    <td>00004</td>
                                    <td>KOUNOU</td>
                                    <td>Orane</td>
                                    <td>CE2B</td>
                                    <td>Feminin</td>
                                    <td><input type="checkbox" class=""></td>
                                    <td>04/09/2021</td>
                                    <td>cotonou</td>
                                    <td>###</td>
                                    <td>4</td>
                                    <td>6</td>
                                    <td>8</td>
                                    <td>10</td>
                                    <td>19</td>
                                    <td>89</td>
                                    <td>10</td>
                                    <td>19</td>
                                  </tr>      
                                </tbody>  
                              </table>
                            </div>
                          </div>
                          </div>
                          <div class="col-lg-2 card">
                            <h5>Colonnes</h5>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Matricule
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Nom
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Prenom
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                               Sexe
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Statut
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Classe
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Datenais
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Lieunais
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Nevers
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Moy1
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Moy2
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Moy3
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Moyan
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Rang1
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Rang2
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Rang3
                              </label>
                            </div>
                            <div class="form-check m-0">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked>
                                Rangan
                              </label>
                            </div>
                          </div>
                      
                          
                        </div>
                      </div>
                      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                      
                      <script>
                        $(document).ready(function() {
                            // Map checkbox labels to table column indices
                            const columnMapping = {
                                'Matricule': 1,
                                'Nom': 2,
                                'Prenom': 3,
                                'Classe': 4,
                                'Sexe': 5,
                                'Statut': 6,
                                'Datenais': 7,
                                'Lieunais': 8,
                                'Nevers': 9,
                                'Moy1': 10,
                                'Moy2': 11,
                                'Moy3': 12,
                                'Moyan': 13,
                                'Rang1': 14,
                                'Rang2': 15,
                                'Rang3': 16,
                                'Rangan': 17
                            };
                        
                            // Hide/show columns based on checkbox state
                            $('input[type=checkbox]').change(function() {
                                let columnLabel = $(this).parent().text().trim();
                                let columnIndex = columnMapping[columnLabel] + 1; // Adjust for 0-based index and th
                        
                                if ($(this).is(':checked')) {
                                    $('table tr').each(function() {
                                        $(this).find('td:nth-child(' + columnIndex + '), th:nth-child(' + columnIndex + ')').show();
                                    });
                                } else {
                                    $('table tr').each(function() {
                                        $(this).find('td:nth-child(' + columnIndex + '), th:nth-child(' + columnIndex + ')').hide();
                                    });
                                }
                            });
                        
                            // Trigger change event on page load to apply initial visibility
                            $('input[type=checkbox]').trigger('change');
                        });
                        </script>
                        
                      
                    </div>
  
                  </div>

            </div>

            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...2</div>

            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...3</div>

        </div>

    </div>
</div>


@endsection