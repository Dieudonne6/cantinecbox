<nav class="sidebar sidebar-offcanvas" id="sidebar" style="max-width: 240px;">
  <ul class="nav">
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
               {{--<a class="nav-link {{ request()->is('reductioncollective') ? 'active' : '' }}" href="{{url('/reductioncollective')}}">Réductions collectives</a> --}} 
              </li>
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
            href="{{ url('/recalculereffectifs') }}">Recalculer effectifs</a>
          </li>
                                  {{-- Dicipline --}}
          <li class="nav-item"> <a class="nav-link {{ request()->is('discipline') ? 'active' : '' }}"
            href="{{ url('/discipline') }}">Discipline</a>
          </li>
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
          'journaldetailleaveccomposante',
          'journaldetaillesanscomposante'
          ]; // Liste des noms de routes associées à l'accueil
          @endphp
          <li class="nav-item">
            <a class="nav-link {{ in_array(request()->route()->getName(), $routeseditions) ? 'active' : '' }}"
              href="{{ route('editions') }}">Editions</a>
          </li>
          <li class="nav-item"> <a class="nav-link {{ request()->is('archive') ? 'active' : '' }}"
            href="{{ url('/archive') }}">Archives</a>
          </li>
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
                <a class="nav-link {{ request()->is('Répartition des classes par opérateur') ? 'active' : '' }}" href="{{ url('/repartitionclassesparoperateur') }}">Répartition des classes par opérateur</a>
              </li>
              <li>
                <a class="nav-link {{ request()->is('Table des matières') ? 'active' : '' }}" href="{{url('/tabledesmatieres')}}">Table des matières</a>
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
                <a class="nav-link {{ request()->is('Verrouillage') ? 'active' : '' }}" href="{{ route('verrouillage') }}">Verrouillage</a>
              </li>
              <li>
                <a class="nav-link {{ request()->is('Déverrouillage') ? 'active' : '' }}" href="#">Déverrouillage</a>
              </li>
            </ul>
          </li>
          {{-- Editions --}}
          @php
          $routeseditions2 = [
          'editions2',
          'fichedenotesvierge',
          'relevesparmatiere',
          'relevespareleves',
          'recapitulatifdenotes',
          'tableauanalytiqueparmatiere',
          'resultatsparpromotion',
          'listedesmeritants',
          ]; // Liste des noms de routes associées à l'édition gestion des notes
          @endphp
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
                <a class="nav-link {{ in_array(request()->route()->getName(), $routeseditions2) ? 'active' : '' }}"
                  href="{{ route('editions2') }}">Editions</a>
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
  </ul>
</nav>
      