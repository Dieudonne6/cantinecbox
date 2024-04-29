@extends('layouts.master')
@section('content')

  <div class="main-panel-10">
    <div class="content-wrapper">
        
        {{--  --}}
        <div class="row">
          
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-body">
                  <div class="row gy-3">
                    <div class="demo-inline-spacing">
                        <button type="button" class="btn btn-primary p-2">Nouveau</button>
                        <button type="button" class="btn btn-secondary p-2">Modifier</button>
                        <button type="button" class="btn btn-success p-2">Supprimez</button>
                        <button type="button" class="btn btn-danger p-2">Paiement</button>
                        <button type="button" class="btn btn-warning p-2">MAJ Paie</button>
                        <button type="button" class="btn btn-info p-2">Echéan.</button>
                        <button type="button" class="btn btn-dark p-2">Profil</button>
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
            <div class="col-md-9">
                
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
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="180"> Éditer </font>
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
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="188"> Éditer </font>
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
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="196"> Éditer </font>
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
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="204"> Éditer </font>
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
                        <td>
                          <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3">
                                <font _mstmutation="1" _msttexthash="88283" _msthash="212"> Éditer </font>
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
                <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a class="nav-link active show" href="#profile" data-toggle="tab">
                            <i class="material-icons" _msttexthash="163033" _msthash="118">bug_report</i>
                            <font _mstmutation="1" _msttexthash="114920" _msthash="119"> Insectes </font>
                            <div class="ripple-container"></div>
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#messages" data-toggle="tab">
                            <i class="material-icons" _msttexthash="45383" _msthash="120">code</i>
                            <font _mstmutation="1" _msttexthash="209911" _msthash="121"> Site internet </font>
                            <div class="ripple-container"></div>
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#settings" data-toggle="tab">
                            <i class="material-icons" _msttexthash="61360" _msthash="122">nuage</i>
                            <font _mstmutation="1" _msttexthash="98696" _msthash="123"> Serveur </font>
                            <div class="ripple-container"></div>
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="profile">
                      <table class="table">
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="checkbox" value="" checked="">
                                  <span class="form-check-sign">
                                    <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                            </td>
                            <td _msttexthash="3965325" _msthash="124">Signez un contrat »</td>
                            <td class="td-actions text-right">
                              <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task">
                                <i class="material-icons" _msttexthash="91195" _msthash="125">éditer</i>
                              </button>
                              <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove">
                                <i class="material-icons" _msttexthash="79521" _msthash="126">fermer</i>
                              </button>
                             </td>
                          </tr>
                          <tr>
                            <td>
                <div class="form-check">
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                <span class="form-check-sign">
                <span class="check"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="2824289" _msthash="127">Des vers de la grande littérature russe ? Ou des e-mails de mon patron ?</td>
                <td class="td-actions text-right">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task">
                <i class="material-icons" _msttexthash="91195" _msthash="128">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove">
                <i class="material-icons" _msttexthash="79521" _msthash="129">fermer</i>
                </button>
                </td>
                </tr>
                <tr>
                <td>
                <div class="form-check">
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="">
                <span class="form-check-sign">
                <span class="check"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="24173136" _msthash="130">Inondations : Un an plus tard, évaluation de ce qui a été perdu et de ce qui a été trouvé lorsqu’une pluie dévastatrice a balayé la région métropolitaine de Détroit </td>
                <td class="td-actions text-right">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task">
                <i class="material-icons" _msttexthash="91195" _msthash="131">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove">
                <i class="material-icons" _msttexthash="79521" _msthash="132">fermer</i>
                </button>
                </td>
                </tr>
                <tr>
                <td>
                <div class="form-check">
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="" checked="">
                <span class="form-check-sign">
                <span class="check"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="3227575" _msthash="133">Créez 4 expériences utilisateur invisibles que vous ne connaissiez pas</td>
                <td class="td-actions text-right">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task">
                <i class="material-icons" _msttexthash="91195" _msthash="134">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove">
                <i class="material-icons" _msttexthash="79521" _msthash="135">fermer</i>
                </button>
                </td>
                </tr>
                </tbody>
                </table>
                </div>
                <div class="tab-pane" id="messages" _mstvisible="0">
                <table class="table" _mstvisible="1">
                <tbody _mstvisible="2">
                <tr _mstvisible="3">
                <td _mstvisible="4">
                <div class="form-check" _mstvisible="5">
                <label class="form-check-label" _mstvisible="6">
                <input class="form-check-input" type="checkbox" value="" checked="" _mstvisible="7">
                <span class="form-check-sign" _mstvisible="7">
                <span class="check" _mstvisible="8"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="24173136" _msthash="136" _mstvisible="4">Inondations : Un an plus tard, évaluation de ce qui a été perdu et de ce qui a été trouvé lorsqu’une pluie dévastatrice a balayé la région métropolitaine de Détroit </td>
                <td class="td-actions text-right" _mstvisible="4">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task" _mstvisible="5">
                <i class="material-icons" _msttexthash="91195" _msthash="137" _mstvisible="6">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" _mstvisible="5" aria-describedby="tooltip557485">
                <i class="material-icons" _msttexthash="79521" _msthash="138" _mstvisible="6">fermer</i>
                </button>
                </td>
                </tr>
                <tr _mstvisible="3">
                <td _mstvisible="4">
                <div class="form-check" _mstvisible="5">
                <label class="form-check-label" _mstvisible="6">
                <input class="form-check-input" type="checkbox" value="" _mstvisible="7">
                <span class="form-check-sign" _mstvisible="7">
                <span class="check" _mstvisible="8"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="3965325" _msthash="139" _mstvisible="4">Signez un contrat pour « De quoi les organisateurs de conférences ont-ils peur ? »</td>
                <td class="td-actions text-right" _mstvisible="4">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task" _mstvisible="5">
                <i class="material-icons" _msttexthash="91195" _msthash="140" _mstvisible="6">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" _mstvisible="5">
                <i class="material-icons" _msttexthash="79521" _msthash="141" _mstvisible="6">fermer</i>
                </button>
                </td>
                </tr>
                </tbody>
                </table>
                </div>
                <div class="tab-pane" id="settings" _mstvisible="0">
                <table class="table" _mstvisible="1">
                <tbody _mstvisible="2">
                <tr _mstvisible="3">
                <td _mstvisible="4">
                <div class="form-check" _mstvisible="5">
                <label class="form-check-label" _mstvisible="6">
                <input class="form-check-input" type="checkbox" value="" _mstvisible="7">
                <span class="form-check-sign" _mstvisible="7">
                <span class="check" _mstvisible="8"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="2824289" _msthash="142" _mstvisible="4">Des vers de la grande littérature russe ? Ou des e-mails de mon patron ?</td>
                <td class="td-actions text-right" _mstvisible="4">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task" _mstvisible="5">
                <i class="material-icons" _msttexthash="91195" _msthash="143" _mstvisible="6">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" _mstvisible="5" aria-describedby="tooltip971624">
                <i class="material-icons" _msttexthash="79521" _msthash="144" _mstvisible="6">fermer</i>
                </button>
                </td>
                </tr>
                <tr _mstvisible="3">
                <td _mstvisible="4">
                <div class="form-check" _mstvisible="5">
                <label class="form-check-label" _mstvisible="6">
                <input class="form-check-input" type="checkbox" value="" checked="" _mstvisible="7">
                <span class="form-check-sign" _mstvisible="7">
                <span class="check" _mstvisible="8"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="24173136" _msthash="145" _mstvisible="4">Inondations : Un an plus tard, évaluation de ce qui a été perdu et de ce qui a été trouvé lorsqu’une pluie dévastatrice a balayé la région métropolitaine de Détroit </td>
                <td class="td-actions text-right" _mstvisible="4">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task" _mstvisible="5">
                <i class="material-icons" _msttexthash="91195" _msthash="146" _mstvisible="6">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" _mstvisible="5">
                <i class="material-icons" _msttexthash="79521" _msthash="147" _mstvisible="6">fermer</i>
                </button>
                </td>
                </tr>
                <tr _mstvisible="3">
                <td _mstvisible="4">
                <div class="form-check" _mstvisible="5">
                <label class="form-check-label" _mstvisible="6">
                <input class="form-check-input" type="checkbox" value="" checked="" _mstvisible="7">
                <span class="form-check-sign" _mstvisible="7">
                <span class="check" _mstvisible="8"></span>
                </span>
                </label>
                </div>
                </td>
                <td _msttexthash="3965325" _msthash="148" _mstvisible="4">Signez un contrat pour « De quoi les organisateurs de conférences ont-ils peur ? »</td>
                <td class="td-actions text-right" _mstvisible="4">
                <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task" _mstvisible="5">
                <i class="material-icons" _msttexthash="91195" _msthash="149" _mstvisible="6">éditer</i>
                </button>
                <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" _mstvisible="5">
                <i class="material-icons" _msttexthash="79521" _msthash="150" _mstvisible="6">fermer</i>
                </button>
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

@endsection