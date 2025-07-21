@extends('layouts.master')
@section('content')

<div class="card">
  <div class="card-header position-relative">
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
      <i class="fas fa-arrow-left"></i> Retour
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="card-body">
    <div class="row">

      <!-- Contenu principal : onglets et formulaires -->
      <div class="col-md-12">

          <!-- Onglets -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="nav-ident-tab" data-bs-toggle="tab" data-bs-target="#pane-identification" type="button" role="tab" aria-controls="pane-identification" aria-selected="true">
                Identification
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="nav-param-tab" data-bs-toggle="tab" data-bs-target="#pane-parametres" type="button" role="tab" aria-controls="pane-parametres" aria-selected="false">
                Paramètres Généraux
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="nav-messages-tab" data-bs-toggle="tab" data-bs-target="#pane-entetes-messages" type="button" role="tab" aria-controls="pane-entetes-messages" aria-selected="false">
                Entêtes et Messages
              </button>
            </li>
          </ul>

          <!-- Contenu des onglets -->
          <div class="tab-content mt-3">

            <!-- Identification -->
            <div class="tab-pane fade show active" id="pane-identification" role="tabpanel" aria-labelledby="nav-ident-tab">
              <div class="card">
                <div class="card-body">
                  <form action="{{ route('params2.updateIdentification') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gx-4 gy-3">
                      <!-- Nom et Code -->
                      <div class="col-md-7">
                        <label class="form-label">Nom établissement</label>
                        <input type="text" name="nom_etablissement" class="form-control-sm"  value="{{ old('nom_etablissement', $settings->NOMETAB ?? '') }}">
                      </div>
                      <div class="col-md-5">
                        <label class="form-label">Code</label>
                        <input type="text" name="code_etablissement" class="form-control-sm bg-warning text-end" value="{{ old('code_etablissement', $settings->CodeSITE ?? '') }}">
                      </div>

                      <!-- Logos -->
                      <div class="col-12 d-flex gap-3">
                        {{-- Logo gauche --}}
                        <div class="card flex-fill text-center">
                          <div class="card-header">Logo gauche</div>
                          <div class="card-body">
                            @if(isset($settings->logoimage1))
                              <img  src="{{ asset('storage/' . $settings->logoimage1) }}"  class="img-fluid mb-2"  style="max-height:160px"  alt="logo gauche">
                            @endif
                            <input  type="file"  name="logo_gauche"  class="form-control-sm">
                          </div>
                        </div>

                        {{-- Logo droite --}}
                        <div class="card flex-fill text-center">
                          <div class="card-header">Logo droite</div>
                          <div class="card-body">
                            @if(isset($settings->LOGO1))
                              <img  src="{{ asset('storage/' . $settings->LOGO1) }}"  class="img-fluid mb-2"  style="max-height:160px"  alt="logo droite">
                            @endif
                            <input  type="file"  name="logo_droit"  class="form-control-sm">
                          </div>
                        </div>
                      </div>

                      <!-- Types d'établissement -->
                      @php
                        $typeetabArray = str_split($settings->Typeetab ?? '');
                      @endphp

                      <div class="col-md-8">
                        <fieldset class="border rounded p-3">
                          <legend class="w-auto px-2">Type établissement</legend>
                          <div class="row gy-2">
                            <div class="col-12 border rounded bg-light p-3">
                              <div class="row">
                                <!-- Ligne 1 : A, C, D, E -->
                                <div class="col-12 d-flex flex-wrap mb-2">
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="prescolaire" value="A"
                                      {{ in_array('A', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="prescolaire">Prescolaire</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="college_general" value="C"
                                      {{ in_array('C', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="college_general">Collège Général</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="lycee_general" value="D"
                                      {{ in_array('D', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lycee_general">Lycée Général</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="college_technique" value="E"
                                      {{ in_array('E', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="college_technique">Collège Technique</label>
                                  </div>
                                </div>

                                <!-- Ligne 2 : B, F, G, H -->
                                <div class="col-12 d-flex flex-wrap">
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="primaire" value="B"
                                      {{ in_array('B', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="primaire">Primaire</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="lycee_technique" value="F"
                                      {{ in_array('F', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lycee_technique">Lycée Technique</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="formation_pro" value="G"
                                      {{ in_array('G', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="formation_pro">Formation Prof.</label>
                                  </div>
                                  <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="checkbox" name="Typeetab[]" id="superieur" value="H"
                                      {{ in_array('H', $typeetabArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="superieur">Supérieur</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                      </div>

                      <!-- Statut et Adresse -->
                      <div class="col-md-4">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                          @foreach(['Public','Prive','Particulier'] as $Secteur)
                            <option value="{{ $Secteur }}" {{ old('Secteur', $settings->Secteur ?? '') === $Secteur ? 'selected' : '' }}>{{ $Secteur }}</option>
                          @endforeach
                        </select>

                        <label class="form-label mt-3">Adresse</label>
                        <input type="text" name="adresse" class="form-control-sm" value="{{ old('adresse', $settings->ADRESSE ?? '') }}">
                        </br>
                        <label class="form-label mt-3">Ville</label>
                        <input type="text" name="ville" class="form-control-sm" value="{{ old('ville', $settings->VILLE ?? '') }}">
                      </div>

                      <div class="card mb-4">
                        <div class="card-header fw-bold">Équipe administrative</div>
                        <div class="card-body">
                          <div class="row g-3 align-items-center">

                            <!-- Département -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Département</label>
                              <input type="text" name="departement" class="form-control" value="{{ old('departement', $settings->DEPARTEMEN ?? '') }}">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">E‑mail</label>
                              <input type="email" name="email" class="form-control" value="{{ old('email', $settings->EMAIL ?? '') }}">
                            </div>

                            <!-- Directeur 1 -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Directeur 1</label>
                              <input type="text" name="directeur1" class="form-control" value="{{ old('directeur1', $settings->NOMDIRECT ?? '') }}">
                            </div>
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Titre</label>
                              <input type="text" name="titre_directeur1" class="form-control" value="{{ old('titre_directeur1', $settings->TITRE ?? '') }}">
                            </div>

                            <!-- Directeur 2 -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Directeur 2</label>
                              <input type="text" name="directeur2" class="form-control" value="{{ old('directeur2', $settings->NOMDIRECT2 ?? '') }}">
                            </div>
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Titre</label>
                              <input type="text" name="titre_directeur2" class="form-control" value="{{ old('titre_directeur2', $settings->TITRE2 ?? '') }}">
                            </div>

                            <!-- Directeur 3 -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Directeur 3</label>
                              <input type="text" name="directeur3" class="form-control" value="{{ old('directeur3', $settings->NOMDIRECT3 ?? '') }}">
                            </div>
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Titre</label>
                              <input type="text" name="titre_directeur3" class="form-control" value="{{ old('titre_directeur3', $settings->TITRE3 ?? '') }}">
                            </div>

                            <!-- Censeur -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Censeur</label>
                              <input type="text" name="censeur" class="form-control" value="{{ old('censeur', $settings->NOMCENSEUR ?? '') }}">
                            </div>
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Titre</label>
                              <input type="text" name="titre_censeur" class="form-control" value="{{ old('titre_censeur', $settings->TITRECENSEUR ?? '') }}">
                            </div>

                            <!-- Comptable / Intendant -->
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Comptable</label>
                              <input type="text" name="comptable" class="form-control" value="{{ old('comptable', $settings->NOMINTEND ?? '') }}">
                            </div>
                            <div class="col-md-6 d-flex">
                              <label class="col-form-label me-2" style="min-width: 120px;">Titre</label>
                              <input type="text" name="titre_comptable" class="form-control" value="{{ old('titre_comptable', $settings->TITREINTENDANT ?? '') }}">
                            </div>

                            <!-- Devise -->
                            <div class="col-md-12 d-flex">
                              <label class="col-form-label me-2" style="min-width: 160px;">Devise de l’établissement</label>
                              <input type="text" name="devise" class="form-control" value="{{ old('devise', $settings->DeviseEtab ?? '') }}">
                            </div>

                          </div>
                        </div>
                      </div>

                      <!-- Actions -->
                      <div class="col-12 text-end mt-4">
                        <a href="{{ route('appreciations.edit') }}" class="btn btn-primary">Appréciation</a>
                        <button type="submit" class="btn btn-success me-2">Enregistrer</button>
                        <button type="reset" class="btn btn-secondary">Annuler</button>
                      </div>

                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Paramètres Généraux -->
            <div class="tab-pane fade" id="pane-parametres" role="tabpanel" aria-labelledby="nav-param-tab">
              <!-- Contenu Paramètres Généraux -->
              <div class="row gx-3 gy-3 mt-2">
                <form>
                    <div class="row">                     
                      <div class="col-md-8">
                        <div class="row">
                          @php
                            $libelles = [
                              'Libellé scolarité' => ['field' => 'Scolarité', 'montant' => $settings->MTS ?? 0],
                              'Libellé frais annexes 1' => ['field' => $settings->LIBELF1 ?? '', 'montant' => $settings->MT1 ?? 0],
                              'Libellé frais annexes 2' => ['field' => $settings->LIBELF2 ?? '', 'montant' => $settings->MT2 ?? 0],
                              'Libellé frais annexes 3' => ['field' => $settings->LIBELF3 ?? '', 'montant' => $settings->MT3 ?? 0],
                              'Libellé frais annexes 4' => ['field' => $settings->LIBELF4 ?? '', 'montant' => $settings->MT4 ?? 0],
                            ];
                          @endphp

                          @foreach($libelles as $label => $info)
                            <div class="col-md-12 mb-2 d-flex align-items-center">
                              <label class="me-2" style="min-width: 220px;">{{ $label }} :</label>
                              <input type="text" name="libel[]" class="form-control form-control-sm me-3" style="max-width: 300px;" value="{{ $info['field'] }}">
                              <label class="me-2">Montant :</label>
                              <input type="number" name="montant[]" class="form-control form-control-sm" style="width: 100px;" value="{{ $info['montant'] }}">
                            </div>
                          @endforeach
                        </div>
                      </div>

                      <div class="col-md-4 bg-warning-subtle p-3 rounded">
                        <input type="text" class="form-control form-control-sm mb-3 text-center fw-bold bg-warning-subtle border-0" value="Échéancier standard" readonly>

                        <div class="mb-3 d-flex align-items-center">
                          <label class="me-2" style="min-width: 180px;">Date 1er paiement standard :</label>
                          <input type="date" name="date1" class="form-control form-control-sm" value="{{ $settings->Date1erPaie_Standard ?? '' }}" style="max-width: 170px;">
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                          <label class="me-2" style="min-width: 180px;">Périodicité standard (mois) :</label>
                          <input type="number" name="periodicite" class="form-control form-control-sm" value="{{ $settings->Periodicite_Standard ?? 0 }}" style="max-width: 100px;">
                        </div>

                        <div class="form-check mb-3">
                          <input class="me-2 form-check-input" type="checkbox" name="echeancier_frais" {{ ($settings->Echeancier_tous_frais ?? false) ? 'checked' : '' }} id="fraisCheck">
                          <label class="form-check-label" for="fraisCheck">Échéancier prend en compte tous les frais</label>
                        </div>

                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 180px;">Tranche 1 :</label>
                          <input type="number" name="t1" class="form-control form-control-sm" value="{{ $settings->pcen1_standard ?? 0 }}" style="max-width: 100px;">
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 180px;">Tranche 2 :</label>
                          <input type="number" name="t2" class="form-control form-control-sm" value="{{ $settings->pcen2_standard ?? 0 }}" style="max-width: 100px;">
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 180px;">Tranche 3 :</label>
                          <input type="number" name="t3" class="form-control form-control-sm" value="{{ $settings->pcent3_standard ?? 0 }}" style="max-width: 100px;">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <!-- Colonne gauche -->
                      <div class="col-md-8">
                        <div class="row">
                          <!-- Type de matricule dans un cadre -->
                          <div class="col-md-4">
                            <fieldset class="border rounded p-3 mb-3 h-100">
                              <legend class="w-auto fs-6 px-2">Type de matricule</legend>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type_matricule" value="manuel"
                                  {{ $settings->TYPEMATRI === 'manuel' ? 'checked' : '' }}>
                                <label class="form-check-label">Manuel</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type_matricule" value="auto"
                                  {{ $settings->TYPEMATRI === 'auto' ? 'checked' : '' }}>
                                <label class="form-check-label">Automatique</label>
                              </div>
                            </fieldset>
                          </div>


                          <!-- Periodicité et Absences -->
                          <div class="col-md-8">
                            <div class="mb-3 d-flex align-items-center">
                              <label class="me-2" style="min-width: 200px;">Periodicité :</label>
                              <select class="form-select form-select-sm" style="max-width: 200px;" name="TYPEAN">
                                <option value="Semestrielle" {{ $settings->TYPEAN === 'Semestrielle' ? 'selected' : '' }}>Semestrielle</option>
                                <option value="Trimestrielle" {{ $settings->TYPEAN === 'Trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                              </select>
                            </div>

                            <div class="mb-1 d-flex align-items-center">
                              <label class="me-2" style="min-width: 200px;">Nb d'absences matière autorisé :</label>
                              <input type="number" name="NMININOTES" class="form-control form-control-sm" value="{{ $settings->NMININOTES }}" style="max-width: 100px;">
                            </div>

                            <small class="text-danger ms-2 d-block">Mettre -1 pour désactiver cette option</small>
                          </div>
                        </div>
                      </div>

                      <!-- Colonne droite -->
                      <div class="col-md-4">
                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 200px;">Dernière Attestation :</label>
                          <input type="number" name="NUMDERATT" class="form-control form-control-sm" value="{{ $settings->NUMDERATT }}" style="max-width: 100px;" readonly>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 200px;">Dernière Carte :</label>
                          <input type="number" name="NUMDERCARTE" class="form-control form-control-sm" value="{{ $settings->NUMDERCARTE }}" style="max-width: 100px;" readonly>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                          <label class="me-2" style="min-width: 200px;">Dernier Certificat :</label>
                          <input type="number" name="NUMDERCERTIF" class="form-control form-control-sm" value="{{ $settings->NUMDERCERTIF }}" style="max-width: 100px;" readonly>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <!-- Bloc Mode de répartition -->
                      <div class="col-md-6">
                        <fieldset class="border rounded p-3 mb-3 h-100">
                          <legend class="fs-6 w-auto px-2">Mode de répartition des volumes horaires</legend>

                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="repartition" checked>
                            <label class="form-check-label">Taux horaire par classe</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="repartition">
                            <label class="form-check-label">Taux horaire par cycle</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="repartition">
                            <label class="form-check-label">Taux horaire par promotion</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="repartition">
                            <label class="form-check-label">Taux horaire unique</label>
                          </div>
                        </fieldset>
                      </div>

                      <!-- Bloc Calcul de la moyenne -->
                      <div class="col-md-6">
                        <fieldset class="border rounded p-3 mb-3 h-100">
                          <legend class="fs-6 w-auto px-2">Calcul de la moyenne par matière</legend>

                          <!-- Option Classique -->
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="moyenne" id="mode_classique" value="classique" checked>
                            <label class="form-check-label" for="mode_classique">
                              Classique : Moy. Int et Devoirs ont même pondération
                            </label>
                          </div>

                          <!-- Option Avancé -->
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="moyenne" id="mode_avance" value="avance">
                            <label class="form-check-label" for="mode_avance">
                              Avancé : Calculer Moy. Int et Moy. Devoirs
                            </label>
                          </div>

                          <!-- Champs pondération (masqués par défaut) -->
                          <div id="advanced-settings" class="mt-3 ms-3" style="display: none;">
                            <div class="mb-2 d-flex align-items-center">
                              <label class="me-2" style="min-width: 230px;">Pondération Interrogations :</label>
                              <input type="number" name="Ponderation_MI" class="form-control form-control-sm" 
                                    value="{{ $settings->Ponderation_MI * 100 }}" 
                                    style="max-width: 100px;" min="0" max="100">%
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                              <label class="me-2" style="min-width: 230px;">Pondération Devoirs :</label>
                              <input type="number" name="Ponderation_Dev" class="form-control form-control-sm" 
                                    value="{{ $settings->Ponderation_Dev * 100 }}" 
                                    style="max-width: 100px;" min="0" max="100">%
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                              <label class="me-2" style="min-width: 230px;">Pondération Composition :</label>
                              <input type="number" name="Ponderation_Comp" class="form-control form-control-sm" 
                                    value="{{ $settings->Ponderation_Comp * 100 }}" 
                                    style="max-width: 100px;" min="0" max="100">%
                            </div>
                          </div>

                        </fieldset>
                      </div>

                      <!-- Script pour activer/désactiver les champs -->
                      <script>
                        document.addEventListener("DOMContentLoaded", function() {
                          const radioClassique = document.getElementById("mode_classique");
                          const radioAvance = document.getElementById("mode_avance");
                          const advancedDiv = document.getElementById("advanced-settings");

                          function toggleAdvancedFields() {
                            advancedDiv.style.display = radioAvance.checked ? "block" : "none";
                          }

                          // Ajout des listeners
                          radioClassique.addEventListener("change", toggleAdvancedFields);
                          radioAvance.addEventListener("change", toggleAdvancedFields);

                          // Initialisation à l'ouverture de la page
                          toggleAdvancedFields();
                        });
                      </script>

                    </div>   
                    <!-- Actions -->
                      <div class="col-12 text-end mt-4">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#renumeroterModal">Renuméroter</button>
                        <button type="submit" class="btn btn-success me-2">Enregistrer</button>
                        <button type="reset" class="btn btn-secondary">Annuler</button>
                      </div>
                </form>
                <!-- Modal -->
                <div class="modal fade" id="renumeroterModal" tabindex="-1" aria-labelledby="renumeroterModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h5 class="modal-title" id="renumeroterModalLabel">Renuméroter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                      </div>

                      <div class="modal-body">
                        {{-- <form method="POST" action="{{ route('ta_route') }}"> --}}
                          {{-- @csrf --}}

                          <div class="mb-3 d-flex align-items-center">
                            <label for="numAttestation" class="form-label me-3 mb-0" style="width: 200px;">Dernière Attestation</label>
                            <input type="number" class="form-control" id="numAttestation" name="NUMDERATT" style="width: 200px;" value="{{ $settings->NUMDERATT }}">
                          </div>

                          <div class="mb-3 d-flex align-items-center">
                            <label for="numCarte" class="form-label me-3 mb-0" style="width: 200px;">Dernière Carte</label>
                            <input type="number" class="form-control" id="numCarte" name="NUMDERCARTE" style="width: 200px;" value="{{ $settings->NUMDERCARTE }}">
                          </div>

                          <div class="mb-3 d-flex align-items-center">
                            <label for="numCertif" class="form-label me-3 mb-0" style="width: 200px;">Dernier Certificat</label>
                            <input type="number" class="form-control" id="numCertif" name="NUMDERCERTIF" style="width: 200px;" value="{{ $settings->NUMDERCERTIF }}">
                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                          </div>

                        {{-- </form> --}}
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </br></br></br>
            </div>

            <!-- Entete et Message -->
            <div class="tab-pane fade" id="pane-entetes-messages" role="tabpanel" aria-labelledby="nav-messages-tab">
                @csrf

                {{-- Groupe 1 --}}
                <div class="row mt-4 mb-4">
                    <div class="col-md-6 mb-4">
                        <label>Message fiche de notes</label>
                        <div class="quill-editor" data-name="message_fiche_notes">{!! $settings->EnteteFiches  ?? '' !!}</div>
                        <input type="hidden" name="message_fiche_notes">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>Message des reçus</label>
                        <div class="quill-editor" data-name="message_des_recus">{!! $settings->EnteteRecu ?? '' !!}</div>
                        <input type="hidden" name="message_des_recus">
                    </div>
                </div>
              </br></br>

                {{-- Groupe 2 --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <label>Entête des Documents</label>
                        <div class="quill-editor" data-name="entete_des_documents">{!! $settings->EnteteDoc ?? '' !!}</div>
                        <input type="hidden" name="entete_des_documents">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>Texte Fiche d’Engagement</label>
                        <div class="quill-editor" data-name="texte_fiche_engagement">{!! $settings->EnteteEngage ?? '' !!}</div>
                        <input type="hidden" name="texte_fiche_engagement">
                    </div>
                </div>
                </br></br>
 
                {{-- Groupe 3 --}}
                <div class="col-md-12 mb-4">
                    <label>Entête des bulletins</label>
                    <div class="quill-editor" data-name="entete_bulletins">{!! $settings->EnteteBull ?? '' !!}</div>
                    <input type="hidden" name="entete_bulletins">
                </div> 

                {{-- Case à cocher --}}
                <div class="form-check mb-4">
                    <input type="checkbox" name="save_as_model" class="form-check-input" id="save_as_model">
                    <label class="form-check-label" for="save_as_model">Enregistrer comme modèle</label>
                </div>

                {{-- Bouton --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </div>

            <!-- Quill CDN -->
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                  const editors = document.querySelectorAll('.quill-editor');

                  editors.forEach(editorDiv => {
                      const name = editorDiv.dataset.name;
                      const hiddenInput = document.querySelector(`input[name="${name}"]`);

                      const quill = new Quill(editorDiv, {
                          theme: 'snow',
                          modules: {
                              toolbar: [
                                  [{ header: [1, 2, 3, false] }],
                                  ['bold', 'italic', 'underline', 'strike'],
                                  [{ list: 'ordered' }, { list: 'bullet' }],
                                  ['link', 'clean']
                              ]
                          }
                      });

                      // Initialiser le contenu dans le champ caché
                      hiddenInput.value = editorDiv.querySelector('.ql-editor').innerHTML;

                      // Mettre à jour le champ caché lors de la modification
                      quill.on('text-change', () => {
                          hiddenInput.value = editorDiv.querySelector('.ql-editor').innerHTML;
                      });
                  });
              });
            </script>

          </div>

        </form>
      </div>

      <!-- Colonne des boutons à droite -->
      {{-- <div class="col-md-2 d-flex flex-wrap gap-2 align-content-start mt-2">
        <button class="btn btn-primary w-100">Enregistrer</button>
        <button class="btn btn-primary w-100">Bouton 2</button>
        <button class="btn btn-primary w-100">Bouton 3</button>
        <button class="btn btn-primary w-100">Bouton 4</button>
        <button class="btn btn-primary w-100">Bouton 5</button>
        <button class="btn btn-primary w-100">Bouton 6</button>
      </div> --}}

    </div>
  </div>
</div>

<style>
  .btn-arrow {
    position: absolute;
    top: 5px;
    left: 5px;
    background-color: transparent !important;
    border: none !important;
    text-transform: uppercase !important;
    font-weight: bold !important;
    cursor: pointer !important;
    font-size: 17px !important;
    color: #b51818 !important;
  }

  .btn-arrow:hover {
    color: #b700ff !important;
  }
</style>

@endsection
