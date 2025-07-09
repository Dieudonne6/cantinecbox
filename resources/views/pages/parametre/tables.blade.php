@extends('layouts.master')
@section('content')

<div class="card">
  <div class="card-header position-relative">
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
      <i class="fas fa-arrow-left"></i> Retour
    </button>
  </div>

  <div class="card-body">
    <div class="row">

      <!-- Contenu principal : les onglets -->
      <div class="col-md-10">
        <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button  class="nav-link active"  id="nav-ident-tab"  data-bs-toggle="tab"  data-bs-target="#pane-identification"  type="button"  role="tab"  aria-controls="pane-identification"  aria-selected="true">
      Identification
    </button>
    <button class="nav-link" id="nav-param-tab" data-bs-toggle="tab" data-bs-target="#pane-parametres" type="button" role="tab" aria-controls="pane-parametres" aria-selected="false">
      Paramètres Généraux
    </button>
    <button class="nav-link" id="nav-messages-tab" data-bs-toggle="tab" data-bs-target="#pane-entetes-messages" type="button" role="tab" aria-controls="pane-entetes-messages" aria-selected="false"  >
      Entêtes et Messages
    </button>
    <button class="nav-link" id="nav-scomerite-tab" data-bs-toggle="tab" data-bs-target="#pane-scomerite" type="button" role="tab" aria-controls="pane-scomerite" aria-selected="false">
      ScoMerite
    </button>
    <button class="nav-link" id="nav-scocarte-tab" data-bs-toggle="tab" data-bs-target="#pane-scocarte" type="button" role="tab" aria-controls="pane-scocarte" aria-selected="false">
      ScoCarte
    </button>
    <button class="nav-link" id="nav-sms-tab"  data-bs-toggle="tab" data-bs-target="#pane-sms" type="button" role="tab"  aria-controls="pane-sms" aria-selected="false">
      ModuleSMS
    </button>
  </div>
</nav>

        <div class="tab-content mt-3">
<div class="tab-content" id="nav-tabContent">
  <div
    class="tab-pane fade show active"
    id="pane-identification"
    role="tabpanel"
    aria-labelledby="nav-ident-tab"
  >                
                {{-- <form action="{{ route('parametre.saveIdentification') }}" method="POST" enctype="multipart/form-data"> --}}
                    @csrf
                    <div class="card">
                        <div class="card-body">
                        <div class="row gx-4 gy-3">
                            <!-- Champ Nom et Code -->
                            <div class="col-md-8">
                            <label class="form-label">Nom établissement</label>
                            <input type="text" name="nom_etablissement" class="form-control" value="{{ old('nom_etablissement', $settings->nom_etablissement ?? '') }}">
                            </div>
                            <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code_etablissement" class="form-control bg-warning text-end" value="{{ old('code_etablissement', $settings->code_etablissement ?? '') }}">
                            </div>

                            <!-- Logos côte à côte -->
                            <div class="col-12 col-md-12 d-flex gap-3">
                            @foreach(['logo_gauche','logo_droit'] as $logo)
                            <div class="card flex-fill text-center">
                                <div class="card-header">{{ Str::studly(str_replace('_',' ',$logo)) }}</div>
                                <div class="card-body">
                                @if(isset($settings->{$logo}))
                                <img src="{{ asset('storage/' . $settings->{$logo}) }}" class="img-fluid mb-2" style="max-height:160px" alt="{{ $logo }}">
                                @endif
                                <input type="file" name="{{ $logo }}" class="form-control">
                                </div>
                            </div>
                            @endforeach
                            </div>

                            <!-- Types établissement -->
                            <div class="col-md-8">
                            <fieldset class="border rounded p-3">
                                <legend class="w-auto px-2">Type établissement</legend>
                                <div class="row gy-2">
                                @foreach(['Prescolaire','Primaire','Collège Général','Collège Technique','Formation Prof.','Lycée Général','Lycée Technique','Supérieur'] as $type)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="types[]" value="{{ $type }}" id="type-{{ Str::slug($type) }}" {{ in_array($type, old('types', $settings->types ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-{{ Str::slug($type) }}">{{ $type }}</label>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            </fieldset>
                            </div>

                            <!-- Statut -->
                            <div class="col-md-4">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                @foreach(['Public','Prive','Particulier'] as $statut)
                                <option value="{{ $statut }}" {{ old('statut', $settings->statut ?? '') === $statut ? 'selected' : '' }}>{{ $statut }}</option>
                                @endforeach
                            </select>

                            <!-- Adresse, Ville, Département et Email -->
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $settings->adresse ?? '') }}">
                            
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" value="{{ old('ville', $settings->ville ?? '') }}">
                            </div>
                            <div class="col-md-6">
                            <label class="form-label">Département</label>
                            <input type="text" name="departement" class="form-control" value="{{ old('departement', $settings->departement ?? '') }}">
                            </div>
                            <div class="col-md-6">
                            <label class="form-label">E‑mail</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email ?? '') }}">
                            </div>

                            <!-- Directeurs, Censeur, Comptable -->
                            @foreach([
                                ['key'=>'directeur1','label'=>'Nom directeur 1','title_key'=>'titre_directeur1','title_label'=>'Titre'],
                                ['key'=>'directeur2','label'=>'Nom directeur 2','title_key'=>'titre_directeur2','title_label'=>'Titre'],
                                ['key'=>'directeur3','label'=>'Nom directeur 3','title_key'=>'titre_directeur3','title_label'=>'Titre'],
                                ['key'=>'censeur','label'=>'Nom censeur','title_key'=>'titre_censeur','title_label'=>'Titre'],
                                ['key'=>'comptable','label'=>'Comptable/Intendant','title_key'=>'titre_comptable','title_label'=>'Titre'],
                                ] as $perso)
                                <div class="col-md-6">
                                <label class="form-label">{{ $perso['label'] }}</label>
                                <input type="text" name="{{ $perso['key'] }}" class="form-control" value="{{ old($perso['key'], $settings->{$perso['key']} ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">{{ $perso['title_label'] }}</label>
                                <input type="text" name="{{ $perso['title_key'] }}" class="form-control" value="{{ old($perso['title_key'], $settings->{$perso['title_key']} ?? '') }}">
                                </div>
                            @endforeach

                            <!-- Devise -->
                            <div class="col-12">
                            <label class="form-label">Devise de l’établissement</label>
                            <input type="text" name="devise" class="form-control" value="{{ old('devise', $settings->devise ?? '') }}">
                            </div>

                            {{-- <!-- Boutons d'action -->
                            <div class="col-12 text-end">
                            <button type="submit" class="btn btn-success me-2">Enregistrer</button>
                            <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div> --}}
                        </div>
                        </div>
                    </div>
                {{-- </form> --}}
                
            </div>


  <div
    class="tab-pane fade"
    id="pane-parametres"
    role="tabpanel"
    aria-labelledby="nav-param-tab"
  >                {{-- … --}}
                <form>
                    <div class="row">
                      <!-- Partie gauche -->
                      <div class="col-md-8">
                        <div class="mb-3">
                          <label>Libellé scolarité</label>
                          <input type="text" class="form-control" value="Scolarité">
                        </div>

                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label>Libellé frais annexes 1</label>
                            <input type="text" class="form-control" value="Droits d'inscription">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>Montant</label>
                            <input type="number" class="form-control" value="0">
                          </div>

                          <div class="col-md-6 mb-3">
                            <label>Libellé frais annexes 2</label>
                            <input type="text" class="form-control" value="Informatique">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>Montant</label>
                            <input type="number" class="form-control" value="0">
                          </div>

                          <div class="col-md-6 mb-3">
                            <label>Libellé frais annexes 3</label>
                            <input type="text" class="form-control" value="Frais Divers">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>Montant</label>
                            <input type="number" class="form-control" value="0">
                          </div>

                          <div class="col-md-6 mb-3">
                            <label>Libellé frais annexes 4</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>Montant</label>
                            <input type="number" class="form-control">
                          </div>
                        </div>

                        <div class="mb-3">
                          <label>Type de matricule</label><br>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_matricule" value="manuel">
                            <label class="form-check-label">Manuel</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_matricule" value="auto" checked>
                            <label class="form-check-label">Automatique</label>
                          </div>
                        </div>

                        <div class="mb-3">
                          <label>Periodicité</label>
                          <select class="form-select">
                            <option selected>Semestrielle</option>
                            <option>Trimestrielle</option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label>Nombre d'absences matière autorisé</label>
                          <input type="number" class="form-control" value="1">
                          <small class="text-danger">Mettre -1 pour désactiver cette option</small>
                        </div>

                        <fieldset class="mb-3">
                          <legend class="fs-6">Mode de répartition des volumes horaires</legend>
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

                        <fieldset>
                          <legend class="fs-6">Calcul de la moyenne par matière</legend>
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="moyenne" checked>
                            <label class="form-check-label">Classique : Moy. Int et Devoirs ont même pondération</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="moyenne">
                            <label class="form-check-label">Avancé : Calculer Moy. Int et Moy. Devoirs</label>
                          </div>

                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label>Pondération Interrogations</label>
                              <input type="number" class="form-control" value="20.00">
                            </div>
                            <div class="col-md-6 mb-3">
                              <label>Pondération Composition</label>
                              <input type="number" class="form-control" value="50.00">
                            </div>
                          </div>
                        </fieldset>
                      </div>

                      <!-- Partie droite (Tranches) -->
                      <div class="col-md-4 bg-warning-subtle p-3 rounded">
                        <h6>Date 1er paiement standard</h6>
                        <input type="date" class="form-control mb-2" value="2013-10-01">

                        <label>Periodicité standard (mois)</label>
                        <input type="number" class="form-control mb-2" value="2">

                        <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" checked>
                          <label class="form-check-label">Échéancier prend en compte tous les frais</label>
                        </div>

                        <div class="mb-2">
                          <label>Tranche 1</label>
                          <input type="number" class="form-control" value="50.00">
                        </div>
                        <div class="mb-2">
                          <label>Tranche 2</label>
                          <input type="number" class="form-control" value="30.00">
                        </div>
                        <div class="mb-2">
                          <label>Tranche 3</label>
                          <input type="number" class="form-control" value="20.00">
                        </div>

                        <hr>

                        <div class="mb-2">
                          <label>Dernière Attestation</label>
                          <input type="number" class="form-control" value="0">
                        </div>
                        <div class="mb-2">
                          <label>Dernière Carte</label>
                          <input type="number" class="form-control" value="0">
                        </div>
                        <div class="mb-2">
                          <label>Dernier Certificat</label>
                          <input type="number" class="form-control" value="0">
                        </div>
                      </div>
                    </div>
                </form>
            </div>
<div
    class="tab-pane fade"
    id="pane-entetes-messages"
    role="tabpanel"
    aria-labelledby="nav-messages-tab"
  >                {{-- … --}}
            </div>
            {{-- etc. --}}
            <div
    class="tab-pane fade"
    id="pane-scomerite"
    role="tabpanel"
    aria-labelledby="nav-scomerite-tab"
  >
    <!-- Contenu ScoMerite -->
  </div>

  <div
    class="tab-pane fade"
    id="pane-scocarte"
    role="tabpanel"
    aria-labelledby="nav-scocarte-tab"
  >
    <!-- Contenu ScoCarte -->
  </div>

  <div
    class="tab-pane fade"
    id="pane-sms"
    role="tabpanel"
    aria-labelledby="nav-sms-tab"
  >
    <!-- Contenu ModuleSMS -->
  </div>
        </div>

      </div>

      <!-- Colonne des boutons à droite -->
      <div class="col-md-2 d-flex flex-wrap gap-2 align-content-start mt-2">
        <button class="btn btn-primary w-100">Bouton 1</button>
        <button class="btn btn-primary w-100">Bouton 2</button>
        <button class="btn btn-primary w-100">Bouton 3</button>
        <button class="btn btn-primary w-100">Bouton 4</button>
        <button class="btn btn-primary w-100">Bouton 5</button>
        <button class="btn btn-primary w-100">Bouton 6</button>
      </div>

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
</br></br></br>
@endsection
