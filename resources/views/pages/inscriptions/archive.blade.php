@extends('layouts.master')
@section('content')
    <style>
        .cadre-photo {
            border: 2px solid #ccc;
            padding: 10px;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .conteneur {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Modal custom styling for responsiveness */
        .modal-lg {
            max-width: 90%; /* Ensures modal is responsive */
        }
    </style>

    <div class="card row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card-body">
                <h4 class="card-title">Archives</h4>

                <table>
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Dern. Classe</th>
                            <th>Sexe</th>
                            <th>Datenais</th>
                            <th>Lieunais</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($elevea as $eleve)
                            <tr>
                                <td>{{ $eleve->MATRICULE }}</td>
                                <td>{{ $eleve->NOM }}</td>
                                <td>{{ $eleve->PRENOM }}</td>
                                <td>{{ $eleve->CLASSESOR }}</td>
                                <td data-sexe="{{ $eleve->SEXE }}">
                                    {{ $eleve->SEXE == 1 ? 'M' : ($eleve->SEXE == 2 ? 'F' : 'Non spécifié') }}
                                </td>
                                @php
                                    $dateNaissance = $eleve->DATENAIS;
                                    $dateFormatted = $dateNaissance
                                        ? \Carbon\Carbon::parse($dateNaissance)->format('d/m/Y')
                                        : '';
                                @endphp

                                <td>{{ $dateFormatted }}</td>
                                <td>{{ $eleve->LIEUNAIS }}</td>
                                <td>
                                    <a href="/pagedetailarchive/{{ $eleve->MATRICULE }}"
                                        class= "btn btn-primary p-2 btn-sm mr-2">Voir plus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for each student -->
    {{-- @foreach ($elevea as $eleve)
        <div class="modal fade" id="exampleModal{{ $eleve->MATRICULE }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $eleve->MATRICULE }}" aria-hidden="true">
            <div class="modal-dialog modal-lg"  style="max-width: 700px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size: 14px;">
                                <button class="nav-link active" id="nav-Infor-tab{{ $eleve->MATRICULE }}" data-bs-toggle="tab"
                                    data-bs-target="#nav-Infor{{ $eleve->MATRICULE }}" type="button" role="tab" aria-controls="nav-Infor{{ $eleve->MATRICULE }}"
                                    aria-selected="true">Informations générales</button>
                                <button class="nav-link" id="nav-Detail-tab{{ $eleve->MATRICULE }}" data-bs-toggle="tab"
                                    data-bs-target="#nav-Detail{{ $eleve->MATRICULE }}" type="button" role="tab" aria-controls="nav-Detail{{ $eleve->MATRICULE }}"
                                    aria-selected="false">Détail des notes</button>
                                <button class="nav-link" id="nav-Deta-tab{{ $eleve->MATRICULE }}" data-bs-toggle="tab"
                                    data-bs-target="#nav-Deta{{ $eleve->MATRICULE }}" type="button" role="tab" aria-controls="nav-Deta{{ $eleve->MATRICULE }}"
                                    aria-selected="false">Informations académiques</button>
                                <button class="nav-link" id="nav-Discipline-tab{{ $eleve->MATRICULE }}" data-bs-toggle="tab"
                                    data-bs-target="#nav-Discipline{{ $eleve->MATRICULE }}" type="button" role="tab"
                                    aria-controls="nav-Discipline{{ $eleve->MATRICULE }}" aria-selected="false">Discipline</button>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent{{ $eleve->MATRICULE }}">
                            <!-- Informations générales -->
                            <div class="tab-pane fade show active" id="nav-Infor{{ $eleve->MATRICULE }}" role="tabpanel"
                                aria-labelledby="nav-Infor-tab{{ $eleve->MATRICULE }}" tabindex="0">
                                <form class="accordion-body col-md-10 mx-auto" >
                                    <div class="form-group row mt-1">
                                        <div class="conteneur">
                                            <div class="cadre-photo">
                                                <!-- Image dynamique de l'élève -->
                                                <img src="{{ asset('images/eleves/' . $eleve->PHOTO) }}" alt="Photo de l'élève">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <label for="lieu" class="col-sm-4 col-form-label">Lieu</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="lieu" placeholder="Lieu" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-2">
                                        <label for="dateN" class="col-sm-4 col-form-label">Date d'inscription</label>
                                        <div class="col-sm-5">
                                            <input type="date" class="form-control mt-2" id="dateN" value="{{ $eleve->DATEINS }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1">
                                        <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="sexe" name="sexe"
                                            value="{{ $eleve->SEXE == 1 ? 'Masculin' : ($eleve->SEXE == 2 ? 'Féminin' : '') }}"
                                            readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1 align-items-center">
                                        <label for="apte" class="col-sm-2 col-form-label">Apte</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="apte" name="apte"
                                            value="{{ $eleve->APTE == 0 ? 'Non' : 'Oui' }}" readonly>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Détail des notes -->
                            <div class="tab-pane fade" id="nav-Detail{{ $eleve->MATRICULE }}" role="tabpanel" aria-labelledby="nav-Detail-tab{{ $eleve->MATRICULE }}"
                                tabindex="0">
                                <form class="accordion-body col-md-12 mx-auto" style="background-color: #f0eff3;">
                                    <div class="table-responsive mt-2">
                                        <table id="myTab{{ $eleve->MATRICULE }}" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Année</th>
                                                    <th>Classe</th>
                                                    <th>Moy. annuelle</th>
                                                    <th>Rang</th>
                                                    <th>Statut</th>
                                                    <th>Observation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($delevea as $note)
                                                    <tr>
                                                        <td>{{ $note->annee }}</td>
                                                        <td>{{ $note->classe }}</td>
                                                        <td>{{ $note->moyenne }}</td>
                                                        <td>{{ $note->rang }}</td>
                                                        <td>{{ $note->statut }}</td>
                                                        <td>
                                                            
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <!-- Informations académiques -->
                            <div class="tab-pane fade" id="nav-Deta{{ $eleve->MATRICULE }}" role="tabpanel" aria-labelledby="nav-Deta-tab{{ $eleve->MATRICULE }}"
                                tabindex="0">
                                <form class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3;">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Année</th>
                                                    <th>Classe</th>
                                                    <th>Promotion</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($eleve->promotions as $promotion)
                                                    <tr>
                                                        <td>{{ $promotion->annee }}</td>
                                                        <td>{{ $promotion->classe }}</td>
                                                        <td>{{ $promotion->promotion }}</td>
                                                        <td>{{ $promotion->statut }}</td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <!-- Discipline -->
                            <div class="tab-pane fade" id="nav-Discipline{{ $eleve->MATRICULE }}" role="tabpanel" aria-labelledby="nav-Discipline-tab{{ $eleve->MATRICULE }}"
                                tabindex="0">
                                <form class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3;">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Motif</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($eleve->discipline as $d)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }}</td>
                                                        <td>{{ $d->motif }}</td>
                                                        <td>
                                                            <a class="btn btn-danger" href="#">Supprimer</a>
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- @endforeach --}} 
@endsection
