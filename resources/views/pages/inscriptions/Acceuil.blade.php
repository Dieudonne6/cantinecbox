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
                        
                        <button type="button" class="btn btn-danger p-2">Paiement</button>
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
                <div class="table-responsive pt-1 xl-5">
                  <table class="table table-striped project-orders-table">
                    <thead>
                      <tr>
                        <th class="ml-5" _msttexthash="13715" _msthash="167">Matricule</th>
                        <th _msttexthash="185380" _msthash="168">Nom</th>
                        <th _msttexthash="76570" _msthash="169">Prénoms</th>
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
                        <td _msttexthash="702468" _msthash="175">Consectetur adipisicing elit </td>
                        <td _msttexthash="255164" _msthash="176">Beulah Cummings</td>
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
                      <tr>
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
                      </tr>
                    </tbody>
                  </table>
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
                              <div class="form-check mx-9">
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
                        <div class="accordion-body">
                          
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                          Accordion Item #3
                        </button>
                      </h5>
                      <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                          Accordion Item #4
                        </button>
                      </h5>
                      <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                          Accordion Item #5
                        </button>
                      </h5>
                      <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                          Accordion Item #6
                        </button>
                      </h5>
                      <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h5 class="accordion-header">
                        <button class="btn btn-light accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                          Accordion Item #7
                        </button>
                      </h5>
                      <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                      </div>
                    </div>
                  </div>
                  
                </div>
                </div>
                
                  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
                  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
                </div>   
          </div>

      </div>

    </div>

@endsection