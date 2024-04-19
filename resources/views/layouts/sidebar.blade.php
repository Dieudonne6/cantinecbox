<div class="theme-setting-wrapper">
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
</div>
<div id="right-sidebar" class="settings-panel">
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
    <!-- chat tab ends -->
  </div>
</div>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <li class="nav-item">
      <a class="nav-link" href="{{url('/dashbord')}}">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Tableau de bord</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="typcn typcn-document-text menu-icon"></i>
        <span class="menu-title">Inscriptions & disciplines</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
        <i class="typcn typcn-film menu-icon"></i>
        <span class="menu-title">Gestion des notes</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="typcn typcn-chart-pie-outline menu-icon"></i>
        <span class="menu-title">Examen Blanc</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
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
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="typcn typcn-compass menu-icon"></i>
        <span class="menu-title">Comptabilité & Budget</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Paramètrage</a></li>
          <div class="collapse" id="icons">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Table des chapitres</a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Table des compres</a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">table des banques</a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Table des partenaitres</a></li>
            </ul>
          </div>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Exécution</a></li>
          <div class="collapse" id="icons">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Enregistrement des écritures</a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">eregistrement linéaire</a></li>
            </ul>
          </div>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Op. Bancaire</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Valider le brouillard</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mise en place</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Décision nominatives</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Suivi des opérations par compte</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Editions</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Exporter</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Verouillage</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Cloture de mois</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
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
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Communication</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> Dialogue par SMS </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> WebScolaire </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Administration</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html">Profils des utilisateurs </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Connexion... </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Erreur Date </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Gestion des clés </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> Bricoles</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="typcn typcn-globe-outline menu-icon"></i>
        <span class="menu-title">Paramètre</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="#"> Table des paramètres </a></li>
          <li class="nav-item"> <a class="nav-link" href="#"> Modifier les bornes de l'exercice </a></li>
          <li class="nav-item"> <a class="nav-link" href="#"> Op. Ouverture </a></li>
          <div class="collapse" id="error">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="#"> Passer en classe supérieure </a></li>
              <li class="nav-item"> <a class="nav-link" href="#"> Reinitialiser les classes </a></li>
              <li class="nav-item"> <a class="nav-link" href="#"> Supprimer les sans classe </a></li>
              <li class="nav-item"> <a class="nav-link" href="#"> Cloturer l'année </a></li>
              <li class="nav-item"> <a class="nav-link" href="#"> Changement de trimestre </a></li>
            </ul>
          </div>
          <li class="nav-item"> <a class="nav-link" href="#"> Configurer Imprimante </a></li>
          <li class="nav-item"> <a class="nav-link" href="#"> Changement de trimestre </a></li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="typcn typcn-document-text menu-icon"></i>
        <span class="menu-title">Cantine</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">

          <li class="nav-item"> <a class="nav-link" href="{{url('/')}}">Toutes les classes</a></li>

          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Paramètre</a></li>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{url('/frais')}}">Frais mensuel et <br>annee academique</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{url('/connexiondonnées')}}">conexion a la<br>base de donnee</a></li>
            </ul>
          </div>

          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Etat</a></li>
          <div class="collapse" id="ui-basics">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="#">Etat des paiements</a></li>
              <li class="nav-item"> <a class="nav-link" href="#">Etat des droits<br>constates</a></li>
              <li class="nav-item"> <a class="nav-link" href="#">lettre de relance</a></li>
            </ul>
          </div>
        </ul>
      </div>
    </li>

  </ul>
</nav>