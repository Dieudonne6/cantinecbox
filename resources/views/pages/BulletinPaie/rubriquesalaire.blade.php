@extends('layouts.master')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-arrow" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                    </div>

                    <div class="card-title"> Liste des Rubriques du Salaires </div>
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Aide</button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel">Liste Rubrique du salaire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        {{-- <div class="offcanvas-body">
                            <p><strong> 1-Sélectionner une classe:</strong> Affiche uniquement la liste des élèves de la
                                classe ayant un contrat. </p>
                            <p><strong> 2-Possibilité de rechercher un élève.</strong>
                            </p>
                            <p><strong> 3-Nouveau contrat:</strong> Permet de créer un nouveau contrat. <br>
                                Le montant affiché correspond aux frais d’inscription de cantine de la classe sélectionnée.
                            </p>
                            <p><strong>4-Paiement:</strong></br>
                                Remplir les informations de paiement (Date, montant) </br>
                                Sélectionner le (les) mois à payer. </br>
                                Le coût total est affiché automatiquement. </br>
                                <strong>Enregistrer:</strong> Génère la facture normalisée. </br>
                                <strong>Annuler:</strong> Réinitialise les informations entrées.
                            </p>
                            <p><strong>5-Suspendre:</strong> Suspend le contrat. </p>
                        </div> --}}
                    </div>
                </div>
                @if (Session::has('status'))
                    <div id="statusAlert" class="alert alert-success btn-primary">
                        {{ Session::get('status') }}
                    </div>
                @endif
                @if (Session::has('erreur'))
                    <div id="statusAlert" class="alert alert-danger btn-primary">
                        {{ Session::get('erreur') }}
                    </div>
                @endif

                <div class="form-group row">

                    <div class="col-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#nouveaucontrat">
                            Nouveau Rubrique
                        </button>
                    </div>

                    {{-- <div class="col-3">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleInscrire">
            Inscriptions mensuelles
          </button>
        </div> --}}

                </div>
                <div class="table-responsive mb-4">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th>Code</th>

                                <th>Libellé rubrique </th>

                                <th>Type Rubrique </th>

                                <th>Montant Fixe </th>

                                <th>% Variable</th>

                                <th>Base</th>

                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody id="eleve-details">
                            @foreach ($rubriques as $rubrique)
                                <tr class="eleve" >
                                    <td>
                                        {{ $rubrique->CODEPR }}
                                    </td>
                                    <td>
                                        {{ $rubrique->LIBELPR }}
                                    </td>
                                    <td>
                                        {{ $rubrique->TYPEPR }}
                                    </td>
                                    <td>
                                        {{ $rubrique->MONTANTFIXE }}
                                    </td>
                                    <td>
                                        {{ $rubrique->MONTANTVAR }}
                                    </td>
                                    <td>
                                        {{ $rubrique->BASEVARIABLE }}
                                    </td>
                                    <td>
                                        {{-- <a href='/paiementcontrat/{{ $rubrique->CODEPR }}'
                                            class='btn btn-primary w-40'>Modifier</a> --}}

                                            <!-- bouton Modifier : remplit le modal via data-attributes -->
                                            <button type="button"
                                                    class="btn btn-primary btn-edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRubriqueModal"
                                                    data-code="{{ $rubrique->CODEPR }}"
                                                    data-libel="{{ e($rubrique->LIBELPR) }}"
                                                    data-type="{{ $rubrique->TYPEPR }}"
                                                    data-montantfixe="{{ $rubrique->MONTANTFIXE }}"
                                                    data-montantvar="{{ $rubrique->MONTANTVAR }}"
                                                    data-base="{{ $rubrique->BASEVARIABLE }}">
                                            Modifier
                                            </button>

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal-{{ $rubrique->CODEPR }}" >Supprimer</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{ $rubrique->CODEPR }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de
                                                            suppression</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer cette rubrique ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ url('supprimerrubrique') }}" method="POST">
                                                            {{-- <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="POST"> --}}
                                                            @csrf
                                                            {{-- @method('PUT') --}}
                                                            <input type="hidden" value="{{ $rubrique->CODEPR }}"
                                                                name="codepr">
                                                            {{-- <a href='/admin/deletecashier/{{$eleves->MATRICULE}}' class='btn btn-danger w-50'>Suspendre</a> --}}
                                                            <input type="submit" class="btn btn-danger" value="Confirmer">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->

    <div class="modal fade" id="nouveaucontrat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="modalForm" method="POST" action="{{ url('enregistrerrubriquesalaire') }}">
              @csrf
      
              <div class="modal-header">
                <h4 class="modal-title">Nouvelle rubrique</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
      
              <div class="modal-body">
                <div class="form-group row">
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="codepr"><strong>Code Rubrique</strong>
                                                                    <p>4 caracteres au plus</p>
                                                                </label>

                                                                <input type="text" id="codepr"
                                                                    name="codepr" class="form-control"
                                                                    value="{{ old('codepr') }}" required
                                                                    minlength="2" maxlength="4"
                                                                    >
                                                            </div>


                                                            <div class="col-sm-6 mb-1">
                                                                <label for="libelpr"><strong>Intitulé Rubrique</strong>
                                                                    <p>Ex: Prime de suggestion</p>
                                                                </label>
                                                                <input type="text" id="libelpr"
                                                                    name="libelpr" class="form-control"
                                                                    value="{{ old('libelpr') }}" required
                                                                    minlength="2" maxlength="50">
                                                            </div>

                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="montantfixe"><strong>Montant Fixe</strong>
                                                                    <p>Partie fixe du montant de la prise/retenu</p>
                                                                </label>
                                                                <input type="number" id="montantfixe" name="montantfixe"
                                                                    class="form-control" required min="1"
                                                                    >
                                                            </div>
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="type"><strong>Type</strong>
                                                                    <p>choisir le type de la rubrique prise/retenu </p>
                                                                </label>
                                                                <select id="type" name="type"
                                                                    class="js-example-basic-multiple form-control w-100"
                                                                    >
                                                                    <option value="P">PRIME IMPOSABLE</option>
                                                                    <option value="I">PRIME NON IMPOSABLE</option>
                                                                    <option value="R">RETENUE</option>
                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="montantvar"><strong>% Montant Variable</strong>
                                                                    <p>Pourcentage a appliquer pour la partie variable</p>
                                                                </label>
                                                                <input type="decimal" id="montantvar" name="montantvar"
                                                                    class="form-control" required min="1"
                                                                    >
                                                            </div>
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="basevariable"><strong>Base Variable</strong>
                                                                    <p>Sur quelle base appliquer la partie variable</p>
                                                                </label>
                                                                <select id="basevariable" name="basevariable"
                                                                    class="js-example-basic-multiple form-control w-100"
                                                                    required>
                                                                    <option value="">AUCUNE</option>
                                                                    <option value="SB">SALAIRE BASE</option>
                                                                    <option value="ST">SALAIRE BRUT</option>
                                                                </select>

                                                            </div>

                                                        </div>

      
              </div> {{-- fin modal-body --}}
      
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Annuler
                </button>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                  Enregistrer
                </button>
              </div>
            </form>
          </div>
        </div>
    </div>
      


    {{-- modal pour le Edit --}}

    <!-- Modal d'édition (unique) -->
<div class="modal fade" id="editRubriqueModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editRubriqueForm" method="POST" action="">
        @csrf
        @method('PUT') {{-- on supposera une route RESTful pour update --}}
        <div class="modal-header">
          <h5 class="modal-title">Modifier la rubrique</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <label for="editCode" class="form-label"><strong>Code Rubrique</strong></label>
            <input type="text" id="editCode" name="codepr" class="form-control" readonly>
          </div>

          <div class="mb-2">
            <label for="editLibel" class="form-label"><strong>Intitulé Rubrique</strong></label>
            <input type="text" id="editLibel" name="libelpr" class="form-control" required maxlength="50">
          </div>

          <div class="row">
            <div class="col-6 mb-2">
              <label for="editType" class="form-label"><strong>Type</strong></label>
              <select id="editType" name="type" class="js-example-basic-multiple form-control w-100" required>
                <option value="P">PRIME IMPOSABLE</option>
                <option value="I">PRIME NON IMPOSABLE</option>
                <option value="R">RETENUE</option>
              </select>
            </div>

            <div class="col-6 mb-2">
              <label for="editMontantFixe" class="form-label"><strong>Montant Fixe</strong></label>
              <input type="number" id="editMontantFixe" name="montantfixe" class="form-control" min="0" required>
            </div>
          </div>

          <div class="row">
            <div class="col-6 mb-2">
              <label for="editMontantVar" class="form-label"><strong>% Montant Variable</strong></label>
              <input type="number" id="editMontantVar" name="montantvar" class="form-control" min="0" step="0.01" required>
            </div>

            <div class="col-6 mb-2">
              <label for="editBaseVar" class="form-label"><strong>Base Variable</strong></label>
              <select id="editBaseVar" name="basevariable" class="js-example-basic-multiple form-control w-100" required>
                <option value="">AUCUNE</option>
                <option value="SB">SALAIRE BASE</option>
                <option value="ST">SALAIRE BRUT</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
      </form>
    </div>
  </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var editModalEl = document.getElementById('editRubriqueModal');

  editModalEl.addEventListener('show.bs.modal', function (event) {
    // button qui a déclenché le modal
    var button = event.relatedTarget;

    var code = button.getAttribute('data-code') || '';
    var libel = button.getAttribute('data-libel') || '';
    var type = button.getAttribute('data-type') || '';
    var montantFixe = button.getAttribute('data-montantfixe') || '';
    var montantVar = button.getAttribute('data-montantvar') || '';
    var baseVar = button.getAttribute('data-base') || '';

    // remplir les champs du modal
    document.getElementById('editCode').value = code;
    document.getElementById('editLibel').value = libel;
    document.getElementById('editType').value = type;
    document.getElementById('editMontantFixe').value = montantFixe;
    document.getElementById('editMontantVar').value = montantVar;
    document.getElementById('editBaseVar').value = baseVar;

    // positionner dynamiquement l'action du formulaire
    var form = document.getElementById('editRubriqueForm');

    // Exemple d'URL : '/modifierubrique/{code}' -> adapte selon ta route
    // si tu utilises une route nommée Laravel, tu peux construire côté JS en baseURL + ...
    form.action = '/modifierubrique/' + encodeURIComponent(code);
  });

  // (optionnel) reset du formulaire quand modal caché
  editModalEl.addEventListener('hidden.bs.modal', function () {
    document.getElementById('editRubriqueForm').reset();
  });

  // --- Correction mineure: répare la réinitialisation du modal "nouveaucontrat" existant
  var nouveau = document.getElementById('nouveaucontrat');
  if (nouveau) {
    nouveau.addEventListener('hidden.bs.modal', function() {
      var form = document.getElementById('modalForm');
      if (form) form.reset();
      form?.querySelectorAll('.form-control')?.forEach(input => input.value = '');
    });
  }
});
</script>


<script>


    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('nouveaucontrat'));

        @if ($errors->any())
            myModal.show();
        @endif

        // Réinitialiser les champs du formulaire à la fermeture du modal
        document.getElementById('nouveaucontrat').addEventListener('hidden.bs.modal', function() {
            document.getElementById('myModalForm').reset();
            document.querySelectorAll('#myModalForm .form-control').forEach(input => input.value = '');
        });
    });

</script>










  
  



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

@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}

