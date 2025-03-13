@extends('layouts.master')

@section('content')

<div class="card">

    <div class="row ">
        <div class="col">
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
                    <h4 class="card-title">Duplicata reçu</h4>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-primary">Imprimer tout</button>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>N° reçu</th>
                                            <th>Date reçu</th>
                                            <th>Scolarité</th>
                                            <th>Arrièré</th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>10/08/2022</td>
                                                <td>90 000</td>
                                                <td>0</td>
                                                <td>20 000</td>
                                                <td>23 000</td>
                                                <td>2 000</td>
                                                <td>0</td>
                                                <td>
                                                    <a href="#" class="btn btn-secondary btn-sm mb-1">
                                                        <i class="far">Imprimer</i> 
                                                    </a>
                                                </td>
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

@endsection