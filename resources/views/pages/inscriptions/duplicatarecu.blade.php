@extends('layouts.master')

@section('content')

<div class="card">

    <div class="row ">
        <div class="col">
            <div class="card">
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