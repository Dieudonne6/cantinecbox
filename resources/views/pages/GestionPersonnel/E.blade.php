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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>

                <script>
                    // Optionnel : fermer automatiquement après 3 secondes
                    setTimeout(() => {
                        const alert = document.querySelector('.alert');
                        if(alert) alert.remove();
                    }, 3000);
                </script>
            @endif
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
                                                <li><a class="dropdown-item" href="{{ url('/inscrirepersonnel/'.$agent->MATRICULE) }}">Modifier</a></li>
                                                <li>
                                                    <button class="dropdown-item"
                                                        data-matricule="{{ $agent->MATRICULE }}" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#disponibiliteModal">
                                                        Disponibilité
                                                    </button>
                                                </li>                                              
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#positionModal">
                                                        Position
                                                    </button>
                                                </li>
                                                <li><a class="dropdown-item" href="">Imprimer Emploi</a></li>
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
                              {{--  <th>Action</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                {{--<td><button type="button" class="btn btn-sm btn-success">Modifier</button></td>--}}
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Supprimer l'agent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la Disponibilité -->
    <div class="modal fade" id="disponibiliteModal" tabindex="-1" aria-labelledby="disponibiliteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title fw-bold" style="color:white;">Disponibilité des profs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formDispo" method="POST" action="{{ route('dispo.storeDispo') }}">
                    @csrf
                    <input type="hidden" name="MATRICULE" id="matricule_agent">

                    <div class="row text-center">
                        @foreach (['LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI'] as $index => $jour)
                            <div class="col-md-2 border p-2">
                                <h6 class="bg-white text-dark fw-bold p-1 rounded">{{ $jour }}</h6>
                                <button type="button" class="btn btn-secondary btn-sm mb-2 w-100">TOUS</button>
                                @for ($h = 7; $h <= 19; $h++)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                            type="checkbox" 
                                            name="dispo[{{ $index+1 }}][]" 
                                            value="{{ $h }}">
                                        <label class="form-check-label">{{ $h }} H</label>
                                    </div>
                                @endfor
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <!-- Modale pour position -->
    <div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Position du professeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>
                <div class="row mb-3">
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Nom</label>
                    <input type="text" class="form-control" value="">
                    </div>
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Prénom</label>
                    <input type="text" class="form-control" value="">
                    </div>
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Sexe</label>
                    <select class="form-select">
                        <option>Masculin</option>
                        <option>Féminin</option>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Cycles</label>
                    <select class="form-select">
                        <option>Cycle 1</option>
                        <option>Cycle 2</option>
                    </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Jour</label>
                    <select class="form-select">
                        <option>Lun</option><option>Mar</option><option>Mer</option>
                        <option>Jeu</option><option>Ven</option><option>Sam</option>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <label class="form-label fw-bold">Heure</label>
                    <select class="form-select">
                        @for ($h = 7; $h <= 19; $h++)
                        <option>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}H</option>
                        @endfor
                    </select>
                    </div>
                    <div class="col-md-2">
                    <label class="form-label fw-bold">Durée</label>
                    <input type="number" class="form-control" min="1" value="1">
                    </div>
                </div>

                <table class="table table-bordered align-middle text-center"> 
                    <thead class="table-secondary">
                    <tr>
                        <th>Classes</th>
                        <th>Salles</th>
                        <th>Matière</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i = 0; $i < 5; $i++)
                    <tr>
                        <td><input type="text" class="form-control"></td>
                        <td><input type="text" class="form-control"></td>
                        <td><input type="text" class="form-control"></td>
                    </tr>
                    @endfor
                    </tbody>
                </table>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">VALIDER</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //script pour cocher automatiquement toutes les cases pour la disponibilité
        document.querySelectorAll('.btn-secondary').forEach(btn => {
            btn.addEventListener('click', () => {
                const parent = btn.closest('.col-md-2');
                const checkboxes = parent.querySelectorAll('input[type=checkbox]');
                const allChecked = Array.from(checkboxes).every(c => c.checked);

                checkboxes.forEach(c => c.checked = !allChecked);
            });
        });

        $(document).on('click', '[data-bs-target="#disponibiliteModal"]', function() {
            let matricule = $(this).data('matricule');
            $('#matricule_agent').val(matricule);
        });

</script>

   
@endsection