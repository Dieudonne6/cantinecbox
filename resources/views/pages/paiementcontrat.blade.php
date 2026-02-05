
@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  .btn-arrow {
      position: absolute;
      top: 0px; /* Ajustez la position verticale */
      left: 0px; /* Positionnez à gauche */
      background-color: transparent !important;
      border:1px !important;
      text-transform: uppercase !important;
      font-weight: bold !important;
      cursor: pointer!important;
      font-size: 17px!important; /* Taille de l'icône */
      color: #b51818!important; /* Couleur de l'icône */
  }
  
  .btn-arrow:hover {
      color: #b700ff !important; /* Couleur au survol */
  }

  /* Responsive styles */
  @media (max-width: 1200px) {
    .col-lg-12 {
      padding: 0.5rem;
    }
  }
  
  @media (max-width: 768px) {
    .col-lg-12 {
      padding: 0.25rem;
    }
    
    .card-body {
      padding: 1rem;
    }
    
    .btn-arrow {
      font-size: 14px !important;
    }
    
    .col-md-8 {
      width: 100%;
      margin: 0.5rem 0;
    }
    
    .card-title {
      font-size: 1rem;
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
  }
  
  @media (max-width: 576px) {
    .card-body {
      padding: 0.75rem;
    }
    
    .btn-arrow {
      font-size: 12px !important;
    }
    
    .card-title {
      font-size: 0.9rem;
    }
    
    .form-group {
      margin-bottom: 0.75rem;
    }
    
    .form-check {
      margin-bottom: 0.5rem;
    }
    
    .form-control {
      font-size: 0.9rem;
      padding: 0.4rem;
    }
    
    .btn {
      font-size: 0.8rem;
      padding: 0.4rem 0.8rem;
    }
    
    strong {
      font-size: 0.9rem;
    }
  }
  </style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>

                                  {{-- erreur concernant le paiement --}}
                                  @if($errors->any())
                                  <div id="statusAlert" class="alert alert-danger">
                                      <ul>
                                          @foreach($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                                  @endif
                <h4 class="card-title" style="text-align: center">Paiement pour <strong>{{ $nomCompletEleveCon }}</strong></h4>

                <form action="{{url('/savepaiementcontrat')}}" method="POST">
                    @csrf
                    
                    @if(Session::has('id_usercontrat'))
                        <?php $id_usercontrat = Session::get('id_usercontrat'); ?>
                        <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
                    @endif
                    <div class="col-md-8 mx-auto grid-margin stretch-card">

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
                                            <input class="typeaheads" type="datetime-local" id="date" name="date"
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
                    <div class="col-md-8 mx-auto grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Mois impayés</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
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
                    <div class="col-md-8 mx-auto grid-margin stretch-card">
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
                    <div class="col-md-8 mx-auto grid-margin stretch-card mt-5 mb-5">

                        <input type="submit" class="btn btn-primary mr-2" value="Enregistrer">
                        <input type="reset" class="btn btn-light" value="Annuler">
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

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





{{-- 
    
     // Attend que le document soit prêt
document.addEventListener("DOMContentLoaded", function() {
    // Sélectionne tous les éléments avec la classe checkbox-mois
    var checkboxes = document.querySelectorAll('.checkbox-mois');
    var fraismensuelle = document.querySelector('#fraismensuelle');
    var fraistotal = document.querySelector('#fraistotal');
    var reductionInput = document.getElementById('reduction');

    // Ajouter un écouteur d'événement sur l'input de réduction
    reductionInput.addEventListener('input', function() {
        // Récupérer la valeur saisie dans l'input de réduction
        var valuereduction = reductionInput.value;
        // Mettre à jour le montant total en soustrayant la réduction
        updateCheckedCount(valuereduction);
    });

    // Fonction pour mettre à jour le montant total en fonction du nombre de cases cochées et de la réduction
    function updateCheckedCount(valuereduction) {
        var checkedCheckboxes = document.querySelectorAll('.checkbox-mois:checked');
        var numberOfCheckedCheckboxes = checkedCheckboxes.length;
        var montantTotal = (numberOfCheckedCheckboxes * fraismensuelle.value) - valuereduction;
        
        // Mettre à jour le contenu de l'élément HTML affichant le montant total
        fraistotal.value = montantTotal;
    }

    // Écoute les changements d'état des cases à cocher
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Met à jour le montant total
            updateCheckedCount(reductionInput.value);
        });
    });

    // Mettre à jour le montant total initial lors du chargement de la page
    updateCheckedCount(reductionInput.value);
});
    --}}