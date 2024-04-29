@extends('layouts.master')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" style="text-align: center">Paiement</h4>

                <form action="{{url('/savepaiementcontrat')}}" method="POST">
                    @csrf
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
                                            <input class="typeaheads" id="fraismensuelle" name="montantcontrat" type="text" value="{{ $fraismensuelle }}" readonly>
                                        </div>
                                    </div>
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
                        <button class="btn btn-light">Annuler</button>
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

        // Accéder à la valeur de l'élément input
        var valeurInput = fraismensuelle.value;

        // console.log(valeurInput);
        // Fonction pour mettre à jour le nombre de cases cochées
        function updateCheckedCount() {
            var checkedCheckboxes = document.querySelectorAll('.checkbox-mois:checked');
            var numberOfCheckedCheckboxes = checkedCheckboxes.length;
  
            // Mettre à jour le contenu de l'élément HTML
            // document.getElementById('checked-count').textContent = numberOfCheckedCheckboxes * valeurInput;
            var jojoj = document.getElementById('checked-count');

             jojoj.textContent = numberOfCheckedCheckboxes * valeurInput;
             var montanttot = jojoj.textContent;

            // Mettre à jour la valeur de l'input avec le montant total
            fraistotal.value = montanttot;

        }
  
        // Écoute les changements d'état des cases à cocher
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Met à jour le nombre de cases cochées
                updateCheckedCount();
            });
        });

    });
  </script>