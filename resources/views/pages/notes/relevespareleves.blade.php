@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div>
      <style>
          .btn-arrow {
              position: absolute;
              top: 0px;
              /* Ajustez la position verticale */
              left: 0px;
              /* Positionnez à gauche */
              background-color: transparent !important;
              border: 1px !important;
              text-transform: uppercase !important;
              font-weight: bold !important;
              cursor: pointer !important;
              font-size: 17px !important;
              /* Taille de l'icône */
              color: #b51818 !important;
              /* Couleur de l'icône */
          }
  
          .btn-arrow:hover {
              color: #b700ff !important;
              /* Couleur au survol */
          }
      </style>
      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
      </button>   
      <br>
      <br>                                   
  </div>
    <div class="card-body">
      <div class="col-md-12 p-4">
        <h3 class="mb-3">Relevé périodique des notes</h3>

        <!-- Ouvrir le formulaire pour envoyer les paramètres au contrôleur -->
        <form method="GET" action="{{ route('relevespareleves') }}">
          <!-- Section dates -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="txtDateSignature" class="font-weight-bold">Date de signature :</label>
              <input 
                type="date" 
                class="form-control d-inline-block" 
                id="txtDateSignature" 
                value="{{ date('Y-m-d') }}" 
                style="width:150px;"
              >
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <label class="font-weight-bold" for="txtDateDebut">Période du :</label>
                <input type="date" class="form-control mx-2" id="txtDateDebut">
                <label class="font-weight-bold" for="txtDateFin">au :</label>
                <input type="date" class="form-control mx-2" id="txtDateFin">
              </div>
            </div>
          </div>

          <!-- Sélection type, classe et impression -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="cbTypeReleve" class="font-weight-bold">Type de relevé</label>
              <select id="cbTypeReleve" class="form-control" name="type_releve">
                <option value="simple"   {{ (isset($typeReleve) && $typeReleve === 'simple')   ? 'selected' : '' }}>Relevé simple</option>
                <option value="bulletin" {{ (isset($typeReleve) && $typeReleve === 'bulletin') ? 'selected' : '' }}>Relevé bulletin</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="cbClasse" class="font-weight-bold">Sélectionner la classe</label>
              <select id="cbClasse" class="form-control" name="classe">
                <option value="">-- Choisir une classe --</option>
                @foreach ($classes as $classe)
                  <option 
                    value="{{ $classe->CODECLAS }}" 
                    {{ (request('classe') == $classe->CODECLAS) ? 'selected' : '' }}
                  >
                    {{ $classe->CODECLAS }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label for="cbTypeImpression" class="font-weight-bold">Type d'impression</label>
              <select id="cbTypeImpression" class="form-control" name="type_impression">
                <option value="classe" {{ (isset($typeImpression) && $typeImpression === 'classe') ? 'selected' : '' }}>
                  Toute la classe
                </option>
                <option value="eleve"  {{ (isset($typeImpression) && $typeImpression === 'eleve')  ? 'selected' : '' }}>
                  Un élève précis
                </option>
              </select>
            </div>
          </div>

          <!-- Si on a choisi "Un élève précis", on affiche un champ pour le matricule -->
          @if(isset($typeImpression) && $typeImpression === 'eleve')
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="matriculeEleve" class="font-weight-bold">Matricule de l'élève</label>
                <input 
                  type="text" 
                  id="matriculeEleve" 
                  class="form-control" 
                  name="matricule" 
                  value="{{ request('matricule') }}"
                >
              </div>
            </div>
          @endif

          <!-- Options d’impression : rang, appréciation, note par élève, destination, etc. -->
          <div class="row mb-3">
            <div class="col-md-3">
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="imprimer_rang_general" 
                  id="imprimer_rang_general" 
                  {{ !empty($imprimerRangGeneral) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="imprimer_rang_general">Imprimer Rang Général</label>
              </div>
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="imprimer_appreciation" 
                  id="imprimer_appreciation" 
                  {{ !empty($imprimerAppreciation) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="imprimer_appreciation">Imprimer Appréciation</label>
              </div>
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="imprimer_note_par_eleve" 
                  id="imprimer_note_par_eleve" 
                  {{ !empty($imprimerNoteParEleve) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="imprimer_note_par_eleve">Imprimer Note par Élève</label>
              </div>
            </div>
            <div class="col-md-3">
              <label for="destinationImpression" class="font-weight-bold">Destination</label>
              <select class="form-control" name="destination" id="destinationImpression">
                <option value="ecran"      {{ (isset($destinationImpression) && $destinationImpression === 'ecran')      ? 'selected' : '' }}>Écran</option>
                <option value="imprimante" {{ (isset($destinationImpression) && $destinationImpression === 'imprimante') ? 'selected' : '' }}>Imprimante</option>
              </select>
              <div class="form-check mt-2">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="imprimer_cours_systeme" 
                  id="imprimer_cours_systeme"
                  {{ !empty($imprimerCoursSysteme) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="imprimer_cours_systeme">Imprimer Cours du Système</label>
              </div>
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="imprimer_ordre_merite" 
                  id="imprimer_ordre_merite"
                  {{ !empty($imprimerOrdreMerite) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="imprimer_ordre_merite">Imprimer par Ordre de Mérite</label>
              </div>
            </div>
            <div class="col-md-6">
              <p class="font-weight-bold">Système de bonification</p>
              <div class="form-check form-check-inline">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="systeme_bonification" 
                  id="rbIntegral" 
                  value="integral"
                  {{ (isset($systemeBonification) && $systemeBonification === 'integral') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="rbIntegral">Intégral</label>
              </div>
              <div class="form-check form-check-inline">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="systeme_bonification" 
                  id="rbIntervalle" 
                  value="intervalle"
                  {{ (isset($systemeBonification) && $systemeBonification === 'intervalle') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="rbIntervalle">Intervalle</label>
              </div>
              <div class="form-check form-check-inline">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="systeme_bonification" 
                  id="rbAucune" 
                  value="aucune"
                  {{ (isset($systemeBonification) && $systemeBonification === 'aucune') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="rbAucune">Aucune</label>
              </div>
            </div>
          </div>

          <!-- Boutons d'actions -->
          <div class="row mb-3">
            <div class="col-md-12">
              <!-- Le name="action" peut aider à différencier 'calculer' vs 'imprimer' dans le contrôleur -->
              <button type="submit" class="btn btn-primary"   name="action" value="calculer" title="Calculer les moyennes">Calculer</button>
              <button type="submit" class="btn btn-secondary" name="action" value="imprimer" title="Imprimer le relevé">Imprimer</button>
            </div>
          </div>

          <!-- Table des matières et colonnes à imprimer -->
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header text-center">
                  <h5>Liste des Matières et Sélection des colonnes</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive mb-4">
                    <table class="table table-bordered" id="tableMatieres">
                      <thead class="thead-light">
                        <tr>
                          <th>Matières</th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="INT1" 
                              {{ (isset($colonnesSelectionnees) && in_array('INT1', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Int1
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="INT2"
                              {{ (isset($colonnesSelectionnees) && in_array('INT2', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Int2
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="INT3"
                              {{ (isset($colonnesSelectionnees) && in_array('INT3', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Int3
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="INT4"
                              {{ (isset($colonnesSelectionnees) && in_array('INT4', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Int4
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="DEV1"
                              {{ (isset($colonnesSelectionnees) && in_array('DEV1', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Dev1
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="DEV2"
                              {{ (isset($colonnesSelectionnees) && in_array('DEV2', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Dev2
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="DEV3"
                              {{ (isset($colonnesSelectionnees) && in_array('DEV3', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Dev3
                          </th>
                          <th>
                            <input 
                              type="checkbox" 
                              name="colonnes[]" 
                              value="TEST"
                              {{ (isset($colonnesSelectionnees) && in_array('TEST', $colonnesSelectionnees)) ? 'checked' : '' }}
                            >
                            Test
                          </th>
                        </tr>
                      </thead>
                      <tbody id="tableMatieresBody">
                        @foreach ($matieres as $matiere)
                          <tr>
                            <td>{{ $matiere->LIBELMAT }}</td>
                            <!-- Les colonnes ci-dessous sont juste informatives ou placeholders ;
                                 selon votre logique, vous pouvez y afficher des notes, etc. -->
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Table des informations des étudiants -->
            <div class="col-md-6">
              <div class="card">
                <div class="card-header text-center">
                  <h5>Informations des Étudiants</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive" style="max-height: 512px; margin: auto;">
                    <table class="table table-bordered table-sm text-center" id="tableEleves">
                      <thead class="thead-light">
                        <tr>
                          <th style="width: 10%;">Moy</th>
                          <th style="width: 10%;">Rang</th>
                          <th style="width: 20%;">Matricule</th>
                          <th style="width: 50%;">Nom et Prénom</th>
                          <th style="width: 10%;">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($notes as $note)
                          <tr>
                            <!-- Affichage de la moyenne calculée dans le contrôleur (ou MS1 par défaut) -->
                            <td>{{ $note->moyenne ?? $note->MS1 }}</td>
                            <td>{{ $note->RANG }}</td>
                            <td>{{ $note->MATRICULE }}</td>
                            <td>
                              <!-- Exemple si vous avez la relation "eleve()" dans le modèle Notes -->
                              {{ optional($note->eleve)->NOM }} {{ optional($note->eleve)->PRENOM }}
                            </td>
                            <td>
                              <button class="btn btn-primary btn-sm">Imprimer relevé</button>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="5">Aucun étudiant trouvé</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- Fin row mb-3 -->

          <!-- Section Signature et Messages (facultatif, ou à adapter) -->
          <div class="row">
            <div class="col">
              <div class="mb-3 d-flex align-items-center">
                <p class="font-weight-bold me-2 mb-0">Signature :</p>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="cbBac">
                  <label class="form-check-label" for="cbBac">Bac</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="cbDirecteur">
                  <label class="form-check-label" for="cbDirecteur">Directeur</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="cbCC">
                  <label class="form-check-label" for="cbCC">CC</label>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="txtMessage1" class="font-weight-bold">Message ligne 1 :</label>
                  <input type="text" class="form-control" id="txtMessage1" placeholder="Message ligne 1">
                </div>
                <div class="col-md-6">
                  <label for="txtMessage2" class="font-weight-bold">Message ligne 2 :</label>
                  <input type="text" class="form-control" id="txtMessage2" placeholder="Message ligne 2">
                </div>
              </div>
            </div>
            <div class="col">
              <div class="bg-warning ml-2 text-center font-weight-bold" 
                   style="width: 50px; height: 50px; line-height: 50px;">
                1
              </div>
            </div>
          </div>

        </form> <!-- Fin du formulaire principal -->

      </div> <!-- fin col-md-12 p-4 -->
    </div> <!-- fin card-body -->
  </div> <!-- fin card -->
</div> <!-- fin grid-margin stretch-card -->
@endsection
