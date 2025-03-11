@extends('layouts.master')
@section('content')
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
            <div class="nav nav-tabs" id="nav-tab{{ $elevea->MATRICULE }}" role="tablist" style="font-size: 16px;">
                @foreach ([
            'Infor' => 'Informations générales',
            'Detail' => 'Détail des notes',
            'finan' => 'Informations
                    Académiques',
            // 'Discipline' => 'Discipline',
        ] as $key => $label)
                    <button class="nav-link{{ $loop->first ? ' active' : '' }}"
                        id="nav-{{ $key }}-tab{{ $elevea->MATRICULE }}" data-bs-toggle="tab"
                        data-bs-target="#nav-{{ $key }}{{ $elevea->MATRICULE }}" type="button" role="tab"
                        aria-controls="nav-{{ $key }}{{ $elevea->MATRICULE }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button>
                @endforeach
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent{{ $elevea->MATRICULE }}">
            <!-- Informations générales -->
            <div class="tab-pane fade show active" id="nav-Infor{{ $elevea->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Infor-tab{{ $elevea->MATRICULE }}">
                <form class="accordion-body col-md-10 mx-auto">
                    <div class="form-group row mt-1">
                        <div class="conteneur">
                            <div class="cadre-photo">
                                <!-- Image dynamique de l'élève -->
                                <img src="{{ asset('images/eleveas/' . $elevea->PHOTO) }}" alt="Photo de l'élève">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="lieu" class="col-sm-4 col-form-label">Lieu</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="lieu" value="{{ $elevea->LIEUNAIS }}"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="dateN" class="col-sm-4 col-form-label">Date d'inscription</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control mt-2" id="dateN"
                                value="{{ \Carbon\Carbon::parse($elevea->DATEINS)->format('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="sexe" name="sexe"
                                value="{{ $elevea->SEXE == 1 ? 'Masculin' : ($elevea->SEXE == 2 ? 'Féminin' : '') }}"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-1 align-items-center">
                        <label for="apte" class="col-sm-2 col-form-label">Apte</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="apte" name="apte"
                                value="{{ $elevea->APTE == 0 ? 'Non' : 'Oui' }}" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Détails Notes -->
            <div class="tab-pane fade" id="nav-Detail{{ $elevea->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Detail-tab{{ $elevea->MATRICULE }}" tabindex="0">
                <form class="accordion-body col-md-12 mx-auto" style="background-color: #f0eff3;">
                    <div class="table-responsive mt-2">
                        <table id="myTab{{ $elevea->MATRICULE }}" class="table table-bordered table-striped">
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
                                @foreach ($delevea as $note)
                                    <tr>
                                        <td>{{ $note->ANSCOL }}</td>
                                        <td>{{ $note->CODECLAS }}</td>
                                        <td>{{ $note->MOYENNES }}</td>
                                        <td>{{ $note->RANGX }}</td>
                                        <td>{{ $note->STATUX }}</td>
                                        <td>{{ $note->OBSERVATION }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <!-- Informations Académiques -->
            <div class="tab-pane fade" id="nav-finan{{ $elevea->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-finan-tab{{ $elevea->MATRICULE }}" tabindex="0">
                <div class="accordion-body col-md-12 mx-auto">
                    <form class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3;">
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Année</th>
                                        <th>Moy. Trim1</th>
                                        <th>Rg1</th>
                                        <th>Moy. Trim2</th>
                                        <th>Rg2</th>
                                        <th>Moy. Trim3</th>
                                        <th>Rg3</th>
                                        <th>Moy. an</th>
                                        <th>Rang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleve_pont as $info)
                                        <tr>
                                            <td>{{ $info->anneeacademique }}</td>
                                            <td>{{ $info->MS1 }}</td>
                                            <td>{{ $info->RANG1 }}</td>
                                            <td>{{ $info->MS2 }}</td>
                                            <td>{{ $info->RANG2 }}</td>
                                            <td>{{ $info->MS3 }}</td>
                                            <td>{{ $info->RANG3 }}</td>
                                            <td>{{ $info->MAN }}</td>
                                            <td>{{ $info->RANGA }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

            </div>
            <!-- Discipline -->
            <div class="tab-pane fade" id="nav-Situation{{ $elevea->MATRICULE }}" role="tabpanel"
                aria-labelledby="nav-Situation-tab{{ $elevea->MATRICULE }}" tabindex="0">
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
                                {{-- @foreach ($elevea->discipline as $d)
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
@endsection
