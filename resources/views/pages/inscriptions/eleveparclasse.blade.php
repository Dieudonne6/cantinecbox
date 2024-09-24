@extends('layouts.master')
@section('content')
    <div class="main-panel-10">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Liste des élèves par classe</h4>
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
                            <button class="btn-sm btn-primary" id="submitBtn1">Appliquer la sélection</button>
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
                        text-align: left;
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
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                {{-- <th>Photo</th> --}}
                                <th>Date de Naissance</th>
                                <th>Lieu de Naissance</th>
                                <th>Sexe</th>
                                <th>Statut</th>
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
