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
                <h4 class="card-title mt-2">Mise à jour Presonnel</h4>
                <div class="row gy-6">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <a class="btn btn-primary btn-sm" href="{{url('/inscrirepersonnel')}}">
                                <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau
                            </a>
                        </div>
                    </div>                    
                </div>
                <br>
                <div id="contenu">
                    <div class="table-responsive mb-4">
                        <table id="myTab" class="table table-bordered table-hover table-striped align-middle text-center">
                            <thead class="table-primary sticky-top" >
                                <tr>
                                    <th class="ml-6">Matricule</th>
                                    <th>Signe</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Typeagent</th>
                                    <th>Quota</th>
                                    <th class="hide-printe">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                              
                            </tbody>
                        </table>
                        <div id="noResultsMessage" class="alert alert-success btn-primary my-2" style="display: none;">
                            Aucun résultat trouvé.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
@endsection