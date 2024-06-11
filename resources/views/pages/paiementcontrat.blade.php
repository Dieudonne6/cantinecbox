@extends('layouts.master')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" style="text-align: center">Paiement</h4>

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
                                            <input class="typeaheads" type="date" id="date" name="date"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Montant Mensuel</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="fraismensuelle" name="montantcontrat" type="text" value="{{ $fraismensuelle }}" >
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
                                <h4 class="card-title">Mois a payer</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                @foreach ($moisCorrespondants as $id_moiscontrat => $nom_moiscontrat)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="moiscontrat[]" class="form-check-input checkbox-mois"
                                                                value="{{ $id_moiscontrat }}">
                                                            {{ $nom_moiscontrat }}
                                                        </label>
                                                    </div>
                                                @endforeach
                    
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
                    <div class="col-md-8 mx-auto grid-margin stretch-card mt-5">

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