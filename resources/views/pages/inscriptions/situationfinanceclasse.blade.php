@extends('layouts.master')
@section('content')
    <div class="main-panel-10">
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
                    <h4 class="card-title">Situation financiere selon echeancier</h4>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="" style="margin-top: 2px">Classe</label>
                            <select class="js-example-basic-multiple w-100" multiple="multiple" name="classeCode[]">
                                <option value="">Sélectionnez une classe</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mt-4">
                            <button class="btn-sm btn-primary" id="submitBtnSFE">Appliquer la sélection</button>
                        </div>

                    </div>

                </div>



                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f8f9fa;
                    }

                    .table-container {
                        width: 100%;
                        overflow-x: auto;
                        margin: 20px auto;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        min-width: 800px;
                        background-color: #fff;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    th,
                    td {
                        padding: 10px 15px;
                        border: 1px solid #ddd;
                        /* text-align: left; */
                        text-align: center;
                    }

                    th {
                        background-color: #f4f4f4;
                        font-weight: bold;
                    }

                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }

                    .photo {
                        width: 50px;
                        height: auto;
                        border-radius: 50%;
                    }

                    @media (max-width: 768px) {

                        th,
                        td {
                            padding: 8px 10px;
                        }

                        .photo {
                            width: 40px;
                        }
                    }

                    @media (max-width: 480px) {
                        table {
                            display: block;
                            overflow-x: auto;
                        }

                        th,
                        td {
                            font-size: 12px;
                            padding: 5px;
                        }

                        .photo {
                            width: 30px;
                        }
                    }
                </style>

                <div class="table-container">
                    
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nom et Prénom</th>
                                <th>Classe</th>
                                <th>Reste Echeance</th>
                                <th>Reste Arriere</th>
                                <th>Reste Autres Frais</th>
                                <th>Decision</th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>

                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
