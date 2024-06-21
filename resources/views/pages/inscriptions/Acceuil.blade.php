@extends('layouts.master')
@section('content')



  <div class="main-panel-10">
    <div class="content-wrapper">
        
      {{--  --}}
      <div class="row">          
        <div class="col-12">
          <div class="card mb-6">
            <div class="card-body">
              <div class="row gy-3">
                <div class="demo-inline-spacing">
                  <button type="button" class="btn btn-primary p-2">Nouveau</button>
                  <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#formulaire">Paiement</button>
                  <button type="button" class="btn btn-warning p-2">MAJ Paie</button>
                  <button type="button" class="btn btn-info p-2">Echéan.</button>
                  <button type="button" class="btn btn-light p-2" >Cursus</button>
                  <button type="button" class="btn btn-secondary p-2">Imprimer</button>
                  <button class="btn btn-danger p-2 btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="291"> Liste déroulante </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="" _mstvisible="0">
                    <h6 class="dropdown-header">Commandes</h6>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Une autre action</a>
                    <a class="dropdown-item" href="#">Autre chose ici</a>
                  </div>      
                </div>
              </div>
            </div>
          </div>
        </div>       
      </div>

      {{--  --}}
      <div class="row">
        <div class="col-md-8">
                
          <div class="card">
            <div  style="overflow: auto;">
              <table class="table table-bordered table-striped" style="min-width: 800px;">
                <thead>
                  <tr>
                        <th class="ml-5" _msttexthash="13715" _msthash="167">Matricule</th>
                        <th _msttexthash="185380" _msthash="168">Nom</th>
                        {{-- <th _msttexthash="76570" _msthash="169">Prénoms</th> --}}
                        <th _msttexthash="155961" _msthash="170">Classe</th>
                        <th _msttexthash="136253" _msthash="171">Sexe</th>
                        <th _msttexthash="74568" _msthash="172">Red.</th>
                        <th _msttexthash="155961" _msthash="173">Date nai</th>
                        <th _msttexthash="95901" _msthash="01">Lieunais</th>
                  </tr>
                </thead>
                <tbody>
                      <tr>
                        <td _msttexthash="15990" _msthash="174">#D1</td>
                        <td style="width: 15px" !important>Consectetur adipisicing elit </td>
                        {{-- <td _msttexthash="255164" _msthash="176">Beulah Cummings</td> --}}
                        <td _msttexthash="117845" _msthash="177">03 janv. 2019</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                            <input type="checkbox" class="form-check-input-center"></td>
                        <td _msttexthash="141805" _msthash="179">1,3 millier</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-secondary p-2 btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Modifier </font>
                                <i class="typcn typcn-edit btn-icon-append"></i>                          
                            </button>
                            <button type="button" class="btn btn-success p-2 btn-sm btn-icon-suprim mr-3">
                              <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Supprimer </font>
                              <i class="typcn typcn-edit btn-icon-append"></i>                          
                          </button>
                          <button type="button" class="btn btn-dark p-2 btn-sm btn-icon-profil mr-3">
                            <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Profil </font>
                            <i class="typcn typcn-edit btn-icon-append"></i>                          
                        </button>
                          </div>
                        </td>
                      </tr>
                      {{-- <tr>
                        <td _msttexthash="16107" _msthash="182">#D2</td>
                        <td _msttexthash="1387555" _msthash="183">Corrélation silo des ressources</td>
                        <td _msttexthash="253994" _msthash="184">Mitchel Dunford</td>
                        <td _msttexthash="173615" _msthash="185">09 octobre 2019</td>
                        <td _msttexthash="27586" _msthash="186">3233 $</td>
                        <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                            <input type="checkbox" class="form-check-input-center"></td>
                        <td _msttexthash="120575" _msthash="188">5,4 milles</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-secondary p-2 btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Modifier </font>
                                <i class="typcn typcn-edit btn-icon-append"></i>                          
                            </button>
                            <button type="button" class="btn btn-success p-2 btn-sm btn-icon-suprim mr-3">
                              <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Supprimer </font>
                              <i class="typcn typcn-edit btn-icon-append"></i>                          
                          </button>
                          <button type="button" class="btn btn-dark p-2 btn-sm btn-icon-profil mr-3">
                            <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Profil </font>
                            <i class="typcn typcn-edit btn-icon-append"></i>                          
                        </button>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td _msttexthash="16224" _msthash="190">#D3</td>
                        <td _msttexthash="845338" _msthash="191">capital social compassion social</td>
                        <td _msttexthash="148486" _msthash="192">Pei Canaday</td>
                        <td _msttexthash="108667" _msthash="193">18 juin 2019</td>
                        <td _msttexthash="27287" _msthash="194">4311 $</td>
                        <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                            <input type="checkbox" class="form-check-input-center"></td>
                        <td _msttexthash="119951" _msthash="196">2,1 milles</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-secondary p-2 btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Modifier </font>
                                <i class="typcn typcn-edit btn-icon-append"></i>                          
                            </button>
                            <button type="button" class="btn btn-success p-2 btn-sm btn-icon-suprim mr-3">
                              <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Supprimer </font>
                              <i class="typcn typcn-edit btn-icon-append"></i>                          
                          </button>
                          <button type="button" class="btn btn-dark p-2 btn-sm btn-icon-profil mr-3">
                            <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Profil </font>
                            <i class="typcn typcn-edit btn-icon-append"></i>                          
                        </button>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td _msttexthash="16341" _msthash="198">#D4</td>
                        <td _msttexthash="1348893" _msthash="199">Donner aux communautés les moyens</td>
                        <td _msttexthash="289055" _msthash="200">Gaynell Sharpton</td>
                        <td _msttexthash="108056" _msthash="201">23 mars 2019</td>
                        <td _msttexthash="28587" _msthash="202">7743 $</td>
                        <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                            <input type="checkbox" class="form-check-input-center"></td>
                        <td _msttexthash="120653" _msthash="204">2,7 milles</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-secondary p-2 btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Modifier </font>
                                <i class="typcn typcn-edit btn-icon-append"></i>                          
                            </button>
                            <button type="button" class="btn btn-success p-2 btn-sm btn-icon-suprim mr-3">
                              <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Supprimer </font>
                              <i class="typcn typcn-edit btn-icon-append"></i>                          
                          </button>
                          <button type="button" class="btn btn-dark p-2 btn-sm btn-icon-profil mr-3">
                            <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Profil </font>
                            <i class="typcn typcn-edit btn-icon-append"></i>                          
                        </button>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td _msttexthash="16458" _msthash="206">#D5</td>
                        <td _msttexthash="582101" _msthash="207"> Efficace ciblé&nbsp;; mobiliser </td>
                        <td _msttexthash="230282" _msthash="208">Audrie Midyett</td>
                        <td _msttexthash="128115" _msthash="209">22 août 2019</td>
                        <td _msttexthash="28197" _msthash="210">2455 $</td>
                        <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                            <input type="checkbox" class="form-check-input-center"></td>
                        <td _msttexthash="141687" _msthash="212">1,2 millier</td>
                        <td _msttexthash="28028" _msthash="178">5235 $</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-secondary p-2 btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Modifier </font>
                                <i class="typcn typcn-edit btn-icon-append"></i>                          
                            </button>
                            <button type="button" class="btn btn-success p-2 btn-sm btn-icon-suprim mr-3">
                              <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Supprimer </font>
                              <i class="typcn typcn-edit btn-icon-append"></i>                          
                          </button>
                          <button type="button" class="btn btn-dark p-2 btn-sm btn-icon-profil mr-3">
                            <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Profil </font>
                            <i class="typcn typcn-edit btn-icon-append"></i>                          
                        </button>
                          </div>
                        </td>
                      </tr> --}}
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="container mt-2">
                  <form>
                    <div class="mb-3">
                      <input type="text" class="form-control" id="lastName" placeholder="Entrez le nom">
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-5">
                <div class="container mt-2">
                  <form>
                    <div class="mb-3">
                      <input type="text" class="form-control" id="firstName" placeholder="Entrez le prénom">
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-2">
                <div class="container mt-2">
                  <form>
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                  </form>
                </div>
              </div>
            </div>
            
          </div>

        </div>

        {{--  --}}
        <div class="col-md-3">
          <div class="card">
            <div class="accordion accordion-flush" id="accordionFlushExample">
              <div class="accordion-item">
                <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                          Informations généraless
                        </button>
                </h5>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <form class="accordion-body">
                          
                            <div class="mb-2">
                              <input type="" class="form-control" id="date" placeholder="Date de Naissance">
                            </div>
                            <div class="mb-2">
                              <input type="" class="form-control" id="lieu" placeholder="Lieu de Naissance">
                            </div>
                            <div class="mb-2">
                              <label for="exampleDropdownFormDate" class="form-label p-2">Sexe</label>
                              <select class="form-auto">
                                <option _msttexthash="42861" _msthash="226">Masculin</option>
                                <option _msttexthash="73437" _msthash="227">Féminin</option>
                              </select>
                            </div>
                            <div class="mb-2">
                              <label for="exampleDropdownFormPassword2" class="form-label">Types élèves</label>
                              <select class="form-auto">
                                <option _msttexthash="42861" _msthash="226">Ancien</option>
                                <option _msttexthash="73437" _msthash="227">Nouveau</option>
                              </select>
                            </div>
                            <div class="mb-2">
                              <input type="" class="form-control" id="date" placeholder="Date d'inscription">
                            </div>
                            <div class="mb-2 p-2">
                              <label for="exampleDropdownFormPassword2" class="form-label">Apte</label>
                              <select class="form-auto">
                                <option >Oui</option>
                                <option >Non</option>
                              </select>
                            </div>
                            <div class="mb-2">
                              <div class="form-check-center mx-9">
                                <input type="checkbox" class="form-check-input mx-9" id="dropdownCheck2">
                                <label class="form-check-label" for="dropdownCheck2">
                                  Statut Redoublant
                                </label>
                              </div>
                            </div>                           
                          </form>                        
                </div>
              </div>

              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                          Détail des notes
                        </button>
                      </h5>
                      <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <form class="accordion-body">
                          <div class="table-responsive" style="height: 100px; overflow: auto;">
                            <table class="table table-bordered table-striped" style="min-width: 800px;">
                              <thead>
                                <tr>
                                  <th scope="col">Matière</th>
                                  <th scope="col">Mi</th>
                                  <th scope="col">Dev1</th>
                                  <th scope="col">Dev2</th>
                                  <th scope="col">Dev3</th>
                                  <th scope="col">Test</th>
                                  <th scope="col">Ms</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1</td>
                                  <td>Mark</td>
                                  <td>Otto</td>
                                  <td>@mdo</td>
                                  <td>Jacob</td>
                                  <td>Thornton</td>
                                  <td>@fat</td>
                                </tr>
                                <tr>
                                  <td>2</td>
                                  <td>Jacob</td>
                                  <td>Thornton</td>
                                  <td>@fat</td>
                                  <td>Mark</td>
                                  <td>Otto</td>
                                  <td>@mdo</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                           
                          <div class="row">
                            <div class="mb-2">
                              <label for="exampleDropdownForm">Moy.1</label>
                              <input type="" class="form-control" id="moy1" >
                            </div>
                            <div class="mb-2">
                              <label for="exampleDropdownForm">Moy.2</label>
                              <input type="" class="form-control" id="moy2">
                            </div>
                            <div class="mb-2">
                              <label for="exampleDropdownForm">Moy.3</label>
                              <input type="" class="form-control" id="moy3">
                            </div>
                          </div>

                        </form>
                      </div>
              </div>
              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                          Détails des paiements
                        </button>
                      </h5>
                      <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">  
                          <div class="form-check-center mx-9">
                              <input type="checkbox" class="form-check-input mx-9" id="dropdownCheck2">
                              <label class="form-check-label" for="dropdownCheck2">
                                Détail des composantes
                              </label>
                          </div> 

                          <div class="table-responsive mt-2" style="height: 100px; overflow: auto;">
                              <table class="table table-bordered " style="min-width: 10px;">
                                <thead>
                                  <tr>
                                    <th>n°Recu</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>130</td>
                                    <td>05/06/2024</td>
                                    <td>190 000</td>
                                  </tr>
                                  <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td> 
                                  </tr>
                                </tbody>
                              </table>
                          </div>

                          <div class="input-group p-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Somme</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Montant (au dollar près)" _mstaria-label="669448" _msthash="174">
                          </div>

                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Reste à payer</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Montant (au dollar près)" _mstaria-label="669448" _msthash="174">
                          </div>

                          <button type="button" class="btn btn-outline-danger btn-icon-text p-2">
                            <i class="typcn typcn-upload btn-icon-prepend"></i><font> Imprimer récapitulatif des paiements </font>
                          </button>
                          
                          <div class="card-body">
                            <h4 class="card-title" _msttexthash="323960" _msthash="106">Réduction Montants dus</h4>
                            <h8><form class="forms-sample">
                              <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-6 p-2 col-form-label">Scolarité</label>
                                <div class="col-sm-6">
                                  <input type="num" class="form-control" id="exampleInputUsername2" >
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputEmail2" class="col-sm-6 col-form-label">Arrièré</label>
                                <div class="col-sm-6">
                                  <input type="num" class="form-control" id="exampleInputEmail2" >
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-6 col-form-label">Frais 1</label>
                                <div class="col-sm-6">
                                  <input type="num" class="form-control" id="exampleInputMobile">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-6 col-form-label">Frais 2</label>
                                <div class="col-sm-6">
                                  <input type="num" class="form-control" id="exampleInputPassword2">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-6 col-form-label">Frais 3</label>
                                <div class="col-sm-6">
                                  <input type="num" class="form-control" id="exampleInputConfirmPassword2">
                                </div>
                              </div>
                            </form></h8>
                          </div>

                        </div>
                      </div>
              </div>
              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                          Informations financières
                        </button>
                      </h5>
                      <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                          <table class="table table-responsive text-center p-2">
                            <thead>
                              <tr>
                                <th scope="col">Scolarités perçus <br> le 23/05/24</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Arrièrés perçus <br> le 23/05/24</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Total</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Total recettes <br> à ce jour</th>
                                <th scope="col">57 575 500</th>
                              </tr>
                              <tr>
                                <th scope="col">Versé à la banque</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Recettes attendues <br> ce jour</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Recettes attendues <br> cette semaine</th>
                                <th scope="col">0</th>
                              </tr>
                              <tr>
                                <th scope="col">Recettes attendues <br> ce mois</th>
                                <th scope="col">0</th>
                              </tr>
                            </thead>
                           
                          </table>
                        </div>
                      </div>
              </div>
              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                          Emploi du temps
                        </button>
                      </h5>
                      <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                          <table class="table table-responsive">
                            <thead>
                              <tr>
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                              </tr>
                              <tr>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                              </tr>
                              <tr>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
              </div>
              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                          Position Enseignants
                        </button>
                      </h5>
                      <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                          <table class="table table-responsive">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                              </tr>
                              <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                              </tr>
                              <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
              </div>
              <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                          Filtrages
                        </button>
                      </h5>
                      <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">

                              <div class="container">
                                  <form>
                                      <div class="form-row">
                                          <div class="form-group col-md-6">
                                              <label for="nom">Nom</label>
                                              <input type="text" class="form-control" id="nom" placeholder="Nom de l'élève">
                                          </div>
                                          <div class="form-group col-md-6">
                                              <label for="classe">Classe</label>
                                              <select id="classe" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Classe 1</option>
                                                  <option>Classe 2</option>
                                                  <option>Classe 3</option>
                                                  <!-- Ajoutez d'autres classes selon vos besoins -->
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-row">
                                          <div class="form-group col-md-4">
                                              <label for="sexe">Sexe</label>
                                              <select id="sexe" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Masculin</option>
                                                  <option>Féminin</option>
                                                  <option>Autre</option>
                                              </select>
                                          </div>
                                          <div class="form-group col-md-4">
                                              <label for="serie">Série</label>
                                              <select id="serie" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Série A</option>
                                                  <option>Série B</option>
                                                  <option>Série C</option>
                                                  <!-- Ajoutez d'autres séries selon vos besoins -->
                                              </select>
                                          </div>
                                          <div class="form-group col-md-4">
                                              <label for="statut">Statut</label>
                                              <select id="statut" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Inscrit</option>
                                                  <option>Non inscrit</option>
                                                  <option>En attente</option>
                                                  <!-- Ajoutez d'autres statuts selon vos besoins -->
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-row">
                                          <div class="form-group col-md-4">
                                              <label for="categorie">Catégorie</label>
                                              <select id="categorie" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Catégorie 1</option>
                                                  <option>Catégorie 2</option>
                                                  <option>Catégorie 3</option>
                                                  <!-- Ajoutez d'autres catégories selon vos besoins -->
                                              </select>
                                          </div>
                                          <div class="form-group col-md-4">
                                              <label for="nationalite">Nationalité</label>
                                              <select id="nationalite" class="form-control">
                                                  <option selected>Choisir...</option>
                                                  <option>Française</option>
                                                  <option>Américaine</option>
                                                  <option>Canadienne</option>
                                                  <option>Autre</option>
                                                  <!-- Ajoutez d'autres nationalités selon vos besoins -->
                                              </select>
                                          </div>
                                          <div class="form-group col-md-4">
                                              <label for="matieres">Matières</label>
                                              <select id="matieres" class="form-control">
                                                  <option>Mathématiques</option>
                                                  <option>Sciences</option>
                                                  <option>Histoire</option>
                                                  <option>Géographie</option>
                                                  <option>Français</option>
                                                  <option>Anglais</option>
                                                  <!-- Ajoutez d'autres matières selon vos besoins -->
                                              </select>
                                          </div>
                                      </div>
                                      <button type="submit" class="btn btn-primary">Filtrer</button>
                                  </form>
                              </div>
                                               
                                                  
                        </div>
                      </div>
              </div>
            </div>                  
          </div>
        </div>                




        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
      </div>  
      
      {{--  --}}
      

    </div>
  </div>

@endsection