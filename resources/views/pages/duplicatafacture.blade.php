@extends('layouts.master')
@section('content')

<div class="card-8">

    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="page-title"><h4>Reçus de paiement</h4></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dt-search mt-3 mx-6">
                                <label for="dt-search-0">Rechercher&nbsp;:</label>
                                <input type="search" class="dt-input" id="dt-search-0" placeholder="" aria-controls="myTable">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Classe</th>
                                            <th>Eleve</th>
                                            <th>Date de paiement</th>
                                            <th>Reference</th>
                                            <th>Facture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CM1</td>
                                            <td>Parker</td>
                                            <td>ST-0d001</td>
                                            <td>20/1/2021</td>
                                            <td>
                                                <a href="edit-student.html" class="btn btn-primary btn-sm mb-1">
                                                    <i class="far">Télécharger</i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Repeat similar <tr> blocks for other students -->
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