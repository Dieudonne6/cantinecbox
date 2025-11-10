<div id="parent-accordion">
    <nav class="sidebar sidebar-offcanvas" id="sidebar" style="max-width: 255px;">
        <ul class="nav">
            @php
                $routesAccueil = ['Acceuil', 'inscrireeleve', 'modifiereleve', 'paiementeleve', 'majpaiementeleve', 'echeancier', 'profil', 'pagedetail'];
                $routesClass = ['tabledesclasses', 'enrclasse', 'modifierclasse'];
                $routesFacture = ['facturesclasses', 'detailfacturesclasses'];
                $routesEditions = [
                    'editions', 'listedeseleves', 'listedesclasses', 'listeselectiveeleve', 'eleveparclasse',
                    'certificatsolarite', 'etatdelacaisse', 'enquetesstatistiques', 'situationfinanciereglobale',
                    'etatdesrecouvrements', 'arriereconstate', 'journaldetailleaveccomposante', 'journaldetaillesanscomposante'
                ];
                $activeExtractRoutesInsc = ['importernote', 'import'];
                $routesbulletindePaie = ['rubriquesalaire', 'profilsagents'];
            @endphp

            <!-- Bloc Inscriptions & disciplines -->
            @canAccess('Acceuil')
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic"
                    aria-expanded="{{ in_array(request()->route()->getName(), $routesAccueil) ? 'true' : 'false' }}"
                    aria-controls="ui-basic">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Inscriptions<br>& disciplines</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ in_array(request()->route()->getName(), $routesAccueil) ? 'show' : '' }}"
                    id="ui-basic" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        @canAccess('Acceuil')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('Acceuil') ? 'active' : '' }}" href="{{ route('Acceuil') }}">Accueil</a>
                        </li>
                        @endcanAccess

                        @if(canAccess('typesclasses') || canAccess('series') || canAccess('promotions') || canAccess('tabledesclasses') || canAccess('groupes'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('typesclasses*') || request()->is('series*') || request()->is('promotions*') || request()->is('groupes*') || in_array(request()->route()->getName(), $routesClass) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#gestion-classes"
                                aria-expanded="{{ request()->is('typesclasses*') || request()->is('series*') || request()->is('promotions*') || request()->is('groupes*') || in_array(request()->route()->getName(), $routesClass) ? 'true' : 'false' }}"
                                aria-controls="gestion-classes">
                                Gestion des classes
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ request()->is('typesclasses*') || request()->is('series*') || request()->is('promotions*') || request()->is('groupes*') || in_array(request()->route()->getName(), $routesClass) ? 'show' : '' }}"
                                id="gestion-classes" data-bs-parent="#ui-basic">
                                <ul class="nav sub-menu">
                                    @canAccess('typesclasses')
                                    <li><a class="nav-link {{ request()->is('typesclasses*') ? 'active' : '' }}" href="{{ url('/typesclasses') }}">Types classes</a></li>
                                    @endcanAccess
                                    @canAccess('series')
                                    <li><a class="nav-link {{ request()->is('series*') ? 'active' : '' }}" href="{{ url('/series') }}">Séries</a></li>
                                    @endcanAccess
                                    @canAccess('promotions')
                                    <li><a class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}" href="{{ url('/promotions') }}">Promotions</a></li>
                                    @endcanAccess
                                    @canAccess('tabledesclasses')
                                    <li><a class="nav-link {{ in_array(request()->route()->getName(), $routesClass) ? 'active' : '' }}" href="{{ route('tabledesclasses') }}">Gestion des classes</a></li>
                                    @endcanAccess
                                    @canAccess('groupes')
                                    <li><a class="nav-link {{ request()->is('groupes*') ? 'active' : '' }}" href="{{ url('/groupes') }}">Grouper</a></li>
                                    @endcanAccess
                                </ul>
                            </div>
                        </li>
                        @endif

                        @canAccess('recalculereffectifs')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('recalculereffectifs') ? 'active' : '' }}" href="{{ url('/recalculereffectifs') }}">Recalculer effectifs</a>
                        </li>
                        @endcanAccess

                        @canAccess('discipline')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('discipline*') ? 'active' : '' }}" href="{{ url('/discipline') }}">
                                Discipline
                                @canOnlyView('discipline')
                                    <span class="badge badge-warning ms-1" style="font-size: 0.6em;">RO</span>
                                @endcanOnlyView
                            </a>
                        </li>
                        @endcanAccess

                        @canAccess('editions')
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesEditions) ? 'active' : '' }}" href="{{ route('editions') }}">Éditions</a>
                        </li>
                        @endcanAccess

                        @canAccess('archive')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('archive*') ? 'active' : '' }}" href="{{ url('/archive') }}">Archives</a>
                        </li>
                        @endcanAccess

                        @if(canAccess('exporternote') || canAccess('importernote'))
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $activeExtractRoutesInsc) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#extract-insc"
                                aria-expanded="{{ in_array(request()->route()->getName(), $activeExtractRoutesInsc) ? 'true' : 'false' }}"
                                aria-controls="extract-insc">
                                Extraction
                                <i class="menu-arrow"></i>
                            </a>

                            <!-- ici : data-bs-parent passé de "#form-elements" à "#ui-basic" -->
                            <div class="collapse {{ in_array(request()->route()->getName(), $activeExtractRoutesInsc) ? 'show' : '' }}"
                                id="extract-insc" data-bs-parent="#ui-basic">
                                <ul class="nav sub-menu">
                                    @canAccess('exporternote')
                                    <li><a class="nav-link {{ request()->routeIs('exporternote') ? 'active' : '' }}" href="{{ route('exporternote') }}">Exporter</a></li>
                                    @endcanAccess
                                    @canAccess('importernote')
                                    <li><a class="nav-link {{ request()->routeIs('importernote') ? 'active' : '' }}" href="{{ route('importernote') }}">Importer</a></li>
                                    @endcanAccess
                                </ul>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endcanAccess

            <!-- Bloc Scolarité -->
            @if(canAccess('creerprofil') || canAccess('paramcomposantes') || canAccess('facturesclasses') || canAccess('duplicatarecu') || canAccess('facturesimpleduplicata'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#scolarite" aria-expanded="false" aria-controls="scolarite">
                    <i class="typcn typcn-film menu-icon"></i>
                    <span class="menu-title">Scolarité</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="scolarite" data-bs-parent="#parent-accordion">
                    <ul class="nav flex-column sub-menu">
                        @canAccess('creerprofil')
                        <li><a class="nav-link {{ request()->is('creerprofil*') ? 'active' : '' }}" href="{{ url('/creerprofil') }}">Créer profils</a></li>
                        @endcanAccess
                        @canAccess('paramcomposantes')
                        <li><a class="nav-link {{ request()->is('paramcomposantes*') ? 'active' : '' }}" href="{{ url('/paramcomposantes') }}">Paramétrage composantes</a></li>
                        @endcanAccess
                        @canAccess('facturesclasses')
                        <li><a class="nav-link {{ in_array(request()->route()->getName(), $routesFacture) ? 'active' : '' }}" href="{{ route('facturesclasses') }}">Factures classes</a></li>
                        @endcanAccess
                        @canAccess('duplicatarecu')
                        <li><a class="nav-link {{ request()->is('duplicatarecu') ? 'active' : '' }}" href="{{ url('/duplicatarecu') }}">Duplicata fac normalisée</a></li>
                        @endcanAccess
                        {{-- @canAccess('duplicatarecufacturesimple') --}}
                        <li><a class="nav-link {{ request()->is('facturesimpleduplicata') ? 'active' : '' }}" href="{{ url('/facturesimpleduplicata') }}">Duplicata fac non normalisée </a></li>
                        {{-- @endcanAccess --}}

                        @php
                            $routesAvoirFacPaiementScolarit = ['listefacturescolarite', 'avoirfacturepaiescolarite', 'avoirfacturescolarite'];
                        @endphp
                        <li>
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesAvoirFacPaiementScolarit) ? 'active' : '' }}"
                                href="{{ url('/listefacturescolarite') }}">
                                Mise à jour des Paiements
                            </a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->is('editionscolarite*') ? 'active' : '' }}" href="{{ url('/editionscolarite') }}">Éditions</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Bloc Cantine -->
            @if(canAccess('listecontrat') || canAccess('etat') || canAccess('listefacture') || canAccess('listeFacturesAvoir') || canAccess('duplicatafacture'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#Cantine" aria-expanded="false" aria-controls="Cantine">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Cantine</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Cantine" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        @canAccess('listecontrat')
                        <li class="nav-item">
                            @php $routesClassCantine = ['listecontrat', 'paiementcontrat', 'eleve']; @endphp
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesClassCantine) ? 'active' : '' }}" href="{{ route('listecontrat') }}">Accueil</a>
                        </li>
                        @endcanAccess

                        @canAccess('etat')
                        <li class="nav-item">
                            @php $routesEtat = ['etat', 'traitementetatpaiement', 'filteretat']; @endphp
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesEtat) ? 'active' : '' }}" href="{{ route('etat') }}">Etats</a>
                        </li>
                        @endcanAccess

                        @canAccess('listefacture')
                        <li class="nav-item">
                            @php
                                $routesAvoirFacPaiement = ['listefacture', 'avoirfacturepaie', 'avoirfacture', 'listefacinscription', 'avoirfactureinscription', 'avoirfactureinscri'];
                            @endphp
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesAvoirFacPaiement) ? 'active' : '' }}" href="{{ url('/listefacture') }}">
                                Mise à jour des Paiements/Inscriptions
                            </a>
                        </li>
                        @endcanAccess

                        @canAccess('listeFacturesAvoir')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('listeFacturesAvoir') ? 'active' : '' }}" href="{{ url('/listeFacturesAvoir') }}">Liste des Factures d'avoir</a>
                        </li>
                        @endcanAccess

                        @canAccess('duplicatafacture')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('duplicatafacture*') || request()->is('filterduplicata*') ? 'active' : '' }}" href="{{ url('/duplicatafacture') }}">Duplicata facture</a>
                        </li>
                        @endcanAccess
                    </ul>
                </div>
            </li>
            @endif

            <!-- Bloc Notes et Bulletin -->
            @php
                $routeparams = ['repartitionclassesparoperateur', 'tabledesmatieres', 'gestioncoefficient'];
                $routesmanip = ['saisirnote', 'enregistrer_notes'];
                $routeSecurite = ['verrouillage'];
                $routenew = ['bulletindenotes', 'attestationdemerite', 'printbulletindenotes', 'tableaudenotes', 'filtertableaunotes'];
                $routeseditions2 = [
                    'editions2', 'fichedenotesvierge', 'relevesparmatiere', 'relevespareleves',
                    'recapitulatifdenotes', 'tableauanalytiqueparmatiere', 'resultatsparpromotion', 'listedesmeritants'
                ];
                $activeExtractRoutes = ['extrairenote', 'extractnote'];
                $activeResultatRoutes = ['listeparmerite', 'imprimer.liste.merite'];
            @endphp

            @if(
                canAccess('repartitionclassesparoperateur') ||
                canAccess('tabledesmatieres') ||
                canAccess('gestioncoefficient') ||
                canAccess('saisirnote') ||
                canAccess('enregistrer_notes') ||
                canAccess('verrouillage') ||
                canAccess('tableaudenotes') ||
                canAccess('bulletindenotes') ||
                canAccess('attestationdemerite') ||
                canAccess('editions2')
            )
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#form-elements"
                    aria-expanded="{{ in_array(request()->route()->getName(), array_merge($routeparams, $routesmanip, $routeSecurite)) ? 'true' : 'false' }}"
                    aria-controls="form-elements">
                    <i class="typcn typcn-film menu-icon"></i>
                    <span class="menu-title">Notes et Bulletin</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ in_array(request()->route()->getName(), array_merge($routeparams, $routesmanip, $routeSecurite)) ? 'show' : '' }}"
                    id="form-elements" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">

                        <!-- Paramètres -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routeparams) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#params-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), $routeparams) ? 'true' : 'false' }}"
                                aria-controls="params-notes">
                                Paramètres
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), $routeparams) ? 'show' : '' }}"
                                id="params-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    @canAccess('repartitionclassesparoperateur')
                                    <li><a class="nav-link {{ request()->routeIs('repartitionclassesparoperateur') ? 'active' : '' }}" href="{{ url('/repartitionclassesparoperateur') }}">Répartition des classes par opérateur</a></li>
                                    @endcanAccess
                                    @canAccess('tabledesmatieres')
                                    <li><a class="nav-link {{ request()->routeIs('tabledesmatieres') ? 'active' : '' }}" href="{{ url('/tabledesmatieres') }}">Table des matières</a></li>
                                    @endcanAccess
                                    @canAccess('gestioncoefficient')
                                    <li><a class="nav-link {{ request()->routeIs('gestioncoefficient') ? 'active' : '' }}" href="{{ url('/gestioncoefficient') }}">Table des coefficients</a></li>
                                    @endcanAccess
                                </ul>
                            </div>
                        </li>

                        <!-- Manipulation des notes -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesmanip) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#manip-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), $routesmanip) ? 'true' : 'false' }}"
                                aria-controls="manip-notes">
                                Manipulation des notes
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), $routesmanip) ? 'show' : '' }}"
                                id="manip-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    @canAccess('saisirnote')
                                    <li><a class="nav-link {{ request()->routeIs('saisirnote') ? 'active' : '' }}" href="{{ route('saisirnote') }}">Saisir et mises à jour des notes</a></li>
                                    @endcanAccess
                                    @canAccess('enregistrer_notes')
                                    <li><a class="nav-link {{ request()->routeIs('enregistrer_notes') ? 'active' : '' }}" href="{{ url('/enregistrer_notes') }}">Enregistrer les résultats des examens</a></li>
                                    @endcanAccess
                                    {{-- <li><a class="nav-link disabled" href="#">Vérifier les notes</a></li> --}}
                                </ul>
                            </div>
                        </li>

                        <!-- Sécurité -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routeSecurite) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#securite-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), $routeSecurite) ? 'true' : 'false' }}"
                                aria-controls="securite-notes">
                                Sécurité
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), $routeSecurite) ? 'show' : '' }}"
                                id="securite-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    @canAccess('verrouillage')
                                    <li><a class="nav-link {{ request()->routeIs('verrouillage') ? 'active' : '' }}" href="{{ route('verrouillage') }}">Verrouillage</a></li>
                                    @endcanAccess
                                    {{-- <li><a class="nav-link disabled" href="#">Déverrouillage</a></li> --}}
                                </ul>
                            </div>
                        </li>

                        <!-- Éditions -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), array_merge($routenew, $routeseditions2)) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#edition-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), array_merge($routenew, $routeseditions2)) ? 'true' : 'false' }}"
                                aria-controls="edition-notes">
                                Édition
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), array_merge($routenew, $routeseditions2)) ? 'show' : '' }}"
                                id="edition-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    @canAccess('tableaudenotes')
                                    <li><a class="nav-link {{ request()->routeIs('tableaudenotes', 'filtertableaunotes', 'filtertablenotes') ? 'active' : '' }}" href="{{ route('tableaudenotes') }}">Tableau de notes</a></li>
                                    @endcanAccess
                                    @canAccess('bulletindenotes')
                                    <li><a class="nav-link {{ request()->routeIs('bulletindenotes', 'printbulletindenotes') ? 'active' : '' }}" href="{{ route('bulletindenotes') }}">Bulletin de notes</a></li>
                                    @endcanAccess
                                    @canAccess('attestationdemerite')
                                    <li><a class="nav-link {{ request()->routeIs('attestationdemerite') ? 'active' : '' }}" href="{{ url('/attestationdemerite') }}">Attestations de mérite</a></li>
                                    @endcanAccess
                                    @canAccess('editions2')
                                    <li><a class="nav-link {{ in_array(request()->route()->getName(), $routeseditions2) ? 'active' : '' }}" href="{{ route('editions2') }}">Éditions</a></li>
                                    @endcanAccess
                                </ul>
                            </div>
                        </li>

                        <!-- Résultats -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#resultats-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'true' : 'false' }}"
                                aria-controls="resultats-notes">
                                Résultats
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), $activeResultatRoutes) ? 'show' : '' }}"
                                id="resultats-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link {{ request()->routeIs('listeparmerite', 'imprimer.liste.merite') ? 'active' : '' }}" href="{{ route('listeparmerite') }}">Liste par ordre de mérite</a></li>
                                    <li><a class="nav-link" href="{{ route('tableauanalytique') }}">Tableau analytique</a></li>
                                    <li><a class="nav-link" href="{{ route('rapportannuel') }}">Rapports annuels</a></li>
                                    {{-- <li><a class="nav-link disabled" href="#">Livrets scolaires</a></li> --}}
                                </ul>
                            </div>
                        </li>

                        <!-- Extraction -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#extract-notes"
                                aria-expanded="{{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'true' : 'false' }}"
                                aria-controls="extract-notes">
                                Extraction
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ in_array(request()->route()->getName(), $activeExtractRoutes) ? 'show' : '' }}"
                                id="extract-notes" data-bs-parent="#form-elements">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link {{ request()->routeIs('extrairenote') ? 'active' : '' }}" href="{{ route('extrairenote') }}">Exporter vers EducMaster</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Bloc Ressources Humaines -->
            @if(canAccess('addAgent') || canAccess('updatePersonnel'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#menuPersonnel" aria-expanded="false" aria-controls="menuPersonnel">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Ressources <br> Humaines</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="menuPersonnel" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('updatePersonnel*') || request()->is('addAgent*') ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#personnel-submenu"
                                aria-expanded="{{ request()->is('updatePersonnel*') || request()->is('addAgent*') ? 'true' : 'false' }}"
                                aria-controls="personnel-submenu">
                                Régistre Enseignants
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse {{ request()->is('updatePersonnel*') || request()->is('addAgent*') ? 'show' : '' }}"
                                id="personnel-submenu" data-bs-parent="#menuPersonnel">
                                <ul class="nav sub-menu">
                                    @canAccess('addAgent')
                                    <li><a class="nav-link {{ request()->is('addAgent*') ? 'active' : '' }}" href="{{ url('/addAgent') }}">Type d'agent</a></li>
                                    @endcanAccess
                                    @canAccess('updatePersonnel')
                                    <li><a class="nav-link {{ request()->is('updatePersonnel*') ? 'active' : '' }}" href="{{ url('/updatePersonnel') }}">Mise à jour du Personnel</a></li>
                                    @endcanAccess
                                </ul>
                            </div>
                        </li>

                        <!-- Sous-menu : Bulletins de Paie -->
                        <li class="nav-item">
                            <a class="nav-link {{ in_array(request()->route()->getName(), $routesbulletindePaie) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#bulletinsPaie"
                                aria-expanded="{{ in_array(request()->route()->getName(), $routesbulletindePaie) ? 'true' : 'false' }}"
                                aria-controls="bulletinsPaie">
                                Bulletins de Paie
                                <i class="menu-arrow"></i>
                            </a>

                            <div class="collapse {{ in_array(request()->route()->getName(), $routesbulletindePaie) ? 'show' : '' }}" id="bulletinsPaie" data-bs-parent="#menuPersonnel">
                                <ul class="nav flex-column sub-menu">
                                    <li><a class="nav-link" href="{{ url('/rubriquesalaire') }}">Rubriques Salaire</a></li>
                                    <li><a class="nav-link" href="{{ url('/profilsagents') }}">Créer Profils</a></li>
                                    <li><a class="nav-link" href="#">Taux horaires</a></li>
                                    <li><a class="nav-link" href="#">Avance sur salaire</a></li>
                                    <li><a class="nav-link" href="#">Bulletins et États</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Sous-menu : Emploi du Temps -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('creeremploitemps') || request()->is('Emploidutempsautomatique') || in_array(request()->route()->getName(), ['saisiremploitemps', 'miseajouremploitemps']) ? 'active' : '' }}"
                                data-bs-toggle="collapse" href="#emploiDuTemps"
                                aria-expanded="{{ request()->is('creeremploitemps') || request()->is('Emploidutempsautomatique') || in_array(request()->route()->getName(), ['saisiremploitemps', 'miseajouremploitemps']) ? 'true' : 'false' }}"
                                aria-controls="emploiDuTemps">
                                Emploi du Temps
                                <i class="menu-arrow"></i>
                            </a>

                            <div class="collapse {{ request()->is('creeremploitemps') || request()->is('Emploidutempsautomatique') || in_array(request()->route()->getName(), ['saisiremploitemps', 'miseajouremploitemps']) ? 'show' : '' }}"
                                id="emploiDuTemps" data-bs-parent="#menuPersonnel">
                                <ul class="nav flex-column sub-menu">

                                    <!-- Premier sous-menu : Créer Emploi du Temps -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse" href="#creerSousMenu" aria-expanded="false" aria-controls="creerSousMenu">
                                            Configurer
                                            <i class="menu-arrow"></i>
                                        </a>

                                        <div class="collapse" id="creerSousMenu" data-bs-parent="#emploiDuTemps">
                                            <ul class="nav flex-column sub-menu">
                                                <li><a class="nav-link" href="{{ route('configsalles') }}">Configurer Salles</a></li>
                                                <li><a class="nav-link" href="{{ route('configquotahoraires') }}">Configurer quota. horaires</a></li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Deuxième sous-menu : Consulter Emploi du Temps -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('Emploidutempsautomatique') ? 'active' : '' }}" href="{{ route('emploidutempsautomatique') }}">
                                            Emploi du Temps automatique
                                        </a>
                                    </li>

                                    <!-- Troisième sous-menu : Saisir emplois du temps -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ in_array(request()->route()->getName(), ['saisiremploitemps', 'miseajouremploitemps']) ? 'active' : '' }}" href="{{ route('saisiremploitemps') }}">
                                            Saisir un emplois du temps
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>

                        <!-- Sous-menu : Édition -->
                        <li class="nav-item">
                            <a class="nav-link" href="#editionrh">Édition</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Bloc Ressources Materielles -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#RessourceMaterielle" aria-expanded="false" aria-controls="RessourceMaterielle">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Ressources <br> Materielles</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="RessourceMaterielle" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        <!-- À compléter selon les besoins -->
                    </ul>
                </div>
            </li>

            <!-- Bloc Budget et Finances -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#budgetetFinances" aria-expanded="false" aria-controls="budgetetFinances">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Budget et Finances</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="budgetetFinances" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#params-budget" aria-controls="params-budget">
                                Paramètres
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="params-budget" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link" href="#">Paramétrage</a></li>
                                    <li><a class="nav-link " href="#"><span
                                                class="ab">Parametrage</span></a></li>
                                    {{-- <li><a class="nav-link {{ request()->is('Table des matières') ? 'active' : '' }}" href="{{ url('/tabledesmatieres') }}">Table des matières</a></li>
                                    <li><a class="nav-link {{ request()->is('Table des coefficients') ? 'active' : '' }}" href="{{ url('/gestioncoefficient') }}">Table des coefficients</a></li> --}}
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#executionBudget" aria-controls="executionBudget">
                                Exécution du Budget
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="executionBudget" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link" href="#">Exécution</a></li>
                                    <li><a class="nav-link" href="#">Opérations Bancaires</a></li>
                                    <li><a class="nav-link" href="#">Valider le Brouillard</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#previsionBudgetaire" aria-controls="previsionBudgetaire">
                                Prévision Budgétaire
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="previsionBudgetaire" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link"
                                            href="#"><span
                                                class="ab">Plan Comptable</span></a></li>
                                    <li><a class="nav-link"
                                            href="#">Mise en Place</a></li>
                                    <li><a class="nav-link"
                                            href="#">Décision Modificatives</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#Edition"
                                aria-expanded="" aria-controls="Edition">
                                Edition
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="Edition-budget" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link"
                                            href="#"><span
                                                class="ab">Suivi des Operations par Compte</span></a></li>
                                    <li><a class="nav-link"
                                            href="#">Editions</a></li>

                                </ul>
                            </div>
                        </li>

                        
                        <!-- Bloc Extraction -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#Extraction"
                                aria-expanded="" aria-controls="Extraction">
                                Extraction
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="Extraction-budget" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link"
                                            href="#"><span
                                                class="ab">Exporter</span></a></li>

                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#Integrité"
                                aria-expanded="" aria-controls="Integrité">
                                Integrité
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="Integrite-budget" data-bs-parent="#budgetetFinances">
                                <ul class="nav sub-menu">
                                    <li><a class="nav-link"
                                            href="#"><span
                                                class="ab">Verouillage/Deverouillage</span></a></li>
                                    <li><a class="nav-link"
                                            href="#">Cloture de mois</a></li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Paramètres -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#parametreSubmenu"
                    aria-expanded="{{ request()->routeIs(
                        'parametrecantine',
                        'parametre.tables',
                        'parametre.bornes',
                        'parametre.opouverture',
                        'parametre.configimprimante',
                        'parametre.changementtrimestre',
                        'admin.roles.index'
                    ) ? 'true' : 'false' }}"
                    aria-controls="parametreSubmenu">
                    <i class="typcn typcn-globe-outline menu-icon"></i>
                    <span class="menu-title">Paramètres</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ request()->routeIs(
                    'parametrecantine',
                    'parametre.tables',
                    'parametre.bornes',
                    'parametre.opouverture',
                    'parametre.configimprimante',
                    'parametre.changementtrimestre',
                    'admin.roles.index'
                ) ? 'show' : '' }}"
                    id="parametreSubmenu" data-bs-parent="#parent-accordion" style="margin-left: 2rem !important">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametrecantine') ? 'active' : '' }}" href="{{ route('parametrecantine') }}">Cantine</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametre.tables') ? 'active' : '' }}" href="{{ route('parametre.tables') }}">Tables des paramètres</a>
                        </li>
                        @hasPermission('admin/roles.manage')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                Gestion des Rôles
                            </a>
                        </li>
                        @endhasPermission
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametre.bornes') ? 'active' : '' }}" href="{{ route('parametre.bornes') }}">Modifier bornes exercice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametre.opouverture') ? 'active' : '' }}" href="{{ route('parametre.opouverture') }}">Op. Ouverture</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametre.configimprimante') ? 'active' : '' }}" href="{{ route('parametre.configimprimante') }}">Configurer imprimante</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parametre.changementtrimestre') ? 'active' : '' }}" href="{{ route('parametre.changementtrimestre') }}">Changement de trimestre</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>

<style>
    .nav-link:hover .ab {
        animation: scroll-left 5s linear infinite;
    }

    .ab:hover {
        animation: scroll-left 5s linear infinite;
    }

    @keyframes scroll-left {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>