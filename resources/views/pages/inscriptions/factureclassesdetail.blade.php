@extends('layouts.master')
@section('content')


<style>

    a:hover {
        text-decoration: none !important;
    }

    .form-control{
        padding: 0 !important;
        height: 2rem;
        margin-left: 1rem;
    }

    .form-check .form-check-input {
    margin-left: 0  !important;
    }

    tfoot {
        margin-top: 20px; /* Ajoute un espace de 20px au-dessus du tfoot */
        font-size: 15px !important;
    }

    tbody tr:last-child td {
        border-bottom: 20px solid transparent; /* Ajoute un espace de 20px sous la dernière ligne du tbody */
    }
</style>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        @if (Session::has('status'))
        <div id="statusAlert" class="alert alert-success btn-primary">
          {{ Session::get('status') }}
        </div>
        @endif
        <h4 class="card-title">Enregistrement des classes</h4>

        <div class="col-12">

            <div class="row">

                <div class="col-6">
                    <div class="row">

                        {{-- <div class="form-group row mt-3">
                            <div class="col-md-6">
                            <label for="adresses-parents">Adresses parents</label>
                            <input type="text" id="adresses-parents" name="adressesParents" class="form-control">
                            </div>
                            <div class="col-md-6">
                            <label for="autres-renseignements">Autres renseignements</label>
                            <input type="text" id="autres-renseignements" name="autresRenseignements" class="form-control">
                            </div>
                        </div> --}}

                        <form id="validationForm" action="{{ url('detailfacclasse/'.$CODECLAS) }}" method="post">
                            @csrf
                        <div class="form-group">
                            <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                <label for="classe" class="mr-2">Nom classe</label>
                                <input class="form-control" type="text" name="classe" id="classe" value="{{ $CODECLAS }}" style="width: 110px" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                {{-- <label for="prenom_mere" class="mr-2">Prénom</label> --}}
                                <input class="form-control" type="text" name="classe" id="classe" value="{{ $CODECLAS }}" style="width: 150px" readonly>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                {{-- <form id="validationForm" action="{{ url('detailfacclasse/'.$CODECLAS) }}" method="post">
                        @csrf --}}
                    <div class="row">
                        <h5 style="margin-left: 10.5rem; font-size:14.5px !important;">Nouveaux</h5>
                        <h5 style="margin-top: -1.4rem; margin-left: 20rem; font-size:14.5px !important;">Anciens</h5>
                        
                        <label for="scolarite" style="width: 30%; font-size: 14px !important;">Scolarite</label>
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="APAYER" id="scolarite"  style="width: 25%;" value="{{ $donneClasse->APAYER }}" >
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="APAYER2" id="scolarite_a"  style="width: 25%;" value="{{ $donneClasse->APAYER2 }}" >

                        <label for="frais1" id="labelFrais1" style="width: 30%; font-size: 14px !important;">{{ $donneLibelle->LIBELF1 }}</label>
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS1" id="frais1"  style="width: 25%;" value="{{ $donneClasse->FRAIS1 }}" >
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS1_A" id="frais1_a"  style="width: 25%;;" value="{{ $donneClasse->FRAIS1_A }}" >

                        <label for="frais2" id="labelFrais2" style="width: 30%; font-size: 14px !important;">{{ $donneLibelle->LIBELF2 }}</label>
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS2" id="frais2"  style="width: 25%;" value="{{ $donneClasse->FRAIS2 }}" >
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS2_A" id="frais2_a"  style="width: 25%;;" value="{{ $donneClasse->FRAIS2_A }}" >

                        <label for="frais3" id="labelFrais3" style="width: 30% ; font-size: 14px !important;">{{ $donneLibelle->LIBELF3 }}</label>
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS3" id="frais3"  style="width: 25%;" value="{{ $donneClasse->FRAIS3 }}" >
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS3_A" id="frais3_a"  style="width: 25%;;" value="{{ $donneClasse->FRAIS3_A }}" >

                        <label for="frais4" id="labelFrais4" style="width: 30% ; font-size: 14px !important;">{{ $donneLibelle->LIBELF4 }}</label>
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS4" id="frais4"  style="width: 25%;" value="{{ $donneClasse->FRAIS4 }}" >
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS4_A" id="frais4_a"  style="width: 25%;;" value="{{ $donneClasse->FRAIS4_A }}" >

                    </div>
                    <input type="hidden" id="echeancesData" name="echeancesData">

                    <input class="btn-sm btn-primary" id="validerButton" type="submit" style="margin-left: 22.4rem;" value="Valider" >


                </div>
                <div class="col-6">
                    <h5>Tableau de l'échéancier de paiement</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="1" id="flexRadioDefault1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            L'échéancier prend en compte les frais de scolarité seulement
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="2" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            L'échéancier prend en compte tous les frais [<span id="totalFrais"></span>] et [<span id="totalFraisAnciens"></span>]
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <label for="nbEcheances" class="mr-2">Nb. échéance</label>
                                <input class="form-control" type="number" name="nbEcheances" id="nbEcheances" value="{{ $donneClasse->DUREE }}" style="width: 90px" required>
                            </div>
                            <div class="d-flex align-items-center">
                                <label for="dateDebut" class="mr-2">Date de début de paiement</label>
                                {{-- @php
                                    use Carbon\Carbon;

                                    // Convertir la date en format Carbon
                                    $dateOriginal = $donneClasse->DATEDEB;

                                    // Reformater la date au format souhaité 'd/m/Y'
                                    $dateFormatted = Carbon::createFromFormat('Y-m-d', $dateOriginal)->format('d/m/Y');
                                @endphp --}}
                                <input class="form-control" type="date" name="dateDebut" value="{{ $donneClasse->DATEDEB }}" id="dateDebut" style="width: 150px" required>
                            </div>
                            <div class="d-flex align-items-center">
                                <label for="periodicite" class="mr-2">Périodicité</label>
                                <input class="form-control" type="number" name="periodicite" id="periodicite" value="{{ $donneClasse->PERIODICITE }}" style="width: 50px" required>
                                <p class="ml-2">Mois <span class="ml-3"> >= 7 pour exprimer en jours</span></p>
                            </div>
                        </div>
                    </div>

                    
                    
                    <div class="table-responsive" style="overflow: auto;">
                        <table class="table table-striped" style="font-size: 10px;" id="echeancierTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Tranche</th>
                                    <th class="text-center">% nouv</th>
                                    <th class="text-center">% anc</th>
                                    <th class="text-center">Montant</th>
                                    <th class="text-center">Montant2</th>
                                    <th class="text-center" style="display: none;">date paie</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Contenu dynamique -->
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th id="totalPourcentageNouveau"></th>
                                    <th id="totalPourcentageAncien"></th>
                                    <th id="totalMontantNouveau"></th>
                                    <th id="totalMontantAncien"></th>
                                    {{-- <th id="datepaie" ></th>    --}}
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                </form>

                </div>

            </div><br>

            <hr>

            <!-- Tableaux des échéances pour les nouveaux et anciens élèves -->
            <div class="row">
                <div class="col-5">
                    <h5 style="margin-left:2rem;">Nouveaux élèves</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date paie</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyNouveaux">
                                <!-- Contenu dynamique -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>

                                    <th ></th>
                                    <th id="totalMontantNouveauEleve"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-5" style="margin-left: 8rem">
                    <h5 style="margin-left:2rem;">Anciens élèves</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date paie</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyAnciens">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>

                                    <th ></th>
                                    <th id="totalMontantAncienEleve"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <div class="row"> --}}

                {{-- <a class="btn-sm btn-primary" href="" style="margin-bottom: 2rem; float: right">Creer</a> --}}
            {{-- </div> --}}


            {{-- MODAL PERSONNALISER POUR LES ERREURS EVENTUEL DU SCRIPT DU AU INPUT OU AU NON EGALITER DES MOTANT --}}

            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alertModalLabel">Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <strong id="alertModalMessage"></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>
    </div>
</div>

{{-- script pour ajouter readonly sur les inputs si le label est vide --}}

<script>
    // Fonction pour mettre en readonly les champs si le label est vide
    function setReadonlyIfEmpty(labelId, inputId1, inputId2) {
        const label = document.getElementById(labelId);
        if (label && label.textContent.trim() === '') {
            document.getElementById(inputId1).setAttribute('readonly', true);
            document.getElementById(inputId2).setAttribute('readonly', true);
        }
    }

    // Vérification pour chaque couple label/input
    setReadonlyIfEmpty('labelFrais1', 'frais1', 'frais1_a');
    setReadonlyIfEmpty('labelFrais2', 'frais2', 'frais2_a');
    setReadonlyIfEmpty('labelFrais3', 'frais3', 'frais3_a');
    setReadonlyIfEmpty('labelFrais4', 'frais4', 'frais4_a');



     // Fonction pour calculer la somme des frais et mettre à jour le label
     function calculerSommeFrais() {

        // nouveau eleves
        const scolarite = parseFloat(document.getElementById('scolarite').value) || 0;
        const frais1 = parseFloat(document.getElementById('frais1').value) || 0;
        const frais2 = parseFloat(document.getElementById('frais2').value) || 0;
        const frais3 = parseFloat(document.getElementById('frais3').value) || 0;
        const frais4 = parseFloat(document.getElementById('frais4').value) || 0;

        const total = scolarite + frais1 + frais2 + frais3 + frais4;

        // anciens eleves
        const scolarite_a = parseFloat(document.getElementById('scolarite_a').value) || 0;
        const frais1_a = parseFloat(document.getElementById('frais1_a').value) || 0;
        const frais2_a = parseFloat(document.getElementById('frais2_a').value) || 0;
        const frais3_a = parseFloat(document.getElementById('frais3_a').value) || 0;
        const frais4_a = parseFloat(document.getElementById('frais4_a').value) || 0;

        const total_a = scolarite_a + frais1_a + frais2_a + frais3_a + frais4_a ;
        
        // Mettre à jour la somme dans le label
        document.getElementById('totalFrais').textContent = total;
        document.getElementById('totalFraisAnciens').textContent = total_a;
    }

    // Appel initial pour mettre à jour la somme lors du chargement de la page
    calculerSommeFrais();

    // Mettre à jour la somme chaque fois qu'une valeur de frais change pour les nouveau
    document.getElementById('scolarite').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais1').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais2').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais3').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais4').addEventListener('input', calculerSommeFrais);

    // Mettre à jour la somme chaque fois qu'une valeur de frais change pour les anciens
    document.getElementById('scolarite_a').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais1_a').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais2_a').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais3_a').addEventListener('input', calculerSommeFrais);
    document.getElementById('frais4_a').addEventListener('input', calculerSommeFrais);




    // ANCIEN CODE

    //     // Fonction pour recalculer les totaux et mettre à jour les échéances
    // function mettreAJourTotaux() {
    //     let totalMontantNouveau = 0;
    //     let totalMontantAncien = 0;

    //     // Calcul du total des nouveaux élèves
    //     document.querySelectorAll('.montant-nouveau').forEach(function(input) {
    //         totalMontantNouveau += parseFloat(input.value) || 0;
    //     });

    //     // Calcul du total des anciens élèves
    //     document.querySelectorAll('.montant-ancien').forEach(function(input) {
    //         totalMontantAncien += parseFloat(input.value) || 0;
    //     });

    //     // Mise à jour des totaux dans le tableau
    //     document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
    //     document.getElementById('totalMontantAncienEleve').innerText = totalMontantAncien;

    //      // Mettre à jour les montants des tranches dans le tableau des échéances
    //     mettreAJourMontantsTranches();
    // }

    // // Fonction pour mettre à jour les montants des tranches dans le tableau des échéances
    // function mettreAJourMontantsTranches() {
    //     const montantNouveau = parseFloat(document.getElementById('totalMontantNouveauEleve').innerText);
    //     const montantAncien = parseFloat(document.getElementById('totalMontantAncienEleve').innerText);

    //     const nbEcheances = parseInt(document.getElementById('nbEcheances').value) || 1;
    //     const pourcentageParTranche = (100 / nbEcheances).toFixed(2);

    //     // Parcourir les lignes du tableau des échéances pour mettre à jour les montants
    //     document.querySelectorAll('#tableBody tr').forEach(function(row, index) {
    //         const montantTrancheNouveau = Math.ceil(montantNouveau * pourcentageParTranche / 100);
    //         const montantTrancheAncien = Math.ceil(montantAncien * pourcentageParTranche / 100);

    //         // Mettre à jour les cellules des montants
    //         row.querySelector('#montantTrancheNouveau').innerText = montantTrancheNouveau;
    //         row.cells[4].innerText = montantTrancheAncien;
    //     });

    //     // Mettre à jour les totaux des montants dans le tableau des échéances
    //     document.getElementById('totalMontantNouveau').innerText = montantNouveau;
    //     document.getElementById('totalMontantAncien').innerText = montantAncien;
    // }

    //   // Fonction pour générer le tableau des échéances
    //   function genererEcheancier() {
    //     // Récupérer le nombre d'échéances et les montants
    //     const nbEcheances = parseInt(document.getElementById('nbEcheances').value) || 1;
    //     // recuperer la datedebutpaiement et la peridiocite
    //     const dateDebut = new Date(document.getElementById('dateDebut').value);
    //     const periodicite = parseInt(document.getElementById('periodicite').value) || 1;


    //         // Vérifier si la date de début et la périodicité sont valides
    //         // if (isNaN(dateDebut.getTime()) || periodicite <= 0) {
    //         //     alert('Veuillez entrer une date valide et une périodicité positive.');
    //         //     return;
    //         // }


    //     let montantNouveau, montantAncien;

    //     // Vérifier quelle option radio est sélectionnée
    //     if (document.getElementById('flexRadioDefault1').checked) {
    //     // Option 1 : seulement les frais de scolarité
    //     montantNouveau = parseFloat(document.getElementById('scolarite').value);
    //     montantAncien = parseFloat(document.getElementById('scolarite_a').value);
    //     } else {
    //     // Option 2 : tous les frais
    //     montantNouveau = parseFloat(document.getElementById('totalFrais').textContent);
    //     montantAncien = parseFloat(document.getElementById('totalFraisAnciens').textContent);
    //     }


    //     // const montantScolarite = parseFloat(document.getElementById('scolarite').value);  // Exemple de montant scolarité pour nouveau
    //     // const montantScolariteA = parseFloat(document.getElementById('scolarite_a').value); // Exemple de montant scolarité pour ancien

    //     const tbody = document.getElementById('tableBody');
    //     const tbodyNouveaux = document.getElementById('tbodyNouveaux');
    //     const tbodyAnciens = document.getElementById('tbodyAnciens');
        
    //     // Vider les tableaux
    //     tbody.innerHTML = '';
    //     tbodyNouveaux.innerHTML = '';
    //     tbodyAnciens.innerHTML = '';


    //     // Variables pour stocker les totaux
    //     let totalPourcentageNouveau = 0;
    //     let totalPourcentageAncien = 0;
    //     let totalMontantNouveau = 0;
    //     let totalMontantAncien = 0;

    //     // Calculer le pourcentage et les montants par tranche
    //     const pourcentageParTranche = (100 / nbEcheances).toFixed(2); // Pourcentage par tranche

    //     for (let i = 1; i <= nbEcheances; i++) {
    //         // Copie de la date de début pour calculer la date d'échéance
    //         const datePaiement = new Date(dateDebut);

    //         // Calcul de la nouvelle date d'échéance
    //         if (periodicite < 7) {
    //             // Ajouter des mois si la périodicité est inférieure à 7
    //             datePaiement.setMonth(datePaiement.getMonth() + (i - 1) * periodicite);
    //         } else {
    //             // Ajouter des jours si la périodicité est supérieure ou égale à 7
    //             datePaiement.setDate(datePaiement.getDate() + (i - 1) * periodicite);
    //         }

    //         // Format de la date d'échéance (exemple : 24/10/2023)
    //         const datePaiementFormat = datePaiement.toLocaleDateString('fr-FR');

    //         const montantTrancheNouveau = Math.ceil(montantNouveau * pourcentageParTranche / 100);
    //         const montantTrancheAncien = Math.ceil(montantAncien * pourcentageParTranche / 100);



    //         // Ajouter aux totaux
    //         totalPourcentageNouveau += parseFloat(pourcentageParTranche);
    //         totalPourcentageAncien += parseFloat(pourcentageParTranche);
    //         totalMontantNouveau += parseFloat(montantTrancheNouveau);
    //         totalMontantAncien += parseFloat(montantTrancheAncien);


    //         // Créer une nouvelle ligne pour chaque échéance
    //         const row = `
    //             <tr>
    //                 <td>${i}</td>
    //                 <td>${pourcentageParTranche}%</td>
    //                 <td>${pourcentageParTranche}%</td>
    //                 <td id="montantTrancheNouveau">${montantTrancheNouveau}</td>
    //                 <td>${montantTrancheAncien}</td>
    //             </tr>
    //         `;
    //         tbody.insertAdjacentHTML('beforeend', row);

    //         // Créer et insérer les lignes dans le tableau des nouveaux élèves
    //         const rowNouveau = `
    //             <tr>
    //                 <td>${i}</td>
    //                 <td>${datePaiementFormat}</td>
    //                 <td><input type="number" class="form-control montant-nouveau" value="${montantTrancheNouveau}" /></td>

    //             </tr>
    //         `;
    //         tbodyNouveaux.insertAdjacentHTML('beforeend', rowNouveau);

    //         // Créer et insérer les lignes dans le tableau des anciens élèves
    //         const rowAncien = `
    //             <tr>
    //                 <td>${i}</td>
    //                 <td>${datePaiementFormat}</td>
    //                 <td><input type="number" class="form-control montant-ancien" value="${montantTrancheAncien}" /></td>
    //             </tr>
    //         `;
    //         tbodyAnciens.insertAdjacentHTML('beforeend', rowAncien);


    //             // Insérer les lignes dynamiques dans les tableaux
    //             // tbody.insertAdjacentHTML('beforeend', row);
    //             // tbodyNouveaux.insertAdjacentHTML('beforeend', rowNouveau);
    //             // tbodyAnciens.insertAdjacentHTML('beforeend', rowAncien);
    //     }

    //     // Mettre à jour les totaux dans le tfoot
    //     document.getElementById('totalPourcentageNouveau').innerText = totalPourcentageNouveau.toFixed(2) + '%';
    //     document.getElementById('totalPourcentageAncien').innerText = totalPourcentageAncien.toFixed(2) + '%';
    //     document.getElementById('totalMontantNouveau').innerText = totalMontantNouveau;
    //     document.getElementById('totalMontantAncien').innerText = totalMontantAncien;
    //     document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
    //     document.getElementById('totalMontantAncienEleve').innerText = totalMontantAncien;


    //         // Réattacher les événements sur les champs de montants modifiables
    //         document.querySelectorAll('.montant-nouveau, .montant-ancien').forEach(function(input) {
    //             input.addEventListener('input', mettreAJourTotaux);
    //         });

    // }




    // NOUVEAU CODE 

// Fonction pour recalculer les totaux et mettre à jour les échéances
function mettreAJourTotaux() {
    let totalMontantNouveau = 0;
    let totalMontantAncien = 0;

    // Calcul du total des nouveaux élèves
    document.querySelectorAll('.montant-nouveau').forEach(function(input) {
        totalMontantNouveau += parseFloat(input.value) || 0;
    });

    // Calcul du total des anciens élèves
    document.querySelectorAll('.montant-ancien').forEach(function(input) {
        totalMontantAncien += parseFloat(input.value) || 0;
    });

    // Mise à jour des totaux dans le tableau
    document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
    document.getElementById('totalMontantAncienEleve').innerText = totalMontantAncien;
      // Mettre à jour les totaux dans le tfoot

        document.getElementById('totalMontantNouveau').innerText = totalMontantNouveau;
        document.getElementById('totalMontantAncien').innerText = totalMontantAncien;


    // Mise à jour des pourcentages dans le tableau des échéances
    recalculerPourcentages(totalMontantNouveau, totalMontantAncien);
}

// Fonction pour recalculer les pourcentages dans le tableau des échéances
function recalculerPourcentages(totalMontantNouveau, totalMontantAncien) {
    let totalPourcentageNouveau = 0;
    let totalPourcentageAncien = 0;

    // Recalculer les pourcentages pour chaque tranche de nouveaux élèves
    document.querySelectorAll('.montant-nouveau').forEach(function(input, index) {
        const montant = parseFloat(input.value) || 0;
        const pourcentage = totalMontantNouveau > 0 ? (montant / totalMontantNouveau * 100).toFixed(2) : 0;

        // Mettre à jour le pourcentage pour chaque tranche avec le symbole %
        document.getElementById(`pourcentageTrancheNouveau-${index + 1}`).innerText = pourcentage + '%';

        // Ajouter le pourcentage calculé au total sans le symbole %
        totalPourcentageNouveau += parseFloat(pourcentage);
    });

    // Recalculer les pourcentages pour chaque tranche d'anciens élèves
    document.querySelectorAll('.montant-ancien').forEach(function(input, index) {
        const montant = parseFloat(input.value) || 0;
        const pourcentage = totalMontantAncien > 0 ? (montant / totalMontantAncien * 100).toFixed(2) : 0;

        // Mettre à jour le pourcentage pour chaque tranche avec le symbole %
        document.getElementById(`pourcentageTrancheAncien-${index + 1}`).innerText = pourcentage + '%';

        // Ajouter le pourcentage calculé au total sans le symbole %
        totalPourcentageAncien += parseFloat(pourcentage);
    });

    // Mettre à jour le total des pourcentages avec le symbole % dans le tfoot
    document.getElementById('totalPourcentageNouveau').innerText = totalPourcentageNouveau.toFixed(2) + '%';
    document.getElementById('totalPourcentageAncien').innerText = totalPourcentageAncien.toFixed(2) + '%';
}

// Fonction pour générer le tableau des échéances
function genererEcheancier() {
    const nbEcheances = parseInt(document.getElementById('nbEcheances').value) || 1;
    const dateDebut = new Date(document.getElementById('dateDebut').value);
    const periodicite = parseInt(document.getElementById('periodicite').value) || 1;

    let montantNouveau, montantAncien;

    if (document.getElementById('flexRadioDefault1').checked) {
        montantNouveau = parseFloat(document.getElementById('scolarite').value);
        montantAncien = parseFloat(document.getElementById('scolarite_a').value);
    } else {
        montantNouveau = parseFloat(document.getElementById('totalFrais').textContent);
        montantAncien = parseFloat(document.getElementById('totalFraisAnciens').textContent);
    }

    const tbody = document.getElementById('tableBody');
    const tbodyNouveaux = document.getElementById('tbodyNouveaux');
    const tbodyAnciens = document.getElementById('tbodyAnciens');

    // Vider les tableaux
    tbody.innerHTML = '';
    tbodyNouveaux.innerHTML = '';
    tbodyAnciens.innerHTML = '';

    for (let i = 1; i <= nbEcheances; i++) {
        const datePaiement = new Date(dateDebut);
        if (periodicite < 7) {
            datePaiement.setMonth(datePaiement.getMonth() + (i - 1) * periodicite);
        } else {
            datePaiement.setDate(datePaiement.getDate() + (i - 1) * periodicite);
        }

        const datePaiementFormat = datePaiement.toLocaleDateString('fr-FR');

        const montantTrancheNouveau = Math.ceil(montantNouveau / nbEcheances);
        const montantTrancheAncien = Math.ceil(montantAncien / nbEcheances);

        // Créer une nouvelle ligne pour chaque échéance
        const row = `
            <tr class="echeance-row">
                <td>${i}</td>
                <td id="pourcentageTrancheNouveau-${i}">0%</td>
                <td id="pourcentageTrancheAncien-${i}">0%</td>
                <td id="montantTrancheNouveau-${i}">${montantTrancheNouveau}</td>
                <td id="montantTrancheAncien-${i}">${montantTrancheAncien}</td>
                <td id="datepaie-${i}" classe=".date-paiement" style="display: none;">${datePaiementFormat}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);

        // Créer et insérer les lignes dans le tableau des nouveaux élèves
        const rowNouveau = `
            <tr>
                <td>${i}</td>
                <td>${datePaiementFormat}</td>
                <td><input type="number" class="form-control montant-nouveau" value="${montantTrancheNouveau}" data-tranche-nouveau="${i}" /></td>
            </tr>
        `;
        tbodyNouveaux.insertAdjacentHTML('beforeend', rowNouveau);

        // Créer et insérer les lignes dans le tableau des anciens élèves
        const rowAncien = `
            <tr>
                <td>${i}</td>
                <td>${datePaiementFormat}</td>
                <td><input type="number" class="form-control montant-ancien" value="${montantTrancheAncien}" data-tranche-ancien="${i}" /></td>
            </tr>
        `;
        tbodyAnciens.insertAdjacentHTML('beforeend', rowAncien);
    }




    // Réattacher les événements sur les champs de montants modifiables
    document.querySelectorAll('.montant-nouveau').forEach(function(input) {
        input.addEventListener('input', function() {
            const trancheIndex = input.getAttribute('data-tranche-nouveau');
            document.getElementById(`montantTrancheNouveau-${trancheIndex}`).innerText = input.value;
            mettreAJourTotaux(); // Met à jour les totaux après chaque modification
        });
    });

    document.querySelectorAll('.montant-ancien').forEach(function(input) {
        input.addEventListener('input', function() {
            const trancheIndex = input.getAttribute('data-tranche-ancien');
            document.getElementById(`montantTrancheAncien-${trancheIndex}`).innerText = input.value;
            mettreAJourTotaux(); // Met à jour les totaux après chaque modification
        });
    });

    // Calculer les pourcentages initiaux
    mettreAJourTotaux();
}

    // Attacher un événement au champ Nb. échéance pour recalculer à chaque changement
    document.getElementById('nbEcheances').addEventListener('input', genererEcheancier);
    document.getElementById('scolarite').addEventListener('input', genererEcheancier);
    document.getElementById('scolarite_a').addEventListener('input', genererEcheancier);
    document.getElementById('frais1').addEventListener('input', genererEcheancier);
    document.getElementById('frais1_a').addEventListener('input', genererEcheancier);
    document.getElementById('frais2').addEventListener('input', genererEcheancier);
    document.getElementById('frais2_a').addEventListener('input', genererEcheancier);
    document.getElementById('frais3').addEventListener('input', genererEcheancier);
    document.getElementById('frais3_a').addEventListener('input', genererEcheancier);
    document.getElementById('frais4').addEventListener('input', genererEcheancier);
    document.getElementById('frais4_a').addEventListener('input', genererEcheancier);
    document.getElementById('dateDebut').addEventListener('change', genererEcheancier);
    document.getElementById('periodicite').addEventListener('input', genererEcheancier);


    

    // Ajouter des écouteurs aux boutons radio pour recalculer à chaque changement d'option
    document.getElementById('flexRadioDefault1').addEventListener('change', genererEcheancier);
    document.getElementById('flexRadioDefault2').addEventListener('change', genererEcheancier);

    // Générer l'échéancier initialement
    genererEcheancier();



    document.getElementById('validerButton').addEventListener('click', function(event) {
    // Empêcher la soumission du formulaire si nécessaire
    event.preventDefault();

        // Calculer le total des montants dans le tableau des nouveaux élèves
    let totalMontantNouveauTableau = 0;
    let totalMontantAncienTableau = 0;
    document.querySelectorAll('.montant-nouveau').forEach(function(input) {
        totalMontantNouveauTableau += parseFloat(input.value) || 0;
    });

    document.querySelectorAll('.montant-ancien').forEach(function(input) {
        totalMontantAncienTableau += parseFloat(input.value) || 0;
    });

    if (document.getElementById('flexRadioDefault1').checked) {
        montantNouveauUtilise = parseFloat(document.getElementById('scolarite').value);
        montantAncienUtilise = parseFloat(document.getElementById('scolarite_a').value);
    } else {
        montantNouveauUtilise = parseFloat(document.getElementById('totalFrais').textContent);
        montantAncienUtilise = parseFloat(document.getElementById('totalFraisAnciens').textContent);
    }
    // Récupérer le montant des nouveaux à utiliser
    // const montantNouveauUtilise = parseFloat(document.getElementById('montantNouveauUtilise').value) || 0;

    // Vérifier si les montants correspondent pour les nouveaux élèves
    if (totalMontantNouveauTableau !== montantNouveauUtilise) {
        showModalMessage('Le total des montants dans le tableau des nouveaux élèves ne correspond pas au montant des nouveaux à utiliser.');
        return; // Ne pas soumettre le formulaire
    }

    // Vérifier si les montants correspondent pour les anciens élèves
    if (totalMontantAncienTableau !== montantAncienUtilise) {
        showModalMessage('Le total des montants dans le tableau des anciens élèves ne correspond pas au montant des anciens à utiliser.');
        return; // Ne pas soumettre le formulaire
    }

        // Vérifier si les champs requis sont remplis
        const nbEcheances = document.getElementById('nbEcheances').value.trim();
        const dateDebut = document.getElementById('dateDebut').value.trim();
        // const periodicite = document.getElementById('periodicite').value.trim();

        let errors = [];

        if (!nbEcheances) {
            errors.push('Le nombre d\'échéances est requis.');
        }

        if (!dateDebut) {
            errors.push('La date de début de paiement est requise.');
        }

        // if (!periodicite) {
        //     errors.push('La périodicité est requise.');
        // }

        // Afficher les messages d'erreur s'il y en a
        if (errors.length > 0) {
            showModalMessage( errors.join('\n'));
            return; // Ne pas soumettre le formulaire
        }

        // Récupérer la valeur de la classe
        const classe = document.getElementById('classe').value;

        // Collecter les données des échéances
        let echeances = [];

        // Pour chaque ligne d'échéance
        document.querySelectorAll('.echeance-row').forEach(function(row, index) {
            const tranche = index + 1;
                    // Récupérer les pourcentages de la ligne (nouveaux et anciens)
            const pourcentageNouveau = document.getElementById(`pourcentageTrancheNouveau-${tranche}`).textContent.replace('%', '');
            const pourcentageAncien = document.getElementById(`pourcentageTrancheAncien-${tranche}`).textContent.replace('%', '');

            // const pourcentageTrancheNouveau = document.getElementById(`pourcentageTrancheNouveau-${tranche}`).textContent;
            // const pourcentageTrancheAncien = document.getElementById(`pourcentageTrancheAncien-${tranche}`).textContent;
            const montantTrancheNouveau = document.getElementById(`montantTrancheNouveau-${tranche}`).textContent;
            const montantTrancheAncien = document.getElementById(`montantTrancheAncien-${tranche}`).textContent;
            const datePaiement = document.getElementById(`datepaie-${tranche}`).textContent;

            // Convertir en nombre
            const nombreNouveau = parseFloat(pourcentageNouveau);
            const nombreAncien = parseFloat(pourcentageAncien);

            // Convertir en fractions
            const fractionNouveau = nombreNouveau / 100;
            const fractionAncien = nombreAncien / 100;

            // console.log(fractionNouveau);
            

            // Ajouter ces données dans la collection
            echeances.push(
            {
                tranche: tranche,
                pourcentage_nouveau: fractionNouveau,
                pourcentage_ancien: fractionAncien,
                montant_nouveau: montantTrancheNouveau,
                montant_ancien: montantTrancheAncien,
                date_paiement: datePaiement,
                classe: classe
            });
        });

        // Convertir les données en JSON et les stocker dans un champ caché
        document.getElementById('echeancesData').value = JSON.stringify(echeances);
        // Soumettre le formulaire si la validation est réussie
        document.getElementById('validationForm').submit();    
        


});

        // les pourcentage sous forme de fraction
        function pourcentageEnFraction(pourcentage) {
            // Convertir le pourcentage en fraction
            const fractionNumerator = Math.round(pourcentage * 100); // Multiplier par 100 pour avoir une fraction entière
            const fractionDenominator = 10000; // Dénominateur fixe
            return `${fractionNumerator}/${fractionDenominator}`;
        }

        // Fonction pour afficher le message dans le modal
        function showModalMessage(message) {
            // Convertir les sauts de ligne (\n) en <br> pour une bonne mise en forme dans le HTML
            const formattedMessage = message.replace(/\n/g, '<br>');

            // Mettre à jour le contenu du modal avec le message formaté
            document.getElementById('alertModalMessage').innerHTML = formattedMessage;

            // Afficher le modal
            let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
        }
    

    // soumettre le formulaire dans un nouvell onglet

// document.getElementById('validerButton').addEventListener('click', function(event) {
//     event.preventDefault(); // Empêche la soumission normale du formulaire

//     // Récupérer l'URL d'action du formulaire
//     const form = document.getElementById('validationForm');
//     const formAction = form.getAttribute('action');
//     const formMethod = form.getAttribute('method');

//     // Créer un nouvel onglet pour soumettre le formulaire
//     const newTab = window.open('', '_blank'); // Ouvrir un nouvel onglet

//     // Créer un formulaire temporaire dans la nouvelle fenêtre
//     const tempForm = newTab.document.createElement('form');
//     tempForm.action = formAction;
//     tempForm.method = formMethod;

//     // Copier les champs du formulaire original dans le formulaire temporaire
//     Array.from(form.elements).forEach(function(element) {
//         if (element.name && element.value) {
//             const input = newTab.document.createElement('input');
//             input.type = 'hidden';
//             input.name = element.name;
//             input.value = element.value;
//             tempForm.appendChild(input);
//         }
//     });

//     // Soumettre le formulaire temporaire dans le nouvel onglet
//     newTab.document.body.appendChild(tempForm);
//     tempForm.submit();
// });
</script>

@endsection


{{-- 


--}}