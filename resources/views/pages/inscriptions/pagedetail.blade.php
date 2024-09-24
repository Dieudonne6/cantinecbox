@extends('layouts.master')
@section('content')
    {{-- <h5 class="modal-title" id="exampleModalLabel-{{ $eleve->MATRICULE }}">Informations pour {{ $eleve->NOM }}
        {{ $eleve->PRENOM }}</h5> --}}


    <div class="container card">
        <nav>
            <div class="nav nav-tabs" id="nav-tab{{ $eleve->MATRICULE }}" role="tablist" style="font-size: 14px;">
                @foreach (['Infor' => 'Informations générales', 'Detail' => 'Détail des notes', 'Deta' => 'Détails des paiements', 'finan' => 'Informations financières', 'Emploi' => 'Emploi du temps', 'Position' => 'Position Enseignants'] as $key => $label)
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
                <form class="accordion-body col-md-12 mx-auto">
                    <!-- Date de Naissance et Lieu -->
                    <div class="form-group row mt-2">
                        <label for="dateN{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Date de
                            Naissance</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="dateN{{ $eleve->MATRICULE }}" name="dateN"
                                value="{{ $eleve->DATENAIS }}" readonly>
                        </div>
                        <label for="lieu{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Lieu</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="lieu{{ $eleve->MATRICULE }}" name="lieu"
                                value="{{ $eleve->LIEUNAIS }}" readonly>
                        </div>
                    </div>
                    <!-- Sexe et Types élèves -->
                    <div class="form-group row mt-2">
                        <label for="sexe{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Sexe</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="sexe{{ $eleve->MATRICULE }}" name="sexe"
                                value="{{ $eleve->SEXE == 1 ? 'Masculin' : ($eleve->SEXE == 2 ? 'Féminin' : '') }}"
                                readonly>
                        </div>
                        <label for="typeEleve{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Type Élève</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="typeEleve{{ $eleve->MATRICULE }}"
                                name="typeEleve"
                                value="{{ $eleve->STATUTG == 1 ? 'Nouveau' : ($eleve->STATUTG == 2 ? 'Ancien' : '') }}"
                                readonly>
                        </div>
                    </div>
                    <!-- Date d'inscription, Apte et Statut Redoublant -->
                    <div class="form-group row mt-2">
                        <label for="dateIn{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Date
                            d'inscription</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="dateIn{{ $eleve->MATRICULE }}" name="dateIn"
                                value="{{ $eleve->DATEINS }}" readonly>
                        </div>
                        <label for="apte{{ $eleve->MATRICULE }}" class="col-sm-2 col-form-label">Apte</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="apte{{ $eleve->MATRICULE }}" name="sexe"
                                value="{{ $eleve->APTE == 0 ? 'Non' : ($eleve->APTE == 1 ? 'Oui' : '') }}" readonly>
                        </div>
                        <div class="col-sm-2 form-check" style="margin-left: 4rem">
                            <input type="checkbox" class="form-check-input" id="statutRedoublant{{ $eleve->MATRICULE }}"
                                name="statutRedoublant" {{ $eleve->STATUT == 1 ? 'checked' : '' }} readonly>
                            <label class="form-check-label mt-1" for="statutRedoublant{{ $eleve->MATRICULE }}">Statut
                                Redoublant</label>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Détails Notes -->
            <div class="tab-pane fade" id="nav-Detail{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Detail-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <form class="accordion-body col-md-12 mx-auto">
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped">
                            <thead>
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
                            <tbody>
                                @foreach ($eleve->notes as $note)
                                    <tr>
                                        <td>{{ $note->CODEMAT }}</td>
                                        <td>{{ $note->MI }}</td>
                                        <td>{{ $note->DEV1 }}</td>
                                        <td>{{ $note->DEV2 }}</td>
                                        <td>{{ $note->DEV3 }}</td>
                                        <td>{{ $note->TEST }}</td>
                                        <td>{{ $note->MS }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Moy 1</th>
                                    <th scope="col">Moy 2</th>
                                    <th scope="col">Moy 3</th>
                                    <th scope="col">Moy Totale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>12</td>
                                    <td>14</td>
                                    <td>13</td>
                                    <td>11</td>
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
                                <button type="button" class="btn btn-primary btn-icon-text-center p-2">
                                    <i class="typcn typcn-upload btn-icon-prepend"></i>Imprimer
                                    récapitulatif des paiements
                                </button>
                            </a>
                        </div>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">n°Reçu</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>130</td>
                                        <td>05/06/2024</td>
                                        <td>190 000</td>
                                    </tr>
                                    <tr>
                                        <td>130</td>
                                        <td>05/06/2024</td>
                                        <td>190 000</td>
                                    </tr>
                                    <tr>
                                        <td>130</td>
                                        <td>05/06/2024</td>
                                        <td>190 000</td>
                                    </tr>
                                </tbody>
                                <tfoot class="tfoot-dark">
                                    <tr>
                                        <td colspan="2" class="table-active">Somme</td>
                                        <td>190 000</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="table-active">Reste à Payer</td>
                                        <td>1 900</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade" id="nav-finan{{ $eleve->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-finan-tab{{ $eleve->MATRICULE }}" tabindex="0">
                <div class="accordion-body col-md-12 mx-auto">
                    <div class="table-responsive mt-2">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th scope="row" class="text-start">Scolarités perçus le 23/05/24
                                    </th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Arrièrés perçus le 23/05/24</th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Total</th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Total recettes à ce jour</th>
                                    <td class="text-end">57 575 500</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Versé à la banque</th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues ce jour</th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues cette semaine
                                    </th>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Recettes attendues ce mois</th>
                                    <td class="text-end">0</td>
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

        </div>
    </div>
@endsection