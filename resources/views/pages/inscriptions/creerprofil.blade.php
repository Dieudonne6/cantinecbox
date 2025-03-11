@extends('layouts.master')

@section('content')


<div class="main-panel-10">
    <div class="content-wrapper">
      @if (Session::has('status'))
      <div id="statusAlert" class="alert alert-success btn-primary">
        {{ Session::get('status') }}
      </div>
      @endif
      {{--  --}}
      <div class="row">          
        <div class="col-12">
          <div class="card mb-6">
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
              <h4 class="card-title">Profils de réduction</h4>
              <div class="row gy-3">
                <div class="demo-inline-spacing">
                  {{-- <a  class="btn btn-primary" href=" {{url('/nouveautypesclasses')}}">Nouveau</a> --}}
                  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveaumodal">
    Nouveau
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="nouveaumodal" tabindex="-1" aria-labelledby="exampleModalLabe" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content calcul-modal">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Fiche de réduction</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body profile-content">
          {{-- @if (Session::has('status'))
          <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
          </div>
          @endif --}}

          <div class="row">
            <div class="" id="calcul-one">
                <form class="forms-sample" action="{{ url('/ajouterprofreduction') }}" method="POST">
                    @csrf
                    <!-- Autres champs ici... -->
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Numéro réduction</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="exampleInputUsername2" name="Codereduction" value="{{ $newCode }}" readonly>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Libelle réduction</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="exampleInputUsername2" name="LibelleReduction" placeholder="" required>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="reductionType" class="col-sm-8 col-form-label">Type de réduction</label>
                        <div class="col-sm-12 mb-2">
                            <select class="js-example-basic-multiple w-50" id="reductionType" name="reductionType" required>
                                <option value="1">Réduction par pourcentage</option>
                                <option value="2">Réduction fixe</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur scolarité</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionScolarite" name="Reduction_scolarite" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur arrièré</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionArriere" name="Reduction_arriere" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur frais 1</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionFrais1" name="Reduction_frais1" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur frais 2</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionFrais2" name="Reduction_frais2" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur frais 3</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionFrais3" name="Reduction_frais3" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Réduction accordée sur frais 4</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ReductionFrais4" name="Reduction_frais4" placeholder="0">
                        </div>
                    </div>
{{--                     <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-12 col-form-label mb-0">Mode d'application de la réduction sur les écheanciers</label>
                        <div class="col-sm-12 mb-2">
                            <select class="js-example-basic-multiple w-100" name="mode">
                                <option value="1">Agir sur les dernières tranches</option>
                                <option value="2">Repartir équitablement sur toutes les tranches</option>
                            </select>
                        </div>
                    </div> --}}
                    <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>                </form>
            </div>
            <div class="col-lg-4 d-none" id="percentage">
                <br></br>
                <br></br>
                <h6 style="margin-top: 1rem">Calculateur de pourcentage de réduction</h6>
                <div class="row">
                    <div class="col-lg-6">
                        <p >Avant réduction</p>
                        <div class="mt-4">
                            <input type="text" class="form-control" id="avantScolarite" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="avantArriere" placeholder="0">
                        </div>
                        <div class="mt-2">
                            <input type="text" class="form-control" id="avantFrais1" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="avantFrais2" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="avantFrais3" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="avantFrais4" placeholder="0">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p>Apres réduction</p>
                        <div class="mt-4">
                            <input type="text" class="form-control" id="apresScolarite" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="apresArriere" placeholder="0">
                        </div>
                        <div class="mt-2">
                            <input type="text" class="form-control" id="apresFrais1" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="apresFrais2" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="apresFrais3" placeholder="0">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="apresFrais4" placeholder="0">
                        </div>
                    </div>
                    <div class="my-4 col-lg-12">
                        <button type="button" class="btn btn-secondary" id="closecalculate">Fermer le calculateur</button>
                    </div>
                </div>
            </div>
        </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="calculs">Afficher calculateur de pourcentage</button>              
            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">


            </div>
        </div>
      </div>
    </div>
  </div>
                  {{-- <button type="button" class="btn btn-secondary">Imprimer</button>   --}}
                  <style>
                    table {
                      float: right;
                      width: 10%;
                      border-collapse: collapse;
                      margin: 5px auto;
                    }
                    th, td {
                      /* border: 1px solid #aaa1a1; */
                      padding: 4px;
                      text-align: center;
                    }
                    th {
                      /* background-color: #f2f2f2; */
                    }
                    td.bouton {
                      /* background-color: #ffcccb; */
                    }
                  </style>
                </div>
              </div>
            </div>
          </div>
        </div>       
      </div>

      {{--  --}}
      <div class="row">
        <div class="col">
                
          <div class="card">
            <div class="table-responsive" style="height: 300px; overflow: auto;">
              <table class="table table-striped" style="min-width: 600px; font-size: 10px;">
                <thead>
                  <tr>
                    <th class="">Numéro</th>
                    <th>Libellé réduction</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($reductions as $reduction)
                      
                  <tr>
                    <td>{{ $reduction->CodeReduction }}</td>
                    <td>{{ $reduction->LibelleReduction }}</td>
                    <td>
                        <div class="">
                            <!-- Button trigger modal -->
                            {{-- <a  class="btn btn-primary p-2 btn-sm" href="{{url('/modifiertypesclasses')}}">Modif</a> --}}
                            <button type="button" class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#modifModal"
                            data-code="{{ $reduction->CodeReduction }}"
                            data-libelle="{{ $reduction->LibelleReduction }}"
                            data-scolarite="{{ $reduction->Reduction_scolarite }}"
                            data-arriere="{{ $reduction->Reduction_arriere }}"
                            data-frais1="{{ $reduction->Reduction_frais1 }}"
                            data-frais2="{{ $reduction->Reduction_frais2 }}"
                            data-frais3="{{ $reduction->Reduction_frais3 }}"
                            data-frais4="{{ $reduction->Reduction_frais4 }}">
                                Modifier
                              </button>
                              <!--Modification Modal -->
                              <div class="modal fade" id="modifModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content calcul-modals">
                                        <div class="modal-header">
                                            <h1 class="modal-titlemodif fs-5" id="exampleModalLabel">Modifier fiche de réduction</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body profile-content">
                                            <div class="row">
                                                <div class="" id="calcul-ones">
                                                    <form class="forms-sample" id="modificationForm" action="{{ url('/modifreductions') }}" method="POST">
                                                      @csrf
                                                      @method('PUT')
                                                      <input type="hidden" id="modalReductionId" name="CodeReduction">
                                                        <div class="form-group row">
                                                            <label for="modalCodeReduction" class="col-sm-8 col-form-label text-start">Numéro réduction</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalCodeReduction" name="Codereduction" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalLibelleReduction" class="col-sm-8 col-form-label text-start">Libellé réduction</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalLibelleReduction" name="LibelleReduction">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="reductionType" class="col-sm-8 col-form-label text-start">Type de réduction</label>
                                                            <div class="col-sm-12 mb-2">
                                                                <select class="js-example-basic-multiple w-50" id="reductionType" name="reductionType" required>
                                                                    <option value="1" {{ $reduction->typereduction === 'P' ? 'selected' : '' }}>Réduction par pourcentage</option>
                                                                    <option value="2" {{ $reduction->typereduction === 'F' ? 'selected' : '' }}>Réduction fixe</option>
                                                                </select>
                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        document.getElementById('reductionType').value = '{{ $reduction->typereduction === 'P' ? 1 : 2 }}';
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionScolarite" class="col-sm-8 col-form-label text-start">Réduction accordée sur scolarité</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionScolarite" name="Reduction_scolarite">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionArriere" class="col-sm-8 col-form-label text-start">Réduction accordée sur arriérés</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionArriere" name="Reduction_arriere">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionFrais1" class="col-sm-8 col-form-label text-start">Réduction accordée sur frais 1</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionFrais1" name="Reduction_frais1">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionFrais2" class="col-sm-8 col-form-label text-start">Réduction accordée sur frais 2</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionFrais2" name="Reduction_frais2">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionFrais3" class="col-sm-8 col-form-label text-start">Réduction accordée sur frais 3</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionFrais3" name="Reduction_frais3">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="modalReductionFrais4" class="col-sm-8 col-form-label text-start">Réduction accordée sur frais 4</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="modalReductionFrais4" name="Reduction_frais4">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group row">
                                                            <label for="modalModeApplication" class="col-sm-12 col-form-label text-start mb-0">Mode d'application de la réduction sur les échéanciers</label>
                                                            <div class="col-sm-12 mb-2">
                                                                <select class="js-example-basic-multiple w-100" id="modalModeApplication" name="mode">
                                                                    <option value="1">Agir sur les dernières tranches</option>
                                                                    <option value="2">Repartir équitablement sur toutes les tranches</option>
                                                                </select>
                                                            </div>
                                                        </div> --}}
                                                        <button type="submit" class="btn btn-primary mr-2">Modifier</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </form>
                                                </div>
                                                <div class="col-lg-4 d-none" id="percentages">
                                                    <h6 style="margin-top: 1rem">Calculateur de pourcentage <br> de réduction</h6>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <p >Avant reduction</p>
                                                            <div class="mt-5">
                                                                <input type="text" class="form-control" id="avantScolaritemodif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="avantArrieremodif" placeholder="0">
                                                            </div>
                                                            <div class="mt-2">
                                                                <input type="text" class="form-control" id="avantFrais1modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="avantFrais2modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="avantFrais3modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="avantFrais4modif" placeholder="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p>Apres réduction</p>
                                                            <div class="mt-5">
                                                                <input type="text" class="form-control" id="apresScolaritemodif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="apresArrieremodif" placeholder="0">
                                                            </div>
                                                            <div class="mt-2">
                                                                <input type="text" class="form-control" id="apresFrais1modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="apresFrais2modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="apresFrais3modif" placeholder="0">
                                                            </div>
                                                            <div class="mt-3">
                                                                <input type="text" class="form-control" id="apresFrais4modif" placeholder="0">
                                                            </div>
                                                        </div>
                                                        <div class="my-4 col-lg-12">
                                                            <button type="button" class="btn btn-secondary" id="closecalculates">Fermer le calculateur</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" id="calculss">Afficher calculateur de pourcentage</button>
                                            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <button class="btn btn-danger p-2 btn-sm" type="button" id="deleteButton" data-code-reduction="{{ $reduction->CodeReduction }}">Supprimer</button> --}}

                               <!-- Modal Trigger for Deletion -->
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $reduction->CodeReduction }}">Supprimer</button>

            <!-- Modal for Deletion -->
            <div class="modal fade" id="deleteModal{{ $reduction->CodeReduction }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer cette réduction ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <form action="{{ url('delreductions/' . $reduction->CodeReduction) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger" value="Confirmer">
                            </form>  
                        </div>
                    </div>
                </div>
            </div>

                              <!-- Button trigger modal -->
                               <!-- Bouton Détails avec data-attributes -->
                              <button class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal"
                              data-code="{{ $reduction->CodeReduction }}"
                              data-libelle="{{ $reduction->LibelleReduction }}"
                              data-scolarite="{{ $reduction->Reduction_scolarite }}"
                              data-arriere="{{ $reduction->Reduction_arriere }}"
                              data-frais1="{{ $reduction->Reduction_frais1 }}"
                              data-frais2="{{ $reduction->Reduction_frais2 }}"
                              data-frais3="{{ $reduction->Reduction_frais3 }}"
                              data-frais4="{{ $reduction->Reduction_frais4 }}">
                              Détails
                              </button>
                              {{-- <button class="btn btn-primary p-2 btn-sm dropdown" data-bs-toggle="modal" data-bs-target="#exampleModal3">Détails</button> --}}

                             <!-- Modal Détails -->
                    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="detailsModalLabel">Détails de la Réduction</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <div class="form-group">
                                      <label>Code Réduction</label>
                                      <input type="text" class="form-control" id="modalCodeReduction" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Libellé Réduction</label>
                                      <input type="text" class="form-control" id="modalLibelleReduction" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur scolarité</label>
                                      <input type="text" class="form-control" id="modalReductionScolarite" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur arriérés</label>
                                      <input type="text" class="form-control" id="modalReductionArriere" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur frais 1</label>
                                      <input type="text" class="form-control" id="modalReductionFrais1" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur frais 2</label>
                                      <input type="text" class="form-control" id="modalReductionFrais2" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur frais 3</label>
                                      <input type="text" class="form-control" id="modalReductionFrais3" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label>Réduction accordée sur frais 4</label>
                                      <input type="text" class="form-control" id="modalReductionFrais4" readonly>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
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
  </div>



  <script>

    // afficher les information dans le modal de detail
    document.addEventListener('DOMContentLoaded', function () {
        var detailsModal = document.getElementById('detailsModal');
        detailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
    
            // Récupérer les informations des data-attributes
            var codeReduction = button.getAttribute('data-code');
            var libelleReduction = button.getAttribute('data-libelle');
            var reductionScolarite = button.getAttribute('data-scolarite');
            var reductionArriere = button.getAttribute('data-arriere');
            var reductionFrais1 = button.getAttribute('data-frais1');
            var reductionFrais2 = button.getAttribute('data-frais2');
            var reductionFrais3 = button.getAttribute('data-frais3');
            var reductionFrais4 = button.getAttribute('data-frais4');
    
            // Mettre à jour les champs du modal avec les informations correspondantes
            detailsModal.querySelector('#modalCodeReduction').value = codeReduction;
            detailsModal.querySelector('#modalLibelleReduction').value = libelleReduction;
            detailsModal.querySelector('#modalReductionScolarite').value = reductionScolarite;
            detailsModal.querySelector('#modalReductionArriere').value = reductionArriere;
            detailsModal.querySelector('#modalReductionFrais1').value = reductionFrais1;
            detailsModal.querySelector('#modalReductionFrais2').value = reductionFrais2;
            detailsModal.querySelector('#modalReductionFrais3').value = reductionFrais3;
            detailsModal.querySelector('#modalReductionFrais4').value = reductionFrais4;

            // Mettre à jour le titre du modal avec le libellé de la réduction
            var modalTitle = detailsModal.querySelector('.modal-title');
            modalTitle.textContent = 'Détails de la Réduction : ' + libelleReduction;

        });
    });

    document.querySelector('.btn-secondary').addEventListener('click', function() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('modifModal'));
    modal.hide();
});




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

        const avantFrais1 = document.getElementById('avantFrais1modif');
        const apresFrais1 = document.getElementById('apresFrais1modif');
        const reductionFrais1 = document.getElementById('ReductionFrais1modif');

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

        const avantFrais2 = document.getElementById('avantFrais2modif');
        const apresFrais2 = document.getElementById('apresFrais2modif');
        const reductionFrais2 = document.getElementById('ReductionFrais2modif');

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

        const avantFrais3 = document.getElementById('avantFrais3modif');
        const apresFrais3 = document.getElementById('apresFrais3modif');
        const reductionFrais3 = document.getElementById('ReductionFrais3modif');

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

        const avantFrais4 = document.getElementById('avantFrais4modif');
        const apresFrais4 = document.getElementById('apresFrais4modif');
        const reductionFrais4 = document.getElementById('ReductionFrais4modif');

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


    // afficher les infos du modal pour la modification
    document.addEventListener('DOMContentLoaded', function () {
        var detailsModal = document.getElementById('modifModal');
        detailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
    
            // Récupérer les informations des data-attributes
            var codeReduction = button.getAttribute('data-code');
            var libelleReduction = button.getAttribute('data-libelle');
            var reductionScolarite = button.getAttribute('data-scolarite');
            var reductionArriere = button.getAttribute('data-arriere');
            var reductionFrais1 = button.getAttribute('data-frais1');
            var reductionFrais2 = button.getAttribute('data-frais2');
            var reductionFrais3 = button.getAttribute('data-frais3');
            var reductionFrais4 = button.getAttribute('data-frais4');
    
            // Mettre à jour les champs du modal avec les informations correspondantes
            document.getElementById('modalReductionId').value = codeReduction;
            detailsModal.querySelector('#modalCodeReduction').value = codeReduction;
            detailsModal.querySelector('#modalLibelleReduction').value = libelleReduction;
            detailsModal.querySelector('#modalReductionScolarite').value = reductionScolarite;
            detailsModal.querySelector('#modalReductionArriere').value = reductionArriere;
            detailsModal.querySelector('#modalReductionFrais1').value = reductionFrais1;
            detailsModal.querySelector('#modalReductionFrais2').value = reductionFrais2;
            detailsModal.querySelector('#modalReductionFrais3').value = reductionFrais3;
            detailsModal.querySelector('#modalReductionFrais4').value = reductionFrais4;

            // Mettre à jour le titre du modal avec le libellé de la réduction
            var modalTitle = detailsModal.querySelector('.modal-titlemodif');
            modalTitle.textContent = 'Modifier fiche de Réduction: ' + libelleReduction;

        });
    });

// confirmation avant suppresion

// document.addEventListener('DOMContentLoaded', function() {
//   document.getElementById('deleteButton').addEventListener('click', function() {
//     // Afficher une boîte de confirmation
//     const confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette réduction ?");
    
//     if (confirmation) {
//       // Si l'utilisateur confirme, rediriger vers la route de suppression
//       const codeReduction = this.getAttribute('data-code-reduction');
//       window.location.href = `/delreductions/${codeReduction}`;
//     }
//   });
// });



    // requette ajax pour la modification

//     document.getElementById('modificationForm').addEventListener('submit', function(event) {
//     event.preventDefault(); // Empêcher le rechargement de la page

//     // Récupérer les données du formulaire
//     var formData = new FormData(this);

//     // Récupérer l'ID de la réduction pour construire l'URL de la requête PUT
//     var codereduction = document.getElementById('modalReductionId').value;

//     // Envoyer les données au serveur via AJAX
//     fetch('/modifreductions/' + codereduction, {
//         method: 'PUT',
//         body: formData,
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             // Afficher un message de succès, fermer le modal, etc.
//             alert('Modification effectuée avec succès');
//             $('#modifModal').modal('hide');
//             // Mettre à jour l'interface utilisateur si nécessaire
//         } else {
//             // Gérer les erreurs
//             alert('Erreur lors de la modification : ' + data.message);
//         }
//     })
//     .catch(error => {
//         console.error('Erreur:', error);
//         alert('Erreur lors de la modification.');
//     });
// });
    </script>
@endsection
