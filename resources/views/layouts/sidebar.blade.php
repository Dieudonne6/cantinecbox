<div id="parent-accordion">
  <nav class="sidebar sidebar-offcanvas" id="sidebar" style="max-width: 255px;">
    <ul class="nav">
      @php
      $routesAccueil = [
        'Acceuil', 'inscrireeleve', 'modifiereleve', 'paiementeleve', 'majpaiementeleve', 'echeancier', 
        'profil', 'pagedetail',
      ]; 
      $routesClass = ['tabledesclasses', 'enrclasse', 'modifierclasse'];
      $routesFacture = ['facturesclasses', 'detailfacturesclasses'];
      $routesEditions = [
        'editions', 'listedeseleves', 'listedesclasses', 'listeselectiveeleve', 'eleveparclasse',
        'certificatsolarite', 'etatdelacaisse', 'enquetesstatistiques', 'situationfinanciereglobale',
        'etatdesrecouvrements', 'arriereconstate', 'journaldetailleaveccomposante', 'journaldetaillesanscomposante'
      ];
      @endphp

      <!-- Bloc Inscriptions & disciplines -->
      <li class="nav-item"> 
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="{{ in_array(request()->route()->getName(), $routesAccueil) ? 'true' : 'false' }}" aria-controls="ui-basic">
          <i class="typcn typcn-document-text menu-icon"></i>
          <span class="menu-title">Inscriptions<br>& disciplines</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ in_array(request()->route()->getName(), $routesAccueil) ? 'show' : '' }}" id="ui-basic" data-bs-parent="#parent-accordion">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link {{ in_array(request()->route()->getName(), $routesAccueil) ? 'active' : '' }}" href="{{ route('Acceuil') }}">Accueil</a>
            </li>
            <!-- Bloc Gestion des classes -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('typesclasses') || request()->is('series') || request()->is('promotions') || request()->is('groupes') || in_array(request()->route()->getName(), $routesClass) ? 'active' : '' }}" 
                 data-bs-toggle="collapse" href="#classes" aria-expanded="{{ request()->is('typesclasses') || request()->is('series') || request()->is('promotions') || request()->is('groupes') || in_array(request()->route()->getName(), $routesClass) ? 'true' : 'false' }}" 
                 aria-controls="classes">
                Gestion des classes
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('typesclasses') || request()->is('series') || request()->is('promotions') || request()->is('groupes') || in_array(request()->route()->getName(), $routesClass) ? 'show' : '' }}" id="classes" data-bs-parent="#ui-basic">
                <ul class="nav sub-menu">
                  <li><a class="nav-link {{ request()->is('typesclasses') ? 'active' : '' }}" href="{{ url('/typesclasses') }}">Types classes</a></li>
                  <li><a class="nav-link {{ request()->is('series') ? 'active' : '' }}" href="{{ url('/series') }}">Séries</a></li>
                  <li><a class="nav-link {{ request()->is('promotions') ? 'active' : '' }}" href="{{ url('/promotions') }}">Promotions</a></li>
                  <li><a class="nav-link {{ in_array(request()->route()->getName(), $routesClass) ? 'active' : '' }}" href="{{ route('tabledesclasses') }}">Gestion des classes</a></li>
                  <li><a class="nav-link {{ request()->is('groupes') ? 'active' : '' }}" href="{{ url('/groupes') }}">Grouper</a></li>
                </ul>
              </div>
            </li>
            <!-- Bloc Scolarité -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('creerprofil') || request()->is('paramcomposantes') || request()->is('paiementdesnoninscrits') || request()->is('duplicatarecu') || in_array(request()->route()->getName(), $routesFacture) ? 'active' : '' }}"
                 data-bs-toggle="collapse" href="#scolarite" aria-expanded="{{ request()->is('creerprofil') || request()->is('paramcomposantes') || request()->is('paiementdesnoninscrits') || request()->is('duplicatarecu') || in_array(request()->route()->getName(), $routesFacture) ? 'true' : 'false' }}"
                 aria-controls="scolarite">
                Scolarité
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('creerprofil') || request()->is('paramcomposantes') || request()->is('paiementdesnoninscrits') || request()->is('duplicatarecu') || in_array(request()->route()->getName(), $routesFacture) ? 'show' : '' }}" id="scolarite" data-bs-parent="#ui-basic">
                <ul class="nav sub-menu">
                  <li><a class="nav-link {{ request()->is('creerprofil') ? 'active' : '' }}" href="{{ url('/creerprofil') }}">Créer profils</a></li>
                  <li><a class="nav-link {{ request()->is('paramcomposantes') ? 'active' : '' }}" href="{{ url('/paramcomposantes') }}"><span class="ab">Paramétrage composantes</span></a></li>
                  <li><a class="nav-link {{ in_array(request()->route()->getName(), $routesFacture) ? 'active' : '' }}" href="{{ route('facturesclasses') }}">Factures classes</a></li>
                  {{-- <li><a class="nav-link {{ request()->is('paiementdesnoninscrits') ? 'active' : '' }}" href="{{ url('/paiementdesnoninscrits') }}">Paiement des non inscrits</a></li> --}}
                  <li><a class="nav-link {{ request()->is('duplicatarecu') ? 'active' : '' }}" href="{{ url('/duplicatarecu') }}">Duplicata</a></li>
                </ul>
              </div>
            </li>
            <!-- Recalculer effectifs -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('recalculereffectif') ? 'active' : '' }}" href="{{ url('/recalculereffectifs') }}">
                Recalculer effectifs
              </a>
            </li>
            <!-- Discipline -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('discipline') || request()->is('discipline/*') ? 'active' : '' }}" href="{{ url('/discipline') }}">
                Discipline
              </a>
            </li>
            <!-- Éditions -->
            <li class="nav-item">
              <a class="nav-link {{ in_array(request()->route()->getName(), $routesEditions) ? 'active' : '' }}" href="{{ route('editions') }}">
                Éditions
              </a>
            </li>
            <!-- Archives -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('archive') ? 'active' : '' }}" href="{{ url('/archive') }}">
                Archives
              </a>
            </li>
          </ul>
        </div>
      </li>

      @php
      $routeparams = [
        'repartitionclassesparoperateur',
        'tabledesmatieres',
        'gestioncoefficient',
      ];
      $routesmanip = [
        'saisirnote',
        'enregistrer_notes',
      ];
      $routeSecurite = [
        'verrouillage',
      ];
      $routenew = [
        'bulletindenotes', 'attestationdemerite', 'printbulletindenotes', 'tableaudenotes', 'filtertableaunotes', 
      ];
      $routeseditions2 = [
        'editions2',
        'fichedenotesvierge',
        'relevesparmatiere',
        'relevespareleves',
        'recapitulatifdenotes',
        'tableauanalytiqueparmatiere',
        'resultatsparpromotion',
        'listedesmeritants',
      ];

      $activeExtractRoutes = ['extrairenote', 'extractnote'];

      $activeResultatRoutes = ['listeparmerite', 'imprimer.liste.merite'];

      @endphp

      <!-- Bloc Gestion des notes -->
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="{{ in_array(request()->route()->getName(), $routeparams) || in_array(request()->route()->getName(), $routesmanip) || in_array(request()->route()->getName(), $routeSecurite) ? 'true' : 'false' }}"
          aria-controls="form-elements">
          <i class="typcn typcn-film menu-icon"></i>
          <span class="menu-title">Gestion des notes</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ in_array(request()->route()->getName(), $routeparams) || in_array(request()->route()->getName(), $routesmanip) || in_array(request()->route()->getName(), $routeSecurite) ? 'show' : '' }}" id="form-elements" data-bs-parent="#parent-accordion">
          <ul class="nav flex-column sub-menu">
            <!-- Bloc Paramètres -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('Répartition des classes par opérateur') || request()->is('Table des matières') || request()->is('Table des coefficients') || in_array(request()->route()->getName(), $routeparams) ? 'active' : '' }}" 
                 data-bs-toggle="collapse" href="#params" 
                 aria-expanded="{{ request()->is('Répartition des classes par opérateur') || request()->is('Table des matières') || request()->is('Table des coefficients') || in_array(request()->route()->getName(), $routeparams) ? 'true' : 'false' }}"
                 aria-controls="params">
                Paramètres
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('Répartition des classes par opérateur') || request()->is('Table des matières') || request()->is('Table des coefficients') || in_array(request()->route()->getName(), $routeparams) ? 'show' : '' }}" id="params" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li><a class="nav-link {{ request()->is('Répartition des classes par opérateur') ? 'active' : '' }}" href="{{ url('/repartitionclassesparoperateur') }}"><span class="ab">Répartition des classes par opérateur</span></a></li>
                  <li><a class="nav-link {{ request()->is('Table des matières') ? 'active' : '' }}" href="{{ url('/tabledesmatieres') }}">Table des matières</a></li>
                  <li><a class="nav-link {{ request()->is('Table des coefficients') ? 'active' : '' }}" href="{{ url('/gestioncoefficient') }}">Table des coefficients</a></li>
                </ul>
              </div>
            </li>
            <!-- Bloc Manipulation des notes -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('Saisir et mises à jour des notes') || request()->is('Enrégistrer les résultats des examens') || request()->is('Vérifier les notes') || in_array(request()->route()->getName(), $routesmanip) ? 'active' : '' }}"
                 data-bs-toggle="collapse" href="#manip" aria-expanded="{{ request()->is('Saisir et mises à jour des notes') || request()->is('Enrégistrer les résultats des examens') || request()->is('Vérifier les notes') || in_array(request()->route()->getName(), $routesmanip) ? 'true' : 'false' }}" aria-controls="manip">
                Manipulation des notes
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('Saisir et mises à jour des notes') || request()->is('Enrégistrer les résultats des examens') || request()->is('Vérifier les notes') || in_array(request()->route()->getName(), $routesmanip) ? 'show' : '' }}" id="manip" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li><a class="nav-link {{ request()->is('Saisir et mises à jour des notes') ? 'active' : '' }}" href="{{ route('saisirnote') }}"><span class="ab">Saisir et mises à jour des notes</span></a></li>
                  <li><a class="nav-link {{ request()->is('Enrégistrer les résultats des examens') ? 'active' : '' }}" href="{{ url('/enregistrer_notes') }}"><span class="ab">Enrégistrer les résultats des examens</span></a></li>
                  <li><a class="nav-link {{ request()->is('Vérifier les notes') ? 'active' : '' }}" href="#">Vérifier les notes</a></li>
                </ul>
              </div>
            </li>
            <!-- Bloc Sécurité -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('Verrouillage') || request()->is('Déverrouillage') || in_array(request()->route()->getName(), $routeSecurite) ? 'active' : '' }}"
                 data-bs-toggle="collapse" href="#securite" aria-expanded="{{ request()->is('Verrouillage') || request()->is('Déverrouillage') || in_array(request()->route()->getName(), $routeSecurite) ? 'true' : 'false' }}" aria-controls="securite">
                Sécurité
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('Verrouillage') || request()->is('Déverrouillage') || in_array(request()->route()->getName(), $routeSecurite) ? 'show' : '' }}" id="securite" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li><a class="nav-link {{ request()->is('Verrouillage') ? 'active' : '' }}" href="{{ route('verrouillage') }}">Verrouillage</a></li>
                  <li><a class="nav-link {{ request()->is('Déverrouillage') ? 'active' : '' }}" href="#">Déverrouillage</a></li>
                </ul>
              </div>
            </li>
            <!-- Bloc Editions -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('Saisir et mises à jour des notes') || request()->is('Enrégistrer les résultats des examens') || request()->is('Vérifier les notes') || in_array(request()->route()->getName(), $routeseditions2) || in_array(request()->route()->getName(), $routenew) ? 'active' : '' }}"
                 data-bs-toggle="collapse" href="#edition" aria-expanded="{{ request()->is('Saisir et mises à jour des notes') || request()->is('Enrégistrer les résultats des examens') || request()->is('Vérifier les notes') || in_array(request()->route()->getName(), $routeseditions2) || in_array(request()->route()->getName(), $routenew) ? 'true' : 'false' }}" aria-controls="edition">
                Edition
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ request()->is('Tableau de notes') || request()->is('Bulletin de notes') || request()->is('Attestations de mérite') || in_array(request()->route()->getName(), $routeseditions2) || in_array(request()->route()->getName(), $routenew) ? 'show' : '' }}" id="edition" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li>
                    <a class="nav-link {{ request()->is('Tableau de notes') || request()->is('filtertableaunotes') || request()->is('filtertablenotes') ? 'active' : '' }}" href="{{ route('tableaudenotes') }}">Tableau de notes</a>
                  </li>
                  <li>
                    <a class="nav-link {{ request()->is('Bulletin de notes') || request()->is('printbulletindenotes') ? 'active' : '' }}" href="{{ route('bulletindenotes') }}">Bulletin de notes</a>
                  </li>
                  <li>
                    <a class="nav-link {{ request()->is('Attestations de mérite') ? 'active' : '' }}" href="{{ url('/attestationdemerite') }}">Attestations de mérite</a>
                  </li>
                  <li>
                    <a class="nav-link {{ in_array(request()->route()->getName(), $routeseditions2) ? 'active' : '' }}" href="{{ route('editions2') }}">Editions</a>
                  </li>
                </ul>
              </div>
            </li>
            <!-- Bloc Résultats -->
            <li class="nav-item">
              <a  class="nav-link {{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'active' : '' }}" data-bs-toggle="collapse" href="#resultats" aria-expanded="{{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'true' : 'false' }}" aria-controls="resultats">
                Résultats
                <i class="menu-arrow"></i>
              </a>  
              <div class="collapse {{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'show' : '' }}" id="resultats" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li><a class="nav-link  {{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'active' : '' }}"  href="{{ route('listeparmerite') }}">Liste par ordre de mérite</a></li>
                  <li><a class="nav-link {{ request()->is('Tableau analytique') ? 'active' : '' }}" href="#">Tableau analytique</a></li>
                  <li><a class="nav-link {{ request()->is('Rapports annuels') ? 'active' : '' }}" href="#">Rapports annuels</a></li>
                  <li><a class="nav-link {{ request()->is('Livrets scolaires') ? 'active' : '' }}" href="#">Livrets scolaires</a></li>
                </ul>
              </div>
            </li>

            <!-- Bloc Extraction -->
            <li class="nav-item">
              <a class="nav-link {{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'active' : '' }}" data-bs-toggle="collapse" href="#extract" aria-expanded="{{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'true' : 'false' }}" aria-controls="extract">
                Extraction
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse {{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'show' : '' }}" id="extract" data-bs-parent="#form-elements">
                <ul class="nav sub-menu">
                  <li>
                    <a class="nav-link {{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'active' : '' }}" href="{{ route('extrairenote') }}">
                      Exporter vers EducMaster
                    </a>
                  </li>
                  <li>
                    <a class="nav-link {{ request()->is('Exporter') ? 'active' : '' }}" href="#">
                      Exporter
                    </a>
                  </li>
                  <li>
                    <a class="nav-link {{ request()->is('Importer') ? 'active' : '' }}" href="#">
                      Importer
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </li>

      <!-- Lien vers Paramètres -->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/parametre') }}">
          <i class="typcn typcn-globe-outline menu-icon"></i>
          <span class="menu-title">Paramètres</span>
        </a>
      </li>
    </ul>
  </nav>
</div>

<style>
  .nav-link:hover .ab {
    animation: scroll-left 5s linear infinite;
  }

  @keyframes scroll-left {
    0% {
      transform: translateX(100%);
    }
    100% {
      transform: translateX(-100%);
    }
  }
</style>
