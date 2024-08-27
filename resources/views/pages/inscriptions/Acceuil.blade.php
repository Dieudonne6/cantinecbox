@extends('layouts.master')

@section('content')
    <style>
        /* Styles spécifiques pour l'impression sur papier A4 */
        @media print and (size: A4) {
            table {
                font-size: 5pt;
                /* Ajuste la taille de la police pour A4 */
            }

            th,
            td {
                padding: 3px;
                /* Ajuste le padding pour A4 */
            }
        }

        /* Styles spécifiques pour l'impression sur papier A3 */
        @media print and (size: A3) {
            table {
                font-size: 6pt;
                /* Ajuste la taille de la police pour A3 */
            }

            th,
            td {
                padding: 4px;
                /* Ajuste le padding pour A3 */
            }
        }
    </style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            @if (Session::has('status'))
                <div id="statusAlert" class="alert alert-success btn-primary">
                    {{ Session::get('status') }}
                </div>
            @endif
            {{--  --}}


            <div class="card-body">
                <h4 class="card-title">Accueil</h4>
                <div class="row gy-6">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <a class="btn btn-primary btn-sm" href="{{ url('/inscrireeleve') }}">
                                <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau
                            </a>

                            <button type="button" class="btn btn-secondary btn-sm" onclick="imprimerPage()">
                                <i class="typcn typcn-printer btn-icon-prepend"></i> Imprimer
                            </button>
                        </div>
                        <div>
                            <button id="recalculer" type="button" class="btn btn-primary btn-sm">Recalculer
                                effectifs</button>
                        </div>
                        <div>
                            <table id="tableau-effectifs" class="table">
                                <tbody>
                                    <tr>
                                        <td class="bouton">Eff.Total</td>
                                        <td id="total">942</td>
                                        <td class="bouton">Filles</td>
                                        <td id="filles">60</td>
                                        <td class="bouton">Garçons</td>
                                        <td id="garcons">742</td>
                                    </tr>
                                    <tr>
                                        <td class="bouton">Eff.Red</td>
                                        <td id="total-red">10</td>
                                        <td class="bouton">Red.Filles</td>
                                        <td id="filles-red">2</td>
                                        <td class="bouton">Red.Garçons</td>
                                        <td id="garcons-red">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <style>
                    table {
                        float: right;
                        width: 60%;
                        border-collapse: collapse;
                        margin: 5px auto;
                    }

                    th,
                    td {
                        border: 1px solid #ddd;
                        padding: 4px;
                        text-align: center;
                    }

                    th {
                        background-color: #f2f2f2;
                    }

                    td.bouton {
                        background-color: #ffcccb;
                    }
                </style>

                <!-- Your recalculating script -->
                {{--  --}}

                <div class="table-responsive mb-4">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th class="ml-5">Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th>Classe</th>
                                <th>Sexe</th>
                                <th>Red.</th>
                                <th>Date nai</th>
                                <th>Lieunais</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eleves as $eleve)
                                <tr>
                                    <td>{{ $eleve->MATRICULE }}</td>
                                    <td>{{ $eleve->NOM }} <br>{{ $eleve->PRENOM }}</td>
                                    <td>{{ $eleve->CODECLAS }}</td>
                                    <td>
                                        @if ($eleve->SEXE == 1)
                                            Masculin
                                        @elseif($eleve->SEXE == 2)
                                            Féminin
                                        @else
                                            Non spécifié
                                        @endif
                                    </td>
                                    <td class="checkboxes-select" style="width: 24px;">
                                        <input type="checkbox" class="form-check-input-center"
                                            {{ $eleve->STATUT ? 'checked' : '' }}>
                                    </td>
                                    @php
                                        $dateNaissance = $eleve->DATENAIS;

                                        // Convertir et formater la date au format d-m-Y
                                        $dateFormatted = \Carbon\Carbon::parse($dateNaissance)->format('d-m-Y');
                                    @endphp

                                    <td>{{ $dateFormatted }}</td>
                                    <td>{{ $eleve->LIEUNAIS }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary p-2 btn-sm btn-icon-text mr-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $eleve->MATRICULE }}">
                                                <i class="typcn typcn-eye btn-icon-append"></i>
                                            </button>
                                            <button class="btn btn-primary p-2 btn-sm dropdown" type="button"
                                                id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="typcn typcn-th-list btn-icon-append"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $eleve->MATRICULE }}">
                                                        Supprimer
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="/modifiereleve/{{ $eleve->MATRICULE }}">Modifier</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ url('/paiementeleve') }}">Paiement</a></li>
                                                <li><a class="dropdown-item" href="{{ url('/majpaiementeleve') }}">Maj
                                                        Paie</a></li>
                                                <li><a class="dropdown-item" href="{{ url('/profil') }}">Profil</a></li>
                                                <li><a class="dropdown-item" href="{{ url('/echeancier') }}">Echéance</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#">Cursus</a></li>
                                            </ul>
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


    @foreach ($eleves as $eleve)
        <!-- Modal de suppression -->
        <div class="modal fade" id="deleteModal{{ $eleve->MATRICULE }}" tabindex="-1"
            aria-labelledby="deleteModalLabel{{ $eleve->MATRICULE }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $eleve->MATRICULE }}">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer l'élève {{ $eleve->NOM }} {{ $eleve->PRENOM }} ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form action="{{ route('eleves.destroy', $eleve->MATRICULE) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal{{ $eleve->MATRICULE }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
                <div class="modal-dialog modal-lg" style="max-width: 1100px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informations pour {{ $eleve->NOM }} {{ $eleve->PRENOM }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab{{ $eleve->MATRICULE }}" role="tablist" style="font-size: 14px;">
                        @foreach (['Infor' => 'Informations générales', 'Detail' => 'Détail des notes', 'Deta' => 'Détails des paiements', 'finan' => 'Informations financières', 'Emploi' => 'Emploi du temps', 'Position' => 'Position Enseignants'] as $key => $label)
                            <button class="nav-link{{ $loop->first ? ' active' : '' }}" id="nav-{{ $key }}-tab{{ $eleve->MATRICULE }}"
                                data-bs-toggle="tab" data-bs-target="#nav-{{ $key }}{{ $eleve->MATRICULE }}"
                                type="button" role="tab" aria-controls="nav-{{ $key }}{{ $eleve->MATRICULE }}"
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
                                            <input type="date" class="form-control" id="dateN{{ $eleve->MATRICULE }}"
                                                name="dateN" value="{{ $eleve->DATENAIS }}" readonly>
                                        </div>
                                        <label for="lieu{{ $eleve->MATRICULE }}"
                                            class="col-sm-2 col-form-label">Lieu</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="lieu{{ $eleve->MATRICULE }}"
                                                name="lieu" value="{{ $eleve->LIEUNAIS }}" readonly>
                                        </div>
                                    </div>
                                    <!-- Sexe et Types élèves -->
                                    <div class="form-group row mt-2">
                                        <label for="sexe{{ $eleve->MATRICULE }}"
                                            class="col-sm-2 col-form-label">Sexe</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="sexe{{ $eleve->MATRICULE }}"
                                                name="sexe"
                                                value="{{ $eleve->SEXE == 1 ? 'Masculin' : ($eleve->SEXE == 2 ? 'Féminin' : '') }}" readonly>
                                        </div>
                                        <label for="typeEleve{{ $eleve->MATRICULE }}"
                                            class="col-sm-2 col-form-label">Type Élève</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"
                                                id="typeEleve{{ $eleve->MATRICULE }}" name="typeEleve"
                                                value="{{ $eleve->STATUTG == 1 ? 'Nouveau' : ($eleve->STATUTG == 2 ? 'Ancien' : '') }}" readonly>
                                        </div>
                                    </div>
                                    <!-- Date d'inscription, Apte et Statut Redoublant -->
                                    <div class="form-group row mt-2">
                                        <label for="dateIn{{ $eleve->MATRICULE }}" class="col-sm-4 col-form-label">Date
                                            d'inscription</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control"
                                                id="dateIn{{ $eleve->MATRICULE }}" name="dateIn"
                                                value="{{ $eleve->DATEINS }}" readonly>
                                        </div>
                                        <label for="apte{{ $eleve->MATRICULE }}"
                                            class="col-sm-2 col-form-label">Apte</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="apte{{ $eleve->MATRICULE }}"
                                                name="sexe"
                                                value="{{ $eleve->APTE == 0 ? 'Non' : ($eleve->APTE == 1 ? 'Oui' : '') }}" readonly>
                                        </div>
                                        <div class="col-sm-2 form-check">
                                            <input type="checkbox" class="form-check-input"
                                                id="statutRedoublant{{ $eleve->MATRICULE }}" 
                                                name="statutRedoublant"
                                                {{ $eleve->STATUT == 1 ? 'checked' : '' }} readonly>
                                            <label class="form-check-label" for="statutRedoublant{{ $eleve->MATRICULE }}">Statut Redoublant</label>        
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
                </div>
            </div>
        </div>
        
        <!-- Scripts JavaScript à placer à la fin du body pour optimiser le chargement -->
        <script>
            // JavaScript pour afficher le jour de la semaine et l'heure actuelle
            function afficherDateHeure() {
                // Récupérer la date et l'heure actuelles
                let date = new Date();

                // Obtenir le jour de la semaine
                let joursSemaine = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
                let jour = joursSemaine[date.getDay()];

                // Formater l'heure
                let heures = date.getHours();
                let minutes = date.getMinutes();
                let secondes = date.getSeconds();
                let heureFormatee =
                    `${heures.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secondes.toString().padStart(2, '0')}`;

                // Créer une chaîne de texte avec jour et heure
                let texteAffichage = `Aujourd'hui, c'est ${jour}. Il est actuellement ${heureFormatee}.`;

                // Afficher le texte dans l'élément HTML correspondant
                document.getElementById("dateTime").textContent = texteAffichage;
            }

            // Appeler la fonction pour l'exécuter initialement
            afficherDateHeure();

            // Actualiser l'affichage de l'heure chaque seconde
            setInterval(afficherDateHeure, 1000);
        </script>
    @endforeach


    <script>
        document.getElementById('recalculer').addEventListener('click', function() {
            let total = Math.floor(Math.random() * 1000);
            let filles = Math.floor(Math.random() * 100);
            let garcons = total - filles;
            let totalRed = Math.floor(Math.random() * 20);
            let fillesRed = Math.floor(Math.random() * 5);
            let garconsRed = totalRed - fillesRed;

            document.getElementById('total').textContent = total;
            document.getElementById('filles').textContent = filles;
            document.getElementById('garcons').textContent = garcons;
            document.getElementById('total-red').textContent = totalRed;
            document.getElementById('filles-red').textContent = fillesRed;
            document.getElementById('garcons-red').textContent = garconsRed;
        });
    </script>

    <script>
        function imprimerPage() {
            var table = document.getElementById('myTable');
            table.classList.remove('dataTable');

            // Masque les colonnes avec la classe hide-on-print
            var columns = table.querySelectorAll('.hide-on-print');
            columns.forEach(function(column) {
                column.style.display = 'none';
            });

            var page = window.open('', '_blank');
            page.document.write('<html><head><title>Liste des eleves</title>');
            page.document.write(
                '<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />');
            page.document.write(
                '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >'
            );
            // page.document.write('<style>@media print { .dt-end { display: none !important; } }</style>');
            // page.document.write('<style>@media print { .dt-start { display: none !important; } }</style>');
            page.document.write(
                '<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; } .cell-classe { background-color: #f8f9fa; } .cell-eleve { background-color: #e9ecef; } .cell-montant { background-color: #dee2e6; } .cell-mois { background-color: #ced4da; } .cell-date { background-color: #adb5bd; } .cell-reference { background-color: #6c757d; } .cell-action { background-color: #343a40; color: #fff; } tbody tr:nth-child(even) { background-color: #f1f3f5; } tbody tr:nth-child(odd) { background-color: #ffffff; } </style>'
            );
            page.document.write('</head><body>');
            page.document.write('<div>' + document.getElementById('contenu').innerHTML + '</div>');
            page.document.write('</body></html>');
            page.document.close();
            page.onload = function() {
                page.print();
                page.close();
            };

            // Restaure les colonnes après l'impression
            columns.forEach(function(column) {
                column.style.display = '';
            });
        }
    </script>
@endsection
