@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gestion des groupes</h4>

                    <div class="form-group row"><br>
                        {{-- <p>Ajout d'un nouveau groupe</p> --}}
                        <label for="exampleInputUsername2" class="col-sm-2 col-form-label">Nouveau groupe</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Nom groupe">
                        </div>
                        <div class="col-sm-3">
                            {{-- <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle"> --}}
                            <button type="button" class="btn btn-primary">Ajouter</button>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-8" style="text-align: center;">

                            <div class="table-responsive pt-3">
    
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Groupe</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                        <tr>
                                            <td>Ens. General</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">Supprimer</button>
                                            </td>
                                        </tr>
    
                                        <tr>
                                            <td>Ens. Maternel</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">Supprimer</button>
                                            </td>
                                        </tr>
    
                                        <tr>
                                            <td>Ens. Primaire</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">Supprimer</button>
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
@endsection
