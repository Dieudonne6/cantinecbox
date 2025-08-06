
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
  </style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button></br>

        @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div></br>
        @endif

        @if(Session::has('erreur'))
            <div id="statusAlert" class="alert alert-danger btn-primary">
            {{ Session::get('erreur')}}
            </div></br>
        @endif
                <h4 class="card-title" style="text-align: center">Annulation de la facture de paiement de <strong>{{ $factureOriginale->nom }}</strong></h4>

                <form action="{{url('avoirfactureetmodification/'.$codemecef)}}" method="POST">
                    @csrf
                    {{-- @if(Session::has('id_usercontrat'))
                        <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
                    @endif --}}
                    <div class="col-md-8 mx-auto grid-margin stretch-card">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Infos de la facture de vente</h4>
                                {{-- <p class="card-description">
                                    Veuillez remplir les champs
                                </p> --}}
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Date de paiement</label>
                                        <div id="the-basics">
                                            <input class="typeaheads" type="datetime-local" id="date" name="dateAncienne"
                                                value="{{ $factureOriginale->datepaiementcontrat }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Montant</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="fraismensuelleAvoir" name="montantcontratAncien" type="text" value="{{ $factureOriginale->montant_total }}" readonly>
                                            <input class="typeaheads" id="fraismensuelleAvoircache" name="montantcontratReelAncien" type="hidden" value="{{ $fraismensuelle }}" >
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mx-auto grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Entrer le codemef de la facture originale</h4>
                                <div class="form-group">
                                    <div class="col">
                                        {{-- <p>Nombre de cases cochées : <span id="checked-count">0</span></p> --}}
                                        <label for="codemecefEntrer">Codemecef Facture Originale</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="codemecefEntrer" name="inputCodemecef" type="text" >
                                            {{-- <p style="visibility: hidden"><span id="checked-count">0</span></p> --}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mx-auto grid-margin stretch-card">
                        <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Type de Modification</h4>
                            <div class="form-group row">
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="typeFormulaire" id="radio1" value="corriger_eleve" checked>
                                        <label class="form-check-label" for="radio1">Corriger Elève</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="typeFormulaire" id="radio2" value="corriger_mois" >
                                        <label class="form-check-label" for="radio2">Corriger Mois</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire 1 : Par Codemef -->
                    <div id="form1-container">
                        <div class="col-md-8 mx-auto grid-margin stretch-card">
                            <select class="form-select js-example-basic-multiple" id="eleve" name="eleve" style="width: 100%;">
                              <option value="">Sélectionner l'élève</option>
                                @foreach ($eleves as $eleve)
                                <option value="{{ $eleve->MATRICULE }}">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>

                    <!-- Formulaire 2 : Par Justificatif -->
                    <div id="form2-container" class="d-none">
                        <div class="col-md-8 mx-auto grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                        @csrf
                                        {{-- @if(Session::has('id_usercontrat'))
                                             $id_usercontrat = Session::get('id_usercontrat'); 
                                            <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
                                        @endif --}}
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
                                                                <input class="typeaheads" id="fraismensuellecache" name="montantcontratReel" type="hidden" value="{{ $fraismensuelle }}" >
                                                            </div>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mx-auto grid-margin stretch-card mt-5 mb-5">

                        <input type="submit" class="btn btn-primary mr-2" value="Confirmer">
                        <input type="reset" class="btn btn-light" value="Annuler">
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const radio1 = document.getElementById("radio1");
        const radio2 = document.getElementById("radio2");
        const form1 = document.getElementById("form1-container");
        const form2 = document.getElementById("form2-container");
    
        function toggleForms() {
            if (radio1.checked) {
                form1.classList.remove("d-none");
                form2.classList.add("d-none");
            } else {
                form1.classList.add("d-none");
                form2.classList.remove("d-none");
            }
        }
    
        radio1.addEventListener("change", toggleForms);
        radio2.addEventListener("change", toggleForms);
    
        toggleForms(); // Au cas où une valeur est déjà cochée
    });
</script>
    

    <style>
        input[type="radio"] {
            transform: scale(1.5); /* Agrandit le bouton */
            accent-color: #b51818; /* Change la couleur principale (fonctionne sur navigateurs modernes) */
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
    
{{-- 
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
    </script> --}}