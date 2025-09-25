@extends('layouts.master')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
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
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br>
                <br>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h4 class="card-title mt-2">Mise à jour Personnel</h4>
                        <a class="btn btn-primary btn-sm" href="{{ url('/inscrirepersonnel') }}">
                            <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau agent
                        </a>
                    </div>
                </div>
                <br>
                <div id="contenu">
                    <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                        <table id="myTab" class="table table-bordered table-hover table-striped align-middle text-center">
                            <thead class="table-primary sticky-top">
                                <tr>
                                    <th class="ml-6">Matricule</th>
                                    <th>Poste</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Typeagent</th>
                                    <th>Quota</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agents as $agent)
                                <tr>
                                    <td>{{ $agent->MATRICULE }}</td>
                                    <td>{{ $agent->POSTE }}</td>
                                    <td>{{ $agent->NOM }}</td>
                                    <td>{{ $agent->PRENOM }}</td>
                                    <td>{{ $agent->LibelTypeAgent }}</td>
                                    <td></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-primary p-2 btn-sm dropdown" type="button"
                                                id="dropdownMenuSizeButton3" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="typcn typcn-th-list btn-icon-append"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                                                <li>
                                                    <button class="dropdown-item delete-eleve"
                                                        data-matricule="{{ $agent->MATRICULE }}"
                                                        data-nom="{{ $agent->NOM }}"
                                                        data-prenom="{{ $agent->PRENOM }}" 
                                                        data-poste="{{ $agent->POSTE }}"
                                                        data-LibelTypeAgent="{{ $agent->LibelTypeAgent }}"
                                                        data-quota="0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal">
                                                        Supprimer
                                                    </button>
                                                </li>
                                                <li><a class="dropdown-item" href="/modifieragent/{{ $agent->MATRICULE }}">Modifier</a></li>
                                                <li><a class="dropdown-item" href="">Position</a></li>
                                                <li><a class="dropdown-item" href="">Liste des classes</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="noResultsMessage" class="alert alert-success btn-primary my-2" style="display: none;">
                            Aucun agent trouvé.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Matières enseignées</label>
                    <table class="table table-bordered table-hover align-middle text-center shadow-sm" id="matiereTable">
                        <thead class="table-primary">
                            <tr>
                                <th>Code</th>
                                <th>Nom Court</th>
                                <th>Libellé Matière</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><button type="button" class="btn btn-sm btn-success">Modifier</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
@endsection