@extends('layouts.master')
@section('content')



<div class="main-panel-10">
  <div class="content-wrapper">
      
    <div class="row">
      <div class="col-xl-6 grid-margin stretch-card flex-column">
          <h5 class="mb-2 text-titlecase mb-4" _msttexthash="2536417" _msthash="99">Statistiques d’état</h5>
        <div class="row">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="mb-0 text-muted" _msttexthash="209352" _msthash="100">Transactions</p>
                  <p class="mb-0 text-muted" _msttexthash="34658" _msthash="101">+1.37%</p>
                </div>
                <h4 _msttexthash="22464" _msthash="102">1352</h4>
                <canvas id="transactions-chart" class="mt-auto chartjs-render-monitor" height="66" width="188" style="display: block; width: 188px; height: 66px;"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div>
                    <p class="mb-2 text-muted" _msttexthash="78663" _msthash="103">Ventes</p>
                    <h6 class="mb-0" _msttexthash="16406" _msthash="104">563</h6>
                  </div>
                  <div>
                    <p class="mb-2 text-muted" _msttexthash="77948" _msthash="105">Ordres</p>
                    <h6 class="mb-0" _msttexthash="15821" _msthash="106">720</h6>
                  </div>
                  <div>
                    <p class="mb-2 text-muted" _msttexthash="78884" _msthash="107">Revenu</p>
                    <h6 class="mb-0" _msttexthash="22607" _msthash="108">5900</h6>
                  </div>
                </div>
                <canvas id="sales-chart-a" class="mt-auto chartjs-render-monitor" height="66" width="188" style="display: block; width: 188px; height: 66px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="row h-100">
          <div class="col-md-6 stretch-card grid-margin grid-margin-md-0">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <p class="text-muted" _msttexthash="324376" _msthash="109">Analyse des ventes</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h3 class="mb-" _msttexthash="30368" _msthash="110">27632</h3>
                  <h3 class="mb-" _msttexthash="15158" _msthash="111">78%</h3>
                </div>
                <canvas id="sales-chart-b" class="mt-auto chartjs-render-monitor" height="38" width="188" style="display: block; width: 188px; height: 38px;"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row h-100">
                  <div class="col-6 d-flex flex-column justify-content-between"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <p class="text-muted" _msttexthash="24362" _msthash="112">CPU</p>
                    <h4 _msttexthash="14664" _msthash="113">55%</h4>
                    <canvas id="cpu-chart" class="mt-auto chartjs-render-monitor" width="82" height="40" style="display: block; width: 82px; height: 40px;"></canvas>
                  </div>
                  <div class="col-6 d-flex flex-column justify-content-between"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <p class="text-muted" _msttexthash="108290" _msthash="114">Mémoire</p>
                    <h4 _msttexthash="37336" _msthash="115">123,65</h4>
                    <canvas id="memory-chart" class="mt-auto chartjs-render-monitor" width="82" height="40" style="display: block; width: 82px; height: 40px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-6 grid-margin stretch-card flex-column">
        <h5 class="mb-2 text-titlecase mb-4" _msttexthash="592228" _msthash="116">Statistiques sur le revenu</h5>
        <div class="row h-100">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                  <div>
                    <p class="mb-3" _msttexthash="497549" _msthash="117">Augmentation mensuelle</p>
                    <h3 _msttexthash="31096" _msthash="118">67842</h3>
                  </div>
                  <div id="income-chart-legend" class="d-flex flex-wrap mt-1 mt-md-0"><div class="d-flex align-items-center mr-3"><div class="mr-2" style="width: 12px; border-radius: 50%; height: 12px; background-color: #a43cda "></div><p class="mb-0" _msttexthash="2632526" _msthash="119">Enregistrer l’utilisateur</p></div><div class="d-flex align-items-center"><div class="mr-2" style="width: 12px; border-radius: 50%; height: 12px; background-color: #00c8bf "></div><p class="mb-0" _msttexthash="387725" _msthash="120">Utilisateur Premium</p></div></div>
                </div>
                <canvas id="income-chart" width="456" height="228" style="display: block; width: 456px; height: 228px;" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium" _msttexthash="259311" _msthash="121">Ventes globales</h6>
              <div class="dropdown">
                <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="289536" _msthash="122"> 30 derniers jours </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" _msthidden="5">
                  <h6 class="dropdown-header" _msttexthash="117221" _msthidden="1" _msthash="123">Settings</h6>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="76466" _msthidden="1" _msthash="124">Action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="232752" _msthidden="1" _msthash="125">Another action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="349791" _msthidden="1" _msthash="126">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="230529" _msthidden="1" _msthash="127">Separated link</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="sales-chart-c" class="mt-2 chartjs-render-monitor" width="277" height="138" style="display: block; width: 277px; height: 138px;"></canvas>
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 mt-4">
              <div class="d-flex flex-column justify-content-center align-items-center">
                <p class="text-muted" _msttexthash="212147" _msthash="128">Ventes brutes</p>
                <h5 _msttexthash="16510" _msthash="129">492</h5>
                <div class="d-flex align-items-baseline">
                  <p class="text-success mb-0" _msttexthash="20163" _msthash="130">0.5%</p>
                  <i class="typcn typcn-arrow-up-thick text-success"></i>
                </div>
              </div>
              <div class="d-flex flex-column justify-content-center align-items-center">
                <p class="text-muted" _msttexthash="75517" _msthash="131">Achats</p>
                <h5 _msttexthash="23335" _msthash="132">87k</h5>
                <div class="d-flex align-items-baseline">
                  <p class="text-success mb-0" _msttexthash="20514" _msthash="133">0.8%</p>
                  <i class="typcn typcn-arrow-up-thick text-success"></i>
                </div>
              </div>
              <div class="d-flex flex-column justify-content-center align-items-center">
                <p class="text-muted" _msttexthash="473798" _msthash="134">Déclaration de revenus</p>
                <h5 _msttexthash="16770" _msthash="135">882</h5>
                <div class="d-flex align-items-baseline">
                  <p class="text-danger mb-0" _msttexthash="19981" _msthash="136">-04%</p>
                  <i class="typcn typcn-arrow-down-thick text-danger"></i>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="dropdown">
                <button class="btn bg-white p-0 pb-1 pt-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="266409" _msthash="137"> 7 derniers jours </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" _msthidden="5">
                  <h6 class="dropdown-header" _msttexthash="117221" _msthidden="1" _msthash="138">Settings</h6>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="76466" _msthidden="1" _msthash="139">Action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="232752" _msthidden="1" _msthash="140">Another action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="349791" _msthidden="1" _msthash="141">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="230529" _msthidden="1" _msthash="142">Separated link</a>
                </div>
              </div>
              <p class="mb-0" _msttexthash="98397" _msthash="143">aperçu</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4 grid-margin stretch-card">
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card newsletter-card bg-gradient-warning">
              <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                  <h5 class="mb-3 text-white" _msttexthash="115154" _msthash="144">Bulletin</h5>
                  <form class="form d-flex flex-column align-items-center justify-content-between w-100">
                    <div class="form-group mb-2 w-100">
                      <input type="text" class="form-control" placeholder="adresse courriel" _mstplaceholder="206765" _msthash="145">
                    </div>
                    <button class="btn btn-danger btn-rounded mt-1" type="submit" _msttexthash="1002209" _msthash="146">S’inscrire</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 stretch-card">
            <div class="card profile-card bg-gradient-primary">
              <div class="card-body">
                <div class="row align-items-center h-100">
                  <div class="col-md-4">
                    <figure class="avatar mx-auto mb-4 mb-md-0">
                      <img src="images/faces/face20.jpg" alt="avatar" _mstalt="79183" _msthash="147">
                    </figure>
                  </div>
                  <div class="col-md-8">
                    <h5 class="text-white text-center text-md-left" _msttexthash="224978" _msthash="148">Phoebe Kennedy</h5>
                    <p class="text-white text-center text-md-left" _msttexthash="328315" _msthash="149">kennedy@gmail.com</p>
                    <div class="d-flex align-items-center justify-content-between info pt-2">
                      <div>
                        <p class="text-white font-weight-bold" _msttexthash="282269" _msthash="150">Date de naissance</p>
                        <p class="text-white font-weight-bold" _msttexthash="314106" _msthash="151">Ville de naissance</p>
                      </div>
                      <div>
                        <p class="text-white" _msttexthash="119795" _msthash="152">16 sept. 2019</p>
                        <p class="text-white" _msttexthash="100529" _msthash="153">Pays-Bas</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium" _msttexthash="427284" _msthash="154">Statistiques de vente</h6>
              <div class="dropdown">
                <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="266409" _msthash="155"> 7 derniers jours </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4" _msthidden="5">
                  <h6 class="dropdown-header" _msttexthash="117221" _msthidden="1" _msthash="156">Settings</h6>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="76466" _msthidden="1" _msthash="157">Action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="232752" _msthidden="1" _msthash="158">Another action</a>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="349791" _msthidden="1" _msthash="159">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:;" _msttexthash="230529" _msthidden="1" _msthash="160">Separated link</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="sales-chart-d" height="295" width="277" style="display: block; width: 277px; height: 295px;" class="chartjs-render-monitor"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
              <div>
                <p class="mb-2 text-md-center text-lg-left" _msttexthash="348296" _msthash="161">Total des dépenses</p>
                <h1 class="mb-0" _msttexthash="23400" _msthash="162">8742</h1>
              </div>
              <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
            </div>
            <canvas id="expense-chart" height="73" width="277" style="display: block; width: 277px; height: 73px;" class="chartjs-render-monitor"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
              <div>
                <p class="mb-2 text-md-center text-lg-left" _msttexthash="175812" _msthash="163">Total Budget</p>
                <h1 class="mb-0" _msttexthash="37804" _msthash="164">47,840</h1>
              </div>
              <i class="typcn typcn-chart-pie icon-xl text-secondary"></i>
            </div>
            <canvas id="budget-chart" height="73" width="277" style="display: block; width: 277px; height: 73px;" class="chartjs-render-monitor"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
              <div>
                <p class="mb-2 text-md-center text-lg-left" _msttexthash="158522" _msthash="165">Solde total</p>
                <h1 class="mb-0" _msttexthash="28067" _msthash="166">7 243 $</h1>
              </div>
              <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
            </div>
            <canvas id="balance-chart" height="73" width="277" style="display: block; width: 277px; height: 73px;" class="chartjs-render-monitor"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
              <thead>
                <tr>
                  <th class="ml-5" _msttexthash="13715" _msthash="167">ID</th>
                  <th _msttexthash="185380" _msthash="168">Nom du projet</th>
                  <th _msttexthash="76570" _msthash="169">Client</th>
                  <th _msttexthash="155961" _msthash="170">Date limite</th>
                  <th _msttexthash="136253" _msthash="171">Paiements </th>
                  <th _msttexthash="74568" _msthash="172">Trafic</th>
                  <th _msttexthash="95901" _msthash="173">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td _msttexthash="15990" _msthash="174">#D1</td>
                  <td _msttexthash="702468" _msthash="175">Consectetur adipisicing elit </td>
                  <td _msttexthash="255164" _msthash="176">Beulah Cummings</td>
                  <td _msttexthash="117845" _msthash="177">03 janv. 2019</td>
                  <td _msttexthash="28028" _msthash="178">5235 $</td>
                  <td _msttexthash="24752" _msthash="179">1,3 K</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3"><font _mstmutation="1" _msttexthash="88283" _msthash="180"> Éditer </font><i class="typcn typcn-edit btn-icon-append"></i>                          
                      </button>
                      <button type="button" class="btn btn-danger btn-sm btn-icon-text"><font _mstmutation="1" _msttexthash="139100" _msthash="181"> Supprimer </font><i class="typcn typcn-delete-outline btn-icon-append"></i>                          
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td _msttexthash="16107" _msthash="182">#D2</td>
                  <td _msttexthash="1373567" _msthash="183">Corrélation : silo de ressources naturelles</td>
                  <td _msttexthash="253994" _msthash="184">Mitchel Dunford</td>
                  <td _msttexthash="98514" _msthash="185">09 oct. 2019</td>
                  <td _msttexthash="27586" _msthash="186">3233 $</td>
                  <td _msttexthash="25441" _msthash="187">5.4K</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3"><font _mstmutation="1" _msttexthash="88283" _msthash="188"> Éditer </font><i class="typcn typcn-edit btn-icon-append"></i>                          
                      </button>
                      <button type="button" class="btn btn-danger btn-sm btn-icon-text"><font _mstmutation="1" _msttexthash="139100" _msthash="189"> Supprimer </font><i class="typcn typcn-delete-outline btn-icon-append"></i>                          
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
                  <td _msttexthash="24609" _msthash="195">2,1 K</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3"><font _mstmutation="1" _msttexthash="88283" _msthash="196"> Éditer </font><i class="typcn typcn-edit btn-icon-append"></i>                          
                      </button>
                      <button type="button" class="btn btn-danger btn-sm btn-icon-text"><font _mstmutation="1" _msttexthash="139100" _msthash="197"> Supprimer </font><i class="typcn typcn-delete-outline btn-icon-append"></i>                          
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td _msttexthash="16341" _msthash="198">#D4</td>
                  <td _msttexthash="915720" _msthash="199">Responsabiliser les communautés</td>
                  <td _msttexthash="289055" _msthash="200">Gaynell Sharpton</td>
                  <td _msttexthash="108056" _msthash="201">23 mars 2019</td>
                  <td _msttexthash="28587" _msthash="202">7743 $</td>
                  <td _msttexthash="25311" _msthash="203">2,7 K</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3"><font _mstmutation="1" _msttexthash="88283" _msthash="204"> Éditer </font><i class="typcn typcn-edit btn-icon-append"></i>                          
                      </button>
                      <button type="button" class="btn btn-danger btn-sm btn-icon-text"><font _mstmutation="1" _msttexthash="139100" _msthash="205"> Supprimer </font><i class="typcn typcn-delete-outline btn-icon-append"></i>                          
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td _msttexthash="16458" _msthash="206">#D5</td>
                  <td _msttexthash="567619" _msthash="207"> Ciblé efficace ; mobiliser </td>
                  <td _msttexthash="230282" _msthash="208">Audrie Midyett</td>
                  <td _msttexthash="128115" _msthash="209">22 août 2019</td>
                  <td _msttexthash="28197" _msthash="210">2455 $</td>
                  <td _msttexthash="40508" _msthash="211">1,2 Ko</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3"><font _mstmutation="1" _msttexthash="88283" _msthash="212"> Éditer </font><i class="typcn typcn-edit btn-icon-append"></i>                          
                      </button>
                      <button type="button" class="btn btn-danger btn-sm btn-icon-text"><font _mstmutation="1" _msttexthash="139100" _msthash="213"> Supprimer </font><i class="typcn typcn-delete-outline btn-icon-append"></i>                          
                      </button>
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
  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <footer class="footer">
      <div class="card">
          <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between">
                  <span class="text-muted text-center text-sm-left d-block d-sm-inline-block" _msttexthash="3906006" _msthash="214">Droits d’auteur © 2020 <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank" _istranslated="1">Bootstrapdash</a>. Tous droits réservés.</span>
                  <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted" _msttexthash="2906189" _msthash="215">Modèles de <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank" _istranslated="1">tableaux de bord Bootstrap</a> gratuits de Bootstrapdash.com</span>
              </div>
          </div>    
      </div>        
  </footer>
  <!-- partial -->
</div>

@endsection