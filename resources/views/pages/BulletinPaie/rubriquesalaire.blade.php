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
                        <div class="offcanvas-body">
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
                        </div>
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
                                        <a href='/paiementcontrat/{{ $rubrique->CODEPR }}'
                                            class='btn btn-primary w-40'>Modifier</a>
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
      
    <!-- Modal de confirmation -->
    {{-- <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Cet éleve est déja inscrit .Cependant sont inscription a été suspendu.Désirez vous réactiver cette
                    souscription?
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="button" class="btn btn-primary" id="confirmYesBtn">Oui</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="modal fade" id="exampleInscrire" tabindex="-1" aria-labelledby="exampleInscrireLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-3" id="exampleModalLabel">Inscription Mensuelle</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Mois a payer</h4>
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input">
                                                    Janvier
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" checked>
                                                    Février
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input">
                                                    Mars
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" checked>
                                                    Avril
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input">
                                                    Juillet
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input">
                                                    Août
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Enregister</button>
                </div>
            </div>
        </div>
    </div> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>



<script>
    // $(document).ready(function() {
    //     $('#nouveaucontrat').on('hidden.bs.modal', function () {
    //         // Réinitialiser les champs du formulaire
    //         $('#myModalForm')[0].reset();

    //         // Réinitialiser les selects
    //         $('#myModalForm select').each(function() {
    //             $(this).prop('selectedIndex', 0);
    //         });
    //     });
    // });

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

