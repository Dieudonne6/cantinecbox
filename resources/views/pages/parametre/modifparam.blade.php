@extends('layouts.master')
@section('content')

          
    <div class="content-wrapper">
      <div class="row">
        
        <div class="col-md-9 grid-margin stretch-card">
            <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper">
                            <ul class="nav nav-tabs" data-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link active show" href="#profile" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="163033" _msthash="118">Identification</i>
                                        <font _mstmutation="1" _msttexthash="114920" _msthash="119">  </font>
                                        <div class="ripple-container"></div>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#messages" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="45383" _msthash="120">Paramètres</i>
                                        <font _mstmutation="1" _msttexthash="209911" _msthash="121"> généraux </font>
                                        <div class="ripple-container"></div>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#settings" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="61360" _msthash="122">Entètes &</i>
                                        <font _mstmutation="1" _msttexthash="98696" _msthash="123"> Messages </font>
                                        <div class="ripple-container"></div>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ScoMérite" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="61360" _msthash="122">ScoMérite</i>
                                        <font _mstmutation="1" _msttexthash="98696" _msthash="123">  </font>
                                        <div class="ripple-container"></div>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#scocarte" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="61360" _msthash="122">ScoCarte</i>
                                        <font _mstmutation="1" _msttexthash="98696" _msthash="123">  </font>
                                        <div class="ripple-container"></div>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#modulesms" data-toggle="tab">
                                        <i class="material-icons" _msttexthash="61360" _msthash="122">Module</i>
                                        <font _mstmutation="1" _msttexthash="98696" _msthash="123"> SMS </font>
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
                               
                                <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group row">
                                        <div class="col-sm-9">
                                          <input type="text" class="form-control" placeholder="Nom établissement">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group row">
                                        <div class="col-sm-9">
                                          <input type="text" class="form-control" placeholder="Code">
                                        </div>
                                      </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 grid-margin stretch-card">
                                        <div class="card">
                                           <div class="card-body">
                                            

                                            <div class="form-group row">
                                                <h4 class="card-title">Types d'établissement</h4>
                                                <div class="col">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" checked="">
                                                    <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                      Primary
                                                    </font>
                                                    <i class="input-helper"></i>
                                                  </label>                      
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                      
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                      
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                       
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                       
                                                </div>
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                      
                                                </div> 
                                                <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                      
                                                  </div>
                                                  <div class="col">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" checked="">
                                                        <font _mstmutation="1" _msttexthash="97695" _msthash="195">
                                                        Succès
                                                      </font><i class="input-helper"></i>
                                                    </label>                       
                                                  </div>
                                                
                                                    <form class="form-sample">
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                              <input type="text" class="form-control" placeholder="Adresse">
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                              <input type="text" class="form-control" placeholder="Ville">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-md-7">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Département">
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                              <input type="mail" class="form-control" placeholder="Email">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                     
                                                      <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                              <input type="text" class="form-control" placeholder="Nom directeur 1">
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Nom directeur 2">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Nom directeur 3">
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Nom censeur">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Comptable/Intendant">
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                          <div class="form-group row">
                                                            <div class="col-sm-20">
                                                                <input type="text" class="form-control" placeholder="Devise de l'étéblissement">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </form>
                                                  </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-4 grid-margin stretch-card">
                                        <div class="card">
                                          <div class="card-body">

                                            <div class="col-md-12">

                                            <div class="form-group">
                                              <label>Statut</label>
                                              <div class="col-sm-20">
                                                <select class="form-control">
                                                  <option _msttexthash="42861" _msthash="226">Public</option>
                                                  <option _msttexthash="73437" _msthash="227">Privé</option>
                                                  <option _msttexthash="73438" _msthash="228">Conv.</option>
                                                </select>
                                              </div>
                                            </div>

                                            <div class="nav-item nav-profile dropdown">
                                                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown" aria-expanded="false">
                                                  <img src="images/faces/face5.jpg" _mstalt="97227" _msthash="5">
                                                  <span class="nav-profile-name" _msttexthash="256932" _msthash="6">Logo gauche</span>
                                                  
                                                </a>
                                                <input class="form-control col-sm-20" type="file" id="form-file">
                                               
                                            </div>

                                            <div class="nav-item nav-profile dropdown">
                                                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown" aria-expanded="false">
                                                  <img src="images/faces/face5.jpg" _mstalt="97227" _msthash="5">
                                                  <span class="nav-profile-name" _msttexthash="256932" _msthash="6">Logo droite</span>
                                                  
                                                </a>
                                                <input class="form-control col-sm-20" type="file" id="form-file">
                                               
                                            </div>


                                          </div>
                                        </div>
                                    </div>
                                </div>


                                

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
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="template-demo ">
                <button type="button" class="btn btn-info btn-lg btn-block p-2"><font _mstmutation="1" _msttexthash="362778" _msthash="149">Enrégistrer </font><i class="typcn typcn-th-menu float-right"></i>
                </button>
                <button type="button" class="btn btn-dark btn-lg btn-block p-2" _msttexthash="362778" _msthash="150">Aide</button>
                <button type="button" class="btn btn-primary btn-lg btn-block p-2">
                  <i class="typcn typcn-user"></i><font _mstmutation="1" _msttexthash="362778" _msthash="151"> Appréciations </font></button>
                <button type="button" class="btn btn-outline-secondary btn-lg btn-block p-2" _msttexthash="362778" _msthash="152">Renuméroter</button>
                <button type="button" class="btn btn-outline-right btn-lg btn-block p-2" _msttexthash="362778" _msthash="152">Tester</button>
                <button type="button" class="btn btn-outline-danger btn-lg btn-block p-2" _msttexthash="362778" _msthash="152">Fermer</button>
              </div>
            </div>
          </div>
        </div>
        
        
        
      </div>
    </div>
    
  
 

@endsection