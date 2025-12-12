@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
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



                            /* Styles pour tous les champs input */
                            .form-control {
                                border: 2px solid #ced4da;   /* bordure plus épaisse */
                                border-radius: 0.3rem;       /* coins arrondis */
                                padding: 0.5rem 0.75rem;
                                background-color: #f9f9f9;   /* fond léger */
                                transition: all 0.3s ease;
                            }

                            /* Effet au focus */
                            .form-control:focus {
                                border-color: #007bff;       /* bordure bleue */
                                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                                background-color: #fff;
                            }
                            /* Pour le select */
                            .form-select {
                                border: 2px solid #ced4da;
                                border-radius: 0.5rem;
                                background-color: #f9f9f9;
                                transition: all 0.3s ease;
                            }

                            .form-select:focus {
                                border-color: #007bff;
                                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                                background-color: #fff;
                            }
                        </style>
                        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <br>
                        <br>
                    </div>
                    <div class="card-body">
                        @if (Session::has('status'))
                            <div id="statusAlert" class="alert alert-success btn-primary">
                                {{ Session::get('status') }}
                            </div>
                        @endif
                        <form action="{{ url('modifieprofil/' . $eleve->MATRICULE) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="hidden" name="duree" value="{{ $elev->classe->DUREE }}">
                                    <h5>Changement de profil</h5>
                                    <div class="mb-2">
                                        <input type="" class="form-control" id="nom" placeholder="" style="border: none;"
                                            value="{{ $eleve->NOM }}" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <input type="" class="form-control" id="prenom" placeholder="" style="border: none;"
                                            value="{{ $eleve->PRENOM }}" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <input type="" class="form-control" id="classe" placeholder="" style="border: none;"
                                            name="classe" value="{{ $eleve->CODECLAS }}" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <select class="js-example-basic-multiple w-100" id="exampleSelectGender"
                                            name="type" readonly>
                                            <option value="1" {{ $eleve->STATUTG == 1 ? 'selected' : '' }}>Nouveau
                                            </option>
                                            <option value="2" {{ $eleve->STATUTG == 2 ? 'selected' : '' }}>Ancien
                                            </option>
                                            <option value="3" {{ $eleve->STATUTG == 3 ? 'selected' : '' }}>Transferer
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <h5 style="margin-left:12.5rem;">Données classes</h5>
                                    <div class="d-flex mb-2">
                                        <label class="w-100">Scolarité</label>
                                        <input type="" id="classesco" class="form-control"
                                            value="{{ $elev->STATUTG == 1 ? $elev->classe->APAYER : $elev->classe->APAYER2 }}"
                                            placeholder="" readonly>
                                    </div>

                                    <div class="mb-2 d-flex">
                                        <label class="w-100">{{ $libel->LIBELF1 ?? '' }}</label>
                                        <input type="text" id="fraisclasse1" class="form-control"
                                            value="{{ $elev->STATUTG == 1 ? $elev->classe->FRAIS1 ?? 0 : $elev->classe->FRAIS1_A ?? 0 }}"
                                            readonly>
                                    </div>

                                    <div class="mb-2 d-flex">
                                        <label class="w-100">{{ $libel->LIBELF2 ?? '' }}</label>
                                        <input type="text" id="fraisclasse2" class="form-control"
                                            value="{{ $elev->STATUTG == 1 ? $elev->classe->FRAIS2 ?? 0 : $elev->classe->FRAIS2_A ?? 0 }}"
                                            readonly>
                                    </div>
                                    <div class="mb-2 d-flex">
                                        <label class="w-100">{{ $libel->LIBELF3 ?? '' }}</label>
                                        <input type="text" id="fraisclasse3" class="form-control"
                                            value="{{ $elev->STATUTG == 1 ? $elev->classe->FRAIS3 ?? 0 : $elev->classe->FRAIS3_A ?? 0 }}"
                                            readonly>
                                    </div>

                                    <div class="d-flex mb-2">
                                        <label class="w-100">{{ $libel->LIBELF4 ?? '' }}</label>
                                        <input type="text" id="fraisclasse4" class="form-control"
                                            value="{{ $elev->STATUTG == 1 ? $elev->classe->FRAIS4 ?? 0 : $elev->classe->FRAIS4_A ?? 0 }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <h5>Donnée eleve</h5>
                                    <div class="mb-2">
                                        <input id="apayer" type="" class="form-control"
                                            value="{{ $eleve->APAYER }}" readonly>
                                        <input type="hidden" name="sco" id="sco_hidden" value="{{ $eleve->APAYER }}">
                                    </div>
                                    <div class="mb-2">
                                        <input id="frais1" type="" class="form-control" placeholder=""
                                            value="{{ $eleve->FRAIS1 }}" readonly>
                                        <input type="hidden" name="frais1" id="frais1_hidden"
                                            value="{{ $eleve->FRAIS1 }}">
                                    </div>
                                    <div class="mb-2">
                                        <input id="frais2" type="" class="form-control" placeholder=""
                                            value="{{ $eleve->FRAIS2 }}" readonly>
                                        <input type="hidden" name="frais2" id="frais2_hidden"
                                            value="{{ $eleve->FRAIS2 }}">
                                    </div>
                                    <div class="mb-2">
                                        <input id="frais3" type="" class="form-control" placeholder=""
                                            value="{{ $eleve->FRAIS3 }}" readonly>
                                        <input type="hidden" name="frais3" id="frais3_hidden"
                                            value="{{ $eleve->FRAIS3 }}">
                                    </div>
                                    <div class="mb-2">
                                        <input id="frais4" type="" class="form-control" placeholder=""
                                            value="{{ $eleve->FRAIS4 }}" readonly>
                                        <input type="hidden" name="frais4" id="frais4_hidden"
                                            value="{{ $eleve->FRAIS4 }}">
                                    </div>

                                </div>
                            </div>
              <div class="row">
                <div class="col-lg-4">
                  <h6>Arriere (initial a payer)</h6>
                </div>
                <div class="col-lg-4">
                  <!-- <input id="arriereinitial" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE_INITIAL}}" readonly> -->
                  <input id="arriereinitial" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE_INITIAL}}" readonly>
                </div>
                <div class="col-lg-4">
                  <input id="arriere" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE}}" readonly>
                  <input type="hidden" name="arriere" id="arriere_hidden" value="{{ $eleve->ARRIERE }}">
                </div>
              </div>
                            <div class="row my-3">
                                <div class="col-lg-4">
                                    <h6>Sélectionnez une réduction</h6>

                                </div>
                                <div class="col-lg-4">
                                    <div class="dropup-center dropup">
                                        <select id="class-select" name="reduction"
                                            class="js-example-basic-multiple mb-3">
                                            <option value="">Sélectionnez une réduction</option>
                                            @foreach ($reduction as $reductions)
                                                <option value="{{ $reductions->CodeReduction }}"
                                                    data-type="{{ $reductions->typereduction }}"
                                                    data-sco="{{ $reductions->Reduction_scolarite }}"
                                                    data-arrie="{{ $reductions->Reduction_arriere }}"
                                                    data-frais1="{{ $reductions->Reduction_frais1 }}"
                                                    data-frais2="{{ $reductions->Reduction_frais2 }}"
                                                    data-frais3="{{ $reductions->Reduction_frais3 }}"
                                                    data-frais4="{{ $reductions->Reduction_frais4 }}"
                                                    data-fixesco="{{ $reductions->Reduction_fixe_sco }}"
                                                    data-fixefrais1="{{ $reductions->Reduction_fixe_frais1 }}"
                                                    data-fixefrais2="{{ $reductions->Reduction_fixe_frais2 }}"
                                                    data-fixefrais3="{{ $reductions->Reduction_fixe_frais3 }}"
                                                    data-fixefrais4="{{ $reductions->Reduction_fixe_frais4 }}"
                                                    data-fixearriere="{{ $reductions->Reduction_fixe_arriere }}"
                                                    @if ($reductions->CodeReduction == $eleve->CodeReduction) selected @endif>
                                                    {{ $reductions->LibelleReduction }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary" style="margin-left: 7rem" data-bs-toggle="modal"
                                            data-bs-target="#nouveaumodal">
                                            Creer un Nouveau profil de reduction
                                        </button>




                                    </div>
                                    <div class="col-lg-4 justify-content-end d-flex">
                                        <div class="mr-2">
                                            @if(Session::has('account') && Session::get('account')->groupe && strtoupper(Session::get('account')->groupe->nomgroupe) === 'ADMINISTRATEUR')
                                                <button type="submit" class="btn btn-secondary mb-2" style="margin-top: -4.5rem; padding: 1.3rem">Sauvegarde</button>
                                            @endif
                                            <div class="alert alert-info mt-3" id="montantReduction"
                                                style="font-size: 18px; font-weight: bold; display:none;">
                                                Montant total réduction : 0 F
                                            </div>
                                            <input type="hidden" name="montant_total_reduction"
                                                id="montant_total_reduction" value="0">


                                        </div>
                                        <div>
                                            {{-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolli" aria-controls="offcanvasScrolli">Aide</button> --}}

                                            <div class="offcanvas offcanvas-start" data-bs-scroll="true"
                                                data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolli"
                                                aria-labelledby="offcanvasScrolliLabel">
                                                <div class="offcanvas-header">
                                                    <h5 class="offcanvas-title" id="offcanvasScrolliLabel">Offcanvas with
                                                        body scrolling</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="offcanvas-body">
                                                    <p>Try scrolli the rest of the page to see this option in action.</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                {{-- <button type="submit" class="btn btn-primary">
                Enregistrer
              </button> --}}
                                <br><br><br><br><br>
                        </form>
                    </div>


                                                            <!-- Modal -->
                                      <div class="modal fade" id="nouveaumodal" tabindex="-1"
                                            aria-labelledby="exampleModalLabe" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content calcul-modal">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Fiche de
                                                            réduction</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body profile-content">
                                                        {{-- @if (Session::has('status'))
                                                          <div id="statusAlert" class="alert alert-success btn-primary">
                                                              {{ Session::get('status') }}
                                                          </div>
                                                          @endif --}}

                                                        <div class="row">
                                                            <div class="" id="calcul-one">
                                                                <form class="forms-sample"
                                                                    action="{{ url('/ajouterprofreduction') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <!-- Autres champs ici... -->
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">Numéro
                                                                            réduction</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="exampleInputUsername2"
                                                                                name="Codereduction"
                                                                                value="{{ $newCode }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">Libelle
                                                                            réduction</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="exampleInputUsername2"
                                                                                name="LibelleReduction" placeholder=""
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row align-items-center">
                                                                        <div class="col-auto">
                                                                            <label for="reductionType"
                                                                                class="col-form-label mb-0">Type de
                                                                                réduction</label>
                                                                        </div>
                                                                        <div class="col-auto" style="margin-left: 3rem;">
                                                                            <select
                                                                                class="js-example-basic-multiple form-select"
                                                                                id="reductionType" name="reductionType"
                                                                                required>
                                                                                <option value="1">Réduction par
                                                                                    pourcentage</option>
                                                                                <option value="2">Réduction fixe
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">Réduction
                                                                            accordée sur scolarité</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionScolarite"
                                                                                name="Reduction_scolarite"
                                                                                placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">Réduction
                                                                            accordée sur arrièré</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionArriere"
                                                                                name="Reduction_arriere" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">

                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">{{ $donneLibelle->LIBELF1 }}
                                                                        </label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionFrais1"
                                                                                name="Reduction_frais1" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">{{ $donneLibelle->LIBELF2 }}</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionFrais2"
                                                                                name="Reduction_frais2" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">{{ $donneLibelle->LIBELF3 }}</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionFrais3"
                                                                                name="Reduction_frais3" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="exampleInputUsername2"
                                                                            class="col-sm-8 col-form-label">{{ $donneLibelle->LIBELF4 }}</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control"
                                                                                id="ReductionFrais4"
                                                                                name="Reduction_frais4" placeholder="0">
                                                                        </div>
                                                                    </div>

                                                                    {{--  <div class="form-group row">
                                                        <label for="exampleInputUsername2" class="col-sm-12 col-form-label mb-0">Mode d'application de la réduction sur les écheanciers</label>
                                                        <div class="col-sm-12 mb-2">
                                                            <select class="js-example-basic-multiple w-100" name="mode">
                                                                <option value="1">Agir sur les dernières tranches</option>
                                                                <option value="2">Repartir équitablement sur toutes les tranches</option>
                                                            </select>
                                                        </div>
                                                    </div> --}}

                                                                    <button type="submit"
                                                                        class="btn btn-primary mr-2">Enregistrer</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Fermer</button>
                                                                </form>
                                                            </div>
                                                            <div class="col-lg-4 d-none" id="percentage" style="margin-top: -3rem; background-color:rgb(207, 204, 204) !important;">
                                                                <br></br>
                                                                <br></br>
                                                                <h6 style="margin-top: 1rem">Calculateur de pourcentage de
                                                                    réduction</h6>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <p>Avant réduction</p>
                                                                        <div class="mt-4">
                                                                            <input type="text" class="form-control"
                                                                                id="avantScolarite" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="avantArriere" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <input type="text" class="form-control"
                                                                                id="avantFrais1" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="avantFrais2" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="avantFrais3" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="avantFrais4" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p>Apres réduction</p>
                                                                        <div class="mt-4">
                                                                            <input type="text" class="form-control"
                                                                                id="apresScolarite" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="apresArriere" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <input type="text" class="form-control"
                                                                                id="apresFrais1" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="apresFrais2" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="apresFrais3" placeholder="0">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <input type="text" class="form-control"
                                                                                id="apresFrais4" placeholder="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="my-4 col-lg-12" style="">
                                                                        <button type="button" class="btn btn-secondary" style=" margin-left: 25%; margin-right: 25%;"
                                                                            id="closecalculate">Fermer le
                                                                            calculateur</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            id="calculs">Afficher calculateur de pourcentage</button>
                                                        <div class="offcanvas offcanvas-start" data-bs-scroll="true"
                                                            data-bs-backdrop="false" tabindex="-1"
                                                            id="offcanvasScrolling"
                                                            aria-labelledby="offcanvasScrollingLabel">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                </div>
            </div>
        </div>
    </div><br><br>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action*="modifieprofil"]');
        if (!form) return;

        form.addEventListener('submit', function (e) {
          // Récupère les éléments visibles (colonne élève)
          const apayerVisible = document.getElementById('apayer');
          const frais1Visible = document.getElementById('frais1');
          const frais2Visible = document.getElementById('frais2');
          const frais3Visible = document.getElementById('frais3');
          const frais4Visible = document.getElementById('frais4');
          const arriereVisible = document.getElementById('arriere');

          // Récupère les hidden à envoyer
          const scoHidden = document.getElementById('sco_hidden');
          const frais1Hidden = document.getElementById('frais1_hidden');
          const frais2Hidden = document.getElementById('frais2_hidden');
          const frais3Hidden = document.getElementById('frais3_hidden');
          const frais4Hidden = document.getElementById('frais4_hidden');
          const arriereHidden = document.getElementById('arriere_hidden');

          // Copier les valeurs visibles vers les hidden (si éléments présents)
          if (apayerVisible && scoHidden) scoHidden.value = apayerVisible.value;
          if (frais1Visible && frais1Hidden) frais1Hidden.value = frais1Visible.value;
          if (frais2Visible && frais2Hidden) frais2Hidden.value = frais2Visible.value;
          if (frais3Visible && frais3Hidden) frais3Hidden.value = frais3Visible.value;
          if (frais4Visible && frais4Hidden) frais4Hidden.value = frais4Visible.value;
          if (arriereVisible && arriereHidden) arriereHidden.value = arriereVisible.value;

          // (Optionnel) console.log pour debug — commenter/supprimer en prod
          console.log('Hidden values before submit:', {
            sco: scoHidden ? scoHidden.value : null,
            frais1: frais1Hidden ? frais1Hidden.value : null,
            frais2: frais2Hidden ? frais2Hidden.value : null,
            frais3: frais3Hidden ? frais3Hidden.value : null,
            frais4: frais4Hidden ? frais4Hidden.value : null,
            arriere: arriereHidden ? arriereHidden.value : null,
          });

          // laisser le formulaire se soumettre normalement
        });
      });

      function calculerReductionTotale() {
        // Frais Classe
        const classesco = parseInt(document.getElementById('classesco').value) || 0;
        const fc1 = parseInt(document.getElementById('fraisclasse1').value) || 0;
        const fc2 = parseInt(document.getElementById('fraisclasse2').value) || 0;
        const fc3 = parseInt(document.getElementById('fraisclasse3').value) || 0;
        const fc4 = parseInt(document.getElementById('fraisclasse4').value) || 0;


        // Frais Élève
        const e_sco = parseInt(document.getElementById('apayer').value) || 0;
        const e_f1 = parseInt(document.getElementById('frais1').value) || 0;
        const e_f2 = parseInt(document.getElementById('frais2').value) || 0;
        const e_f3 = parseInt(document.getElementById('frais3').value) || 0;
        const e_f4 = parseInt(document.getElementById('frais4').value) || 0;

        //  Mise à jour des champs visibles + hidden après recalcul
          document.getElementById('apayer').value = e_sco;
          document.getElementById('sco_hidden').value = e_sco;

          document.getElementById('frais1').value = e_f1;
          document.getElementById('frais1_hidden').value = e_f1;

          document.getElementById('frais2').value = e_f2;
          document.getElementById('frais2_hidden').value = e_f2;

          document.getElementById('frais3').value = e_f3;
          document.getElementById('frais3_hidden').value = e_f3;

          document.getElementById('frais4').value = e_f4;
          document.getElementById('frais4_hidden').value = e_f4;

          // L'arrière reste identique ici
          document.getElementById('arriere_hidden').value = document.getElementById('arriere').value;


        const totalClasse = classesco + fc1 + fc2 + fc3 + fc4;
        const totalEleve = e_sco + e_f1 + e_f2 + e_f3 + e_f4;

        const reduction = totalClasse - totalEleve;

        document.getElementById('montantReduction').innerText =
            "Montant total réduction : " + reduction.toLocaleString() + " F";

        //Pou envoyer le montant total de réduction au controller
        document.getElementById('montant_total_reduction').value = reduction;

      }
      // 1) Liste des inputs à surveiller
      const watchedIds = ['classesco','fraisclasse1','fraisclasse2','fraisclasse3','fraisclasse4',
                          'apayer','frais1','frais2','frais3','frais4','arriere'];

      // 2) Ajouter des écouteurs 'input' et 'change'
      watchedIds.forEach(id => {
          const el = document.getElementById(id);
          if (!el) return;
          // si l'input change par JS, certains code ne déclenchent pas 'input' — on ajoute 'change' aussi
          el.addEventListener('input', calculerReductionTotale);
          el.addEventListener('change', calculerReductionTotale);
      });

      // 3) Si un plugin modifie le DOM sans déclencher d'événements, on ajoute un MutationObserver de secours
      const observerTarget = document.querySelector('form[action*="modifieprofil"]') || document.body;
      const observer = new MutationObserver(mutations => {
          // on filtre pour éviter trop d'appels — on appelera le calcul au prochain tick
          window.requestAnimationFrame(calculerReductionTotale);
      });
      observer.observe(observerTarget, { attributes: true, childList: false, subtree: true, attributeFilter: ['value'] });

      // 4) Appel initial (au cas où)
      calculerReductionTotale();


      // On calcule au chargement
      document.addEventListener('DOMContentLoaded', calculerReductionTotale);

      // On recalcule après changement de réduction
      document.getElementById('class-select').addEventListener('change', function () {
          setTimeout(calculerReductionTotale, 200);
      });

    </script>



<script>



  // calculateur de pourcentage pour nouveau
    document.addEventListener('DOMContentLoaded', (event) => {
        function calculatePercentage(avant, apres) {
            if (avant == 0) {
                return 0;
            }
            return ((avant - apres) / avant * 100).toFixed(2) + '%';
        }

        function calculatePercentage1(avant, apres) {
            if (avant == 0) {
                return 0;
            }
            return ((avant - apres) / avant * 100).toFixed(8) + '%';
        }

        function calculateFixe(avant, apres) {
            if (avant == 0) {
                return 0;
            }
            return (avant - apres);
        }

        const reductionType = document.getElementById('reductionType');

        const avantScolarite = document.getElementById('avantScolarite');
        const apresScolarite = document.getElementById('apresScolarite');
        const reductionScolarite = document.getElementById('ReductionScolarite');

        avantScolarite.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionScolarite.value = calculateFixe(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
            } else {
                reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
            }
        });

        apresScolarite.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionScolarite.value = calculateFixe(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
            } else {
                reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
            }
        });

        const avantArriere = document.getElementById('avantArriere');
        const apresArriere = document.getElementById('apresArriere');
        const reductionArriere = document.getElementById('ReductionArriere');

        avantArriere.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionArriere.value = calculateFixe(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
            } else {
                reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
            }
        });

        apresArriere.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionArriere.value = calculateFixe(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
            } else {
                reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
            }
        });

        const avantFrais1 = document.getElementById('avantFrais1');
        const apresFrais1 = document.getElementById('apresFrais1');
        const reductionFrais1 = document.getElementById('ReductionFrais1');

        avantFrais1.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais1.value = calculateFixe(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
            } else {
                reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
            }
        });

        apresFrais1.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais1.value = calculateFixe(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
            } else {
                reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
            }
        });

        const avantFrais2 = document.getElementById('avantFrais2');
        const apresFrais2 = document.getElementById('apresFrais2');
        const reductionFrais2 = document.getElementById('ReductionFrais2');

        avantFrais2.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais2.value = calculateFixe(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
            } else {
                reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
            }
        });

        apresFrais2.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais2.value = calculateFixe(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
            } else {
                reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
            }
        });

        const avantFrais3 = document.getElementById('avantFrais3');
        const apresFrais3 = document.getElementById('apresFrais3');
        const reductionFrais3 = document.getElementById('ReductionFrais3');

        avantFrais3.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais3.value = calculateFixe(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
            } else {
                reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
            }
        });

        apresFrais3.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais3.value = calculateFixe(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
            } else {
                reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
            }
        });

        const avantFrais4 = document.getElementById('avantFrais4');
        const apresFrais4 = document.getElementById('apresFrais4');
        const reductionFrais4 = document.getElementById('ReductionFrais4');

        avantFrais4.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais4.value = calculateFixe(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
            } else {
                reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
            }
        });

        apresFrais4.addEventListener('input', () => {
            if (reductionType.value == 2) { // Réduction fixe
                reductionFrais4.value = calculateFixe(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
            } else {
                reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
            }
        });
    });



  // calculateur de pourcentage pour modifier
    // document.addEventListener('DOMContentLoaded', (event) => {
    //     function calculatePercentage(avant, apres) {
    //         if (avant == 0) {
    //             return 0;
    //         }
    //         return ((avant - apres) / avant * 100).toFixed(2) + '%';
    //     }

    //     function calculatePercentage1(avant, apres) {
    //         if (avant == 0) {
    //             return 0;
    //         }
    //         return ((avant - apres) / avant * 100).toFixed(8) + '%';
    //     }

    //     function calculateFixe(avant, apres) {
    //         if (avant == 0) {
    //             return 0;
    //         }
    //         return (avant - apres);
    //     }

    //     const reductionType = document.getElementById('reductionType');

    //     const avantScolarite = document.getElementById('avantScolarite');
    //     const apresScolarite = document.getElementById('apresScolarite');
    //     const reductionScolarite = document.getElementById('ReductionScolarite');

    //     avantScolarite.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionScolarite.value = calculateFixe(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    //         } else {
    //             reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    //         }
    //     });

    //     apresScolarite.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionScolarite.value = calculateFixe(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    //         } else {
    //             reductionScolarite.value = calculatePercentage1(parseFloat(avantScolarite.value), parseFloat(apresScolarite.value));
    //         }
    //     });

    //     const avantArriere = document.getElementById('avantArriere');
    //     const apresArriere = document.getElementById('apresArriere');
    //     const reductionArriere = document.getElementById('ReductionArriere');

    //     avantArriere.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionArriere.value = calculateFixe(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    //         } else {
    //             reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    //         }
    //     });

    //     apresArriere.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionArriere.value = calculateFixe(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    //         } else {
    //             reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value), parseFloat(apresArriere.value));
    //         }
    //     });

    //     const avantFrais1 = document.getElementById('avantFrais1modif');
    //     const apresFrais1 = document.getElementById('apresFrais1modif');
    //     const reductionFrais1 = document.getElementById('ReductionFrais1modif');

    //     avantFrais1.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais1.value = calculateFixe(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    //         } else {
    //             reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    //         }
    //     });

    //     apresFrais1.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais1.value = calculateFixe(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    //         } else {
    //             reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value), parseFloat(apresFrais1.value));
    //         }
    //     });

    //     const avantFrais2 = document.getElementById('avantFrais2modif');
    //     const apresFrais2 = document.getElementById('apresFrais2modif');
    //     const reductionFrais2 = document.getElementById('ReductionFrais2modif');

    //     avantFrais2.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais2.value = calculateFixe(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    //         } else {
    //             reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    //         }
    //     });

    //     apresFrais2.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais2.value = calculateFixe(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    //         } else {
    //             reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value), parseFloat(apresFrais2.value));
    //         }
    //     });

    //     const avantFrais3 = document.getElementById('avantFrais3modif');
    //     const apresFrais3 = document.getElementById('apresFrais3modif');
    //     const reductionFrais3 = document.getElementById('ReductionFrais3modif');

    //     avantFrais3.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais3.value = calculateFixe(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    //         } else {
    //             reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    //         }
    //     });

    //     apresFrais3.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais3.value = calculateFixe(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    //         } else {
    //             reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value), parseFloat(apresFrais3.value));
    //         }
    //     });

    //     const avantFrais4 = document.getElementById('avantFrais4modif');
    //     const apresFrais4 = document.getElementById('apresFrais4modif');
    //     const reductionFrais4 = document.getElementById('ReductionFrais4modif');

    //     avantFrais4.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais4.value = calculateFixe(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    //         } else {
    //             reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    //         }
    //     });

    //     apresFrais4.addEventListener('input', () => {
    //         if (reductionType.value == 2) { // Réduction fixe
    //             reductionFrais4.value = calculateFixe(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    //         } else {
    //             reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value), parseFloat(apresFrais4.value));
    //         }
    //     });
    // });


    // afficher les infos du modal pour la modification
   // Script pour remplir automatiquement le modal de modification
// document.addEventListener('DOMContentLoaded', function () {
//     var modifModal = document.getElementById('modifModal');

//     modifModal.addEventListener('show.bs.modal', function (event) {
//         var button = event.relatedTarget; // Bouton qui a déclenché le modal

//         // Récupération des informations depuis les data-attributes
//         var codeReduction = button.getAttribute('data-code');
//         var typereduction = button.getAttribute('data-type');
//         var libelleReduction = button.getAttribute('data-libelle');
//         var reductionScolarite = button.getAttribute('data-scolarite');
//         var reductionArriere = button.getAttribute('data-arriere');
//         var reductionFrais1 = button.getAttribute('data-frais1');
//         var reductionFrais2 = button.getAttribute('data-frais2');
//         var reductionFrais3 = button.getAttribute('data-frais3');
//         var reductionFrais4 = button.getAttribute('data-frais4');

//         // Sélectionner le bon élément <select>
//         var select = modifModal.querySelector('#reductionTypeModif');
//         select.value = (typereduction === 'F' ? '2' : '1');
//         select.dispatchEvent(new Event('change'));

//         // Remplir les champs du modal avec les données existantes
//         modifModal.querySelector('#modalReductionId').value = codeReduction;
//         modifModal.querySelector('#modalCodeReduction').value = codeReduction;
//         modifModal.querySelector('#modalLibelleReduction').value = libelleReduction;
//         modifModal.querySelector('#modalReductionScolarite').value = reductionScolarite;
//         modifModal.querySelector('#modalReductionArriere').value = reductionArriere;
//         modifModal.querySelector('#modalReductionFrais1').value = reductionFrais1;
//         modifModal.querySelector('#modalReductionFrais2').value = reductionFrais2;
//         modifModal.querySelector('#modalReductionFrais3').value = reductionFrais3;
//         modifModal.querySelector('#modalReductionFrais4').value = reductionFrais4;

//         // Mettre à jour le titre du modal
//         var modalTitle = modifModal.querySelector('.modal-titlemodif');
//         modalTitle.textContent = 'Modifier fiche de Réduction : ' + libelleReduction;
//     });
// });




    document.addEventListener('DOMContentLoaded', () => {
    function calculatePercentage(avant, apres) {
        if (isNaN(avant) || avant === 0) return 0;
        return ((avant - apres) / avant * 100).toFixed(2) + '%';
    }

    function calculateFixe(avant, apres) {
        if (isNaN(avant)) return 0;
        return (avant - apres).toFixed(2);
    }

    // ========================
    //      MODAL CREATION
    // ========================
    const reductionType = document.getElementById('reductionType');
    const avantScolarite = document.getElementById('avantScolarite');
    const apresScolarite = document.getElementById('apresScolarite');
    const reductionScolarite = document.getElementById('ReductionScolarite');

    const avantArriere = document.getElementById('avantArriere');
    const apresArriere = document.getElementById('apresArriere');
    const reductionArriere = document.getElementById('ReductionArriere');

    const avantFrais1 = document.getElementById('avantFrais1');
    const apresFrais1 = document.getElementById('apresFrais1');
    const reductionFrais1 = document.getElementById('ReductionFrais1');

    const avantFrais2 = document.getElementById('avantFrais2');
    const apresFrais2 = document.getElementById('apresFrais2');
    const reductionFrais2 = document.getElementById('ReductionFrais2');

    const avantFrais3 = document.getElementById('avantFrais3');
    const apresFrais3 = document.getElementById('apresFrais3');
    const reductionFrais3 = document.getElementById('ReductionFrais3');

    const avantFrais4 = document.getElementById('avantFrais4');
    const apresFrais4 = document.getElementById('apresFrais4');
    const reductionFrais4 = document.getElementById('ReductionFrais4');

    function updateNewCalcul() {
        if (reductionType.value == 2) { // Réduction fixe
            reductionScolarite.value = calculateFixe(parseFloat(avantScolarite.value) || 0, parseFloat(apresScolarite.value) || 0);
            reductionArriere.value = calculateFixe(parseFloat(avantArriere.value) || 0, parseFloat(apresArriere.value) || 0);
            reductionFrais1.value = calculateFixe(parseFloat(avantFrais1.value) || 0, parseFloat(apresFrais1.value) || 0);
            reductionFrais2.value = calculateFixe(parseFloat(avantFrais2.value) || 0, parseFloat(apresFrais2.value) || 0);
            reductionFrais3.value = calculateFixe(parseFloat(avantFrais3.value) || 0, parseFloat(apresFrais3.value) || 0);
            reductionFrais4.value = calculateFixe(parseFloat(avantFrais4.value) || 0, parseFloat(apresFrais4.value) || 0);
        } else { // Pourcentage
            reductionScolarite.value = calculatePercentage(parseFloat(avantScolarite.value) || 0, parseFloat(apresScolarite.value) || 0);
            reductionArriere.value = calculatePercentage(parseFloat(avantArriere.value) || 0, parseFloat(apresArriere.value) || 0);
            reductionFrais1.value = calculatePercentage(parseFloat(avantFrais1.value) || 0, parseFloat(apresFrais1.value) || 0);
            reductionFrais2.value = calculatePercentage(parseFloat(avantFrais2.value) || 0, parseFloat(apresFrais2.value) || 0);
            reductionFrais3.value = calculatePercentage(parseFloat(avantFrais3.value) || 0, parseFloat(apresFrais3.value) || 0);
            reductionFrais4.value = calculatePercentage(parseFloat(avantFrais4.value) || 0, parseFloat(apresFrais4.value) || 0);
        }
    }

    [avantScolarite, apresScolarite, avantArriere, apresArriere, avantFrais1, apresFrais1, avantFrais2, apresFrais2, avantFrais3, apresFrais1, avantFrais4, apresFrais1, reductionType]
        .forEach(el => el.addEventListener('input', updateNewCalcul));
    reductionType.addEventListener('change', updateNewCalcul);


});



    
</script>
@endsection




