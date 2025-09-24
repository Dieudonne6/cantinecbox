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

                    <div class="card-title"> Liste des contrats disponibles </div>
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Aide</button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel">Liste des contrats</h5>
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
                        <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                            <option value="">Sélectionnez une classe</option>
                            @foreach ($classe as $classes)
                                <option value="eleve/{{ $classes->CODECLAS }}">{{ $classes->CODECLAS }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#nouveaucontrat">
                            Nouveau Contrat
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
                                <th>
                                    Classe
                                </th>

                                <th>Elève </th>

                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="eleve-details">
                            @foreach ($eleve as $index => $eleves)
                                <tr class="eleve" data-id="{{ $eleves->id }}" data-nom="{{ $eleves->NOM }}"
                                    data-prenom="{{ $eleves->PRENOM }}" data-codeclas="{{ $eleves->CODECLAS }}">
                                    <td>
                                        {{ $eleves->CODECLAS }}
                                    </td>
                                    <td>
                                        {{ $eleves->NOM }} {{ $eleves->PRENOM }}

                                    </td>
                                    <td>
                                        <a href='/paiementcontrat/{{ $eleves->CODECLAS }}/{{ $eleves->MATRICULE }}'
                                            class='btn btn-primary w-40'>Paiement</a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal-{{ $eleves->MATRICULE }}" >Suspendre</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{ $eleves->MATRICULE }}" tabindex="-1"
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
                                                        Êtes-vous sûr de vouloir supprimer ce contrat ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ url('supprimercontrat') }}" method="POST">
                                                            {{-- <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="POST"> --}}
                                                            @csrf
                                                            {{-- @method('PUT') --}}
                                                            <input type="hidden" value="{{ $eleves->MATRICULE }}"
                                                                name="matricule">
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
            <form id="modalForm" method="POST" action="{{ url('creercontrat') }}">
              @csrf
      
              <div class="modal-header">
                <h4 class="modal-title">Nouveau contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
      
              <div class="modal-body">
                {{-- — Champs Création de contrat — --}}
                <input type="hidden" name="id_usercontrat" value="{{ Session::get('id_usercontrat') }}">
                <div class="mb-3">
                  <label for="classSelect">Classe</label>
                  <select id="classSelect" class="js-example-basic-multiple w-100" name="classes" class="form-select">
                    @foreach($classe as $c)
                      <option value="{{ $c->CODECLAS }}">{{ $c->CODECLAS }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="eleveSelect">Elève</label>
                  <select id="eleveSelect" class="js-example-basic-multiple w-100" name="matricules" class="form-select">
                    {{-- options chargées via JS/Ajax --}}
                  </select>
                </div>
                <div class="row mb-3">
                  <div class="col">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                  </div>
                  <div class="col">
                    <label>Montant</label>
                    <input type="text" name="montant" id="montant" class="form-control">
                  </div>
                </div>
      
                {{-- — Checkbox pour passer en paiement — --}}
                <div class="form-check mb-3  rounded p-2" id="checkboxContainer">
                  <input class="form-check-input border border-dark ml-1" type="checkbox" id="voirMoisCheckbox">
                  <label class="form-check-label "  for="voirMoisCheckbox">
                    Cocher pour voir les mois concernés
                  </label>
                </div>
      
                {{-- — Bloc Infos paiement (caché par défaut) — --}}
                <div id="paymentFields" style="display: none;" class="col-lg-12 grid-margin stretch-card">
                  <div class="card mb-3">
                    <div class="card-body">
                        <div class="col-md-12 mx-auto grid-margin stretch-card">

                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Infos paiement</h4>
                                    <p class="card-description">
                                        Veuillez remplir les champs
                                    </p>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label>Date</label>
                                            <div id="the-basics">
                                                <input class="typeaheads" type="datetime-local" id="date" name="datePaiement"
                                                    value="{{ date('Y-m-d\TH:i:s') }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Montant Mensuel</label>
                                            <div id="bloodhound">
                                                <input class="typeaheads" id="fraismensuelle" name="montantcontrat" type="text" value="{{ $fraismensuelle }}" >
                                                <input class="typeaheads" id="fraismensuelle" name="montantcontratReel" type="hidden" value="{{ $fraismensuelle }}" >
                                            </div>
                                        </div>
                                        {{-- <div class="col">
                                            <label>Reduction</label>
                                            <div id="bloodhound">
                                                <input class="typeaheads" id="reduction" name="reduction" type="number" >
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Mois impayés</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{-- {{ $moisCorrespondants }} --}}
                                                    {{-- @php
                                                        if (count($moisCorrespondants) <= 0) {
                                                            $nommois = "l'eleve est a jour";
                                                        }
                                                    @endphp --}}
                                                    {{-- @if (count($moisCorrespondants) <= 0)
                                                        <h5>L'eleve est a jour</h5>
                                                    @else --}}
                                                        {{-- <p>{{ $nommois }}</p> --}}
                                                       
                                                    @foreach ($moisCorrespondants as $id_moiscontrat => $nom_moiscontrat)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="moiscontrat[]" class="form-check-input checkbox-mois"
                                                            value="{{ $id_moiscontrat }}">
                                                            {{ $nom_moiscontrat }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    {{-- @endif --}}
                                                        
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Coût total</h4>
                                    <div class="form-group">
                                        <div class="col">
                                            {{-- <p>Nombre de cases cochées : <span id="checked-count">0</span></p> --}}
                                            <label>Montant Total</label>
                                            <div id="bloodhound">
                                                <input class="typeaheads" id="fraistotal" name="montanttotal" type="number" value="0" readonly>
                                                <p style="visibility: hidden"><span id="checked-count">0</span></p>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
      
              </div> {{-- fin modal-body --}}
      
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Annuler
                </button>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                  Enregistrer contrat
                </button>
              </div>
            </form>
          </div>
        </div>
    </div>
      
    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    </div>

    <div class="modal fade" id="exampleInscrire" tabindex="-1" aria-labelledby="exampleInscrireLabel"
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
    </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const montantInput = document.getElementById('montant');
        const checkboxContainer = document.getElementById('checkboxContainer');

        montantInput.addEventListener('input', function () {
            const montant = parseFloat(this.value.replace(',', '.'));
            if (!isNaN(montant) && montant > 0) {
                checkboxContainer.style.display = 'block';
            } else {
                checkboxContainer.style.display = 'none';
            }
        });
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  // On attend que le modal soit ouvert pour binder sur le bon élément
  $('#nouveaucontrat').on('shown.bs.modal', function () {
    const $eleve = $('#eleveSelect');
    const $chkCtn = $('#checkboxContainer');

    // Réinitialise l’état au cas où le modal est rouvert
    $chkCtn.hide();

    // Si tu utilises Select2
    $eleve.off('select2:select.showCheckbox')   // enlève un éventuel ancien binding
          .on('select2:select.showCheckbox', () => {
            console.log('Select2 select:', $eleve.val());
            $chkCtn.show();
          });

    // Et on garde le fallback “change” si tu ne voulais pas jQuery/Select2
    $eleve.off('change.showCheckbox')
          .on('change.showCheckbox', () => {
            console.log('change:', $eleve.val());
            if ($eleve.val()) $chkCtn.show();
            else            $chkCtn.hide();
          });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('voirMoisCheckbox');
  const paymentForm = document.getElementById('paymentFormContainer');

  checkbox.addEventListener('change', function () {
    // Affiche le formulaire si coché, masque sinon
    paymentForm.style.display = this.checked ? 'block' : 'none';
  });
});
</script>





<script>
document.addEventListener('DOMContentLoaded', () => {
  const checkbox      = document.getElementById('voirMoisCheckbox');
  const paymentFields = document.getElementById('paymentFields');
  const form          = document.getElementById('modalForm');
  const submitBtn     = document.getElementById('submitBtn');

  // À l'ouverture du modal : état initial
  $('#nouveaucontrat').on('shown.bs.modal', () => {
    checkbox.checked    = false;
    paymentFields.style.display = 'none';
    form.action         = "{{ url('creercontrat') }}";
    submitBtn.textContent = 'Enregistrer contrat';
  });

  // Quand on coche/décoche
  checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
      paymentFields.style.display = 'block';
      form.action         = "{{ url('savepaiementetinscriptioncontrat') }}";
      submitBtn.textContent = 'Valider paiement';
    } else {
      paymentFields.style.display = 'none';
      form.action         = "{{ url('creercontrat') }}";
      submitBtn.textContent = 'Enregistrer contrat';
    }
  });


});
</script>

<script>
// Attend que le document soit prêt
document.addEventListener("DOMContentLoaded", function() {
// Sélectionne tous les éléments avec la classe checkbox-mois
var checkboxes = document.querySelectorAll('.checkbox-mois');
var fraismensuelle = document.querySelector('#fraismensuelle');
var fraistotal = document.querySelector('#fraistotal');

// Fonction pour mettre à jour le montant total en fonction du nombre de cases cochées et du frais mensuel
function updateTotalAmount() {
    var valeurInput = fraismensuelle.value;
    var checkedCheckboxes = document.querySelectorAll('.checkbox-mois:checked');
    var numberOfCheckedCheckboxes = checkedCheckboxes.length;
    var montantTotal = numberOfCheckedCheckboxes * valeurInput;
    fraistotal.value = montantTotal;
}

// Écoute les changements de valeur de l'élément fraismensuelle
fraismensuelle.addEventListener('input', function() {
    // Met à jour le montant total lorsque la valeur du frais mensuel change
    updateTotalAmount();
});

// Écoute les changements d'état des cases à cocher
checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        // Met à jour le montant total lorsque le nombre de cases cochées change
        updateTotalAmount();
    });
});

// Met à jour le montant total initial
updateTotalAmount();
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

