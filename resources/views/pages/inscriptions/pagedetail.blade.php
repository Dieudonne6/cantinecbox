@extends('layouts.master')
@section('content')
    {{-- <h5 class="modal-title" id="exampleModalLabel-{{ $eleve->MATRICULE }}">Informations pour {{ $eleve->NOM }}
        {{ $eleve->PRENOM }}</h5> --}}


    <div class="container card">
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
        <nav>
            <div class="nav nav-tabs" id="nav-tab{{ $eleve->MATRICULE }}" role="tablist" style="font-size: 14px;">
                @foreach (['Infor' => ' ', 'Detail' => 'Détail des notes', 'Deta' => 'Détails des paiements', 'finan' => 'Informations financières', 'Emploi' => 'Emploi du temps', 'Position' => 'Position Enseignants', 'Situation' => 'Situation selon Echéancier', 'Autre' => 'Autre Situation'] as $key => $label)
                {{-- @foreach (['Infor' => 'Informations générales', ] as $key => $label) --}}
                    <button class="nav-link{{ $loop->first ? ' active' : '' }}"
                        id="nav-{{ $key }}-tab{{ $eleve->MATRICULE }}" data-bs-toggle="tab"
                        data-bs-target="#nav-{{ $key }}{{ $eleve->MATRICULE }}" type="button" role="tab"
                        aria-controls="nav-{{ $key }}{{ $eleve->MATRICULE }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button>
                @endforeach
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent{{ $eleve->MATRICULE }}">
            <!-- Informations générales -->
            <div class="tab-pane fade show active" id="nav-Infor{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Infor-tab{{ $eleve->MATRICULE }}">
                <div class="card shadow-lg p-4 bg-light rounded">
                    <form class="accordion-body col-md-12 mx-auto">
                        <!-- Date de Naissance et Lieu -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="dateN{{ $eleve->MATRICULE }}" class="fw-bold">📅 Date de Naissance</label>
                                <input type="date" class="form-control" id="dateN{{ $eleve->MATRICULE }}" name="dateN"
                                    value="{{ $eleve->DATENAIS }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="lieu{{ $eleve->MATRICULE }}" class="fw-bold">📍 Lieu de Naissance</label>
                                <input type="text" class="form-control" id="lieu{{ $eleve->MATRICULE }}" name="lieu"
                                    value="{{ $eleve->LIEUNAIS }}" readonly>
                            </div>
                        </div>
            
                        <!-- Sexe et Type d'élève -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="sexe{{ $eleve->MATRICULE }}" class="fw-bold">⚧ Sexe</label>
                                <input type="text" class="form-control" id="sexe{{ $eleve->MATRICULE }}" name="sexe"
                                    value="{{ $eleve->SEXE == 1 ? 'Masculin' : ($eleve->SEXE == 2 ? 'Féminin' : '') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="typeEleve{{ $eleve->MATRICULE }}" class="fw-bold">🎓 Type Élève</label>
                                <input type="text" class="form-control" id="typeEleve{{ $eleve->MATRICULE }}" name="typeEleve"
                                    value="{{ $eleve->STATUTG == 1 ? 'Nouveau' : ($eleve->STATUTG == 2 ? 'Ancien' : '') }}" readonly>
                            </div>
                        </div>
            
                        <!-- Date d'inscription, Apte et Redoublant -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="dateIn{{ $eleve->MATRICULE }}" class="fw-bold">📝 Date d'inscription</label>
                                <input type="date" class="form-control" id="dateIn{{ $eleve->MATRICULE }}" name="dateIn"
                                    value="{{ $eleve->DATEINS }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="apte{{ $eleve->MATRICULE }}" class="fw-bold">✔ Apte</label>
                                <input type="text" class="form-control" id="apte{{ $eleve->MATRICULE }}" name="apte"
                                    value="{{ $eleve->APTE == 0 ? 'Non' : ($eleve->APTE == 1 ? 'Oui' : '') }}" readonly>
                            </div>
                            <div class="col-md-3 d-flex align-items-center mt-4">
                                <input type="checkbox" class="form-check-input me-2" id="statutRedoublant{{ $eleve->MATRICULE }}"
                                    name="statutRedoublant" {{ $eleve->STATUT == 1 ? 'checked' : '' }} readonly>
                                <label class="form-check-label" for="statutRedoublant{{ $eleve->MATRICULE }}">🔄 Redoublant</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- //a faire --}}
            <!-- Détails Notes -->
            <div class="tab-pane fade" id="nav-Detail{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Detail-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <form class="accordion-body col-md-12 mx-auto">
                    <!-- Table des notes -->
                    <div class="table-responsive mt-3 p-3 shadow-lg rounded bg-light">
                        <h5 class="text-center text-primary">Notes des Matières</h5>
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th scope="col">Matière</th>
                                    <th scope="col">Mi</th>
                                    <th scope="col">Dev1</th>
                                    <th scope="col">Dev2</th>
                                    <th scope="col">Dev3</th>
                                    <th scope="col">Test</th>
                                    <th scope="col">Ms</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($eleve->notes as $note)
                                    <tr>
                                        <td class="fw-bold">{{ $note->matiere->LIBELMAT }}</td>
                                        <td>{{ ($note->MI == -1 || $note->MI == 21) ? '***' : $note->MI }}</td>
                                        <td>{{ ($note->DEV1 == -1 || $note->DEV1 == 21) ? '***' : $note->DEV1 }}</td>
                                        <td>{{ ($note->DEV2 == -1 || $note->DEV2 == 21) ? '***' : $note->DEV2 }}</td>
                                        <td>{{ ($note->DEV3 == -1 || $note->DEV3 == 21) ? '***' : $note->DEV3 }}</td>
                                        <td>{{ ($note->TEST == -1 || $note->TEST == 21) ? '***' : $note->TEST }}</td>
                                        <td class="fw-bold text-danger">{{ ($note->MS1 == -1 || $note->MS1 == 21) ? '***' : $note->MS1 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            
                    <!-- Table des moyennes -->
                    <div class="table-responsive mt-3 p-3 shadow-lg rounded bg-white">
                        <h5 class="text-center text-success">Moyennes Générales</h5>
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success text-center">
                                <tr>
                                    <th scope="col">Moy 1</th>
                                    <th scope="col">Moy 2</th>
                                    <th scope="col">Moy 3</th>
                                    <th scope="col">Moy Totale</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr class="fw-bold">
                                    <td class="text-info">{{ ($eleve->MS1 == -1 || $eleve->MS1 == 21) ? '***' : $eleve->MS1 }}</td>
                                    <td class="text-info">{{ ($eleve->MS2 == -1 || $eleve->MS2 == 21) ? '***' : $eleve->MS2 }}</td>
                                    <td class="text-info">{{ ($eleve->MS3 == -1 || $eleve->MS3 == 21) ? '***' : $eleve->MS3 }}</td>
                                    <td class="text-danger fs-5">{{ ($eleve->MAN == -1 || $eleve->MAN == 21) ? '***' : $eleve->MAN }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>            
            <!--  -->
            <div class="tab-pane fade" id="nav-Deta{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Deta-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body col-md-12 mx-auto bg-light-gray">
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkDetails" readonly>
                                <label class="form-check-label" for="checkDetails">Détail des
                                    composantes</label>
                            </div>
                            
                            <a href="votre-lien-ici" style="text-decoration: none;">
                                <button type="button" class="btn btn-primary btn-icon-text-center p-2" onclick="printPayments()">
                                    <i class="typcn typcn-upload btn-icon-prepend"></i>Imprimer récapitulatif des paiements
                                </button>

                            </a>
                        </div>
                        <div id="printArea" class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table  id="paymentsTable" class="table table-hover">
                                <p>Elève: {{$eleve->NOM}}  {{$eleve->PRENOM}}</p>
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">N reçu</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scolarite as $item)
                                        <tr>
                                          {{--  <td>{{ $item->NUMERO }}</td>--}}
                                          <td></td>
                                            <td>{{ \Carbon\Carbon::parse($item->DATEOP)->format('d-m-Y') }}</td>
                                            <td>{{ number_format($item->MONTANT, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-dark">
                                    <tr>
                                        <td colspan="2" class="table-active">Somme</td>
                                        <td>{{ number_format($scolarite->sum('MONTANT'), 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="table-active">Reste à Payer</td>
                                        <td> 
                                            {{ number_format(($eleve->ARRIERE + $eleve->FRAIS1 + $eleve->FRAIS2 + $eleve->FRAIS3 + $eleve->FRAIS4 + $eleve->APAYER) - $scolarite->sum('MONTANT'), 0, ',', ' ') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    {{--<div class="card-body">
                        <h6 class="card-title text-center">Réduction Montants dus</h6>
                        <table class="table">
                            <tbody style=" width: 50%;">
                                <tr>
                                    <td>[ 3,3% ] Scolarité</td>
                                    <td><input type="number" class="form-control" id="scolarite" readonly></td>
                                    <td>[ 0,0% ] Arriéré</td>
                                    <td><input type="number" class="form-control" id="arriere" readonly></td>
                                </tr>
                                <tr>
                                    <td>Frais 1</td>
                                    <td><input type="number" class="form-control" id="frais1" readonly></td>
                                    <td>Frais 2</td>
                                    <td><input type="number" class="form-control" id="frais2" readonly></td>
                                </tr>
                                <tr>
                                    <td>Frais 3</td>
                                    <td><input type="number" class="form-control" id="frais3" readonly></td>
                                    <td>Frais 4</td>
                                    <td><input type="number" class="form-control" id="frais4" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>--}}
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade" id="nav-finan{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-finan-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body col-md-12 mx-auto">
                    <div class="table-responsive mt-2">
                            <a href="votre-lien-ici" style="text-decoration: none; display: flex; justify-content: end;">
                                <button type="button" class="btn btn-primary btn-icon-text-center p-2 m-2" onclick="printInfoFinancire()">
                                    <i class="typcn typcn-upload btn-icon-prepend"></i>Imprimer récapitulatif des paiements
                                </button>
                            </a>
                        <table class="table table-striped table-hover" id="info_finance">
                            <p>Elève: {{ $eleve->NOM }} {{ $eleve->PRENOM }} </p>
                            <tbody>
                                <tr>
                                    <th scope="row" class="text-start">Scolarités déjà perçus
                                    </th>
                                    <td class="text-end">{{ number_format($sommeScolarité, 0, ',', ' ') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Arrièrés déjà perçus </th>
                                    <td class="text-end">{{ number_format($sommeArriéré, 0, ',', ' ') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Total</th>
                                    <td class="text-end">{{ number_format(($sommeScolarité + $sommeArriéré), 0, ',', ' ') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Total recettes à ce jour</th>
                                    <td class="text-end"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Versé à la banque</th>
                                    <td class="text-end"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues ce jour</th>
                                    <td class="text-end"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues cette semaine
                                    </th>
                                    <td class="text-end"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues ce mois</th>
                                    <td class="text-end"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!--  -->
            <div class="tab-pane fade" id="nav-Emploi{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Emploi-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body col-md-12 mx-auto">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 1</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 1.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 1.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 1.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 2.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 2.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 2.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 3.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 3.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 3.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade" id="nav-Position{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Position-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body">
                    <div id="dateTime" style="text-align: justify;"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 1</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 1.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 1.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 1.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 2.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 2.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 2.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Colonne 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contenu 3.1</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 3.2</td>
                                        </tr>
                                        <tr>
                                            <td>Contenu 3.3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Situation selon échéance -->
            <div class="tab-pane fade" id="nav-Situation{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Situation-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body">
                    <div id="dateTime" style="text-align: justify;"></div>
                    <div class="col-12 mt-4">
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Échéances</th>
                                        <th scope="col">Montant Du</th>
                                        <th scope="col">Déjà Payé</th>
                                        <th scope="col">Reste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Variables pour les totaux
                                        $montantPaye = $sommeTotale; // Montant déjà payé total
                                    @endphp
            
                                    @foreach ($echeancee as $echeance)
                                        @php
                                            // Montant restant à payer pour la ligne actuelle
                                            $montantDu = $echeance->APAYER;
            
                                            // Initialiser le montant déjà payé pour la ligne actuelle
                                            $dejaPaye = 0;
            
                                            // Vérifiez si le montant déjà payé est supérieur au montant dû
                                            if ($montantPaye > 0) {
                                                // Si le montant à payer est inférieur ou égal au montant payé
                                                if ($montantPaye >= $montantDu) {
                                                    $dejaPaye = $montantDu; // Tout le montant dû est payé
                                                    $montantPaye -= $montantDu; // Réduire le montant payé
                                                } else {
                                                    $dejaPaye = $montantPaye; // Montant payé partiel
                                                    $montantPaye = 0; // Épuise le montant payé
                                                }
                                            }
            
                                            // Calcul du reste
                                            $reste = $montantDu - $dejaPaye;
                                        @endphp
                                        <tr>
                                            <td>{{ $echeance->NUMERO }}</td>
                                            <td>{{ $echeance->DATEOP }}</td>
                                            <td>{{ number_format($montantDu, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($dejaPaye, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($reste, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-dark">
                                    <tr>
                                        <td colspan="2" class="table-active">Total</td>
                                        <td>{{ number_format(array_sum($echeancee->pluck('APAYER')->toArray()), 0, ',', ' ') }}</td>
                                        <td>{{ number_format($sommeTotale, 0, ',', ' ') }}</td>
                                        <td>{{ number_format(array_sum($echeancee->pluck('APAYER')->toArray()) - $sommeTotale, 0, ',', ' ') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Autres Situations -->
            <div class="tab-pane fade" id="nav-Autre{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Autre-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body">
                    <div id="dateTime" style="text-align: justify;"></div>
                    @if (isset($eleve) && $echeancee->contains('MATRICULE', $eleve->MATRICULE))
                        <!-- Si les données de l'élève existent, afficher le tableau -->
                        <div class="col-12 mt-4">
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Désignation</th>
                                            <th scope="col">Montant Du</th>
                                            <th scope="col">Déjà Payé</th>
                                            <th scope="col">Reste</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Arriéré</td>
                                            <td>{{ number_format($eleve->ARRIERE, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeArriéré, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->ARRIERE - $sommeArriéré, 0, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $libelles->LIBELF1 }}</td>
                                            <td>{{ number_format($eleve->FRAIS1, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeFrais1, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->FRAIS1 - $sommeFrais1, 0, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $libelles->LIBELF2 }}</td>
                                            <td>{{ number_format($eleve->FRAIS2, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeFrais2, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->FRAIS2 - $sommeFrais2, 0, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $libelles->LIBELF3 }}</td>
                                            <td>{{ number_format($eleve->FRAIS3, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeFrais3, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->FRAIS3 - $sommeFrais3, 0, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $libelles->LIBELF4 }}</td>
                                            <td>{{ number_format($eleve->FRAIS4, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeFrais4, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->FRAIS4 - $sommeFrais4, 0, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Scolarité</td>
                                            <td>{{ number_format($eleve->APAYER, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($sommeScolarité, 0, ',', ' ') }}</td>
                                            <td>{{ number_format($eleve->APAYER - $sommeScolarité, 0, ',', ' ') }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="tfoot-dark">
                                        <tr>
                                            <td colspan="1" class="table-active">Total</td>
                                            <td>{{ number_format($eleve->ARRIERE + $eleve->FRAIS1 + $eleve->FRAIS2 + $eleve->FRAIS3 + $eleve->FRAIS4 + $eleve->APAYER, 0, ',', ' ') }}
                                            </td>
                                            <td>{{ number_format($sommeArriéré + $sommeFrais1 + $sommeFrais2 + $sommeFrais3 + $sommeFrais4 + $sommeScolarité, 0, ',', ' ') }}
                                            </td>
                                            <td>{{ number_format(
                                                $eleve->ARRIERE -
                                                    $sommeArriéré +
                                                    ($eleve->FRAIS1 - $sommeFrais1) +
                                                    ($eleve->FRAIS2 - $sommeFrais2) +
                                                    ($eleve->FRAIS3 - $sommeFrais3) +
                                                    ($eleve->FRAIS4 - $sommeFrais4) +
                                                    ($eleve->APAYER - $sommeScolarité),
                                                0,
                                                ',',
                                                ' ',
                                            ) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <!-- Si les données ne sont pas disponibles, afficher un message d'erreur -->
                        <div class="alert alert-danger">
                            Aucune donnée disponible pour cet élève dans la table des échéances.
                            Veuillez d'abord créer un échéancier.
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>

    <script>
        function printPayments() {
            var printContents = document.getElementById('printArea').innerHTML;

            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;

            // Optionnel : ajouter un style directement pour l'impression
            var style = document.createElement('style');
            style.innerHTML = `
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: #343a40; color: #fff; }
                tfoot tr td { font-weight: bold; }
            `;
            document.head.appendChild(style);

            window.print();

            document.body.innerHTML = originalContents; // Restaure la page
            location.reload(); // Recharge pour réinitialiser tous les scripts
        }
    
        function printInfoFinancire() {
            // Récupérer la section à imprimer
            var printContents = document.getElementById("info_finance").outerHTML;

            var eleveInfo = document.querySelector("#info_finance").previousElementSibling.outerHTML;

            // Ouvrir une nouvelle fenêtre pour l'impression
            var originalContents = document.body.innerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Récapitulatif des paiements</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3 class="text-center mb-3">Récapitulatif des paiements</h3>');
            printWindow.document.write(eleveInfo);
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Lancer l'impression
            printWindow.print();
        }
    </script>

@endsection
 