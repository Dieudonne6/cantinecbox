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
                            {{-- <div class="dt-search mt-3 mx-6">
                                <label for="dt-search-0">Rechercher&nbsp;:</label>
                                <input type="search" class="dt-input" id="dt-search-0" placeholder="" aria-controls="myTable">
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th> Type de Facture</th>
                                            <th>Classe</th>
                                            <th>Eleve</th>
                                            <th>Date de paiement</th>
                                            {{-- <th>Reference</th> --}}
                                            <th>Facture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($duplicatafactures as $duplicatafacture)

                                            <tr>
                                                <td>{{ $duplicatafacture['reference'] }}</td>
                                                <td>{{ $duplicatafacture['classe'] }}</td>
                                                <td>{{ $duplicatafacture['nomeleve'] }}</td>
                                                <td>{{ $duplicatafacture['datepaiement'] }}</td>
                                                {{-- <td>{{ $duplicatafacture['reference'] }}</td> --}}
                                                <td>
                                                    {{-- {{ public_path('qrcodes/' . $fileNameqrcode) }} --}}
                                                    <a href="{{ asset('pdfs/' .$duplicatafacture['url']) }}" class="btn btn-primary btn-sm mb-1" target="_blank">
                                                        <i class="far">Télécharger</i> 
                                                    </a>
                                                </td>
                                            </tr>
                       
                                        @endforeach
                               
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