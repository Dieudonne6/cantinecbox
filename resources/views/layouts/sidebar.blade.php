{{-- <div class="theme-setting-wrapper">
  <div id="settings-trigger"><i class="typcn typcn-cog-outline"></i></div>
  <div id="theme-settings" class="settings-panel">
    <i class="settings-close typcn typcn-times"></i>
    <p class="settings-heading">SIDEBAR SKINS</p>
    <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
    <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
    <p class="settings-heading mt-2">HEADER SKINS</p>
    <div class="color-tiles mx-0 px-4">
      <div class="tiles success"></div>
      <div class="tiles warning"></div>
      <div class="tiles danger"></div>
      <div class="tiles info"></div>
      <div class="tiles dark"></div>
      <div class="tiles default"></div>
    </div>
  </div>
</div> --}}
{{-- <div id="right-sidebar" class="settings-panel">
  <i class="settings-close typcn typcn-times"></i>
  <ul class="nav nav-tabs" id="setting-panel" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
    </li>
  </ul>
  <div class="tab-content" id="setting-content">
    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
      <div class="add-items d-flex px-3 mb-0">
        <form class="form w-100">
          <div class="form-group d-flex">
            <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
            <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
          </div>
        </form>
      </div>
      <div class="list-wrapper px-3">
        <ul class="d-flex flex-column-reverse todo-list">
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Team review meeting at 3.00 PM
              </label>
            </div>
            <i class="remove typcn typcn-delete-outline"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Prepare for presentation
              </label>
            </div>
            <i class="remove typcn typcn-delete-outline"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Resolve all the low priority tickets due today
              </label>
            </div>
            <i class="remove typcn typcn-delete-outline"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Schedule meeting for next week
              </label>
            </div>
            <i class="remove typcn typcn-delete-outline"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Project review
              </label>
            </div>
            <i class="remove typcn typcn-delete-outline"></i>
          </li>
        </ul>
      </div>
      <div class="events py-4 border-bottom px-3">
        <div class="wrapper d-flex mb-2">
          <i class="typcn typcn-media-record-outline text-primary mr-2"></i>
          <span>Feb 11 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Creating component page</p>
        <p class="text-gray mb-0">build a js based app</p>
      </div>
      <div class="events pt-4 px-3">
        <div class="wrapper d-flex mb-2">
          <i class="typcn typcn-media-record-outline text-primary mr-2"></i>
          <span>Feb 7 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
        <p class="text-gray mb-0 ">Call Sarah Graves</p>
      </div>
    </div>
    <!-- To do section tab ends -->
    <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
      <div class="d-flex align-items-center justify-content-between border-bottom">
        <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
        <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
      </div>
      <ul class="chat-list">
        <li class="list active">
          <div class="profile"><img src="../../images/faces/face1.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Thomas Douglas</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">19 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="../../images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
          <div class="info">
            <div class="wrapper d-flex">
              <p>Catherine</p>
            </div>
            <p>Away</p>
          </div>
          <div class="badge badge-success badge-pill my-auto mx-2">4</div>
          <small class="text-muted my-auto">23 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="../../images/faces/face3.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Daniel Russell</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">14 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="../../images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
          <div class="info">
            <p>James Richardson</p>
            <p>Away</p>
          </div>
          <small class="text-muted my-auto">2 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="../../images/faces/face5.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Madeline Kennedy</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">5 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="../../images/faces/face6.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Sarah Graves</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">47 min</small>
        </li>
      </ul>
    </div>
  </div>
</div> --}}

<nav class="sidebar sidebar-offcanvas" id="sidebar" style="max-width: 240px;">
    <ul class="nav">

        {{-- <li class="nav-item">
      <a class="nav-link" href="{{url('/dashbord')}}">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Tableau de bord</span>
      </a>
    </li> --}}


        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="typcn typcn-document-text menu-icon"></i>
                <span class="menu-title">Inscriptions<br>& disciplines</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">

                    @php
                        $routesAccueil = [
                            'Acceuil',
                            'inscrireeleve',
                            'modifiereleve',
                            'paiementeleve',
                            'majpaiementeleve',
                            'echeancier',
                            'profil',
                            'pagedetail',
                        ]; // Liste des noms de routes associées à l'accueil
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link {{ in_array(request()->route()->getName(), $routesAccueil) ? 'active' : '' }}"
                            href="{{ route('Acceuil') }}">Accueil</a>
                    </li>
                    {{-- Créations des classes --}}
                    <li class="nav-item menu-item-has-children">
                        <a href="" class="nav-link">Gestion des classes</a>
                        <ul class="sub-menus">
                            <li>
                                <a class="nav-link {{ request()->is('typesclasses') ? 'active' : '' }}"
                                    href="{{ url('/typesclasses') }}">Types classes</a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('series') ? 'active' : '' }}"
                                    href="{{ url('/series') }}">Séries</a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('promotions') ? 'active' : '' }}"
                                    href="{{ url('/promotions') }}">Promotions</a>
                            </li>
                            @php
                                $routesClass = ['tabledesclasses', 'enrclasse', 'modifierclasse']; // Liste des noms de routes associées à l'accueil
                            @endphp

                            <li class="nav-item">
                                <a class="nav-link {{ in_array(request()->route()->getName(), $routesClass) ? 'active' : '' }}"
                                    href="{{ route('tabledesclasses') }}">Gestions des classes</a>
                            </li>

                            <li>
                                <a class="nav-link {{ request()->is('groupes') ? 'active' : '' }}"
                                    href="{{ url('/groupes') }}">Grouper</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Scolarité --}}
                    <li class="nav-item menu-item-has-children">
                        <a href="" class="nav-link">Scolarité</a>
                        <ul class="sub-menus">
                            <li>
                                <a class="nav-link {{ request()->is('creerprofil') ? 'active' : '' }}"
                                    href="{{ url('/creerprofil') }}">Créer profils</a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('paramcomposantes') ? 'active' : '' }}"
                                    href="{{ url('/paramcomposantes') }}">Parametrage composantes</a>
                            </li>
                            @php
                                $routesfacture = ['facturesclasses', 'detailfacturesclasses']; // Liste des noms de routes associées à l'accueil
                            @endphp
                            <li>
                                <a class="nav-link {{ in_array(request()->route()->getName(), $routesfacture) ? 'active' : '' }}"
                                    href="{{ route('facturesclasses') }}">Factures classes</a>

                            </li>
                            <li>
                                {{--                 <a class="nav-link {{ request()->is('reductioncollective') ? 'active' : '' }}" href="{{url('/reductioncollective')}}">Réductions collectives</a> --}} </li>
                            <li>
                                <a class="nav-link {{ request()->is('paiementdesnoninscrits') ? 'active' : '' }}"
                                    href="{{ url('/paiementdesnoninscrits') }}">Paiement des non inscrits</a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('duplicatarecu') ? 'active' : '' }}"
                                    href="{{ url('/duplicatarecu') }}"> Duplicata</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"> <a class="nav-link" {{ request()->is('recalculereffectif') ? 'active' : '' }}
                            href="{{ url('/recalculereffectifs') }}">Recalculer effectifs</a></li>
                    {{-- Dicipline --}}
                    <li class="nav-item"> <a class="nav-link {{ request()->is('discipline') ? 'active' : '' }}"
                            href="{{ url('/discipline') }}">Discipline</a></li>

                    {{-- Extraction de données --}}
                    {{-- <li class="nav-item menu-item-has-children">
            <a href="" class="nav-link">Extraction de données</a>
            <ul class="sub-menus">
              <li>
                <a href="{{url('/transfert')}}">Transfert</a>
              </li>
              <li>
                <a href="{{url('/exporter')}}">Exporter</a>
              </li>
              <li>
                <a href="{{url('/importer')}}">Importer</a>
              </li>
            </ul>
          </li> --}}

                    {{-- Intégrité
          <li class="nav-item menu-item-has-children">
            <a href="" class="nav-link">Intégrité</a>
            <ul class="sub-menus">
              <li>
                <a href="{{url('/verrouillage')}}">Verrouillage</a>
              </li>
              <li>
                <a href="{{url('/recaculereffectifs')}}">Recalculer Effectifs</a>
              </li>
            </ul>
          </li> --}}

                    {{-- Editions --}}
                    @php
                        $routeseditions = [
                            'editions',
                            'listedeseleves',
                            'listedesclasses',
                            'listeselectiveeleve',
                            'eleveparclasse',
                            'certificatsolarite',
                            'etatdelacaisse',
                            'enquetesstatistiques',
                            'situationfinanciereglobale',
                            'etatdesrecouvrements',
                            'arriereconstate',
                        ]; // Liste des noms de routes associées à l'accueil
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(request()->route()->getName(), $routeseditions) ? 'active' : '' }}"
                            href="{{ route('editions') }}">Editions</a>

                    </li>

                    {{-- <li class="nav-item"> <a class="nav-link {{ request()->is('editions') ? 'active' : '' }}" href="{{url('/editions')}}">Editions</a></li> --}}

                    {{-- Archives --}}
                    <li class="nav-item"> <a class="nav-link {{ request()->is('archive') ? 'active' : '' }}"
                            href="{{ url('/archive') }}">Archives</a></li>
                    {{-- <div class="dropdown">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton90" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Etats </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton90" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Etat des paiements</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Etat des droits constatés</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Lettre de relance</a>
            </div> --}}

                </ul>
            </div>
        </li>


        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false"
              aria-controls="form-elements">
              <i class="typcn typcn-film menu-icon"></i>
              <span class="menu-title">Gestion des notes</span>
              <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                  {{-- Paramètres --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Paramètres</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Répartition des classes par opérateur') ? 'active' : '' }}" href="#">Répartition des classes par opérateur</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Table des matières') ? 'active' : '' }}" href="#">Table des matières</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Table des coefficients') ? 'active' : '' }}" href="#">Table des coefficients</a>
                          </li>
                      </ul>
                  </li>
      
                  {{-- Manipulation des notes --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Manipulation des notes</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Saisir et mises à jour des notes') ? 'active' : '' }}" href="#">Saisir et mises à jour des notes</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Enrégistrer les résultats des examens') ? 'active' : '' }}" href="#">Enrégistrer les résultats des examens</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Vérifier les notes') ? 'active' : '' }}" href="#">Vérifier les notes</a>
                          </li>
                      </ul>
                  </li>
      
                  {{-- Sécurité --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Sécurité</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Verrouillage') ? 'active' : '' }}" href="#">Verrouillage</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Déverrouillage') ? 'active' : '' }}" href="#">Déverrouillage</a>
                          </li>
                      </ul>
                  </li>
      
                  {{-- Edition --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Edition</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Tableau de notes') ? 'active' : '' }}" href="#">Tableau de notes</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Bulletin de notes') ? 'active' : '' }}" href="#">Bulletin de notes</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Attestations de mérite') ? 'active' : '' }}" href="#">Attestations de mérite</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Fiches de notes vierge') ? 'active' : '' }}" href="#">Fiches de notes vierge</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Relevés par matière') ? 'active' : '' }}" href="#">Relevés par matière</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Relevés par élèves') ? 'active' : '' }}" href="#">Relevés par élèves</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Récapitulatif de notes') ? 'active' : '' }}" href="#">Récapitulatif de notes</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Tableau analytique par matière') ? 'active' : '' }}" href="#">Tableau analytique par matière</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Résultats par promotion') ? 'active' : '' }}" href="#">Résultats par promotion</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Liste des méritants') ? 'active' : '' }}" href="#">Liste des méritants</a>
                          </li>
                      </ul>
                  </li>
      
                  {{-- Résultats --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Résultats</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Liste par ordre de mérite') ? 'active' : '' }}" href="#">Liste par ordre de mérite</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Tableau analytique') ? 'active' : '' }}" href="#">Tableau analytique</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Rapports annuels') ? 'active' : '' }}" href="#">Rapports annuels</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Livrets scolaires') ? 'active' : '' }}" href="#">Livrets scolaires</a>
                          </li>
                      </ul>
                  </li>
      
                  {{-- Extraction --}}
                  <li class="nav-item menu-item-has-children">
                      <a href="#" class="nav-link">Extraction</a>
                      <ul class="sub-menus">
                          <li>
                              <a class="nav-link {{ request()->is('Exporter') ? 'active' : '' }}" href="#">Exporter</a>
                          </li>
                          <li>
                              <a class="nav-link {{ request()->is('Importer') ? 'active' : '' }}" href="#">Importer</a>
                          </li>
                      </ul>
                  </li>
              </ul>
          </div>
      </li>
      

        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="typcn typcn-chart-pie-outline menu-icon"></i>
        <span class="menu-title">Examen Blanc</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Confection des listes</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Anonymat</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Fiches de notes vierges</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Saisit des notes</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Calcul des moyennes</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Transfert des notes</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Tableau des notes</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Listes par ordre de mérite</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">Relevés de notes</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <i class="typcn typcn-th-small-outline menu-icon"></i>
        <span class="menu-title">Ressource humaine</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="tables">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Type agent</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Mise à jour du personnel</a></li>

          <div class="dropdown">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton6" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Configurer </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton6" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Configuration des salles</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Config. quotas horaires</a>
            </div>
            </li>
          </div>
          
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Emploi du temps automatique</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Saisir un emploi du temps</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Pointage des heures</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Pointage manuel</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Hors emploi du temps</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Heures sup.</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Rubriques salaire</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Créer Profils</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Taux horaires</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Avances sur salaires</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Buletins et états</a></li>

          <div class="dropdown">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton7" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Editions </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton7" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="22" _msthash="22" _mstvisible="1">Liste des profs par matière</a>
              <a class="dropdown-item" href="#" _msttexthash="23" _msthash="23" _mstvisible="1">Liste des profs par classes <br> et par matière</a>
              <a class="dropdown-item" href="#" _msttexthash="24" _msthash="24" _mstvisible="1">Liste des proffesseurs <br> principaux</a>
              <a class="dropdown-item" href="#" _msttexthash="25" _msthash="25" _mstvisible="1">Liste des nominative <br> du personnel</a>
              <a class="dropdown-item" href="#" _msttexthash="26" _msthash="26" _mstvisible="1">Etat d'effectif</a>
              <a class="dropdown-item" href="#" _msttexthash="27" _msthash="27" _mstvisible="1">Volume horaire exécuté/prof</a>
              <a class="dropdown-item" href="#" _msttexthash="28" _msthash="28" _mstvisible="1">Volume horaire exécuté <br>  par matière/classe</a>
              <a class="dropdown-item" href="#" _msttexthash="29" _msthash="29" _mstvisible="1">Etat paiement IPTS & CNSS</a>
            </div>
            </li>
          </div>
        
        </ul>
      </div>
    </li> --}}

        {{-- Comptabilité & Budget --}}
        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="typcn typcn-compass menu-icon"></i>
        <span class="menu-title">Comptabilité & Budget</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">

          <div class="dropdown">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton6" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Paramètrage </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton6" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Table des chapitres</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Table des comptes</a>
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Table des banques</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Table des partenaitres</a>
            </div>
            </li>
          </div>

          <div class="dropdown-scroll">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton6" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Exécution </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton6" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Enregistrement des écritures</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Enregistrement linéaire</a>
            </div>
            </li>
          </div>
        
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Op. Bancaire</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Valider le brouillard</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mise en place</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Décision nominatives</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Suivi des opérations par compte</a></li>
          
          <div class="dropdown">
           <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton20" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" > Editions </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton20" style="max-height: 200px ; overflow-y: auto;" >
              <a class="dropdown-item" href="#">Liste des comptes</a>
              <a class="dropdown-item" href="#">Liste des partenaires</a>
              <a class="dropdown-item" href="#">Prévisions Budgétaires</a>
              <a class="dropdown-item" href="#">Edition Borderaux <br> et relevés</a>
              <a class="dropdown-item" href="#">Fiche de suivi des <br> opérations par compte</a>
              <a class="dropdown-item" href="#">Fiche de suivi des <br> comptes par mois</a>
              <a class="dropdown-item" href="#">Situation mensuelle <br> des dépenses</a>
              <a class="dropdown-item" href="#">Suivi des comptes de <br> recettes spécifiques</a>
              <a class="dropdown-item" href="#">Journaux</a>
              <a class="dropdown-item" href="#">Balances des <br> comptes/Résultats</a>
              <a class="dropdown-item" href="#">Grand livre périodique <br> des comptes</a>
              <a class="dropdown-item" href="#">Situation des finances <br> de l'établissement</a>
              <a class="dropdown-item" href="#">Situation de la banque</a>
              <a class="dropdown-item" href="#">Situation des finances <br> mois par mois</a>
              <a class="dropdown-item" href="#">Situation des engagements</a>
              <a class="dropdown-item" href="#">Compte rendu <br> d'exécution du budget</a>
            </div>
           </li>
          </div>
          
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Exporter</a></li>

            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton6" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Verouillage </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton6" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Vérouillage</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Dévérouillage</a>
            </div>

          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Cloture de mois</a></li>
        </ul>
      </div>
    </li> --}}

        {{-- Ressource matérielles --}}
        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="typcn typcn-user-add-outline menu-icon"></i>
        <span class="menu-title">Ressource matérielles</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Configuration des Matières </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Configuration des Denrées </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Entrée de stock </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Sortie de stock </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> PV de Réception </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Affectations </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Suivi </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Etat d'inventaire </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Editions </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Réservations Salles </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Occupation Salles </a></li>
        </ul>
      </div>
    </li> --}}

        {{-- Communication --}}
        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#Communication" aria-expanded="false" aria-controls="Communication">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Communication</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="Communication">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/Communication-404.html"> Dialogue par SMS </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/Communication-500.html"> WebScolaire </a></li>
        </ul>
      </div>
    </li> --}}

        {{-- Administration --}}
        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#Administration" aria-expanded="false" aria-controls="Administration">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Administration</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="Administration">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html">Profils des utilisateurs </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Connexion... </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Erreur Date </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Gestion des clés </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Bricoles</a></li>
        </ul>
      </div>
    </li> --}}

        {{-- Paramètres --}}

        {{--
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#Cantine" aria-expanded="false" aria-controls="Cantine">
                <i class="typcn typcn-document-text menu-icon"></i>
                <span class="menu-title">Cantine</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="Cantine">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/inscriptioncantine') }}">Inscriptions</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href="{{ url('/classes') }}">Toutes les classes</a></li>

                    <li class="nav-item menu-item-has-children">
                        <a href="" class="nav-link">Etats</a>
                        <ul class="sub-menus">
                            <li>
                                <a href="{{ url('/etatpaiement') }}">Etat des paiements</a>
                            </li>
                            <li>
                                <a href="{{ url('/etatdroits') }}">Etat des droits constatés</a>
                            </li>
                            <li>
                <a href="{{url('/lettrederelance')}}">Lettre de relance</a>
              </li> 
                        </ul>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/duplicatafacture') }}">Duplicata
                            facture</a></li>

                  <div class="dropdown">
            <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton90" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Etats </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton90" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="20" _msthash="20" _mstvisible="1">Etat des paiements</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Etat des droits constatés</a>
              <a class="dropdown-item" href="#" _msttexthash="21" _msthash="21" _mstvisible="1">Lettre de relance</a>
            </div>

                </ul>
            </div>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/parametre') }}">
                <i class="typcn typcn-globe-outline menu-icon"></i>
                <span class="menu-title" _msttexthash="234962" _msthash="98">Paramètres</span>
            </a>
        </li>
        {{-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Paramètres</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          {{-- <li class="nav-item"> <a class="nav-link" href="#"> Table des paramètres </a></li>
          <li class="nav-item"> <a class="nav-link" href="#"> Modifier les bornes de l'exercice </a></li>
           --}}
        {{-- <div class="dropdown">
           <li class="nav-item">
            <button class="btn btn-light-lg dropdown-toggle" type="button" id="dropdownMenuSizeButton1" data-toggle="dropdown" 
            aria-haspopup="true" aria-expanded="false" _msttexthash="313989" _msthash="279"> Op. Ouverture </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton1" style="" _mstvisible="0" >
              <a class="dropdown-item" href="#" _msttexthash="76466" _msthash="281" _mstvisible="1">Passer en classe supérieure</a>
              <a class="dropdown-item" href="#" _msttexthash="261807" _msthash="282" _mstvisible="1">Reinitialiser les classes</a>
              <a class="dropdown-item" href="#" _msttexthash="229879" _msthash="283" _mstvisible="1">Supprimer les sans classe</a>
              <a class="dropdown-item" href="#" _msttexthash="26180" _msthash="284" _mstvisible="1">Cloturer l'année</a>
              <a class="dropdown-item" href="#" _msttexthash="22987" _msthash="285" _mstvisible="1">Changement de trimestre</a>
            </div>
           </li>
          </div> --}}
        {{-- <li class="nav-item"> <a class="nav-link" href="{{url('/confimpression')}}"> Configurer Imprimante </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{url('/changetrimestre')}}"> Changement de trimestre </a></li> --}}
        {{-- <li class="nav-item"> <a class="nav-link" href="{{url('/frais')}}">Frais mensuel et <br>année academique </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{url('/connexiondonnees')}}">Connexion à la<br>base de donnée </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{url('/paramsfacture')}}">Paramètre Facture</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{url('/inscriptions')}}">Enregistrement utilisateurs </a></li> 

        </ul>
      </div>
    </li>  --}}
    </ul>



</nav>
